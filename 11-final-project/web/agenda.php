 
<?php

// agenda.php: displays the agenda for the user, including a calendar and to-do list.
//  It retrieves event data from the database and passes it to JavaScript for rendering the calendar and events.
//  The page includes logic to determine if there are any scheduling conflicts and provides suggestions for resolving them.

// USING THE NUMBER OF EVENTS TO FIGURE OUT HEATMAP COLORS:
$connection = get_connection();

// Add "WHERE ev_completed = 0" so completed events are ignored by the count
$countSql = <<<SQL
SELECT ev_date, COUNT(*) as total 
FROM events 
WHERE ev_completed = 0 
GROUP BY ev_date
SQL;

$countResult = $connection->query($countSql);
$eventCounts = [];

while ($row = $countResult->fetch_assoc()) {
    $eventCounts[$row['ev_date']] = (int)$row['total'];
}
$connection->close();

// Pass data to JavaScript using JSON for use in the calendar rendering
echo "<script>var eventHeatmap = " . json_encode($eventCounts) . ";</script>";
?>

<div class="content">
  <table>
    <td>
      <h2>Agenda</h2>
      <!-- CALENDAR -->
      <button id="show-completed-btn">Completed Events</button>
      <br><br>
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
            $todo_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

            $connection = get_connection();
            if ($connection->connect_error) {
                echo 'DB connection failed';
                exit;
            }

            $todoDate = $connection->real_escape_string($todo_date);
            // look for 0 OR NULL
            $sql = <<<SQL
            SELECT todo_id, todo_task FROM todos WHERE todo_date = '$todoDate' AND (todo_completed = 0 OR todo_completed IS NULL)
            SQL;
            $result = $connection->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<li data-id="' . $row["todo_id"] . '">' . htmlspecialchars($row["todo_task"]) . '</li>';
                }
            }
            $connection->close();
          ?>
        </ul>
      </div>
      <div id="event-section">
        <input type="text" id="event-title" placeholder="Event title" />
        <textarea id="event-desc" placeholder="Event description"></textarea>
        <input type="time" id="event-time" />
        <select id="event-cat">
          <option value="">Select category</option>
          <option value="Work">Work</option>
          <option value="Personal">Personal</option>
          <option value="Health">Health</option>
          <option value="School">School</option>
          <option value="Other">Other</option>
        </select>
        <button id="add-event-btn" class="button">Add Event</button>
        <br><br>
      </div>
      <div id="event-error" class="error-message"></div>
      <br><hr><br>
      <h3>Events for <?php echo htmlspecialchars(isset($_GET['date']) ? $_GET['date'] : date('Y-m-d')); ?></h3>
      <br>
      <table id="eventTable" class="stripe hover dataTables"></table>
      <br><br>
      <div id="dog">
        <table>
          <td class="dog-bubble">
            <img id="bubble" src="bubble.gif" alt="Thought Bubble">
            <p id="dog-feedback" class="overlay-text"></p>
            <strong><a class="button centered-button" href="https://www.cdc.gov/mental-health/living-with/index.html">CDC WEBSITE</a></strong>
          </td>
          <td>
            <img id="dog-image" src="dog-sprites/neutral.png" alt="Dog Image">
          </td>
        </table>
      </div>
    </td>
  </table>
</div>

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
  $sql = <<<SQL
  SELECT *, date_format(ev_date, '%m/%d/%Y') as 'formatted_date'
  FROM events
  WHERE ev_date = '$dateEsc'
  ORDER BY ev_title ASC
  SQL;
} else {
  $sql = <<<SQL
  SELECT *, date_format(ev_date, '%m/%d/%Y') as 'formatted_date'
  FROM events
  WHERE ev_date = CURDATE()
  ORDER BY ev_title ASC
  SQL;
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
          return type === 'display' ? '<a href="index.php?nav=detail&id=' + row.ev_id + '">' + data + '</a>' : data;
      }},
      { title: "Event Time", data: "ev_time" },
      { title: "Category", data: "ev_cat" },
      { title: "Description", data: "ev_desc" }
    ]
  });

  $('#eventTable').css('background-color', '#7fa07eff');
  $('#eventTable').css('color', '#e6e3db');

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
    // spread operator takes all the items out of events array and puts them into a new array, which can then sort without affecting the original data
    // converts both times into total minutes from midnight and subtracts event b's time from event a's time. 
    // If the result is negative, a is earlier; if positive, b is earlier; if zero, they are at the same time.
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

  // Suggests the next available 1-hour slot between 5am and 10pm that doesn't conflict with existing events (Gemini help with map)
  function findSuggestedGap(events) {
    // converts the event's start time into total minutes from midnight using 'toMins()'
    // and calculates the end time by adding 60 minutes (assuming each event is 1 hour long).
    const busySlots = events.map(e => ({ start: toMins(e.ev_time), end: toMins(e.ev_time) + 60 }));
    
    // loop through the workday in 30-minute increments.
    // 300 represents minutes from midnight (5:00 AM) and 1320 represents 10:00 PM.
    for (let time = 300; time <= 1320; time += 30) { // 5am to 10pm

      // check if the current 60-minute window conflicts with any existing busy slots.
      // '.some()' checks if at least one busy slot overlaps with our current 'time' to 'time + 60'.
      // The '!' (NOT operator) flips it, meaning 'isFree' will be true ONLY if there are zero conflicts.
      const isFree = !busySlots.some(slot => (time < slot.end && time + 60 > slot.start));

      // convert the minutes back into a readable time string and exit the function.
      if (isFree) return toStr(time);
    }
    return "later tonight";
  }

  // 3. THE DOG

  // COOL CODE: TYPEWRITER EFFECT
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
        // tells the browser: "wait 40 milliseconds,  then run this type function again."
        typewriterTimeout = setTimeout(type, 40);
      } else {
        // After finishing typing, switch back to neutral after a delay (2 seconds)
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
    const resource = document.querySelector('.centered-button');
    
    const count = eventHeatmap[selectedDate] || 0;
    const conflict = getConflictDetails(data); 

    var todoCount = $('#todo-list li').length;
    // console.log("To-do count:", todoCount);

    feedback.style.visibility = 'hidden';
    bubble.style.visibility = 'visible';
    resource.style.visibility = 'hidden';

    let message = "";
    let sprite = 'neutral.png';

    if (conflict) {
      sprite = 'think.png';
      const suggestion = findSuggestedGap(data);
      message = `Bark! "${conflict.event1}" and "${conflict.event2}" overlap. Maybe move "${conflict.event2}" to ${suggestion}?`;
    } else if (count > 4) {
      sprite = 'sleepy.png';
      messages = [
        "Your schedule looks packed! Don't forget to take breaks and manage your time wisely.",
        "That schedule is looking scary! Make sure to take care of yourself while tackling those tasks.",
        "Woah, that's a busy day! Remember to breathe and take it one step at a time."
      ];
      message = messages[Math.floor(Math.random() * messages.length)];
      resource.style.visibility = 'visible';
    } else if (todoCount > 5) {
      sprite = 'sleepy.png';
      messages = [
        "Your schedule looks light, but your to-do list is long! Don't forget to take breaks and manage your time wisely.",
        "That to-do list is looking a little scary! Make sure to take care of yourself while tackling those tasks.",
        "Woah, that's a lot on your plate! Remember to breathe and take it one step at a time."
      ];
      message = messages[Math.floor(Math.random() * messages.length)];
      resource.style.visibility = 'visible';
    } else if (count > 0) {
      sprite = 'bleh.png';
      messages = [
        "Not too bad! You've got this.",
        "Looking good! A few tasks to tackle but nothing you can't handle.",
        "Your schedule is looking manageable. Keep up the good work!"
      ];
      message = messages[Math.floor(Math.random() * messages.length)];
    } else {
      sprite = 'neutral.png';
      messages = [
        "All quiet on the western front! Want to add some plans?",
        "Nothing on the schedule! Maybe it's a good day to relax or try something new?",
        "Your calendar is clear! How about setting some goals or planning a fun activity?"
      ];
      message = messages[Math.floor(Math.random() * messages.length)];
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

    // Catch missing details before even sending the AJAX request
    if (!selectedDate || !title || !time) {
      $('#event-error').text('All fields (Date, Title, and Time) are required!');
      return;
    }

    $.ajax({
      url: 'events.php',
      method: 'POST',
      data: { action: 'add', ev_date: selectedDate, ev_time: time, ev_title: title, ev_desc: desc, ev_cat: $('#event-cat').val() },
      success: function(response) {
        if (response.startsWith('success')) {
          // Split the response string (e.g., "success:42") to extract the ID
          const parts = response.split(':');
          const newId = parts[1];

          // Update local data including the real database ID
          const newEv = { 
            ev_id: newId,
            ev_title: title, 
            ev_time: time, 
            ev_desc: desc, 
            formatted_date: selectedDate, 
            ev_cat: $('#event-cat').val() 
          };
          data.push(newEv);
          
          eventHeatmap[selectedDate] = (eventHeatmap[selectedDate] || 0) + 1;
          
          // Refresh UI
          dataTable.clear().rows.add(data).draw();
          renderCalendar(currentDate);
          updateDogImage();
          
          // Clear ALL inputs including description
          document.getElementById('event-title').value = '';
          document.getElementById('event-time').value = '';
          document.getElementById('event-desc').value = ''; 
        } else {
          $('#event-error').text(response);
        }
      },
      error: function() {
        $('#event-error').text('AJAX request failed completely.');
      }
    });
  };

  // Buttons
  prevMonthButton.onclick = () => { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(currentDate); };
  nextMonthButton.onclick = () => { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(currentDate); };

  // Init
  renderCalendar(currentDate);
  updateDogImage();


  // 5. TO-DO LIST FUNCTIONALITY
  const todoInput = document.getElementById('todo-input');
  const addTodoButton = document.getElementById('add-todo-btn');
  const todoList = document.getElementById('todo-list');

  function loadTasks() {
    $.ajax({
      url: 'todo.php',
      method: 'GET',
      data: { date: selectedDate },
      success: function(response) {
        // make sure only events that arent completed show up on the to-do list
        const incompleteTasks = JSON.parse(response).filter(task => !task.todo_completed);
        todoList.innerHTML = '';
        incompleteTasks.forEach(task => {
          const li = document.createElement('li');
          li.textContent = task.todo_task;
          li.setAttribute('data-id', task.todo_id);
          todoList.appendChild(li);
        });
        updateDogImage();
      }
    });
  }

  function saveTask() {
    var taskText = $('#todo-input').val().trim();
    if (!taskText) return;

    $.ajax({
      url: 'todo.php',
      method: 'POST',
      data: { task: taskText, action: 'add', date: selectedDate },
      cache: false,
      headers: {
          'Cache-Control': 'no-cache'
      },
      success: function(response) {
        const id = parseInt(response, 10);
        if (id > 0) {
          const li = $('<li>').text(taskText).attr('data-id', id);
          $('#todo-list').append(li);
          $('#todo-input').val('');
          updateDogImage();
        } else {
          alert('Error: ' + response);
        }
      },
      error: function() {
        alert('AJAX request failed');
      }
    });
  }

  // todo addition
  addTodoButton.onclick = saveTask;

  // todo removal
  $('#todo-list').on('click', 'li', function() {
      const li = $(this);
      const id = li.data('id');

      if (!id) return;

      if (!confirm('Delete this task?')) return;

      $.ajax({
        url: 'todo.php',
        method: 'POST',
        data: { action: 'delete', id: id },
        cache: false,
        success: function(response) {
          if (response === 'success') {
            li.remove();
            updateDogImage();
          } else {
            alert('Error deleting task: ' + response);
          }
        },
        error: function() {
          alert('AJAX request failed');
        }
      });
    });

  // Make the DIV element draggable: (https://www.w3schools.com/howto/howto_js_draggable.asp)
  dragElement(document.getElementById("dog"));

  function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    elmnt.onmousedown = dragMouseDown;

    function dragMouseDown(e) {
      e = e || window.event;
      e.preventDefault();
      // get the mouse cursor position at startup:
      pos3 = e.clientX;
      pos4 = e.clientY;
      document.onmouseup = closeDragElement;
      // call a function whenever the cursor moves:
      document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
      e = e || window.event;
      e.preventDefault();
      // calculate the new cursor position:
      pos1 = pos3 - e.clientX;
      pos2 = pos4 - e.clientY;
      pos3 = e.clientX;
      pos4 = e.clientY;
      // set the element's new position:
      elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
      elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
      // stop moving when mouse button is released:
      document.onmouseup = null;
      document.onmousemove = null;
    }
  }

  // Completed Events Button
  document.getElementById('show-completed-btn').onclick = function() {
    window.location.href = 'index.php?nav=completed';
  };
  
</script>