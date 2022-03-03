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
					include 'is_logged_employed.php';
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

// Create connection
$db_conn = pg_connect(" host = $hostname dbname = $dbname user = $username password = $pass ");
pg_query($db_conn,'SET search_path TO proj');



function debug_to_console($data) {

    $output = $data;

    if (is_array($output)){

        $output = implode(',', $output);
}


    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}



if (isset($_SESSION['role']) || isset($_SESSION['name']) || isset($_SESSION['surname']) || isset($_SESSION['emp_id']) ) {

	$name = $_SESSION['name'];
	$surname = $_SESSION['surname'];
	$emp_id = $_SESSION['emp_id'];
	
	echo "<p class='hd'><u>Dane personalne: </u></p>";
	echo "<div class='personal_list'>";

	// getting position in company of the logged user (from database)
	$result = pg_query($db_conn, "SELECT u.nazwa_stanowiska FROM proj.uprawnienia u, proj.sprzedawca p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_sprzedawca='$emp_id';");
	
	$rows = pg_num_rows($result);
	
	if ( $rows > 0 ) {
		
		 while ($row = pg_fetch_assoc($result)) {
				
				debug_to_console($row);
				echo "<p><span class='personal_title'>Uprawnienia (stanowisko): </span><span class='personal_data' id='position'>" .$row['nazwa_stanowiska']. "</span></p>";
		}
	}

	// getting position's short description
	$result = pg_query($db_conn, "SELECT u.opis FROM proj.uprawnienia u, proj.sprzedawca p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_sprzedawca='$emp_id';");
	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Funkcja: </span><span class='personal_data'>" .$row['opis']. "</span></p>";
		}
	} 

	// getting user's name
	$result = pg_query($db_conn, "SELECT p.imie FROM proj.uprawnienia u, proj.sprzedawca p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_sprzedawca='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Imię: </span><span class='personal_data'>" .$row['imie']. "</span></p>";
		}
	}	 

	// getting user's surname
	$result = pg_query($db_conn, "SELECT p.nazwisko FROM proj.uprawnienia u, proj.sprzedawca p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_sprzedawca='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Nazwisko: </span><span class='personal_data'>" .$row['nazwisko']. "</span></p>";
		}
	}	

	// getting user's email
	$result = pg_query($db_conn, "SELECT p.email FROM proj.uprawnienia u, proj.sprzedawca p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_sprzedawca='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>E-mail: </span><span class='personal_data'>" .$row['email']. "</span></p>";
		}
	}

	// getting user's phone number
	$result = pg_query($db_conn, "SELECT p.telefon FROM proj.uprawnienia u, proj.sprzedawca p WHERE u.id_uprawnienia=p.id_uprawnienia AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_sprzedawca='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Telefon: </span><span class='personal_data'>" .$row['telefon']. "</span></p>";
		}
	}	

	echo "</div>";
	echo "<br><br><br><p class='hd'><u>Dane logowania</u></p>";
	echo "<div class='personal_list'>";

	// getting user's login
	$result = pg_query($db_conn, "SELECT w.login FROM proj.logowanie w, proj.uprawnienia u, proj.sprzedawca p WHERE u.id_uprawnienia=p.id_uprawnienia AND w.id_sprzedawca=p.id_sprzedawca AND p.imie='$name' AND p.nazwisko='$surname' AND p.id_sprzedawca='$emp_id';");

	$rows = pg_num_rows($result);
	if ( $rows > 0 ) {
		 while ($row = pg_fetch_assoc($result)) {
				echo "<p><span class='personal_title'>Login: </span><span class='personal_data'>" .$row['login']. "</span></p>";
		}
	}	

	echo "<p><span class='personal_title'>Zmień hasło </span><span class='personal_data'><button onclick='showChangingPassForm()' class='btn btn-default' id='showFormButton'>Formularz</button></span></p>";
	
}
?>
		<div id="changePassDiv">
			<form  id="changePassForm" method="POST" action='<?php echo $_SERVER['PHP_SELF'] ?>'> 
					Nowe hasło:<br>
					<input  type="password"  name="newPass1" id="newPass1">
					<br>Powtórz nowe hasło:<br>
					<input  type="password"  name="newPass2" id="newPass2">
					
					<input  type="submit"  name="submitNewPass" value="Zmień hasło" id="submitNewPass" class="btn btn-default" >
			</form>
			<script type="text/javascript" src="js/personal.js"></script> 
		</div>
		<p id="info"></p>
		<?php include 'change_password.php';?>
	
	</div>				
			</div>
			
		</div>



    </body>
</html>