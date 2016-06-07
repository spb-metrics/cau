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
	if($v_SEQ_ITEM_CONFIGURACAO != ""){
		$aItemAba = Array( array("Ordem_servicoPesquisa.php", "", "Pesquisa Todos"),
						   array("Ordem_servicoPesquisa.php?v_SEQ_ITEM_CONFIGURACAO=".$v_SEQ_ITEM_CONFIGURACAO, "", "Pesquisa"),
						   array("Item_configuracaoAlteracao.php?v_SEQ_ITEM_CONFIGURACAO=".$v_SEQ_ITEM_CONFIGURACAO, "", "Retornar"),
						   array("Ordem_servicoCadastro.php?v_SEQ_ITEM_CONFIGURACAO=".$v_SEQ_ITEM_CONFIGURACAO, "tabact", "Adicionar") );
	}else{
		$aItemAba = Array( array("Ordem_servicoPesquisa.php", "", "Pesquisa"),
							array("#", "tabact", "Adicionar") );
	}

	$pagina->SettituloCabecalho("Cadastro de Ordem de Servi�o"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", $v_SEQ_ITEM_CONFIGURACAO);
	$pagina->AbreTabelaPadrao("center", "85%");
	
	if($v_SEQ_ITEM_CONFIGURACAO != ""){
		// Buscar dados da tabela externa
		require_once 'include/PHP/class/class.item_configuracao.php';
		$item_configuracao = new item_configuracao();
		$item_configuracao->select($v_SEQ_ITEM_CONFIGURACAO);
		$pagina->LinhaCampoFormulario("Item configuracao:", "right", "S", 
									   $item_configuracao->NOM_ITEM_CONFIGURACAO
									, "left");
	}else{
		
		// Adicionar combo no formul�rio
		$pagina->LinhaCampoFormulario("Item configuracao:", "right", "S", 
									   $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO", "N", "" , "60", "60", "", "readonly").
									   $pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO", "v_SEQ_ITEM_CONFIGURACAO")
									, "left");
	}
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
	$pagina->LinhaCampoFormulario("Fornecedor:", "right", "S", $pagina->CampoSelect("v_NUM_CPF_CGC", "S", "Fornecedor", "S", $aItemOption), "left");
	$pagina->LinhaCampoFormulario("N� da O.S.:", "right", "S", $pagina->CampoTexto("v_NUM_ORDEM_SERVICO", "S", "N�mero", "30", "30", ""), "left"); 
	$pagina->LinhaCampoFormulario("Valor:", "right", "N", $pagina->CampoTexto("v_VAL_PAGAMENTO", "N", "Valor de Pagamento", "8", "8", ""), "left"); 
	$pagina->LinhaCampoFormulario("N� da Nota fiscal:", "right", "N", $pagina->CampoTexto("v_NUM_NOTA_FISCAL", "N", "N�mero de Nota fiscal", "30", "30", ""), "left"); 
	$pagina->LinhaCampoFormulario("Data de Inicio:", "right", "N", $pagina->CampoData("v_DAT_INICIO", "N", "Data de Inicio", ""), "left");
	$pagina->LinhaCampoFormulario("Data Final:", "right", "N", $pagina->CampoData("v_DAT_FIM", "N", "Data de Fim", ""), "left");
	$pagina->LinhaCampoFormulario("Data de Entrega:", "right", "N", $pagina->CampoData("v_DAT_ENTREGA", "N", "Data de Entrega", ""), "left");
	$pagina->LinhaCampoFormulario("Descri��o:", "right", "N", $pagina->CampoTextArea("v_DSC_ORDEM_SERVICO", "N", "Descri��o", "59", "5", ""), "left");
	$pagina->LinhaCampoFormulario("N�mero da Pec:", "right", "N", $pagina->CampoTexto("v_NUM_PEC", "N", "N�mero de Pec", "30", "30", ""), "left"); 

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape(); 
}else{
	// Incluir regstro

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
	$banco->insert();
	// C�digo inserido: $banco->SEQ_ORDEM_SERVICO_TI
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Ordem_servicoPesquisa.php?v_SEQ_ITEM_CONFIGURACAO=$banco->SEQ_ITEM_CONFIGURACAO");
	}
}
?>
