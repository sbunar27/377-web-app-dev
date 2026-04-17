<?php
// USING THE NUMBER OF EVENTS TO FIGURE OUT HEATMAP COLORS:
$connection = get_connection();

// We add "WHERE ev_completed = 0" so completed events are ignored by the count
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

// Pass this data to JavaScript using JSON for use in the calendar rendering
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

<!-- datatables for table of events -->
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
    // Log the error for debugging; avoid exposing DB errors to users
    error_log('DB query failed: ' . $connection->error);
}

$connection->close();

print('<script>');
print('var data = ' . json_encode($rows, JSON_PARTIAL_OUTPUT_ON_ERROR) . ';');
print('</script>');
?>

<script>
    var dataTable = $('#eventTable').DataTable({
        data: data,
        columns: [
            { title: "Event", data: "ev_title", render: function(data, type, row) {
                if (type === 'display') {
                    return '<a href="detail.php?id=' + row.ev_id + '">' + data + '</a>';
                }
                return data;
            }},
            { title: "Event Date", data: "formatted_date" },
            { title: "Event Time", data: "ev_time" },
            { title: "Description", data: "ev_desc" }
        ]
    });

  const calendarDates = document.getElementById('calendar-dates');
  const monthYear = document.getElementById('month-year');
  const selectedDateDisplay = document.getElementById('selected-date-display');
  const addEventButton = document.getElementById('add-event-btn');

  const prevMonthButton = document.getElementById('prev-month');
  const nextMonthButton = document.getElementById('next-month');

  // 1. Check the URL for a 'date' parameter
  const urlParams = new URLSearchParams(window.location.search);

  // 2. Set selectedDate to the URL date, or leave it null if none exists
  let selectedDate = urlParams.get('date');

  // 3. Set the calendar's view to the selected date's month
  let currentDate = selectedDate ? new Date(selectedDate + 'T12:00:00') : new Date();

  function renderCalendar(date) {
    calendarDates.innerHTML = '';

    const year = date.getFullYear();
    const month = date.getMonth();

    const monthNames = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];
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

      // --- HEATMAP LOGIC ---
      const count = eventHeatmap[cellDate] || 0; // Get count or 0 if none
      
      if (count > 0) {
        if (count === 1) {
          cell.style.backgroundColor = '#c3d2c2ff'; // Very light shade
        } else if (count >= 2 && count <= 3) {
          cell.style.backgroundColor = '#a4baa3ff'; // Medium shade
        } else if (count >= 4 && count <= 5) {
          cell.style.backgroundColor = '#779976'; // Medium-dark shade
        } else {
          cell.style.backgroundColor = '#5f7b5eff'; // Darker shade for many events
          cell.style.color = '#ddebdcff'; // White text for contrast
        }
        // Optional: Add a small dot or text to show the number
        cell.title = count + " events"; 
      }
      // --- HEATMAP LOGIC ---

      cell.onclick = function() {
        window.location.href = 'index.php?nav=agenda&date=' + cellDate;
      };

      if (selectedDate === cellDate) {
        cell.style.backgroundColor = '#588157'; 
        cell.style.color = '#ffffff';
      }

      calendarDates.appendChild(cell);
    }
  }

  // buttons for calendar month change
  prevMonthButton.onclick = function() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
  };

  nextMonthButton.onclick = function() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
  };

  // initial calendar render
  renderCalendar(currentDate);

  // to-do list functionality
  const todoInput = document.getElementById('todo-input');
  const addTodoButton = document.getElementById('add-todo-btn');
  const todoList = document.getElementById('todo-list');

  function saveTask() {
    var taskText = $('#todo-input').val().trim();
    if (!taskText) return;

    $.ajax({
      url: 'todo.php',
      method: 'POST',
      data: { task: taskText, action: 'add' },
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
        } else {
          alert('Error: ' + response);
        }
      },
      error: function() {
        alert('AJAX request failed');
      }
    });
  }

  $('#add-todo-btn').click(saveTask);

  // to-do removal functionality
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
        } else {
          alert('Error deleting task: ' + response);
        }
      },
      error: function() {
        alert('AJAX request failed');
      }
    });
  });

  // FUNCTION TO ADD AN EVENT
  document.getElementById('add-event-btn').onclick = function() {
    if (!selectedDate) {
      alert('Please select a date first.');
      return;
    }

    const title = document.getElementById('event-title').value.trim();
    const desc = document.getElementById('event-desc').value.trim();

    if (!title) {
      alert('Event title is required.');
      return;
    }

    // Send event data to server
    $.ajax({
      url: 'events.php',
      method: 'POST',
      data: {
        action: 'add',
        ev_date: selectedDate,
        ev_time: document.getElementById('event-time').value.trim(),
        ev_title: title,
        ev_desc: desc
      },
      success: function(response) {
        if (response.startsWith('success')) {
          alert('Event added!');
          // Clear inputs
          document.getElementById('event-title').value = '';
          document.getElementById('event-desc').value = '';
          loadEvents(selectedDate); // Refresh events list
        } else {
          alert('Error adding event: ' + response);
        }
      },
      error: function() {
        alert('AJAX request failed');
      }
    });
  };
</script>