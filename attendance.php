<?php
include('./config/db_connect.php');

$employees = array();
$employee_schedules = array();

// Fetch all employees from the database
$sql = "SELECT id, lastname FROM employee";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[$row['id']] = $row['lastname'];
    }
}

// Fetch all schedules for all employees based on the selected date
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$sql = "SELECT e.*, s.*, CONCAT(e.firstname, ' ', e.lastname) AS employee FROM employee e LEFT JOIN schedule s ON e.id = s.employee_ID AND s.date = '$selected_date' WHERE s.date IS NOT NULL;";
$employee_list = mysqli_query($conn, $sql);


// Output data of each row
// while ($row = mysqli_fetch_assoc($results)) {
//     // Output the results as needed
//     echo "Employee ID: " . $row["employee_no"] . " - Name: " . $row["employee"] . "<br>";
// }


mysqli_close($conn);
?>


<div class="container-fluid">
    <div class="col-lg-12">
        <br>
        <br>
        <div class="card">
            <div class="card-header">
                <span id="selected_date_display"><b>Select Date</b></span>
                <input type="date" id="selected_date" class="btn btn-sm col-md-3 float-right border shadow-sm py-2 fw-semibold rounded-4 ">
            </div>

            <!--    <button class="btn btn-sm col-md-3 float-right" type="button" id="new_attendance_btn" style="background-color: #d04848; color: white; padding: 5px 10px;"><span class="fa fa-plus"></span> Add Attendance</button>
            -->

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
                            <?php foreach ($employee_list as $employee) : ?>
                                <tr id="emp-<?php echo $employee["id"]; ?>">
                                    <td><?php echo $employee["employee"]; ?></td>
                                    <td>No schedule available</td>

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
                                    $current_time_displayed = true; // Example value for demonstration

                                    // Conditional display of circle icon
                                    if ($current_time_displayed) {
                                        echo '<td><i class="fas fa-circle text-success"></i></td>';
                                    }
                                    // else {
                                    //     echo '<td><i class="fas fa-circle text-danger"></i></td>';
                                    // }
                                    ?>

                                    <td></td>

                                    <td>
                                        <center>
                                            <!-- Button with trash icon -->
                                            <button class="btn btn-sm btn-outline-danger remove_attendance" data-id="1" type="button"><i class="fa fa-trash"></i></button>
                                        </center>
                                    </td>



                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Modal -->
<div class="modal fade scanner-close-btn" id="qrScannerModal" tabindex="-1" role="dialog" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrScannerModalLabel">QR Code Scanner</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="qr-reader"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary  scanner-close-btn" data-bs-dismiss="modal">Close</button>
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
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
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
    // SET A DEFAULT VALUE IN DATE PICKER
    $(document).ready(function() {
        // Get the current date
        var currentDate = new Date();

        var urlParams = new URLSearchParams(window.location.search);
        var dateParam = urlParams.get('date');

        // Check if the 'date' parameter exists
        if (dateParam !== null) {
            // Set the value of the input field to the value of the 'date' parameter
            $('#selected_date').val(dateParam);
        } else {
            // Format the current date as "YYYY-MM-DD"
            var formattedDate = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2);

            // Set the formatted date as the value of the input field
            $('#selected_date').val(formattedDate);
        }



        function toggleDropdown(icon) {
            var dropdownContent = icon.nextElementSibling;
            dropdownContent.classList.toggle("show");
        }


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

        // DATE PICKER HANDLE
        $("#selected_date").on("change", function() {
            let date = $(this).val()

            var url = new URL(window.location.href);
            url.searchParams.set("date", date);
            window.history.replaceState({}, '', url);

            location.reload();
        })

        // QR CODE SCANNER
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 10,
                qrbox: 250,
                // disableFlip: true
            }
        );

        // Listen for modal close event
        // Stop the QR code scanner when the modal is closed
        $('#qrScannerModal').on('hidden.bs.modal', function() {
            html5QrcodeScanner.clear();
            html5QrcodeScanner.stop();
        });

        // Function to handle QR scanner modal
        function handleQRScannerModal() {
            $('#qrScannerModal').modal('show');
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

                    var currentTime = new Date().toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                    $('.clicked-cell').find('.current-time').text(currentTime).fadeIn().removeClass('current-time');
                }
            });

            html5QrcodeScanner.clear();
            // Close the camera by stopping the scanning process.
            html5QrcodeScanner.stop();
        }
    });
</script>