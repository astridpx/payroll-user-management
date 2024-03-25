<?php include('./config/db_connect.php') ?>
<?php

// Define start and end dates for the range
$start_date = '2024-03-01';
$end_date = '2024-03-16';

$sql = "SELECT * FROM schedule WHERE date(date) BETWEEN '$start_date' AND '$end_date'";
// Execute the query
$result = $conn->query($sql);

$hourly_rate = 59; // 59 pesos per hour


// Define the function to calculate salary
function calculateSalary($employeeID, $records)
{
    global $hourly_rate;

    $totalSalary = 0;

    foreach ($records as $record) {
        if ($record['employee_ID'] == $employeeID) {
            // Calculate the duration between time_start and time_end
            $startTime = strtotime($record['time_start']);
            $endTime = strtotime($record['time_end']);
            $durationHours = ($endTime - $startTime) / 3600; // Convert to hours

            // Calculate salary for this record
            $salary = $durationHours * $hourly_rate;

            // Add salary to total
            $totalSalary += $salary;
        }
    }

    return $totalSalary;
}

// Fetch all records once
$allRecords = $result->fetch_all(MYSQLI_ASSOC);

// Check if there are any records
if (!empty($allRecords)) {
    // Create an associative array to store total salary for each employee
    $employeeSalaries = array();

    // Loop through all records to calculate total salary for each employee
    foreach ($allRecords as $row) {
        $employeeID = $row['employee_ID'];

        // Check if the employee ID already exists in the array
        if (!isset($employeeSalaries[$employeeID])) {
            // If not, initialize the total salary for this employee
            $employeeSalaries[$employeeID] = 0;
        }

        // Calculate the salary for this record
        $startTime = strtotime($row['time_start']);
        $endTime = strtotime($row['time_end']);
        $durationHours = ($endTime - $startTime) / 3600; // Convert to hours
        $salary = $durationHours * $hourly_rate;

        // Add the salary to the total salary for this employee
        $employeeSalaries[$employeeID] += $salary;
    }

    // Output the total salary for each employee
    foreach ($employeeSalaries as $employeeID => $totalSalary) {
        echo "Total Salary for Employee ID $employeeID: $" . number_format($totalSalary, 2) . "<br>";
    }
} else {
    echo "No records found.";
}


// Convert 1 hour into time
// $one_hour = date('H:i', strtotime('1 hour')); // Output: 01:00

// Convert 30 minutes into time
// $thirty_minutes = date('H:i', strtotime('30 minutes')); // Output: 00:30

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <input type="time" id="time">
    <button onclick="getTime()">Get Time</button>

    <script>
        function getTime() {
            // Get the input element
            var timeInput = document.getElementById('time');

            // Get the value of the input
            var selectedTime = timeInput.value;

            // Log the value
            console.log("Selected time:", selectedTime);
        }
    </script>
</body>

</html>