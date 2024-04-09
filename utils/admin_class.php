<?php
session_start();
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include '../config/db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);

		if (!$username || !$password)
			return "All fields are required.";

		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . $password . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			// return 3;
			// Unsuccessful login
			$query = $this->db->query("SELECT login_attempt , login_cd FROM users WHERE username = '" . $username . "'");

			// Fetch the result
			$result = $query->fetch_assoc();
			if (!$result)
				return "Username not found.";

			// ADD # MINUSTES
			function Cooldown()
			{
				// Get the current time
				$current_time = new DateTime();

				// Add 3 minutes to the current time
				$current_time->add(new DateInterval('PT3M'));

				// Get the timestamp of the new time
				$new_timestamp = $current_time->format('Y-m-d H:i:s');

				return $new_timestamp;
			}

			// GET THE MINUTS COOLDOWN
			function getMinutesRemaining($given_timestamp)
			{
				// Convert the given timestamp to a UNIX timestamp
				$given_unix_timestamp = strtotime($given_timestamp);

				// Get the current UNIX timestamp
				$current_unix_timestamp = time();

				// Calculate time difference in minutes
				$time_difference_minutes = ($given_unix_timestamp - $current_unix_timestamp) / 60;

				// Return remaining minutes
				return floor($time_difference_minutes);
			}

			// CHECK IF IT'S IN COOLDOWN
			if (getMinutesRemaining($result['login_cd']) > 0) {
				$this->db->query("UPDATE users SET login_attempt = 1 WHERE username = '" . $username . "'");
				return 'Your are in a ' . getMinutesRemaining($result['login_cd']) . ' min cooldown';
			}

			$loginAttempt = $result['login_attempt'];


			// HANDLE THE LOGIN ATTEMPT UPDATE THE TIME AND ATTEMPT
			if ($loginAttempt >= 5) {
				$cd =  Cooldown();
				$this->db->query("UPDATE users SET login_attempt = 1, login_cd = '" . $cd . "'WHERE username = '" . $username . "'");
			}

			// Increment the login attempt count
			$save = $this->db->query("UPDATE users SET login_attempt = " . ($loginAttempt + 1) . " WHERE username = '" . $username . "'");
			if ($save)
				return "Invalid username or password. Your atempt is " .	$loginAttempt;
		}
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $email . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location: ../login.php");
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '$password' ";
		$data .= ", type = '$type' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set " . $data);
		} else {
			$save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}
	function signup()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = 3";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
			// exit;
		}
		$save = $this->db->query("INSERT INTO users set " . $data);
		if ($save) {
			$qry = $this->db->query("SELECT * FROM users where username = '" . $email . "' and password = '" . md5($password) . "' ");
			if ($qry->num_rows > 0) {
				foreach ($qry->fetch_array() as $key => $value) {
					if ($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_' . $key] = $value;
				}
			}
			return 1;
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['setting_' . $key] = $value;
			}

			return 1;
		}
	}


	function save_employee()
	{
		extract($_POST);
		$data = " firstname='$firstname' ";
		$data .= ", middlename='$middlename' ";
		$data .= ", lastname='$lastname' ";
		$data .= ", suffix='$suffix' ";
		$data .= ", email='$email' ";
		$data .= ", emergencynumber='$emergencynumber' ";
		$data .= ", name='$name' ";
		$data .= ", status='$status' ";

		// if theres is no ID the request will recognized as new employee
		// otherwise the request will recognized as update employee based on Id passed in
		if (empty($id)) {
			$i = 1;
			while ($i == 1) {
				$e_num = date('Y') . '-' . mt_rand(1, 9999);
				$chk  = $this->db->query("SELECT * FROM employee where employee_no = '$e_num' ")->num_rows;
				if ($chk <= 0) {
					$i = 0;
				}
			}
			$data .= ", employee_no='$e_num' ";

			$save = $this->db->query("INSERT INTO employee set " . $data);
		} else {
			$save = $this->db->query("UPDATE employee set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_employee()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM employee where id = " . $id);
		if ($delete)
			return 1;
	}

	function save_department()
	{
		extract($_POST);
		$data = " name='$name' ";


		if (empty($id)) {
			$save = $this->db->query("INSERT INTO department set " . $data);
		} else {
			$save = $this->db->query("UPDATE department set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_department()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM department where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_position()
	{
		extract($_POST);
		$data = " name='$name' ";
		$data .= ", department_id = '$department_id' ";


		if (empty($id)) {
			$save = $this->db->query("INSERT INTO position set " . $data);
		} else {
			$save = $this->db->query("UPDATE position set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_position()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM position where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_allowances()
	{
		extract($_POST);
		$data = " allowance='$allowance' ";
		$data .= ", description = '$description' ";


		if (empty($id)) {
			$save = $this->db->query("INSERT INTO allowances set " . $data);
		} else {
			$save = $this->db->query("UPDATE allowances set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_allowances()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM allowances where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_employee_allowance()
	{
		extract($_POST);

		foreach ($allowance_id as $k => $v) {
			$data = " employee_id='$employee_id' ";
			$data .= ", allowance_id = '$allowance_id[$k]' ";
			$data .= ", type = '$type[$k]' ";
			$data .= ", amount = '$amount[$k]' ";
			$data .= ", effective_date = '$effective_date[$k]' ";
			$save[] = $this->db->query("INSERT INTO employee_allowances set " . $data);
		}

		if (isset($save))
			return 1;
	}
	function delete_employee_allowance()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM employee_allowances where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_deductions()
	{
		extract($_POST);
		$data = " deduction='$deduction' ";
		$data .= ", description = '$description' ";


		if (empty($id)) {
			$save = $this->db->query("INSERT INTO deductions set " . $data);
		} else {
			$save = $this->db->query("UPDATE deductions set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_deductions()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM deductions where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_employee_deduction()
	{
		extract($_POST);

		foreach ($deduction_id as $k => $v) {
			$data = " employee_id='$employee_id' ";
			$data .= ", deduction_id = '$deduction_id[$k]' ";
			$data .= ", type = '$type[$k]' ";
			$data .= ", amount = '$amount[$k]' ";
			$data .= ", effective_date = '$effective_date[$k]' ";
			$save[] = $this->db->query("INSERT INTO employee_deductions set " . $data);
		}

		if (isset($save))
			return 1;
	}
	function delete_employee_deduction()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM employee_deductions where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_employee_attendance()
	{
		extract($_POST);

		foreach ($employee_id as $k => $v) {
			$datetime_log[$k] = date("Y-m-d H:i", strtotime($datetime_log[$k]));
			$data = " employee_id='$employee_id[$k]' ";
			$data .= ", log_type = '$log_type[$k]' ";
			$data .= ", datetime_log = '$datetime_log[$k]' ";
			$save[] = $this->db->query("INSERT INTO attendance set " . $data);
		}

		if (isset($save))
			return 1;
	}
	function delete_employee_attendance()
	{
		extract($_POST);
		$date = explode('_', $id);
		$dt = date("Y-m-d", strtotime($date[1]));

		$delete = $this->db->query("DELETE FROM attendance where employee_id = '" . $date[0] . "' and date(datetime_log) ='$dt' ");
		if ($delete)
			return 1;
	}
	function delete_employee_attendance_single()
	{
		extract($_POST);


		$delete = $this->db->query("DELETE FROM attendance where id = $id ");
		if ($delete)
			return 1;
	}
	function save_payroll()
	{
		extract($_POST);
		$data = " date_from='$date_from' ";
		$data .= ", date_to = '$date_to' ";
		$data .= ", type = '$type' ";


		if (empty($id)) {
			$i = 1;
			while ($i == 1) {
				$ref_no = date('Y') . '-' . mt_rand(1, 9999);
				$chk  = $this->db->query("SELECT * FROM payroll where ref_no = '$ref_no' ")->num_rows;
				if ($chk <= 0) {
					$i = 0;
				}
			}
			$data .= ", ref_no='$ref_no' ";
			$save = $this->db->query("INSERT INTO payroll set " . $data);
		} else {
			$save = $this->db->query("UPDATE payroll set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_payroll()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payroll where id = " . $id);
		if ($delete)
			return 1;
	}
	function calculate_payroll8382u()
	{
		extract($_POST);
		$am_in = "08:00";
		$am_out = "12:00";
		$pm_in = "13:00";
		$pm_out = "17:00";
		$this->db->query("DELETE FROM payroll_items where payroll_id=" . $id);
		$pay = $this->db->query("SELECT * FROM payroll where id = " . $id)->fetch_array();
		$employee = $this->db->query("SELECT * FROM employee");
		if ($pay['type'] == 1)
			$dm = 22;
		else
			$dm = 11;
		$calc_days = abs(strtotime($pay['date_to'] . " 23:59:59")) - strtotime($pay['date_from'] . " 00:00:00 -1 day");
		$calc_days = floor($calc_days / (60 * 60 * 24));
		$att = $this->db->query("SELECT * FROM attendance where date(datetime_log) between '" . $pay['date_from'] . "' and '" . $pay['date_from'] . "' order by UNIX_TIMESTAMP(datetime_log) asc  ") or die(mysqli_error($conn));
		while ($row = $att->fetch_array()) {
			$date = date("Y-m-d", strtotime($row['datetime_log']));
			if ($row['log_type'] == 1 || $row['log_type'] == 3) {
				if (!isset($attendance[$row['employee_id'] . "_" . $date]['log'][$row['log_type']]))
					$attendance[$row['employee_id'] . "_" . $date]['log'][$row['log_type']] = $row['datetime_log'];
			} else {
				$attendance[$row['employee_id'] . "_" . $date]['log'][$row['log_type']] = $row['datetime_log'];
			}
		}
		$deductions = $this->db->query("SELECT * FROM employee_deductions where (`type` = '" . $pay['type'] . "' or (date(effective_date) between '" . $pay['date_from'] . "' and '" . $pay['date_from'] . "' ) ) ");
		$allowances = $this->db->query("SELECT * FROM employee_allowances where (`type` = '" . $pay['type'] . "' or (date(effective_date) between '" . $pay['date_from'] . "' and '" . $pay['date_from'] . "' ) ) ");
		while ($row = $deductions->fetch_assoc()) {
			$ded[$row['employee_id']][] = array('did' => $row['deduction_id'], "amount" => $row['amount']);
		}
		while ($row = $allowances->fetch_assoc()) {
			$allow[$row['employee_id']][] = array('aid' => $row['allowance_id'], "amount" => $row['amount']);
		}
		while ($row = $employee->fetch_assoc()) {
			$salary = $row['salary'];
			$daily = $salary / 22;
			$min = (($salary / 22) / 8) / 60;
			$absent = 0;
			$late = 0;
			$dp = 22 / $pay['type'];
			$present = 0;
			$net = 0;
			$allow_amount = 0;
			$ded_amount = 0;


			for ($i = 0; $i < $calc_days; $i++) {
				$dd = date("Y-m-d", strtotime($pay['date_from'] . " +" . $i . " days"));
				$count = 0;
				$p = 0;
				if (isset($attendance[$row['id'] . "_" . $dd]['log']))
					$count = count($attendance[$row['id'] . "_" . $dd]['log']);

				if (isset($attendance[$row['id'] . "_" . $dd]['log'][1]) && isset($attendance[$row['id'] . "_" . $dd]['log'][2])) {
					$att_mn = abs(strtotime($attendance[$row['id'] . "_" . $dd]['log'][2])) - strtotime($attendance[$row['id'] . "_" . $dd]['log'][1]);
					$att_mn = floor($att_mn  / 60);
					$net += ($att_mn * $min);
					$late += (240 - $att_mn);
					$present += .5;
				}
				if (isset($attendance[$row['id'] . "_" . $dd]['log'][3]) && isset($attendance[$row['id'] . "_" . $dd]['log'][4])) {
					$att_mn = abs(strtotime($attendance[$row['id'] . "_" . $dd]['log'][4])) - strtotime($attendance[$row['id'] . "_" . $dd]['log'][3]);
					$att_mn = floor($att_mn  / 60);
					$net += ($att_mn * $min);
					$late += (240 - $att_mn);
					$present += .5;
				}
			}
			$ded_arr = array();
			$all_arr = array();
			if (isset($allow[$row['id']])) {
				foreach ($allow[$row['id']] as $arow) {
					$all_arr[] = $arow;
					$net += $arow['amount'];
					$allow_amount += $arow['amount'];
				}
			}
			if (isset($ded[$row['id']])) {
				foreach ($ded[$row['id']] as $drow) {
					$ded_arr[] = $drow;
					$net -= $drow['amount'];
					$ded_amount += $drow['amount'];
				}
			}
			$absent = $dp - $present;
			$data = " payroll_id = '" . $pay['id'] . "' ";
			$data .= ", employee_id = '" . $row['id'] . "' ";
			$data .= ", absent = '$absent' ";
			$data .= ", present = '$present' ";
			$data .= ", late = '$late' ";
			$data .= ", salary = '$salary' ";
			$data .= ", allowance_amount = '$allow_amount' ";
			$data .= ", deduction_amount = '$ded_amount' ";
			$data .= ", allowances = '" . json_encode($all_arr) . "' ";
			$data .= ", deductions = '" . json_encode($ded_arr) . "' ";
			$data .= ", net = '$net' ";
			$save[] = $this->db->query("INSERT INTO payroll_items set " . $data);
		}
		if (isset($save)) {
			$this->db->query("UPDATE payroll set status = 1 where id = " . $pay['id']);
			return $data;
		}
	}

	function employee_scheduling_drag()
	{
		extract($_POST);
		// Use prepared statements to prevent SQL injection
		$stmt = $this->db->prepare("INSERT INTO schedule (employee_id, department_id, date) VALUES (?, ?, ?)");

		// Bind parameters
		$stmt->bind_param("iss", $employee_id, $department_id, $date);

		// Execute the query
		$success = $stmt->execute();

		// Check for errors and return appropriate response
		if ($success) {
			return "Data saved successfully";
		} else {
			// Log the error or return a meaningful message
			return "Error: " . $stmt->error;
		}
	}
	function move_employee_scheduling_drag()
	{
		extract($_POST);
		// Use prepared statements to prevent SQL injection
		// $stmt = $this->db->prepare("UPDATE schedule (employee_id, department_id) VALUES (?, ?)");
		$stmt = $this->db->prepare("UPDATE schedule SET department_id = ? WHERE employee_id = ? AND date = ?");

		// Bind parameters
		$stmt->bind_param("sis", $department_id, $employee_id,  $date);

		// Execute the query
		$success = $stmt->execute();

		// Check for errors and return appropriate response
		if ($success) {
			return "Data updated successfully";
		} else {
			// Log the error or return a meaningful message
			return "Error: " . $stmt->error;
		}
	}
	function remove_employee_schedule()
	{
		extract($_POST);
		// Use prepared statements to prevent SQL injection
		$stmt = $this->db->prepare("DELETE FROM schedule WHERE department_id = ? AND employee_id = ? AND date = ?");

		// Bind parameters
		$stmt->bind_param("sis", $department_id, $employee_id,  $date);

		// Execute the query
		$success = $stmt->execute();

		// Check for errors and return appropriate response
		if ($success) {
			return "Employee remove successfully";
		} else {
			// Log the error or return a meaningful message
			return "Error: " . $stmt->error;
		}
	}

	function setScheduleTime()
	{
		extract($_POST);
		// Assuming $Ids is an array of IDs

		// Construct the SQL statement
		$sql = "UPDATE schedule SET time_start = ?, time_end = ?, isOT = $isOT WHERE ID = ?";

		// Prepare the statement
		$stmt = $this->db->prepare($sql);

		foreach ($IDs as $id) {
			$stmt->bind_param("ssi", $time_start, $time_end, $id);
			// Execute the query
			$success = $stmt->execute();

			// Check for errors and return appropriate response
			if (!$success) {
				// Log the error or return a meaningful message
				return "Error: " . $stmt->error;
			}
		}

		// Return success message after all iterations
		return "Time set successfully for IDs: " . implode(', ', $IDs) . $isOT;
	}

	function employee_attendance()
	{
		// $column = ["1st_in", "1st_out", "1st_break", "2nd_in", "2nd_out", "2nd_break", "3rd_in", "3rd_out"];
		extract($_POST);

		// Sanitize and validate input data
		$column = $_POST['column'];
		$time = $_POST['time'];
		$id = $_POST['id'];

		$stmt = $this->db->prepare("UPDATE schedule SET  $column = ? WHERE employee_ID = ? AND date = ?");

		// Bind parameters
		$stmt->bind_param("sis", $time, $id, $date);

		// Execute the query
		$success = $stmt->execute();

		// Check for errors and return appropriate response
		if ($success) {
			// return "id: " . $id . " column: " . $column . " time: " . $time . " date: " . $date;
			return "Attendance Successfully Recorded";
		} else {
			// Log the error or return a meaningful message
			return "Error: " . $stmt->error;
		}
	}



	function calculate_payroll() {
		// Extract POST data
		extract($_POST);
		$this->db->query("DELETE FROM payroll_items WHERE payroll_id = $id");
	
		// Fetch payroll data
		$pay = $this->db->query("SELECT * FROM payroll WHERE id = $id")->fetch_array();
		$start_date = $pay["date_from"];
		$end_date = $pay["date_to"];
	
		// Fetch attendance records within the specified date range
		$att = $this->db->query("SELECT *, net FROM schedule WHERE date(date) BETWEEN '$start_date' AND '$end_date'");

		// Function to calculate salary for an employee based on attendance records
		function calculateSalary($employeeID, $records) {
    $totalNetSalary = 0;

    foreach ($records as $record) {
        if ($record['employee_ID'] == $employeeID) {
            // Add net salary to total
            $totalNetSalary += $record['net'];
        }
    }

    return $totalNetSalary;
}

	
		// Fetch all attendance records
		$allRecords = $att->fetch_all(MYSQLI_ASSOC);
	
		if (!empty($allRecords)) {
			// Array to store total net salary for each employee
			$employeeNetSalaries = array();
		
			// Loop through all records to calculate total net salary for each employee
			foreach ($allRecords as $row) {
				$employeeID = $row['employee_ID'];
		
				// Check if the employee ID already exists in the array
				if (!isset($employeeNetSalaries[$employeeID])) {
					// If not, initialize the total net salary for this employee
					$employeeNetSalaries[$employeeID] = 0;
				}
		
				// Add the net salary to the total net salary for this employee
				$employeeNetSalaries[$employeeID] += $row['net'];
			}
	
			// Begin transaction
			$this->db->begin_transaction();
	
			try {
				// Insert new data into payroll_items table
				foreach ($employeeNetSalaries as $employeeID => $totalSalary) {
					// Deductions for PhilHealth and SSS for days 1-15
					$deduction_philhealth = 0;
					$deduction_sss = 0;
					$start_day = date('d', strtotime($start_date));
					$end_day = date('d', strtotime($end_date));
	
					if ($start_day <= 15 && $end_day >= 1) {
						$deduction_philhealth = 250;
						$deduction_sss = 180;
					}
	
					// Deduct the specified amounts
					$totalSalary -= $deduction_philhealth + $deduction_sss;
	
					// Prepare data for inserting into payroll_items table
					$data = " payroll_id = '" . $pay['id'] . "' ";
					$data .= ", employee_id = '" . $employeeID . "' ";
					$data .= ", salary = '$totalSalary' "; // Inserting total salary instead of net
					$data .= ", deductions_philhealth = '$deduction_philhealth' ";
					$data .= ", deductions_sss = '$deduction_sss' ";
					$data .= ", net = '$totalSalary' "; // Net salary after deductions
	
					// Insert data into payroll_items table
					$this->db->query("INSERT INTO payroll_items SET " . $data);
				}
	
				// Delete previous payroll_items records
				
				// Commit transaction
				$this->db->commit();
			} catch (Exception $e) {
				// Rollback transaction on error
				$this->db->rollback();
				throw $e;
			}
	
			// Update payroll status to indicate it's calculated
			$this->db->query("UPDATE payroll SET status = 1 WHERE id = " . $pay['id']);
			return 1;
		}
	}
	
	


	function get_employee_sched()
	{

		$query = $this->db->query("SELECT date FROM schedule");

		// Fetch all rows from the result set as an associative array
		$result = $query->fetch_all(MYSQLI_ASSOC);

		// Return the fetched result as JSON
		header('Content-Type: application/json');
		return json_encode($result);
	}
}
