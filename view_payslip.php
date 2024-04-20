<?php include './config/db_connect.php' ?>

<?php
$payroll = $conn->query("SELECT p.*, concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename, e.employee_no FROM payroll_items p INNER JOIN employee e ON e.id = p.employee_id WHERE p.id=" . $_GET['id']);
$payroll_data = $payroll->fetch_array();
foreach ($payroll_data as $key => $value) {
    $$key = $value;
}
$pay = $conn->query("SELECT * FROM payroll WHERE id = " . $payroll_id)->fetch_array();
$pt = array(1 => "Monthly", 2 => "Semi-Monthly");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .payroll-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .payroll-heading {
            color: crimson;
            font-weight: bold;
        }
        .payroll-button {
            background-color: crimson;
            border-color: crimson;
        }
        .payroll-button:hover {
            background-color: #a10c27;
            border-color: #a10c27;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="payroll-card">
                    <h5 class="mb-4 payroll-heading">Payroll Details</h5>
                    <div class="row">
                        <div class="col-md-6">
							<p><strong>Payroll Ref:</strong> <?php echo $pay['ref_no'] ?></p>
                            <p><strong>Employee ID:</strong> <?php echo $employee_no ?></p>
                            <p><strong>Name:</strong> <?php echo ucwords($ename) ?></p>
                            
                            <p><strong>Payroll Range:</strong> <?php echo date("M d, Y", strtotime($pay['date_from'])) . " - " . date("M d, Y", strtotime($pay['date_to'])) ?></p>
                            <p><strong>Payroll Type:</strong> <?php echo $pt[$pay['type']] ?></p>
                        </div>
                        <div class="col-md-6">
                            
                            <p><strong>Tardy/Undertime (mins):</strong> <?php echo $late ?></p>
                            <p><strong>PhilHealth Deduction:</strong> <?php echo number_format($deductions_philhealth, 2) ?></p>
                            <p><strong>SSS Deduction:</strong> <?php echo number_format($deductions_sss, 2) ?></p>
                            <p><strong>PAGIBIG Deduction:</strong> <?php echo number_format($deduction_pagibig, 2) ?></p>
                            <p><strong>Net Pay:</strong> <?php echo number_format($net, 2) ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="text-end">
                        <button class="btn btn-primary payroll-button" onclick="closeTab();">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function closeTab() {
            window.close();
        }
    </script>
</body>
</html>
