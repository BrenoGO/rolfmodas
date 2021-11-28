function selectforn(CC) {

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		document.getElementById("fornselection").innerHTML=this.responseText;
	}
    }
	xmlhttp.open("GET","_AJAX/selectforn.php?CC="+CC,true);
	xmlhttp.send();
	
}

function formcadastCC(){
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		document.getElementById("cadastnovoCC").innerHTML=this.responseText;
		document.getElementById("selectdoCC").innerHTML="";
		document.getElementById("fornselection").innerHTML="";
	}
    }
	xmlhttp.open("GET","_AJAX/cadastronovoCC.php",true);
	xmlhttp.send();
}

function formcadastForn(){
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		document.getElementById("cadastnovoForn").innerHTML=this.responseText;
		document.getElementById("fornselection").innerHTML="";
	}
    }
	xmlhttp.open("GET","_AJAX/cadastronovoForn.php",true);
	xmlhttp.send();
}

function parcelagasto(parcelas,valor){
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		document.getElementById("parcelamentogasto").innerHTML=this.responseText;
	}
    }
	xmlhttp.open("GET","_AJAX/parcelagasto.php?parcelas="+parcelas+"&valor="+valor,true);
	xmlhttp.send();
}