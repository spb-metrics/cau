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
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.destino_triagem.php';
require_once 'include/PHP/class/class.prioridade_chamado.php';
$destino_triagem = new destino_triagem();
$tipo_chamado = new tipo_chamado();
$subtipo_chamado = new subtipo_chamado();
$situacao_chamado = new situacao_chamado();
$pagina = new Pagina();
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
$banco = new chamado();
$empregados = new empregados();
$prioridade_chamado = new prioridade_chamado();

// ============================================================================================================
// Configura��es AJAX
// ============================================================================================================
require_once 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function CarregarComboTipoChamado($v_SEQ_TIPO_OCORRENCIA){
	if($v_SEQ_TIPO_OCORRENCIA != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.tipo_chamado.php';
		$pagina = new Pagina();
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		return $pagina->AjaxFormataArrayCombo($tipo_chamado->combo("DSC_TIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboSubtipoChamado($v_SEQ_TIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
	if($v_SEQ_TIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.subtipo_chamado.php';
		$pagina = new Pagina();
		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
		$subtipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		//$subtipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
		return $pagina->AjaxFormataArrayCombo($subtipo_chamado->combo("DSC_SUBTIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboAtividade($v_SEQ_SUBTIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
	if($v_SEQ_SUBTIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.atividade_chamado.php';
		$pagina = new Pagina();
		$atividade_chamado = new atividade_chamado();
		$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
		$atividade_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		//$atividade_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
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
$Sajax->sajax_export("CarregarComboSubtipoChamado", "CarregarComboAtividade", "CarregarComboEdificacao", "CarregarComboLocalFisico", "CarregarComboEquipe", "CarregarComboProfissional", "CarregarComboTipoChamado");
$Sajax->sajax_handle_client_request();

// Configura��o da p�g�na
$pagina->SettituloCabecalho("Pesquisa de Chamados"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configura��es AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	<?
	$Sajax->sajax_show_javascript();
	?>
	// Chamada
	function do_CarregarComboTipoChamado() {
		x_CarregarComboTipoChamado(document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboTipoChamado);
	}
	// Retorno
	function retorno_CarregarComboTipoChamado(val) {
		fEncheComboBox(val, document.form.v_SEQ_TIPO_CHAMADO);
		do_CarregarComboSubtipoChamado();
	}
	// Chamada
	function do_CarregarComboSubtipoChamado() {
		x_CarregarComboSubtipoChamado(document.form.v_SEQ_TIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboSubtipoChamado);
	}
	// Retorno
	function retorno_CarregarComboSubtipoChamado(val) {
		fEncheComboBox(val, document.form.v_SEQ_SUBTIPO_CHAMADO);
		do_CarregarComboAtividade();
	}
	// Chamada
	function do_CarregarComboAtividade() {
		x_CarregarComboAtividade(document.form.v_SEQ_SUBTIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboAtividade);
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
			fEncheComboBoxPlus("", document.form.v_NUM_MATRICULA_RECURSO, "Profissionais - Selecione a sua equipe");
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

	function fEnviarChamadosSelecionados(){

		var lRetorno ="";		
		var lAchou =false;		
		 
		 for (i=0;i<document.getElementsByName("chamados").length;i++){				
		      if(document.getElementsByName("chamados")[i].checked){
		    	  lAchou = true;		         
		    	  //alert(document.getElementsByName("chamados")[i].value);
		    	  if(lRetorno==""){
		    		  lRetorno = document.getElementsByName("chamados")[i].value;
			      }else{
			    	  lRetorno = lRetorno +"#_#"+document.getElementsByName("chamados")[i].value;
				 }
		    	  
		      } 
		 }
		 if(!lAchou){
			 alert('Nenhum chamado foi selecionado!');
			 return false; 
		 }else{
			 //alert(lRetorno);
			 //window.opener.document.<?=$vNomeFuncaoRetorno?>(lRetorno);
			 window.opener.<?=$vNomeFuncaoRetorno?>(lRetorno);
			 window.close();
			 return false; 
		 }
	}
</script>

<style>
		#combo_multiple {
			font-family: Verdana;
			width: 776px;
			size: 3;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_patrimonio {
			font-family: Verdana;
			width: 576px;
			size: 3;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_equipe {
			width: 270px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #F0F0F0;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_equipe_atribuicao {
			width: 345px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#combo_profissional {
			width: 344px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
		#CampoSelect {
			font-family: Verdana;
			width: 480px;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
	</style>
<?

// Mostrar ou n�o os par�metros
if($flag == ""){ // Mostrar par�metros
	$MaisParametros = "style=\"display: none;\" ";
	$MenosParametros = "";
	$tabelaParametros = "";
}else{ // N�o mostrar par�metros
	$MaisParametros = "style=\"display: none;\" ";
	$MenosParametros = "";
	$tabelaParametros = "style=\"display: none;\" ";
}


print $pagina->CampoHidden("vNomeFuncaoRetorno", $vNomeFuncaoRetorno);

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisParametros\" $MaisParametros cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/mais.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Exibir Filtros de Pesquisa</a>", "left","", "3%");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%", "id=\"MenosParametros\" $MenosParametros cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
$pagina->LinhaCampoFormulario("<a href=\"javascript: fExibirParametros();\"><img border=\"0\" src=\"imagens/menos.jpg\"></a>", "right", "N", "<a href=\"javascript: fExibirParametros();\">Esconder Filtros de Pesquisa</a>", "left","","3%");
$pagina->FechaTabelaPadrao();

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaParametros\" $tabelaParametros");

$pagina->LinhaCampoFormulario("N� do Chamado:", "right", "N", $pagina->CampoInt("v_SEQ_CHAMADO", "N", "Descri��o", "9", $v_SEQ_CHAMADO), "left", "id=".$pagina->GetIdTable(), "20%");

$pagina->LinhaCampoFormulario("Mat. solicitante:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_SOLICITANTE", "N", "Matr�cula do solicitante" , "10", "10", $v_NUM_MATRICULA_SOLICITANTE, "").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_SOLICITANTE")
								  , "left", "id=".$pagina->GetIdTable());

// Contato
$pagina->LinhaCampoFormulario("Mat. pessoa de contato:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_CONTATO", "N", "Matr�cula da pessoa de contato" , "10", "10", $v_NUM_MATRICULA_CONTATO, "onBlur=\"do_ValidarPessoaContato()\"").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_CONTATO")
								  , "left", "id=".$pagina->GetIdTable());

// Montar a combo da tabela situacao_chamado
require_once 'include/PHP/class/class.situacao_chamado.php';
$situacao_chamado = new situacao_chamado();
$pagina->LinhaCampoFormulario("Situa��o:", "right", "N", $pagina->CampoSelect("v_SEQ_SITUACAO_CHAMADO", "N", "Situacao chamado", "S", $situacao_chamado->combo(2, $v_SEQ_SITUACAO_CHAMADO)), "left", "id=".$pagina->GetIdTable());

$aItemOption = Array();
$aItemOption[] = array("1", $pagina->iif($v_SLA_ATENDIMENTO == "1","Selected", ""), "Em dia");
$aItemOption[] = array("0", $pagina->iif($v_SLA_ATENDIMENTO == "0","Selected", ""), "Risco de atraso");
$aItemOption[] = array("-1", $pagina->iif($v_SLA_ATENDIMENTO == "-1","Selected", ""), "Atrasado");

// Montar a combo da tabela tipo_chamado
require_once 'include/PHP/class/class.tipo_ocorrencia.php';
$tipo_ocorrencia = new tipo_ocorrencia();
//$tipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "N", $pagina->CampoSelect("v_SEQ_TIPO_OCORRENCIA", "N", "Classe", "S", $tipo_ocorrencia->combo(1, $v_SEQ_TIPO_OCORRENCIA), "Escolha", "do_CarregarComboTipoChamado()", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");

// Montar a combo da tabela tipo_chamado
require_once 'include/PHP/class/class.tipo_chamado.php';
$aItemOption = array();
$aItemOption[] = array("", "", "Selecione o tipo de chamado");
$tipo_chamado = new tipo_chamado();
//$tipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "N", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "N", "Classe", "N", $aItemOption, "Escolha", "do_CarregarComboSubtipoChamado()", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");

// Montar a combo da tabela subtipo_chamado
$aItemOption = Array();
$aItemOption[] = array("", "", "Selecione a classe de chamado");
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "N", $pagina->CampoSelect("v_SEQ_SUBTIPO_CHAMADO", "N", "Subclasse", "N", $aItemOption, "Escolha", "do_CarregarComboAtividade()", "CampoSelect"), "left", "id=".$pagina->GetIdTable());

// Montar a combo da tabela atividade
$aItemOption = Array();
$aItemOption[] = array("", "", "Selecione a subclasse");
$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", "right", "N", $pagina->CampoSelect("v_SEQ_ATIVIDADE_CHAMADO", "N", "Atividade", "N", $aItemOption, "Escolha", "do_BuscarAtribuicaoAutomatica()", "CampoSelect"), "left", "id=".$pagina->GetIdTable());

// Montar a combo da tabela prioridade
$prioridade_chamado = new prioridade_chamado();
$pagina->LinhaCampoFormulario("Prioridade:", "right", "N", $pagina->CampoSelect("v_SEQ_PRIORIDADE_CHAMADO", "N", "Prioridade", "S", $prioridade_chamado->combo("DSC_PRIORIDADE_CHAMADO")), "left", "id=".$pagina->GetIdTable());

// Desci��o do chamado
$pagina->LinhaCampoFormulario("Solicita��o:", "right", "N",
                              $pagina->CampoTextArea("v_TXT_CHAMADO", "N", "Solicita��o", "99", "1", $v_TXT_CHAMADO, "")
                              , "left", "id=".$pagina->GetIdTable());

// Localiza��o
//require 'include/PHP/class/class.dependencias.php';
//$dependencias = new dependencias();
//$aItemOptionEdificacao = Array();

//if($v_COD_DEPENDENCIA != ""){
	require_once 'include/PHP/class/class.edificacao.php';
	$edificacao = new edificacao();
//	$edificacao->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	$aItemOptionEdificacao = $edificacao->combo("NOM_EDIFICACAO", $v_SEQ_EDIFICACAO);
	$vItemTodosEdificacao = "S";
//}else{
//	$aItemOptionEdificacao = Array();
//	$aItemOptionEdificacao[] = array("", "", "Selecione a depend�ncia");
//	$vItemTodosEdificacao = "N";
//}

if($v_SEQ_EDIFICACAO != ""){
	require_once 'include/PHP/class/class.localizacao_fisica.php';
	$localizacao_fisica = new localizacao_fisica();
	$localizacao_fisica->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
	$aItemOptionLocal = $localizacao_fisica->combo("NOM_LOCALIZACAO_FISICA", $banco->SEQ_LOCALIZACAO_FISICA);
	$vItemTodosLocal = "S";
}else{
	$aItemOptionLocal = Array();
	$aItemOptionLocal[] = array("", "", "Selecione a edifica��o");
	$vItemTodosLocal = "N";
}

$pagina->LinhaCampoFormulario("Localiza��o:", "right", "N",
//				$pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Depend�ncia", "S", $dependencias->comboSimples(2, $v_COD_DEPENDENCIA), "Escolha", "do_CarregarComboEdificacao()")." ".
				$pagina->CampoSelect("v_SEQ_EDIFICACAO", "N", "Edifica��o", $vItemTodosEdificacao, $aItemOptionEdificacao, "Escolha", "do_CarregarComboLocalFisico()")." ".
				$pagina->CampoSelect("v_SEQ_LOCALIZACAO_FISICA", "N", "Localiza��o F�sica", $vItemTodosLocal, $aItemOptionLocal)
				, "left", "id=".$pagina->GetIdTable());

if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
    $pagina->LinhaCampoFormulario("N� do patrim�nio:", "right", "N", $pagina->CampoTexto("v_NUM_PATRIMONIO", "N", "" , "9", "9", $v_NUM_PATRIMONIO, ""), "left", "id='v_NUM_PATRIMONIO' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));
}

// Equipe
//$dependencias = new dependencias();

//if($v_COD_DEPENDENCIA_ATRIBUICAO != ""){
	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
//	$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA_ATRIBUICAO);
	$aItemOptionEquipe = $equipe_ti->combo("NOM_EQUIPE_TI");
	$vItemTodosEquipe = "S";
//}else{
//	$aItemOptionEquipe = Array();
//	$aItemOptionEquipe[] = array("", "", "Profissionais - Selecione a sua equipe");
//	$vItemTodosEquipe = "N";
//}

if($v_SEQ_EQUIPE_TI != ""){
	require_once 'include/PHP/class/class.recurso_ti.php';
	$recurso_ti = new recurso_ti();
	$recurso_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$aItemOptionProfissional = $recurso_ti->combo("NOME");
	$vItemTodosProfissional = "S";
}else{
	$aItemOptionProfissional = Array();
	$aItemOptionProfissional[] = array("", "", "Profissionais - Selecione a sua equipe");
	$vItemTodosProfissional = "N";
}

$pagina->LinhaCampoFormulario("Executor:", "right", "N",
//								  $pagina->CampoSelect("v_COD_DEPENDENCIA_ATRIBUICAO", "N", "Dependencia", "S", $dependencias->comboSimplesEquipe("DEP_SIGLA",$v_COD_DEPENDENCIA_ATRIBUICAO), "Escolha", "do_CarregarComboEquipe()")."&nbsp;".
								  $pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", $vItemTodosEquipe, $aItemOptionEquipe, "Escolha", "do_CarregarComboProfissional()", "combo_equipe").
								  $pagina->CampoSelect("v_NUM_MATRICULA_RECURSO", "N", "Profissional", $vItemTodosProfissional, $aItemOptionProfissional, "Escolha", "", "combo_profissional")
								  , "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Data de Abertura:", "right", "N",
			"de ".$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)
			." a ".$pagina->CampoData("v_DTH_ABERTURA_FINAL", "N", " de Abertura", $v_DTH_ABERTURA_FINAL)
			, "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("In�cio Efetivo:", "right", "N",
			"de ".$pagina->CampoData("v_DTH_INICIO_EFETIVO", "N", " de Inicio efetivo", "")
			." a ".$pagina->CampoData("v_DTH_INICIO_EFETIVO_FINAL", "N", " de Inicio efetivo", "")
			, "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Encerramento Efetivo:", "right", "N",
			"de ".$pagina->CampoData("v_DTH_ENCERRAMENTO_EFETIVO", "N", " de Encerramento efetivo", "")
			." a ".$pagina->CampoData("v_DTH_ENCERRAMENTO_EFETIVO_FINAL", "N", " de Encerramento efetivo", "")
			, "left", "id=".$pagina->GetIdTable());

/*
// Montar a combo da tabela item_configuracao
require_once 'include/PHP/class/class.item_configuracao.php';
$item_configuracao = new item_configuracao();
$pagina->LinhaCampoFormulario("Item configuracao:", "right", "N", $pagina->CampoSelect("v_SEQ_ITEM_CONFIGURACAO", "N", "Item configuracao", "S", $item_configuracao->combo(2, $v_SEQ_ITEM_CONFIGURACAO)), "left");
*/

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();

// Configura��o de triagem
//$vDestinoTriagem = $destino_triagem->BuscarDependenciasEquipe($_SESSION["SEQ_EQUIPE_TI"]);

// =======================================================================================================================
// Chamados sob minha responsabilidade
// =======================================================================================================================
$vMsgErro= "";

if( $flag == "1" && ($v_SEQ_CHAMADO==""|| $v_SEQ_CHAMADO == null)&&($v_NUM_MATRICULA_SOLICITANTE == "" || $v_NUM_MATRICULA_SOLICITANTE== null)&&
   ($v_NUM_MATRICULA_CONTATO == "" || $v_NUM_MATRICULA_CONTATO == null) && ($v_SEQ_SITUACAO_CHAMADO == "" || $v_SEQ_SITUACAO_CHAMADO == null) &&
   ($v_SEQ_TIPO_OCORRENCIA == "" || $v_SEQ_TIPO_OCORRENCIA == null) && ($v_SEQ_SUBTIPO_CHAMADO == "" || $v_SEQ_SUBTIPO_CHAMADO == null) &&
   ($v_SEQ_ATIVIDADE_CHAMADO == "" || $v_SEQ_ATIVIDADE_CHAMADO == null) && ($v_SEQ_PRIORIDADE_CHAMADO == "" || $v_SEQ_PRIORIDADE_CHAMADO == null)&&
   ($v_TXT_CHAMADO == "" || $v_TXT_CHAMADO == null) && ($v_SEQ_EDIFICACAO == "" || $v_SEQ_EDIFICACAO == null) &&
   ($v_SEQ_LOCALIZACAO_FISICA == "" || $v_SEQ_LOCALIZACAO_FISICA == null) && ($v_NUM_PATRIMONIO == "" || $v_NUM_PATRIMONIO == null) &&
   ($v_SEQ_EQUIPE_TI == "" || $v_SEQ_EQUIPE_TI == null) && ($v_NUM_MATRICULA_RECURSO == "" || $v_NUM_MATRICULA_RECURSO == null) &&
   ($$v_DTH_ABERTURA == "" || $$v_DTH_ABERTURA == null) && ($v_DTH_ABERTURA_FINAL == "" || $v_DTH_ABERTURA_FINAL == null)&&
   ($v_DTH_INICIO_EFETIVO == "" || $v_DTH_INICIO_EFETIVO == null) && ($v_DTH_INICIO_EFETIVO_FINAL == "" || $v_DTH_INICIO_EFETIVO_FINAL == null) &&
   ($v_DTH_ENCERRAMENTO_EFETIVO == "" || $v_DTH_ENCERRAMENTO_EFETIVO == null) && ($v_DTH_ENCERRAMENTO_EFETIVO_FINAL == "" || $v_DTH_ENCERRAMENTO_EFETIVO_FINAL == null)
  ){
  	
  	$vMsgErro = "Favor informar pelo menos uma condi��o de filtro.";
	
}

if($flag == "1" && $vMsgErro==""){
	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "5%");
	$header[] = array("Prioridade", "10%");
	$header[] = array("Chamado", "10%");
	$header[] = array("Atividade", "20%");
	$header[] = array("Solicita��o", "20%");
	$header[] = array("Situa��o", "15%");
	$header[] = array("Abertura", "10%");
	$header[] = array("Vencimento", "10%");
	//$header[] = array("SLA", "5%");

	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);

	$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$banco->setNUM_MATRICULA_SOLICITANTE($empregados->GetNumeroMatricula($v_NUM_MATRICULA_SOLICITANTE));
	$banco->setNUM_MATRICULA_CONTATO($empregados->GetNumeroMatricula($v_NUM_MATRICULA_CONTATO));
	$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
	//$banco->setCOD_SLA_ATENDIMENTO($v_COD_SLA_ATENDIMENTO);
	$banco->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
	$banco->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
	$banco->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
	$banco->setSEQ_ATIVIDADE_CHAMADO($v_SEQ_ATIVIDADE_CHAMADO);
	$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
	$banco->setTXT_CHAMADO($v_TXT_CHAMADO);

	// Localiza��o
	//$banco->setCOD_DEPENDENCIA_LOCALIZACAO($v_COD_DEPENDENCIA);
	$banco->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
	$banco->setSEQ_LOCALIZACAO_FISICA($v_SEQ_LOCALIZACAO_FISICA);

	// Atribui��o
	//$banco->setCOD_DEPENDENCIA_ATRIBUICAO($v_COD_DEPENDENCIA_ATRIBUICAO);
	$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco->setNUM_MATRICULA_EXECUTOR($v_NUM_MATRICULA_RECURSO);

	$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
	$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);

	//$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);

	$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
	$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);

	$banco->setDTH_INICIO_EFETIVO($v_DTH_INICIO_EFETIVO);
	$banco->setDTH_INICIO_EFETIVO_FINAL($v_DTH_INICIO_EFETIVO);

	$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
	$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);

	$banco->setNUM_PATRIMONIO((int)$v_NUM_PATRIMONIO);

	if($v_SEQ_EQUIPE_TI != "" || $v_COD_DEPENDENCIA_ATRIBUICAO != ""){
		$banco->AtenderChamados("DTH_ABERTURA_DATA", $vNumPagina, 10);
	}else{
		$banco->selectParam("DTH_ABERTURA_DATA", $vNumPagina, 10);
	}
	if($banco->database->rows > 0){
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Chamados encontrados para os par�metros informados", $header);
		$vLink = "?flag=1&v_SEQ_CHAMADO_PESQUISA=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL";
		
		while ($row = pg_fetch_array($banco->database->result)){
			$situacao_chamado = new situacao_chamado();
			$CheckBox="";
			
			if($row["seq_situacao_chamado"]!=$situacao_chamado->COD_Encerrada && 
			   $row["seq_situacao_chamado"]!=$situacao_chamado->COD_Cancelado){
			   	
					$CheckBox = "<INPUT type=CHECKBOX name=\"chamados\" id=\"chamados\" 
					VALUE=\"".$row["seq_chamado"]."<_>".$row["dsc_atividade_chamado"]."<_>"
					 .$pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"])
					."\"			
					/>";
					//.$row["txt_chamado"]."\"
			}else{
				$CheckBox="";
			}
			
			
			$corpo[] = array("right", "campo", $CheckBox);
			
			// Prioridade
			$prioridade_chamado = new $prioridade_chamado();
			$prioridade_chamado->select($row["seq_prioridade_chamado"]);
			$corpo[] = array("left", "campo", $prioridade_chamado->DSC_PRIORIDADE_CHAMADO);

			// Chamado
			$corpo[] = array("right", "campo", $row["seq_chamado"]);

			// Atividade
			$subtipo_chamado = new subtipo_chamado();
			$subtipo_chamado->select($row["seq_subtipo_chamado"]);
			$tipo_chamado = new tipo_chamado();
			$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
			$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);

			// Solicita��o
			$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

			// Situa��o
			
			$situacao_chamado->select($row["seq_situacao_chamado"]);
			$corpo[] = array("left", "campo", $situacao_chamado->DSC_SITUACAO_CHAMADO);

			// Abertura
			$corpo[] = array("center", "campo", $row["dth_abertura"]);

			// Recuperar dados do SLA
			$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
			$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

			// Vencimento
			$corpo[] = array("center", "campo", "<font color=".$pagina->CorSLA($v_COD_SLA_ATENDIMENTO).">".$v_DTH_ENCERRAMENTO_PREVISAO."</font>");

			// SLA
			//$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));

			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" ");
			$corpo = "";
		}
		$pagina->FechaTabelaPadrao();
		$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL&v_SEQ_TIPO_OCORRENCIA=$v_SEQ_TIPO_OCORRENCIA&vNomeFuncaoRetorno=$vNomeFuncaoRetorno");
		
		//$pagina->LinhaColspan("center", "&nbsp;", "2", "header");
		//$pagina->LinhaCampoFormularioColspan("left", $pagina->CampoButton("fEnviarChamadosSelecionados();", " Selecionar chamados ","button"), "2");
		$pagina->AbreTabelaPadrao("center", "100%", "id=\"MaisChamadosRDM\"  cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
		 
		$pagina->LinhaCampoFormularioColspan("left","<br><br>&nbsp;&nbsp;".$pagina->CampoButton("fEnviarChamadosSelecionados();", " Selecionar chamados ","button"), 2);
		$pagina->FechaTabelaPadrao();
		
	}else{
		$pagina->LinhaColspan("center", "Chamados encontrados para os par�metros informados", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}
}


if($vMsgErro!=""){
	$pagina->ScriptAlert($vMsgErro);
}

$pagina->MontaRodape();
?>
