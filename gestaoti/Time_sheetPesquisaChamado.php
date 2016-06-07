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
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.time_sheet.php';
$pagina = new Pagina();
$banco = new time_sheet();
if($v_SEQ_CHAMADO != ""){
	// Configuração da págína
	$pagina->flagScriptCalendario = 0;
	$pagina->flagMenu = 0;
	$pagina->flagTopo = 0;
	$pagina->MontaCabecalho();

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Profissional", "50%");
	$header[] = array("Início", "25%");
	$header[] = array("Fim", "25%");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$banco->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
	$banco->setDTH_INICIO($v_DTH_INICIO);
//	$banco->setDTH_FIM($v_DTH_FIM);
	$banco->selectParam("DTH_INICIO DESC ", $vNumPagina, 10);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$corpo[] = array("left", "campo", $row["nom_colaborador"]);
			$corpo[] = array("center", "campo", $row["dth_inicio"]);
			$corpo[] = array("center", "campo", $row["dth_fim"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIME_SHEET=$v_SEQ_TIME_SHEET&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_NUM_MATRICULA=$v_NUM_MATRICULA&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM");
	print "</body></html>";
	//$pagina->MontaRodape();
}

?>
