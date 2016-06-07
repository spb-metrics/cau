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
require_once 'include/PHP/class/class.recurso_ti.php';
require_once 'include/PHP/class/class.area_atuacao.php';
$pagina = new Pagina();
$banco = new recurso_ti();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Profissional de TI"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("#", "tabact", "Pesquisa") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_NUM_MATRICULA_RECURSO);
	$pagina->ScriptAlert("Registro Excluído");
	$v_NUM_MATRICULA_RECURSO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", "");

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");

//$pagina->LinhaCampoFormulario(" de Numero matricula:", "right", "N", $pagina->CampoTexto("v_NUM_MATRICULA_RECURSO", "N", " de Numero matricula", "9)", "9)", ""), "left");


// Buscar dados da tabela externa
require_once 'include/PHP/class/class.perfil_recurso_ti.php';
$perfil_recurso_ti = new perfil_recurso_ti();
$aItemOption = Array();

$perfil_recurso_ti->selectParam(2);
$cont = 0;
while ($row = pg_fetch_array($perfil_recurso_ti->database->result)){
	$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_PERFIL_RECURSO_TI == $row[0],"Selected", ""), $row[1]);
	$cont++;
}
// Adicionar combo no formulário
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOME", "N", "Nome", "60", "60", $v_NOME), "left");
$pagina->LinhaCampoFormulario("Perfil:", "right", "N", $pagina->CampoSelect("v_SEQ_PERFIL_RECURSO_TI", "N", "Perfil recurso ti", "S", $aItemOption), "left");
$pagina->LinhaCampoFormulario("Lotação:", "right", "N", $pagina->CampoTexto("v_UOR_SIGLA", "N", "Unidade Organizacional", "10", "10", $v_UOR_SIGLA), "left");
$pagina->LinhaCampoFormulario("Dependência:", "right", "N", $pagina->CampoTexto("v_DEP_SIGLA", "N", "Unidade Organizacional", "10", "10", $v_DEP_SIGLA), "left");
//$pagina->LinhaCampoFormulario("Nível:", "right", "N", $pagina->CampoTexto("v_FLG_LIDER", "N", "Indicador de Lider", "1", "1", ""), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Nome", "20%");
$header[] = array("Login", "10%");
$header[] = array("Perfil", "15%");
$header[] = array("Atuação", "10%");
$header[] = array("Dependência", "10%");
$header[] = array("Lotação", "10%");
$header[] = array("Acesso", "10%");

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setNOME($v_NOME);
$banco->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
$banco->setSEQ_PERFIL_RECURSO_TI($v_SEQ_PERFIL_RECURSO_TI);
$banco->setUOR_SIGLA($v_UOR_SIGLA);
$banco->setFLG_LIDER($v_FLG_LIDER);
$banco->setDEP_SIGLA($v_DEP_SIGLA);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Recursos de TI encontrados para os parâmentos pesquisados", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoTimeSheetGridPesquisa("TimeSheet.php?v_NUM_MATRICULA_RECURSO=".$row["NUM_MATRICULA_RECURSO"]."&GESTOR=TRUE");
		$corpo[] = array("center", "campo", $valor);
		// Buscando o nome do colaborador
		//require_once 'include/PHP/class/class.empregados.php';
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($row["NUM_MATRICULA_RECURSO"]);
		$corpo[] = array("left", "campo", $empregados->NOME);
		$corpo[] = array("left", "campo", $empregados->NOM_LOGIN_REDE);
		// Buscar dados da tabela externa
		require_once 'include/PHP/class/class.perfil_recurso_ti.php';
		$perfil_recurso_ti = new perfil_recurso_ti();
		$perfil_recurso_ti->select($row["SEQ_PERFIL_RECURSO_TI"]);
		$corpo[] = array("left", "campo", $perfil_recurso_ti->NOM_PERFIL_RECURSO_TI);
		$area_atuacao = new area_atuacao();
		$area_atuacao->select($row["SEQ_AREA_ATUACAO"]);
		$corpo[] = array("left", "campo", $area_atuacao->NOM_AREA_ATUACAO);
		$corpo[] = array("center", "campo", $empregados->DEP_SIGLA);
		$corpo[] = array("center", "campo", $empregados->UOR_SIGLA);
		require_once 'include/PHP/class/class.perfil_acesso.php';
		$perfil_acesso = new perfil_acesso();
		$perfil_acesso->select($row["SEQ_PERFIL_ACESSO"]);
		$corpo[] = array("left", "campo", $perfil_acesso->NOM_PERFIL_ACESSO);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_SEQ_PERFIL_RECURSO_TI=$v_SEQ_PERFIL_RECURSO_TI&v_COD_UOR=$v_COD_UOR&v_FLG_LIDER=$v_FLG_LIDER&v_UOR_SIGLA=$v_UOR_SIGLA&v_DEP_SIGLA=$v_DEP_SIGLA");
$pagina->MontaRodape();
?>
