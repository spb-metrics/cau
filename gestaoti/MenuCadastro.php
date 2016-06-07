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
require 'include/PHP/class/class.menu.php';
$pagina = new Pagina();
$banco = new menu();
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Menu"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("MenuPesquisa.php", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formul�rio
	$pagina->MontaCabecalho();

	$pagina->AbreTabelaPadrao("center", "85%");
	print $pagina->CampoHidden("flag", "1");
	$pagina->LinhaCampoFormulario("Menu:", "right", "S", $pagina->CampoTexto("v_DSC_MENU_ACESSO", "S", "Menu", "60", "60", ""), "left");

	$menu = new menu();
	$i = 0;
	$menu->selectParam("2");
	while ($rowMenuPai = pg_fetch_array($menu->database->result)){
		$aItemOptionMenuPai[$i][0] = $rowMenuPai["seq_menu_acesso"];
		$aItemOptionMenuPai[$i][1] = "";
		$aItemOptionMenuPai[$i][2] = $rowMenuPai["dsc_menu_acesso"];
		$i++;
	}
	$pagina->LinhaCampoFormulario("Menu Pai:", "right", "N", $pagina->CampoSelect("v_SEQ_MENU_ACESSO_PAI", "N", "Escolha", "S", $aItemOptionMenuPai), "left");

	$pagina->LinhaCampoFormulario("Arquivo:", "right", "S", $pagina->CampoTexto("v_NOM_ARQUIVO", "N", "Arquivo", "60", "60", ""), "left");
	$pagina->LinhaCampoFormulario("Prioridade:", "right", "S", $pagina->CampoTexto("v_NUM_PRIORIDADE", "S", "Prioridade", "2)", "2)", ""), "left");
	$pagina->LinhaCampoFormulario("Imagem escuro:", "right", "N", $pagina->CampoTexto("v_NOM_ARQUIVO_IMAGEM_ESCURO", "N", "Nome de Rrquivo imagem escuro", "30", "30", ""), "left");
	$pagina->LinhaCampoFormulario("Imagem claro:", "right", "N", $pagina->CampoTexto("v_NOM_ARQUIVO_IMAGEM_CLARO", "N", "Nome de Rrquivo imagem claro", "30", "30", ""), "left");

	require_once 'include/PHP/class/class.perfil_acesso.php';
	$perfil_acesso = new perfil_acesso();
	$i = 0;
	$perfil_acesso->selectParam("2");
	while ($rowTipoUsuario = pg_fetch_array($perfil_acesso->database->result)){
		$aItemOption[$i][0] = $rowTipoUsuario["seq_perfil_acesso"];
		$aItemOption[$i][1] = "";
		$aItemOption[$i][2] = $rowTipoUsuario["nom_perfil_acesso"];
		$i++;
	}

	$pagina->LinhaCampoFormulario("Perfil:", "right", "N", $pagina->CampoCheckbox($aItemOption, "acesso[]"), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro
	$banco->setDSC_MENU_ACESSO($v_DSC_MENU_ACESSO);
	$banco->setSEQ_MENU_ACESSO_PAI($v_SEQ_MENU_ACESSO_PAI);
	$banco->setNOM_ARQUIVO($v_NOM_ARQUIVO);
	$banco->setNUM_PRIORIDADE($v_NUM_PRIORIDADE);
	$banco->setNOM_ARQUIVO_IMAGEM_ESCURO($v_NOM_ARQUIVO_IMAGEM_ESCURO);
	$banco->setNOM_ARQUIVO_IMAGEM_CLARO($v_NOM_ARQUIVO_IMAGEM_CLARO);
	$banco->insert();
	// C�digo inserido: $banco->SEQ_MENU_ACESSO
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		// Incluir o perfil de acesso
		require_once 'include/PHP/class/class.menu_perfil_acesso.php';
		$menu_perfil_acesso = new menu_perfil_acesso();
	    for ($i = 0; $i < count($acesso); $i++){
			$menu_perfil_acesso->setSEQ_MENU_ACESSO($banco->SEQ_MENU_ACESSO);
			$menu_perfil_acesso->setSEQ_PERFIL_ACESSO($acesso[$i]);
			$menu_perfil_acesso->insert();
	    }

		$pagina->redirectTo("MenuPesquisa.php?v_SEQ_MENU_ACESSO=$banco->SEQ_MENU_ACESSO");
	}
}
?>
