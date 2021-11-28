$(document).ready(() => {
  $('.butExcluirBanner').on('click', e => {
    const id = e.currentTarget.id.split('-');
    const pic = id[1];
    const action = 'removeBannerPic';
    if ( confirm('Tem certeza que deseja excluir essa foto?') ) {
      $.post('_AJAX/actions.php', { action, pic }, result => {
        alert(result);
        location.reload();
      });
    } else {
      return false;
    }
  });
  $('.butAlterBanner').on('click', e => {
    const id = e.currentTarget.id.split('-');
    const pic = id[1];
    const action = 'alterBannerPic';
    var file_data = $(`#file-${pic}`).prop('files')[0];
    var formData = new FormData();
    formData.append('file', file_data);
    formData.append('action', action);
    formData.append('pic', pic);
    if(!file_data){
      alert('Favor selecionar arquivo..');
    }else{
      if ( confirm('Tem certeza?') ) {
        $.ajax({
          url: '_AJAX/actions.php',
          dataType: 'text',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          type: 'post',
          success: result => {
            alert(result);
            location.reload();
          }
        });
      } else {
        return false;
      }
    }
  });
  $('.butAlterProdPic').on('click', e => {
    const action = 'alterProdPic';
    const id = e.currentTarget.id.split('-');
    const ref = id[1];
    const numPic = id[2];
    const data_pic = $(`#pic-${ref}-${numPic}`).prop('files')[0];
    var formData = new FormData();
    formData.append('ref', ref);
    formData.append('data_pic', data_pic);
    formData.append('numPic', numPic);
    formData.append('action', action);
    // console.log(data_pic);
    if(!data_pic){
      alert('Favor selecionar uma foto');
    }else{
      if( confirm('Tem certeza?') ){
        $.ajax({
          url: '_AJAX/actions.php',
          dataType: 'text',
          cache: false,
          contentType: false,
          processData: false,
          type: 'post',
          data: formData,
          success: result => {
            alert(result);
            location.reload();
          }
        });
      }
    }
  });
  $('.butExcluirProdPic').on('click', e => {
    const action = 'excluirProdPic';
    const id = e.currentTarget.id.split('-');
    const ref = id[1];
    const numPic = id[2];
    $.post('_AJAX/actions.php', {ref, numPic, action}, result => {
      alert(result);
      location.reload();
    });
  });
});
function lsbuscaprod(str) {
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
  xmlhttp.open("GET","_AJAX/buscaprod.php?q="+str,true);
  xmlhttp.send();
}
function lsbuscacor(str) {
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
  xmlhttp.open("GET","_AJAX/buscacor.php?q="+str,true);
  xmlhttp.send();
}
function uptipoacesso(acesso) {
  
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("divtipoacesso").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","_AJAX/ajaxcadastipoacesso.php?acesso="+acesso,true);
  xmlhttp.send();
}
function se_promo(){
	if(document.getElementById("promo").checked == true){
		document.getElementById("se_promocao").style="display:block"
	}else{
		document.getElementById("se_promocao").style="display:none"
	}
}
//confirm('Tem certeza que deseja excluir essa foto??')