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
require_once 'include/PHP/class/class.epm.atividades.php';
$pagina = new Pagina();
$banco = new atividades();

$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
$pagina->tituloCabecalho = "";
$pagina->MontaCabecalho();

print $pagina->CampoHidden("v_SEQ_ATIVIDADE", "");

$pagina->AbreTabelaResultado("center", "150%");
$header = array();
$header[] = array("Nome da Tarefa", "25%");
$header[] = array("% Concl.", "5%");
$header[] = array("Dura��o", "7%");
$header[] = array("In�cio", "10%");
$header[] = array("T�rmino", "10%");
$header[] = array("Predecessoras", "10%");
$header[] = array("�rea", "7%");
$header[] = array("Nomes dos Recursos", "15%");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setCOD_PROJETO($v_COD_PROJETO);
$banco->selectParam("CAST([TaskOutlineNumber] AS varchar(10))");
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("left", "Nenhuma tarefa encontrada", count($header));
	$pagina->FechaTabelaPadrao();
}else{
	$corpo = array();
	//$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Atividades do Projeto", $header);
	while ($row = odbc_fetch_array($banco->database->result)){
		$corpo[] = array("left", "campo", $pagina->fIncluiEspacos($row["NUM_NIVEL"]).$row["COD_ATIVIDADE"]." - ".$row["NOM_ATIVIDADE"]);
		$corpo[] = array("right", "campo", $row["PER_COMPLETA"]);
		$corpo[] = array("right", "campo", $row["QTD_DURACAO"]."h");
		$corpo[] = array("left", "campo", $row["DAT_INICIO_PREVISTA"]);
		$corpo[] = array("left", "campo", $row["DAT_FINAL_PREVISTA"]);
		$corpo[] = array("left", "campo", $row["Predecessoras"]);
		$corpo[] = array("left", "campo", $row["SIG_AREA_RECURSO"]);
		$corpo[] = array("left", "campo", $row["NOM_RECURSOS"]);
		$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"parent.location.href='EpmAtividadeDetalhes.php?v_SEQ_ATIVIDADE=".$row["SEQ_ATIVIDADE"]."&v_COD_PROJETO=$v_COD_PROJETO';\"");
		$corpo = "";
	}
	$pagina->FechaTabelaPadrao();
	//$pagina->LinhaCampoFormularioColspan("left", $pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_SERVIDOR=$v_SEQ_SERVIDOR&v_SEQ_SISTEMA_OPERACIONAL=$v_SEQ_SISTEMA_OPERACIONAL&v_SEQ_MARCA_HARDWARE=$v_SEQ_MARCA_HARDWARE&v_NUM_PATRIMONIO=$v_NUM_PATRIMONIO&v_NUM_IP=$v_NUM_IP&v_NOM_SERVIDOR=$v_NOM_SERVIDOR&v_NOM_MODELO=$v_NOM_MODELO&v_DSC_SERVIDOR=$v_DSC_SERVIDOR&v_DSC_LOCALIZACAO=$v_DSC_LOCALIZACAO&v_DSC_PROCESSADOR=$v_DSC_PROCESSADOR&v_TXT_OBSERVACAO=$v_TXT_OBSERVACAO&v_DAT_CRIACAO=$v_DAT_CRIACAO&v_DAT_ALTERACAO=$v_DAT_ALTERACAO"), 20);
}
?>
