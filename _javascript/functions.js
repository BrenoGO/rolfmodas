function up_tipo_pessoa(tipo){
	if(tipo=='F'){
		document.getElementById("span_rsocial").innerHTML="Nome Completo: ";
		document.getElementById("span_fantasia").innerHTML="Como deseja ser chamado(a): ";
		document.getElementById("span_data_nascimento").innerHTML="Data de Nascimento: ";
		document.getElementById("span_cpf_cnpj").innerHTML="CPF: ";
		document.getElementById("span_IE_RG").innerHTML="RG: ";
		document.getElementById("tp_js").value="F";
	
		
	}if(tipo=='J'){
		document.getElementById("span_rsocial").innerHTML="Razão Social: ";
		document.getElementById("span_fantasia").innerHTML="Nome Fantasia: ";
		document.getElementById("span_data_nascimento").innerHTML="Data do CNPJ: ";
		document.getElementById("span_cpf_cnpj").innerHTML="CNPJ: ";
		document.getElementById("span_IE_RG").innerHTML="Inscrição Estadual: ";
		document.getElementById("tp_js").value="J";
		
	}
}
function lsbuscausuario(str,destino) {
  if (str.length==0) { 
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";
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
      document.getElementById("livesearch").innerHTML=this.responseText;
      document.getElementById("livesearch").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","../_AJAX/buscausuario.php?q="+str+"&destino="+destino,true);
  xmlhttp.send();
}
function selectcidade(estado) {
  
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("ajaxcidade").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","../_AJAX/ajaxcidade.php?estado="+estado,true);
  xmlhttp.send();
}
function cepcorreios(cep) {
  
  if(strlen(cep)=8)
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("ajaxcidade").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","../_AJAX/ajaxcidade.php?estado="+estado,true);
  xmlhttp.send();
}
function formatar(tipo, documento){
  if(tipo=='J'){
	mascara='##.###.###/####-##'; 
  }else if(tipo=='F'){
	  mascara='###.###.###-##';
  }else{
	mascara=tipo;
  }
  
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
		documento.value += texto.substring(0,1);
  }
}
function checkdocuser(doc){
	
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			if(this.responseText.length > 100){
				document.getElementById("formprecadastro").innerHTML=this.responseText;
			}
			
		}
	}
	xmlhttp.open("GET","../_AJAX/checkdocuser.php?doc="+doc,true);
	xmlhttp.send();
	
}
function checkCard(num){
	var msg = Array();
	var tipo = null;
	
	if(num.length > 16 || num[0]==0){
		
		msg.push("Número de cartão inválido");
		
	} else {
		
		var total = 0;
		var arr = Array();
		
		for(i=0;i<num.length;i++){
			if(i%2==0){
				dig = num[i] * 2;
					
				if(dig > 9){
					dig1 = dig.toString().substr(0,1);
					dig2 = dig.toString().substr(1,1);
					arr[i] = parseInt(dig1)+parseInt(dig2);
				} else {
					arr[i] = parseInt(dig);
				}
							
				total += parseInt(arr[i]);
	
			} else {
	
				arr[i] =parseInt(num[i]);
				total += parseInt(arr[i]);
			} 
		}
				
		switch(parseInt(num[0])){
			case 0:
				msg.push("Número incorreto");
				break;
			case 1:
				tipo = "Empresas Aéreas";
				break;
			case 2:
				tipo = "Empresas Aéreas";
				break
			case 3:
				tipo = "Viagens e Entretenimento";
				if(parseInt(num[0]+num[1]) == 34 || parseInt(num[0]+num[1])==37){	operadora = "Amex";	} 
				if(parseInt(num[0]+num[1]) == 36){	operadora = "Diners";	} 
				break
			case 4:
				tipo = "Bancos e Instituições Financeiras";
				operadora = "Visa";
				break
			case 5:
				if(parseInt(num[0]+num[1]) >= 51 && parseInt(num[0]+num[1])<=55){	operadora = "Mastercard";	} 
				tipo = "Bancos e Instituições Financeiras";
				operadora = "Mastercard"
				break;
			case 6:
				tipo = "Bancos e Comerciais";
				operadora = "";
				break
			case 7:
				tipo = "Companhias de petróleo";
				operadora = "";
				break
			case 8:
				tipo = "Companhia de telecomunicações";
				operadora = "";
				break
			case 9:
				tipo = "Nacionais";
				operadora = "";
				break
			default:
				msg.push("Número incorreto");
				break;
			}

	}
	
	if(msg.length>0){	
	
		document.getElementById("div_bandeira").style.display="none";	
		console.log(msg);
		

	} else {
	
	console.log(num);
	
		if(total%10 == 0){
			console.log("Cartão válido: ("+total+")");
			console.log("Tipo: " + tipo);
			console.log("Operadora: " + operadora);
		} else {
			console.log("Cartão inválido: ("+total+")");
		}
		document.getElementById("div_bandeira").style.display="inline";
		document.getElementById("img_bandeira").src = "../_imagens/"+operadora+"_card.png";
	}	
}

function add_frete_no_valor(frete,totalgeral){
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
	  document.getElementById("valor_pagamento").value=this.responseText;
    }
  }
  xmlhttp.open("GET","../_AJAX/add_frete_no_valor.php?frete="+frete+"&totalgeral="+totalgeral,true);
  xmlhttp.send();
}
function update_valor(totalgeral){
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
	  document.getElementById("uso_bonus").value=this.responseText;
    }
  }
  xmlhttp.open("GET","../_AJAX/update_valor.php?totalgeral="+totalgeral,true);
  xmlhttp.send();
}
function verif_CEP(CEP){
	if(document.getElementById("optcid1") !== null){
		document.getElementById("optuf1").innerHTML='carregando...';
		document.getElementById("optcid1").innerHTML='carregando...';
	}
		
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
		
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		
		if (this.readyState==4 && this.status==200) {
			preenche_inf_CEP(this.responseText);
			//document.getElementById("ajax_CEP").innerHTML=this.responseText;
			
		}
	}
	xmlhttp.open("GET","../_AJAX/verif_CEP.php?CEP="+CEP,true);
	xmlhttp.send();
}
function preenche_inf_CEP(str){
	array=str.split(",");
	if( (array[5] !== undefined) || (array[1].length < 1) ){
		if(document.getElementById("estado") == undefined){
			alert("CEP inválido");
			location.reload();
		}else{
			document.getElementById("estado").disabled=false;
			document.getElementById("cidade").disabled=false;
			document.getElementById("optuf1").innerHTML='Selecione seu Estado';
			document.getElementById("optcid1").innerHTML='Selecione seu Estado';
		}
			
	}else{
		document.getElementById("div_estado").innerHTML=
		'<input class="fl" type="text" name="estado" id="optuf1" placeholder="Digite seu CEP" readonly/>';
		document.getElementById("ajaxcidade").innerHTML=
		'<input class="fl" type="text" name="cidade" id="optcid1" placeholder="Digite seu CEP" readonly/>';
		document.getElementById("optcid1").value=array[0];
		document.getElementById("optuf1").value=array[1];
		if(array[2] !== undefined){
			document.getElementById("bairro").value=array[2];
			document.getElementById("logradouro").value=array[3];
		}else{
			document.getElementById("bairro").value="";
			document.getElementById("logradouro").value="";
		}
	}
}

function isset ()
{
  var a = arguments,
    l = a.length,
    i = 0,
    undef;

  if (l === 0)
  {
    throw new Error('Empty isset');
  }

  while (i !== l)
  {
    if (a[i] === undef || a[i] === null)
    {
      return false;
    }
    i++;
  }
  return true;
}
/*
function meu_callback(conteudo) {
    
}
*/

function previa_frete(CEP,valor){
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("previa_frete").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","_AJAX/previa_frete.php?CEP="+CEP+"&valor="+valor,true);
  xmlhttp.send();
}

//Funcões para mudar o tipo do pedido abaixo:
function set_tipo_ped(){
	var select = document.getElementById("tipo_ped");
	document.cookie="tipo_ped="+select.value;
	location.reload();
}
function set_tipo_ped_PE(){
	document.cookie="tipo_ped=PE";
	location.reload();
}
function set_tipo_ped_Pedido(){
	document.cookie="tipo_ped=Pedido";
	location.reload();
}
function set_tipo_ped_Ambos(){
	document.cookie="tipo_ped=Ambos";	
	location.reload();
}

function ShowFat(){
	var fat = document.getElementById("TableFat");
	span = document.getElementById("+1")
	if (span.innerHTML == '+'){
		fat.style.display="block";
		span.innerHTML = '-';
	}else{
		fat.style.display="none";
		span.innerHTML = '+';
	}
}

function ShowNFat(){
	var nfat = document.getElementById("TableNFat");
	nspan = document.getElementById("+2")
	if (nspan.innerHTML == '+'){
		nfat.style.display="block";
		nspan.innerHTML = '-';
	}else{
		nfat.style.display="none";
		nspan.innerHTML = '+';
	}
	
}