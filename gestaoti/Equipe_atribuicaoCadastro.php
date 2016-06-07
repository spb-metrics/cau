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
require 'include/PHP/class/class.equipe_atribuicao.php';
$pagina = new Pagina();
$banco = new equipe_atribuicao();
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Atribui��o de Equipes de TI"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Equipe_atribuicaoPesquisa.php", "", "Pesquisa"),
						array("Equipe_atribuicaoCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	
	$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];
	

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");

	// Montar a combo da tabela equipe_ti
	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	
	$pagina->LinhaCampoFormulario("Equipe:", "right", "S", $pagina->CampoSelect("v_SEQ_EQUIPE_TI", "S", "Equipe ti", "S", $equipe_ti->combo(2, $banco->SEQ_EQUIPE_TI)), "left");
	$pagina->LinhaCampoFormulario("Descri��o:", "right", "S", $pagina->CampoTexto("v_DSC_EQUIPE_ATRIBUICAO", "S", "Descri��o", "60", "60", ""), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro

	$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco->setDSC_EQUIPE_ATRIBUICAO($v_DSC_EQUIPE_ATRIBUICAO);
	$banco->insert();
	// C�digo inserido: $banco->SEQ_EQUIPE_ATRIBUICAO
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Equipe_atribuicaoPesquisa.php?v_SEQ_EQUIPE_ATRIBUICAO=$banco->SEQ_EQUIPE_ATRIBUICAO");
	}
}
?>
