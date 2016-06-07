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
require 'include/PHP/class/class.tipo_chamado.php';
$pagina = new Pagina();
$banco = new tipo_chamado();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Classe de Chamados"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("Tipo_chamadoPesquisa.php", "tabact", "Pesquisa"),
				   array("Tipo_chamadoCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_TIPO_CHAMADO);
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_TIPO_CHAMADO = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_TIPO_CHAMADO", "");

//$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

/* Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Descri��o:", "right", "N", $pagina->CampoTexto("v_DSC_TIPO_CHAMADO", "N", "Descri��o", "60", "60", ""), "left");

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
$header[] = array("Descri��o", "60%");
$header[] = array("Central de Atendimento", "20%");
$header[] = array("Atendimento externo?", "");
$header[] = array("Utilizado no SLA?", "");


// Setar vari�veis
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
		$corpo[] = array("left", "campo", $pagina->iif($row["flg_atendimento_externo"]=="S","Sim","N�o"));
		$corpo[] = array("left", "campo", $pagina->iif($row["flg_utilizado_sla"]=="S","Sim","N�o"));
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_DSC_TIPO_CHAMADO=$v_DSC_TIPO_CHAMADO&v_FLG_ATENDIMENTO_EXTERNO=$v_FLG_ATENDIMENTO_EXTERNO");
$pagina->MontaRodape();
?>
