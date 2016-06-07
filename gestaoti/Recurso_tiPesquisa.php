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
require 'include/PHP/class/class.recurso_ti.php';
require 'include/PHP/class/class.area_atuacao.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.perfil_recurso_ti.php';
require_once 'include/PHP/class/class.perfil_acesso.php';
require_once 'include/PHP/class/class.equipe_ti.php';
$pagina = new Pagina();
$banco = new recurso_ti();
$equipe_ti = new equipe_ti();

// ============================================================================================================
// Configura��es AJAX
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

$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboEquipe");
$Sajax->sajax_handle_client_request();

// Configura��o da p�g�na
$pagina->SettituloCabecalho("Pesquisa Colaboradores"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$pagina->ForcaAutenticacao();
if($pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){
	$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
					   array("Recurso_tiCadastro.php", "", "Adicionar") );
}else{
	$aItemAba = Array( array("#", "tabact", "Pesquisa"));
}
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

// ============================================================================================================
// Configura��es AJAX JAVASCRIPTS
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
</script>
<style>
		#combo_equipe {
			width: 350px;
			font-family: Verdana;
			font-size: 11px;
			color: #000000;
			border-color: #F0F0F0;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
		}
	</style>
<?
// Deletar registro
if($flag == "2"){
	// Verificar se existem tarefas
	require_once 'include/PHP/class/class.tarefa_ti.php';
	$tarefa_ti = new tarefa_ti();
	$tarefa_ti->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
	if($tarefa_ti->database->rows == 0){
		// Deletar aloca��o para sistemas de informa��o
		require_once 'include/PHP/class/class.equipe_envolvida.php';
		$equipe_envolvida = new equipe_envolvida();
		$equipe_envolvida->deleteAlocacao($v_NUM_MATRICULA_RECURSO);

		// Verificar se est� alocado para Servidores
		require_once 'include/PHP/class/class.equipe_servidor.php';
		$equipe_servidor = new equipe_servidor();
		$equipe_servidor->deleteAlocacao($v_NUM_MATRICULA_RECURSO);
		
		// deletar perfis associados
		require_once 'include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
		$recurso_ti_x_perfil_acesso = new recurso_ti_x_perfil_acesso();
		$recurso_ti_x_perfil_acesso->deleteByNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);

		// Deletar colaborador
		$banco->delete($v_NUM_MATRICULA_RECURSO);
		$pagina->ScriptAlert("Registro Exclu�do");
		$v_NUM_MATRICULA_RECURSO = "";
	}else{
		$pagina->ScriptAlert("O colaborador n�o pode ser exclu�do por existirem tarefas vinculadas.");
	}
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", "");

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

//$pagina->LinhaCampoFormulario(" de Numero matricula:", "right", "N", $pagina->CampoTexto("v_NUM_MATRICULA_RECURSO", "N", " de Numero matricula", "9)", "9)", ""), "left");


// Buscar dados da tabela externa
$perfil_recurso_ti = new perfil_recurso_ti();
$aItemOption = Array();

$perfil_recurso_ti->selectParam(2);
$cont = 0;
while ($row = pg_fetch_array($perfil_recurso_ti->database->result)){
	$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_PERFIL_RECURSO_TI == $row[0],"Selected", ""), $row[1]);
	$cont++;
}
// Adicionar combo no formul�rio
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOME", "N", "Nome", "60", "60", $v_NOME), "left");
$pagina->LinhaCampoFormulario("Perfil Profissional:", "right", "N", $pagina->CampoSelect("v_SEQ_PERFIL_RECURSO_TI", "N", "", "S", $aItemOption), "left");

// Equipe
//require 'include/PHP/class/class.dependencias.php';
//$dependencias = new dependencias();

//if($v_COD_DEPENDENCIA != ""){
	require_once 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
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
}
/*
$pagina->LinhaCampoFormulario("Depend�ncia/Equipe:", "right", "N",
							//	  $pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Dependencia", "S", $dependencias->comboSimplesEquipe("DEP_SIGLA",$v_COD_DEPENDENCIA), "Escolha", "do_CarregarComboEquipe()")."&nbsp;".
								  $pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", $vItemTodosEquipe, $aItemOptionEquipe, "Escolha", "", "combo_equipe")
								  , "left", "id=".$pagina->GetIdTable());

$pagina->LinhaCampoFormulario("Lota��o:", "right", "N",
								  $pagina->CampoTexto("v_UOR_SIGLA", "N", "Lota��o", "10", "10", $v_UOR_SIGLA, "").
								  $pagina->ButtonProcuraUorg("v_UOR_SIGLA", "")
								  , "left");
*/
// Montar a combo

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();

//if($flag == "1"){
//	$pagina->LinhaVazia(1);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Nome", "25%");
	$header[] = array("Perfil", "20%");
	$header[] = array("Lota��o", "10%");
	$header[] = array("Equipe", "20%");
	$header[] = array("Ramal", "10%");
	$header[] = array("Acesso", "10%");

	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setNOME($v_NOME);
	$banco->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
	$banco->setSEQ_PERFIL_RECURSO_TI($v_SEQ_PERFIL_RECURSO_TI);
	$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
	//$banco->setUOR_SIGLA($v_UOR_SIGLA);
	//if($v_COD_DEPENDENCIA != ""){
	//	$banco->setDEP_SIGLA($dependencias->GetSiglaDependencia($v_COD_DEPENDENCIA));
	//}
	$banco->selectParam("2", $vNumPagina);
	
	$SQL_EXPORT = $banco->SQL_EXPORT;
	$Rows = $banco->database->rows;

	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Recursos  encontrados para os par�mentos pesquisados", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			// Buscando o nome do colaborador
			$empregados = new empregados();
			$corpo[] = array("left", "campo", $row["nome"]);
			$corpo[] = array("left", "campo", $row["nom_perfil_recurso_ti"]);
			$corpo[] = array("center", "campo", $row["uor_sigla"]);
			$corpo[] = array("left", "campo", $row["nom_equipe_ti"]);
			$corpo[] = array("center", "campo", $row["num_voip"]);
			$corpo[] = array("left", "campo", $row["nom_perfil_acesso"]);
			$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"location.href='Recurso_tiDetalhes.php?v_NUM_MATRICULA_RECURSO=".$row["num_matricula_recurso"]."';\"");
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	//if($Rows > 0){		
	  $pagina->fMontarExportacao("Recurso_tiPesquisaDecorator.php",$SQL_EXPORT,"PROFISSIONAIS_TI") ;	
	//}
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_SEQ_PERFIL_RECURSO_TI=$v_SEQ_PERFIL_RECURSO_TI&v_COD_UOR=$v_COD_UOR&v_FLG_LIDER=$v_FLG_LIDER&v_UOR_SIGLA=$v_UOR_SIGLA&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA");
//}

$pagina->MontaRodape();
?>
