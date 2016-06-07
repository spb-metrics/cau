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
require 'include/PHP/class/class.atribuicao_chamado.php';
$pagina = new Pagina();
$banco = new atribuicao_chamado();
if($v_SEQ_CHAMADO != ""){
	// Configura��o da p�g�na
	$pagina->flagScriptCalendario = 0;
	$pagina->flagMenu = 0;
	$pagina->flagTopo = 0;
	$pagina->MontaCabecalho();

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();

	$header[] = array("Equipe", "17%");
	$header[] = array("Profissional", "17%");
	$header[] = array("Situacao", "15%");
	//$header[] = array("Atribui��o", "");
	$header[] = array("Atividades", "");
	$header[] = array("In�cio", "10%");
	$header[] = array("Encerramento", "10%");

	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_ATRIBUICAO_CHAMADO($v_SEQ_ATRIBUICAO_CHAMADO);
	$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
	$banco->setNUM_MATRICULA($v_NUM_MATRICULA);
	$banco->setTXT_ATIVIDADE($v_TXT_ATIVIDADE);
	$banco->setDTH_ATRIBUICAO($v_DTH_ATRIBUICAO);
	$banco->selectParam("NOM_EQUIPE_TI, NOM_COLABORADOR", $vNumPagina, 10);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$corpo[] = array("left", "campo", $row["nom_equipe_ti"]);
			$corpo[] = array("left", "campo", $row["nom_colaborador"]);
			$corpo[] = array("left", "campo", $row["dsc_situacao_chamado"]);
			//$corpo[] = array("left", "campo", $row["dsc_equipe_atribuicao"]);
			$corpo[] = array("left", "campo", $row["txt_atividade"]);
			$corpo[] = array("center", "campo", $row["dth_inicio_efetivo"]);
			$corpo[] = array("center", "campo", $row["dth_encerramento_efetivo"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_ATRIBUICAO_CHAMADO=$v_SEQ_ATRIBUICAO_CHAMADO&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_NUM_MATRICULA=$v_NUM_MATRICULA&v_TXT_ATIVIDADES=$v_TXT_ATIVIDADES&v_DTH_ATRIBUICAO=$v_DTH_ATRIBUICAO");
	print "</body></html>";
}
?>
