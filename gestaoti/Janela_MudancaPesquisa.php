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
require 'include/PHP/class/class.janela_mudanca.php';
$pagina = new Pagina();
$banco = new janela_mudanca();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Janela de Mudan�as"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("Janela_MudancaPesquisa.php", "tabact", "Pesquisa"),
				   array("Janela_MudancaCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	
	// Incluir servidores
	require_once 'include/PHP/class/class.janela_mudanca_servidor.php';
	$janela_mudanca_servidor = new janela_mudanca_servidor();
	$janela_mudanca_servidor->deleteBySeq_janela_mudanca($seq_janela_mudanca);
	
	// Incluir o item configuracao
	require_once 'include/PHP/class/class.janela_mudacao_item_configuracao.php';
	$janela_mudacao_item_configuracao = new janela_mudacao_item_configuracao();
	$janela_mudacao_item_configuracao->deleteBySeq_janela_mudanca($seq_janela_mudanca);
	
	$banco->delete($seq_janela_mudanca);
	
	$pagina->ScriptAlert("Registro Exclu�do");
	$seq_janela_mudanca = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("seq_janela_mudanca", "");

/* Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

$pagina->LinhaCampoFormulario("Descri��o:", "right", "N", $pagina->CampoTexto("v_DSC_MOTIVO_CANCELAMENTO", "N", "Descri��o", "60", "60", ""), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->LinhaVazia(1);
*/
// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("&nbsp;", "5%");
$header[] = array("Descri��o", "");
$header[] = array("Dia da Semana Inicial", "");
$header[] = array("Hora Inicial", "");
//$header[] = array("Minuto Inicial", "");
$header[] = array("Dia da Semana Final", "");
$header[] = array("Hora Final", "");
//$header[] = array("Minuto Final", "");
$header[] = array("Limite para RDM", "");

// Setar vari�veis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSeq_janela_mudanca($seq_janela_mudanca);
$banco->setDsc_janela_mudanca($dsc_janela_mudanca);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Janelas de Mudan�as", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("Janela_MudancaAlteracao.php?seq_janela_mudanca=".$row["seq_janela_mudanca"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("seq_janela_mudanca", $row["seq_janela_mudanca"]);
		$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["dsc_janela_mudanca"]);
		$corpo[] = array("left", "campo", $row["dia_semana_inicial"]);
		$HoraInicio = "";
		if(strlen($row["hora_inicio_mudanca"]) == 1){
        	$HoraInicio = '0'.$row["hora_inicio_mudanca"];
		}else{
			$HoraInicio = $row["hora_inicio_mudanca"];
		}
		$HoraInicio = $HoraInicio.":";
		if(strlen($row["minuto_inicio_mudanca"]) == 1){
        	$HoraInicio = $HoraInicio.'0'.$row["minuto_inicio_mudanca"];
		}else{
			$HoraInicio = $HoraInicio.$row["minuto_inicio_mudanca"];
		}
         
//		$corpo[] = array("left", "campo", $row["hora_inicio_mudanca"]);
//		$corpo[] = array("left", "campo", $row["minuto_inicio_mudanca"]);
		$corpo[] = array("left", "campo", $HoraInicio);
		
		$corpo[] = array("left", "campo", $row["dia_semana_final"]);
		
		$HoraFim= "";
		$HoraFim = "";
		if(strlen($row["hora_fim_mudanca"]) == 1){
        	$HoraFim = '0'.$row["hora_fim_mudanca"];
		}else{
			$HoraFim = $row["hora_fim_mudanca"];
		}
		$HoraFim = $HoraFim.":";
		if(strlen($row["minuto_fim_mudanca"]) == 1){
        	$HoraFim = $HoraFim.'0'.$row["minuto_fim_mudanca"];
		}else{
			$HoraFim = $HoraFim.$row["minuto_fim_mudanca"];
		}
//		$corpo[] = array("left", "campo", $row["hora_fim_mudanca"]);
//		$corpo[] = array("left", "campo", $row["minuto_fim_mudanca"]);
		$corpo[] = array("left", "campo", $HoraFim);
		$corpo[] = array("left", "campo", $row["limite_para_rdm"]);
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->FechaTabelaPadrao();
$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&seq_janela_mudanca=$seq_janela_mudanca&dsc_janela_mudanca=$dsc_janela_mudanca");
$pagina->MontaRodape();
?>
