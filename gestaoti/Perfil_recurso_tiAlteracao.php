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
require 'include/PHP/class/class.perfil_recurso_ti.php';
$pagina = new Pagina();
$banco = new perfil_recurso_ti();
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterar de Perfil dos Profissionais"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Perfil_recurso_tiPesquisa.php", "", "Pesquisa"),
						array("Perfil_recurso_tiCadastro.php", "", "Adicionar"),
		 			    array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_PERFIL_RECURSO_TI);

	// Inicio do formul�rio
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_PERFIL_RECURSO_TI", $banco->SEQ_PERFIL_RECURSO_TI);
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_PERFIL_RECURSO_TI", "S", "Nome", "60", "60", "$banco->NOM_PERFIL_RECURSO_TI"), "left");
	$pagina->LinhaCampoFormulario("Valor da hora:", "right", "S", $pagina->CampoMoney("v_VAL_HORA", "S", "Valor da hora", "$banco->VAL_HORA") , "left");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Alterar regstro

	$banco->setNOM_PERFIL_RECURSO_TI($v_NOM_PERFIL_RECURSO_TI);
	$banco->setVAL_HORA($v_VAL_HORA);
	$banco->update($v_SEQ_PERFIL_RECURSO_TI);
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Perfil_recurso_tiPesquisa.php?v_SEQ_PERFIL_RECURSO_TI=$v_SEQ_PERFIL_RECURSO_TI");
	}
}
?>
