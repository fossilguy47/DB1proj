
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Sklep spozywczy</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/list.css">
	<link rel="stylesheet" href="css/warehouses_add.css">
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
			<div >
				<h1>Sklep spozywczy</h1> 
			</div>
			 
			<div  id="contentBox" >

					
<p class='ListHeader'><u>Rejestracja nowego sklepu</u></p><br>

<div class='newWarehouseFormDiv'>
	<form  id="newWarehouseForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

		<div >
			<label for="ifMainWarehouse" class="selectLabel" >Czy jest jednym z głównych?</label>
		
		<!-- selecting if warehouse will be a main one -->
			<select name="ifMainWarehouse" id="ifMainWarehouse"  required>
				<option value="" selected disabled hidden> - </option>
				<option value="true">tak</option>
				<option value="false">nie</option>
			</select>
		</div>
		
		<!-- 
			address imput 
		-->
		<p class="addressH"> Adres</p>
		
		<div >
			<label for="street" class="inputLabel" >Ulica i numer:</label>
			<input type="text" id="street" name="street" required>
		</div>
		<div >
			<label for="postcode" class="inputLabel" >Kod pocztowy:</label>
			<input type="text" id="postcode" name="postcode" pattern="[0-9]{2}[-][0-9]{3}" placeholder="00-000" required>
		</div>
		<div >
			<label for="city" class="inputLabel" >Miasto:</label>
			<input type="text" id="city" name="city" required>
		</div>

		<input  type="submit" name="submitAddWarehouse"  value="Dodaj do bazy"   id="submitAddWarehouse" class="btn btn-primary" >

	</form>
<div id ="info"></div>
</div>		
	
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
	if(isset($_POST['submitAddWarehouse']))
	{
		$main = $_POST['ifMainWarehouse'];
		$street = $_POST['street'];
		$postcode = $_POST['postcode'];
		$city = $_POST['city'];
		 echo '<script type="text/javascript">console.log("'.$main.'");</script>';
		 echo '<script type="text/javascript">console.log("'.$street.'");</script>';
		 echo '<script type="text/javascript">console.log("'.$postcode.'");</script>';
		 echo '<script type="text/javascript">console.log("'.$city.'");</script>';
		
		if( isset($main) && isset($street) && isset($postcode) && isset($city) )
		{
			
			$result = pg_query($db_conn, "SELECT proj.dodajMagazyn('$main','$street','$postcode','$city');");
			debug_to_console($result);
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{	
					echo '<script type="text/javascript">console.log("'.$row[0].'");</script>';
					
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Magazyn dodany do bazy";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Magazyn o takich danych już istnieje w bazie";</script>';
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