
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Sklep spozywczy</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/list.css">
	<link rel="stylesheet" href="css/restock.css">
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
					
<p class='ListHeader'><u>Uzupełnij stan magazynu o nowe egzemplarze</u></p><br>


<div class='restockFormDiv'>
	<form  id="restockForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

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

// get id of warehouse managed by a logged un warehouseman
$result = pg_query($db_conn, "SELECT id_magazyn FROM proj.sprzedawca WHERE id_sprzedawca=$id;");
$rows = pg_num_rows($result);
if ( $rows > 0 ) 
{
	while ($row = pg_fetch_assoc($result)) {
		$id_wh = $row['id_magazyn'];
	}
}

if($_SESSION['role']=='magazynier')
{
	
	// gets to dropdown list all products from catalog
	
	$result = pg_query($db_conn, "SELECT * FROM proj.produkty_lista;");
	$rows = pg_num_rows($result);
	
	echo '<div >';
	echo '<label for="product" class="selectLabel" >Produkt: </label>';
	echo '	<select id="product" name="product"  required>';
	echo '	<option value="" selected disabled hidden> - </option>';
		
	if ( $rows > 0 ){
		
		while ($row= pg_fetch_assoc($result))
		{
			echo '<option value="'.$row["id_produkt"].'">'.$row["nazwa"].'</option>';
		}
	}
	echo '	</select></div>';	
}
	
?>

		<!-- product's quantity imput -->
		<div >
			<label for="quantity" class="inputLabel" >Ilość:</label>
			<input type="text" id="quantity" name="quantity" pattern="[0-9]{1,10}"  required>
		</div>

		<input  type="submit" name="submitRestock"  value="Dodaj do bazy"   id="submitRestock" class="btn btn-primary" >

	<p id="info"></p>
	</form>
</div>	



<?php

if($_SESSION['role']=='magazynier')
{
	if(isset($_POST['submitRestock']))
	{
		$id_product = $_POST['product'];	
		$quantity = $_POST['quantity'];

		if( isset($id_product) && isset($quantity) )
		{
			$result = pg_query($db_conn, "SELECT proj.dodajAsortyment('$id_wh', '$id_product', '$quantity');");
			debug_to_console($result);
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{						
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Zaktualizowano stan";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy dodawaniu do bazy";</script>';
					}
				}
			}
			else
			{
				echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy dodawaniu do bazy";</script>';
			}
		}
		else
		{
			echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd danych";</script>';
		}

	}
}
?>				
			</div>
			
		</div>



		
    </body>
</html>