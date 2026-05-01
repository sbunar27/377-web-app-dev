<?php
// USING THE NUMBER OF EVENTS TO FIGURE OUT HEATMAP COLORS:
$connection = get_connection();

// Add "WHERE ev_completed = 0" so completed events are ignored by the count
$countSql = "SELECT ev_date, COUNT(*) as total 
             FROM events 
             WHERE ev_completed = 0 
             GROUP BY ev_date";

$countResult = $connection->query($countSql);
$eventCounts = [];

while ($row = $countResult->fetch_assoc()) {
    $eventCounts[$row['ev_date']] = (int)$row['total'];
}
$connection->close();

// Pass data to JavaScript using JSON for use in the calendar rendering
echo "<script>var eventHeatmap = " . json_encode($eventCounts) . ";</script>";
?>


<table>
  <td>
    <h2>Agenda</h2>
    <!-- CALENDAR -->
    <div id="calendar">
      <div id="calendar-header">
        <button id="prev-month">&lt;</button>
        <span id="month-year"></span>
        <button id="next-month">&gt;</button>
      </div>
      <div id="calendar-days">
        <div>Sun</div>
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
      </div>
      <div id="calendar-dates"></div>
    </div>
  </td>
  <td>
    <div id="to-do">
      <h3>To-Do List</h3>
      <input type="text" id="todo-input" placeholder="Add to-do item" />
      <button id="add-todo-btn">Add</button>
      <ul id="todo-list">
        <?php
          $connection = get_connection();
          if ($connection->connect_error) {
              echo 'DB connection failed';
              exit;
          }

          $sql = "SELECT todo_id, todo_task FROM todos WHERE todo_date = CURDATE()";
          $result = $connection->query($sql);

          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  echo '<li data-id="' . $row["todo_id"] . '">' . htmlspecialchars($row["todo_task"]) . '</li>';
              }
          }
          $connection->close();
         ?>
      </ul>
    </div>
    <div id="dog">
      <table>
        <td style="width: 120px; height: 120px;">
          <img id="bubble" src="bubble.gif" alt="Thought Bubble" style="width: 100px; display: block; margin-left: auto; margin-right: auto;">
          <p id="dog-feedback" class="overlay-text"></p>
        </td>
        <td style="width: 200px; height: 120px; position: relative;">
          <img id="dog-image" src="dog-sprites/neutral.png" alt="Dog Image" style="width: 100%; max-width: 200px; display: block; margin: 0 auto;">
        </td>
      </table>
    </div>
    <strong><a class="button centered-button" href="https://www.cdc.gov/mental-health/living-with/index.html">CDC WEBSITE</a></strong>
    <div id="event-section" style="margin-top: 20px;">
      <input type="text" id="event-title" placeholder="Event title" />
      <textarea id="event-desc" placeholder="Event description"></textarea>
      <input type="time" id="event-time" />
      <button id="add-event-btn" class="button">Add Event</button>
    </div>
    <br><hr><br>
    <h3>Events</h3>
    <br>
    <table id="eventTable" class="stripe hover dataTables"></table>
    <br><br>
  </td>
</table>

<?php
// Use selected date from calendar (passed as ?date=YYYY-MM-DD), otherwise use today
$selected_date = null;
if (isset($_GET['date'])) {
  $selected_date = $_GET['date'];
}

$connection = get_connection();
if ($connection->connect_error) {
    echo 'DB connection failed';
    exit;
}

if ($selected_date) {
  $dateEsc = $connection->real_escape_string($selected_date);
  $sql = "SELECT *, date_format(ev_date, '%m/%d/%Y') as 'formatted_date'
      FROM events
      WHERE ev_date = '$dateEsc'
      ORDER BY ev_title ASC";
} else {
  $sql = "SELECT *, date_format(ev_date, '%m/%d/%Y') as 'formatted_date'
      FROM events
      WHERE ev_date = CURDATE()
      ORDER BY ev_title ASC";
}

// Execute query and collect rows with error checking
$rows = [];
$result = $connection->query($sql);
if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $result->free();
} else {
    // Log the error for debugging
    error_log('DB query failed: ' . $connection->error);
}

$connection->close();

print('<script>');
print('var data = ' . json_encode($rows, JSON_PARTIAL_OUTPUT_ON_ERROR) . ';');
print('</script>');
?>

<script>
  // 1. SETUP
  const calendarDates = document.getElementById('calendar-dates');
  const monthYear = document.getElementById('month-year');
  const prevMonthButton = document.getElementById('prev-month');
  const nextMonthButton = document.getElementById('next-month');

  const urlParams = new URLSearchParams(window.location.search);
  let selectedDate = urlParams.get('date');
  // If no date is selected, default to today for the dog's logic
  if (!selectedDate) {
    const today = new Date();
    selectedDate = today.getFullYear() + '-' + (today.getMonth() + 1).toString().padStart(2, '0') + '-' + today.getDate().toString().padStart(2, '0');
  }
  let currentDate = new Date(selectedDate + 'T12:00:00');

  // DataTable Initialization
  var dataTable = $('#eventTable').DataTable({
    data: data,
    columns: [
      { title: "Event", data: "ev_title", render: function(data, type, row) {
          return type === 'display' ? '<a href="detail.php?id=' + row.ev_id + '">' + data + '</a>' : data;
      }},
      { title: "Event Date", data: "formatted_date" },
      { title: "Event Time", data: "ev_time" },
      { title: "Description", data: "ev_desc" }
    ]
  });

  // Time converters
  const toMins = (t) => {
    if(!t) return 0;
    const [h, m] = t.split(':').map(Number);
    return h * 60 + m;
  };

  const toStr = (m) => {
    const h = Math.floor(m / 60);
    const mins = m % 60;
    return `${h % 12 || 12}:${mins.toString().padStart(2, '0')} ${h >= 12 ? 'PM' : 'AM'}`;
  };

  // 2. CONFLICT & SUGGESTION LOGIC
  function getConflictDetails(events) {
    if (!events || events.length < 2) return null;
    const sorted = [...events].sort((a, b) => toMins(a.ev_time) - toMins(b.ev_time));

    for (let i = 0; i < sorted.length - 1; i++) {
      const current = sorted[i];
      const next = sorted[i + 1];
      // Assuming 60 min duration
      if (toMins(current.ev_time) + 60 > toMins(next.ev_time)) {
        return { event1: current.ev_title, event2: next.ev_title, time2: next.ev_time };
      }
    }
    return null;
  }

  // Suggests the next available 1-hour slot between 9am and 5pm that doesn't conflict with existing events (Gemini help with map)
  function findSuggestedGap(events) {
    const busySlots = events.map(e => ({ start: toMins(e.ev_time), end: toMins(e.ev_time) + 60 }));
    for (let time = 540; time <= 1020; time += 30) { // 9am to 5pm
      const isFree = !busySlots.some(slot => (time < slot.end && time + 60 > slot.start));
      if (isFree) return toStr(time);
    }
    return "later tonight";
  }

  // 3. THE DOG
  let typewriterTimeout;

  function dogSpeak(text) {
    const feedback = document.getElementById('dog-feedback');
    const dogImg = document.querySelector('#dog-image');
    
    clearTimeout(typewriterTimeout);
    feedback.innerHTML = '';
    feedback.style.visibility = 'visible';
    dogImg.src = 'dog-sprites/talk.gif';

    let i = 0;
    function type() {
      if (i < text.length) {
        // CHECK IF CURRENT CHARACTER IS START OF AN HTML TAG
        if (text.charAt(i) === '<') {
          // Find the end of the tag
          let tagEnd = text.indexOf('>', i);
          if (tagEnd !== -1) {
            // Add the full tag at once so it doesn't "break" the HTML
            feedback.innerHTML += text.substring(i, tagEnd + 1);
            i = tagEnd + 1;
          }
        } else {
          feedback.innerHTML += text.charAt(i);
          i++;
        }
        typewriterTimeout = setTimeout(type, 40);
      } else {
        setTimeout(() => { 
          if(dogImg.src.includes('talk.gif')) dogImg.src = 'dog-sprites/neutral.png'; 
        }, 2000);
      }
    }
    type();
  }

  function updateDogImage() {
    const dogImg = document.querySelector('#dog-image');
    const feedback = document.getElementById('dog-feedback');
    const bubble = document.querySelector('#bubble');
    
    const count = eventHeatmap[selectedDate] || 0;
    const conflict = getConflictDetails(data); 

    feedback.style.visibility = 'hidden';
    bubble.style.visibility = 'visible';

    let message = "";
    let sprite = 'neutral.png';

    if (conflict) {
      sprite = 'think.png';
      const suggestion = findSuggestedGap(data);
      message = `Bark! "${conflict.event1}" and "${conflict.event2}" overlap. Maybe move "${conflict.event2}" to ${suggestion}?`;
    } else if (count > 1) {
      sprite = 'sleepy.png';
      // message = "Woah, that's a busy day! Remember to take a break. Visit <a href='https://www.cdc.gov/mental-health/living-with/index.html' target='_blank'>this site</a> for tips on managing stress.";
      message = "Woah, that's a busy day! Remember to take a break. Visit the resource below for tips on managing stress.";
    } else if (count > 0) {
      sprite = 'bleh.png';
      message = "Not too bad! You've got this.";
    } else {
      sprite = 'neutral.png';
      message = "All quiet on the western front! Want to add some plans?";
    }

    dogImg.src = 'dog-sprites/' + sprite;
    bubble.onclick = function() {
      bubble.style.visibility = 'hidden';
      dogSpeak(message);
    };
  }

  // 4. CALENDAR & AJAX 
  function renderCalendar(date) {
    calendarDates.innerHTML = '';
    const year = date.getFullYear();
    const month = date.getMonth();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    monthYear.textContent = monthNames[month] + " " + year;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
      const emptyCell = document.createElement('div');
      emptyCell.className = 'calendar-cell empty';
      calendarDates.appendChild(emptyCell);
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const cell = document.createElement('div');
      cell.className = 'calendar-cell';
      cell.textContent = day;
      const cellDate = year + '-' + (month + 1).toString().padStart(2, '0') + '-' + day.toString().padStart(2, '0');

      // HEATMAP LOGIC
      const count = eventHeatmap[cellDate] || 0;
      if (count > 0) {
        cell.style.backgroundColor = count > 5 ? '#5f7b5eff' : count > 3 ? '#779976' : '#c3d2c2ff';
        if(count > 5) cell.style.color = 'white';
      }

      cell.onclick = () => window.location.href = 'index.php?nav=agenda&date=' + cellDate;
      if (selectedDate === cellDate) {
        cell.style.border = "3px solid #588157";
        cell.style.fontWeight = "bold";
      }
      calendarDates.appendChild(cell);
    }
  }

  document.getElementById('add-event-btn').onclick = function() {
    const title = document.getElementById('event-title').value.trim();
    const time = document.getElementById('event-time').value.trim();
    const desc = document.getElementById('event-desc').value.trim();

    if (!selectedDate || !title) return alert('Select a date and enter a title!');

    $.ajax({
      url: 'events.php',
      method: 'POST',
      data: { action: 'add', ev_date: selectedDate, ev_time: time, ev_title: title, ev_desc: desc },
      success: function(response) {
        if (response.startsWith('success')) {
          // Update local data so dog reacts immediately
          const newEv = { ev_title: title, ev_time: time, ev_desc: desc, formatted_date: selectedDate };
          data.push(newEv);
          
          eventHeatmap[selectedDate] = (eventHeatmap[selectedDate] || 0) + 1;
          
          // Refresh UI
          dataTable.clear().rows.add(data).draw();
          renderCalendar(currentDate);
          updateDogImage();
          
          document.getElementById('event-title').value = '';
          document.getElementById('event-time').value = '';
        }
      }
    });
  };

  // Buttons
  prevMonthButton.onclick = () => { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(currentDate); };
  nextMonthButton.onclick = () => { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(currentDate); };

  // Init
  renderCalendar(currentDate);
  updateDogImage();
</script>