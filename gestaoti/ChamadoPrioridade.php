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
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.item_configuracao.php';
$pagina = new Pagina();
$banco = new chamado();
$situacao_chamado = new situacao_chamado();
$item_configuracao = new item_configuracao();
$v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND = $situacao_chamado->COD_Aguardando_Atendimento.",".$situacao_chamado->COD_Aguardando_Planejamento;
$v_SEQ_SITUACAO_CHAMADO_EXEC = $situacao_chamado->COD_Em_Andamento.",".$situacao_chamado->COD_Suspenca;

// Configura��o da p�g�na
$pagina->SettituloCabecalho("Lista de prioridades de manuten��es em sistemas de informa��o"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();

// ============================== Fila de Chamados Aguardando Atendimento ==================
$pagina->AbreTabelaResultado("center", "100%");
// Cabe�alho de resultados
$header = array();
$header[] = array("Prior.", "7%");
$header[] = array("Chamado", "7%");
$header[] = array("Abertura", "10%");
$header[] = array("Previs�o T�rmino", "10%");
$header[] = array("Sistema", "26%");
$header[] = array("Descri��o", "40%");

$pagina->LinhaHeaderTabelaResultado("Prioriza��o de demanas aguardando atendimento da ".$_SESSION["NOM_EQUIPE_TI"], $header);

// Par�metros da pesquisa
$banco->setNUM_PRIORIDADE_FILA("NOTNULL");
$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND);
$banco->selectParam("NUM_PRIORIDADE_FILA");
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum chamado priorizado aguardando atendimento", count($header));
}else{
	$corpo = array();
	$cont = 1;
	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("right", "campo", $row["NUM_PRIORIDADE_FILA"]);
		$corpo[] = array("right", "campo", $row["SEQ_CHAMADO"]);
		$corpo[] = array("center", "campo", $row["DTH_ABERTURA"]);
		$corpo[] = array("center", "campo", $row["DTH_ENCERRAMENTO_PREVISAO"]);

		$item_configuracao = new item_configuracao();
		$item_configuracao->select($row["SEQ_ITEM_CONFIGURACAO"]);
		$corpo[] = array("left", "campo", $item_configuracao->SIG_ITEM_CONFIGURACAO);

		$corpo[] = array("left", "campo", $row["TXT_CHAMADO"]);

		$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhe.php?flag=1&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."';\"");
		$corpo = "";

		$cont++;
	}
}
$pagina->FechaTabelaPadrao();

$pagina->LinhaVazia(1);

// ============================== Fila de Chamados Em Atendimento ==================
$pagina->AbreTabelaResultado("center", "100%");
// Cabe�alho de resultados
$header = array();
$header[] = array("Prior.", "7%");
$header[] = array("Chamado", "7%");
$header[] = array("Abertura", "10%");
$header[] = array("Previs�o T�rmino", "10%");
$header[] = array("Sistema", "26%");
$header[] = array("Descri��o", "40%");

$pagina->LinhaHeaderTabelaResultado("Prioriza��o de demanas em atendimento da ".$_SESSION["NOM_EQUIPE_TI"], $header);

// Par�metros da pesquisa
$banco->setNUM_PRIORIDADE_FILA("NOTNULL");
$banco->setSEQ_EQUIPE_TI($_SESSION["SEQ_EQUIPE_TI"]);
$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO_EXEC);
$banco->selectParam("NUM_PRIORIDADE_FILA");
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum chamado priorizado em atendimento", count($header));
}else{
	$corpo = array();

	$cont = 1;
	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("right", "campo", $row["NUM_PRIORIDADE_FILA"]);
		$corpo[] = array("right", "campo", $row["SEQ_CHAMADO"]);
		$corpo[] = array("center", "campo", $row["DTH_ABERTURA"]);
		$corpo[] = array("center", "campo", $row["DTH_ENCERRAMENTO_PREVISAO"]);

		$item_configuracao = new item_configuracao();
		$item_configuracao->select($row["SEQ_ITEM_CONFIGURACAO"]);
		$corpo[] = array("left", "campo", $item_configuracao->SIG_ITEM_CONFIGURACAO);

		$corpo[] = array("left", "campo", $row["TXT_CHAMADO"]);

		$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhe.php?flag=1&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."';\"");
		$corpo = "";

		$cont++;
	}
}
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>
