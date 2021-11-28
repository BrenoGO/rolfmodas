<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Comissões</title>
  <script src="_javascript/functionsarec.js"></script>
  <script src="_javascript/functionsgeral.js"></script>
  
</head>
<body>
	<?php
	
	require '../config.php';
	require '../header_geral.php';
    require 'menu.php';
    ?>
	
	<nav id="submenu">
	<a href="?acao=addComissao"><button>Adicionar Comissão</button></a>
	</nav>
	</header>
	
    <div class="corpo">
        <?php
            if(isset($_GET['acao'])){
                switch ($_GET['acao']) {
                    case 'addComissao':
                        require 'comissoes/addComissao.php';
                        break;
                    default:
                        echo '<div><h1>Check the Code!</h1></div>';
                        break;
                }
            } else {
                echo '<div><h1>Check the Code!</h1></div>';
            }
        ?>
    </div>
</body>
</html>
    