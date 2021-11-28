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
	xmlhttp.open("GET","../_ajax/verif_CEP.php?CEP="+CEP,true);
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
