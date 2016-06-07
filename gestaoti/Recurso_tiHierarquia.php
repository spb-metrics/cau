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
$pagina = new Pagina();
$banco = new recurso_ti();
$recurso_ti = new recurso_ti();
$empregados = new empregados();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Hier�rquia de Profissionais de TI"); // Indica o t�tulo do cabe�alho da p�gina

// Inicio do formul�rio
$pagina->MontaCabecalho();

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "3%");
$header[] = array("Nome", "32%");
$header[] = array("Perfil", "15%");
$header[] = array("Lota��o", "10%");
$header[] = array("Ramal", "10%");
$header[] = array("Sup. Hier�rquico", "30%");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
if($v_NUM_MATRICULA_SUPERIOR == ""){
	$v_NUM_MATRICULA_SUPERIOR = $banco->GetMatriculaSuperintendente();
}

// L�der de equipe
$pagina->LinhaHeaderTabelaResultado("L�der de Equipe de TI", $header);
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
$header[] = array("Lota��o", "20%");
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
			$pagina->LinhaTabelaResultado($corpo, $cont, "title=\"N�o possui subordinos\"");
		}
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();
?>
