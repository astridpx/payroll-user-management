<style>
	body {
		background-color: #f0f2f5;
	}

	@media (min-width: 991.98px) {
		main {
			padding-left: 240px;
		}
	}

	/* SIDEBAR DESIGN */
	.sidebar {
		position: fixed;
		top: 0;
		bottom: 0;
		left: 0;
		padding: 20px 0 0;
		box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
		width: 240px;
		z-index: 600;
	}


	/* SIDEBAR SAMPLE RESPONSIVE */
	@media (max-width: 991.98px) {
		.sidebar {
			width: 100%;
		}
	}

	.list-group-item.active {
		background-color: #f0f2f5 !important;
		border: none;
		color: #B91C1C;
		border-radius: 6px;
	}

	.list-group-item:hover {
		background-color: #FEDEDE !important;
		border: none;
		color: #B91C1C;
		border-radius: 6px;

	}


	.list-group-item {
		height: 50px;
		border: none;

	}

	.sidebar-sticky {
		position: relative;
		top: 0;
		height: calc(100vh - 48px);
		padding-top: 0.5rem;
		overflow-x: hidden;
		overflow-y: auto;
		/* SCROLLABLE VIEW POINT. */
	}

	.item a .dropdown {
		position: absolute;
		right: 0;
		margin: 20px;
		transition: 0.3 ease;
	}

	.item .sub-menu {
		box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
		display: none;
	}

	.dropdown-container {
		display: none;
		/* background-color: #262626; */
		padding-left: 8px;
	}

	.dropdown-container::after {
		display: block;
		position: absolute;
		top: 50%;
		right: 20px;
		transform: translateY(-50%);
	}

	.dropdown-container::after {
		top: auto;
		bottom: 10px;
		right: 50%;
		-webkit-transform: translateX(50%);
		-ms-transform: translateX(50%);
		transform: translateX(50%);
	}
</style>

<!--MAIN NAVIGATION-->
<header>
	<!-- Sidebar -->
	<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">

		<a style="margin-left: 10%">
			<img src="./assets/img/logo.png" alt="System LOGO" width="50%;">
		</a>

		<div class="list-group list-group-flush mx-3 mt-4">




			<a href="index.php?page=home" class="list-group-item list-group-item-action py-2 ripple nav-home">
				<i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
			</a>
			<!-- <a href="index.php?page=attendance" class="list-group-item list-group-item-action py-2 ripple nav-attendance">
				<i class="fas fa-clipboard-list fa-fw me-3"></i><span>Attendance</span>
			</a> -->

			<a href="index.php?page=payroll" class="list-group-item list-group-item-action py-2 ripple nav-payroll"><i class="fas fa-money-check-alt fa-fw me-3"></i><span>Payroll</span></a>
			<a href="index.php?page=employee" class="list-group-item list-group-item-action py-2 ripple nav-employee"><i class="fas fa-users fa-fw me-3"></i><span>Employee list</span></a>
			<a href="index.php?page=department" class="list-group-item list-group-item-action py-2 ripple nav-department">
				<i class="fas fa-building fa-fw me-3"></i><span>Station</span>
			</a>
			<!-- <a href="index.php?page=position" class="list-group-item list-group-item-action py-2 ripple nav-position"><i class="fas fa-briefcase fa-fw me-3"></i><span>Position</span></a> -->
			<!-- <a href="index.php?page=allowances" class="list-group-item list-group-item-action py-2 ripple nav-allowances"><i class="fas fa-wallet fa-fw me-3"></i><span>Allowance</span></a> -->
			<button class="dropdown-btn list-group-item list-group-item-action"> <i class="fas fa-clipboard-list fa-fw me-3"></i> <span>Opperation</span>
				<i class="fa fa-caret-down"></i>
			</button>

			<div class="dropdown-container">
				<a href="index.php?page=schedule" class="list-group-item list-group-item-action py-2 ripple nav-schedule"><i class="fas fa-calendar-alt fa-fw me-3"></i><span>Schedule</span></a>
				<a href="index.php?page=attendance" class="list-group-item list-group-item-action py-2 ripple nav-attendance">
					<i class="fas fa-clipboard-list fa-fw me-3"></i><span>Attendance</span></a>

			</div>




			<?php if ($_SESSION['login_type'] == 1) : ?>
				<a href="index.php?page=users" class="list-group-item list-group-item-action py-2 ripple nav-users"><i class='fas fa-user fa-fw me-3'></i><span> Users</span></a>

			<?php endif; ?>
		</div>
		</div>
	</nav>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			const links = document.querySelectorAll(".list-group-item");

			links.forEach(function(link) {
				link.addEventListener("click", function(event) {
					links.forEach(function(otherLink) {
						otherLink.classList.remove("active");
					});

					this.classList.add("active");
				});
			});
		});
	</script>
</header>


<!-- NAVBAR -->
<nav id="main-navbar" class="navbar navbar-expand-lg fixed-top" style="background-color: #d04848; margin-left: 240px;">
	<!-- Container wrapper -->
	<div class="container-fluid">
		<!-- Toggle button -->
		<button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<i class="fas fa-bars"></i>
		</button>

		<!-- SAMPLE BRAND ICON -->

		<!-- RIGHT LINKS-->
		<ul class="navbar-nav ms-auto d-flex flex-row">

			<li class="nav-item dropdown" style="padding-left:90px;">
				<a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
					<img style="color: white; aspect-ratio: 1/1; " src="./assets/img/profile.jpg" class="rounded-circle" height="30" width="35" alt="Avatar" loading="lazy" />
				</a>
				<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
					<li>
						<a class="dropdown-item" href="#">My profile</a>
					</li>
					<li>
						<a onclick="" class="dropdown-item" href="#">Settings</a>
					</li>
					<li>
						<a class="dropdown-item" href="#" onclick="logout()">Logout</a>
					</li>
				</ul>
			</li>
			<!-- AVATARA ICON -->
			<!-- <li class="nav-item dropdown">
				<a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
					<img style="color: white; aspect-ratio: 1/1;" src="./assets/img/profile.jpg" class="rounded-circle" height="30" width="35" alt="Avatar" loading="lazy" />
				</a>
				<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
					<li>
						<a class="dropdown-item" href="#" style="color: white">My profile</a>
					</li>
					<li>
						<a class="dropdown-item" href="#">Settings</a>
					</li>
					<li>
						<a class="dropdown-item" href="#">Logout</a>
					</li>
				</ul>
			</li> -->
		</ul>
	</div>

</nav>

</header>
<script>
	var dropdown = document.getElementsByClassName("dropdown-btn");
	var i;

	for (i = 0; i < dropdown.length; i++) {
		dropdown[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var dropdownContent = this.nextElementSibling;
			if (dropdownContent.style.display === "block") {
				dropdownContent.style.display = "none";
			} else {
				dropdownContent.style.display = "block";
			}
		});
	}


	function setupDropdown() {
		// Get the dropdown toggle button and the dropdown menu
		var dropdownToggle = document.querySelector('.dropdown-toggle');
		var dropdownMenu = document.querySelector('.dropdown-menu');

		// Toggle dropdown menu when the dropdown toggle button is clicked
		dropdownToggle.addEventListener('click', function(event) {
			event.preventDefault(); // Prevent default link behavior
			dropdownMenu.classList.toggle('show'); // Toggle the 'show' class
		});

		// Close dropdown menu when clicking outside of it
		document.addEventListener('click', function(event) {
			var isClickInside = dropdownToggle.contains(event.target) || dropdownMenu.contains(event.target);
			if (!isClickInside) {
				dropdownMenu.classList.remove('show'); // Remove the 'show' class
			}
		});
	}

	// Call the setupDropdown function when the DOM content is loaded
	document.addEventListener("DOMContentLoaded", function() {
		setupDropdown();
	});

	function logout() {
		var xhr = new XMLHttpRequest();
		xhr.open("GET", "logout.php", true);

		xhr.onload = function() {
			if (xhr.status === 200) {
				// Optionally handle response
				// alert(xhr.responseText); // Alert the response from the server
				window.location.href = "login.php"; // Redirect to login page
			} else {
				alert("Error logging out. Please try again.");
			}
		};

		xhr.send();
	}
</script>

<!--Main layout-->
<main style="margin-top: 30px;">
	<div class="container pt-4"></div>
</main>






<!--
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

		<! <a href="index.php?page=scheduling-employee" class="link-underline link-underline-opacity-0 nav-item nav-attendance"><span class='icon-field'><i class="fa fa-th-list"></i></span> Scheduling drag</a> -->
<!--
		<a href="index.php?page=schedule" class="link-underline link-underline-opacity-0 nav-item nav-schedule"><span class='icon-field'><i class="fa fa-columns"></i></span> Schedule</a>

		<?php if ($_SESSION['login_type'] == 1) : ?>
			<a href="index.php?page=users" class="link-underline link-underline-opacity-0 nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>

		<?php endif; ?>
	</div>

</nav>

		-->
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>