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
            // Redirect to the PHP file passing the QR content as a parameter
            window.location.href = 'qr_scan.php?qr_content=' + decodedText;
        }

        // Attach event handler for successful scan
        html5QrcodeScanner.render(onScanSuccess);

        // Listen for modal close event
        // Stop the QR code scanner when the modal is closed
        window.addEventListener('beforeunload', function () {
            html5QrcodeScanner.clear();
        });
    </script>

    <?php
    include('./config/db_connect.php');

    // Check if QR code content is provided
    if (isset($_GET['qr_content'])) {
        // Get the QR content
        $qr_content = $_GET['qr_content'];

        // Check if the QR content matches any employee ID
        $sql = "SELECT id FROM employee WHERE id = '$qr_content'";
        $result = mysqli_query($conn, $sql);

        // If a matching employee ID is found
        if (mysqli_num_rows($result) > 0) {
            $employee_id = $qr_content;
            $current_time = date('h:i A'); // Get current time

            // Update the 1st_in time for the employee
            $update_sql = "UPDATE schedule SET 1st_in = '$current_time' WHERE employee_ID = '$employee_id'";
            if (mysqli_query($conn, $update_sql)) {
                echo "1st_in updated successfully.";
            } else {
                echo "Error updating 1st_in: " . mysqli_error($conn);
            }
        } else {
            echo "No employee found with the provided ID.";
        }
    } else {
        echo "QR content not provided.";
    }

    mysqli_close($conn);
    ?>
</body>
</html>
