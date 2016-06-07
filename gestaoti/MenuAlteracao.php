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
	$pagina->SettituloCabecalho("Alteração de Menu"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("MenuPesquisa.php", "", "Pesquisa"),
						array("MenuCadastro.php", "", "Adicionar"),
		 			    array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_MENU_ACESSO);

	// Inicio do formulário
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
	$pagina->LinhaCampoFormulario("Descrição:", "right", "S", $pagina->CampoTexto("v_DSC_MENU_ACESSO", "S", "Descrição de Menu", "60", "60", "$banco->DSC_MENU_ACESSO"), "left");
	$pagina->LinhaCampoFormulario("Menu Pai:", "right", "N", $pagina->CampoSelect("v_SEQ_MENU_ACESSO_PAI", "N", "Escolha", "S", $aItemOptionMenuPai), "left");
	$pagina->LinhaCampoFormulario("Arquivo:", "right", "S", $pagina->CampoTexto("v_NOM_ARQUIVO", "S", "Nome de Arquivo", "60", "60", "$banco->NOM_ARQUIVO"), "left");
	$pagina->LinhaCampoFormulario("Prioridade:", "right", "S", $pagina->CampoTexto("v_NUM_PRIORIDADE", "S", "Número de Prioridade", "2)", "2)", "$banco->NUM_PRIORIDADE"), "left");
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
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
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
