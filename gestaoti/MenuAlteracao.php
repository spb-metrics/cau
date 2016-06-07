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
	$pagina->SettituloCabecalho("Altera��o de Menu"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("MenuPesquisa.php", "", "Pesquisa"),
						array("MenuCadastro.php", "", "Adicionar"),
		 			    array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_MENU_ACESSO);

	// Inicio do formul�rio
	$pagina->MontaCabecalho();

	$pagina->AbreTabelaPadrao("center", "85%");

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_MENU_ACESSO", $v_SEQ_MENU_ACESSO);

	$menu = new menu();
	$i = 0;
	$menu->selectParam("2");
	while ($rowMenuPai = pg_fetch_array($menu->database->result)){
		$aItemOptionMenuPai[$i][0] = $rowMenuPai["seq_menu_acesso"];
		$aItemOptionMenuPai[$i][1] = $pagina->iif($rowMenuPai["seq_menu_acesso"] == $banco->SEQ_MENU_ACESSO_PAI,"Selected","");
		$aItemOptionMenuPai[$i][2] = $rowMenuPai["dsc_menu_acesso"];
		$i++;
	}
	$pagina->LinhaCampoFormulario("Descri��o:", "right", "S", $pagina->CampoTexto("v_DSC_MENU_ACESSO", "S", "Descri��o de Menu", "60", "60", "$banco->DSC_MENU_ACESSO"), "left");
	$pagina->LinhaCampoFormulario("Menu Pai:", "right", "N", $pagina->CampoSelect("v_SEQ_MENU_ACESSO_PAI", "N", "Escolha", "S", $aItemOptionMenuPai), "left");
	$pagina->LinhaCampoFormulario("Arquivo:", "right", "S", $pagina->CampoTexto("v_NOM_ARQUIVO", "S", "Nome de Arquivo", "60", "60", "$banco->NOM_ARQUIVO"), "left");
	$pagina->LinhaCampoFormulario("Prioridade:", "right", "S", $pagina->CampoTexto("v_NUM_PRIORIDADE", "S", "N�mero de Prioridade", "2)", "2)", "$banco->NUM_PRIORIDADE"), "left");
	$pagina->LinhaCampoFormulario("Imagem escuro:", "right", "N", $pagina->CampoTexto("v_NOM_ARQUIVO_IMAGEM_ESCURO", "N", "Nome de Arquivo imagem escuro", "30", "30", "$banco->NOM_ARQUIVO_IMAGEM_ESCURO"), "left");
	$pagina->LinhaCampoFormulario("Imagem claro:", "right", "N", $pagina->CampoTexto("v_NOM_ARQUIVO_IMAGEM_CLARO", "N", "Nome de Arquivo imagem claro", "30", "30", "$banco->NOM_ARQUIVO_IMAGEM_CLARO"), "left");

	require_once 'include/PHP/class/class.perfil_acesso.php';
	$perfil_acesso = new perfil_acesso();
	$i = 0;
	$perfil_acesso->selectParam("2");
	while ($rowTipoUsuario = pg_fetch_array($perfil_acesso->database->result)){
		$aItemOption[$i][0] = $rowTipoUsuario["seq_perfil_acesso"];
		require_once 'include/PHP/class/class.menu_perfil_acesso.php';
		$menu_perfil_acesso = new menu_perfil_acesso();
		$menu_perfil_acesso->setSEQ_MENU_ACESSO($banco->SEQ_MENU_ACESSO);
		$menu_perfil_acesso->setSEQ_PERFIL_ACESSO($rowTipoUsuario["seq_perfil_acesso"]);
		$menu_perfil_acesso->selectParam();
		if($menu_perfil_acesso->database->rows == 0){
			$aItemOption[$i][1] = "";
		}else{
			$aItemOption[$i][1] = "checked";
		}
		$aItemOption[$i][2] = $rowTipoUsuario["nom_perfil_acesso"];
		$i++;
	}

	$pagina->LinhaCampoFormulario("Perfil:", "right", "N", $pagina->CampoCheckbox($aItemOption, "acesso[]"), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Alterar regstro
	$banco->setSEQ_MENU_ACESSO_PAI($v_SEQ_MENU_ACESSO_PAI);
	$banco->setDSC_MENU_ACESSO($v_DSC_MENU_ACESSO);
	$banco->setNOM_ARQUIVO($v_NOM_ARQUIVO);
	$banco->setNUM_PRIORIDADE($v_NUM_PRIORIDADE);
	$banco->setNOM_ARQUIVO_IMAGEM_ESCURO($v_NOM_ARQUIVO_IMAGEM_ESCURO);
	$banco->setNOM_ARQUIVO_IMAGEM_CLARO($v_NOM_ARQUIVO_IMAGEM_CLARO);
	$banco->update($v_SEQ_MENU_ACESSO);
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		// Incluir o perfil de acesso
		require_once 'include/PHP/class/class.menu_perfil_acesso.php';
		$menu_perfil_acesso = new menu_perfil_acesso();
		$menu_perfil_acesso->delete($v_SEQ_MENU_ACESSO);
	    for ($i = 0; $i < count($acesso); $i++){
			$menu_perfil_acesso->setSEQ_MENU_ACESSO($v_SEQ_MENU_ACESSO);
			$menu_perfil_acesso->setSEQ_PERFIL_ACESSO($acesso[$i]);
			$menu_perfil_acesso->insert();
	    }

		$pagina->redirectTo("MenuPesquisa.php?v_SEQ_MENU_ACESSO=$banco->SEQ_MENU_ACESSO");
		$pagina->redirectTo("MenuPesquisa.php?v_SEQ_MENU_ACESSO=$v_SEQ_MENU_ACESSO");
	}
}
?>
