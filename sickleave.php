<?php 
include('./config/db_connect.php');
include('./includes/header.php');

// Fetch all employees from the database
$sql = "SELECT * FROM employee";
$result = mysqli_query($conn, $sql);
$employees = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $employee_id = $_POST['employee_id'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $reason = $_POST['reason'];

    // Insert the data into the database
    $sql = "INSERT INTO sick_leave (employee_no, lastname, email, date_from, date_to, reason) 
            VALUES ('$employee_id', '$lastname', '$email', '$date_from', '$date_to', '$reason')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: "Success!",
                text: "  Request Successfully Submitted.",
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
        });
        </script>';
    } else {
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "Something Went Wrong. Please try again",
        });
        </script>';
    }

    mysqli_close($conn);
} 
?>

<div class="container-fluid">
    <div class="col-lg-10 offset-lg-1">
        <br />
        <br />
        <div class="card">
            <div class="card-header">
                <!-- Request form header -->
                <h5 class="card-title">Sick Leave Request Form</h5>
            </div>
            <div class="card-body">
                <!-- Form -->
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="employee">Employee ID</label>
                            <select class="form-control" id="employee_id" name="employee_id" required>
    <option value="">Select Employee ID</option>
    <?php foreach ($employees as $employee) : ?>
        <option value="<?php echo $employee['id']; ?>"><?php echo $employee['employee_no'] . ' - ' . $employee['lastname']; ?></option>
    <?php endforeach; ?>
</select>

                        </div>

                        <div class="form-group col-md-4">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required readonly>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_from">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" required>
                    </div>
                    <div class="form-group">
                        <label for="date_to">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" required>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Leave</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('employee_id').addEventListener('change', function() {
        var selectedEmployeeId = this.value;
        var employees = <?php echo json_encode($employees); ?>;
        var selectedEmployee = employees.find(function(employee) {
            return employee.id == selectedEmployeeId;
        });
        if (selectedEmployee) {
            document.getElementById('email').value = selectedEmployee.email;
            document.getElementById('lastname').value = selectedEmployee.lastname;
        } else {
            document.getElementById('email').value = '';
            document.getElementById('lastname').value = '';
        }
    });
</script>
