
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>SKlepy spozywcze</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/list.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

	
</head>

    <body>
		
		<div  id="main">
			<div class="navbar navbar-dark bg-dark" id="sidenav">
				<a class="role" id="role">Zalogowany jako: <br> <span id="roleName">  </span></a>
				<a class="navA" id="personalInfo"  href="personal.php">Informacje personalne</a>
					<div class="dropdownContent"></div>
				<?php
					include 'is_logged_employed_manager.php';
					include 'nav.php';
					
				?>
				<a class="navLogOut" href="logout.php">Wyloguj</a>
			</div>

			<div>
			<h1>Sklep spozywczy</h1> 
			</div> 
			<div  id="contentBox" >
					<?php

						session_start();


						if($_SESSION['role']=='kierownik')
						{	
							echo '<h1 class="ml11">
								<span class="text-wrapper">
									<span class="line line1"></span>
									<span class="letters">Sklepy</span>
								</span>
							</h1>';
						}
					?>
			

<div class ="dropdownContentclass" id="dropdownContentWarehouse">
						<a id="warehouseList" href="warehouses_list.php" >Lista</a>
						<a id="warehouseAdd" href="warehouses_add.php" >Dodaj</a>

					</div>	
			</div>
	
			
		</div>


			
    </body>
</html>