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
require 'include/PHP/class/class.tipo_chamado.php';
$pagina = new Pagina();
$banco = new tipo_chamado();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Classe de Chamados"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Tipo_chamadoPesquisa.php", "tabact", "Pesquisa"),
				   array("Tipo_chamadoCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_TIPO_CHAMADO);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_TIPO_CHAMADO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_TIPO_CHAMADO", "");

//$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

/* Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTexto("v_DSC_TIPO_CHAMADO", "N", "Descrição", "60", "60", ""), "left");

$pagina->LinhaCampoFormulario("Indicador de Atendimento externo:", "right", "N", $pagina->CampoTexto("v_FLG_ATENDIMENTO_EXTERNO", "N", "Indicador de Atendimento externo", "1", "1", ""), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/

$pagina->AbreTabelaPadrao("center", "85%");

// Montar a combo
require 'include/PHP/class/class.central_atendimento.php';
$central_atendimento = new central_atendimento();
 
$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "N", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "N", "Central de Atendimento", "S", $central_atendimento->combo(2)), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");

//$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Descrição", "60%");
$header[] = array("Central de Atendimento", "20%");
$header[] = array("Atendimento externo?", "");
$header[] = array("Utilizado no SLA?", "");


// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
$banco->setDSC_TIPO_CHAMADO($v_DSC_TIPO_CHAMADO);
$banco->setFLG_ATENDIMENTO_EXTERNO($v_FLG_ATENDIMENTO_EXTERNO);
$banco->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
$banco->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);


$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$central = new central_atendimento();
	
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Classes de Chamados", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("Tipo_chamadoAlteracao.php?v_SEQ_TIPO_CHAMADO=".$row["seq_tipo_chamado"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_TIPO_CHAMADO", $row["seq_tipo_chamado"]);
		$valor .= $pagina->BotaoLupa("Subtipo_chamadoPesquisa.php?flag=1&v_SEQ_TIPO_CHAMADO=".$row["seq_tipo_chamado"], "Ver Atividades Relacionadas");
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["dsc_tipo_chamado"]);
                $corpo[] = array("left", "campo", $central->GetNomeCentral($row["seq_central_atendimento"]));
		$corpo[] = array("left", "campo", $pagina->iif($row["flg_atendimento_externo"]=="S","Sim","Não"));
		$corpo[] = array("left", "campo", $pagina->iif($row["flg_utilizado_sla"]=="S","Sim","Não"));
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_DSC_TIPO_CHAMADO=$v_DSC_TIPO_CHAMADO&v_FLG_ATENDIMENTO_EXTERNO=$v_FLG_ATENDIMENTO_EXTERNO");
$pagina->MontaRodape();
?>
