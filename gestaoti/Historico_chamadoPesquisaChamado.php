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
require 'include/PHP/class/class.historico_chamado.php';
$pagina = new Pagina();
$banco = new historico_chamado();
if($v_SEQ_CHAMADO != ""){
	// Configuração da págína
	$pagina->flagScriptCalendario = 0;
	$pagina->flagMenu = 0;
	$pagina->flagTopo = 0;
	$pagina->MontaCabecalho();

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Data", "17%");
	$header[] = array("Situação", "20%");
	$header[] = array("Responsável", "25%");
	$header[] = array("Observação", "");

	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();

	// Setar variáveis
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
