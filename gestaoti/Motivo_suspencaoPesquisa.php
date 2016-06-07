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
require 'include/PHP/class/class.motivo_suspencao.php';
$pagina = new Pagina();
$banco = new motivo_suspencao();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Motivos de Suspens�o de Chamados"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("Motivo_suspencaoPesquisa.php", "tabact", "Pesquisa"),
				   array("Motivo_suspencaoCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_MOTIVO_SUSPENCAO);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_MOTIVO_SUSPENCAO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_MOTIVO_SUSPENCAO", "");

/* Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Descri��o:", "right", "N", $pagina->CampoTexto("v_DSC_MOTIVO_SUSPENCAO", "N", "Descri��o", "60", "60", ""), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Descri��o", "");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSEQ_MOTIVO_SUSPENCAO($v_SEQ_MOTIVO_SUSPENCAO);
$banco->setDSC_MOTIVO_SUSPENCAO($v_DSC_MOTIVO_SUSPENCAO);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Motivos de suspens�o", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("Motivo_suspencaoAlteracao.php?v_SEQ_MOTIVO_SUSPENCAO=".$row["seq_motivo_suspencao"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_MOTIVO_SUSPENCAO", $row["seq_motivo_suspencao"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["dsc_motivo_suspencao"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_MOTIVO_SUSPENCAO=$v_SEQ_MOTIVO_SUSPENCAO&v_DSC_MOTIVO_SUSPENCAO=$v_DSC_MOTIVO_SUSPENCAO");
$pagina->MontaRodape();
?>
