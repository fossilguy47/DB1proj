
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Sklep spozywczy</title>
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
					include 'is_logged_employed_manager.php';
					include 'nav.php';
				?>
				<a class="navLogOut" href="logout.php">Wyloguj</a>
			</div>
			<div>
				<h1>Sklep spozywczy</h1> 
			</div>

			<script type="text/javascript" src="js/navbar_emp.js"></script> 
			<div  id="contentBox" >
					<?php
$hostname = "localhost";
$dbname = "";         // changed in orginal project
$username = "";     // changed in orginal project
$pass = "";             // changed in orginal project


session_start();




// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO proj');


if($_SESSION['role']=='kierownik')
{
	echo "<p class='ListHeader'><u>Lista pracowników</u></p>";
	echo "<div class='employee_list'>";

	// getting list of all employees
	$result = pg_query($db_conn, "SELECT * FROM proj.pracownicy_lista ORDER BY nazwa_stanowiska;");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		$i = 0;
		echo "<table style='width:100%'>";
		echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Imię</th><th>Nazwisko</th><th>Stanowisko</th><th>Magazyn</th><th>E-mail</th><th>Telefon</th><th colspan='3'>Adres<br><span id='det'>(ulica i nr bud., kod pocz., miasto)</span></th><th colspan='2'>Zatrudnienie<br><span id='det'>(od - do)</span></th></tr>";
		echo "<tbody>";
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell'>".$row['imie']."</td><td class='dec_cell'>".$row['nazwisko']."</td><td class='c'>".$row['nazwa_stanowiska']."</td><td class='c'>".$row['id_magazyn']."</td><td class='c'>".$row['email']."</td><td class='c'>".$row['telefon']."</td><td class='c'>".$row['ulica_i_numer']."</td><td class='c'>".$row['kod_pocztowy']."</td><td class='c'>".$row['miasto']."</td><td class='startDate'>".$row['poczatek_zatrudnienia']."</td><td class='endDate'>".$row['koniec_zatrudnienia']."</td></tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}

	echo "</div>"; 
}
?>				
			</div>
			
		</div>

    </body>
</html>