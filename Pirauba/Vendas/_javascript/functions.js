

function lsProd(q){
	if (q.length==0) { 
		document.getElementById("lsProd").style.border="0px";
	}
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
		document.getElementById("lsProd").innerHTML=this.responseText;
		document.getElementById("lsProd").style.border="1px solid #A5ACB2";
	}
  }
  xmlhttp.open("GET","_ajax/lsProd.php?q="+q,true);
  xmlhttp.send();
}
function FormVenda(id){
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
			
			document.getElementById("ref").value=array[0];
			document.getElementById("desc").value=array[1];
			document.getElementById("cor").value=array[2];
			document.getElementById("tam").value=array[3];
			document.getElementById("custo").value=array[4];
			document.getElementById("qnt_est").value=array[5];
			
			document.getElementById("formVenda").style.display='block';
			document.getElementById("lsProd").style.display='none';
			document.getElementById("digprod").style.display='none';
			
		}
	}
	xmlhttp.open("GET","_ajax/fillVenda.php?id="+id,true);
	xmlhttp.send();
}
