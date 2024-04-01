<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    tr,
    td,
    th {
        border: 1px solid black
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }
</style>

<?php include('./config/db_connect.php'); ?>

<?php
$date = $_GET['date'];

// FETCH SCHED WITH DEPARTMENT AND EMPLOYEE NAME
$sched = $conn->query("SELECT  s.*,e.*, CASE
WHEN  LENGTH(s.time_start) = 0 OR  LENGTH(s.time_end) = 0  THEN  CONCAT(s.time_start,'',s.time_end) 
WHEN LENGTH(s.time_start) != 0 AND  LENGTH(s.time_end) != 0 THEN CONCAT(s.time_start,'-',s.time_end)  END AS time, d.name AS department, d.id AS departmentID, s.ID AS sched_ID, CONCAT(e.firstname,' ',e.lastname) AS fullname FROM department d lEFT JOIN schedule s ON d.id = s.department_id AND s.date = '$date' LEFT JOIN employee e ON s.employee_id = e.id  ORDER BY departmentID ASC;");
$deptArray = $sched->fetch_all(MYSQLI_ASSOC);


// CREATE NEW STRUCTURE OF ARRAY AND SORT THE DATA      
$depSched = [];

foreach ($deptArray  as $newEntry) {
    $found = false;

    foreach ($depSched as &$data) {
        if ($data["dep_id"] === $newEntry["departmentID"]) {
            // if (!isset($data["employee"]) || !is_array($data["employee"])) {
            //     $data["employee"] = [];
            // }

            // id dep_id found insert this on the employee
            $data["employee"][] = [
                "fullname" => $newEntry["fullname"],
                "emp_id" => $newEntry['employee_ID'],
                "sid" => $newEntry["ID"],
                "time" => $newEntry["time"],
                "emp_no" => $newEntry["employee_no"],
                "time_start" => $newEntry["time_start"],
                "time_end" => $newEntry["time_end"],

            ];
            $data["employee"] = array_values($data["employee"]); // Reindex the array

            $found = true;
            break;
        }
    }

    if (!$found) {
        // If dep_id not found, add a new entry
        $depSched[] = [
            "dep_id" => $newEntry["departmentID"],
            "dep_name" => $newEntry["department"],
            "employee" => [
                [
                    "fullname" => $newEntry["fullname"],
                    "emp_id" => $newEntry['employee_ID'],
                    "sid" => $newEntry["ID"],
                    "time" => $newEntry["time"],
                    "emp_no" => $newEntry["employee_no"],
                    "time_start" => $newEntry["time_start"],
                    "time_end" => $newEntry["time_end"],
                ]
            ]
        ];
    }
}

?>
<div>
    <h2 class="text-center">Schedule - </h2>
    <hr>
</div>
<table>
    <thead>
        <tr>
            <th class="text-center">Employee ID</th>
            <th class="text-center">Employee Name</th>
            <th class="text-center">Departmen</th>
            <th class="text-center">Time In</th>
            <th class="text-center">Time Out</th>
        </tr>
    </thead>
    <tbody>
        <?php

        foreach ($depSched  as $sched) {

            foreach ($sched["employee"]  as $emp) {
                if ($emp["emp_id"] !== null) {
        ?>
                    <tr>
                        <td><?php echo $emp["emp_no"] ?></td>
                        <td><?php echo $emp['fullname'] ?></td>
                        <td class="text-right"><?php echo $sched["dep_name"] ?></td>
                        <td class="text-right"><?php echo $emp['time_start'] ? $emp['time_start'] : "" ?></td>
                        <td class="text-right"><?php echo $emp['time_end'] ? $emp['time_end'] : "" ?></td>
                    </tr>
        <?php
                }
            }
        }
        ?>
    </tbody>
</table>