
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	
	<title>Sklep spozywczy</title>
	
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/list.css">
	<link rel="stylesheet" href="css/employee_add.css">
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
			 
			<div  id="contentBox" >
					
<p class='ListHeader'><u>Zatrudnianie nowego pracownika</u></p><br>

<div class='newEmployeeFormDiv'>
	<form  id="newEmployeeForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >


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
	
	// gets to dropdown list all available job positions
	
	$result_position = pg_query($db_conn, "SELECT * FROM proj.uprawnienia ORDER BY id_uprawnienia;");
	$row_position = pg_num_rows($result_position);
		
	if ( $row_position > 0 ){
		echo '<form>';
		echo '<div>';
		echo '<label for="empPosition" class="selectLabel" >Stanowisko: </label>';
		echo '	<select id="empPosition" name="empPosition"  required>';
		echo '	<option value="" selected disabled hidden> - </option>';
		while ($row_position = pg_fetch_assoc($result_position)) 
		{		
			echo '<option value="'.$row_position["nazwa_stanowiska"].'">'.$row_position["nazwa_stanowiska"].'</option>';
		}		 
		echo '	</select></div>';
		
	}
	
		echo '<!-- input start day of employment -->
		<div>
			<label for="startDate" class="inputLabel" >Początek zatrudnienia:</label>
			<input type="text" id="startDate" name="startDate" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="np. 2021-01-18" required>
		</div>';
	
	
	// gets to dropdown list all available warehouses ( those that don't have warehouseman assigned)
	
	$result_warehouse = pg_query($db_conn, "SELECT * from proj.magazyny_lista WHERE imie IS NULL AND nazwisko IS NULL;");
	$row_warehouse = pg_num_rows($result_warehouse);
	debug_to_console($result_warehouse);
		
	if ( $row_warehouse > 0 ){
		
		$mainWarehouse = $row_warehouse['glowny']=='t' ? 'tak' : 'nie';
		
		echo '<div >';
		echo '<label for="warehouseNo" class="selectLabel" >Magazyn: </label>';
		echo '	<select name="warehouseNo" id="warehouseNo" required>';
		echo '	<option value="" selected disabled hidden> Wybierz jeśli stanowisko: magazynier </option>';
		while ($row_warehouse = pg_fetch_assoc($result_warehouse)) 
		{
			echo '		<option value="'.$row_warehouse["id_magazyn"].'" >id: '.$row_warehouse["id_magazyn"].' | główny: '.$mainWarehouse.' | adres: '.$row_warehouse["ulica_i_numer"].' '.$row_warehouse["miasto"].'</option>';	
		}
		echo '	</select> </div>';
		
	}

}
?>
	<!-- name imput -->
	<div >
		<label for="name" class="inputLabel" >Imię:</label>
		<input type="text" id="name" name="name">
	</div>
	
	<!-- surname imput -->
	<div >
		<label for="surname" class="inputLabel" >Nazwisko:</label>
		<input type="text" id="surname" name="surname">
	</div>
	
	<!-- email imput -->
	<div >
		<label for="email" class="inputLabel" >Email:</label>
		<input type="email" id="email" name="email">
	</div>
	
	<!-- phone number imput -->
	<div >
		<label for="phone" class="inputLabel" >Nr telefonu:</label>
		<input type="text" id="phone" name="phone" pattern="[0-9]{9}">
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
			<input type="text"  id="postcode" name="postcode" pattern="[0-9]{2}[-][0-9]{3}" placeholder="00-000" required>
		</div>
		<div >
			<label for="city" class="inputLabel" >Miasto:</label>
			<input type="text"  id="city" name="city" required>
		</div>
		
		<br><br>
		<div>
			<label for="log" class="inputLabel" >Login:</label>
			<input type="text"  id="log" name="log" required>
		</div>
		
		<div >
			<label for="pass" class="inputLabel" >Hasło:</label>
			<input type="password" id="pass" name="pass" required>
		</div>
		
		<input  type="submit" name="submitAddEmployee"  value="Dodaj do bazy"   id="submitAddEmployee" class="btn btn-primary">

	</form>
	<p id="info">etst</p>
</div>

		
<?php

if($_SESSION['role']=='kierownik')
{
	if(isset($_POST['submitAddEmployee']))
	{
		$position = $_POST['empPosition'];
		$startDate = $_POST['startDate'];
		$warehouseNo = $_POST['warehouseNo'];
		
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];

		$street = $_POST['street'];

		$postcode = $_POST['postcode'];
		$city = $_POST['city'];
		$log = $_POST['log'];
		$pass = $_POST['pass'];
		//debug_to_console($position);
		//debug_to_console($startDate);
		//debug_to_console($warehouseNo);
		//debug_to_console($name);
		//debug_to_console($surname);
		//debug_to_console($email);
		//debug_to_console($phone);
		//debug_to_console($street);
		//debug_to_console($postcode);
		//debug_to_console($city);
		//debug_to_console($log);
		//debug_to_console($pass);
		
		if( isset($position) && isset($startDate) && isset($name) && isset($surname) && isset($email) && isset($phone) && isset($street) && isset($postcode) && isset($city) && isset($log) && isset($pass) )
		{
			if( isset($warehouseNo) )
			{
				$result = pg_query($db_conn, "SELECT proj.dodajPracownika('$position', '$startDate', '$warehouseNo', '$name', '$surname', '$email', '$phone', '$street',  '$postcode', '$city', '$log', '$pass');");
			}
			else
			{
				$result = pg_query($db_conn, "SELECT proj.dodajPracownika($position, $startDate, null, $name, $surname, $email, $phone, $street, $postcode, $city, $log, $pass);");
			}
			//debug_to_console($result);
			$rows = pg_num_rows($result);
			//debug_to_console(pg_fetch_result($result));
			
			if ( $rows > 0 ) {
				//debug_to_console("test1");
				while ($row = pg_fetch_row($result)) 
				{	
					//debug_to_console("test2");
					
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Pracownik dodany do bazy";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Pracownik o takich danych już istnieje w bazie";</script>';
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

		
		<script type="text/javascript" src="js/add_employee.js"></script> 
    </body>
</html>