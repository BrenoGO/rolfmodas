$(document).ready(function(){
	$(document).on('keyup','#lsProdEditPed',function(){
		$.ajax({
			type:'GET',
			url:'_AJAX/lsProdsEditPed.php',
			data:'q='+ $(this).val(),
			success:function(msg){
				if(msg == 'none'){
					$('#lsDivProds').css({'display': 'none'});
				}else{
					$('#lsDivProds').css({'display': 'block','border':'1px solid #A5ACB2'});
					$('#lsDivProds').html(msg);
				}
			}
		});
	});
});

function showlistprod(){
	document.getElementById("desapbutton").style.display="none";
	document.getElementById("listprod").style.display="inline";
}

function calc_desc(desc,ref,preco){
	desco = desc.replace(",",".");
	idpreconovo = 'preconovo-'+ref;	
	preconovo = (1-desco/100) * preco;
	precon = preconovo.toFixed(2);
	precon = precon.replace(".",",");
	document.getElementById(idpreconovo).value= precon;
}

function calc_porcent_mud_valor(valor,ref,preco){
	valor = valor.replace(",",".");
	desconto_novo = (-valor/preco+1)*100;
	desconto_novo=desconto_novo.toFixed(2);
	desconto_novo=desconto_novo.replace(".",",");
	iddesconto='desconto-'+ref;
	document.getElementById(iddesconto).value= desconto_novo;
}

function ConsigToPed(ref,cor,tam,pedCons,Ped,linha,Sit,Qnt){
	 if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById(linha).innerHTML=this.response;
	  document.getElementById(linha).style.color="green";
	   document.getElementById(linha).style.cursor="auto";
    }
  }
  xmlhttp.open("GET","_AJAX/ConsigToPed.php?ref="+ref+"&cor="+cor+"&tam="+tam+"&pedCons="+pedCons+"&Ped="+Ped+"&Sit="+Sit+"&Qnt="+Qnt,true);
  xmlhttp.send();
}
