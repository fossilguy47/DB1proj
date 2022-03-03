var positionSelect  = document.getElementById("empPosition");
var warehouseSelect = document.getElementById("warehouseNo");
positionSelect.addEventListener("change", function() {
    if(positionSelect.value == "kierownik")
    {
        warehouseSelect.setAttribute("disabled", "disabled");
		warehouseSelect.value = null;
    }
	else{
		warehouseSelect.removeAttribute("disabled");
	}
});