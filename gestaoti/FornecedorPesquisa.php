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
require 'include/PHP/class/class.fornecedor.php';
$pagina = new Pagina();
$banco = new fornecedor();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Fornecedor"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
				   array("FornecedorCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_NUM_CPF_CGC);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_NUM_CPF_CGC = ""; 
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_NUM_CPF_CGC", "");

/* Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_FORNECEDOR", "N", "Nome", "60", "60", ""), "left"); 

$pagina->LinhaCampoFormulario("Nome de Razao social:", "right", "N", $pagina->CampoTexto("v_NO_RAZAO_SOCIAL", "N", "Nome de Razao social", "60", "60", ""), "left"); 

$pagina->LinhaCampoFormulario("Nome de Contato:", "right", "N", $pagina->CampoTexto("v_NOM_CONTATO", "N", "Nome de Contato", "60", "60", ""), "left"); 

$pagina->LinhaCampoFormulario("N�mero de Telefone contato:", "right", "N", $pagina->CampoTexto("v_NUM_TELEFONE_CONTATO", "N", "N�mero de Telefone contato", "20", "20", ""), "left"); 

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Nome", "30%"); 
$header[] = array("Raz�o Social", "30%"); 
$header[] = array("Contato", "20%"); 
$header[] = array("Telefone", "20%"); 

// Setar vari�veis 
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setNUM_CPF_CGC($v_NUM_CPF_CGC);
$banco->setNOM_FORNECEDOR($v_NOM_FORNECEDOR);
$banco->setNO_RAZAO_SOCIAL($v_NO_RAZAO_SOCIAL);
$banco->setNOM_CONTATO($v_NOM_CONTATO);
$banco->setNUM_TELEFONE_CONTATO($v_NUM_TELEFONE_CONTATO);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Fornecedors encontrados para os par�mentos pesquisados", $header);
	while ($row = pg_fetch_array($banco->database->result)){ 
		$valor = $pagina->BotaoAlteraGridPesquisa("FornecedorAlteracao.php?v_NUM_CPF_CGC=".$row["NUM_CPF_CGC"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_NUM_CPF_CGC", $row["NUM_CPF_CGC"]); 
		$corpo[] = array("center", "campo", $valor); 
		$corpo[] = array("left", "campo", $row["NOM_FORNECEDOR"]);
		$corpo[] = array("left", "campo", $row["NO_RAZAO_SOCIAL"]);
		$corpo[] = array("left", "campo", $row["NOM_CONTATO"]);
		$corpo[] = array("left", "campo", $row["NUM_TELEFONE_CONTATO"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_NUM_CPF_CGC=$v_NUM_CPF_CGC&v_NOM_FORNECEDOR=$v_NOM_FORNECEDOR&v_NO_RAZAO_SOCIAL=$v_NO_RAZAO_SOCIAL&v_NOM_CONTATO=$v_NOM_CONTATO&v_NUM_TELEFONE_CONTATO=$v_NUM_TELEFONE_CONTATO");
$pagina->MontaRodape(); 
?>
