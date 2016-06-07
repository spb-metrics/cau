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
// Configura��o da p�g�na
$pagina->SettituloCabecalho("Confirma��o de Abertura de Chamado"); // Indica o t�tulo do cabe�alho da p�gina
$pagina->MontaCabecalho();
$pagina->AbreTabelaPadrao("left", "100%");
$pagina->LinhaColspan("center", "<font size=2>Chamado registrado com o n�mero</font> <font size=4><b>$v_SEQ_CHAMADO</b></font> ", "2", "");
$pagina->LinhaColspan("center", "<br>", "2", "");
if($chamado->QTD_MIN_SLA_ATENDIMENTO != "" && $banco->QTD_MIN_SLA_SOLUCAO_FINAL == ""){
	$vTempoRestante = $pagina->FormatarData($myDate->diff($chamado->DTH_ENCERRAMENTO_PREVISAO, 'all'));
	$pagina->LinhaColspan("center", "<font size=3>O tempo estimado para solu��o do chamado �<b> $vTempoRestante</b>.</font>", "2", "");
	$pagina->LinhaColspan("center", "<br><br>", "2", "");
}elseif($chamado->QTD_MIN_SLA_ATENDIMENTO != "" && $banco->QTD_MIN_SLA_SOLUCAO_FINAL != ""){
	$pagina->LinhaColspan("center", "<font size=3>O tempo estimado para o contingenciamento do chamado �<b> $vTempoRestante</b>.</font>", "2", "");
	$pagina->LinhaColspan("center", "<br><br>", "2", "");
}else{
	$pagina->LinhaColspan("center", "<font size=3>O tempo estimado para solu��o do chamado ser� mensurado pelo l�der da equipe correspondente.</font>", "2", "");
}

$pagina->LinhaColspan("center", "Estes valores foram estimados com base na categoria de atendimento selecionada para o chamado. Caso a categoria tenha sido equivocadamente selecionada, a �rea resposn�vel efetuar� a corre��o que poder� implicar em altera��es nos tempos acima informados.", "2", "");
if($mensagemErro != ""){
	$pagina->LinhaColspan("center", "O seguinte erro ocorreu: $mensagemErro", "2", "");
}


$atividade_chamado->select($chamado->SEQ_ATIVIDADE_CHAMADO);
$subtipo_chamado->select($atividade_chamado->SEQ_SUBTIPO_CHAMADO);

if($atividade_chamado->SEQ_TIPO_OCORRENCIA == $pagina->SEQ_TIPO_OCORRENCIA_SOLICITACAO && $subtipo_chamado->SEQ_TIPO_CHAMADO ==$pagina->SEQ_CLASSE_CHAMADO_TRANSPORTE ){
	$pagina->LinhaColspan("center", "<br><a target=\"XXX\" href=\"RelatorioRequisicaoTransporteParaServicoPopup.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO\">Clique aqui para imprimir a Requisi��o de Transporte. </a>", "2", "");
}


$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();

?>