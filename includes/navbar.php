<style>
</style>
<nav id="sidebar" style="height: 100vh;" class='mx-lt-5 bg-dark overflow-y-auto pb-2'>

	<div class="sidebar-list">
		<a href="index.php?page=home" class="link-underline link-underline-opacity-0 nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>

		<a href="index.php?page=attendance" class="link-underline link-underline-opacity-0 nav-item nav-attendance"><span class='icon-field'><i class="fa fa-th-list"></i></span> Attendance</a>

		<a href="index.php?page=payroll" class="link-underline link-underline-opacity-0 nav-item nav-payroll"><span class='icon-field'><i class="fa fa-columns"></i></span> Payroll List</a>

		<a href="index.php?page=employee" class="link-underline link-underline-opacity-0 nav-item nav-employee"><span class='icon-field'><i class="fa fa-user-tie"></i></span> Employee List</a>

		<a href="index.php?page=department" class="link-underline link-underline-opacity-0 nav-item nav-department"><span class='icon-field'><i class="fa fa-columns"></i></span> Depatment List</a>

		<a href="index.php?page=position" class="link-underline link-underline-opacity-0 nav-item nav-position"><span class='icon-field'><i class="fa fa-user-tie"></i></span> Position List</a>

		<a href="index.php?page=allowances" class="link-underline link-underline-opacity-0 nav-item nav-allowances"><span class='icon-field'><i class="fa fa-list"></i></span> Allowance List</a>

		<a href="index.php?page=deductions" class="link-underline link-underline-opacity-0 nav-item nav-deductions"><span class='icon-field'><i class="fa fa-money-bill-wave"></i></span> Deduction List</a>

		<!-- <a href="index.php?page=scheduling-employee" class="link-underline link-underline-opacity-0 nav-item nav-attendance"><span class='icon-field'><i class="fa fa-th-list"></i></span> Scheduling drag</a> -->

		<a href="index.php?page=schedule" class="link-underline link-underline-opacity-0 nav-item nav-schedule"><span class='icon-field'><i class="fa fa-columns"></i></span> Schedule</a>

		<?php if ($_SESSION['login_type'] == 1) : ?>
			<a href="index.php?page=users" class="link-underline link-underline-opacity-0 nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>

		<?php endif; ?>
	</div>

</nav>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>