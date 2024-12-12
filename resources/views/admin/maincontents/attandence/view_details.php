<?php
use App\Helpers\Helper;
use App\Models\Admin;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\Companies;
use App\Models\Employees;
use App\Models\EmployeeType;

$controllerRoute = $module['controller_route'];
?>
<style>
  * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  p {
      padding: 0;
      margin: 0;
  }

  .stats-card {
      border: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 0.5rem;
  }

  .calendar-day {
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.25rem;
  }

  .calendar-day.present {
      background-color: #d4edda;
      color: #155724;
  }

  .calendar-day.absent {
      background-color: #f8d7da;
      color: #721c24;
  }

  .calendar-day.late {
      position: relative;
  }

  .calendar-day.late::after {
      content: "LATE";
      position: absolute;
      bottom: -15px;
      font-size: 0.75rem;
      color: #ff9800;
  }

  .calendar-day.week-off {
      background-color: #e2e3e5;
      color: #6c757d;
  }

  .employee_attendance_holder .card-header a {
      color: #000;
      font-size: 1.5rem;
      text-decoration: none;
      font-weight: 500;
  }

  .user_holder {
      margin: 20px 0 35px;
  }

  .attendance_info_holder .col-md-2 {
      width: 20%;
  }

  .present_box {
      background: #eff9f1;
      border-left: 5px solid #36c887;
  }

  .absent_box {
      background: #fcecec;
      border-left: 5px solid #e84b54;
  }

  .halfday_box {
      background: #fdf5ea;
      border-left: 5px solid #ecb82e;
  }

  .paidleave_box {
      background: #fdf1ff;
      border-left: 5px solid #d575d7;
  }

  .text-leave {
      color: #d575d7
  }

  .weekoff_box {
      background: #f3f3f3;
      border-left: 5px solid #898989;
  }

  .attendance_calender th,
  .attendance_calender td {
      text-align: center;
      padding: 0;
  }

  .attendance_calender table,
  .attendance_calender tbody,
  .attendance_calender td,
  .attendance_calender th,
  .attendance_calender thead,
  .attendance_calender tr {
      border: none
  }

  .attendance_calender td .cal_date {
      border-radius: 5px;
      color: #fff;
      background: #3ace78;
      padding: 5px;
      height: 42px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      margin: 5px;
  }

  .attendance_calender td:first-child .cal_date {
      background: #8a8a8a;
      color: #fff;
      margin-left: 0;
  }

  .attendance_calender td:last-child .cal_date {
      margin-right: 0;
  }

  .cal_date p {
      text-transform: uppercase;
      line-height: 1;
      font-weight: 500;
  }

  .cal_date p+p {
      font-size: 10px;
  }
  /* modal css */
  .modal.drawer {
      display: flex !important;
      pointer-events: none;
  }

  .modal.drawer * {
      pointer-events: none;
  }

  .modal.drawer .modal-dialog {
      margin: 0px;
      display: flex;
      flex: auto;
      transform: translate(25%, 0);
  }

  .modal.drawer .modal-dialog .modal-content {
      border: none;
      border-radius: 0px;
  }

  .modal.drawer .modal-dialog .modal-content .modal-body {
      overflow: auto;
  }

  .modal.drawer.show {
      pointer-events: auto;
  }

  .modal.drawer.show * {
      pointer-events: auto;
  }

  .modal.drawer.show .modal-dialog {
      transform: translate(0, 0);
  }

  .modal.drawer.right-align {
      flex-direction: row-reverse;
  }

  .modal.drawer.left-align:not(.show) .modal-dialog {
      transform: translate(-25%, 0);
  }

  .status-buttons button {
      margin-right: 5px;
      font-size: 14px;
  }

  .leaves span {
      margin-right: 5px;
      font-size: 14px;
      padding: 5px 10px;
      border-radius: 20px;
      border: 1px solid;
      display: inline-block;
  }

  .leaves span.paid {
      background-color: #f8f9fa;
      color: purple;
      border-color: purple;
  }

  .leaves span.unpaid {
      background-color: #f8f9fa;
      color: blue;
      border-color: blue;
  }

  .leaves span.half {
      background-color: #f8f9fa;
      color: orange;
      border-color: orange;
  }

  .note {
      margin-top: 20px;
  }
  .table_user{
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-right: 10px;
  }
  @media(max-width: 767px){
      .attendance_info_holder .col-md-2{
          width: 50%;
          margin-bottom: 15px;
      }
  }
</style>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><?=$page_header?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section">
  <div class="row justify-content-center">
    <!-- <div class="col-xl-12">
      @if(session('success_message'))
        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('success_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if(session('error_message'))
        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('error_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div> -->
    <div class="employee_attendance_holder">
        <div class="container-fluid py-3">
            <!-- <header class="mb-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">KEYLINE DIGITECH PRIVATE LIMITED</h5>
            <span>Company Code: CN0459</span>
        </header> -->
            <div class="card">
                <div class="card-header">
                    <a href="#"><i class="fa fa-arrow-left-long me-3"></i> Employee Attendance</a>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between user_holder">
                        <div class="d-flex align-items-center">
                            <img src="<?=env('UPLOADS_URL').$row->profile_image?>" class="rounded-circle me-3 table_user" alt="Employee">
                            <h5 class="mb-0"><?=$row->name?></h5>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 mt-md-0">
                          <div class="me-2">
                            <select class="form-select d-inline-block w-auto" id="monthSelect">
                                <?php
                                $currentMonth = date('m');
                                for ($m = 1; $m <= 12; $m++) {
                                    $selected = ($m == $currentMonth) ? 'selected' : '';
                                    echo "<option value='{$m}' {$selected}>".date('F', mktime(0, 0, 0, $m, 1))."</option>";
                                }
                                ?>
                            </select>
                            <select class="form-select d-inline-block w-auto ms-2" id="yearSelect">
                                <?php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 5;
                                $endYear = $currentYear + 5;

                                for ($y = $startYear; $y <= $endYear; $y++) {
                                    $selected = ($y == $currentYear) ? 'selected' : '';
                                    echo "<option value='{$y}' {$selected}>{$y}</option>";
                                }
                                ?>
                            </select>
                          </div>
                          <div class="mt-2 mt-sm-0">
                              <button class="btn btn-primary">Download Report</button>
                          </div>
                        </div>
                    </div>
                    <div class="attendance_info_holder mb-4">
                        <div class="row mb-4">
                            <div class="col-md-2">
                                <div class="card stats-card present_box">
                                    <div class="card-body">
                                        <h6 class="text-success">Present</h6>
                                        <h4 class="mb-0">20</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card stats-card absent_box">
                                    <div class="card-body">
                                        <h6 class="text-danger">Absent</h6>
                                        <h4 class="mb-0">5</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card stats-card halfday_box">
                                    <div class="card-body">
                                        <h6 class="text-warning">Half Day</h6>
                                        <h4 class="mb-0">0</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card stats-card paidleave_box">
                                    <div class="card-body">
                                        <h6 class="text-leave">Paid Leave</h6>
                                        <h4 class="mb-0">0.0</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card stats-card weekoff_box">
                                    <div class="card-body">
                                        <h6 class="text-muted">Week Off</h6>
                                        <h4 class="mb-0">5</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="attendance_calender pt-4">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th>Sun</th>
                                  <th>Mon</th>
                                  <th>Tue</th>
                                  <th>Wed</th>
                                  <th>Thu</th>
                                  <th>Fri</th>
                                  <th>Sat</th>
                              </tr>                            
                            <tbody id="calendarBody">
                              <!-- Calendar Days will be inserted here dynamically -->            
                            </tbody>
                          </thead>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade drawer right-align" id="attendance_info_popup" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Attendance: Bappa Day</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">5th August</h6>
                        </div>
                    </div>
                    <div class="status-buttons mb-3">
                        <button class="btn btn-outline-danger mb-1">ABSENT</button>
                        <button class="btn btn-outline-warning mb-1">HALF DAY</button>
                        <button class="btn btn-outline-success mb-1">PRESENT</button>
                        <button class="btn btn-outline-secondary mb-1">WEEK OFF</button>
                        <button class="btn btn-outline-info mb-1">HOLIDAY</button>
                    </div>
                    <div class="d-flex align-items-center mb-3 border-top pt-3">
                        <div>
                            <h6 class="mb-0">LEAVE</h6>
                        </div>
                    </div>
                    <div class="leaves mb-3">
                        <span class="paid m-1">PAID LEAVE</span>
                        <span class="half m-1">HALF DAY LEAVE</span>
                        <span class="unpaid m-1">UNPAID LEAVE</span>
                    </div>
                    <div class="mb-3">
                        <h6 class="mb-1">Punch Details</h6>
                        <div class="d-flex align-items-center mb-3">
                            <img src="5.png" alt="Profile" class="rounded-circle me-3 table_user">
                            <div>
                                <p><strong>10:42 AM</strong> &middot; In</p>
                                <small class="text-muted">45/30/1A, Surya Nagar Colony, Ashok Nagar, Tollygunge, Kolkata, West
                                Bengal 700040, India</small>
                            </div>
                        </div>
                        
                    </div>
                    <div class="d-flex justify-content-start gap-3">
                        <button class="btn btn-link">+ ADD PUNCH OUT</button>
                        <button class="btn btn-link">+ ADD BREAK START</button>
                    </div>
                    <div class="note">
                        <textarea class="form-control" rows="3" placeholder="Add Note"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>  
</section>
<script>
    function generateCalendar(month, year) {
        // Get the first day of the month (0-6 where 0 = Sunday, 6 = Saturday)
        const firstDay = new Date(year, month - 1, 1).getDay();
        // Get the number of days in the month
        const daysInMonth = new Date(year, month, 0).getDate();
        
        let calendarHtml = '';
        let day = 1;
        
        // Loop through weeks (max 6 weeks)
        for (let week = 0; week < 6; week++) {
            calendarHtml += '<tr>';
            
            // Loop through days of the week (0=Sun, 6=Sat)
            for (let weekday = 0; weekday < 7; weekday++) {
                if (week === 0 && weekday < firstDay) {
                    // Empty cells before the first day of the month
                    calendarHtml += '<td></td>';
                } else if (day <= daysInMonth) {
                    // Add the day cell
                    calendarHtml += `<td><div class="cal_date" data-bs-toggle="modal" data-bs-target="#attendance_info_popup"><p>${day}</p></div></td>`;
                    day++;
                } else {
                    // Empty cells after the last day of the month
                    calendarHtml += '<td></td>';
                }
            }
            calendarHtml += '</tr>';
            
            if (day > daysInMonth) break;
        }
        
        // Insert the generated calendar HTML into the table body
        document.getElementById('calendarBody').innerHTML = calendarHtml;
    }

    // Event listeners for the select dropdowns
    document.getElementById('monthSelect').addEventListener('change', function() {
        const month = parseInt(this.value);
        const year = parseInt(document.getElementById('yearSelect').value);
        generateCalendar(month, year);
    });

    document.getElementById('yearSelect').addEventListener('change', function() {
        const month = parseInt(document.getElementById('monthSelect').value);
        const year = parseInt(this.value);
        generateCalendar(month, year);
    });

    // Initial calendar load
    generateCalendar(new Date().getMonth() + 1, new Date().getFullYear());
</script>
<script>
    // Function to handle the month/year change and update the calendar via AJAX
    document.getElementById('monthSelect').addEventListener('change', function() {
        const month = parseInt(this.value);
        const year = parseInt(document.getElementById('yearSelect').value);

        updateCalendar(month, year);
    });

    document.getElementById('yearSelect').addEventListener('change', function() {
        const month = parseInt(document.getElementById('monthSelect').value);
        const year = parseInt(this.value);

        updateCalendar(month, year);
    });

    function updateCalendar(month, year) {
        fetch("{{ route('attendance.updateCalendar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ month, year })
        })
        .then(response => response.json())
        .then(data => {
            // Update the calendar body with the new calendar HTML
            document.getElementById('calendarBody').innerHTML = data.calendarHtml;
        });
    }
</script>
