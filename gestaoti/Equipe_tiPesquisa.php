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
require 'include/PHP/class/class.equipe_ti.php';
require 'include/PHP/class/class.empregados.oracle.php';
$empregados = new empregados();
$pagina = new Pagina();
$banco = new equipe_ti();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Equipes de Atendimento"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("Equipe_tiPesquisa.php", "tabact", "Pesquisa"),
				   array("Equipe_tiCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();

//$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_EQUIPE_TI);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_EQUIPE_TI = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_EQUIPE_TI", "");

/* Inicio da tabela de par�metros */
$pagina->AbreTabelaPadrao("center", "85%");

// Montar a combo
require 'include/PHP/class/class.central_atendimento.php';
$central_atendimento = new central_atendimento();
 
$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "N", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "N", "Central de Atendimento", "S", $central_atendimento->combo(2)), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");

	
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_EQUIPE_TI", "N", "Nome", "60", "60", ""), "left");
$pagina->LinhaCampoFormulario("Matricula do L�der:", "right", "N",
							  $pagina->CampoTexto("v_NUM_MATRICULA_LIDER", "N", "Matr�cula do L�der" , "11", "11", $banco->NUM_MATRICULA_LIDER, "").
							  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_LIDER", "TI")
							  , "left");

$pagina->LinhaCampoFormulario("Matricula do Substituto:", "right", "N",
							  $pagina->CampoTexto("v_NUM_MATRICULA_SUBSTITUTO", "N", "Matr�cula do Substituto" , "11", "11", $banco->NUM_MATRICULA_SUBSTITUTO, "").
							  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_SUBSTITUTO", "TI")
							  , "left");

$pagina->LinhaCampoFormulario("Matricula do Priorizador:", "right", "N",
							  $pagina->CampoTexto("v_NUM_MATRICULA_PRIORIZADOR", "N", "Matr�cula do Priorizador" , "11", "11", $banco->NUM_MATRICULA_PRIORIZADOR, "").
							  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_PRIORIZADOR", "")
							  , "left");

// Montar a combo
//require 'include/PHP/class/class.dependencias.php';
//$dependencias = new dependencias();
//$pagina->LinhaCampoFormulario("Depend�ncia:", "right", "N", $pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Depend�ncia", "S", $dependencias->combo(2, $banco->COD_DEPENDENCIA)), "left", "v_COD_DEPENDENCIA", "30%", "70%");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Nome", "20%");
$header[] = array("Central", "20%");
$header[] = array("L�der", "20%");
$header[] = array("Substituto", "20%");
$header[] = array("Priorizador", "20%");
//$header[] = array("Depend�ncia", "15%");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
$banco->setNOM_EQUIPE_TI($v_NOM_EQUIPE_TI);
$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);

if($v_NUM_MATRICULA_LIDER !="" && $v_NUM_MATRICULA_LIDER != null){
	// Preenchendo a RDM com os campos do formul�rio
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$v_NUM_MATRICULA = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_LIDER);
	$banco->setNUM_MATRICULA_LIDER($v_NUM_MATRICULA);
}
if($v_NUM_MATRICULA_SUBSTITUTO !="" && $v_NUM_MATRICULA_SUBSTITUTO != null){
	// Preenchendo a RDM com os campos do formul�rio
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$v_NUM_MATRICULA = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_SUBSTITUTO);
	$banco->setNUM_MATRICULA_SUBSTITUTO($v_NUM_MATRICULA);
}

if($v_NUM_MATRICULA_PRIORIZADOR !="" && $v_NUM_MATRICULA_PRIORIZADOR != null){
	// Preenchendo a RDM com os campos do formul�rio
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$v_NUM_MATRICULA = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_PRIORIZADOR);
	$banco->setNUM_MATRICULA_PRIORIZADOR($v_NUM_MATRICULA);
}


//$banco->setNUM_MATRICULA_LIDER($v_NUM_MATRICULA_LIDER);
//$banco->setNUM_MATRICULA_SUBSTITUTO($v_NUM_MATRICULA_SUBSTITUTO);
//$banco->setNUM_MATRICULA_PRIORIZADOR($v_NUM_MATRICULA_PRIORIZADOR);
$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	
	$central = new central_atendimento();
	
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Equipes encontradas para os par�mentos pesquisados", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("Equipe_tiAlteracao.php?v_SEQ_EQUIPE_TI=".$row["seq_equipe_ti"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_EQUIPE_TI", $row["seq_equipe_ti"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["nom_equipe_ti"]);
		$corpo[] = array("left", "campo", $central->GetNomeCentral($row["seq_central_atendimento"]));
		$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_lider"]));
		$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_substituto"]));
		$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_priorizador"]));
		//$corpo[] = array("center", "campo", $dependencias->GetSiglaDependencia($row["COD_DEPENDENCIA"]));
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NOM_EQUIPE_TI=$v_NOM_EQUIPE_TI&v_NUM_MATRICULA_LIDER=$v_NUM_MATRICULA_LIDER&v_NUM_MATRICULA_SUBSTITUTO=$v_NUM_MATRICULA_SUBSTITUTO&v_NUM_MATRICULA_PRIORIZADOR=$v_NUM_MATRICULA_PRIORIZADOR&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA");
$pagina->MontaRodape();
?>
