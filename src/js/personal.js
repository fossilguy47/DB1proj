
function showChangingPassForm() {
	var div = document.getElementById("changePassDiv");
	if (div.style.display === "none") {
		div.style.display = "block";
	}
	else{
		div.style.display = "none";
	}
	document.getElementById('info').innerHTML = '';
}

document.getElementById('newPass1').addEventListener('input', function() {
	var pass1 = document.getElementById('newPass1').value;
	var pass2 = document.getElementById('newPass2').value;
	if (pass1 != "" && pass2 != "") {
		if (pass1 != pass2) 
		{
			document.getElementById("info").innerHTML = "Hasła się różnią";
			document.getElementById('submitNewPass').setAttribute("disabled", null);
		}
		else
		{
			document.getElementById("info").innerHTML = "";
			document.getElementById('submitNewPass').removeAttribute("disabled");
		}	
	} 
	else 
	{
		document.getElementById('submitNewPass').setAttribute("disabled", null);
	}
  }
);
document.getElementById('newPass2').addEventListener('input', function() {
	var pass1 = document.getElementById('newPass1').value;
	var pass2 = document.getElementById('newPass2').value;
	if (pass1 != "" && pass2 != "") {
		if (pass1 != pass2) 
		{
			document.getElementById("info").innerHTML = "Hasła się różnią";
			document.getElementById('submitNewPass').setAttribute("disabled", null);
		}
		else
		{
			document.getElementById("info").innerHTML = "";
			document.getElementById('submitNewPass').removeAttribute("disabled");
		}	
	} 
	else 
	{
		document.getElementById('submitNewPass').setAttribute("disabled", null);
	}
  }
);