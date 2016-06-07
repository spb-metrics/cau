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

require_once 'include/PHP/class/class.tarefa.php';
require_once 'include/PHP/class/class.time_sheet.php';

$pagina = new Pagina();
$banco_tarefa = new tarefa();
$banco_timesheet = new time_sheet();

// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Time Sheet"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("#", "tabact", "Pesquisa")
				    );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", "");

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");

// Buscar dados da tabela externa
$pagina->LinhaCampoFormulario("Tarefa:", "right", "N", $pagina->CampoTexto("v_SEQ_TAREFA_TI", "N", "", "30", "30", $v_SEQ_TAREFA_TI), "left"); 

//$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO", "N", "Nome", "60", "60", $v_NOM_ITEM_CONFIGURACAO), "left"); 

$aItemOption = Array();
$aItemOption[] = array("SEQ_TAREFA_TI", $pagina->iif($vOrderBy == "SEQ_TAREFA_TI","Selected", ""), "Tarefa");
$aItemOption[] = array("DAT_INICIO", $pagina->iif($vOrderBy == "DAT_INICIO","Selected", ""), "Data Início");
$aItemOption[] = array("DAT_FIM", $pagina->iif($vOrderBy == "DAT_FIM","Selected", ""), "Data Fim");

// Adicionar combo no formulário
$pagina->LinhaCampoFormulario("Ordenar lista por:", "right", "N", $pagina->CampoSelect("vOrderBy", "N", "", "N", $aItemOption), "left");
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();

	$pagina->LinhaVazia(1);
	
	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	
	$header[] = array("Tarefa", "73%"); 
	$header[] = array("Data Inicio", "15%"); 
	$header[] = array("Data Fim", ""); 

	// Setar variáveis 
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	
	
	$banco_timesheet->setSEQ_TIME_SHEET($v_SEQ_TIME_SHEET);
	$banco_timesheet->setSEQ_TAREFA_TI($v_SEQ_TAREFA_TI);
	$banco_timesheet->setDAT_INICIO($v_DAT_INICIO);
	$banco_timesheet->setDAT_FIM($v_DAT_FIM);		
	$banco_timesheet->selectParam($pagina->iif($vOrderBy == "", "SEQ_TIME_SHEET", $vOrderBy), $vNumPagina);
	print "quantidade".$banco_timesheet->database->rows;
	if($banco_timesheet->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco_timesheet->rowCount, $banco_timesheet->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Histórico Time Sheet", $header);
		$cont = 0;
		while ($row = pg_fetch_array($banco_timesheet->database->result)){ 	
		
			// Formata Data INICIO
			$dataInicio = substr($row["DAT_INICIO"],8,2)."/".substr($row["DAT_INICIO"],5,2)."/".substr($row["DAT_INICIO"],0,4);
			$dataFim = substr($row["DAT_FIM"],8,2)."/".substr($row["DAT_FIM"],5,2)."/".substr($row["DAT_FIM"],0,4);
			if ($dataInicio == "//") {
				$dataInicio = "";
			}
			if ($dataFim == "//") {
				$dataFim = "";
			}			
					
			$corpo[] = array("left", "campo", $row["SEQ_TAREFA_TI"]);
			$corpo[] = array("center", "campo", $dataInicio);
			$corpo[] = array("center", "campo", $dataFim);
			
			$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"location.href='Detalhe_Time_Sheet.php?v_SEQ_TIME_SHEET=".$row["SEQ_TIME_SHEET"]."';\"");
			$corpo = "";
			$cont++;
		}
	}	
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco_timesheet->rowCount, $banco_timesheet->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIME_SHEET=$v_SEQ_TIME_SHEET&v_SEQ_TAREFA_TI=$v_SEQ_TAREFA_TI&v_DAT_INICIO=$v_DAT_INICIO&v_DAT_FIM=$v_DAT_FIM");	
		
$pagina->MontaRodape(); 
?>
