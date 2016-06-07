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
require 'include/PHP/class/class.historico_acesso_chamado.php';
$pagina = new Pagina();
$banco = new historico_acesso_chamado();
if($v_SEQ_CHAMADO != ""){
	// Configuração da págína
	$pagina->flagScriptCalendario = 0;
	$pagina->flagMenu = 0;
	$pagina->flagTopo = 0;
	$pagina->MontaCabecalho();

	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Acessado por", "60%");
	$header[] = array("Data e Hora", "");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_HISTORICO_ACESSO_CHAMADO($v_SEQ_HISTORICO_ACESSO_CHAMADO);
	$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$banco->setNUM_MATRICULA($v_NUM_MATRICULA);
	$banco->setDTH_ACESSO($v_DTH_ACESSO);
	$banco->selectParam("DTH_ACESSO DESC ", $vNumPagina, 10);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$corpo[] = array("left", "campo", $row["nom_colaborador"]);
			$corpo[] = array("center", "campo", $row["dth_acesso"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_HISTORICO_ACESSO_CHAMADO=$v_SEQ_HISTORICO_ACESSO_CHAMADO&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_NUM_MATRICULA=$v_NUM_MATRICULA&v_DTH_ACESSO=$v_DTH_ACESSO");
	print "</body></html>";
	//$pagina->MontaRodape();
}
?>
