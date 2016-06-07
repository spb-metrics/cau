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
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.item_configuracao.php';
$pagina = new Pagina();
$banco = new chamado();
$situacao_chamado = new situacao_chamado();
$item_configuracao = new item_configuracao();
$v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND = $situacao_chamado->COD_Aguardando_Atendimento.",".$situacao_chamado->COD_Aguardando_Planejamento;
$v_SEQ_SITUACAO_CHAMADO_EXEC = $situacao_chamado->COD_Em_Andamento.",".$situacao_chamado->COD_Suspenca;

// Configuração da págína
$pagina->SettituloCabecalho("Lista de prioridades de manutenções em sistemas de informação"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();

// ============================== Fila de Chamados Aguardando Atendimento ==================
$pagina->AbreTabelaResultado("center", "100%");
// Cabeçalho de resultados
$header = array();
$header[] = array("Prior.", "7%");
$header[] = array("Chamado", "7%");
$header[] = array("Abertura", "10%");
$header[] = array("Previsão Término", "10%");
$header[] = array("Sistema", "26%");
$header[] = array("Descrição", "40%");

$pagina->LinhaHeaderTabelaResultado("Priorização de demanas aguardando atendimento da ".$_SESSION["NOM_EQUIPE_TI"], $header);

// Parâmetros da pesquisa
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
// Cabeçalho de resultados
$header = array();
$header[] = array("Prior.", "7%");
$header[] = array("Chamado", "7%");
$header[] = array("Abertura", "10%");
$header[] = array("Previsão Término", "10%");
$header[] = array("Sistema", "26%");
$header[] = array("Descrição", "40%");

$pagina->LinhaHeaderTabelaResultado("Priorização de demanas em atendimento da ".$_SESSION["NOM_EQUIPE_TI"], $header);

// Parâmetros da pesquisa
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
