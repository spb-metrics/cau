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
/*
if($flag == "1"){
	if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
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
*/
// ============================================================================================================
// Configurações AJAX
// ============================================================================================================
require 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();


function CarregarComboEquipe($v_COD_DEPENDENCIA){
	//if($v_COD_DEPENDENCIA != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.equipe_ti.php';
		$pagina = new Pagina();
		$equipe_ti = new equipe_ti();
	//	$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
		return $pagina->AjaxFormataArrayCombo($equipe_ti->combo("NOM_EQUIPE_TI"));
	//}else{
	//	return "";
	//}
}

function CarregarComboProfissional($v_SEQ_EQUIPE_TI){
	//if($v_SEQ_EQUIPE_TI != "" && $v_SEQ_EQUIPE_TI == $_SESSION["SEQ_EQUIPE_TI"]){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.recurso_ti.php';
		$pagina = new Pagina();
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
		return $pagina->AjaxFormataArrayCombo($recurso_ti->combo("NOME"));
	//}else{
	//	return "";
	//}
}

$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboEquipe", "CarregarComboProfissional");
$Sajax->sajax_handle_client_request();

$pagina = new Pagina();
$banco = new time_sheet();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Atividade"); // Indica o título do cabeçalho da página

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
			x_CarregarComboEquipe("", retorno_CarregarComboEquipe);
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
				fEncheComboBoxPlus("", document.form.v_NUM_MATRICULA_RECURSO, "Profissionais - Selecione a sua equipe");
			}else{
				fEncheComboBox(val, document.form.v_NUM_MATRICULA_RECURSO);
			}
		}
		// ==================================================== FIM AJAX =====================================
		function fValidaFormLocal(){

			if(document.form.V_NUM_MATRICULA_SOLICITANTE.value == ""){

				alert("Preencha o campo matrícula");
				document.form.V_NUM_MATRICULA_SOLICITANTE.focus();
				return false;
			}

			if(document.form.v_DTH_INICIO.value == ""){
				alert("Preencha o campo Data de Início");
				document.form.v_DTH_INICIO.focus();
				return false;
			}

			if(document.form.v_DTH_INICIO_FINAL.value == ""){
				alert("Preencha o campo Data Final");
				document.form.v_DTH_INICIO_FINAL.focus();
				return false;
			}
			if(!comparaDatas(document.form.v_DTH_INICIO, document.form.v_DTH_INICIO_FINAL)){
				alert("A data de início deve ser menor que a data final.");
			 	return false;
			}
			document.form.action = 'Relatorios_Atividade.php';
			document.form.target = '';
			return true;
		}

		function fValidaFormLocal1(){
			if(fValidaFormLocal()){
				document.form.action = 'Relatorios_AtividadePDF.php';
				document.form.target = '_blank';
				return true;
			}else{
				return false;
			}
		}
	</script>

	<?
	print $pagina->CampoHidden("flag", "1");

	// Inicio da tabela de parâmetros
	$pagina->AbreTabelaPadrao("center", "100%");

	// Equipe
	/*TODO: NOVO PERFIL ACESSO*/
	//if($_SESSION["SEQ_PERFIL_ACESSO"] == "4" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
	
		if($pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"]) || $pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){
		//require 'include/PHP/class/class.dependencias.php';
		//$dependencias = new dependencias();
		//$aItemOptionDependencia = $dependencias->comboSimplesEquipe("DEP_SIGLA",$v_COD_DEPENDENCIA);
		$v_OPCAO_TODOS_DEPENDENCIA = "S";

		//if($v_COD_DEPENDENCIA != ""){
			require_once 'include/PHP/class/class.equipe_ti.php';
			$equipe_ti = new equipe_ti();
		//	$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
			$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI", $v_SEQ_EQUIPE_TI);
			$vItemTodosEquipe = "S";
		//}else{
		//	$aItemOptionEquipe = Array();
			//$aItemOptionEquipe[] = array("", "", "Profissionais - Selecione a sua equipe");
		//	$vItemTodosEquipe = "N";
		//}

		if($v_SEQ_EQUIPE_TI != ""){
			require_once 'include/PHP/class/class.recurso_ti.php';
			$recurso_ti = new recurso_ti();
			$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
			$aItemOptionProfissional = $recurso_ti->combo("NOME", $v_NUM_MATRICULA_RECURSO);
			$vItemTodosProfissional = "S";
		}else{
			$aItemOptionProfissional = Array();
			//$aItemOptionProfissional[] = array("", "", "Profissionais - Selecione a sua equipe");
			$vItemTodosProfissional = "N";
		}
	}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Lider de equipe pode ver todas da sua equipe
		//require 'include/PHP/class/class.dependencias.php';
		//$dependencias = new dependencias();
		//$aItemOptionDependencia = $dependencias->comboSimplesEquipe("DEP_SIGLA",$_SESSION["COD_DEPENDENCIA"]);
		$v_OPCAO_TODOS_DEPENDENCIA = "N";

		require_once 'include/PHP/class/class.equipe_ti.php';
		$equipe_ti = new equipe_ti();
		//$equipe_ti->setCOD_DEPENDENCIA($_SESSION["COD_DEPENDENCIA"]);
		$equipe_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
		$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI", $pagina->iif($v_SEQ_EQUIPE_TI=="", $_SESSION["SEQ_EQUIPE_TI"], $v_SEQ_EQUIPE_TI));
		$vItemTodosEquipe = "N";

		require_once 'include/PHP/class/class.recurso_ti.php';
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
		$aItemOptionProfissional = $recurso_ti->combo("NOME", $v_NUM_MATRICULA_RECURSO);
		$vItemTodosProfissional = "S";
	}else{ // Colaborador ve somente o seu
		//require 'include/PHP/class/class.dependencias.php';
		//$dependencias = new dependencias();
		//$aItemOptionDependencia = $dependencias->comboSimplesEquipe("DEP_SIGLA",$_SESSION["COD_DEPENDENCIA"]);
		$v_OPCAO_TODOS_DEPENDENCIA = "N";

		require_once 'include/PHP/class/class.equipe_ti.php';
		$equipe_ti = new equipe_ti();
		//$equipe_ti->setCOD_DEPENDENCIA($_SESSION["COD_DEPENDENCIA"]);
		$equipe_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
		$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI", $_SESSION["SEQ_EQUIPE_TI"]);
		$vItemTodosEquipe = "N";

		require_once 'include/PHP/class/class.recurso_ti.php';
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
		$recurso_ti->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
		$aItemOptionProfissional = $recurso_ti->combo("NOME");
		$vItemTodosProfissional = "N";
	}

	$pagina->LinhaCampoFormulario("Matrícula:", "center", "S",
								  $pagina->CampoTexto("V_NUM_MATRICULA_SOLICITANTE", "N", "Matrícula do solicitante" , "19", "19", $V_NUM_MATRICULA_SOLICITANTE, "").
								  $pagina->ButtonProcuraRecursoTi("V_NUM_MATRICULA_SOLICITANTE") , "left", "id=".$pagina->GetIdTable());


	$pagina->LinhaCampoFormulario("Período:", "center", "S", "de ".$pagina->CampoData("v_DTH_INICIO", "N", " de Inicio", $v_DTH_INICIO)
								 ." a ".$pagina->CampoData("v_DTH_INICIO_FINAL", "N", " de Inicio", $v_DTH_INICIO_FINAL), "left");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal1();", " Rel. PDF "), "20");
	$pagina->FechaTabelaPadrao();

}

$pagina->MontaRodape();
?>