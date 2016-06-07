<?
/*
Copyright 2011 da EMBRATUR
 Este arquivo é parte do programa CAU - Central de Atendimento ao Usuário
 O CAU é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela 
 Fundação do Software Livre (FSF); na versão 2 da Licença.
 Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
 Observe no diretório gestaoti/install/ a cópia da Licença Pública Geral GNU, sob o título "licensa_uso.htm". 
 Se preferir acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.correcao_time_sheet.php';
$pagina = new Pagina();
$banco = new correcao_time_sheet();
// Configuração da págína

if($_SESSION["FLG_LIDER_EQUIPE"] != "S"){
	$pagina->ScriptAlert("Usuário não é líder de equipe ou substituto. Acesso não permitido.");
	$pagina->redirectToJS("Time_sheetPesquisa.php");
}

if($flag == "1" && $v_FLG_APROVADO != "" && $v_SEQ_TIME_SHEET != ""){
	$banco->setFLG_APROVADO($v_FLG_APROVADO);
	$banco->setNUM_MATRICULA_APROVADOR($_SESSION["NUM_MATRICULA_RECURSO"]);
	$banco->avaliar($v_SEQ_TIME_SHEET);

	// Dados do registro
	require_once 'include/PHP/class/class.time_sheet.php';
	$time_sheet = new time_sheet();
	$time_sheet->select($v_SEQ_TIME_SHEET);

	// Dados do e-mail
	require_once 'include/PHP/class/class.phpmailer.php';
	$mail = new PHPMailer();
	$mail->From     = $pagina->EmailRemetente;
	$mail->FromName = $pagina->remetenteEmailCEA;
	$mail->Sender   = $pagina->EmailRemetente;
	$mail->AddCustomHeader("X-HTTP-Proxy-Server:".$_SERVER['REMOTE_ADDR']);

	if($v_FLG_APROVADO == "S"){ // Atualizar Time Sheet
		$banco->select($v_SEQ_TIME_SHEET);
		require_once 'include/PHP/class/class.time_sheet.php';
		$time_sheet1 = new time_sheet();
		$time_sheet1->setDTH_INICIO($banco->DTH_INICIO_CORRECAO);
		$time_sheet1->setDTH_FIM($banco->DTH_FIM_CORRECAO);
		$time_sheet1->CorrigirDatas($v_SEQ_TIME_SHEET);

		// Corpo do e-mail
		$mail->Subject  = "CEA - Aprovação de solicitação de alteração de time sheet";
		$v_DS_CORPO ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
				    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
				<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
				<head>
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
					<link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
				</head>
				<body>
				<div align=\"left\">
						<NOM_DETINATARIO>,<br>
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser aprovada a sua solicitação de alteração de lançamento de horas de trabalho para o chamado ".$time_sheet->SEQ_CHAMADO." no dia ".substr($time_sheet->DTH_INICIO,0,10).". <br>
			   <br>Para verificar a alteração, acesse o Gestão TI no menu Profissionais - Time Sheet - Horas Trabalhadas.
			   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
			   <br>---
			   <br>Gestão TI
			   <br>".$pagina->enderecoGestaoTI."
			   </div>
			   </body>
			   </html>";

	}else{
		// Corpo do e-mail
		$mail->Subject  = "CEA - Reprovação de solicitação de alteração de time sheet";
		$v_DS_CORPO ="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
				    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
				<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pt\" lang=\"pt\">
				<head>
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
					<link href=\"".$pagina->enderecoGestaoTI."include/CSS/CascadeStyleSheet.css\" rel=\"stylesheet\" type=\"text/css\" />
				</head>
				<body>
				<div align=\"left\">
						<NOM_DETINATARIO>,<br>
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;acaba de ser reprovada a sua solicitação de alteração de lançamento de horas de trabalho para o chamado ".$time_sheet->SEQ_CHAMADO." no dia ".substr($time_sheet->DTH_INICIO,0,10).". <br>
			   <br>Por favor, entre em contato com o seu líder para maiores informações.
			   <br>Esta e uma mensagem autom&aacute;tica, favor n&atilde;o responder.
			   <br>---
			   <br>Gestão TI
			   <br>".$pagina->enderecoGestaoTI."
			   </div>
			   </body>
			   </html>";

	}
	// Enviar e-mail
	if (isset ($_SERVER ['HTTP_USER_AGENT' ])) {
        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:".$_SERVER[ 'HTTP_USER_AGENT' ]);
    } else {
        $mail->AddCustomHeader("X-HTTP-Posting-UserAgent:Unknown");
    }

	// Buscar contatos do solicitante
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$empregados->GetNomeEmail($time_sheet->NUM_MATRICULA);
	// Adicionar
	$mail->AddAddress($empregados->DES_EMAIL, $empregados->NOME);
	$mail->Body    = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
	$mail->AltBody = str_replace("<NOM_DETINATARIO>", $empregados->NOME, $v_DS_CORPO);
	$mail->Send();
	$mail->ClearAddresses();

	// Exibir Mensagem
	$pagina->ScriptAlert("Avaliação processada com sucesso.");
	$pagina->redirectToJS("Correcao_time_sheetPesquisa.php");
}

$pagina->SettituloCabecalho("Avaliar Correção de Time Sheet"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Correcao_time_sheetPesquisa.php", "tabact", "Pendentes"),
				   array("Correcao_time_sheetPesquisaProcessados.php", "", "Processados") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_TIME_SHEET", "");

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Profissional", "20%");
$header[] = array("Chamado", "7%");
$header[] = array("Início registrado", "10%");
$header[] = array("Término registrado", "10%");
$header[] = array("Início Solicitado", "10%");
$header[] = array("Término Solicitado", "10%");
$header[] = array("Justificativa", "");

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
//$banco->setSEQ_TIME_SHEET($v_SEQ_TIME_SHEET);
//$banco->setDTH_INICIO_CORRECAO($v_DTH_INICIO_CORRECAO);
//$banco->setDTH_FIM_CORRECAO($v_DTH_FIM_CORRECAO);
//$banco->setTXT_JUSTIFICATIVA_CORRECAO($v_TXT_JUSTIFICATIVA_CORRECAO);
//$banco->setFLG_APROVADO($v_FLG_APROVADO);
$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
$banco->setNUM_MATRICULA_APROVADOR("NULL");
$banco->selectParam("SEQ_TIME_SHEET", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhuma solicitação pendente de análise.", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Solicitações de correção pendentes de análise", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoTimeSheetAprovar("Correcao_time_sheetPesquisa.php?flag=1&v_FLG_APROVADO=S&v_SEQ_TIME_SHEET=".$row["SEQ_TIME_SHEET"]."");
		$valor .="&nbsp;". $pagina->BotaoTimeSheetReprovar("Correcao_time_sheetPesquisa.php?flag=1&v_FLG_APROVADO=N&v_SEQ_TIME_SHEET=".$row["SEQ_TIME_SHEET"]."");
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["NOM_COLABORADOR"]);
		$corpo[] = array("right", "campo", "<a href=\"ChamadoDetalhe.php?v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."\">".$row["SEQ_CHAMADO"]."</a>");
		$corpo[] = array("center", "campo", $row["DTH_INICIO"]);
		$corpo[] = array("center", "campo", $row["DTH_FIM"]);
		$corpo[] = array("center", "campo", $row["DTH_INICIO_CORRECAO"]);
		$corpo[] = array("center", "campo", $row["DTH_FIM_CORRECAO"]);
		$corpo[] = array("left", "campo", $row["TXT_JUSTIFICATIVA_CORRECAO"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIME_SHEET=$v_SEQ_TIME_SHEET&v_DTH_INICIO_CORRECAO=$v_DTH_INICIO_CORRECAO&v_DTH_FIM_CORRECAO=$v_DTH_FIM_CORRECAO&v_TXT_JUSTIFICATIVA_CORRECAO=$v_TXT_JUSTIFICATIVA_CORRECAO&v_FLG_APROVADO=$v_FLG_APROVADO&v_NUM_MATRICULA_APROVADOR=$v_NUM_MATRICULA_APROVADOR");
$pagina->MontaRodape();
?>
