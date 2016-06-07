<?php
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
require_once 'include/PHP/class/class.rdm.php';
require_once 'include/PHP/class/dateObj.class.php';
function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
}
$pagina = new Pagina();
$RDM = new rdm();
$myDate = new dateObj();
if($v_SEQ_RDM == ""){
	$pagina->redirectTo("RDMPesquisa.php");
}else{
	$RDM->select($v_SEQ_RDM);
}
// Configura��o da p�g�na
$pagina->SettituloCabecalho("Confirma��o de Abertura de RDM"); // Indica o t�tulo do cabe�alho da p�gina
$pagina->MontaCabecalho();
$pagina->AbreTabelaPadrao("left", "100%");
$pagina->LinhaColspan("center", "<br>", "2", "");
$pagina->LinhaColspan("center", "<font size=3>RDM registrada com o n�mero</font> <font size=4><b>$v_SEQ_RDM</b></font> ", "2", "");
//$pagina->LinhaColspan("center", "<br>", "2", "");

//FORMATAR DATA
$array_teste = split(" ",$RDM->DATA_HORA_PREVISTA_EXECUCAO);
$data_array_teste = split("-",$array_teste[0]);
$hora_array_teste = split(":",$array_teste[1]);

$dia_teste = $data_array_teste[2];
$mes_teste = $data_array_teste[1];
$ano_teste = $data_array_teste[0];

//$hora_array_teste = split(":",$dataHoraAtual);
$h_teste = $hora_array_teste[0];
$min_teste =  $hora_array_teste[1];
 

$DATA_HORA_PREVISTA_EXECUCAO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
//$pagina->LinhaColspan("center", "<font size=3>A execua��o prevista para <b> $RDM->DATA_HORA_PREVISTA_EXECUCAO</b>.</font>", "2", "");
$pagina->LinhaColspan("center", "<font size=3>A execu��o prevista para <b> ".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b></font>", "2", "");
$pagina->LinhaColspan("center", "<font size=3>Tipo da RDM </font> <font size=4><b>".iif($RDM->TIPO==1, "Normal", "Emergencial")."</b></font> ", "2", "");

if($mensagemErro != ""){
	$pagina->LinhaColspan("center", "O seguinte erro ocorreu: $vMsgErro", "2", "");
}

$pagina->LinhaColspan("center", "<font size=3>RDM registrada com o n�mero</font> <font size=4><b>$v_SEQ_RDM</b></font> ", "2", "");


$pagina->LinhaColspan("center","<br><font size=3><a href=\"RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM."\">Ver detalhes da RDM</a></font>", "2", "");
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();


	
?>