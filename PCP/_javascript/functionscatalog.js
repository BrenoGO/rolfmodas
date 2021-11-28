function showlistprod(){
	document.getElementById("desapbutton").style.display="none";
	document.getElementById("listprod").style.display="inline";
}

function calc_desc(desc,ref,preco){
		var desco = desc.replace(",",".");
		var idpreconovo = 'preconovo-'+ref;	
		var preconovo = (1-desco/100) * preco;
		precon = preconovo.toFixed(2);
		document.getElementById(idpreconovo).value= precon;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function addEmail(emailnovo){
	if(document.getElementById("emailnovo1")==null){
		var i ="1";
	}else{
		if(document.getElementById("emailnovo2")==null){
			var i ="2";
		}else{
			var i ="3";
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
		  
		  var x = document.getElementById("outrosemails").innerHTML;
		  document.getElementById("outrosemails").innerHTML=x+this.responseText;
		  document.getElementById("emailadd").value = "";
		}
    }
	xmlhttp.open("GET","_AJAX/addemailcatalog.php?q="+emailnovo+"&i="+i,true);
	xmlhttp.send();
}

	z = [];
function prox(max,ref){

	if(z[ref] == undefined){
		z[ref] = 1;
	}
	if(z[ref] >= max){
		z[ref] = 1;
	}else{
	z[ref]++;}
	
	document.getElementById("image").src= "_fotos/"+ref+"-"+z[ref]+".jpg";
	
	Image2="_fotos/"+ref+"-"+z[ref]+".jpg";
	y=z[ref]-1;
	Image1="_fotos/"+ref+"-"+y+".jpg"
	ez = $('.zoom').data('elevateZoom');
	ez.swaptheimage(Image2, Image2);
	
	
}
function ant(max,ref){

	if(z[ref] == undefined){
		z[ref] = 1;
	}
	if(z[ref] == 1){
		z[ref] = max;
	}else{
	z[ref]--;}
	
	
	document.getElementById("image").src= "_fotos/"+ref+"-"+z[ref]+".jpg";
	
	Image2="_fotos/"+ref+"-"+z[ref]+".jpg";
	y=z[ref]+1;
	Image1="_fotos/"+ref+"-"+y+".jpg"
	ez = $('.zoom').data('elevateZoom');
	ez.swaptheimage(Image2, Image2);
	
	
}

function proc_produto_catalog(str) {
  if (str.length==0) { 
    document.getElementById("ls_produto_catalogo").innerHTML="";
    document.getElementById("ls_produto_catalogo").style.border="0px";
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
      document.getElementById("ls_produto_catalogo").innerHTML=this.responseText;
      document.getElementById("ls_produto_catalogo").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","_AJAX/buscaprodcatalogo.php?q="+str,true);
  xmlhttp.send();
}
function clear_slideshow(){
	clearTimeout(var_time);
}

function slideshow_banner(i,max){

	t1="_banner/"+i+"-banner.png";
	document.getElementById("img_banner").src=t1;
	t2="../_imagens/bola_cinza_claro.png";
	t3="../_imagens/bola_cinza_escuro.png";
	c=1;
	time=3000;
	while(c <= max){
		if(c == i){
			document.getElementById("bola-"+c).src=t3;
		}else{
			document.getElementById("bola-"+c).src=t2;
		}
		c++;
	}
	
	if(i == 1){
		document.getElementById("link_img_banner").href="?secao=Feminino#anchor";
	}
	if(i == 2){
		document.getElementById("link_img_banner").href="?secao=Masculino#anchor";
	}
	if(i == 3){
		document.getElementById("link_img_banner").href="?secao=Feminino&grupo=Plus#anchor";
	}
	if(i == 4){
		document.getElementById("link_img_banner").href="?secao=Infantil#anchor";
	}
	
	if(i < max){
		i++;
	}else{
		i=1;
	}
	
	var_time=setTimeout("slideshow_banner(" + i + "," + max + ")",time);
		
}

function escolherPEemref(){
	/*document.getElementById("se_escolher_PE").style.display="none";*/
	document.getElementById("se_escolher_PE").style.cursor="default";
	document.getElementById("cores_no_add_pedido").style.display="block";
	document.getElementById("submit_no_add_pedido").style.display="block";
	document.getElementById("form_add_pedido_ped").style.display="none";
	document.getElementById("voltar").style.display="none";
}

function escolherPedemref(){
	/*document.getElementById("se_escolher_ped").style.display="none";*/
	document.getElementById("se_escolher_ped").style.cursor="default";
	document.getElementById("cores_no_add_pedido_ped").style.display="block";
	document.getElementById("submit_no_add_pedido_ped").style.display="block";
	document.getElementById("form_add_pedido").style.display="none"
}

function BotVoltarPE(){
	document.getElementById("se_escolher_PE").style.cursor="default";
	document.getElementById("cores_no_add_pedido").style.display="none";
	document.getElementById("submit_no_add_pedido").style.display="none";
	document.getElementById("form_add_pedido_ped").style.display="block";
	document.getElementById("voltar").style.display="block";
}
function BotVoltarPed(){
	document.getElementById("se_escolher_ped").style.cursor="default";
	document.getElementById("cores_no_add_pedido_ped").style.display="none";
	document.getElementById("submit_no_add_pedido_ped").style.display="none";
	document.getElementById("form_add_pedido").style.display="block"
}

function hoverDrop(w,inim){
	document.getElementById(inim+"GH"+w).style.display="block";
}
function hDout(w,inim){
	document.getElementById(inim+"GH"+w).style.display="none";
}
GHs = "Hidden";
function lat_hoverDrop(w,inim){
	if(GHs == "Hidden"){
		GHs = "shown";
		document.getElementById(inim+"GH"+w).style.display="flex";
		document.getElementById(inim+"GH"+w).style.flexDirection="column";
		document.getElementById("lat_down").src="../_imagens/up.png";
	}else{
		GHs = "Hidden";
		document.getElementById(inim+"GH"+w).style.display="none";
		document.getElementById("lat_down").src="../_imagens/down.png";
	}
}
function ChangeCatSize(){
	var w = window.innerWidth
	|| document.documentElement.clientWidth
	|| document.body.clientWidth;

	var h = window.innerHeight
	|| document.documentElement.clientHeight
	|| document.body.clientHeight;
	
	/*if(w > 760){
		document.getElementById("menu_lateral").style.display="none";
		//document.getElementById("icone").style.display="none";
		document.getElementById("fundo_opaco").style.display="none";
	}else{
		document.getElementById("icone").style.display="block";
	}*/
}
function MostrarLogin(){
	var header = document.getElementById("login_mobile");
	var icone = document.getElementById("login");
	var body = document.getElementById("catalogo");
	body.style.overflow="hidden";
	header.style.display="block";
	icone.style.display="none";
}

var w = window.innerWidth
|| document.documentElement.clientWidth
|| document.body.clientWidth;

var h = window.innerHeight
|| document.documentElement.clientHeight
|| document.body.clientHeight;

function OcultarLogin(){

	var divlog = document.getElementById("login_mobile");
	var icone = document.getElementById("login");
	var body = document.getElementById("catalogo");
	body.style.overflow="visible";
	divlog.style.display="none";

	if(w > 720){
		icone.style.display="none";
	}else{
		icone.style.display="block";
	}
} 

function imgCor(cont){
	document.getElementById("cor"+cont).style.display="block";
}
function imgCor2(cont){
	document.getElementById("cor"+cont).style.display="none";
}


/*function busca(){
	var input = document.getElementById("buscaprod");
	var lupa = document.getElementById("lupa");
	var busca = document.getElementById("espacobuscar");
	var tipo = document.getElementById("tipo_ped");
	var label = document.getElementById("label_mobile");
	input.focus();
	busca.style.cssFloat="left";
	lupa.style.display="none";
	busca.style.display="block";
}*/

$(document).ready(function(){

	var buttonPed = "#tipo_ped";
	var select = "#tipo_ped_mobile";
	
	$(buttonPed).click(function(){
			$(select).addClass("active");
			$(select).css({"display":"flex"});
	});

	var slide_wrp 		= "#menu_lateral"; //Menu Wrapper
	var open_button 	= "#icone"; //Menu Open Button
	var close_button 	= "#x"; //Menu Close Button
	var overlay 		= ".menu-overlay"; //Overlay

	$(slide_wrp).hide().css( {"left": '-250%'}).delay(50).queue(function(){$(slide_wrp).show()}); 

	$(open_button).click(function(e){
		e.preventDefault();
		$(slide_wrp).css( {"left": "0px"});
		setTimeout(function(){
			$(slide_wrp).addClass('active');
		},50);
		$(overlay).css({"opacity":"1", "width":"100%"});
	});

	$(close_button).click(function(e){
		e.preventDefault();
		$(slide_wrp).css( {"left": '-250%'});
		setTimeout(function(){
			$(slide_wrp).removeClass('active');
		},50);
		$(overlay).css({"opacity":"0", "width":"0"});
	});

	$(document).on('click', function(e) {
		if (!e.target.closest(slide_wrp) && $(slide_wrp).hasClass("active")){
			$(slide_wrp).css( {"left": '-250%'}).removeClass('active');
			$(overlay).css({"opacity":"0", "width":"0"});
		}
	});
	
	var headerD 		= "#headerdireito"; //Menu Wrapper
	var entrarButton 	= "#entrar"; //Menu Open Button
	var fecharButton 	= "#fecharlog"; //Menu Close Button
	var fundoOpaco 		= "#opaco_fundo"; //Overla
	var catalogo 		= "#catalogo"; // Catalogo
	var login 			= "#login_mobile"; // Mobile Login
	var inputLogin 		= "#id";

	$(headerD).hide().css( {"top": '-150%'}).delay(50).queue(function(){$(headerD).show()}); 

	$(entrarButton).click(function(e){
		e.preventDefault();
		$(headerD).css( {"top": "45%"});
		setTimeout(function(){
			$(headerD).addClass('active');
		},50);
		$(fundoOpaco).css({"opacity":"0.2", "display":"block"});
		$(catalogo).css({"overflow":"hidden"});
		$(entrarButton).toggleClass("loginButton none");
		$(inputLogin).focus();
	});

	$(fecharButton).click(function(e){
		e.preventDefault();
		$(headerD).css( {"top": '-150%'});
		setTimeout(function(){
			$(headerD).removeClass('active');
		},50);
		$(fundoOpaco).css({"display": "none"});
		$(catalogo).css({"overflow":"visible"});
		$(entrarButton).toggleClass("loginButton none");
	});

	var tipoPed = "#tipo_ped_mobile";

	$(document).on('click', function(e) {
		if(e.target.closest(tipoPed)){
			$(tipoPed).css( {"display": 'none'}).removeClass('active');
		}
		if (!e.target.closest(headerD) && $(headerD).hasClass("active")){
			$(headerD).css( {"top": '-150%'}).removeClass('active');
			setTimeout(function(){
				$(headerD).removeClass('active');
			},50);
			$(fundoOpaco).css({"display": "none"});
			$(catalogo).css({"overflow":"visible"});
			$(entrarButton).removeClass("none");
		}
	});


	var lupa = "#lupa";
	$(lupa).click(function(e){
		var input = document.getElementById("buscaprod");
		var lupa = document.getElementById("lupa");
		var busca = document.getElementById("espacobuscar");
		var tipo = document.getElementById("tipo_ped");
		var label = document.getElementById("label_mobile");
		busca.style.cssFloat="left";
		$("#lupa").addClass("lupa2");
		$("#espacobuscar").removeClass("input2 espacobuscar");
		$("#espacobuscar").addClass("inputbusca");
		input.focus();
		$("#buscaprod").prop("size","15");
	});

	/*var label = document.getElementById("label_mobile");
	var input = document.getElementById("buscaprod");
	input.addEventListener('focus', function() { 
		$("#label_mobile").addClass("none");
		$("#label_mobile").removeClass("label_mobile");
	});
	input.addEventListener('blur', function() { 
				
		setTimeout(function (){
			var lupa = document.getElementById("lupa");
			$("#label_mobile").removeClass("none");
			$("#label_mobile").addClass("label_mobile");
			$("#lupa").removeClass("lupa2");	
			$("#espacobuscar").addClass("input2 espacobuscar");
		}, 200);	
	});*/

	var buscaInput = document.getElementById("busca");
	buscaInput.addEventListener('blur', function() { 
		setTimeout(function (){
	        document.getElementById("ls_produto_catalogo").innerHTML="";
		    document.getElementById("ls_produto_catalogo").style.border="0px";			
		}, 200);	
	});


	var logo = "#div_logo";
	var loginH = "#div_dados"; 
	


	var inputB = document.createElement('input');
	inputB.setAttribute('type','text');
	inputB.setAttribute('class','busca');
	inputB.setAttribute('onkeyup','proc_produto_catalog(this.value)');
	inputB.setAttribute('placeholder','Busque aqui !');
	inputB.setAttribute('autocomplete','off');
	
	var imgB = document.createElement('img');
	imgB.setAttribute('src','../_imagens/lupa.png');
	imgB.setAttribute('id','lupaBusca');

	var divB = document.createElement('div');
	divB.setAttribute('id','ls_produto_catalogo');

	var w = window.innerWidth
		|| document.documentElement.clientWidth
		|| document.body.clientWidth;

	if(w > 540){
		inputB.setAttribute('size','40');
		var busca = document.createElement("div");
		busca.setAttribute('id','buscar');

		var cookie = getCookie("tipo_ped");

		var select 	= document.createElement("select");
		select.setAttribute('id','SelPed');

		var option1 = document.createElement('option');
		option1.setAttribute('value','PE');
		option1.setAttribute('id','PE');
		if(cookie == 'PE'){
			option1.selected = true;
		}
		option1.innerHTML = 'Pronta Entrega';

		var option2 = document.createElement('option');
		option2.setAttribute('value','Ped');
		option2.setAttribute('id','Ped');
		if(cookie == 'Pedido'){
			option2.selected = true;
		}
		option2.innerHTML = 'Pedido a Prazo';

		var option3 = document.createElement('option');
		option3.setAttribute('value','Ambos');
		option3.setAttribute('id','Ambos');
		if(cookie == 'Ambos'){
			option3.selected = true;
		}
		option3.innerHTML = 'Ambos';

		$(select).append(option1).append(option2).append(option3);

		inputB.setAttribute('id','busca');
		$('#buscar').css({'float':'left'});
		$(busca).append(inputB);
		$(busca).append(divB);
		$(busca).append(select);
		$(busca).insertAfter(logo);

		var SelPed = document.getElementById('SelPed');

		$('#SelPed').change(function(){
			if(SelPed.value == 'PE'){
				document.cookie="tipo_ped=PE";
			}
			if(SelPed.value == 'Ped'){
				document.cookie="tipo_ped=Pedido";
			}
			if(SelPed.value == 'Ambos'){
				document.cookie="tipo_ped=Ambos";
			}
			location.reload();
		});

	}else{
		$(logo).left = w/3 - $(logo).width;

		var divBuscageral = document.createElement("div");
		divBuscageral.setAttribute('id','divGeral');

		var busca = document.createElement("div");
		busca.setAttribute('id','buscar2');
		inputB.setAttribute('id','busca2');
		inputB.setAttribute('class','busca buscaInative');
		
		$(busca).append(inputB);
		$(busca).append(divB);
		$(divBuscageral).append(busca).append(imgB).append(document.getElementById('div_logo'));
		$(loginH).append(divBuscageral);
		var lupaBusca = "#lupaBusca";
		var busca = "#buscar2";
		
		$(lupaBusca).click(function(e){
			$(busca).css({'display':'flex', 'margin': '5px 10px'})
			$(lupaBusca).css({'display':'none'});
			setTimeout(function(){
				inputB.focus();
				inputB.setAttribute('class','busca buscaActive');
			}, 100);
		});
		inputB.addEventListener('blur', function() { 
			setTimeout(function (){
		        inputB.setAttribute('class','busca buscaInative');			
			}, 200);
			setTimeout(function(){
		        $(lupaBusca).css({'display':'block'});
				$(busca).css({'display':'none'});
			}, 700);	
		});

		var cookie = getCookie("tipo_ped");
		var select_mobile = document.createElement("select");
		select_mobile.setAttribute('id','SelPed_mobile');
		$(select_mobile).css({'clear':'both','float':'right','margin':'10px 0'});

		var option1 = document.createElement('option');
		option1.setAttribute('value','PE');
		option1.setAttribute('id','PE_mobile');
		if(cookie == 'PE'){
			option1.selected = true;
		}
		option1.innerHTML = 'Pronta Entrega';

		var option2 = document.createElement('option');
		option2.setAttribute('value','Ped');
		option2.setAttribute('id','Ped_mobile');
		if(cookie == 'Pedido'){
			option2.selected = true;
		}
		option2.innerHTML = 'Pedido a Prazo';

		var option3 = document.createElement('option');
		option3.setAttribute('value','Ambos');
		option3.setAttribute('id','Ambos_mobile');
		if(cookie == 'Ambos'){
			option3.selected = true;
		}
		option3.innerHTML = 'Ambos';

		$(select_mobile).append(option1).append(option2).append(option3);
		$(select_mobile).change(function(){
			if(select_mobile.value == 'PE'){
				document.cookie="tipo_ped=PE";
			}
			if(select_mobile.value == 'Ped'){
				document.cookie="tipo_ped=Pedido";
			}
			if(select_mobile.value == 'Ambos'){
				document.cookie="tipo_ped=Ambos";
			}
			location.reload();
		});

		$('#divGeral').append(select_mobile);
		
	}


});

