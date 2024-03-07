<?php include './config/db_connect.php' ?>
<style>

</style>

<div class="container-fluid  p-2">

	<div class="row">
		<div class="col-lg-12">

		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<?php echo "Welcome back " . $_SESSION['login_name'] . "!"  ?>
				</div>
			</div>
		</div>
	</div>

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