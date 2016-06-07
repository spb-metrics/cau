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
	if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI(5) e Administrador(2) pode ver tudo
		// Usuário pode ver tudo

	}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Lider de equipe pode ver todas da sua equipe
		if($v_SEQ_EQUIPE_TI == ""){
			$flag="";
		}
	}else{ // Colaborador ve somente o seu
		if($v_SEQ_EQUIPE_TI == ""){
			$flag="";
		}
		if($v_NUM_MATRICULA_RECURSO == ""){
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
//	if($v_COD_DEPENDENCIA != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.equipe_ti.php';
		$pagina = new Pagina();
		$equipe_ti = new equipe_ti();
//		$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
		return $pagina->AjaxFormataArrayCombo($equipe_ti->combo("NOM_EQUIPE_TI"));
//	}else{
//		return "";
//	}
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
$pagina->SettituloCabecalho("Chamados Por Profissional"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

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
		//x_CarregarComboEquipe(document.form.v_COD_DEPENDENCIA.value, retorno_CarregarComboEquipe);
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
			fEncheComboBoxPlus("", document.form.v_NUM_MATRICULA_RECURSO, "Selecione a sua equipe");
		}else{
			fEncheComboBox(val, document.form.v_NUM_MATRICULA_RECURSO);
		}
	}

	// ==================================================== FIM AJAX =====================================

	function fValidaFormLocal(){

	//	if( document.form.v_COD_DEPENDENCIA.value != ""){

			if( document.form.v_SEQ_EQUIPE_TI.value == ""){
				alert("Selecione a Equipe");
				return false;
			}

			if( document.form.v_NUM_MATRICULA_RECURSO.value == ""){
				alert("Selecione o Colaborador");
				return false;
			}
	//	}

		if(document.form.v_DTH_ABERTURA.value == ""){
			alert("Preencha o campo Data de Início");
			document.form.v_DTH_ABERTURA.focus();
			return false;
		}

		if(document.form.v_DTH_ENCERRAMENTO_EFETIVO_FINAL.value == ""){
			alert("Preencha o campo Data de Encerramento");
			document.form.v_DTH_ENCERRAMENTO_EFETIVO_FINAL.focus();
			return false;
		}
		if(!comparaDatas(document.form.v_DTH_ABERTURA, document.form.v_DTH_ENCERRAMENTO_EFETIVO_FINAL)){
			alert("A data de início deve ser menor que a data final.");
		 	return false;
		}

		document.form.action = 'RelatoriosChamadosAbertos.php';
		document.form.target = '';
		return true;
	}

	function fValidaFormLocal1(){
		if(fValidaFormLocal()){
			document.form.action = 'RelatoriosChamadosAbertosPDF.php';
			document.form.target = '_blank';
			return true;
		}else{
			return false;
		}
	}

</script>
<style>
	#combo_equipe {
		width: 305px;
		font-family: Verdana;
		font-size: 11px;
		color: #000000;
		border-color: #F0F0F0;
		border-style: double;
		border-width: 1px;
		background-color: #f6f5f5;
	}
	#combo_profissional {
		width: 325px;
		font-family: Verdana;
		font-size: 11px;
		color: #000000;
		border-color: #000000;
		border-style: double;
		border-width: 1px;
		background-color: #f6f5f5;
	}
</style>
<?
print $pagina->CampoHidden("flag", "1");

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%");

// Equipe
// ==== Gestor PRTI(5) ou Administrador(2) pode ver tudo

/*TODO: NOVO PERFIL ACESSO*/
//if($_SESSION["SEQ_PERFIL_ACESSO"] == "4" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){
	
if($pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"]) || $pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){
	
//	require 'include/PHP/class/class.dependencias.php';
//	$dependencias = new dependencias();
//	$aItemOptionDependencia = $dependencias->comboSimplesEquipe("DEP_SIGLA",$v_COD_DEPENDENCIA);
	$v_OPCAO_TODOS_DEPENDENCIA = "S";

//	if($v_COD_DEPENDENCIA != ""){
		require_once 'include/PHP/class/class.equipe_ti.php';
		$equipe_ti = new equipe_ti();
//		$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
		$equipe_ti->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
		$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI", $v_SEQ_EQUIPE_TI);
		$vItemTodosEquipe = "S";
//	}else{
//		$aItemOptionEquipe = Array();
//		$aItemOptionEquipe[] = array("", "", "Selecione a sua equipe");
//		$vItemTodosEquipe = "N";
//	}

	if($v_SEQ_EQUIPE_TI != ""){
		require_once 'include/PHP/class/class.recurso_ti.php';
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
		$aItemOptionProfissional = $recurso_ti->combo("NOME", $v_NUM_MATRICULA_RECURSO);
		$vItemTodosProfissional = "S";
	}else{
		$aItemOptionProfissional = Array();
		$aItemOptionProfissional[] = array("", "", "Selecione o Colaborador");
		$vItemTodosProfissional = "N";
	}
}elseif( ( $_SESSION["FLG_LIDER_EQUIPE"] == "S" && $_SESSION["FLG_PRIORIZADOR_EQUIPE"] == "S") || ( $_SESSION["FLG_SUBSTITUTO_EQUIPE"] == "S" && $_SESSION["FLG_PRIORIZADOR_EQUIPE"] == "S") ){
	//Importando a classe dependencias.
//	require 'include/PHP/class/class.dependencias.php';
//	$dependencias = new dependencias();
//	$aItemOptionDependencia = $dependencias->comboSimplesEquipe("DEP_SIGLA",$_SESSION["COD_DEPENDENCIA"]);
	$v_OPCAO_TODOS_DEPENDENCIA = "N";

	//Importando a classe equipe_ti.
	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$vItemTodosEquipe = "S";
	$aItemOptionEquipe2 = array();
//	$equipe_ti->setCOD_DEPENDENCIA($_SESSION["COD_DEPENDENCIA"]);
	$equipe_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$equipe_ti->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	$aItemOptionEquipe2[] = $equipe_ti->combo("NOM_EQUIPE_TI", $pagina->iif($v_SEQ_EQUIPE_TI=="", $_SESSION["SEQ_EQUIPE_TI"], $v_SEQ_EQUIPE_TI));

	//Priorizador
//	$equipe_ti->setCOD_DEPENDENCIA($_SESSION["CodDependencia"]);
	$equipe_ti->setSEQ_EQUIPE_TI($_SESSION["SeqEquipeTi"]);
	$aItemOptionEquipe2[] = $equipe_ti->combo("NOM_EQUIPE_TI", $pagina->iif($v_SEQ_EQUIPE_TI=="", $_SESSION['SeqEquipeTi'], $v_SEQ_EQUIPE_TI));
	$vItemTodosEquipe = "N";
	//Funde um ou mais arrays
	$aItemOptionEquipe = @array_merge($aItemOptionEquipe2[0], $aItemOptionEquipe2[1]);

	//Importando a classe recurso_ti.
	require_once 'include/PHP/class/class.recurso_ti.php';
	$recurso_ti = new recurso_ti();
	$aItemOptionProfissional2 = array();
	$recurso_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$aItemOptionProfissional2[] = $recurso_ti->combo("NOME", $v_NUM_MATRICULA_RECURSO);
	$vItemTodosProfissional = "N";

	//Priorizador
	$recurso_ti->setSEQ_EQUIPE_TI($_SESSION["SeqEquipeTi"]);
	$aItemOptionProfissional2[] = $recurso_ti->combo("NOME", $v_NUM_MATRICULA_RECURSO);
	$vItemTodosProfissional = "S";
	//Funde um ou mais arrays

	$aItemOptionProfissional = @array_merge(array_slice((array_unique( $aItemOptionProfissional2[0])), 1, -1), $aItemOptionProfissional2[1]);

}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S" || $_SESSION['FLG_SUBSTITUTO_EQUIPE'] == "S"){
//	require 'include/PHP/class/class.dependencias.php';
//	$dependencias = new dependencias();
//	$aItemOptionDependencia = $dependencias->comboSimplesEquipe("DEP_SIGLA",$_SESSION["COD_DEPENDENCIA"]);
	$v_OPCAO_TODOS_DEPENDENCIA = "N";

	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
//	$equipe_ti->setCOD_DEPENDENCIA($_SESSION["COD_DEPENDENCIA"]);
	$equipe_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$equipe_ti->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI", $pagina->iif($v_SEQ_EQUIPE_TI=="", $_SESSION["SEQ_EQUIPE_TI"], $v_SEQ_EQUIPE_TI));
	$vItemTodosEquipe = "N";

	require_once 'include/PHP/class/class.recurso_ti.php';
	$recurso_ti = new recurso_ti();
	$recurso_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$aItemOptionProfissional = $recurso_ti->combo("NOME", $v_NUM_MATRICULA_RECURSO);
	$vItemTodosProfissional = "S";

}elseif($_SESSION['FLG_PRIORIZADOR_EQUIPE'] == "S"){
//	require 'include/PHP/class/class.dependencias.php';
//	$dependencias = new dependencias();
//	$aItemOptionDependencia = $dependencias->comboSimplesEquipe("DEP_SIGLA",$_SESSION["CodDependencia"]);
	$v_OPCAO_TODOS_DEPENDENCIA = "N";

	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
//	$equipe_ti->setCOD_DEPENDENCIA($_SESSION["CodDependencia"]);
	$equipe_ti->setSEQ_EQUIPE_TI($_SESSION["SeqEquipeTi"]);
	$equipe_ti->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	 
	$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI", $pagina->iif($v_SEQ_EQUIPE_TI=="", $_SESSION["SeqEquipeTi"], $v_SEQ_EQUIPE_TI));
	$vItemTodosEquipe = "N";

	require_once 'include/PHP/class/class.recurso_ti.php';
	$recurso_ti = new recurso_ti();
	$recurso_ti->setSEQ_EQUIPE_TI($_SESSION["SeqEquipeTi"]);	
	$aItemOptionProfissional = $recurso_ti->combo("NOME", $v_NUM_MATRICULA_RECURSO);
	$vItemTodosProfissional = "S";

}else{ // Colaborador ve somente o seu
//	require 'include/PHP/class/class.dependencias.php';
//	$dependencias = new dependencias();
//	$aItemOptionDependencia = $dependencias->comboSimplesEquipe("DEP_SIGLA",$_SESSION["COD_DEPENDENCIA"]);
	$v_OPCAO_TODOS_DEPENDENCIA = "N";

	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
//	$equipe_ti->setCOD_DEPENDENCIA($_SESSION["COD_DEPENDENCIA"]);
	$equipe_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$equipe_ti->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI", $_SESSION["SEQ_EQUIPE_TI"]);
	$vItemTodosEquipe = "N";

	require_once 'include/PHP/class/class.recurso_ti.php';
	$recurso_ti = new recurso_ti();
	$recurso_ti->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
	$recurso_ti->setNUM_MATRICULA_RECURSO($_SESSION["NUM_MATRICULA_RECURSO"]);
	$aItemOptionProfissional = $recurso_ti->combo("NOME");
	$vItemTodosProfissional = "N";
}

$pagina->LinhaCampoFormulario("Executor:", "right", "N",
//								  $pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Dependencia", $v_OPCAO_TODOS_DEPENDENCIA, $aItemOptionDependencia, "Escolha", "do_CarregarComboEquipe()")."&nbsp;".
								  $pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", $vItemTodosEquipe, $aItemOptionEquipe, "Escolha", "do_CarregarComboProfissional()", "combo_equipe").
								  $pagina->CampoSelect("v_NUM_MATRICULA_RECURSO", "N", "Profissional", $vItemTodosProfissional, $aItemOptionProfissional, "Escolha", "", "combo_profissional")
								  , "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Período:", "right", "S",
			"de " .$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)
			." a ".$pagina->CampoData("v_DTH_ENCERRAMENTO_EFETIVO_FINAL", "N", " de Encerramento efetivo", $v_DTH_ENCERRAMENTO_EFETIVO_FINAL)
			, "left", "id=".$pagina->GetIdTable());


$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal1();", " Gerar Relatório "), "2");
$pagina->FechaTabelaPadrao();


$pagina->MontaRodape();
?>
