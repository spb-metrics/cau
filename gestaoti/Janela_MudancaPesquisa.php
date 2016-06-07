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
require 'include/PHP/class/class.janela_mudanca.php';
$pagina = new Pagina();
$banco = new janela_mudanca();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Janela de Mudanças"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Janela_MudancaPesquisa.php", "tabact", "Pesquisa"),
				   array("Janela_MudancaCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
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
	
	$pagina->ScriptAlert("Registro Excluído");
	$seq_janela_mudanca = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("seq_janela_mudanca", "");

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
$header[] = array("Dia da Semana Inicial", "");
$header[] = array("Hora Inicial", "");
//$header[] = array("Minuto Inicial", "");
$header[] = array("Dia da Semana Final", "");
$header[] = array("Hora Final", "");
//$header[] = array("Minuto Final", "");
$header[] = array("Limite para RDM", "");

// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setSeq_janela_mudanca($seq_janela_mudanca);
$banco->setDsc_janela_mudanca($dsc_janela_mudanca);
$banco->selectParam("2", $vNumPagina);
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Janelas de Mudanças", $header);
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
