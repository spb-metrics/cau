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
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de  Fornecedor"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("FornecedorPesquisa.php", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();
	
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_FORNECEDOR", "S", "Nome", "60", "60", ""), "left"); 

	$pagina->LinhaCampoFormulario("Nome de Razao social:", "right", "N", $pagina->CampoTexto("v_NO_RAZAO_SOCIAL", "N", "Nome de Razao social", "60", "60", ""), "left"); 

	$pagina->LinhaCampoFormulario("Nome de Contato:", "right", "N", $pagina->CampoTexto("v_NOM_CONTATO", "N", "Nome de Contato", "60", "60", ""), "left"); 

	$pagina->LinhaCampoFormulario("Número de Telefone contato:", "right", "N", $pagina->CampoTexto("v_NUM_TELEFONE_CONTATO", "N", "Número de Telefone contato", "20", "20", ""), "left"); 

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape(); 
}else{
	// Incluir regstro

	$banco->setNOM_FORNECEDOR($v_NOM_FORNECEDOR);
	$banco->setNO_RAZAO_SOCIAL($v_NO_RAZAO_SOCIAL);
	$banco->setNOM_CONTATO($v_NOM_CONTATO);
	$banco->setNUM_TELEFONE_CONTATO($v_NUM_TELEFONE_CONTATO);
	$banco->insert();
	// Código inserido: $banco->NUM_CPF_CGC
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("FornecedorPesquisa.php?v_NUM_CPF_CGC=$banco->NUM_CPF_CGC");
	}
}
?>
