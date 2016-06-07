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
require 'include/PHP/class/class.chamado.php';
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.destino_triagem.php';
require 'include/PHP/class/class.prioridade_chamado.php';

$destino_triagem 	= new destino_triagem();
$pagina 			= new Pagina();
$banco 				= new chamado();

// ============================================================================================================
// Configurações AJAX
// ============================================================================================================
require 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function CarregarComboSubtipoChamado($v_SEQ_TIPO_CHAMADO){
	if($v_SEQ_TIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.subtipo_chamado.php';
		$pagina = new Pagina();
		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
		return $pagina->AjaxFormataArrayCombo($subtipo_chamado->combo("DSC_SUBTIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboAtividade($v_SEQ_SUBTIPO_CHAMADO){
	if($v_SEQ_SUBTIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.atividade_chamado.php';
		$pagina = new Pagina();
		$atividade_chamado = new atividade_chamado();
		$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
		return $pagina->AjaxFormataArrayCombo($atividade_chamado->combo("DSC_ATIVIDADE_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboEdificacao($v_COD_DEPENDENCIA){
	require_once 'include/PHP/class/class.pagina.php';
	require_once 'include/PHP/class/class.edificacao.php';
	$pagina = new Pagina();
	$edificacao = new edificacao();
	$edificacao->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	return $pagina->AjaxFormataArrayCombo($edificacao->comboSimples("NOM_EDIFICACAO"));
}

function CarregarComboLocalFisico($v_SEQ_EDIFICACAO){
	if($v_SEQ_EDIFICACAO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.localizacao_fisica.php';
		$pagina = new Pagina();
		$localizacao_fisica = new localizacao_fisica();
		$localizacao_fisica->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
		return $pagina->AjaxFormataArrayCombo($localizacao_fisica->combo("NOM_LOCALIZACAO_FISICA"));
	}else{
		return "";
	}
}

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
	if($v_SEQ_EQUIPE_TI != "" && $v_SEQ_EQUIPE_TI == $_SESSION["SEQ_EQUIPE_TI"]){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.recurso_ti.php';
		$pagina = new Pagina();
		$recurso_ti = new recurso_ti();
		$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
		return $pagina->AjaxFormataArrayCombo($recurso_ti->combo("NOME"));
	}else{
		return "";
	}
}
$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboSubtipoChamado", "CarregarComboAtividade", "CarregarComboEdificacao", "CarregarComboLocalFisico", "CarregarComboEquipe", "CarregarComboProfissional");
$Sajax->sajax_handle_client_request();

// Configuração da págína
$pagina->SettituloCabecalho("TIST-1"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	<?
	$Sajax->sajax_show_javascript();
	?>
	// Chamada
	function do_CarregarComboSubtipoChamado() {
		x_CarregarComboSubtipoChamado(document.form.v_SEQ_TIPO_CHAMADO.value, retorno_CarregarComboSubtipoChamado);
	}
	// Retorno
	function retorno_CarregarComboSubtipoChamado(val) {
		fEncheComboBox(val, document.form.v_SEQ_SUBTIPO_CHAMADO);
		do_CarregarComboAtividade();
	}
	// Chamada
	function do_CarregarComboAtividade() {
		x_CarregarComboAtividade(document.form.v_SEQ_SUBTIPO_CHAMADO.value, retorno_CarregarComboAtividade);
	}
	// Retorno
	function retorno_CarregarComboAtividade(val) {
		fEncheComboBox(val, document.form.v_SEQ_ATIVIDADE_CHAMADO);
	}
	// Chamada
	function do_CarregarComboEdificacao() {
		x_CarregarComboEdificacao(document.form.v_COD_DEPENDENCIA.value, retorno_CarregarComboEdificacao);
	}
	// Retorno
	function retorno_CarregarComboEdificacao(val) {
		fEncheComboBox(val, document.form.v_SEQ_EDIFICACAO);
	}
	// Chamada
	function do_CarregarComboLocalFisico() {
		x_CarregarComboLocalFisico(document.form.v_SEQ_EDIFICACAO.value, retorno_CarregarComboLocalFisico);
	}
	// Retorno
	function retorno_CarregarComboLocalFisico(val) {
		fEncheComboBox(val, document.form.v_SEQ_LOCALIZACAO_FISICA);
	}
	// Chamada
	function do_CarregarComboEquipe() {
		x_CarregarComboEquipe(document.form.v_COD_DEPENDENCIA_ATRIBUICAO.value, retorno_CarregarComboEquipe);
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

	function fExibirParametros(){
		if(document.getElementById("tabelaParametros").style.display == "none"){
			document.getElementById("tabelaParametros").style.display = "block";
			document.getElementById("MaisParametros").style.display = "none";
			document.getElementById("MenosParametros").style.display = "block";
		}else{
			document.getElementById("tabelaParametros").style.display = "none";
			document.getElementById("MaisParametros").style.display = "block";
			document.getElementById("MenosParametros").style.display = "none";
		}
	}
	
	function fValidaFormLocal(){			
			
		if(document.form.v_DTH_ATRIBUICAO.value == ""){
			alert("Preencha o campo Data de Início");
			document.form.v_DTH_ATRIBUICAO.focus();
			return false;
		}			
			
		if(document.form.v_DTH_ENCERRAMENTO_EFETIVO.value == ""){
			alert("Preencha o campo Data de Encerramento");
			document.form.v_DTH_ENCERRAMENTO_EFETIVO.focus();
			return false;
		}
		if(!comparaDatas(document.form.v_DTH_ATRIBUICAO, document.form.v_DTH_ENCERRAMENTO_EFETIVO)){
			alert("A data de início deve ser menor que a data final.");
		 	return false;
		}
		document.form.action = 'RelatoriosOrdensDeServicoConcluidosTist1.php';
		document.form.target = '';
		return true;
	}

	function fValidaFormLocal1(){
		if(fValidaFormLocal()){
			document.form.action = 'RelatoriosOrdensDeServicoConcluidosTist1PDF.php';
			document.form.target = '_blank';
			return true;
		}else{
			return false;
		}
	}
	
	
</script>
<?

// Mostrar ou não os parâmetros
if($flag == ""){ // Mostrar parâmetros
	$MaisParametros = "style=\"display: none;\" ";
	$MenosParametros = "";
	$tabelaParametros = "";
}else{ // Não mostrar parâmetros
	$MaisParametros = "";
	$MenosParametros = "style=\"display: none;\" ";
	$tabelaParametros = "style=\"display: none;\" ";
}
$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisParametros\" $MaisParametros cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/mais.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Exibir Filtros de Pesquisa</a>", "left","", "3%");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosParametros\" $MenosParametros cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");

$pagina->FechaTabelaPadrao();

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaParametros\" $tabelaParametros");

// Montar a combo da tabela situacao_chamado
require_once 'include/PHP/class/class.situacao_chamado.php';
$situacao_chamado = new situacao_chamado();

// Montar a combo da tabela tipo_chamado
require_once 'include/PHP/class/class.tipo_chamado.php';
$tipo_chamado = new tipo_chamado();

$tipo_chamado->setSEQ_TIPO_CHAMADO_NAO_EXIBIR($tipo_chamado->COD_TIPO_IMPROCEDENTE);

// Montar a combo da tabela atividade
if($v_SEQ_SUBTIPO_CHAMADO != ""){
	require 'include/PHP/class/class.atividade_chamado.php';
	$atividade_chamado = new atividade_chamado();
	$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($banco->SEQ_SUBTIPO_CHAMADO);
	$aItemOption = $atividade_chamado->combo("DSC_ATIVIDADE_CHAMADO", $v_SEQ_SUBTIPO_CHAMADO);
	$vItemTodosAtividade = "S";
}else{
	$aItemOption = Array();
	$aItemOption[] = array("", "", "Selecione a Atividade");
	$vItemTodosAtividade = "N";
}

// Equipe
require 'include/PHP/class/class.dependencias.php';
$dependencias = new dependencias();

if($v_COD_DEPENDENCIA_ATRIBUICAO != ""){
	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA_ATRIBUICAO);
	$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI");
	$vItemTodosEquipe = "S";
}else{
	$aItemOptionEquipe = Array();
	$aItemOptionEquipe[] = array("", "", "Selecione a sua equipe");
	$vItemTodosEquipe = "N";
}

if($v_SEQ_EQUIPE_TI != ""){
	require_once 'include/PHP/class/class.recurso_ti.php';
	$recurso_ti = new recurso_ti();
	$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$aItemOptionProfissional = $recurso_ti->combo("NOME");
	$vItemTodosProfissional = "S";
}else{
	$aItemOptionProfissional = Array();
	$aItemOptionProfissional[] = array("", "", "Selecione a sua equipe");
	$vItemTodosProfissional = "N";
}

$aItemOption = Array();
$aItemOption[] = array("1", $pagina->iif($v_SLA_ATENDIMENTO == "1","Selected", ""), "Em dia");
$aItemOption[] = array("0", $pagina->iif($v_SLA_ATENDIMENTO == "0","Selected", ""), "Risco de atraso");
$aItemOption[] = array("-1", $pagina->iif($v_SLA_ATENDIMENTO == "-1","Selected", ""), "Atrasado");

$pagina->LinhaCampoFormulario("Período:", "right", "S", "de " .
$pagina->CampoData("v_DTH_ATRIBUICAO", "N", " de Abertura", $v_DTH_ATRIBUICAO)." a ".
$pagina->CampoData("v_DTH_ENCERRAMENTO_EFETIVO", "N", " de Encerramento efetivo", $v_DTH_ENCERRAMENTO_EFETIVO), "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal1();", " Gerar Relatório "), "2");
$pagina->FechaTabelaPadrao();

// Configuração de triagem
$vDestinoTriagem = $destino_triagem->BuscarDependenciasEquipe($_SESSION["SEQ_EQUIPE_TI"]);

// =======================================================================================================================
// Chamados sob minha responsabilidade
// =======================================================================================================================

$pagina->MontaRodape();
?>
