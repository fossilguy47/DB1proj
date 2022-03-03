
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Sklep spozywczy</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/list.css">
	<link rel="stylesheet" href="css/employee_edit.css">
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
				<h1>Sklep spozywyczy</h1> 
			</div>
			<script type="text/javascript" src="js/navbar_emp.js"></script> 
			<div  id="contentBox" >
					
<p class='ListHeader'><u>Edytuj dane pracownika</u></p>
<p class='header_info'>( możesz edytować swoje dane oraz dane sprzedawcow )</p><br>

<div class='editEmployeeFormDiv'>
	<form  id="editEmployeeForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >


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
	// gets to dropdown data list of all warehouseman and logged manager)
			
	$result = pg_query($db_conn, "SELECT * FROM proj.pracownicy_lista WHERE (nazwa_stanowiska='magazynier' OR id_sprzedawca=".$_SESSION['emp_id'].") AND koniec_zatrudnienia IS NULL;");
	
	$rows = pg_num_rows($result);
		
	if ( $rows > 0 )
	{	
		echo '<form>';
		echo '<div>';
		echo '<label for="employeeToEdit" class="selectLabel" >Pracownik: </label>';
		echo '<select name="employeeToEdit" id="employeeToEdit"   required>';
		echo '	<option value="" selected disabled hidden> Wybierz pracownika </option>';
		while ($row = pg_fetch_assoc($result)) 
		{
			echo '		<option value="'.$row["id_sprzedawca"].'">'.$row["imie"].','.$row["nazwisko"]. ','.$row["email"].','.$row["telefon"].','.$row["login"].'</option>';	
		}
		echo '	</select> </div>';
	}

}
?>
	<!-- name imput -->
	<div >
		<label for="name" class="inputLabel" >Imię:</label>
		<input type="text"  id="name" name="name">
	</div>
	
	<!-- surname imput -->
	<div >
		<label for="surname" class="inputLabel" >Nazwisko:</label>
		<input type="text"  id="surname" name="surname">
	</div>
	
	<!-- email imput -->
	<div >
		<label for="email" class="inputLabel" >Email:</label>
		<input type="email"  id="email" name="email">
	</div>
	
	<!-- phone number imput -->
	<div >
		<label for="phone" class="inputLabel" >Nr telefonu:</label>
		<input type="text" id="phone" name="phone" pattern="[0-9]{9}">
	</div>
	
	<div>
		<label for="log" class="inputLabel" >Login:</label>
		<input type="text" id="log" name="log" required>
	</div>
	
	<input  type="submit" name="submitEditEmployee"  value="Zatwierdź"   id="submitEditEmployee" class="btn btn-primary">

	</form>
	<p id="info"></p>
</div>
	
<?php

if($_SESSION['role']=='kierownik')
{
	
	if(isset($_POST['submitEditEmployee']))
	{
		$id_emp = $_POST['employeeToEdit'];
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$log = $_POST['log'];
		debug_to_console($id_emp);
		
		if( isset($id_emp) && isset($name) && isset($surname) && isset($email) && isset($phone) && isset($log) )
		{	
			$result = pg_query($db_conn, "SELECT proj.edytujDanePracownika($id_emp,'$name','$surname','$email','$phone','$log');");
			$rows = pg_num_rows($result);
			
						
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{	
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Dane zaktualizowane";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Nieprawidłowe dane / podany login już istnieje";</script>';
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


			<script type="text/javascript" src="js/edit_employee.js"></script> 
    </body>
</html>