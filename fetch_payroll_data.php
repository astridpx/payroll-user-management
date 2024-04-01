<?php
// Include your database connection file
include './config/db_connect.php';

// QUERY T FETCH PAYROL BY GROUP OF MONTHLY ONLY
$query = "SELECT 
            DATE_FORMAT(date_created, '%b') AS month,
            SUM(salary) AS total_salary
          FROM 
            payroll_items
          GROUP BY 
            DATE_FORMAT(date_created, '%b')
          ORDER BY 
            FIELD(DATE_FORMAT(date_created, '%b'), 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$payroll_data = array_fill(0, 12, 0); 

while ($row = mysqli_fetch_assoc($result)) {

    switch ($row['month']) {
        case 'Jan':
            $payroll_data[0] = $row['total_salary'];
            break;
        case 'Feb':
            $payroll_data[1] = $row['total_salary'];
            break;
        case 'Mar':
            $payroll_data[2] = $row['total_salary'];
            break;
        case 'Apr':
            $payroll_data[3] = $row['total_salary'];
            break;
        case 'May':
            $payroll_data[4] = $row['total_salary'];
            break;
        case 'Jun':
            $payroll_data[5] = $row['total_salary'];
            break;
        case 'Jul':
            $payroll_data[6] = $row['total_salary'];
            break;
        case 'Aug':
            $payroll_data[7] = $row['total_salary'];
            break;
        case 'Sep':
            $payroll_data[8] = $row['total_salary'];
            break;
        case 'Oct':
            $payroll_data[9] = $row['total_salary'];
            break;
        case 'Nov':
            $payroll_data[10] = $row['total_salary'];
            break;
        case 'Dec':
            $payroll_data[11] = $row['total_salary'];
            break;
    }
}

// Close database connection
mysqli_close($conn);


$response = array('payroll_data' => $payroll_data);


header('Content-Type: application/json');
echo json_encode($response);
?>
