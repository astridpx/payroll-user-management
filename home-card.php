<?php include './config/db_connect.php'?>

<?php

// TOTAL NUMBER OF EMPLOYEE

$sql = "SELECT COUNT(*) AS total_employees FROM employee";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = $result->fetch_assoc()) {
    $total_employees = $row["total_employees"];
  }
} else {
  $total_employees = 0;
}

// SICK LEAVE CARDS

$sql = "SELECT COUNT(*) AS total_sick_leave_requests FROM sick_leave";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

  while ($row = $result->fetch_assoc()) {
    $total_sick_leave_requests = $row["total_sick_leave_requests"];
  }
} else {
  $total_sick_leave_requests = 0;
}

// TOTAL SALARY CARDS

$sql = "SELECT SUM(salary) AS total_salary FROM payroll_items";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = $result->fetch_assoc()) {
    $total_salary = $row["total_salary"];
  }
} else {
  $total_salary = 0;
}

// CREW TODAY DISPLAY

$current_date = date("Y-m-d");
$sql = "SELECT COUNT(*) AS total_crew_today FROM schedule WHERE DATE(date) = '$current_date'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = $result->fetch_assoc()) {
    $total_crew_today = $row["total_crew_today"];
  }
} else {
  $total_crew_today = 0;
}





?>



<style>

</style>

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />

<div class="container-fluid  p-2">

	<div class="row">
		<div class="col-lg-12">

		

		</div>
	</div>

	<br>
<!--
	<div class="row mt-2">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<?php echo "Welcome back " . $_SESSION['login_name'] . "!"  ?>
				</div>
			</div>
		</div>
	</div>
	<br>
-->
	
	<div class="row">


             
<!-- EMPLOYEE -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card border-left-primary shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-s font-weight-bold text-primary text-uppercase mb-1">
            EMPLOYEE
          </div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php echo $total_employees; ?>
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-user fa-2x text-gray-600"></i>
        </div>
      </div>
    </div>
  </div>
</div>

              <!-- Total Salary -->
				<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-success shadow h-100 py-2">
					<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
						<div class="text-s font-weight-bold text-success text-uppercase mb-1">
							Total Salary
						</div>
						<div class="h5 mb-0 font-weight-bold text-gray-800">
							P <?php echo number_format($total_salary, 2); ?>
						</div>
						</div>
						<div class="col-auto">
						<i class="fas fa-dollar-sign fa-2x text-gray-600"></i>
						</div>
					</div>
					</div>
				</div>
				</div>

						<!-- Crew Today -->
				<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-info shadow h-100 py-2">
					<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
						<div class="text-xs font-weight-bold text-info text-uppercase mb-1">
							Crew Today
						</div>
						<div class="row no-gutters align-items-center">
							<div class="col-auto">
							<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
								<?php echo $total_crew_today; ?>
							</div>
							</div>
							<div class="col">
							<div class="progress progress-sm mr-2">
								<div class="progress-bar bg-info" role="progressbar" style="width: <?php echo ($total_crew_today * 100 / 5); ?>%" aria-valuenow="<?php echo $total_crew_today; ?>" aria-valuemin="0" aria-valuemax="20"></div>
							</div>
							</div>
						</div>
						</div>
						<div class="col-auto">
						<i class="fas fa-clipboard-list fa-2x text-gray-600"></i>
						</div>
					</div>
					</div>
				</div>
				</div>

				<!-- total Requests Card Example -->
					<div class="col-xl-3 col-md-6 mb-4">
					<div class="card border-left-warning shadow h-100 py-2">
						<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
								SickLeave Request
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?php echo $total_sick_leave_requests; ?>
							</div>
							</div>
							<div class="col-auto">
							<i class="fas fa-comments fa-2x text-gray-600"></i>
							</div>
						</div>
						</div>
					</div>
					</div>
            </div>

			<!--END OF CARDS TABLE -->

	<!--CHARTS  -->
	<div style="column-gap: 8px;" class="d-flex  mt-3 ">
		<div style="width: 59%; background-color: #fff; position: relative; height:60vh" class="flex-1 rounded-2">
			<canvas id="chart1" class=""></canvas>
		</div>
		<div style=" background-color: #fff; position: relative; height:60vh" class="flex-fill rounded-2">
			<canvas id="chart2" class=""></canvas>
		</div>
	</div>

</div>
<script>
	const ctx1 = $('#chart1');
	const ctx2 = $('#chart2');

	new Chart(ctx1, {
		type: 'bar',
		data: {
			labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
			datasets: [{
				label: '# of Votes',
				data: [12, 19, 3, 5, 20, 3],
				borderWidth: 1,
			}]
		},
		options: {
			aspectRatio: 16 / 9,
			scales: {
				x: {
					beginAtZero: true,
					grid: {
						display: false, // Disable vertical grid lines for the x-axis / nakahiga
					},
					border: {
						display: false
					},
				},
				y: {
					beginAtZero: true,
					border: {
						display: false
					},
				},
			},
			plugins: {
				legend: {
					display: true,
					labels: {
						usePointStyle: true,
						pointStyle: "circle",
						boxWidth: 8,
						boxHeight: 8,
					},
				},
				title: {
					display: true,
					text: "Total Profit",
					font: {
						size: 19,
					},
				},
			}

		},
	});

	new Chart(ctx2, {
		type: 'pie',
		data: {
			labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
			datasets: [{
				label: '# of Votes',
				data: [12, 19, 3, 5, 20, 3],
				borderWidth: 1,
			}]
		},
		options: {
			// aspectRatio: 16 / 9,
			plugins: {
				legend: {
					display: true,
					labels: {
						usePointStyle: true,
						pointStyle: "circle",
						boxWidth: 8,
						boxHeight: 8,
					},
				},
				title: {
					display: true,
					text: "Total Profit",
					font: {
						size: 19,
					},
				},
			}

		},
	});
</script>