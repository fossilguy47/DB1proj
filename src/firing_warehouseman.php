
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Sklep spozywczy</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/list.css">
	<link rel="stylesheet" href="css/firing_warehouseman.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</head>

    <body>
		
		<divid="main">
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
					
<p class='ListHeader'><u>Zwalnianie pracownika </u></p><br>

<div class='fireWarehousemanFormDiv'>
	<form  id="fireWarehousemanForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >
	
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
			// gets to dropdown list all hired warehouseman
			
			$result = pg_query($db_conn, "SELECT * FROM proj.pracownicy_lista WHERE nazwa_stanowiska='magazynier' AND koniec_zatrudnienia IS NULL;");
			$rows = pg_num_rows($result);
				
			if ( $rows > 0 )
			{	
				echo '<div class="form-group">';
				echo '<label for="warehouseman" class="selectLabel" >Pracownik: </label>';
				echo '	<select name="warehouseman" id="warehouseman"  class="form-control"  required>';
				echo '	<option value="" selected disabled hidden> - </option>';
				while ($row = pg_fetch_assoc($result)) 
				{
					echo '		<option value="'.$row["id_sprzedawca"].'">'.$row["imie"].' '.$row["nazwisko"]. ' ( id magazynu: '.$row["id_magazyn"].' )</option>';	
				}
				echo '	</select> </div>';
			}
		}
		?>

		<input  type="submit" name="submitFiring"  value="Zwalniam pracownika"   id="submitFiring" class="btn btn-primary" >

	<p id="info"></p>
	</form>
</div>	
	
<?php

if($_SESSION['role']=='kierownik')
{
	if(isset($_POST['submitFiring']))
	{
		$id_emp = $_POST['warehouseman'];
		
		echo '<script type="text/javascript">console.log("id='.$id_emp.'")</script>';
		echo '<script type="text/javascript">console.log("ida='.$_POST['warehouseman'].'")</script>';
		
		if( isset($id_emp) )
		{
			
			$result = pg_query($db_conn, "SELECT proj.zwolnijMagazyniera($id_emp);");
			$rows = pg_num_rows($result);
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{					
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Pracownik został dzisiaj zwolniony";</script>';
					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd";</script>';
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