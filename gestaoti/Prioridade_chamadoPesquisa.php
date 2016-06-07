<?php
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
// =======================================================================
// Página de pesquisa de exclusão de registros da tabela Prioridade_chamado
// Página gerada pelo sistema GeraPHP - 03/09/2008 14:26:25
// =======================================================================
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.prioridade_chamado.php';
$pagina = new Pagina();
$banco = new prioridade_chamado();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa de Prioridades de Chamados"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Prioridade_chamadoPesquisa.php", "tabact", "Pesquisa"),
				   array("Prioridade_chamadoCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_PRIORIDADE_CHAMADO);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_PRIORIDADE_CHAMADO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_PRIORIDADE_CHAMADO", "");

/* Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTexto("v_DSC_PRIORIDADE_CHAMADO", "N", "Descrição", "60", "60", ""), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Descrição", "");

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
$banco->setDSC_PRIORIDADE_CHAMADO($v_DSC_PRIORIDADE_CHAMADO);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Prioridades de chamados", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("Prioridade_chamadoAlteracao.php?v_SEQ_PRIORIDADE_CHAMADO=".$row["seq_prioridade_chamado"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_PRIORIDADE_CHAMADO", $row["seq_prioridade_chamado"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["dsc_prioridade_chamado"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_DSC_PRIORIDADE_CHAMADO=$v_DSC_PRIORIDADE_CHAMADO");
$pagina->MontaRodape();
?>
