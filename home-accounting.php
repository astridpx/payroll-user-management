<?php
include './config/db_connect.php';

// SICKLEAVE CHART
$query = "SELECT status, COUNT(*) AS count FROM sick_leave GROUP BY status";
$result = mysqli_query($conn, $query);

$status_counts = array(
    'approved' => 0,
    'declined' => 0,
    'pending' => 0
);


while ($row = mysqli_fetch_assoc($result)) {
    $status_counts[$row['status']] = $row['count'];
}
?>

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


// TOTAL NUMBER OF EMPLOYEE

$sql = "SELECT COUNT(*) AS total_station FROM department";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = $result->fetch_assoc()) {
    $total_station = $row["total_station"];
  }
} else {
  $total_station = 0;
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
  <div class="card border-left-primary shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-s font-weight-bold text-primary text-uppercase mb-1">
            STATION
          </div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php echo $total_station; ?>
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-building fa-2x text-gray-600"></i>
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
<div class="container mt-3">
  <div class="row gx-3">
    <div class="col-md-7" style="background-color: #ffff; border-radius: 8px; box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.2);">
      <canvas id="chart1" style="height: 60vh;"></canvas>
    </div>
    <div class="col-md-auto"></div> <!-- Short gap between the columns -->
    <div class="col-md-4" style="background-color: #ffff; border-radius: 8px; box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.2);">
      <canvas id="chart2" style="height: 60vh;"></canvas>
    </div>
  </div>
</div>





</div>

<!--PAYROL HISTORY CHART-->
<script>
    // Function to fetch data from the server
    function fetchData() {
        $.ajax({
            url: 'fetch_payroll_data.php', 
            type: 'GET',
            dataType: 'json',
            success: function(response) {
            
                updateChart(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function updateChart(data) {
        const ctx1 = $('#chart1');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: '# Payroll',
                    data: data.payroll_data,
                    backgroundColor: generateGradientColors(data.payroll_data),
                    borderWidth: 1,
                }]
            },
            options: {
                aspectRatio: 16 / 9,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: false,
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
                        text: "Payroll History",
                        font: {
                            size: 19,
                        },
                    },
                },
                animation: {
                    duration: 2000, 
                }
            }
        });
    }


    function generateGradientColors(data) {
        const colors = [];
        const max = Math.max(...data);
        const min = Math.min(...data);
        const range = max - min;
        data.forEach(value => {
            const normalizedValue = (value - min) / range;
            const color = `rgba(208, 60, 60, ${normalizedValue})`; 
            colors.push(color);
        });
        return colors;
    }

   
    $(document).ready(function() {
        fetchData();
    });
</script>



<script>
    const ctx2 = $('#chart2');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Approved', 'Cancelled', 'Pending'],
            datasets: [{
                label: '# of Request',
                data: [
                    <?php echo $status_counts['approved']; ?>,
                    <?php echo $status_counts['declined']; ?>,
                    <?php echo $status_counts['pending']; ?>
                ],
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
                    text: "Total Sick Leave",
                    font: {
                        size: 19,
                    },
                },
            }

        },
    });
</script>