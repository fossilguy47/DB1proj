
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
$pass = "";             // changed in orginal project

session_start();



function debug_to_console($data) {

    $output = $data;

    if (is_array($output))

        $output = implode(',', $output);



    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}




// Create connection

$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");

pg_query($db_conn,'SET search_path TO proj');






$id_wh;
$id = $_SESSION['emp_id'];


if($_SESSION['role']=='magazynier')
{

	// get id of warehouse managed by a logged un warehouseman
$result = pg_query($db_conn, "SELECT s.id_magazyn FROM proj.sprzedawca  s WHERE s.id_sprzedawca=$id;");

$rows = pg_num_rows($result);
if ( $rows > 0 ) 
{
	while ($row = pg_fetch_assoc($result)) {
		$id_wh = $row['id_magazyn'];
	}
}

echo "<p class='ListHeader' style='margin-bottom: 2px;'><u>Lista zamowien</u></p>";
echo "<p style='text-align: center;'>( id magazynu: $id_wh. )</p><br>";
	echo "<div class='list'>";
	echo "<table style='width:100%'>";
	echo "<thead><tr class='c'><th class='lp'>Lp.</th><th>Data złożenia</th><th>Status</th><th>Id zamów.</th> <th>Klient</th><th>NIP</th><th>Email</th><th colspan='2'>Adres klienta<br><span id='det'>(ulica i nr,  miasto)</span></th></tr>";
	
	
	// getting list of all producers
	$result = pg_query($db_conn, "SELECT * FROM proj.zamowienia_lista zl WHERE zl.id_magazyn=$id_wh;");
	
	$rows = pg_num_rows($result);
	debug_to_console($rows);
	debug_to_console($id_wh);
	if ( $rows > 0 ) 
	{
		$i = 0;
		echo "<tbody>";
		while ($row = pg_fetch_assoc($result)) {
			$i += 1;
			echo "<tr ><td class='lp'>".$i."</td><td class='dec_cell2'>".$row['data']."</td><td class='dec_cell2'>".$row['status']."</td><td class='c'>".$row['id_zamowienie']."</td><td class='c'>".$row['nazwisko']."</td><td class='c'>".$row['nip']."</td><td class='c'>".$row['email']."</td><<td class='c'>".$row['ulica_i_numer']."</td><td class='c'>".$row['miasto']."</td></tr>";
		}
		echo "</tbody>";
	}
	echo "</table>";
	echo "</div>"; 
}
?>				
			</div>
					</div>


		<?php
			include 'is_logged_employed_warehouseman.php';
		?>

		
    </body>
</html>