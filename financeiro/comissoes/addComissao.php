<?php
echo '
    <div>
        <h2>Adicionar Comissão:</h2>
    </div>
';

if(isset($_POST['comissaoAdded'])){
    $num_doc = $_POST['num_doc'];
    $valor_comis = $_POST['valor_comis'];
    $data_prev = $_POST['data_prev'];
    $data_fat_comis = null;
    $id_apg = 296;
    $parcela = $_POST['parcela'];
    $obs = $_POST['obs'];
    $qr = 'insert into comissoes (num_doc, valor_comis, data_prev, data_fat_comis, id_apg, parcela, obs)
    values (?, ?, ?, ?, ?, ?, ?)';
    $values=[$num_doc, $valor_comis, $data_prev, $data_fat_comis, $id_apg, $parcela, $obs];
    $stmt=executeSQL($dbh,$qr,$values);
	if(is_string($stmt)){//deu erro no mysql..
		echo $stmt;
	}else{
		echo 'Executado com sucesso..';
	}
} else {
    echo '
        <div>
            <form method="post">
            Num Doc <input type="text" id="num_doc" name="num_doc" size="5" autofocus/>
            Valor <input type="text" id="valor_comis" name="valor_comis" size="5"/> </br>         
            Data Prev <input type="date" id="data_prev" name="data_prev" size="8"/>
            Parcela <input type="text" id="parcela" name="parcela" size="5"/></br>
            Obs <textarea id="obs" name="obs"></textarea></br>
            <input type="submit" value="Adicionar" name="comissaoAdded"/>
            </form>
        </div>
    ';

}
/*


Data Pag <input type="date" id="data_pag" name="data_pag" size="8"/>
Situacao <input type="text" id="situacao" name="situacao" size="8"/>
RecPag <input type="text" id="RecPg" name="RecPg"/></br>
*/