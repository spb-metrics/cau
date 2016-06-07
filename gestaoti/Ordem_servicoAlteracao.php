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
require 'include/PHP/class/class.ordem_servico.php';
$pagina = new Pagina();
$banco = new ordem_servico();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterar Ordem de Serviço"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Ordem_servicoPesquisa.php", "", "Pesquisa"),
						array("Ordem_servicoCadastro.php", "", "Adicionar"),
		 			    array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_ORDEM_SERVICO_TI);
	
	// Inicio do formulário
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
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Fornecedor:", "right", "S", $pagina->CampoSelect("v_NUM_CPF_CGC", "S", "Fornecedor", "S", $aItemOption), "left");
	$pagina->LinhaCampoFormulario("O.S.:", "right", "S", $pagina->CampoTexto("v_NUM_ORDEM_SERVICO", "S", "Número", "30", "30", "$banco->NUM_ORDEM_SERVICO"), "left"); 
	$pagina->LinhaCampoFormulario("Valor:", "right", "N", $pagina->CampoTexto("v_VAL_PAGAMENTO", "N", "Valor de Pagamento", "8", "8", "$banco->VAL_PAGAMENTO"), "left"); 	$pagina->LinhaCampoFormulario("Nº Nota fiscal:", "right", "N", $pagina->CampoTexto("v_NUM_NOTA_FISCAL", "N", "Número de Nota fiscal", "30", "30", "$banco->NUM_NOTA_FISCAL"), "left"); 
	$pagina->LinhaCampoFormulario("Data de Inicio:", "right", "N", $pagina->CampoData("v_DAT_INICIO", "N", "Data de Inicio", $pagina->ConvDataDMA($banco->DAT_INICIO,"/")), "left");
	$pagina->LinhaCampoFormulario("Data de Fim:", "right", "N", $pagina->CampoData("v_DAT_FIM", "N", "Data de Fim", $pagina->ConvDataDMA($banco->DAT_FIM,"/")), "left");
	$pagina->LinhaCampoFormulario("Data de Entrega:", "right", "N", $pagina->CampoData("v_DAT_ENTREGA", "N", "Data de Entrega", $pagina->ConvDataDMA($banco->DAT_ENTREGA,"/")), "left");
	$pagina->LinhaCampoFormulario("PEC:", "right", "N", $pagina->CampoTexto("v_NUM_PEC", "N", "Número de Pec", "30", "30", "$banco->NUM_PEC"), "left"); 
	$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTextArea("v_DSC_ORDEM_SERVICO", "N", "Descrição", "59", "5", "$banco->DSC_ORDEM_SERVICO"), "left");
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
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Ordem_servicoPesquisa.php?v_SEQ_ORDEM_SERVICO_TI=$v_SEQ_ORDEM_SERVICO_TI&v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO");
	}
}
?>
