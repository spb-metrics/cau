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
require 'include/PHP/class/class.historico_chamado.php';
$pagina = new Pagina();
$banco = new historico_chamado();
if($v_SEQ_CHAMADO != ""){
	// Configura��o da p�g�na
	$pagina->flagScriptCalendario = 0;
	$pagina->flagMenu = 0;
	$pagina->flagTopo = 0;
	$pagina->MontaCabecalho();

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Data", "17%");
	$header[] = array("Situa��o", "20%");
	$header[] = array("Respons�vel", "25%");
	$header[] = array("Observa��o", "");

	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();

	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_HISTORICO_CHAMADO($v_SEQ_HISTORICO_CHAMADO);
	$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$banco->setNUM_MATRICULA($v_NUM_MATRICULA);
	$banco->setDTH_HISTORICO($v_DTH_HISTORICO);
	$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
	$banco->setSEQ_MOTIVO_SUSPENCAO($v_SEQ_MOTIVO_SUSPENCAO);
	$banco->setTXT_HISTORICO($v_TXT_HISTORICO);
	$banco->selectParam("SEQ_HISTORICO_CHAMADO DESC", $vNumPagina, 10);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$corpo[] = array("center", "campo", $row["dth_historico"]);
			$corpo[] = array("left", "campo", $row["dsc_situacao_chamado"]);
			$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula"]));
			$corpo[] = array("left", "campo", $row["txt_historico"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_HISTORICO_CHAMADO=$v_SEQ_HISTORICO_CHAMADO&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_NUM_MATRICULA=$v_NUM_MATRICULA&v_DTH_HISTORICO=$v_DTH_HISTORICO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_SEQ_MOTIVO_SUSPENCAO=$v_SEQ_MOTIVO_SUSPENCAO&v_TXT_HISTORICO=$v_TXT_HISTORICO");
	print "</body></html>";
	//$pagina->MontaRodape();
}
?>
