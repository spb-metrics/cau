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
$recurso_ti = new recurso_ti();
$empregados = new empregados();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Hierárquia de Profissionais de TI"); // Indica o título do cabeçalho da página

// Inicio do formulário
$pagina->MontaCabecalho();

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "3%");
$header[] = array("Nome", "32%");
$header[] = array("Perfil", "15%");
$header[] = array("Lotação", "10%");
$header[] = array("Ramal", "10%");
$header[] = array("Sup. Hierárquico", "30%");

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
if($v_NUM_MATRICULA_SUPERIOR == ""){
	$v_NUM_MATRICULA_SUPERIOR = $banco->GetMatriculaSuperintendente();
}

// Líder de equipe
$pagina->LinhaHeaderTabelaResultado("Líder de Equipe de TI", $header);
$recurso_ti->select($v_NUM_MATRICULA_SUPERIOR);
if($recurso_ti->SEQ_PERFIL_RECURSO_TI != ""){
	$vNome = $recurso_ti->NOME;

	$valor = $pagina->BotaoLupa("Recurso_tiDetalhes.php?v_NUM_MATRICULA_RECURSO=".$recurso_ti->NUM_MATRICULA_RECURSO, "Detalhes de ".$recurso_ti->NOME);
	$corpo[] = array("center", "campo", $valor);
	$corpo[] = array("left", "campo", $recurso_ti->NOME);

	$perfil_recurso_ti = new perfil_recurso_ti();
	$perfil_recurso_ti->select($recurso_ti->SEQ_PERFIL_RECURSO_TI);
	$corpo[] = array("left", "campo", $perfil_recurso_ti->NOM_PERFIL_RECURSO_TI);

	$corpo[] = array("center", "campo", $recurso_ti->UOR_SIGLA);
	$corpo[] = array("center", "campo", $recurso_ti->NUM_DDD." - ".$recurso_ti->NUM_VOIP);

	$banco->select($recurso_ti->NUM_MATRICULA_SUPERIOR);
	if($banco->SEQ_PERFIL_RECURSO_TI != ""){
		$corpo[] = array("center", "campo", "<a  href='Recurso_tiHierarquia.php?v_NUM_MATRICULA_SUPERIOR=".$recurso_ti->NUM_MATRICULA_SUPERIOR."'>".$banco->NOME."</a>");
	}else{
		$corpo[] = array("center", "campo", $empregados->GetNomeEmpregado($recurso_ti->NUM_MATRICULA_SUPERIOR));
	}
	$pagina->LinhaTabelaResultado($corpo, $cont);
	$corpo = "";
}else{
	$empregados->select($v_NUM_MATRICULA_SUPERIOR);
	$vNome = $empregados->NOME;
	$corpo[] = array("center", "campo", "");
	$corpo[] = array("left", "campo", $empregados->NOME);

	$corpo[] = array("left", "campo", "&nbsp;");

	$corpo[] = array("center", "campo", $empregados->UOR_SIGLA);

	$corpo[] = array("center", "campo", $empregados->NUM_DDD." - ".$empregados->NUM_VOIP);
	$corpo[] = array("center", "campo", "");
	$pagina->LinhaTabelaResultado($corpo, $cont);
	$corpo = "";
}
$pagina->FechaTabelaPadrao();
print "<hr>";
// Subordinados
$banco = new recurso_ti();
$header = array();
$header[] = array("&nbsp;", "3%");
$header[] = array("Nome", "37%");
$header[] = array("Perfil", "25%");
$header[] = array("Lotação", "20%");
$header[] = array("Ramal", "15%");
$pagina->AbreTabelaResultado("center", "100%");

$banco->setNUM_MATRICULA_SUPERIOR($v_NUM_MATRICULA_SUPERIOR);

$banco->selectParam("NOME, UOR_SIGLA", $vNumPagina, 50);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
//	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Subordinados de $vNome", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$vQtdSubordinados = $recurso_ti->GetQtdSubordinados($row["NUM_MATRICULA_RECURSO"]);
		$valor = $pagina->BotaoLupa("Recurso_tiDetalhes.php?v_NUM_MATRICULA_RECURSO=".$row["NUM_MATRICULA_RECURSO"], "Detalhes de ".$row["NOME"]);
		$corpo[] = array("center", "campo", $valor);
		// Buscando o nome do colaborador
		if($vQtdSubordinados > 0){ // Apenas o adminsitrador
			$corpo[] = array("left", "campo", "<a href='Recurso_tiHierarquia.php?v_NUM_MATRICULA_SUPERIOR=".$row["NUM_MATRICULA_RECURSO"]."'>".$row["NOME"]."</a>");
		}else{
			$corpo[] = array("left", "campo", $row["NOME"]);
		}

		// Buscar dados da tabela externa
		$perfil_recurso_ti = new perfil_recurso_ti();
		$perfil_recurso_ti->select($row["SEQ_PERFIL_RECURSO_TI"]);
		$corpo[] = array("left", "campo", $perfil_recurso_ti->NOM_PERFIL_RECURSO_TI);

		$corpo[] = array("center", "campo", $row["UOR_SIGLA"]);

		$corpo[] = array("center", "campo", $row["NUM_DDD"]." - ".$row["NUM_VOIP"]);


		if($vQtdSubordinados > 0){ // Apenas o adminsitrador
			$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" title=\"$vQtdSubordinados subordinado(s)\" onclick=\"location.href='Recurso_tiHierarquia.php?v_NUM_MATRICULA_SUPERIOR=".$row["NUM_MATRICULA_RECURSO"]."';\"");
		}else{
			$pagina->LinhaTabelaResultado($corpo, $cont, "title=\"Não possui subordinos\"");
		}
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();
?>
