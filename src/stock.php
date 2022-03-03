
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
					include 'is_logged_employed_warehouseman.php';
					include 'nav.php';
				?>
				<a class="navLogOut" href="logout.php">Wyloguj</a>
			</div>
<div>
			<h1>Sklep spozywczy</h1> 
		</div>
			<div  id="contentBox" >
					<?php
$hostname = "localhost";
$dbname = "";         // changed in orginal project
$username = "";     // changed in orginal project
$pass = "";             // changed in orginal project

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO hurtownia');

$id_wh;
$id = $_SESSION['emp_id'];

// get id of warehouse managed by a logged un warehouseman
$result = pg_query($db_conn, "SELECT id_magazyn FROM proj.sprzedawca WHERE id_sprzedawca=$id;");
$rows = pg_num_rows($result);
if ( $rows > 0 ) 
{
	while ($row = pg_fetch_assoc($result)) {
		$id_wh = $row['id_magazyn'];
	}
}

echo "<p class='ListHeader' style='margin-bottom: 2px;'><u>Stan magazynu</u></p>";
echo "<p style='text-align: center;'>( id magazynu: $id_wh. )</p><br>";

echo "<div class='list'>";
echo "<table style='width:100%'>";
	echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Nazwa produktu</th><th>Ilość</th><th>Cena j.</th><th class='c'>Id produktu </th><th>Kategoria</th><th>Producent</th></tr>";
	echo "<tbody>";
	
if($_SESSION['role']=='magazynier')
{


	// getting stock listed
	$result = pg_query($db_conn, "SELECT * FROM proj.pelny_stan_magazynow WHERE id_magazyn=$id_wh;");
	$rows = pg_num_rows($result); 
	if ( $rows > 0 ) 
	{
		$i = 0;
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell2'>".$row['nazwa_produktu']."</td><td class='dec_cell2'>".$row['ilosc']."</td><td class='c'>".$row['cena']."</td><td class='c'>".$row['id_produkt']."</td><td class='dec_cell2'>".$row['dzial']."</td><td class='c'>".$row['producent']."</td></tr>";
		}
	}
	echo "</tbody>";
	echo "</table>";

	echo "</div>"; 
}
?>				
			</div>
			
		</div>


	
    </body>
</html>