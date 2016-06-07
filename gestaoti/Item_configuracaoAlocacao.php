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
require 'include/PHP/class/class.item_configuracao.php';
require 'include/PHP/class/class.unidades_organizacionais.php';
//require 'include/PHP/class/class.empregados.php';
require 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$banco = new item_configuracao();
$unidades_organizacionais = new unidades_organizacionais();
$empregados = new empregados();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Controle de Alocação de Profissionais"); // Indica o título do cabeçalho da página
// Itens das abas
if($flag == "1"){
	$aItemAba = Array( array("Item_configuracaoAlocacao.php", "tabact", "Pesquisa"),
					   array("Equipe_envolvidaCadastro.php?v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO", "", "Adicionar") );
}else{
	$aItemAba = Array( array("Item_configuracaoAlocacao.php", "tabact", "Pesquisa") );
}

$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	require_once 'include/PHP/class/class.equipe_envolvida.php';
	$equipe_envolvida = new equipe_envolvida();
	$equipe_envolvida->delete($v_SEQ_ITEM_CONFIGURACAO, $v_NUM_MATRICULA_RECURSO);
	$v_NUM_MATRICULA_RECURSO = $empregados->GetNomLoginRedeMatricula($v_NUM_MATRICULA_RECURSO);
	$flag = "1";
	$pagina->ScriptAlert("Registro Excluído");
}

print $pagina->CampoHidden("flag", "1");

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "45%");

if($flag == "1"){
	if($empregados->GetNumeroMatricula($v_NUM_MATRICULA_RECURSO) == ""){
		$pagina->ScriptAlert("Matrícula não encontrada");
		$flag = "";
	}
}

$pagina->LinhaCampoFormulario("Matricula do Profissional:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_RECURSO", "S", "Matrícula do Profissional" , "11", "11", $v_NUM_MATRICULA_RECURSO, "").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_RECURSO", "TI")
								  , "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);

if($flag == "1"){
	//print "<div align=left>&nbsp;&nbsp;".$pagina->BotaoAdicionarRegistro("Equipe_envolvidaCadastro.php?v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO")."</div>";
	//$pagina->LinhaVazia(1);
	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();

	if($pagina->segurancaPerfilExclusao($_SESSION["SEQ_PERFIL_ACESSO"]) || $pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas o adminsitrador
		$header[] = array("&nbsp;", "5%");
	}

	$header[] = array("Sigla", "20%");
	$header[] = array("Nome", "35%");
	$header[] = array("Área", "7%");
//	$header[] = array("Tipo", "13%");
	//$header[] = array("Servico", "");
//	$header[] = array("Gestor", "20%");
	$header[] = array("Líder", "20%");
	$header[] = array("Hr. Semanais", "15%");

	//$header[] = array("Descrição", "");
	//$header[] = array("Tipo disponibilidade", "");
	//$header[] = array("Tipo criticidade", "");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->selectAlocacao($empregados->GetNumeroMatricula(strtoupper($v_NUM_MATRICULA_RECURSO)), "2", $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Alocação do colaborador ".$empregados->GetNomeEmpregado($empregados->GetNumeroMatricula($v_NUM_MATRICULA_RECURSO)), $header);
		while ($row = pg_fetch_array($banco->database->result)){

			if($pagina->segurancaPerfilExclusao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas o adminsitrador
				$valor = $pagina->BotaoAlteraGridPesquisa("Equipe_envolvidaAlteracao.php?v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_SEQ_ITEM_CONFIGURACAO=".$row["SEQ_ITEM_CONFIGURACAO"]."&v_NOM_ITEM_CONFIGURACAO=".$row["NOM_ITEM_CONFIGURACAO"]."&v_VAL_PERCENT_ALOCACAO=".$row["VAL_PERCENT_ALOCACAO"]);
				$valor .= $pagina->BotaoExcluiGridPesquisa1("Item_configuracaoAlocacao.php?v_SEQ_ITEM_CONFIGURACAO=".$row["SEQ_ITEM_CONFIGURACAO"]."&v_NUM_MATRICULA_RECURSO=".$empregados->GetNumeroMatricula($v_NUM_MATRICULA_RECURSO)."&flag=2");
				$corpo[] = array("center", "campo", $valor);
			}elseif($pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas o adminsitrador
				$valor = $pagina->BotaoAlteraGridPesquisa("Equipe_envolvidaAlteracao.php?v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_SEQ_ITEM_CONFIGURACAO=".$row["SEQ_ITEM_CONFIGURACAO"]."&v_NOM_ITEM_CONFIGURACAO=".$row["NOM_ITEM_CONFIGURACAO"]."&v_VAL_PERCENT_ALOCACAO=".$row["VAL_PERCENT_ALOCACAO"]);
				$corpo[] = array("center", "campo", $valor);
			}

			$corpo[] = array("left", "campo", $row["SIG_ITEM_CONFIGURACAO"]);
			$corpo[] = array("left", "campo", $row["NOM_ITEM_CONFIGURACAO"]);
			$corpo[] = array("left", "campo", $unidades_organizacionais->GetUorSigla($row["COD_UOR_AREA_GESTORA"]));

	//		require_once 'include/PHP/class/class.tipo_item_configuracao.php';
	//		$tipo_item_configuracao = new tipo_item_configuracao();
	//		$tipo_item_configuracao->select($row["SEQ_TIPO_ITEM_CONFIGURACAO"]);
	//		$corpo[] = array("left", "campo", $tipo_item_configuracao->NOM_TIPO_ITEM_CONFIGURACAO);
	//		$corpo[] = array("right", "campo", $row["SEQ_SERVICO"]);
	//		$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["NUM_MATRICULA_GESTOR"]));
			$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["NUM_MATRICULA_LIDER"]));
			$corpo[] = array("right", "campo", $row["QTD_HORA_ALOCADA"]);
	//		$corpo[] = array("left", "campo", $row["TXT_ITEM_CONFIGURACAO"]);
	//		$corpo[] = array("right", "campo", $row["SEQ_TIPO_DISPONIBILIDADE"]);
	//		$corpo[] = array("right", "campo", $row["SEQ_PRIORIDADE"]);
			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO&v_SEQ_TIPO_ITEM_CONFIGURACAO=$v_SEQ_TIPO_ITEM_CONFIGURACAO&v_SEQ_SERVICO=$v_SEQ_SERVICO&v_NUM_MATRICULA_GESTOR=$v_NUM_MATRICULA_GESTOR&v_NUM_MATRICULA_LIDER=$v_NUM_MATRICULA_LIDER&v_SIG_ITEM_CONFIGURACAO=$v_SIG_ITEM_CONFIGURACAO&v_NOM_ITEM_CONFIGURACAO=$v_NOM_ITEM_CONFIGURACAO&v_COD_UOR_AREA_GESTORA=$v_COD_UOR_AREA_GESTORA&v_TXT_ITEM_CONFIGURACAO=$v_TXT_ITEM_CONFIGURACAO&v_SEQ_TIPO_DISPONIBILIDADE=$v_SEQ_TIPO_DISPONIBILIDADE&v_SEQ_PRIORIDADE=$v_SEQ_PRIORIDADE");
}
$pagina->MontaRodape();
?>
