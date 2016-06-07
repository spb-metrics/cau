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
require 'include/PHP/class/class.menu.php';
$pagina = new Pagina();
$banco = new menu();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Menu"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("MenuPesquisa.php", "", "Pesquisa"),
		 			    array("#", "tabact", "Adicionar") );
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
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
	// Código inserido: $banco->SEQ_MENU_ACESSO
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
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
