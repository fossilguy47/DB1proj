
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
			<div >
			<h1>Sklep spozywczy</h1> 
		</div>
			<div  id="contentBox" >
					<?php
$hostname = "localhost";
$dbname = "";         // changed in orginal project
$username = "";     // changed in orginal project
$pass = "";             // changed in orginal projectt

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO proj');

echo "<p class='ListHeader'><u>Lista produktów</u></p>";
echo "<div class='list'>";
echo "<table style='width:100%'>";
	echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Nazwa produktu</th><th>Id produktu </th><th>Kategoria</th><th>Producent</th><th>Cena</th></tr>";
		
	echo "<tbody>";
	
if($_SESSION['role']=='magazynier')
{

	// getting list of all products category
	$result = pg_query($db_conn, "SELECT * FROM proj.produkty_lista ORDER BY dzial;");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) 
	{
		$i = 0;
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell2'>".$row['nazwa']."</td><td class='dec_cell2'>".$row['id_produkt']."</td><td class='dec_cell2'>".$row['dzial']."</td><td class='dec_cell2'>".$row['producent']."</td><td class='c'>".$row['cena']."</td></tr>";
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