function add_prod_nf_dev(contador) {
	/*NAO FIZ NADA AQUI AINDA!*/
	/*NAO FIZ NADA AQUI AINDA!*/
	/*NAO FIZ NADA AQUI AINDA!*/
	/*NAO FIZ NADA AQUI AINDA!*/

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
