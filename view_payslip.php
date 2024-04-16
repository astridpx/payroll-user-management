<?php include './config/db_connect.php' ?>

<?php
$payroll = $conn->query("SELECT p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no FROM payroll_items p inner join employee e on e.id = p.employee_id  where p.id=" . $_GET['id']);
foreach ($payroll->fetch_array() as $key => $value) {
	$$key = $value;
}
$pay = $conn->query("SELECT * FROM payroll where id = " . $payroll_id)->fetch_array();
$pt = array(1 => "Monhtly", 2 => "Semi-Monthly");
?>

<div class="contriner-fluid">
	<div class="col-md-12">
		<h5><b><small>Employee ID :</small><?php echo $employee_no ?></b></h5>
		<h4><b><small>Name: </small><?php echo ucwords($ename) ?></b></h4>
		<hr class="divider">
		<div class="row">
			<div class="col-md-6">
				<p><b>Payroll Ref : <?php echo $pay['ref_no'] ?></b></p>
				<p><b>Payroll Range : <?php echo date("M d, Y", strtotime($pay['date_from'])) . " - " . date("M d, Y", strtotime($pay['date_to'])) ?></b></p>
				<p><b>Payroll type : <?php echo $pt[$pay['type']] ?></b></p>
			</div>
			<div class="col-md-6">
				<p><b>Days of Absent : <?php echo $absent ?></b></p>
				<p><b>Tardy/Undertime (mins) : <?php echo $late ?></b></p>
				
	
				<p><b>Net Pay : <?php echo number_format($net, 2) ?></b></p>
			</div>
		</div>


		<hr class="divider">
		<div class="row">
			<div class="col-md-6">
				
			</div>
			<div class="col-md-6">
				
		</div>

		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-lg-12">
		<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" type="button" onclick="closeTab();">Close</button>


		</div>
	</div>
</div>
<style type="text/css">
	.list-group-item>span>p {
		margin: unset;
	}

	.list-group-item>span>p>small {
		font-weight: 700
	}

	#uni_modal .modal-footer {
		display: none;
	}

	.alist,
	.dlist {
		width: 100%
	}
</style>
<script>
function closeTab() {
    window.close();
}
</script>



