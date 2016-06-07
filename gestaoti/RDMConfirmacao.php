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
// Configuração da págína
$pagina->SettituloCabecalho("Confirmação de Abertura de RDM"); // Indica o título do cabeçalho da página
$pagina->MontaCabecalho();
$pagina->AbreTabelaPadrao("left", "100%");
$pagina->LinhaColspan("center", "<br>", "2", "");
$pagina->LinhaColspan("center", "<font size=3>RDM registrada com o número</font> <font size=4><b>$v_SEQ_RDM</b></font> ", "2", "");
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
//$pagina->LinhaColspan("center", "<font size=3>A execuação prevista para <b> $RDM->DATA_HORA_PREVISTA_EXECUCAO</b>.</font>", "2", "");
$pagina->LinhaColspan("center", "<font size=3>A execução prevista para <b> ".date("d/m/y H:i:s",$DATA_HORA_PREVISTA_EXECUCAO)."</b></font>", "2", "");
$pagina->LinhaColspan("center", "<font size=3>Tipo da RDM </font> <font size=4><b>".iif($RDM->TIPO==1, "Normal", "Emergencial")."</b></font> ", "2", "");

if($mensagemErro != ""){
	$pagina->LinhaColspan("center", "O seguinte erro ocorreu: $vMsgErro", "2", "");
}

$pagina->LinhaColspan("center", "<font size=3>RDM registrada com o número</font> <font size=4><b>$v_SEQ_RDM</b></font> ", "2", "");


$pagina->LinhaColspan("center","<br><font size=3><a href=\"RDMDetalhe.php?v_SEQ_RDM=".$v_SEQ_RDM."\">Ver detalhes da RDM</a></font>", "2", "");
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();


	
?>