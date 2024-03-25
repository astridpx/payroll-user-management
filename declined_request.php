<?php
include('./config/db_connect.php');
include('./includes/header.php');

// Fetch data from the "sick_leave" table where status is equal to 'approved'
$query = "SELECT * FROM sick_leave WHERE status = 'declined'";
$result = mysqli_query($conn, $query); 
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <br />
        <br />
        <div class="card">
            <div class="card-header">
                <span><b>Employee List</b></span>
            </div>
            <div class="card-body">
                <table id="table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Employee No</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Date From</th>
                            <th>Date To</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['employee_no'] ?></td>
                            <td><?= $row['lastname'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['date_from'] ?></td>
                            <td><?= $row['date_to'] ?></td>
                            <td><?= $row['reason'] ?></td>
                            <td>
                                <?php
                                if (empty($row['status'])) {
                                    echo "Pending";
                                } else {
                                    echo $row['status'];
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
