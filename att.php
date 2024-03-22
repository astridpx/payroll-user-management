<?php include('./config/db_connect.php') ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <br>
        <br>
        <div class="card">
            <div class="card-header">
                <span><b>March 1, 2024</b></span>
				<button class="btn btn-sm col-md-3 float-right" type="button" id="new_attendance_btn" style="background-color: #d04848; color: white; padding: 5px 10px;"><span class="fa fa-plus"></span> Add Attendance</button>


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
                            <?php
                            $att = $conn->query("SELECT a.*, e.employee_no, concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename FROM attendance a inner join employee e on a.employee_id = e.id order by UNIX_TIMESTAMP(datetime_log) asc  ") or die(mysqli_error($conn));
                            $lt_arr = array(1 => "Time-in AM", 2 => "Time-out AM", 3 => "Time-in PM", 4 => "Time-out PM");
                            while ($row = $att->fetch_array()) {
                                $date = date("Y-m-d", strtotime($row['datetime_log']));
                                $attendance[$row['employee_id'] . "_" . $date]['details'] = array("eid" => $row['employee_id'], "name" => $row['ename'], "eno" => $row['employee_no'], "date" => $date);
                                if ($row['log_type'] == 1 || $row['log_type'] == 3) {
                                    if (!isset($attendance[$row['employee_id'] . "_" . $date]['log'][$row['log_type']]))
                                        $attendance[$row['employee_id'] . "_" . $date]['log'][$row['log_type']] = array('id' => $row['id'], "date" =>  $row['datetime_log']);
                                } else {
                                    $attendance[$row['employee_id'] . "_" . $date]['log'][$row['log_type']] = array('id' => $row['id'], "date" =>  $row['datetime_log']);
                                }
                            }
                            foreach ($attendance as $key => $value) {
                            ?>



                        
							
    <td><?php echo "Lubuguin"; ?></td>
	<td><?php echo " 08:00:00"; ?></td>

    <td>
    <i class="fas fa-plus-circle" style="margin-left: 5px; cursor: pointer;" onclick="openCamera()"></i>
    <span id="timeDisplay"></span>
    <div id="cameraModal" class="modal" style="display: none;">
        <div class="modal-content" style="width: 300px; height: 300px; padding: 20px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <span class="close" onclick="closeCamera()">&times;</span>
            <h3 style="margin-top: 0;">Scan QR Code</h3>
          
            <video id="cameraFeed" style="width: 100%; height: 100%;"></video>
        </div>
    </div>
</td>




    <td><i class="fas fa-minus-circle" style="margin-right: 10px;"></i><?php echo "11:00"; ?></td>
    <td></td> 



    <td><i class="fas fa-plus-circle " style="margin-right: 10px;"></i></td>




    
    <td><i class="fas fa-minus-circle " style="margin-right: 10px;"></i><?php echo "13:00"; ?></td>
    <td></td> <!-- Breaktime icon -->
    
    <td><i class="fas fa-plus-circle " style="margin-right: 10px;"></i></td>


    
    <td><i class="fas fa-minus-circle " style="margin-right: 10px;"></i><?php echo "18:00 "; ?></td>
    <td>
        <?php 
            $status = "Present"; // Example status pero kung kayang i lagay sa backend G!
            if ($status == "Present") {
                echo '<i class="fas fa-circle text-success"></i>'; // Green dot for present
            } else {
                echo '<i class="fas fa-circle text-danger"></i>'; // Red dot for absent
            }
        ?>
    </td>
    <td><?php echo "560"; ?></td>
    <td>
        <center>
            <button class="btn btn-sm btn-outline-danger remove_attendance" data-id="1" type="button"><i class="fa fa-trash"></i></button>
        </center>
    </td>
</tr>

                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="background-color: #d04848; height: 200px; width:200px;" id="qr-reader"  width="60px"></div>


<style>
    th, td {
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
</style>

<!-- HTML QRCODE https://github.com/mebjas/html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    var lastResult, countResults = 0;

   function onScanSuccess(decodedText, decodedResult) {
    if (decodedText !== lastResult) {
        ++countResults;
        lastResult = decodedText;
        // Handle on success condition with the decoded message.
        console.log(`Scan result ${decodedText}`, decodedResult);
        alert(`Scan result ${decodedText}`, decodedResult);
    }
}

var html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess);
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




<!-- <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script> -->