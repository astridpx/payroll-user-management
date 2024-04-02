<?php include('./config/db_connect.php') ?>

<!-- Modal -->
<div class="modal fade " id="addEmpModal" tabindex="-1" aria-labelledby="addEmpModalLabel" aria-hidden="true" style="padding-right: 60px;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" >
				<h1 class="modal-title fs-5" id="addEmpModalLabel">New Employee</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid ">
					<div class="row mb-3">
						<div class="col ">
							<label for="firstname" style="font-size: 14px;" class="fw-medium">*Firstname</label>
							<input type="text" id="firstname" class="form-control" placeholder="firstname*" aria-label="firstname*">
						</div>
						<div class="col">
							<label for="middlename" style="font-size: 14px;" class="fw-medium">*Middlename</label>
							<input type="text" id="middlename" class="form-control" placeholder="middlename*" aria-label="middlename*">
						</div>
						<div class="col">
							<label for="lastname" style="font-size: 14px;" class="fw-medium">*Lastname</label>
							<input type="text" id="lastname" class="form-control" placeholder="lastname*" aria-label="lastname*">
						</div>
						<div class="col ">
							<label for="suffix" style="font-size: 14px;" class="fw-medium">*Suffix</label>
							<input type="text" id="suffix" class="form-control" placeholder="suffix*" aria-label="suffix*">
						</div>
					</div>
					<div class="row">
						<div class="col ">
							<label for="email" style="font-size: 14px;" class="fw-medium">*Email</label>
							<input type="email" id="email" class="form-control" placeholder="email*" aria-label="email*">
						</div>
						<div class="col ">
							<label for="emergencynumber" style="font-size: 14px;" class="fw-medium">*Emergencynuber</label>
							<input type="number" id="emergencynumber" class="form-control" placeholder="Emergencynumber*" aria-label="Emergencynuber*">
						</div>
						<div class="col ">
							<label for="name" style="font-size: 14px;" class="fw-medium">*Name</label>
							<input type="text" id="name" class="form-control" placeholder="Name*" aria-label="email*">
						</div>
						<div class="col ">
							<label for="status" style="font-size: 14px;" class="fw-medium">*Job-Type</label>
							<select id="status" class="form-select" aria-label="Default select example">
								<option selected value="0" class="fw-semibold">Select a job-type</option>
								<option value="Parttime">Parttime</option>
								<option value="Fulltime">Fulltime</option>
							</select>
						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
				<button type="button" id="saveEmp" class="btn btn-primary btn-sm">Save Employee</button>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid ">
	<div class="col-lg-12">

		<br />
		<br />
		<div class="card" style="width: 1600px">
			<div class="card-header">
				<span><b>Employee List</b></span>
				<button class="btn btn-primary btn-sm btn-block col-md-3 float-right" data-bs-toggle="modal" data-bs-target="#addEmpModal" type="button" id="addEmpbtn"><span class="fa fa-plus"></span> Add Employee</button>
			</div>
			<div class="card-body">
				<table id="table" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Employee No</th>
							<th>Firstname</th>
							<th>Middlename</th>
							<th>Lastname</th>
							<th>Suffix</th>
							<th>Email</th>
							<th>Emergencynumber</th>
							<th>Name</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$d_arr[0] = "Unset";
						$p_arr[0] = "Unset";
						$dept = $conn->query("SELECT * from department order by name asc");
						while ($row = $dept->fetch_assoc()) :
							$d_arr[$row['id']] = $row['name'];
						endwhile;
						$pos = $conn->query("SELECT * from position order by name asc");
						while ($row = $pos->fetch_assoc()) :
							$p_arr[$row['id']] = $row['name'];
						endwhile;
						$employee_qry = $conn->query("SELECT * FROM employee") or die(mysqli_error($conn));
						while ($row = $employee_qry->fetch_array()) {
						?>
							<tr class="empRow">
								<td><?php echo $row['employee_no'] ?></td>
								<td data-firstname="<?php echo $row['firstname'] ?>"><?php echo $row['firstname'] ?></td>
								<td data-middlename="<?php echo $row['middlename'] ?>"><?php echo $row['middlename'] ?></td>
								<td data-lastname="<?php echo $row['lastname'] ?>"> <?php echo $row['lastname'] ?></td>
								<td data-suffix="<?php echo $row['suffix'] ?>"> <?php echo $row['suffix'] ?></td>
								<td data-email="<?php echo $row['email']  ?>"><?php echo $row['email']  ?></td>
								<td data-emergencynumber="<?php echo $row['emergencynumber'] ?>"> <?php echo $row['emergencynumber'] ?></td>
								<td data-name="<?php echo $row['name'] ?>"> <?php echo $row['name'] ?></td>
								<td data-status="<?php echo $row['status'] ?>"><?php echo $row['status'] ?></td>
								<td>
									<center>
										<button class="btn btn-sm btn-outline-primary edit_employee" data-bs-toggle="modal" data-bs-target="#addEmpModal" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-edit"></i></button>
										<button class="btn btn-sm btn-outline-danger remove_employee" data-id="<?php echo $row['id'] ?>" type="button"><i class="fa fa-trash"></i></button>
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

		$('.remove_employee').on("click", function() {
			Swal.fire({
				title: "Are you sure?",
				text: "You won't be able to revert this!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, delete it!"
			}).then((result) => {
				if (result.isConfirmed) {
					remove_employee($(this).attr('data-id'))
				}
			})
		})

		const newEmpData = {
			isNew: true,
			id: null,
			firstname: "",
			middlename: "",
			lastname: "",
			suffix: "",
			email: "",
			emergencynumber: "",
			name: "",
			status: "",
		}

		$("#saveEmp").on("click", async function() {
			newEmpData.firstname = $("#firstname").val();
			newEmpData.lastname = $("#lastname").val();
			newEmpData.middlename = $("#middlename").val();
			newEmpData.suffix=$("#suffix").val();
			newEmpData.email = $("#email").val();
			newEmpData.emergencynumber=$("#emergencynumber").val();
			newEmpData.name=$("#name").val();
			newEmpData.status = $("#status").val();

			if (newEmpData.isNew)
				newEmpData.id = null

			await add_employee_req(newEmpData)

		})

		function add_employee_req(data) {
			start_load()
			$.ajax({
				url: './services/ajax.php?action=save_employee',
				method: "POST",
				data: newEmpData,
				error: err => console.log(err),
				success: function(resp) {
					if (resp == 1) {
						alert_toast("New Employee successfully save", "success");
						setTimeout(function() {
							location.reload();
						}, 1000)
					}
				}
			})
		}

		$(".edit_employee").on("click", function() {
			newEmpData.isNew = false

			// Get data from the clicked row
			var row = $(this).closest("tr");
			var firstname = row.find("td:eq(1)").data("firstname");
			var middlename = row.find("td:eq(2)").data("middlename");
			var lastname = row.find("td:eq(3)").data("lastname");
			var suffix = row.find("td:eq(4)").data("suffix");
			var email = row.find("td:eq(5)").data("email");
			var emergencynumber = row.find("td:eq(6)").data("emergencynumber");
			var name = row.find("td:eq(7)").data("name");
			var status = row.find("td:eq(8)").data("status");
			var employeeId = $(this).data("id");
			newEmpData.id = employeeId

			// Use the data to populate the form fields or perform other actions
			// For example, you can update the modal fields if using a modal for editing
			$("#addEmpModal #firstname").val(firstname);
			$("#addEmpModal #middlename").val(middlename);
			$("#addEmpModal #lastname").val(lastname);
			$("#addEmpModal #suffix").val(suffix);
			$("#addEmpModal #email").val(email);
			$("#addEmpModal #emergencynumber").val(emergencynumber);
			$("#addEmpModal #name").val(name);
			$("#addEmpModal #status").val(status);
		});



		function remove_employee(id) {
			start_load()
			$.ajax({
				url: './services/ajax.php?action=delete_employee',
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
	});
</script>