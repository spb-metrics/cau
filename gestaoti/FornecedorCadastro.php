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
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de  Fornecedor"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("FornecedorPesquisa.php", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_FORNECEDOR", "S", "Nome", "60", "60", ""), "left"); 

	$pagina->LinhaCampoFormulario("Nome de Razao social:", "right", "N", $pagina->CampoTexto("v_NO_RAZAO_SOCIAL", "N", "Nome de Razao social", "60", "60", ""), "left"); 

	$pagina->LinhaCampoFormulario("Nome de Contato:", "right", "N", $pagina->CampoTexto("v_NOM_CONTATO", "N", "Nome de Contato", "60", "60", ""), "left"); 

	$pagina->LinhaCampoFormulario("N�mero de Telefone contato:", "right", "N", $pagina->CampoTexto("v_NUM_TELEFONE_CONTATO", "N", "N�mero de Telefone contato", "20", "20", ""), "left"); 

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
	// C�digo inserido: $banco->NUM_CPF_CGC
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("FornecedorPesquisa.php?v_NUM_CPF_CGC=$banco->NUM_CPF_CGC");
	}
}
?>
