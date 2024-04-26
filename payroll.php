<?php include('./config/db_connect.php') ?>

<!-- Modal -->
<div class="modal fade" id="newPayrollModal" tabindex="-1" aria-labelledby="newPayrollModal" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="newPayrollModal">Modal title</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row g-3 needs-validation" novalidate>
					<div class="col-full">
						<label for="validationCustom01" class="form-label">Date From:</label>
						<input type="date" class="form-control" id="dateFrom" required>
						<div class="valid-feedback">
							Looks good!
						</div>
					</div>
					<div class="col">
						<label for="validationCustom02" class="form-label">Date To:</label>
						<input type="date" class="form-control" id="dateTo" required>
						<div class="valid-feedback">
							Looks good!
						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save_new_payroll">Save changes</button>
			</div>
		</form>
	</div>
</div>

<div class="container-fluid ">
	<div class="col-lg-12">

		<br />
		<br />
		<div class="card">
			<div class="card-header">
				<span><b>Monitoring List</b></span>
				<button class="btn btn-primary btn-sm btn-block col-md-3 float-right" type="button" id="new_payroll_btn" data-bs-toggle="modal" data-bs-target="#newPayrollModal"><span class="fa fa-plus"></span> Add Payroll
				</button>

			</div>
			<div class="card-body">
				<table id="table" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Ref No</th>
							<th>Date From</th>
							<th>Date To</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$payroll = $conn->query("SELECT * FROM payroll order by date(date_from) desc") or die(mysqli_error($conn));
						while ($row = $payroll->fetch_array()) {
						?>
							<tr>
								<td><?php echo $row['ref_no'] ?></td>
								<td><?php echo date("M d, Y", strtotime($row['date_from'])) ?></td>
								<td><?php echo date("M d, Y", strtotime($row['date_to'])) ?></td>
								<?php if ($row['status'] == 0) : ?>
									<td class="text-center"><span class="badge badge-primary">New</span></td>
								<?php else : ?>
									<td class="text-center"><span class="badge badge-success">Calculated</span></td>
								<?php endif ?>
								<td>
									<center>
										<?php if ($row['status'] == 0) : ?>
											<button class="btn btn-sm btn-outline-primary calculate_payroll" data-id="<?php echo $row['id'] ?>" type="button">Calculate</button>
										<?php else : ?>
											<button class="btn btn-sm btn-outline-primary view_payroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-eye"></i></button>
										<?php endif ?>

										<button class="btn btn-sm btn-outline-primary edit_payroll" data-bs-toggle="modal" data-bs-target="#newPayrollModal" data-id="<?php echo $row['id'] ?>" type="button">
											<i class="fa fa-edit"></i>
										</button>
										<button class="btn btn-sm btn-outline-danger remove_payroll" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-trash"></i></button>
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



<script type="text/javascript">
	$(document).ready(function() {
		$('#table').DataTable();
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {

		async function PayrollManage(data) {
			start_load()

			await $.ajax({
				url: './services/ajax.php?action=save_payroll',
				method: "POST",
				data: data,
				error: err => console.log(),
				success: function(resp) {
					if (resp == 1) {
						alert_toast("Payroll successfully saved", "success");
						setTimeout(function() {
							location.reload()
						}, 600)
					}
				}
			})
		}


		let data = {
			date_from: "",
			date_to: "",
			type: 2,
			id: null
		}


		// ADD NEW PAYROLL
		$("#save_new_payroll").on("click", function() {
			const dateFrom = $("#dateFrom").val()
			const dateTo = $("#dateTo").val()

			data.date_from = dateFrom
			data.date_to = dateTo


			PayrollManage(data)
		})

		// EDIT PAYROLL
		$('.edit_payroll').click(function() {
			var id = $(this).attr('data-id');

			data.id = id
		});

		$('.view_payroll').click(function() {
			var $id = $(this).attr('data-id');
			location.href = "index.php?page=payroll_items&id=" + $id;

		});

		$('.remove_payroll').click(function() {
    var id = $(this).attr('data-id');
    remove_payroll(id);
});

		$('.calculate_payroll').click(function() {
			start_load()
			$.ajax({
				url: './services/ajax.php?action=calculate_payroll',
				method: "POST",
				data: {
					id: $(this).attr('data-id')
				},
				error: err => console.log(err),
				success: function(resp) {
					if (resp == 1) {
						alert_toast("Payroll successfully computed", "success");
						setTimeout(function() {
							location.reload();

						}, 1000)
					}
				}
			})
		})
	});

	function remove_payroll(id) {
		start_load()
		$.ajax({
			url: './services/ajax.php?action=delete_payroll',
			method: "POST",
			data: {
				id: id
			},
			error: err => console.log(err),
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Employee's data successfully deleted", "success");
					setTimeout(function() {
						location.reload();

					}, 1000)
				}
			}
		})
	}
</script>