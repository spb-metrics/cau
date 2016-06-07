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
require 'include/PHP/class/class.fornecedor.php';
$pagina = new Pagina();
$banco = new fornecedor();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Fornecedor"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
				   array("FornecedorCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_NUM_CPF_CGC);
	$pagina->ScriptAlert("Registro Excluído");
	$v_NUM_CPF_CGC = ""; 
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_NUM_CPF_CGC", "");

/* Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_FORNECEDOR", "N", "Nome", "60", "60", ""), "left"); 

$pagina->LinhaCampoFormulario("Nome de Razao social:", "right", "N", $pagina->CampoTexto("v_NO_RAZAO_SOCIAL", "N", "Nome de Razao social", "60", "60", ""), "left"); 

$pagina->LinhaCampoFormulario("Nome de Contato:", "right", "N", $pagina->CampoTexto("v_NOM_CONTATO", "N", "Nome de Contato", "60", "60", ""), "left"); 

$pagina->LinhaCampoFormulario("Número de Telefone contato:", "right", "N", $pagina->CampoTexto("v_NUM_TELEFONE_CONTATO", "N", "Número de Telefone contato", "20", "20", ""), "left"); 

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Nome", "30%"); 
$header[] = array("Razão Social", "30%"); 
$header[] = array("Contato", "20%"); 
$header[] = array("Telefone", "20%"); 

// Setar variáveis 
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
	$pagina->LinhaHeaderTabelaResultado("Fornecedors encontrados para os parâmentos pesquisados", $header);
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
