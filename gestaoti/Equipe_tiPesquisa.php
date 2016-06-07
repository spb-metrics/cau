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
require 'include/PHP/class/class.equipe_ti.php';
require 'include/PHP/class/class.empregados.oracle.php';
$empregados = new empregados();
$pagina = new Pagina();
$banco = new equipe_ti();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Equipes de Atendimento"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Equipe_tiPesquisa.php", "tabact", "Pesquisa"),
				   array("Equipe_tiCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();

//$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_EQUIPE_TI);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_EQUIPE_TI = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_EQUIPE_TI", "");

/* Inicio da tabela de parâmetros */
$pagina->AbreTabelaPadrao("center", "85%");

// Montar a combo
require 'include/PHP/class/class.central_atendimento.php';
$central_atendimento = new central_atendimento();
 
$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "N", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "N", "Central de Atendimento", "S", $central_atendimento->combo(2)), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");

	
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_EQUIPE_TI", "N", "Nome", "60", "60", ""), "left");
$pagina->LinhaCampoFormulario("Matricula do Líder:", "right", "N",
							  $pagina->CampoTexto("v_NUM_MATRICULA_LIDER", "N", "Matrícula do Líder" , "11", "11", $banco->NUM_MATRICULA_LIDER, "").
							  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_LIDER", "TI")
							  , "left");

$pagina->LinhaCampoFormulario("Matricula do Substituto:", "right", "N",
							  $pagina->CampoTexto("v_NUM_MATRICULA_SUBSTITUTO", "N", "Matrícula do Substituto" , "11", "11", $banco->NUM_MATRICULA_SUBSTITUTO, "").
							  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_SUBSTITUTO", "TI")
							  , "left");

$pagina->LinhaCampoFormulario("Matricula do Priorizador:", "right", "N",
							  $pagina->CampoTexto("v_NUM_MATRICULA_PRIORIZADOR", "N", "Matrícula do Priorizador" , "11", "11", $banco->NUM_MATRICULA_PRIORIZADOR, "").
							  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_PRIORIZADOR", "")
							  , "left");

// Montar a combo
//require 'include/PHP/class/class.dependencias.php';
//$dependencias = new dependencias();
//$pagina->LinhaCampoFormulario("Dependência:", "right", "N", $pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Dependência", "S", $dependencias->combo(2, $banco->COD_DEPENDENCIA)), "left", "v_COD_DEPENDENCIA", "30%", "70%");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Nome", "20%");
$header[] = array("Central", "20%");
$header[] = array("Líder", "20%");
$header[] = array("Substituto", "20%");
$header[] = array("Priorizador", "20%");
//$header[] = array("Dependência", "15%");

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
$banco->setNOM_EQUIPE_TI($v_NOM_EQUIPE_TI);
$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);

if($v_NUM_MATRICULA_LIDER !="" && $v_NUM_MATRICULA_LIDER != null){
	// Preenchendo a RDM com os campos do formulário
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$v_NUM_MATRICULA = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_LIDER);
	$banco->setNUM_MATRICULA_LIDER($v_NUM_MATRICULA);
}
if($v_NUM_MATRICULA_SUBSTITUTO !="" && $v_NUM_MATRICULA_SUBSTITUTO != null){
	// Preenchendo a RDM com os campos do formulário
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	$v_NUM_MATRICULA = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_SUBSTITUTO);
	$banco->setNUM_MATRICULA_SUBSTITUTO($v_NUM_MATRICULA);
}

if($v_NUM_MATRICULA_PRIORIZADOR !="" && $v_NUM_MATRICULA_PRIORIZADOR != null){
	// Preenchendo a RDM com os campos do formulário
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
	$pagina->LinhaHeaderTabelaResultado("Equipes encontradas para os parâmentos pesquisados", $header);
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
