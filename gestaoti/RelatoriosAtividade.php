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
	return $pagina->AjaxFormataArrayCombo($recurso_ti->combo("NOME"));
}

$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboEquipe", "CarregarComboProfissional");
$Sajax->sajax_handle_client_request();

$pagina = new Pagina();
$banco 	= new time_sheet();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Atividade"); // Indica o título do cabeçalho da página

// Inicio do formulário
$pagina->MontaCabecalho();
	?>
	<script language="javascript">
		function fValidaFormLocal1(){
			//if(fValidaFormLocal()){
				document.form.action = 'RelatoriosAtividadePDF.php';
				document.form.target = '_blank';
				return true;
			//}else{
			//	return false;
			//}
		}
	</script>
<?
print $pagina->CampoHidden("flag", "1");

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%");
$pagina->LinhaCampoFormulario("Matrícula:", "center", "S", $pagina->CampoTexto("V_NUM_MATRICULA_SOLICITANTE", "N", "Matrícula do solicitante" , "19", "19", $v_NUM_MATRICULA_SOLICITANTE, ""). $pagina->ButtonProcuraRecursoTi("V_NUM_MATRICULA_SOLICITANTE") , "left", "id=".$pagina->GetIdTable());
$pagina->LinhaCampoFormulario("Período:", "center", "S", "de ".$pagina->CampoData("v_DTH_INICIO", "N", " de Inicio", $v_DTH_INICIO)." a ".$pagina->CampoData("v_DTH_INICIO_FINAL", "N", " de Inicio", $v_DTH_INICIO_FINAL), "left");
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal1();", " Gerar Relatório "), "20");
$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();
?>