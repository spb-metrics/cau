<?
/*
Copyright 2011 da EMBRATUR
�Este arquivo � parte do programa CAU - Central de Atendimento ao Usu�rio
�O CAU � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela 
 Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
�Este programa � distribu�do na esperan�a que possa ser� �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer� 
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
�Observe no diret�rio gestaoti/install/ a c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licensa_uso.htm". 
 Se preferir acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA� 02110-1301, USA
*/
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.correcao_time_sheet.php';
$pagina = new Pagina();
$banco = new correcao_time_sheet();
// Configura��o da p�g�na

if($_SESSION["FLG_LIDER_EQUIPE"] != "S"){
	$pagina->ScriptAlert("Usu�rio n�o � l�der de equipe ou substituto. Acesso n�o permitido.");
	$pagina->redirectToJS("Time_sheetPesquisa.php");
}

$pagina->SettituloCabecalho("Solcita��es de Corre��o de Time Sheet Processadas"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("Correcao_time_sheetPesquisa.php", "", "Pendentes"),
				   array("Correcao_time_sheetPesquisaProcessados.php", "tabact", "Processados") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_TIME_SHEET", "");

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("Profissional", "20%");
$header[] = array("Chamado", "7%");
$header[] = array("In�cio registrado", "10%");
$header[] = array("T�rmino registrado", "10%");
$header[] = array("In�cio Solicitado", "10%");
$header[] = array("T�rmino Solicitado", "10%");
$header[] = array("Justificativa", "");
$header[] = array("An�lise", "10%");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
//$banco->setSEQ_TIME_SHEET($v_SEQ_TIME_SHEET);
//$banco->setDTH_INICIO_CORRECAO($v_DTH_INICIO_CORRECAO);
//$banco->setDTH_FIM_CORRECAO($v_DTH_FIM_CORRECAO);
//$banco->setTXT_JUSTIFICATIVA_CORRECAO($v_TXT_JUSTIFICATIVA_CORRECAO);
//$banco->setFLG_APROVADO($v_FLG_APROVADO);
$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
$banco->setNUM_MATRICULA_APROVADOR("NOT NULL");
$banco->selectParam("SEQ_TIME_SHEET", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhuma solicita��o processada.", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Solicita��es de corre��o processadas", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("left", "campo", $row["NOM_COLABORADOR"]);
		$corpo[] = array("right", "campo", "<a href=\"ChamadoDetalhe.php?v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."\">".$row["SEQ_CHAMADO"]."</a>");
		$corpo[] = array("center", "campo", $row["DTH_INICIO"]);
		$corpo[] = array("center", "campo", $row["DTH_FIM"]);
		$corpo[] = array("center", "campo", $row["DTH_INICIO_CORRECAO"]);
		$corpo[] = array("center", "campo", $row["DTH_FIM_CORRECAO"]);
		$corpo[] = array("left", "campo", $row["TXT_JUSTIFICATIVA_CORRECAO"]);
		$corpo[] = array("left", "campo", $pagina->iif($row["FLG_APROVADO"]=="S","Aprovado","Reprovado"));
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIME_SHEET=$v_SEQ_TIME_SHEET&v_DTH_INICIO_CORRECAO=$v_DTH_INICIO_CORRECAO&v_DTH_FIM_CORRECAO=$v_DTH_FIM_CORRECAO&v_TXT_JUSTIFICATIVA_CORRECAO=$v_TXT_JUSTIFICATIVA_CORRECAO&v_FLG_APROVADO=$v_FLG_APROVADO&v_NUM_MATRICULA_APROVADOR=$v_NUM_MATRICULA_APROVADOR");
$pagina->MontaRodape();
?>
