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

require_once 'include/PHP/class/class.tarefa.php';
require_once 'include/PHP/class/class.time_sheet.php';

$pagina = new Pagina();
$banco_tarefa = new tarefa();
$banco_timesheet = new time_sheet();

// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Time Sheet"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("#", "tabact", "Pesquisa")
				    );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", "");

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

// Buscar dados da tabela externa
$pagina->LinhaCampoFormulario("Tarefa:", "right", "N", $pagina->CampoTexto("v_SEQ_TAREFA_TI", "N", "", "30", "30", $v_SEQ_TAREFA_TI), "left"); 

//$pagina->LinhaCampoFormulario("Descri��o:", "right", "N", $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO", "N", "Nome", "60", "60", $v_NOM_ITEM_CONFIGURACAO), "left"); 

$aItemOption = Array();
$aItemOption[] = array("SEQ_TAREFA_TI", $pagina->iif($vOrderBy == "SEQ_TAREFA_TI","Selected", ""), "Tarefa");
$aItemOption[] = array("DAT_INICIO", $pagina->iif($vOrderBy == "DAT_INICIO","Selected", ""), "Data In�cio");
$aItemOption[] = array("DAT_FIM", $pagina->iif($vOrderBy == "DAT_FIM","Selected", ""), "Data Fim");

// Adicionar combo no formul�rio
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

	// Setar vari�veis 
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
		$pagina->LinhaHeaderTabelaResultado("Hist�rico Time Sheet", $header);
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
