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
require 'include/PHP/class/class.item_configuracao.php';
require 'include/PHP/class/class.unidades_organizacionais.php';
require 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$banco = new item_configuracao();
$unidades_organizacionais = new unidades_organizacionais();
$empregados = new empregados();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Lista de Sistemas de Informa��o"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");


// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("TI Regional:", "right", "S",
								  $pagina->CampoTexto("v_UOR_SIGLA", "S", "TI Regional", "10", "10", "TISI").
								  $pagina->ButtonProcuraUorg("v_UOR_SIGLA", "TI")
								  , "left");

$aItemOption = Array();
$aItemOption[] = array("D", $pagina->iif($vOrderBy == "REGIONAL","Selected", ""), "Sistemas em desenvolvimento");
$aItemOption[] = array("M", $pagina->iif($vOrderBy == "NOME","Selected", ""), "Sistemas em manuten��o");
$pagina->LinhaCampoFormulario("Listar:", "right", "N", $pagina->CampoSelect("vListar", "N", "", "S", $aItemOption), "left");

$aItemOption = Array();
$aItemOption[] = array("NOME", $pagina->iif($vOrderBy == "NOME","Selected", ""), "L�der");
$aItemOption[] = array("REGIONAL", $pagina->iif($vOrderBy == "REGIONAL","Selected", ""), "Diretoria");
$aItemOption[] = array("SIG_ITEM_CONFIGURACAO", $pagina->iif($vOrderBy == "SIG_ITEM_CONFIGURACAO","Selected", ""), "Sigla");
$aItemOption[] = array("NOM_ITEM_CONFIGURACAO", $pagina->iif($vOrderBy == "NOM_ITEM_CONFIGURACAO","Selected", ""), "Nome");
$pagina->LinhaCampoFormulario("Ordenar lista por:", "right", "N", $pagina->CampoSelect("vOrderBy", "N", "", "N", $aItemOption), "left");
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
if($flag == "1"){
	$pagina->LinhaVazia(1);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("L�der", "30%");
	$header[] = array("Sigla", "25%");
	$header[] = array("Nome", "35%");
	$header[] = array("Diretoria", "10%");

	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setUOR_SIGLA($v_UOR_SIGLA);

	$banco->selectParamSoftware($pagina->iif($vOrderBy == "", "NOME", $vOrderBy), vListar, $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Item configuracaos encontrados para os par�mentos pesquisados", $header);
		$cont = 0;
		while ($row = pg_fetch_array($banco->database->result)){
			$corpo[] = array("left", "campo", $row["NOME"]);
			$corpo[] = array("left", "campo", $row["SIG_ITEM_CONFIGURACAO"]);
			$corpo[] = array("left", "campo", $row["NOM_ITEM_CONFIGURACAO"]);
			$corpo[] = array("center", "campo", $row["REGIONAL"]);
			$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"location.href='Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$row["SEQ_ITEM_CONFIGURACAO"]."';\"");
			$corpo = "";
			$cont++;
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO&v_SEQ_TIPO_ITEM_CONFIGURACAO=$v_SEQ_TIPO_ITEM_CONFIGURACAO&v_SEQ_SERVICO=$v_SEQ_SERVICO&v_NUM_MATRICULA_GESTOR=$v_NUM_MATRICULA_GESTOR&v_NUM_MATRICULA_LIDER=$v_NUM_MATRICULA_LIDER&v_SIG_ITEM_CONFIGURACAO=$v_SIG_ITEM_CONFIGURACAO&v_NOM_ITEM_CONFIGURACAO=$v_NOM_ITEM_CONFIGURACAO&v_COD_UOR_AREA_GESTORA=$v_COD_UOR_AREA_GESTORA&v_TXT_ITEM_CONFIGURACAO=$v_TXT_ITEM_CONFIGURACAO&v_SEQ_TIPO_DISPONIBILIDADE=$v_SEQ_TIPO_DISPONIBILIDADE&v_SEQ_PRIORIDADE=$v_SEQ_PRIORIDADE&vListar=$vListar&vOrderBy=$vOrderBy&v_UOR_SIGLA=$v_UOR_SIGLA");
}
$pagina->MontaRodape();
?>
