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
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterar Ordem de Servi�o"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Ordem_servicoPesquisa.php", "", "Pesquisa"),
						array("Ordem_servicoCadastro.php", "", "Adicionar"),
		 			    array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_ORDEM_SERVICO_TI);
	
	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", $banco->SEQ_ITEM_CONFIGURACAO);
	print $pagina->CampoHidden("v_SEQ_ORDEM_SERVICO_TI", $banco->SEQ_ORDEM_SERVICO_TI);
	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.item_configuracao.php';
	$item_configuracao = new item_configuracao();
	$item_configuracao->select($banco->SEQ_ITEM_CONFIGURACAO);
	$pagina->LinhaCampoFormulario("Item configuracao:", "right", "S", $item_configuracao->NOM_ITEM_CONFIGURACAO, "left");
	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.fornecedor.php';
	$fornecedor = new fornecedor();
	$aItemOption = Array();
	
	$fornecedor->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($fornecedor->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($banco->NUM_CPF_CGC == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formul�rio
	$pagina->LinhaCampoFormulario("Fornecedor:", "right", "S", $pagina->CampoSelect("v_NUM_CPF_CGC", "S", "Fornecedor", "S", $aItemOption), "left");
	$pagina->LinhaCampoFormulario("O.S.:", "right", "S", $pagina->CampoTexto("v_NUM_ORDEM_SERVICO", "S", "N�mero", "30", "30", "$banco->NUM_ORDEM_SERVICO"), "left"); 
	$pagina->LinhaCampoFormulario("Valor:", "right", "N", $pagina->CampoTexto("v_VAL_PAGAMENTO", "N", "Valor de Pagamento", "8", "8", "$banco->VAL_PAGAMENTO"), "left"); 	$pagina->LinhaCampoFormulario("N� Nota fiscal:", "right", "N", $pagina->CampoTexto("v_NUM_NOTA_FISCAL", "N", "N�mero de Nota fiscal", "30", "30", "$banco->NUM_NOTA_FISCAL"), "left"); 
	$pagina->LinhaCampoFormulario("Data de Inicio:", "right", "N", $pagina->CampoData("v_DAT_INICIO", "N", "Data de Inicio", $pagina->ConvDataDMA($banco->DAT_INICIO,"/")), "left");
	$pagina->LinhaCampoFormulario("Data de Fim:", "right", "N", $pagina->CampoData("v_DAT_FIM", "N", "Data de Fim", $pagina->ConvDataDMA($banco->DAT_FIM,"/")), "left");
	$pagina->LinhaCampoFormulario("Data de Entrega:", "right", "N", $pagina->CampoData("v_DAT_ENTREGA", "N", "Data de Entrega", $pagina->ConvDataDMA($banco->DAT_ENTREGA,"/")), "left");
	$pagina->LinhaCampoFormulario("PEC:", "right", "N", $pagina->CampoTexto("v_NUM_PEC", "N", "N�mero de Pec", "30", "30", "$banco->NUM_PEC"), "left"); 
	$pagina->LinhaCampoFormulario("Descri��o:", "right", "N", $pagina->CampoTextArea("v_DSC_ORDEM_SERVICO", "N", "Descri��o", "59", "5", "$banco->DSC_ORDEM_SERVICO"), "left");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape(); 
}else{
	// Alterar regstro

	$banco->setNUM_CPF_CGC($v_NUM_CPF_CGC);
	$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
	$banco->setNUM_ORDEM_SERVICO($v_NUM_ORDEM_SERVICO);
	$banco->setVAL_PAGAMENTO($v_VAL_PAGAMENTO);
	$banco->setNUM_NOTA_FISCAL($v_NUM_NOTA_FISCAL);
	$banco->setDAT_INICIO($pagina->ConvDataAMD($v_DAT_INICIO));
	$banco->setDAT_FIM($pagina->ConvDataAMD($v_DAT_FIM));
	$banco->setDAT_ENTREGA($pagina->ConvDataAMD($v_DAT_ENTREGA));
	$banco->setDSC_ORDEM_SERVICO($v_DSC_ORDEM_SERVICO);
	$banco->setNUM_PEC($v_NUM_PEC);
	$banco->update($v_SEQ_ORDEM_SERVICO_TI);
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Ordem_servicoPesquisa.php?v_SEQ_ORDEM_SERVICO_TI=$v_SEQ_ORDEM_SERVICO_TI&v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO");
	}
}
?>
