		<?php include('./config/db_connect.php') ?>
		<!DOCTYPE html>

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
									<label for="firstname" style="font-size: 14px;" class="fw-medium">*First Name</label>
									<input type="text" id="firstname" class="form-control"   required>
								</div>
								<div class="col">
									<label for="middlename" style="font-size: 14px;" class="fw-medium">*Middle Name</label>
									<input type="text" id="middlename" class="form-control"  required>
								</div>
								<div class="col">
									<label for="lastname" style="font-size: 14px;" class="fw-medium">*Lastname</label>
									<input type="text" id="lastname" class="form-control"  required>
								</div>
								<div class="col ">
									<label for="suffix" style="font-size: 14px;" class="fw-medium">*Suffix</label>
									<input type="text" id="suffix" class="form-control"  >
								</div>
							</div>
							<div class="row">
								<div class="col ">
									<label for="email" style="font-size: 14px;" class="fw-medium">*Email</label>
									<input type="email" id="email" class="form-control"  required>
								</div>
								<div class="col ">
									<label for="emergencynumber" style="font-size: 14px;" class="fw-medium">*Emergency No.</label>
									<input type="number" id="emergencynumber" class="form-control" required>
									


								</div>
								<div class="col ">
									<label for="name" style="font-size: 14px;" class="fw-medium">*Contact Person</label>
									<input type="text" id="name" class="form-control" required>

								</div>
								<div class="col ">
									<label for="status" style="font-size: 14px;" class="fw-medium">*Job-Type</label>
									<select id="status" class="form-select" aria-label="Default select example" required>
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
		<div class="container-fluid">
    <div class="col-lg-12">
        <br />
        <br />
        <div class="card" style="width: 1600px">
            <div class="card-header">
                <span><b>Employee List</b></span>
                <button class="btn btn-primary btn-sm btn-block col-md-3 float-right" data-bs-toggle="modal" data-bs-target="#addEmpModal" type="button" id="addEmpbtn"><span class="fa fa-plus"></span> Add Employee</button>
            </div>
			<br>
			<div class="col-md-4">
				<input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search Employee">
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
									<th>Qr Code</th>
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
			// Employee data
			$employeeID = $row['id'];
			$qrCodeURL = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($employeeID) . '&size=100x100';
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
										<td>
											<div>
											<a href="<?php echo $qrCodeURL; ?>" id="qrLink<?php echo $employeeID; ?>" onclick="openQRInNewTab(event, <?php echo $employeeID; ?>)">
			<img src="<?php echo $qrCodeURL; ?>" alt="QR Code" id="qrImg<?php echo $employeeID; ?>">
		</a>


												<br>
												<!-- Displaying QR code and Print button -->
												<a href="#" onclick="printQR('<?php echo $qrCodeURL; ?>')" class="btn btn-primary">Print QR</a>
											</div>
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
	
<script>
  

    function printQR(qrCodeURL) {
        var qrWindow = window.open('', 'PrintQRWindow');
        qrWindow.document.write('<html><head><title>Print QR Code</title><style>@media print { #printButton { display: none; } }</style></head><body>');
        qrWindow.document.write('<img src="' + qrCodeURL + '" alt="QR Code">');
        qrWindow.document.write('<button id="printButton" onclick="window.print()">Print</button>');
        qrWindow.document.write('</body></html>');
        qrWindow.document.close();
    }

    function openQRInNewTab(event, employeeID) {
        // Prevent the default behavior of the anchor tag
        event.preventDefault();

        var qrLink = document.getElementById('qrLink' + employeeID);
        var qrCodeURL = qrLink.getAttribute('href');

        // Open QR code URL in a new tab
        window.open(qrCodeURL, '_blank');
    }
</script>

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
			// Retrieve data from input fields
			newEmpData.firstname = $("#firstname").val().trim();
			newEmpData.lastname = $("#lastname").val().trim();
			newEmpData.middlename = $("#middlename").val().trim();
			newEmpData.suffix = $("#suffix").val().trim();
			newEmpData.email = $("#email").val().trim();
			newEmpData.emergencynumber = $("#emergencynumber").val().trim();
			newEmpData.name = $("#name").val().trim();
			newEmpData.status = $("#status").val().trim();

			// Check if any required field is empty
			if (
				newEmpData.firstname === "" ||
				newEmpData.lastname === "" ||
				newEmpData.middlename === "" ||
				newEmpData.email === "" ||
				newEmpData.emergencynumber === "" ||
				newEmpData.name === "" ||
				newEmpData.status === "0"
			) {
				// Show alert if any required field is empty
				alert_toast("Please fill in all required fields.");
				return; // Exit function to prevent further execution
			}

			// If all required fields are filled, proceed to save the employee data
			if (newEmpData.isNew)
				newEmpData.id = null;

			await add_employee_req(newEmpData);
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



<script>
	document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        var searchText = this.value.toLowerCase();
        var empRows = document.getElementsByClassName('empRow');
        Array.from(empRows).forEach(function(row) {
            var employeeNo = row.cells[0].innerText.toLowerCase(); // Assuming the first column contains the employee number
            var employeeName = row.cells[1].innerText.toLowerCase(); // Assuming the second column contains the employee name
            if (employeeNo.includes(searchText) || employeeName.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

</script>