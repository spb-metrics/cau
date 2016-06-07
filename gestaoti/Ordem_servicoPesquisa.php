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
require 'include/PHP/class/class.ordem_servico.php';
$pagina = new Pagina();
$banco = new ordem_servico();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Ordem de Servi�o"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
if($v_SEQ_ITEM_CONFIGURACAO != ""){
	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.item_configuracao.php';
	$item_configuracao = new item_configuracao();
	$item_configuracao->select($v_SEQ_ITEM_CONFIGURACAO);
		
	$aItemAba = Array( array("Ordem_servicoPesquisa.php", "", "Pesquisa Todos"),
					   array("Ordem_servicoPesquisa.php", "tabact", "Pesquisa"),
					   array("Item_configuracaoAlteracao.php?v_SEQ_ITEM_CONFIGURACAO=".$v_SEQ_ITEM_CONFIGURACAO, "", "Retornar"),
					   array("Ordem_servicoCadastro.php?v_SEQ_ITEM_CONFIGURACAO=".$v_SEQ_ITEM_CONFIGURACAO, "", "Adicionar") );
}else{
	$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
					   array("Ordem_servicoCadastro.php", "", "Adicionar") );
}

$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_ORDEM_SERVICO_TI);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_ORDEM_SERVICO_TI = ""; 
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_ORDEM_SERVICO_TI", "");
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", $v_SEQ_ITEM_CONFIGURACAO);

/* Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");


// Buscar dados da tabela externa
require_once 'include/PHP/class/class.fornecedor.php';
$fornecedor = new fornecedor();
$aItemOption = Array();
	
$fornecedor->selectParam(2);
$cont = 0;
while ($row = pg_fetch_array($fornecedor->database->result)){
	$aItemOption[$cont] = array($row[0], $pagina->iif($v_NUM_CPF_CGC == $row[0],"Selected", ""), $row[1]);
	$cont++;
}
// Adicionar combo no formul�rio
$pagina->LinhaCampoFormulario("Fornecedor:", "right", "N", $pagina->CampoSelect("v_NUM_CPF_CGC", "N", "Fornecedor", "S", $aItemOption), "left");

// Buscar dados da tabela externa
require_once 'include/PHP/class/class.item_configuracao.php';
$item_configuracao = new item_configuracao();
$aItemOption = Array();
	
$item_configuracao->selectParam(2);
$cont = 0;
while ($row = pg_fetch_array($item_configuracao->database->result)){
	$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_ITEM_CONFIGURACAO == $row[0],"Selected", ""), $row[1]);
	$cont++;
}
// Adicionar combo no formul�rio
$pagina->LinhaCampoFormulario("Item configuracao:", "right", "N", $pagina->CampoSelect("v_SEQ_ITEM_CONFIGURACAO", "N", "Item configuracao", "S", $aItemOption), "left");
$pagina->LinhaCampoFormulario("N�mero:", "right", "N", $pagina->CampoTexto("v_NUM_ORDEM_SERVICO", "N", "N�mero", "30", "30", ""), "left"); 

$pagina->LinhaCampoFormulario("Valor de Pagamento:", "right", "N", $pagina->CampoTexto("v_VAL_PAGAMENTO", "N", "Valor de Pagamento", "8", "8", ""), "left"); 

$pagina->LinhaCampoFormulario("N�mero de Nota fiscal:", "right", "N", $pagina->CampoTexto("v_NUM_NOTA_FISCAL", "N", "N�mero de Nota fiscal", "30", "30", ""), "left"); 

$pagina->LinhaCampoFormulario("Data de Inicio:", "right", "N", 
			"de ".$pagina->CampoData("v_DAT_INICIO", "N", "Data de Inicio", "")
			." a ".$pagina->CampoData("v_DAT_INICIO_FINAL", "N", "Data de Inicio", "")
			, "left");

$pagina->LinhaCampoFormulario("Data de Fim:", "right", "N", 
			"de ".$pagina->CampoData("v_DAT_FIM", "N", "Data de Fim", "")
			." a ".$pagina->CampoData("v_DAT_FIM_FINAL", "N", "Data de Fim", "")
			, "left");

$pagina->LinhaCampoFormulario("Data de Entrega:", "right", "N", 
			"de ".$pagina->CampoData("v_DAT_ENTREGA", "N", "Data de Entrega", "")
			." a ".$pagina->CampoData("v_DAT_ENTREGA_FINAL", "N", "Data de Entrega", "")
			, "left");

$pagina->LinhaCampoFormulario("Descri��o:", "right", "N", $pagina->CampoTextArea("v_DSC_ORDEM_SERVICO", "N", "Descri��o", "59", "5", ""), "left");

$pagina->LinhaCampoFormulario("N�mero de Pec:", "right", "N", $pagina->CampoTexto("v_NUM_PEC", "N", "N�mero de Pec", "30", "30", ""), "left"); 

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Fornecedor", "20%"); 
if($v_SEQ_ITEM_CONFIGURACAO == ""){
	$header[] = array("Item", "15%"); 
}
$header[] = array("O.S.", "10%");
$header[] = array("Valor", "10%"); 
$header[] = array("Nota Fical", "10%"); 
$header[] = array("Inicio", "10%"); 
$header[] = array("Fim", "10%"); 
$header[] = array("Entrega", "10%"); 
//$header[] = array("PEC", "10%"); 
//$header[] = array("Descri��o", ""); 


// Setar vari�veis 
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_ORDEM_SERVICO_TI($v_SEQ_ORDEM_SERVICO_TI);
$banco->setNUM_CPF_CGC($v_NUM_CPF_CGC);
$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
$banco->setNUM_ORDEM_SERVICO($v_NUM_ORDEM_SERVICO);
$banco->setVAL_PAGAMENTO($v_VAL_PAGAMENTO);
$banco->setNUM_NOTA_FISCAL($v_NUM_NOTA_FISCAL);
$banco->setDAT_INICIO($v_DAT_INICIO);
$banco->setDAT_FIM($v_DAT_FIM);
$banco->setDAT_ENTREGA($v_DAT_ENTREGA);
$banco->setDSC_ORDEM_SERVICO($v_DSC_ORDEM_SERVICO);
$banco->setNUM_PEC($v_NUM_PEC);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	if($v_SEQ_ITEM_CONFIGURACAO == ""){
		$pagina->LinhaHeaderTabelaResultado("Ordens de Servi�os Encontradas", $header);
	}else{
		$pagina->LinhaHeaderTabelaResultado("Ordens de Servi�os Relacionadas a ".$item_configuracao->NOM_ITEM_CONFIGURACAO, $header);
	}
	while ($row = pg_fetch_array($banco->database->result)){ 
		$valor = $pagina->BotaoAlteraGridPesquisa("Ordem_servicoAlteracao.php?v_SEQ_ORDEM_SERVICO_TI=".$row["SEQ_ORDEM_SERVICO_TI"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_ORDEM_SERVICO_TI", $row["SEQ_ORDEM_SERVICO_TI"]); 
		$corpo[] = array("center", "campo", $valor); 
		require_once 'include/PHP/class/class.fornecedor.php';
		$fornecedor = new fornecedor();
		$fornecedor->select($row["NUM_CPF_CGC"]);
		$corpo[] = array("Left", "campo", $fornecedor->NOM_FORNECEDOR);
		if($v_SEQ_ITEM_CONFIGURACAO == ""){
			$corpo[] = array("right", "campo", $row["SEQ_ITEM_CONFIGURACAO"]);
		}
		$corpo[] = array("left", "campo", $row["NUM_ORDEM_SERVICO"]);
		$corpo[] = array("right", "campo", number_format($row["VAL_PAGAMENTO"],2));
		$corpo[] = array("left", "campo", $row["NUM_NOTA_FISCAL"]);
		$corpo[] = array("center", "campo", $pagina->ConvDataDMA($row["DAT_INICIO"],"/"));
		$corpo[] = array("center", "campo", $pagina->ConvDataDMA($row["DAT_FIM"],"/"));
		$corpo[] = array("center", "campo", $pagina->ConvDataDMA($row["DAT_ENTREGA"],"/"));
//		$corpo[] = array("left", "campo", $row["DSC_ORDEM_SERVICO"]);
//		$corpo[] = array("left", "campo", $row["NUM_PEC"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_ORDEM_SERVICO_TI=$v_SEQ_ORDEM_SERVICO_TI&v_NUM_CPF_CGC=$v_NUM_CPF_CGC&v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO&v_NUM_ORDEM_SERVICO=$v_NUM_ORDEM_SERVICO&v_VAL_PAGAMENTO=$v_VAL_PAGAMENTO&v_NUM_NOTA_FISCAL=$v_NUM_NOTA_FISCAL&v_DAT_INICIO=$v_DAT_INICIO&v_DAT_FIM=$v_DAT_FIM&v_DAT_ENTREGA=$v_DAT_ENTREGA&v_DSC_ORDEM_SERVICO=$v_DSC_ORDEM_SERVICO&v_NUM_PEC=$v_NUM_PEC");
$pagina->MontaRodape(); 
?>
