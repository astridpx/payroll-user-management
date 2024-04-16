
<?php
include('./config/db_connect.php');

// Fetch all schedules for all employees based on the selected date
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$sql = "SELECT e.*, s.*, CONCAT(e.firstname, ' ', e.lastname) AS employee, 
        CASE WHEN LENGTH(s.time_start) = 0 OR LENGTH(s.time_end) = 0 
        THEN CONCAT(s.time_start, s.time_end) ELSE CONCAT(s.time_start, '-', s.time_end) END AS time,
        s.1st_in, s.1st_out, s.2nd_in, s.2nd_out, s.3rd_in, s.3rd_out
        FROM employee e 
        LEFT JOIN schedule s ON e.id = s.employee_ID AND s.date = '$selected_date' 
        WHERE s.date IS NOT NULL;";
$employee_list = mysqli_query($conn, $sql);
// Function to convert duration string to hours
function convertToTime($duration_string) {
    if (!$duration_string) return;

    // Split the duration string into parts
    $parts = explode(" ", $duration_string);

    // Extract the numeric value and unit
    $value = (int)$parts[0];
    $unit = strtolower($parts[1]);

    // Convert to time format
    if ($unit === 'hour' || $unit === 'hours') {
        return $value; // Return the numeric value for hours
    } elseif ($unit === 'min' || $unit === 'mins' || $unit === 'minute' || $unit === 'minutes') {
        return $value / 60; // Return the numeric value for minutes converted to hours
    } else {
        return 0; // Return 0 for invalid duration format
    }
}
// Output data of each row
function calculateNetHours($time_start, $time_end) {
    if (!$time_start || !$time_end) {
        return 0;
    }

    // Create DateTime objects for start and end times
    $start_time = DateTime::createFromFormat('h:i A', $time_start);
    $end_time = DateTime::createFromFormat('h:i A', $time_end);

    // Calculate the difference in hours
    $interval = $start_time->diff($end_time);
    $total_hours = $interval->h + ($interval->i / 60);

    return $total_hours;
}
function calculatePay($employee) {
    // Calculate net working hours for each shift
    $net_hours_1st = calculateNetHours($employee["1st_in"], $employee["1st_out"]);
    $net_hours_2nd = calculateNetHours($employee["2nd_in"], $employee["2nd_out"]);
    $net_hours_3rd = calculateNetHours($employee["3rd_in"], $employee["3rd_out"]);
    
    // Calculate total net working hours
    $net_hours_1st_2nd_3rd = $net_hours_1st + $net_hours_2nd + $net_hours_3rd;

    // Initialize variables for pay calculation
    $regular_pay = 0;
    $ot_pay = 0;

    // Check if total net hours exceed 8 hours
    if ($net_hours_1st_2nd_3rd > 8) {
        // Calculate overtime pay
        $ot_hours = $net_hours_1st_2nd_3rd - 8;
        $ot_pay = $ot_hours * 74.84375;

        // Calculate regular pay
        $regular_hours = $net_hours_1st_2nd_3rd - $ot_hours;
        $regular_pay = $regular_hours * 59.875;
    } else {
        // Calculate regular pay
        $regular_pay = $net_hours_1st_2nd_3rd * 59.875;
    }

    // Total pay is the sum of regular pay and overtime pay
    $total_pay = $regular_pay + $ot_pay;

    return $total_pay;
}

function calculateLate($first_in, $time_start) {
    if (!$first_in || !$time_start) {
        return 0; // Return 0 if either time is missing
    }

    // Create DateTime objects for first_in and time_start
    $first_in_time = DateTime::createFromFormat('h:i A', $first_in);
    $time_start = DateTime::createFromFormat('h:i A', $time_start);

    // Calculate the difference in minutes
    $interval = $first_in_time->diff($time_start);
    $minutes_difference = $interval->i;

    return $minutes_difference;
}





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
                            <col style="min-width: 8rem;" width="8%">
                            <col style="min-width: 10rem;" width="10%">
                            <col style="min-width: 5rem;" width="8%">
                            <col style="min-width: 5rem;" width="8%">
                            <col style="min-width: 5rem;" width="8%">
                            <col style="min-width: 5rem;" width="8%">
                            <col style="min-width: 5rem;" width="8%">
                            <col style="min-width: 5rem;" width="8%">
                            <col style="min-width: 5rem;" width="8%">
                            <col style="min-width: 5rem;" width="8%">
                            <col width="8%">
                            <!-- <col width="8%"> -->
                            <!-- <col width="8%"> -->
                        </colgroup>
                        <thead>
                        <th>Name</th>
<th>Schedule</th>
<th>1st In</th>
<th>1st Out</th>
<th>2nd In</th>
<th>2nd Out</th>
<th>3rd In</th>
<th>3rd Out</th>
<th>Net</th>
<th>Lates</th>

<tbody>
<?php foreach ($employee_list as $employee) : ?>
    <tr id="emp-<?php echo $employee["id"]; ?>">
        <td><?php echo $employee["employee"]; ?></td>
        <td><?php echo $employee["time"] ? $employee["time"] : "No schedule available"; ?></td>

        <?php // Calculate net working hours and total pay for 1st and 2nd shifts ?>
        <?php 
        $net_hours_1st_2nd_3rd = 0;
        $total_pay_1st_2nd = 0;

        // Calculate net working hours for 1st shift
        $net_hours_1st = calculateNetHours($employee["1st_in"], $employee["1st_out"]);

        // Calculate net working hours for 2nd shift
        $net_hours_2nd = calculateNetHours($employee["2nd_in"], $employee["2nd_out"]);

        // Calculate net working hours for 3rd shift
        $net_hours_3rd = calculateNetHours($employee["3rd_in"], $employee["3rd_out"]);

        // Calculate total net working hours for all shifts
        $net_hours_1st_2nd_3rd = $net_hours_1st + $net_hours_2nd + $net_hours_3rd;
        
        // Calculate total pay for 1st and 2nd shifts
        $total_pay_1st_2nd = calculatePay($employee);
        $late_minutes = calculateLate($employee["1st_in"], $employee["time_start"]);
     
        
        // Update the 'net' column in the 'schedule' table with the total pay
        $employee_id = $employee["id"];
        $update_sql = "UPDATE schedule SET net = $total_pay_1st_2nd, lates=$late_minutes  WHERE employee_ID = $employee_id AND date = '$selected_date'";
        mysqli_query($conn, $update_sql); // Execute the SQL update statement
        ?>

        <td><?php echo $employee["1st_in"] ? $employee["1st_in"] : ''; ?></td>
        <td><?php echo $employee["1st_out"] ? $employee["1st_out"] : ''; ?></td>
        <td><?php echo $employee["2nd_in"] ? $employee["2nd_in"] : ''; ?></td>
        <td><?php echo $employee["2nd_out"] ? $employee["2nd_out"] : ''; ?></td>
        <td><?php echo $employee["3rd_in"] ? $employee["3rd_in"] : ''; ?></td>
        <td><?php echo $employee["3rd_out"] ? $employee["3rd_out"] : ''; ?></td>
        <td><?php echo "₱" . number_format($total_pay_1st_2nd, 2); ?></td>
        <td><?php echo "Late: " . $late_minutes . " minutes"; ?></td>
    </tr>
<?php endforeach; ?>


</tbody>
                    </table>
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
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    // SET A DEFAULT VALUE IN DATE PICKER
    // $(document).ready(function() {
    // Get the current date
    var currentDate = new Date();
    var paramsDate = null;

    var urlParams = new URLSearchParams(window.location.search);
    var dateParam = urlParams.get('date');

    // Check if the 'date' parameter exists
    if (dateParam !== null) {
        // Set the value of the input field to the value of the 'date' parameter
        $('#selected_date').val(dateParam);
        paramsDate = dateParam
    } else {
        // Format the current date as "YYYY-MM-DD"
        var formattedDate = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2);

        // Set the formatted date as the value of the input field
        $('#selected_date').val(formattedDate);
        paramsDate = formattedDate
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

    // DATA PAYLOAD
    let data = {
        column: "",
        time: "",
        id: null,
        date: paramsDate,
    }

    // Listen for modal close event
    // Stop the QR code scanner when the modal is closed
    $('#qrScannerModal').on('hidden.bs.modal', function() {
        html5QrcodeScanner.clear();
    });

    // Function to handle QR scanner modal
    function handleQRScannerModal() {
        $('#qrScannerModal').modal('show');
        html5QrcodeScanner.render(onScanSuccess);
    }

    // Attach click event handler to plus icons
    // GET THE ID OF ROWS AND COLUMN
    $(document).on('click', '.add-qr-scanner', function() {
        // Find the closest table row (tr)
        var row = $(this).closest('tr');

        // Get the index of the cell within its row
        var cellIndex = $(this).closest('td').index();

        // Find the corresponding header cell in the <thead>
        var headerCell = row.closest('table').find('thead th').eq(cellIndex);

        // Get the column ID from the header cell
        var columnId = headerCell.attr('id');
        var rowId = row.attr('id')
        console.log(rowId)

        data.column = columnId
        data.id = rowId.split("-")[1]

        handleQRScannerModal();
        // Store the clicked cell for later use
        $(this).closest('td').addClass('clicked-cell');
    });

    // Function to handle successful QR code scan
    function onScanSuccess(decodedText, decodedResult) {
        // console.log(`Scan result ${decodedText}`, decodedResult);

        $('#qrScannerModal').modal('hide'); // Close the modal

        const currentTime = new Date();
        // currentTime.toLocaleTimeString("PST");

        const formattedTime = currentTime.toLocaleTimeString("en-US", {
            hour: '2-digit',
            minute: '2-digit'
        });

        data.time = formattedTime

        $('.clicked-cell').find('.current-time').text(formattedTime).fadeIn().removeClass('current-time');
        // Hide the icon
        $('.clicked-cell').find('.add-qr-scanner').hide();

        // console.log(data)
        HandleAttendance(data)

        // Close the camera by stopping the scanning process.
        html5QrcodeScanner.clear();
    }


    // BREAKTIME DROPDOWN
    async function handleDropdownSelection(value) {
        // Find the closest table row (tr)
        var row = $(this).closest('tr');

        // Get the index of the cell within its row
        var cellIndex = $(this).closest('td').index();

        // Find the corresponding header cell in the <thead>
        var headerCell = row.closest('table').find('thead th').eq(cellIndex);

        // Get the column ID from the header cell
        var columnId = headerCell.attr('id');
        var rowId = row.attr('id')

        data.column = columnId
        data.id = rowId.split("-")[1]

        data.time = value
        // console.log(data)
        await HandleAttendance(data)
        location.reload()
    }


    // ATTENDANCE AJAX
    async function HandleAttendance(payload) {
        await $.ajax({
            url: './services/ajax.php?action=attendance_employee',
            method: 'POST',
            data: payload,
            error: err => {
                console.log(err)
                Swal.fire({
                    title: "Something went wrong.",
                    // text: "John Patrick Lubuguin",
                    icon: "error",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK",
                    customClass: {
                        container: "custom-sweetalert-container",
                        popup: "custom-sweetalert-popup",
                        title: "custom-sweetalert-title",
                        text: "custom-sweetalert-text",
                        confirmButton: "custom-sweetalert-confirm-button"
                    }
                });
            },
            success: (resp) => {
                if (resp) {
                    // console.log(resp)
                    Swal.fire({
                        title: "Attendance Successfully.",
                        // text: "John Patrick Lubuguin",
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
                    });
                }
            }
        })
    }

    // });
</script>