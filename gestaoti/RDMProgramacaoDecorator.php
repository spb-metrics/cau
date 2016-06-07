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
require_once 'include/PHP/class/class.situacao_rdm.php';
require_once 'include/PHP/class/class.util.php';
require_once 'include/PHP/class/class.exportacao.php';
require_once 'include/PHP/GridMetaDados.php';

$pagina = new Pagina();
$RDM = new rdm();
$situacaoRDM = new situacao_rdm();



// =======================================================================================================================
// PESQUISAR RDMs
// =======================================================================================================================

	$header = array();
	//$header[] = array("&nbsp;", "5%");
	$header[] = array("N�mero", "10%");
	$header[] = array("T�tulo", "30%");
	$header[] = array("Justificativa", "30%");
	$header[] = array("Tipo", "10%");
	//$header[] = array("Situa��o", "");	
	$header[] = array("Prevista execu��o", "10%");
	//$header[] = array("Respos�vel Checklist", "");
	
	$banco = new Exportacao();
	$TIPO = $_REQUEST['tipo'];
	
	$value = base64_decode($_REQUEST[$_REQUEST['lookup']]);	
		//$value = base64_decode($_REQUEST['export']);
	$value = unserialize($value);
	
	$banco->getRows($value);
	
	if($banco->database->rows > 0){
		$corpo = array();
		
		$DADOS = new GridMetaDados(); 
		$DADOS->titulo = "Calend�rio de Mudan�as (PFM)";
		$DADOS->SetHeaders($header);
		
		while ($row = pg_fetch_array($banco->database->result)){ 
				
			// N�mero
			$corpo[] = array("right", "campo", $row["seq_rdm"]);			
			// T�tulo
			$corpo[] = array("left", "campo",$row["titulo"]);
			// Justificativa
			$corpo[] = array("left", "campo",$row["justificativa"]);
			// Tipo
			$corpo[] = array("left", "campo", $RDM->getTipoDescricao($row["tipo"]));
			// Situa��o
			//$corpo[] = array("left", "campo", $situacaoRDM->getDescricao($row["situacao_atual"]));
			// Abertura
			$corpo[] = array("center", "campo", $row["data_hora_prevista_execucao"]);
			// Respos�vel Checklist
			//$corpo[] = array("left", "campo", $row["nome_resp_checklist"]);		
			
			
			$DADOS->addRow($corpo);
			
			$corpo = "";	 
		
		} 
		
		if("XLS" == $TIPO){
			require_once 'include/PHP/XLSHandler.php';			 
			$XLSHandler = new XLSHandler($DADOS);
			$XLSHandler->output(); 
		}else if("PDF" == $TIPO){	 
			//require_once 'include/PHP/PDFHandler.php';
			require_once 'include/PHP/GridFPDF.php';
			$pdf = new GridFPDF($DADOS);
			$pdf->SetFont('Arial','',10);
			$pdf->AddPage("L");
			$pdf->BasicTable();
			$pdf->Output("arquivo.pdf","I");	
		}
		
}
$pagina->MontaRodape();