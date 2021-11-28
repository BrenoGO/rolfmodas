<?php
echo '
    <div>
        <h2>Adicionar Conta:</h2>
    </div>
';

if(isset($_POST['contaAdded'])){
    $num_doc = $_POST['num_doc'];
    //$num_doc = '';
    $tipo_doc = $_POST['tipo_doc'];
    $id_usuario = $_POST['id_usuario'];
    //$id_usuario = null;
    $descricao = $_POST['descricao'];
    $data_doc = $_POST['data_doc'] === '' ? null : $_POST['data_doc'];
    $data_vencimento = $_POST['data_vencimento'] === '' ? null : $_POST['data_vencimento'];
    $valor = $_POST['valor'];
    //$data_pag = $_POST['data_pag'] === '' ? null : $_POST['data_pag'];
    $data_pag=null;
    $parcela = $_POST['parcela'];
    $obs = $_POST['obs'];
    //$situacao = $_POST['situacao'];
    $situacao = 'aberto';
    //$RecPg = $_POST['RecPg'];
    $RecPg = 'Rec';
    $qr = 'insert into contas (num_doc, tipo_doc, descricao, id_usuario, data_doc, data_vencimento, valor, data_pag, parcela, obs, situacao, RecPg, dataalter)
    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, default)';
    $values=[$num_doc, $tipo_doc, $descricao, $id_usuario, $data_doc, $data_vencimento, $valor, $data_pag, $parcela, $obs, $situacao, $RecPg];
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
            id_usuario <input type="text" id="id_usuario" name="id_usuario" size="5"/>           
            Tipo Doc <input type="text" id="tipo_doc" name="tipo_doc" size="10"/>
            Descrição <input type="text" id="descricao" name="descricao"/></br>
            Data Doc <input type="date" id="data_doc" name="data_doc" size="8"/>
            Data Venc <input type="date" id="data_vencimento" name="data_vencimento" size="8"/>
            Valor <input type="text" id="valor" name="valor" size="5"/>
            Parcela <input type="text" id="parcela" name="parcela" size="5"/></br>
            Obs <textarea id="obs" name="obs"></textarea></br>
            <input type="submit" value="Adicionar" name="contaAdded"/>
            </form>
        </div>
    ';

}
/*


Data Pag <input type="date" id="data_pag" name="data_pag" size="8"/>
Situacao <input type="text" id="situacao" name="situacao" size="8"/>
RecPag <input type="text" id="RecPg" name="RecPg"/></br>
*/