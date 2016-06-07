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
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Menu"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
				   array("MenuCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	require 'include/PHP/class/class.menu_perfil_acesso.php';
	$menu_perfil_acesso = new menu_perfil_acesso();
	$menu_perfil_acesso->delete($v_SEQ_MENU_ACESSO);
	$banco->delete($v_SEQ_MENU_ACESSO);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_MENU_ACESSO = "";
}
print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_MENU_ACESSO", "");
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Nome", "25%");
$header[] = array("Pai", "17%");
$header[] = array("Acesso", "17%");
$header[] = array("Prio.", "5%");
//$header[] = array("Img escuro", "10%");
//$header[] = array("Img claro", "10%");

$pagina->LinhaHeaderTabelaResultado("Menus do sistema", $header);
// Setar variáveis
$banco->selectParam("SEQ_MENU_ACESSO_PAI, NUM_PRIORIDADE");
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("MenuAlteracao.php?v_SEQ_MENU_ACESSO=".$row["seq_menu_acesso"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_MENU_ACESSO", $row["seq_menu_acesso"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["dsc_menu_acesso"]);

		if($row["seq_menu_acesso_pai"] != ""){
			$menu = new menu();
			$menu->select($row["seq_menu_acesso_pai"]);
			$corpo[] = array("left", "campo", $menu->DSC_MENU_ACESSO);
		}else{
			$corpo[] = array("left", "campo", "RAIZ");
		}

		require_once 'include/PHP/class/class.menu_perfil_acesso.php';
		$menu_perfil_acesso = new menu_perfil_acesso();
		$menu_perfil_acesso->setSEQ_MENU_ACESSO($row["seq_menu_acesso"]);
		$menu_perfil_acesso->selectParam();
		$vPerfis = "";
		$vcont = 1;
		while ($rowTipoUsuario = pg_fetch_array($menu_perfil_acesso->database->result)){
			require_once 'include/PHP/class/class.perfil_acesso.php';
			$perfil_acesso = new perfil_acesso();
			$perfil_acesso->select($rowTipoUsuario["seq_perfil_acesso"]);
			$vPerfis .= $perfil_acesso->NOM_PERFIL_ACESSO;
			if($menu_perfil_acesso->database->rows > $vcont){
				$vPerfis .= ", ";
			}
			$vcont++;
		}
		$corpo[] = array("left", "campo", $vPerfis);
//		$corpo[] = array("left", "campo", $row["NOM_ARQUIVO"]);
		$corpo[] = array("left", "campo", $row["num_prioridade"]);
//		$corpo[] = array("left", "campo", $row["NOM_ARQUIVO_IMAGEM_ESCURO"]);
//		$corpo[] = array("left", "campo", $row["NOM_ARQUIVO_IMAGEM_CLARO"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>
