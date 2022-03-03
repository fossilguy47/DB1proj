<?php
	session_start();
	if( isset($_SESSION['role']) ){
		if( $_SESSION['role']=='kierownik' ){
			echo    '<a class="navA" id="employee" href="employee.php" >Pracownicy</a>
					
					<a class="navA" id="warehouses" href="warehouses.php" >	Sklepy</a>
					';
					
		}
		if( $_SESSION['role']=='magazynier' ){
			echo   '					
					<a class="navA" id="products" href="products.php">Katalog produktów</a>
					<a class="navA" id="warehouse" href="warehouse.php">Sklep</a>
					
					<a class="navA" id="orders" href="orders.php">Zamówienia</a>
					
					<a class="navA" id="payments" href="payments.php">Płatności</a>
					';
		}
	}
	else{
		include 'logout.php';
	}
?>