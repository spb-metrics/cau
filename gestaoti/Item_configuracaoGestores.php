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
require 'include/PHP/class/class.recurso_ti.php';
require 'include/PHP/class/class.area_atuacao.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.perfil_recurso_ti.php';
require_once 'include/PHP/class/class.perfil_acesso.php';
$pagina = new Pagina();
$banco = new recurso_ti();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Clientes Gestores"); // Indica o título do cabeçalho da página
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
$pagina->LinhaCampoFormulario("Lotação:", "right", "N", $pagina->CampoTexto("v_UOR_SIGLA", "N", "Unidade Organizacional", "10", "10", $v_UOR_SIGLA), "left");
$pagina->LinhaCampoFormulario("Dependência:", "right", "N", $pagina->CampoTexto("v_DEP_SIGLA", "N", "Unidade Organizacional", "10", "10", $v_DEP_SIGLA), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();

if($flag == "1"){
	$pagina->LinhaVazia(1);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();

	$header[] = array("Nome", "60%");
	$header[] = array("Dependência", "15%");
	$header[] = array("Lotação", "15%");
	$header[] = array("Ramal", "10%");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setNOME($v_NOME);
	$banco->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
	$banco->setUOR_SIGLA($v_UOR_SIGLA);
	$banco->setDEP_SIGLA($v_DEP_SIGLA);
	$banco->GetGestores("NOME", $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Clientes Getores encontrados para os parâmentos pesquisados", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$corpo[] = array("left", "campo", $row["NOME"]);
			$corpo[] = array("center", "campo", $row["DEP_SIGLA"]);
			$corpo[] = array("center", "campo", $row["UOR_SIGLA"]);
			$corpo[] = array("center", "campo", $row["NUM_DDD"]." - ".$row["NUM_VOIP"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_SEQ_PERFIL_RECURSO_TI=$v_SEQ_PERFIL_RECURSO_TI&v_COD_UOR=$v_COD_UOR&v_FLG_LIDER=$v_FLG_LIDER&v_UOR_SIGLA=$v_UOR_SIGLA&v_DEP_SIGLA=$v_DEP_SIGLA");
}

$pagina->MontaRodape();
?>
