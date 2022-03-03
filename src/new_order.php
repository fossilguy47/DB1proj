
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
					
<p class='ListHeader'><u>Nowe zamówienie</u></p><br>


<div class='newOrderFormDiv'>
	<form  id="newOrderForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>" >

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

	
	// gets to dropdown list of all clients that already bought something 
			
	}
	
?>

	<!-- client's name imput -->
	<div >
		<label for="client_surname" class="inputLabel" >Imie:</label>
		<input type="text"  id="client_surname" name="client_surname">
	</div>

	<div >
		<label for="client_name" class="inputLabel" >Nazwisko:</label>
		<input type="text"  id="client_name" name="client_name">
	</div>
	<!-- NIP imput -->
	<div >
		<label for="nip" class="inputLabel" >NIP:</label>
		<input type="text" id="nip" name="nip" pattern="[0-9]{10}" title="10 cyfr">
	</div>
	
	<!-- email imput -->
	<div >
		<label for="email" class="inputLabel" >Email:</label>
		<input type="email" id="email" name="email">
	</div>
	
	 	
	<div >
		<label for="street" class="inputLabel" >Ulica:</label>
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

	<p class="productsHeader"> Dodaj produkty do zamówienia</p>	
<?php

if($_SESSION['role']=='magazynier')
{

	$result = pg_query($db_conn, "SELECT * FROM proj.pelny_stan_magazynow WHERE id_magazyn=$id_wh AND ilosc > 0 ORDER BY dzial, nazwa_produktu;");
	$rows = pg_num_rows($result);

	
	echo'<div class="order_products">';
	
	if ( $rows > 0 )
	{	
		while ($row = pg_fetch_assoc($result)) 
		{
			$max = $row['ilosc'];
			
			echo 
			'<div  id="prod_item">
				<label for='.$row["id_produkt"].' class="inputLabel" >'.$row["nazwa_produktu"].'</label>
				<input type="number" id="form-control2" name='.$row["id_produkt"].' min="0" max="'.$max.'" value="0" required> ('.$max.')
			</div>';
		}
	}	
	echo '</div>';
}
	
?>
	<input type="submit" name="submitNewOrder"  value="Zatwierdź zamówienie"   id="submitNewOrder" class="btn btn-primary" >
	<p id="info"></p>
	
	</form>
</div>	



<?php

if($_SESSION['role']=='magazynier')
{
	if(isset($_POST['submitNewOrder']))
	{
		$name1 = $_POST['client_surname'];
		$name = $_POST['client_name'];
		$nip = $_POST['nip'];
		$email = $_POST['email'];
		
		$street = $_POST['street'];
		$postcode = $_POST['postcode'];
		$city = $_POST['city'];


		if(isset($name1) && isset($name) && isset($nip) && isset($email) && isset($street) && isset($postcode) && isset($city) )
		{
			
			$result = pg_query($db_conn, "SELECT * FROM proj.pelny_stan_magazynow WHERE id_magazyn=$id_wh AND ilosc > 0 ORDER BY dzial, nazwa_produktu;");
			$rows = pg_num_rows($result);
			$id_new_order;
			
			if ( $rows > 0 )
			{	
				
				debug_to_console($name);
				debug_to_console($name1);
				debug_to_console($email);
				debug_to_console($nip);
				debug_to_console($street);
				debug_to_console($postcode);
				debug_to_console($city);
				$res = pg_query($db_conn, "SELECT * FROM proj.noweZamowienie('$name1','$name','$email','$nip','$street','$postcode','$city');");
				
				debug_to_console($res);
				$rs = pg_num_rows($res);
				
				
			
				if ( $rs > 0 ) {
					while($r = pg_fetch_row($res)) 
					{						
						$id_new_order=$r[0];
					}
				}
				else
				{
					echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy tworzeniu nowego zamówienia";</script>';
				}
				
				if($id_new_order!=null)
				{
					while ($row = pg_fetch_assoc($result)) 
					{
						$id_pr = $row['id_produkt'];
						$val = $_POST[$id_pr];
						$re = pg_query($db_conn, "SELECT proj.dodajDoZamowienia($id_new_order, $id_pr, $id_wh, $val);");
						debug_to_console($re);
					}
					echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Zamówienie zostało zarejestrowane";</script>';
				}
				else
				{
					echo '<script type="text/javascript">document.getElementById("info").innerHTML = "Błąd przy tworzeniu nowego zamówienia";</script>';
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