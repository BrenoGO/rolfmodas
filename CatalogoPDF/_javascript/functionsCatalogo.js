function lsRef(q,h){
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
        document.getElementById("lsRef"+h).style.display="block";      
        document.getElementById("lsRef"+h).innerHTML=this.responseText;
        document.getElementById("lsRef"+h).style.border="1px solid #A5ACB2";
      	if (q.length==0) { 
      		document.getElementById("lsRef"+h).style.display="none";
      	}
	    
    }
  }
  xmlhttp.open("GET","lsRef.php?q="+q+"&h="+h,true);
  xmlhttp.send();
}

/*CountProds = 1;

function AddInput(){
  h=CountProds;
    
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      var x = document.getElementById("divForm");
      var y = document.createElement("INPUT");
      var z = document.createElement("div"); 
      
      y.setAttribute("type", "text");
      y.setAttribute("id", "ref" + h);
      y.setAttribute("onkeyup", "lsRef()");
      y.setAttribute("name", "ref" + h);
      
      z.setAttribute("id", "lsRef" + h);
      z.setAttribute("name", "lsRef" + h);
      
      x.appendChild(y);

      
      //document.getElementById("Produtos").innerHTML=this.responseText;
      CountProds++;
    }
  }
  xmlhttp.open("GET","lsRef.php?h="+h,true);
  xmlhttp.send();
  /*if(deleteProd()){
    if(!(w+1)){
      w--;
    }
  }

}*/


function DelIn(h){
  var node = document.getElementById("divForm"+h);
  if (node.parentNode) {
    node.parentNode.removeChild(node);
  }
}

var CountProds = 2;
// INPUT NÃO RECEBE REFERÊNCIA COM LETRA
function AddInput() {  
  h = CountProds;
  var lsRef = document.getElementById("lsRef"+h);
  var div = document.createElement("div");
  var focus = document.getElementById("ref"+(h-1));
  

  if(focus !== null){
    focus.autofocus=false;
  }

  if(focus.value !== ''){
    var center = document.createElement("center");
    var form = document.createElement("div");
    var input = document.createElement("INPUT");
    formProd = document.getElementById("formProd");
    var button = document.getElementById("submitCatalogo");
    var img = document.createElement("IMG");


    input.setAttribute("type", "text");
    input.setAttribute("id", "ref" + h);
    input.setAttribute("onkeyup", "lsRef(this.value,"+h+")");
    input.setAttribute("name", "ref" + h);
    input.setAttribute("autocomplete","off");
    input.style.margin="0 0 0 3.9%";
    input.focus();

    img.setAttribute("id","img"+h);
    img.setAttribute("name", "img"+h);
    img.setAttribute("onclick", "DelIn("+h+")");
    img.src="../_imagens/cancelar.png";
    img.width="13";
    img.height="13";
    img.style.margin="0 3px";
    img.style.cursor="pointer";

    form.setAttribute("id", "divForm"+h);
    form.setAttribute("name","divForm"+h);
    form.style.display="block";
    form.style.margin="5px";

    form.appendChild(input);
    form.appendChild(img);
    formProd.insertBefore(form, button);
    input.autofocus=true;
    CountProds++;

    div.setAttribute("id", "lsRef" + h);
    div.setAttribute("name", "lsRef" + h);

    form.appendChild(div);

  }
  console.log(focus);

}

function LsValue(h,ref){
  var lsRef = document.getElementById('lsRef'+h);
  lsRef.style.display="none";
  var input = document.getElementById('ref'+h);
  input.value= ref;

}

function Some(){
  document.getElementById('AddClick').style.display="none";
}
