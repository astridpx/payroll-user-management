<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <!-- Include the html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
    <div id="qr-reader"></div>
    <div class="card-header">
    <span id="selected_date_display"><b>Select Date</b></span>
    <!-- Set the value of the input field to the current date -->
    <input type="date" id="selected_date" class="btn btn-sm col-md-3 float-right border shadow-sm py-2 fw-semibold rounded-4 " value="<?php echo date('Y-m-d'); ?>">
</div>


    <script>
        // HTML5 QR Code Scanner
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 10,
                qrbox: 250,
            }
        );

        // Function to handle successful QR code scan
        function onScanSuccess(decodedText, decodedResult) {
            // Redirect to the PHP file passing the QR content as a parameter along with selected date
            var selectedDate = document.getElementById('selected_date').value;
            window.location.href = 'qr_scan.php?qr_content=' + decodedText + '&selected_date=' + selectedDate;
        }

        // Attach event handler for successful scan
        html5QrcodeScanner.render(onScanSuccess);

        // Listen for modal close event
        // Stop the QR code scanner when the modal is closed
        window.addEventListener('beforeunload', function () {
            html5QrcodeScanner.clear();
        });
    </script>
</body>
</html>

<?php
// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

include('./config/db_connect.php');

// Function to update the employee's schedule based on the current state
function updateSchedule($conn, $employee_id, $current_state, $selected_date) {
    $current_time = date('H:i:s'); // Get current time in 24-hour format

    // Update the appropriate field based on the current state and selected date
    $update_sql = "UPDATE schedule SET $current_state = '$current_time' WHERE employee_ID = '$employee_id' AND DATE(date) = '$selected_date'";

    // Execute the update query
    if (mysqli_query($conn, $update_sql)) {
        // Retrieve the employee's schedule for the selected date
        $schedule_sql = "SELECT * FROM schedule WHERE employee_ID = '$employee_id' AND DATE(date) = '$selected_date'";
        $schedule_result = mysqli_query($conn, $schedule_sql);
        $schedule_row = mysqli_fetch_assoc($schedule_result);

        // Calculate time differences
        $first_in = strtotime($schedule_row['1st_in']);
        $first_out = strtotime($schedule_row['1st_out']);
        $second_in = strtotime($schedule_row['2nd_in']);
        $second_out = strtotime($schedule_row['2nd_out']);
        $third_in = strtotime($schedule_row['3rd_in']);
        $third_out = strtotime($schedule_row['3rd_out']);

        // Calculate net time differences
        $net_time1 = $first_out && $second_in ? ($second_in - $first_out) / 3600 : 0;
        $net_time2 = $second_out && $third_in ? ($third_in - $second_out) / 3600 : 0;
        $net_time3 = $third_out ? ($third_out - $third_in) / 3600 : 0;

        // Calculate total net time
        $total_net_time = $net_time1 + $net_time2 + $net_time3;

        // Update net time in the database
        $update_net_sql = "UPDATE schedule SET net = '$total_net_time' WHERE employee_ID = '$employee_id' AND DATE(date) = '$selected_date'";
        mysqli_query($conn, $update_net_sql);

        echo "$current_state updated successfully for $selected_date.";
    } else {
        echo "Error updating $current_state: " . mysqli_error($conn);
    }
}

/// Check if QR code content and selected date are provided
if (isset($_GET['qr_content']) && isset($_GET['selected_date'])) {
    // Get the QR content and selected date
    $qr_content = $_GET['qr_content'];
    $selected_date = $_GET['selected_date'];

    // Check if the QR content matches any employee ID
    $sql = "SELECT id FROM employee WHERE id = '$qr_content'";
    $result = mysqli_query($conn, $sql);

    // If a matching employee ID is found
    if (mysqli_num_rows($result) > 0) {
        $employee_row = mysqli_fetch_assoc($result);
        $employee_id = $employee_row['id'];

       // Retrieve the employee's schedule for the selected date
$schedule_sql = "SELECT * FROM schedule WHERE employee_ID = '$employee_id' AND DATE(date) = '$selected_date'";

        $schedule_result = mysqli_query($conn, $schedule_sql);

        // Check if the query executed successfully
        if ($schedule_result === false) {
            echo "Error: " . mysqli_error($conn);
        } else {
            // Check if any rows were returned
            if ($schedule_result !== null && mysqli_num_rows($schedule_result) > 0) {
                // Fetch the row
                $schedule_row = mysqli_fetch_assoc($schedule_result);

                // Determine the current state of the schedule based on existing data
                if (empty($schedule_row['1st_in'])) {
                    $current_state = '1st_in';
                } elseif (empty($schedule_row['1st_out'])) {
                    $current_state = '1st_out';
                } elseif (empty($schedule_row['2nd_in'])) {
                    $current_state = '2nd_in';
                } elseif (empty($schedule_row['2nd_out'])) {
                    $current_state = '2nd_out';
                } elseif (empty($schedule_row['3rd_in'])) {
                    $current_state = '3rd_in';
                } elseif (empty($schedule_row['3rd_out'])) {
                    $current_state = '3rd_out';
                } else {
                    echo "All schedule slots filled for $selected_date.";
                    exit; // Exit the script as all slots are filled
                }

                // Update the schedule based on the current state and selected date
                updateSchedule($conn, $employee_id, $current_state, $selected_date);
            } else {
                echo "No schedule found for employee $employee_id on $selected_date.";
            }
        }
    } else {
        echo "No employee found with the provided ID.";
    }
} else {
    echo "QR content or selected date not provided.";
}



mysqli_close($conn);


?>
