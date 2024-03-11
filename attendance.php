<?php include('./config/db_connect.php') ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <br>
        <br>
        <div class="card">
            <div class="card-header">
                <span id="selected_date_display"><b>Select Date</b></span> 

                <input  class="btn btn-sm col-md-3 float-right" type="date" id="selected_date" onchange="displaySelectedDate()">
            
            <!--    <button class="btn btn-sm col-md-3 float-right" type="button" id="new_attendance_btn" style="background-color: #d04848; color: white; padding: 5px 10px;"><span class="fa fa-plus"></span> Add Attendance</button>
            -->
            </div>


       
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="table table-striped table-bordered custom-table">
                        <colgroup>
                            <col width="8%">
                            <col width="10%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Schedule</th>
                                <th>1st In</th>
                                <th>1st Out</th>
                                <th>1st Break</th> 
                                <th>2nd In</th>
                                <th>2nd Out</th>
                                <th>2nd Break</th> 
                                <th>3rd In</th>
                                <th>3rd Out</th>
                                <th>Status</th>
                                <th>Net</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Lubuguin</td>
                                <td>08:00:00</td>


                                <td><i class="fas fa-plus-circle add-qr-scanner" style="margin-right: 10px;"></i><span class="current-time"></span></td>

                                <td><i class="fas fa-minus-circle add-qr-scanner" style="margin-right: 10px;"></i><span class="current-time"></span></td>

                                <td>
  <i class="fas fa-caret-down dropdown-icon" onclick="toggleDropdown(this)"></i>
  <div class="dropdown-content">
    <a href="#">30 mins</a>
    <a href="#">1 hour</a>
  </div>
</td>

                                
                                <td><i class="fas fa-plus-circle add-qr-scanner" style="margin-right: 10px;"></i><span class="current-time"></span></td>
                                <td><i class="fas fa-minus-circle add-qr-scanner" style="margin-right: 10px;"></i><span class="current-time"></span></td>

                                <td>
  <i class="fas fa-caret-down dropdown-icon" onclick="toggleDropdown(this)"></i>
  <div class="dropdown-content">
    <a href="#">30 mins</a>
    <a href="#">1 hour</a>
  </div>
</td>
        
                                <td><i class="fas fa-plus-circle add-qr-scanner" style="margin-right: 10px;"></i><span class="current-time"></span></td>
                                <td><i class="fas fa-minus-circle add-qr-scanner" style="margin-right: 10px;"></i><span class="current-time"></span></td>
                           
                                <?php
                           
                                $current_time_displayed = true;
                                ?>
                                <?php if ($current_time_displayed): ?>
                                    <td><i class="fas fa-circle text-success"></i></td>
                                <?php else: ?>
                                    <td><i class="fas fa-circle text-danger"></i></td>
                                <?php endif; ?>
                                <td></td>
                                <td>
                                    <center>
                                        <button class="btn btn-sm btn-outline-danger remove_attendance" data-id="1" type="button"><i class="fa fa-trash"></i></button>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- QR Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrScannerModalLabel">QR Code Scanner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="qr-reader"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    th,
    td {
        text-align: center;
    }

    /* Custom CSS for the table */
    .custom-table th {
        font-size: 12px;
        font-weight: bold;
        background-color: #f8f9fa;
        color: #495057;
    }

    .custom-table td {
        font-size: 14px;
        color: #343a40;
    }

    .custom-table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    .custom-table tbody tr:hover {
        background-color: #e9ecef;
    }

    .custom-table thead tr th,
    .custom-table tbody tr td {
        padding: 8px;
    }

    .custom-table thead th {
        vertical-align: middle;
    }

    .custom-table tbody td {
        vertical-align: middle;
    }

    .custom-table th,
    .custom-table td {
        border: 1px solid #dee2e6;

        
    }


    /* Style the dropdown button */
.dropbtn {
  background-color: #fff;
  color: #333;
  border: none;
  font-size: 16px;
  cursor: pointer;
}

.dropbtn i {
  margin-right: 5px;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown content (hidden by default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #fff;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: #333;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {
  background-color: #f1f1f1;
}

/* Show the dropdown menu (use JS to add this class to the dropdown content when the user clicks on the button) */
.show {
  display: block;
}


    
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>



<script>

function toggleDropdown(icon) {
  var dropdownContent = icon.nextElementSibling;
  dropdownContent.classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropdown-icon')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}


    // CALENDAR FUNCTION
    function showDatePicker() {
        var input = document.createElement('input');
        input.setAttribute('type', 'date');
        input.setAttribute('id', 'selected_date');
        input.setAttribute('class', 'btn btn-sm col-md-3 float-right');
        input.style.backgroundColor = '#d04848';
        input.style.color = 'white';
        input.style.padding = '5px 10px';
        input.style.display = 'none';

        input.addEventListener('change', displaySelectedDate);

        document.body.appendChild(input);

        // Trigger click event on the hidden input to open date picker
        input.click();
    }

    function displaySelectedDate() {
        var selectedDate = document.getElementById("selected_date").value;
        var displayElement = document.getElementById("selected_date_display");
        var formattedDate = new Date(selectedDate).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
        displayElement.innerHTML = "<b>" + formattedDate + "</b>";
    }



    $(document).ready(function() {
        // Function to handle QR scanner modal
        function handleQRScannerModal() {
            $('#qrScannerModal').modal('show');
            var html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", {
                    fps: 10,
                    qrbox: 250
                }
            );
            html5QrcodeScanner.render(onScanSuccess);
        }

        // Attach click event handler to plus icons
        $(document).on('click', '.add-qr-scanner', function() {
            handleQRScannerModal();
            // Store the clicked cell for later use
            $(this).closest('td').addClass('clicked-cell');
        });

        // Function to handle successful QR code scan
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Scan result ${decodedText}`, decodedResult);
            Swal.fire({
                title: "Attendance Successfully.",
                text: "John Patrick Lubuguin",
                icon: "success",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK",
                customClass: {
                    container: "custom-sweetalert-container",
                    popup: "custom-sweetalert-popup",
                    title: "custom-sweetalert-title",
                    text: "custom-sweetalert-text",
                    confirmButton: "custom-sweetalert-confirm-button"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#qrScannerModal').modal('hide'); // Close the modal
              
                    var currentTime = new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit', hour12: false});
                    $('.clicked-cell').find('.current-time').text(currentTime).fadeIn().removeClass('current-time'); 
                }
            });
        }
    });

    

    
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.edit_attendance').click(function() {
            var $id = $(this).attr('data-id');
            uni_modal("Edit Employee", "manage_attendance.php?id=" + $id)

        });
        $('.view_attendance').click(function() {
            var $id = $(this).attr('data-id');
            uni_modal("Employee Details", "view_attendance.php?id=" + $id, "mid-large")

        });
        $('#new_attendance_btn').click(function() {
            uni_modal("New Time Record/s", "manage_attendance.php", 'mid-large')
        })
        $('.remove_attendance').click(function() {
            var d = '"' + ($(this).attr('data-id')).toString() + '"';
            _conf("Are you sure to delete this employee's time log record?", "remove_attendance", [d])
        })
        $('.rem_att').click(function() {
            var $id = $(this).attr('data-id');
            _conf("Are you sure to delete this time log?", "rem_att", [$id])
        })
    });

    
    function remove_attendance(id) {
        start_load()
        $.ajax({
            url: './services/ajax.php?action=delete_employee_attendance',
            method: "POST",
            data: {
                id: id
            },
            error: err => console.log(err),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Selected employee's time log data successfully deleted", "success");
                    setTimeout(function() {
                        location.reload();

                    }, 1000)
                }
            }
        })
    }

    function rem_att(id) {
        start_load()
        $.ajax({
            url: './services/ajax.php?action=delete_employee_attendance_single',
            method: "POST",
            data: {
                id: id
            },
            error: err => console.log(err),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Selected employee's time log data successfully deleted", "success");
                    setTimeout(function() {
                        location.reload();

                    }, 1000)
                }
            }
        })
    }
</script>