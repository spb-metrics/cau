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
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/dateObj.class.php';
require_once 'include/PHP/class/class.atividade_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';

$pagina = new Pagina();
$chamado = new chamado();
$myDate = new dateObj();

$atividade_chamado = new atividade_chamado();
$subtipo_chamado = new subtipo_chamado();

if($v_SEQ_CHAMADO == ""){
	$pagina->redirectTo("ChamadoAtendimentoPesquisa.php");
}else{
	$chamado->select($v_SEQ_CHAMADO);
}
// Configuração da págína
$pagina->SettituloCabecalho("Confirmação de Abertura de Chamado"); // Indica o título do cabeçalho da página
$pagina->MontaCabecalho();
$pagina->AbreTabelaPadrao("left", "100%");
$pagina->LinhaColspan("center", "<font size=2>Chamado registrado com o número</font> <font size=4><b>$v_SEQ_CHAMADO</b></font> ", "2", "");
$pagina->LinhaColspan("center", "<br>", "2", "");
if($chamado->QTD_MIN_SLA_ATENDIMENTO != "" && $banco->QTD_MIN_SLA_SOLUCAO_FINAL == ""){
	$vTempoRestante = $pagina->FormatarData($myDate->diff($chamado->DTH_ENCERRAMENTO_PREVISAO, 'all'));
	$pagina->LinhaColspan("center", "<font size=3>O tempo estimado para solução do chamado é<b> $vTempoRestante</b>.</font>", "2", "");
	$pagina->LinhaColspan("center", "<br><br>", "2", "");
}elseif($chamado->QTD_MIN_SLA_ATENDIMENTO != "" && $banco->QTD_MIN_SLA_SOLUCAO_FINAL != ""){
	$pagina->LinhaColspan("center", "<font size=3>O tempo estimado para o contingenciamento do chamado é<b> $vTempoRestante</b>.</font>", "2", "");
	$pagina->LinhaColspan("center", "<br><br>", "2", "");
}else{
	$pagina->LinhaColspan("center", "<font size=3>O tempo estimado para solução do chamado será mensurado pelo líder da equipe correspondente.</font>", "2", "");
}

$pagina->LinhaColspan("center", "Estes valores foram estimados com base na categoria de atendimento selecionada para o chamado. Caso a categoria tenha sido equivocadamente selecionada, a área resposnável efetuará a correção que poderá implicar em alterações nos tempos acima informados.", "2", "");
if($mensagemErro != ""){
	$pagina->LinhaColspan("center", "O seguinte erro ocorreu: $mensagemErro", "2", "");
}


$atividade_chamado->select($chamado->SEQ_ATIVIDADE_CHAMADO);
$subtipo_chamado->select($atividade_chamado->SEQ_SUBTIPO_CHAMADO);

if($atividade_chamado->SEQ_TIPO_OCORRENCIA == $pagina->SEQ_TIPO_OCORRENCIA_SOLICITACAO && $subtipo_chamado->SEQ_TIPO_CHAMADO ==$pagina->SEQ_CLASSE_CHAMADO_TRANSPORTE ){
	$pagina->LinhaColspan("center", "<br><a target=\"XXX\" href=\"RelatorioRequisicaoTransporteParaServicoPopup.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO\">Clique aqui para imprimir a Requisição de Transporte. </a>", "2", "");
}


$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();

?>