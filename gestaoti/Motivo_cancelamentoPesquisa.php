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
require 'include/PHP/class/class.motivo_cancelamento.php';
$pagina = new Pagina();
$banco = new motivo_cancelamento();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Motivos de Cancelamento de Chamados"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Motivo_cancelamentoPesquisa.php", "tabact", "Pesquisa"),
				   array("Motivo_cancelamentoCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_MOTIVO_CANCELAMENTO);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_MOTIVO_CANCELAMENTO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_MOTIVO_CANCELAMENTO", "");

/* Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTexto("v_DSC_MOTIVO_CANCELAMENTO", "N", "Descrição", "60", "60", ""), "left");

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
$banco->setSEQ_MOTIVO_CANCELAMENTO($v_SEQ_MOTIVO_CANCELAMENTO);
$banco->setDSC_MOTIVO_CANCELAMENTO($v_DSC_MOTIVO_CANCELAMENTO);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Motivos de cancelamento", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("Motivo_cancelamentoAlteracao.php?v_SEQ_MOTIVO_CANCELAMENTO=".$row["seq_motivo_cancelamento"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_MOTIVO_CANCELAMENTO", $row["seq_motivo_cancelamento"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["dsc_motivo_cancelamento"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_MOTIVO_CANCELAMENTO=$v_SEQ_MOTIVO_CANCELAMENTO&v_DSC_MOTIVO_CANCELAMENTO=$v_DSC_MOTIVO_CANCELAMENTO");
$pagina->MontaRodape();
?>
