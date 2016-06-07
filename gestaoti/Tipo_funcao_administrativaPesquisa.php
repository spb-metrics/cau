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
require 'include/PHP/class/class.tipo_funcao_administrativa.php';
$pagina = new Pagina();
$banco = new tipo_funcao_administrativa();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Fun��o Administrativa"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
		   array("Tipo_funcao_administrativaCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA = "";
}
print $pagina->CampoHidden("v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA", "");
print $pagina->CampoHidden("flag", "1");
/*
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario(" de _tipo software:", "right", "N", $pagina->CampoTexto("v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA", "N", " de _tipo software", "9)", "9)", ""), "left");

$pagina->LinhaCampoFormulario(" de _tipo software:", "right", "N", $pagina->CampoTexto("v_NOM_TIPO_FUNCAO_ADMINISTRATIVA", "N", " de _tipo software", "60", "60", ""), "left");

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
$banco->setSEQ_TIPO_FUNCAO_ADMINISTRATIVA($v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA);
$banco->setNOM_TIPO_FUNCAO_ADMINISTRATIVA($v_NOM_TIPO_FUNCAO_ADMINISTRATIVA);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Fun��es Administrativas", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("Tipo_funcao_administrativaAlteracao.php?v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA=".$row["seq_tipo_funcao_administrativa"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA", $row["seq_tipo_funcao_administrativa"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["nom_tipo_funcao_administrativa"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA=$v_SEQ_TIPO_FUNCAO_ADMINISTRATIVA&v_NOM_TIPO_FUNCAO_ADMINISTRATIVA=$v_NOM_TIPO_FUNCAO_ADMINISTRATIVA");
$pagina->MontaRodape();
?>
