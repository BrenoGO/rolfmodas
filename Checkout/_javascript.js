function form_endereco(){
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("form_end_novo").style.display="block"
	  document.getElementById("form_end_novo").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","_AJAX/form_end.php",true);
  xmlhttp.send();
}
function displaynone_novo_end(){
	 document.getElementById("form_end_novo").style.display="none"
	  document.getElementById("form_end_novo").innerHTML="";
}
function tot_bonus(bonus,valor_pedido){
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			document.getElementById("rest_pag").value=this.responseText;
		}
	}
	xmlhttp.open("GET","_AJAX/tot_bonus.php?bonus="+bonus+"&valor_pedido="+valor_pedido,true);
	xmlhttp.send();
}
function tot_bonus_pe(bonus,valor_pedido){
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			document.getElementById("rest_pag_pe").value=this.responseText;
		}
	}
	xmlhttp.open("GET","_AJAX/tot_bonus.php?bonus="+bonus+"&valor_pedido="+valor_pedido,true);
	xmlhttp.send();
}
function tot_bonus_ped(bonus,valor_pedido){
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			document.getElementById("rest_pag_ped").value=this.responseText;
		}
	}
	xmlhttp.open("GET","_AJAX/tot_bonus.php?bonus="+bonus+"&valor_pedido="+valor_pedido,true);
	xmlhttp.send();
}
function check_tipo_pag(cartao,deposito,dinheiro,boleto) {
	if(cartao == 1){
		if(!document.getElementById("pagamento_pagseguro")){
			
		}else{
			document.getElementById("pagamento_pagseguro").style.display="block";
		}
		document.getElementById("pagamento_deposito").style.display="none";
		document.getElementById("pagamento_dinheiro").style.display="none";
		document.getElementById("pagamento_boleto").style.display="none";
	}
	if(deposito == 1){
		if(!document.getElementById("pagamento_pagseguro")){
			
		}else{
			document.getElementById("pagamento_pagseguro").style.display="none";
		}
		document.getElementById("pagamento_deposito").style.display="block";
		document.getElementById("pagamento_dinheiro").style.display="none";
		document.getElementById("pagamento_boleto").style.display="none";
	}
	if(dinheiro == 1){
		if(!document.getElementById("pagamento_pagseguro")){
			
		}else{
			document.getElementById("pagamento_pagseguro").style.display="none";
		}
		document.getElementById("pagamento_deposito").style.display="none";
		document.getElementById("pagamento_dinheiro").style.display="block";
		document.getElementById("pagamento_boleto").style.display="none";
	}
	if(boleto == 1){
		if(!document.getElementById("pagamento_pagseguro")){
			
		}else{
			document.getElementById("pagamento_pagseguro").style.display="none";
		}
		document.getElementById("pagamento_deposito").style.display="none";
		document.getElementById("pagamento_dinheiro").style.display="none";
		document.getElementById("pagamento_boleto").style.display="block";
	}
}
function check_tipo_pag_pe(cartao,deposito,dinheiro,boleto) {
	if(cartao == 1){
		if(!document.getElementById("pagamento_pagseguro_pe")){
			
		}else{
			document.getElementById("pagamento_pagseguro_pe").style.display="block";
		}
		document.getElementById("pagamento_deposito_pe").style.display="none";
		document.getElementById("pagamento_dinheiro_pe").style.display="none";
		document.getElementById("pagamento_boleto_pe").style.display="none";
	}
	if(deposito == 1){
		if(!document.getElementById("pagamento_pagseguro_pe")){
			
		}else{
			document.getElementById("pagamento_pagseguro_pe").style.display="none";
		}
		document.getElementById("pagamento_deposito_pe").style.display="block";
		document.getElementById("pagamento_dinheiro_pe").style.display="none";
		document.getElementById("pagamento_boleto_pe").style.display="none";
	}
	if(dinheiro == 1){
		if(!document.getElementById("pagamento_pagseguro_pe")){
			
		}else{
			document.getElementById("pagamento_pagseguro_pe").style.display="none";
		}
		document.getElementById("pagamento_deposito_pe").style.display="none";
		document.getElementById("pagamento_dinheiro_pe").style.display="block";
		document.getElementById("pagamento_boleto_pe").style.display="none";
	}
	if(boleto == 1){
		if(!document.getElementById("pagamento_pagseguro_pe")){
			
		}else{
			document.getElementById("pagamento_pagseguro_pe").style.display="none";
		}
		document.getElementById("pagamento_deposito_pe").style.display="none";
		document.getElementById("pagamento_dinheiro_pe").style.display="none";
		document.getElementById("pagamento_boleto_pe").style.display="block";
	}
}
function check_tipo_pag_ped(cartao,deposito,dinheiro,boleto) {
	if(cartao == 1){
		if(!document.getElementById("pagamento_pagseguro_ped")){
			
		}else{
			document.getElementById("pagamento_pagseguro_ped").style.display="block";
		}
		document.getElementById("pagamento_deposito_ped").style.display="none";
		document.getElementById("pagamento_dinheiro_ped").style.display="none";
		document.getElementById("pagamento_boleto_ped").style.display="none";
	}
	if(deposito == 1){
		if(!document.getElementById("pagamento_pagseguro_ped")){
			
		}else{
			document.getElementById("pagamento_pagseguro_ped").style.display="none";
		}
		document.getElementById("pagamento_deposito_ped").style.display="block";
		document.getElementById("pagamento_dinheiro_ped").style.display="none";
		document.getElementById("pagamento_boleto_ped").style.display="none";
	}
	if(dinheiro == 1){
		if(!document.getElementById("pagamento_pagseguro_ped")){
			
		}else{
			document.getElementById("pagamento_pagseguro_ped").style.display="none";
		}
		document.getElementById("pagamento_deposito_ped").style.display="none";
		document.getElementById("pagamento_dinheiro_ped").style.display="block";
		document.getElementById("pagamento_boleto_ped").style.display="none";
	}
	if(boleto == 1){
		if(!document.getElementById("pagamento_pagseguro_ped")){
			
		}else{
			document.getElementById("pagamento_pagseguro_ped").style.display="none";
		}
		document.getElementById("pagamento_deposito_ped").style.display="none";
		document.getElementById("pagamento_dinheiro_ped").style.display="none";
		document.getElementById("pagamento_boleto_ped").style.display="block";
	}
}