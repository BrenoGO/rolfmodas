<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
  <link rel="stylesheet" href="_css/style.css"/>
  <script type="text/javascript" src="_javascript/functionsCatalogo.js"></script>
  <title>Criar Catalogo</title>
</head>

<body>
    <div>
      <img id="modelo" src="_imagens/modelo.png"/> 
      <img id="modelo2" src="_imagens/modelo2.png"/> 
      <center><img id="logo" src="_imagens/logo.png"/> 
      <h2 id="title">Crie seu catálogo</h2>
      <div id="center">
        <div id="div1">
          <p id="descricao">Escolha as referências que deseja:</p>
          <br>

          <!--<input style="margin: 5px;" id="AddClick" type="button" onclick="AddInput();Some()" value="Clique Aqui" />-->
          <form id="formProd" method="post" action="CatalogoPDF.php">
            
            <div id="divForm1" name="divForm1" style="display: block; margin: 5px;">
              <input type="text" id="ref1" onkeyup="lsRef(this.value,1)" autocomplete="off" name="ref1" autofocus/> 
              
              <div id="lsRef1" name="lsRef1"></div>
            </div>
            

            <input id="submitCatalogo" style="margin: 5px; border-radius: 10px;" type="submit" name="submitCatalogo" value="Gerar PDF">
          </form>
        </div></center>
      </div>
    </div>
 
	</body>
</html>