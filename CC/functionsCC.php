
<?php
function StatusStmt($status){
	switch($status){
		case 0:
			return('Aguardando atualização de status');
			break;
		case 1:
			return('Pagamento apto a ser capturado ou definido como pago');
			break;
		case 2:
			return('Pagamento confirmado e finalizado');
			break;
		case 3:
			return('Pagamento negado por Autorizador');
			break;
		case 10:
			return('Pagamento cancelado');
			break;	
		case 11:
			return('Pagamento cancelado após 23:59 do dia de autorização');
			break;
		case 12:
			return('Aguardando Status de instituição financeira');
			break;
		case 13:
			return('Pagamento cancelado por falha no processamento');
			break;	
		case 20:
			return('Recorrência agendada');
			break;	
	}
}
function RetCodeStmt($RetCode){
	switch($RetCode){
		case '4':
			return('Operação realizada com sucesso');
			break;
		case '6':
			return('Operação realizada com sucesso');
			break;
		case '05':
			return('Não Autorizada');
			break;
		case '57':
			return('Cartão Expirado');
			break;
		case '78':
			return('Cartão Bloqueado');
			break;	
		case '99':
			return('Time Out');
			break;
		case '77':
			return('Cartão Cancelado');
			break;
		case '70':
			return('Problemas com o Cartão de Crédito');
			break;	
		case '99':
			return('Operation Successful / Time Out');
			break;	
	}
}
