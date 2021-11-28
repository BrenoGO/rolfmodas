function formpagam(cod,total,pedido,comissao) {

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		document.getElementById("duplicatas").innerHTML=this.responseText;
		if(cod == 0){
			document.getElementById("serecebido").style.display="inline";
		}else{
			document.getElementById("serecebido").style.display="none";
		}
	}
    }
	xmlhttp.open("GET","_AJAX/dupliparc.php?cod="+cod+"&pedido="+pedido+"&total="+total+"&comissao="+comissao,true);
	xmlhttp.send();
	
	if(cod == 0){
		document.getElementById("duplicaparceladas").innerHTML="";
	}
}
function numparcelas(parcelas,total,difprazo,pedido,comissao) {

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		  document.getElementById("duplicaparceladas").innerHTML=this.responseText;
	}
    }
	xmlhttp.open("GET","_AJAX/duplicatas.php?parcelas="+parcelas+"&total="+total+"&difprazo="+difprazo+"&pedido="+pedido+"&comissao="+comissao,true);
	xmlhttp.send();
}
function parcmanualmente(total,parcelas){
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			  document.getElementById("duplicaparceladas").innerHTML=this.responseText;
		}
    }
	xmlhttp.open("GET","_AJAX/parcmanu.php?total="+total+"&parcelas="+parcelas,true);
	xmlhttp.send();
}
function mudavaloracobrar(parcelas,total){
	total = total.toFixed(2);
	var somaparcelas=parseFloat(0);
	
	for (i=1;i<=parcelas;i++){
		x = document.getElementById("valorparc"+i).value.replace(",",".");
		if(x != ""){
			y=parseFloat(x);
			somaparcelas += y;
		}	
	}
	novo = (total - somaparcelas).toFixed(2);
	result = novo.toString().replace(".",",");
	document.getElementById("valoracob").value= result;
}