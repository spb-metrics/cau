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
require 'include/PHP/class/class.localizacao_fisica.php';
$pagina = new Pagina();
$banco = new localizacao_fisica();
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alter��o de Localiza��o F�sica"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Localizacao_fisicaPesquisa.php", "", "Pesquisa"),
						array("Localizacao_fisicaCadastro.php", "", "Adicionar"),
		 			    array("Localizacao_fisicaAlteracao.php", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_LOCALIZACAO_FISICA);

	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("v_SEQ_LOCALIZACAO_FISICA", $v_SEQ_LOCALIZACAO_FISICA);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");

	// Montar a combo da tabela edificacao
	require_once 'include/PHP/class/class.edificacao.php';
	$edificacao = new edificacao();
	$pagina->LinhaCampoFormulario("Edificacao infraero:", "right", "S", $pagina->CampoSelect("v_SEQ_EDIFICACAO", "S", "Edificacao infraero", "S", $edificacao->combo(2, $banco->SEQ_EDIFICACAO)), "left");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_LOCALIZACAO_FISICA", "S", "Nome", "60", "60", "$banco->NOM_LOCALIZACAO_FISICA"), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Alterar regstro

	$banco->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
	$banco->setNOM_LOCALIZACAO_FISICA($v_NOM_LOCALIZACAO_FISICA);
	$banco->update($v_SEQ_LOCALIZACAO_FISICA);
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Localizacao_fisicaPesquisa.php?flag=1&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA");
	}
}
?>
