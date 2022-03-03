
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Sklepy spozywcze</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/list.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

	
</head>

    <body>
		<div id="main">
			<div class="navbar navbar-dark bg-dark" id="sidenav">
				<a class="role" id="role">Zalogowany jako: <br> <span id="roleName">  </span></a>
				<a class="navA" id="personalInfo"  href="personal.php">Informacje personalne</a>
					<div class="dropdownContent"></div>
				<?php
					include 'is_logged_employed_warehouseman.php';
					include 'nav.php';
				?>
				<a class="navLogOut" href="logout.php">Wyloguj</a>		
			</div>
			<div>
				<h1>Sklepy spozywcze</h1> 
			</div>

			<script type="text/javascript" src="js/navbar_warehouse.js"></script> 
			<div  id="contentBox" >
					<?php

session_start();


if($_SESSION['role']=='kierownik')
{	
	echo '<h1 class="ml11">
			<span class="text-wrapper">
				<span class="line line1"></span>
				<span class="letters">Magazyny</span>
			</span>
		</h1>';
}
?>			


<div class="dropdownContentclass">
						<a href="stock.php">Stan</a>
						<a href="restock.php">Uzupełnij asortyment</a>
						<a href="sale_report.php">Sprzedaż</a>
					</div>	
			</div>
			
		</div>



    </body>
</html>