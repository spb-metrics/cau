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
require 'include/PHP/class/class.time_sheet.php';

if($flag == "1"){
//	if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
	/*TODO: NOVO PERFIL ACESSO*/
	if($pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"]) || $pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){	
	
		// Usuário pode ver tudo
	}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Lider de equipe pode ver todas da sua equipe
		if($v_SEQ_EQUIPE_TI != ""){
			$flag="";
		}
	}else{ // Colaborador ve somente o seu
		if($v_SEQ_EQUIPE_TI != ""){
			$flag="";
		}
		if($v_NUM_MATRICULA_RECURSO != ""){
			$flag="";
		}
	}
}

// ============================================================================================================
// Configurações AJAX
// ============================================================================================================
require 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function CarregarComboEquipe($v_COD_DEPENDENCIA){
	if($v_COD_DEPENDENCIA != ""){		
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.equipe_ti.php';
		$pagina = new Pagina();
		$equipe_ti = new equipe_ti();
		$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
		return $pagina->AjaxFormataArrayCombo($equipe_ti->combo("NOM_EQUIPE_TI"));
	}else{
		return "";
	}
}

function CarregarComboProfissional($v_SEQ_EQUIPE_TI){
	require_once 'include/PHP/class/class.pagina.php';
	require_once 'include/PHP/class/class.recurso_ti.php';
	$pagina = new Pagina();
	$recurso_ti = new recurso_ti();
	$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	return $pagina->AjaxFormataArrayF($recurso_ti->combo("NOME"));
}
$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboEquipe", "CarregarComboProfissional");
$Sajax->sajax_handle_client_request();

$pagina = new Pagina();
$banco = new time_sheet();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Faturamento por Perfil"); // Indica o título do cabeçalho da página

// Inicio do formulário
$pagina->MontaCabecalho();
if($flag == ""){
	// ============================================================================================================
	// Configurações AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		<?
		$Sajax->sajax_show_javascript();
		?>
		// Chamada
		function do_CarregarComboEquipe() {
			x_CarregarComboEquipe(document.form.v_COD_DEPENDENCIA.value, retorno_CarregarComboEquipe);
		}
		// Retorno
		function retorno_CarregarComboEquipe(val) {
			fEncheComboBox(val, document.form.v_SEQ_EQUIPE_TI);
		}
		// Chamada
		function do_CarregarComboProfissional() {
			x_CarregarComboProfissional(document.form.v_SEQ_EQUIPE_TI.value, retorno_CarregarComboProfissional);
		}
		// Retorno
		function retorno_CarregarComboProfissional(val) {
			if(val == ""){
				fEncheComboBoxPlus("", document.form.v_NUM_MATRICULA_RECURSO, "Profissionais - Selecione o cargo");
			}else{
				fEncheComboBox(val, document.form.v_NUM_MATRICULA_RECURSO);
			}
		}
		// ==================================================== FIM AJAX =====================================

		function fValidaFormLocal1(){
			
				document.form.action = 'RelatoriosFaturamentoPorPerfilPDF.php';
				document.form.target = '_blank';
				return true;		
		}
	</script>
	<style>
		#combo_profissional {
			width: 335px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
			margin-top:10px;
			margin-bottom:10px;
		}
		
	</style>
	<?
	print $pagina->CampoHidden("flag", "1");
	// Inicio da tabela de parâmetros
	$pagina->AbreTabelaPadrao("center", "100%");
	// Equipe
	//if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
	/*TODO: NOVO PERFIL ACESSO*/
	if($pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"]) || $pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){	
	
		require_once 'include/PHP/class/class.perfil_recurso_ti.php';
		$perfil_recurso_ti = new perfil_recurso_ti();
		$perfil_recurso_ti->selectParam('NOM_PERFIL_RECURSO_TI');
		$aItemOptionPerfilRecursoTi = $perfil_recurso_ti->combo("NOM_PERFIL_RECURSO_TI", $v_SEQ_PERFIL_RECURSO_TI);
		$vItemTodosPerfilRecursoTi = "S";

		if($v_SEQ_EQUIPE_TI != ""){
			require_once 'include/PHP/class/class.recurso_ti.php';
			$recurso_ti = new recurso_ti();
			$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
			$aItemOptionProfissional = $recurso_ti->combo("NOME", $v_NUM_MATRICULA_RECURSO);
			$vItemTodosProfissional = "S";
		}else{
			$aItemOptionProfissional = Array();
			$aItemOptionProfissional[] = array("", "", "Profissionais - Selecione o cargo");
			$vItemTodosProfissional = "N";
		}
	}else{ // Colaborador ve somente o seu
		
		require_once 'include/PHP/class/class.perfil_recurso_ti.php';
		$perfil_recurso_ti = new perfil_recurso_ti();
		$perfil_recurso_ti->setSEQ_PERFIL_RECURSO_TI($_SESSION["SEQ_PERFIL_RECURSO_TI"]);
		$aItemOptionPerfilRecursoTi = $perfil_recurso_ti->combo("NOM_PERFIL_RECURSO_TI", $_SESSION["SEQ_PERFIL_RECURSO_TI"]);
	
		require_once 'include/PHP/class/class.recurso_ti.php';
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
		$recurso_ti->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
		$aItemOptionProfissional = $recurso_ti->combo("NOME");
		$vItemTodosProfissional = "N";
	}

	$pagina->LinhaCampoFormulario("Executor:", "right", "N", $pagina->CampoSelect("v_SEQ_PERFIL_RECURSO_TI", "N", "Equipe", $vItemTodosPerfilRecursoTi, $aItemOptionPerfilRecursoTi, "Profissionais - Selecione o cargo", "do_CarregarComboProfissional()", "combo_profissional"), "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal1();", " Gerar Relotório "), "2");
	$pagina->FechaTabelaPadrao();
}

$pagina->MontaRodape();
?>