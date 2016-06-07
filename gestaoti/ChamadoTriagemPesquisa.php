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
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.destino_triagem.php';
$destino_triagem = new destino_triagem();
$tipo_chamado = new tipo_chamado();
$subtipo_chamado = new subtipo_chamado();
$situacao_chamado = new situacao_chamado();
$pagina = new Pagina();
$banco = new chamado();
$empregados = new empregados();
$pagina->ForcaAutenticacao();
// Configura��o da p�g�na
$pagina->SettituloCabecalho("Atendimento de 1� n�vel - Central de Atendimento: ".$_SESSION["NOM_CENTRAL_ATENDIMENTO"]); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();
$pagina->LinhaVazia(1);

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];
// =======================================================================================================================
// Em atraso
// =======================================================================================================================
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("Chamado", "5%");
$header[] = array("Solicitante", "20%");
$header[] = array("Atividade", "20%");
$header[] = array("Solicita��o", "30%");
$header[] = array("Abertura", "10%");
$header[] = array("Previs�o", "10%");
$header[] = array("SLA", "5%");

// Setar vari�veis
//$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
//$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
//$banco->setNUM_MATRICULA_SOLICITANTE($_SESSION["NUM_MATRICULA_RECURSO"]);
//$banco->setNUM_MATRICULA_CONTATO($_SESSION["NUM_MATRICULA_RECURSO"]);
//$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
//$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
//$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
//$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Triagem);
$banco->setTXT_CHAMADO($v_TXT_CHAMADO);
$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
//$banco->setPESQUISA_TRIAGEM("ATRASO");
//$banco->setCOD_DEPENDENCIA($vDestinoTriagem);
$banco->selectParam("DTH_ABERTURA");

$SQL_EXPORT = $banco->SQL_EXPORT;
$Rows = $banco->database->rows;
	
if($banco->database->rows > 0){
	$corpo = array();
	//$pagina->LinhaHeaderTabelaResultado("<font color=red>Chamados Atrasados</font>", $header);
	$pagina->LinhaHeaderTabelaResultado("Chamados aguardando atendimento", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("right", "campo", $row["seq_chamado"]);
		$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_solicitante"]));

		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->select($row["seq_subtipo_chamado"]);
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);

		$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);
		$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

		// Recuperar dados do SLA
		$v_DTH_TRIAGEM_PREVISAOO = $banco->fGetDTH_TRIAGEM_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_triagem"]==""?30:$row["qtd_min_sla_triagem"], $row["flg_forma_medicao_tempo"]==""?"U":$row["flg_forma_medicao_tempo"]);
		// $v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_triagem"]==""?60:$row["qtd_min_sla_triagem"], $row["flg_forma_medicao_tempo"]==""?"U":$row["flg_forma_medicao_tempo"]);
		$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_TRIAGEM_PREVISAOO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);


		$corpo[] = array("center", "campo", $row["dth_abertura"]);
		$corpo[] = array("center", "campo", $v_DTH_TRIAGEM_PREVISAOO);
		$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));
		$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoTriagem.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
		$corpo = "";
	}
}else{
	$pagina->LinhaHeaderTabelaResultado("Chamados Aguardando Atendimento", $header);
	$pagina->LinhaCampoFormularioColspan("left", "Nenhum chamado atrasado", "2");
}

if($Rows > 0){		
	$pagina->LinhaColspan("center", $pagina->fMontarExportacao("ChamadoTriagemPesquisaDecorator.php",$SQL_EXPORT,"CHAMADOS_PRIMERIO_NIVEL",true), count($header),"");
	
}
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>