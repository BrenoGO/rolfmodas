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
function lscliente(str) {
    if (str.length==0) { 
		document.getElementById("lsclient").innerHTML="";
		document.getElementById("lsclient").style.border="0px";
		return;
	}
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		  document.getElementById("lsclient").innerHTML=this.responseText;
		  document.getElementById("lsclient").style.border="1px solid #A5ACB2";
	}
    }
	xmlhttp.open("GET","_AJAX/buscacliente.php?q="+str,true);
	xmlhttp.send();
}
function lsclientebuscarec(str) {
    if (str.length==0) { 
		document.getElementById("lsclient").innerHTML="";
		document.getElementById("lsclient").style.border="0px";
		return;
	}
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		  document.getElementById("lsclient").innerHTML=this.responseText;
		  document.getElementById("lsclient").style.border="1px solid #A5ACB2";
	}
    }
	xmlhttp.open("GET","_AJAX/buscaclientebuscarec.php?q="+str,true);
	xmlhttp.send();
}

function preencherdoc(doc){
	document.getElementById("docparc1").value=doc;
}
function num_parcs(num_parcs,ini_num_doc) {

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		  document.getElementById("parceladas").innerHTML=this.responseText;
	}
    }
	xmlhttp.open("GET","_AJAX/parcelas_reneg.php?num_parcs="+num_parcs+"&ini_num_doc="+ini_num_doc,true);
	xmlhttp.send();
}
function mudarRepPed(idusuario,pedido) {

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.open("GET","../financeiro/_AJAX/mudarRepPed.php?idusuario="+idusuario+"&pedido="+pedido,true);
	xmlhttp.send();
}
function AddDoc(acao,num_doc) {

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	if (this.readyState==4 && this.status==200) {
		  document.getElementById("docspbaixar").innerHTML=this.responseText;
	}
    }
	xmlhttp.open("GET","_AJAX/adddoc.php?acao="+acao+"&num_doc="+num_doc,true);
	xmlhttp.send();
}