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
// =======================================================================
// P�gina de pesquisa de exclus�o de registros da tabela Area_atuacao
// =======================================================================
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.area_atuacao.php';
$pagina = new Pagina();
$banco = new area_atuacao();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa �rea de Atua��o"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
				   array("Area_atuacaoCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_AREA_ATUACAO);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_AREA_ATUACAO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_AREA_ATUACAO", "");

/* Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_AREA_ATUACAO", "N", "Nome", "60", "60", ""), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Nome", "");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_AREA_ATUACAO($v_SEQ_AREA_ATUACAO);
$banco->setNOM_AREA_ATUACAO($v_NOM_AREA_ATUACAO);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("�reas de Atua��o", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("Area_atuacaoAlteracao.php?v_SEQ_AREA_ATUACAO=".$row["seq_area_atuacao"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_AREA_ATUACAO", $row["seq_area_atuacao"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["nom_area_atuacao"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_AREA_ATUACAO=$v_SEQ_AREA_ATUACAO&v_NOM_AREA_ATUACAO=$v_NOM_AREA_ATUACAO");
$pagina->MontaRodape();
?>
