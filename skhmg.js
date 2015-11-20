var titles = ["Slave 1", "Slave 2", "Broadcast mode"];

function slaveChange() { 
	var selectBox = document.getElementById("select_slave");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;
	document.getElementById("tytul").innerHTML = titles[selectedValue];
};