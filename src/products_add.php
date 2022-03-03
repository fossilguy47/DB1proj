
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Sklep spozywczy</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/list.css">
	<link rel="stylesheet" href="css/products_add.css">
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
					include 'is_logged_employed_warehouseman.php';
					include 'nav.php';
				?>
				<a class="navLogOut" href="logout.php">Wyloguj</a>
			</div>
<div >
			<h1>Sklep spozywczy</h1> 
		</div>
			<div  id="contentBox" >
					
<p class='ListHeader'><u>Dodaj nowy produkt do katalogu</u></p><br>


<div class='newProductsFormDiv'>
	<form  id="newProductsForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

<?php

$hostname = "localhost";
$dbname = "";         // changed in orginal project
$username = "";     // changed in orginal project
$pass = "";             // changed in orginal project

session_start();

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO proj');


if($_SESSION['role']=='magazynier')
{
	// gets to dropdown list all available categories
	
	$result = pg_query($db_conn, "SELECT * FROM proj.dzial;");
	$rows = pg_num_rows($result);
	
	echo '<div >';
	echo '<label for="category" class="selectLabel" >Kategoria: </label>';
	echo '	<select id="category" name="category"    required>';
	echo '	<option value="" selected disabled hidden> - </option>';	
		
	if ( $rows > 0 )
	{
		while ($row= pg_fetch_assoc($result)) 
		{
			echo '<option value="'.$row["id_dzial"].'">'.$row["nazwa"].'</option>';
		}
	}
	echo '	</select></div>';
	
	// gets to dropdown list all available producers
	
	$result = pg_query($db_conn, "SELECT * FROM proj.producent;");
	$rows = pg_num_rows($result);
		
	if ( $rows > 0 ){
		
		echo '<div >';
		echo '<label for="producer" class="selectLabel" >Producent: </label>';
		echo '	<select id="producer" name="producer"   required>';
		echo '	<option value="" selected disabled hidden> - </option>';
		while ($row= pg_fetch_assoc($result))
		{
			echo '<option value="'.$row["id_producent"].'">'.$row["nazwa"].'</option>';
		}
		echo '	</select></div>';	
	}
}
	
?>


		<!-- product's name imput -->
		<div >
			<label for="pname" class="inputLabel" >Nazwa:</label>
			<input type="text"  id="pname" name="pname" required>
		</div>
		<!-- product's price imput -->
		<div >
			<label for="price" class="inputLabel" >Cena:</label>
			<input type="text"  id="price" name="price" pattern="[1-9][0-9]+\.[0-9][0-9]" placeholder="0.00" required>
		</div>

		<input  type="submit" name="submitAddProducts"  value="Dodaj do bazy"   id="submitAddProducts" class="btn btn-primary" >

	<p id="info"></p>
	</form>
</div>	



<?php

if($_SESSION['role']=='magazynier')
{
	if(isset($_POST['submitAddProducts']))
	{
		$id_category = $_POST['category'];	
		$id_producer = $_POST['producer'];	
		$pname = $_POST['pname'];
		$price = $_POST['price'];	

		if( isset($id_category) && isset($id_producer) && isset($pname) && isset($price) )
		{
			$result = pg_query($db_conn, "SELECT proj.dodajProdukt('$id_category', '$id_producer', '$pname', '$price');");
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{						
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Produkt dodany do bazy";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Taki produkt już istnieje w bazie";</script>';
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