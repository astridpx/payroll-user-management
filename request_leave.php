<?php
include('./config/db_connect.php');
include('./includes/header.php');

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

// Function to send email
function RequestMailer($email, $lname, $remark, $status)
{
    try {
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'trickster2214@gmail.com';
        $mail->Password = 'xmeb nxvf xjmc dckh';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('cjvaldez151@gmail.com', 'SICKLEAVE REQUEST UPDATES');
        $mail->addAddress($email, $lname); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Sickleave Update';
        $mail->Body = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sickleave Confirmation</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            line-height: 1.2;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
        }

        p {
            margin-bottom: 10px;
        }

        .appointment-number {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Sickleave Updates</h2>
        <p>Dear ' . $lname . ',</p>
        <p>Your Sickleave Request is Updated.</p>

        <p><strong>Sickleave Status : </strong> ' . $status . '</p>
        <p><strong>Remark : </strong> ' . $remark . '</p>

        <p><strong>Location:</strong> SVCC Complex Mamatid, City of Cabuyao, Laguna</p>

        <div class="footer">
            <p>This is the status of your sickleave request. If you have any questions. dont bother me! .</p>
        </div>
        <div class="footer">
            <p>BSIT 4A2</p>
        </div>
    </div>
</body>

</html>';
        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $remark = $_POST['remark'];
    $status = $_POST['status'];

    // Update database
    $updateQuery = "UPDATE sick_leave SET remark='$remark', status='$status' WHERE id='$id'";
    $updateResult = mysqli_query($conn, $updateQuery);

    // Check if update was successful
    if ($updateResult) {
        // Fetch employee data
        $fetchQuery = "SELECT * FROM sick_leave WHERE id='$id'";
        $fetchResult = mysqli_query($conn, $fetchQuery);
        $employeeData = mysqli_fetch_assoc($fetchResult);
        $email = $employeeData['email'];
        $lname = $employeeData['lastname']; // Changed this to last name

        // Send email
        $mailSent = RequestMailer($email, $lname, $remark, $status); // Changed $fname to $lname
        if ($mailSent) {
            // Email sent successfully
            echo '<script>
                    Swal.fire({
                        title: "Success!",
                        text: "Updating Request Successfully.",
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
                </script>';
        } else {
            // Failed to send email
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Updating Request Successful but failed to send email notification.",
                    });
                </script>';
        }
    } else {
        // Failed to update database
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Something Went Wrong. Please try again",
                });
            </script>';
    }
}

// Fetch data from the "sick_leave" table
$query = "SELECT * FROM sick_leave";
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
                            <th>Action</th>
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


                            <td>
                                <button class="btn btn-primary edit-btn" data-toggle="modal" data-target="#editModal<?= $row['id'] ?>">Edit</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Edit Modals -->
<?php mysqli_data_seek($result, 0); // Reset the result pointer ?>
<?php while ($row = mysqli_fetch_assoc($result)): ?>
<div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Updating</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for updating data -->
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <!-- Remark Message Input -->
                    <div class="form-group">
                        <label for="remark<?= $row['id'] ?>">Remark:</label>
                        <textarea class="form-control" id="remark<?= $row['id'] ?>" name="remark" placeholder="Enter remark"></textarea>
                    </div>
                    <!-- Dropdown for Approving/Declining -->
                    <div class="form-group">
                        <label for="status<?= $row['id'] ?>">Status:</label>
                        <select class="form-control" id="status<?= $row['id'] ?>" name="status">
                            <option value="approved">Approved</option>
                            <option value="declined">Declined</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Submit button to submit the form -->
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>


<!-- Add Employee Modal -->
<div class="modal fade" id="addEmpModal" tabindex="-1" role="dialog" aria-labelledby="addEmpModalLabel" aria-hidden="true">
    <!-- Add Employee Modal content goes here -->
</div>

<!-- Bootstrap and jQuery CDNs -->
<!-- Ensure you have included Bootstrap and jQuery libraries -->

<!-- Bootstrap JS bundle -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
