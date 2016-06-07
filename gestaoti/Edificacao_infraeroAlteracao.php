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
require 'include/PHP/class/class.edificacao.php';
$pagina = new Pagina();
$banco = new edificacao();
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alter��o de Edifica��o"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Edificacao_infraeroPesquisa.php", "", "Pesquisa"),
						array("Edificacao_infraeroCadastro.php", "", "Adicionar"),
		 			    array("Edificacao_infraeroAlteracao.php", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_EDIFICACAO);

	// Inicio do formul�rio
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("v_SEQ_EDIFICACAO", $v_SEQ_EDIFICACAO);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_EDIFICACAO", "S", "Nome", "60", "60", "$banco->NOM_EDIFICACAO"), "left");

/*
	require 'include/PHP/class/class.dependencias.php';
	$dependencias = new dependencias();
	$pagina->LinhaCampoFormulario("Depend�ncia:", "right", "S", $pagina->CampoSelect("v_COD_DEPENDENCIA", "S", "Depend�ncia", "N", $dependencias->combo(2, $banco->COD_DEPENDENCIA)), "left", "v_COD_DEPENDENCIA", "30%", "70%");
*/
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	$banco->setNOM_EDIFICACAO($v_NOM_EDIFICACAO);
	$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	$banco->selectParam();
	if($banco->database->rows == 0){
		// Alterar regstro
		$banco->setNOM_EDIFICACAO($v_NOM_EDIFICACAO);
		$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
		$banco->update($v_SEQ_EDIFICACAO);
		if($banco->error != ""){
			$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
		}else{
			$pagina->redirectTo("Edificacao_infraeroPesquisa.php?flag=1&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO");
		}
	}else{
		$pagina->mensagem("Registro n�o alterado. J� existe uma edifica��o com o mesmo nome registrada para a depend�ncia selecionada.");
	}
}
?>
