
var CountProds = 1;

$(document).ready(function(){
	$(document).on("keyup", '#inputTextLs', function(){
		$.ajax({
			type:'GET',
			url:'_ajax/lsforn.php',
			data:'q='+$('#inputTextLs').val(),
			success:function(msg){
				$('#lsforn').css({'display': 'block','border':'1px solid #A5ACB2'});
				$('#lsforn').html(msg);
			}
		})
	});
	$(document).on("change", '.inputRef', function(){
		$.ajax({
			type:'Get',
			url:'_ajax/pesqRef.php',
			data:'ref='+$(this).val(),
			success:function(bool){
				if(bool == 'true'){
					alert('Referência Existente!');
				}
			}
		})
	});
});

function lsforn(q){
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
		document.getElementById("lsforn").innerHTML=this.responseText;
		document.getElementById("lsforn").style.border="1px solid #A5ACB2";
		document.getElementById("lsforn").style.display="block";
	}
  }
  xmlhttp.open("GET","_ajax/lsforn.php?q="+q,true);
  xmlhttp.send();
}




function OcultaForm(){
	document.getElementById("formForn").style.display='none';
}


function FormCompra(id){
	//Apagando o que estava escrito (enquanto fazia o LS)
	document.getElementById("inputTextLs").value="";
	var div = document.getElementById("div_rsocial");
	if (div){
		if (div.parentNode) {
			div.parentNode.removeChild(div);
		}
	}

	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			
			var str=this.responseText;
			
			var array = str.split('/');
			
			document.getElementById("rsocial").value=array[0];
			document.getElementById("fantasia").value=array[1];
			document.getElementById("data_nascimento").value=array[2];
			document.getElementById("cnpj").value=array[3];
			document.getElementById("ie").value=array[4];
			document.getElementById("CEP").value=array[5];
			document.getElementById("estado").value=array[6];
			document.getElementById("cidade").value=array[7];
			document.getElementById("bairro").value=array[8];
			document.getElementById("logradouro").value=array[9];
			document.getElementById("numero").value=array[10];
			document.getElementById("complem").value=array[11];
			document.getElementById("contato").value=array[12];
			document.getElementById("email").value=array[13];
			document.getElementById("outroscontatos").value=array[14];
			document.getElementById("id_usuario").value=id;
			
			
			document.getElementById("cadastForn").style.display='block';
			document.getElementById("lsforn").style.display='none';
			if(id != 0){
				document.getElementById("butAltCad").value='Alterar dados e confirmar';
				document.getElementById("butConfirmar").style.display='inline-block';
			}else{
				document.getElementById("butAltCad").value='Cadastrar';
				document.getElementById("butConfirmar").style.display='none';
			}
			
		}
	}
	xmlhttp.open("GET","_ajax/fillFornCompra.php?id="+id,true);
	xmlhttp.send();
}

function ConfForn(){
	var div = document.getElementById("div_rsocial");
	if (div){
		if (div.parentNode) {
			div.parentNode.removeChild(div);
		}
	}
	var razao = document.getElementById("rsocial").value;
	document.getElementById("cadastForn").style.display="none";
	var divNova = document.createElement("div");
	divNova.setAttribute('id','div_rsocial');
	divNova.style.border="1px solid #A5ACB2";
			
	divFornecedor = document.getElementById("Fornecedor");
	divNova.innerHTML='Razão Social:'+razao+"<img src='../../_imagens/checked-of.png' style='height: 20px; width: 20px;'/>";
	divFornecedor.appendChild(divNova);		
	var div = document.getElementById("Produto1");
	if(!div){
		adicProd();
	}
	
}

function AltForn(){
	var rsocial = document.getElementById("rsocial").value;
	var fantasia = document.getElementById("fantasia").value;
	var nasc = document.getElementById("data_nascimento").value;
	var cnpj = document.getElementById("cnpj").value;
	var ie = document.getElementById("ie").value;
	var cep = document.getElementById("CEP").value;
	var uf = document.getElementById("estado");
	if(!uf){
		estado = document.getElementById("optuf1").value;
	}else{
		estado=uf.value;
	}
	var cid = document.getElementById("cidade");
	if(!uf){
		cidade = document.getElementById("optcid1").value;
	}else{
		cidade=cid.value;
	}	
	var bairro = document.getElementById("bairro").value;
	var log = document.getElementById("logradouro").value;
	var num = document.getElementById("numero").value;
	var comp = document.getElementById("complem").value;
	var cont = document.getElementById("contato").value;
	var email = document.getElementById("email").value;
	var outcont = document.getElementById("outroscontatos").value;
	var id = document.getElementById("id_usuario").value;
	var toSend = "rsocial="+rsocial+"&fantasia="+fantasia+"&nasc="+nasc+"&cnpj="+cnpj+"&ie="+ie+"&cep="+cep+"&estado="+estado+"&cidade="+cidade+"&bairro="+bairro+"&log="+log+"&num="+num+"&comp="+comp+"&cont="+cont+"&email="+email+"&outcont="+outcont+"&id="+id;
	
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			var ResT=this.responseText;
			if(ResT == "Alteracao"){
				ConfForn();
			}else if(ResT.includes("Cadastro")){
				var Split =ResT.split("=");
				var NovoId = Split[1];
				document.getElementById("id_usuario").value=NovoId;
				ConfForn();
			}else{
				alert(ResT);
			}
		
		}
	}
	xmlhttp.open("POST","_ajax/AltForn.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(toSend);

}



function adicProd(){
		w=CountProds;
		id_forn=document.getElementById("id_usuario").value;
		
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			var divNova = document.createElement("div");
			divNova.setAttribute('id','Produto'+w);
			divNova.style.border="1px solid #A5ACB2";
			divNova.style.margin = "3px 0";
			
			
			divProdutos = document.getElementById("Produtos");
			divNova.innerHTML=this.responseText;
			divProdutos.appendChild(divNova);
			
			//document.getElementById("Produtos").innerHTML=this.responseText;
			CountProds++;
		}
	}
	xmlhttp.open("GET","_ajax/AddProd.php?w="+w+"&id_forn="+id_forn,true);
	xmlhttp.send();
	/*if(deleteProd()){
		if(!(w+1)){
			w--;
		}
	}*/
}

function lsbuscaprod(str,w) {
  if (str.length==0) { 
    document.getElementById("lsbuscaprod"+w).style.display="none";
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("lsbuscaprod"+w).style.display="block";
	  document.getElementById("lsbuscaprod"+w).innerHTML=this.responseText;
      document.getElementById("lsbuscaprod"+w).style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","_ajax/buscaprod.php?q="+str+"&w="+w,true);
  xmlhttp.send();
}


function FormProd(ref,w){
	document.getElementById("inputTextLsProd"+w).value="";
	
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			if(ref != 0){
				var str=this.responseText;
				
				var array = str.split(';');
				document.getElementById("bool_novo"+w).value=false;
				document.getElementById("ref"+w).value=array[0];
				document.getElementById("desc"+w).value=array[1];
				document.getElementById("tamanhos"+w).value=array[2];
				document.getElementById("preco"+w).value=array[3];
				document.getElementById("secao"+w).value=array[4];
				document.getElementById("NCM"+w).value=array[5];
				
				document.getElementById("tamanhos"+w).style.display="none";
				document.getElementById("secao"+w).style.display="none";
				document.getElementById("NCM"+w).style.display="none";
				document.getElementById("lab_tamanhos"+w).style.display="none";
				document.getElementById("lab_secao"+w).style.display="none";
				document.getElementById("lab_NCM"+w).style.display="none";
				
				document.getElementById("cadastProd"+w).style.display='block';
				document.getElementById("lsbuscaprod"+w).style.display='none';
			}else{
				document.getElementById("cadastProd"+w).style.display='block';
				document.getElementById("lsbuscaprod"+w).style.display='none';
			}
		}
	}
	xmlhttp.open("GET","_ajax/fillProd.php?ref="+ref+"&w="+w,true);
	xmlhttp.send();
}

function deleteProd(w){
	var node = document.getElementById("Produto"+w);
	if (node.parentNode) {
		node.parentNode.removeChild(node);
	}

}

function FinalCompraProd(w){
	var bool_novo=document.getElementById("bool_novo"+w).value;
	var ref = document.getElementById("ref"+w).value;
	var Qnt = document.getElementById("Qnt"+w).value;;
	var cor= document.getElementById("cor"+w).value;;
	var tam= document.getElementById("tam"+w).value;;
	
	var id_forn=document.getElementById("id_usuario").value;
	
	var preco = document.getElementById("preco"+w).value;
	
	var toSend = "w="+w+"&bool_novo"+w+"="+bool_novo+"&id_forn="+id_forn+"&ref"+w+"="+ref+"&Qnt"+w+"="+Qnt+"&preco"+w+"="+preco+"&cor"+w+"="+cor+"&tam"+w+"="+tam;
	if(bool_novo=="true"){
		var desc = document.getElementById("desc"+w).value;
		var tamanhos = document.getElementById("tamanhos"+w).value;
		
		var secao = document.getElementById("secao"+w).value;
		var NCM = document.getElementById("NCM"+w).value;
		
		toSend += "&desc"+w+"="+desc+"&tamanhos"+w+"="+tamanhos+"&secao"+w+"="+secao+"&NCM"+w+"="+NCM;
		
	}
	
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		hr=new XMLHttpRequest();
	} else { // code for IE6, IE5
		hr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	hr.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			document.getElementById("Produto"+w).innerHTML=this.responseText;
		}
	}
	
	hr.open("POST","_ajax/FinCompra.php",true);
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	hr.send(toSend);
	
}

