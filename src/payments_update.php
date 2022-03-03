
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>Sklep spozywczy</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/personal.css">
	<link rel="stylesheet" href="css/list.css">
	<link rel="stylesheet" href="css/payments_update.css">
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
					
<p class='ListHeader'><u>Zaktualizuj status płatności</u></p><br>


<div class='updatePaymentStatusFormDiv'>
	<form id="updatePaymentStatusForm" class="updatePaymentStatusForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

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

	// get id of warehouse managed by a logged warehouseman
	$result = pg_query($db_conn, "SELECT id_magazyn FROM proj.sprzedawca WHERE id_sprzedawca=$id;");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) 
	{
		while ($row = pg_fetch_assoc($result)) {
			$id_wh = $row['id_magazyn'];
		}
	}	
	
	// gets to dropdown list all pending payments
	
	$result = pg_query($db_conn, "SELECT * FROM proj.platnosci_lista WHERE id_magazyn=$id_wh  ORDER BY id_metoda_platnosci;");
	$rows = pg_num_rows($result);
	
	echo '<div >';
	echo '<label for="payment" class="selectLabel" >Wybierz płatność: </label>';
	echo '<select id="payment" name="payment"  required>';
	echo '	<option value="" selected disabled hidden> - </option>';
		
	if ( $rows > 0 ){
		while ($row= pg_fetch_assoc($result))
		{
			echo '<option value="'.$row["id_metoda_platnosci"].','.$row["id_zamowienie"].','.$row["id_klient"].'">-- id płat.: '.$row["id_metoda_platnosci"].' -- id zamów.: '.$row["id_zamowienie"].' -- '.$row["nazwisko"].' -- kwota: ' .$row["kwota"].' -- status: ' .$row["status_platnosci"].' --</option>';
		}
	}
	echo '	</select></div>';	
	
}
	
?>
	<br><div >
		<label for="newStatus" class="selectLabel">Wybierz nowy status:</label>
		<select name="newStatus" id="newStatus"  required>
			<option value="" selected disabled hidden> - </option>
			<option value="zakonczona">zakonczona</option>
			<option value="oczekujaca">oczekujaca</option>
		</select>
	</div>

	<input  type="submit" name="submitPaymentStatus"  value="Zaktualizuj status płatności"   id="submitPaymentStatus" class="btn btn-primary" >

	<p id="info"></p>
	</form>	
	
</div>	



<?php

if($_SESSION['role']=='magazynier')
{
	if(isset($_POST['submitPaymentStatus']))
	{
		$all_info = $_POST['payment'];	
		$newStatus = $_POST['newStatus'];

		if( isset($all_info) && isset($newStatus))
		{
			$all_info = explode(",",$all_info);
			$id_payment = $all_info[0];
			$id_order = $all_info[1];
			$id_client = $all_info[2];	
			//echo '<script type="text/javascript">console.log("'.$all_info.'");</script>';	
			//echo '<script type="text/javascript">console.log("'.$all_payment.'");</script>';
			//echo '<script type="text/javascript">console.log("'.$all_order.'");</script>';	
			//echo '<script type="text/javascript">console.log("'.$all_client.'");</script>';	

			debug_to_console($id_payment);
			debug_to_console($id_order);
			debug_to_console($id_client);
			debug_to_console($newStatus);

			
			$result = pg_query($db_conn, "SELECT proj.zaktualizujplatnosc('$id_payment', '$id_order', '$id_client', '$newStatus');");
			debug_to_console($result);
			$rows = pg_num_rows($result);
			
			
			if ( $rows > 0 ) {
				while ($row = pg_fetch_row($result)) 
				{						
					if( $row[0]=='t')
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Zaktualizowano status płatności";</script>';

					}
					else
					{
						echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy aktualizowaniu danych w bazie";</script>';
					}
				}
			}
			else
			{
				echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd bazy danych";</script>';
			}
		}
		else
		{
			echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd";</script>';
		}
		

	}
}
?>				
			</div>
			
		</div>


		<script type="text/javascript" src="js/payments_update.js"></script> 
    </body>
</html>