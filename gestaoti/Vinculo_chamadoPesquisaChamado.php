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
require 'include/PHP/class/class.chamado.php';
require 'include/PHP/class/class.prioridade_chamado.php';
require 'include/PHP/class/class.subtipo_chamado.php';
require 'include/PHP/class/class.tipo_chamado.php';
$pagina = new Pagina();
$banco = new chamado();
if($v_SEQ_CHAMADO != ""){
	// Configura��o da p�g�na
	$pagina->flagScriptCalendario = 0;
	$pagina->flagMenu = 0;
	$pagina->flagTopo = 0;
	$pagina->MontaCabecalho();

	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Prioridade", "10%");
	$header[] = array("Chamado", "10%");
	$header[] = array("Atividade", "20%");
	$header[] = array("Solicita��o", "20%");
	$header[] = array("Situa��o", "15%");
	$header[] = array("Abertura", "10%");
	$header[] = array("Vencimento", "10%");
	$header[] = array("SLA", "5%");

	$pagina->LinhaCampoFormularioColspanDestaque("Chamados vinculados a esse", count($header));

	// Setar vari�veis
//	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$v_SEQ_ATIVIDADE_CHAMADO = $banco->SEQ_ATIVIDADE_CHAMADO;
	$chamado = new chamado();
	$chamado->setSEQ_CHAMADO_MASTER($v_SEQ_CHAMADO);
	$chamado->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$chamado->selectParam("DTH_ABERTURA DESC");
	if($chamado->database->rows > 0){
		$corpo = array();
		$pagina->LinhaHeaderTabelaResultado("", $header);
		while ($row = pg_fetch_array($chamado->database->result)){
			// Prioridade
			$prioridade_chamado = new prioridade_chamado();
			$prioridade_chamado->select($row["seq_prioridade_chamado"]);
			$corpo[] = array("left", "campo", $prioridade_chamado->DSC_PRIORIDADE_CHAMADO);

			// Chamado
			$corpo[] = array("right", "campo", $row["seq_chamado"]);

			// Atividade
			$subtipo_chamado = new subtipo_chamado();
			$subtipo_chamado->select($row["seq_subtipo_chamado"]);
			$tipo_chamado = new tipo_chamado();
			$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
			$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);

			// Solicita��o
			$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

			// Situa��o
			$situacao_chamado = new situacao_chamado();
			$situacao_chamado->select($row["seq_situacao_chamado"]);
			$corpo[] = array("left", "campo", $situacao_chamado->DSC_SITUACAO_CHAMADO);

			// Abertura
			$corpo[] = array("center", "campo", $row["dth_abertura"]);

			// Recuperar dados do SLA
			$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

			// Vencimento
			$corpo[] = array("center", "campo", "<font color=".$pagina->CorSLA($v_COD_SLA_ATENDIMENTO).">".$v_DTH_ENCERRAMENTO_PREVISAO."</font>");

			// SLA
			$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));

			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"top.location.href='ChamadoAtendimento.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
			$corpo = "";
		}
	}else{
		$pagina->LinhaColspan("left", "Nenhum chamado vinculado", "2", "campo");
	}
	$pagina->FechaTabelaPadrao();
	print "</body></html>";
	//$pagina->MontaRodape();
}
?>
