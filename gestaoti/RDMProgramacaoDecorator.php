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
	$header[] = array("Número", "10%");
	$header[] = array("Título", "30%");
	$header[] = array("Justificativa", "30%");
	$header[] = array("Tipo", "10%");
	//$header[] = array("Situação", "");	
	$header[] = array("Prevista execução", "10%");
	//$header[] = array("Resposável Checklist", "");
	
	$banco = new Exportacao();
	$TIPO = $_REQUEST['tipo'];
	
	$value = base64_decode($_REQUEST[$_REQUEST['lookup']]);	
		//$value = base64_decode($_REQUEST['export']);
	$value = unserialize($value);
	
	$banco->getRows($value);
	
	if($banco->database->rows > 0){
		$corpo = array();
		
		$DADOS = new GridMetaDados(); 
		$DADOS->titulo = "Calendário de Mudanças (PFM)";
		$DADOS->SetHeaders($header);
		
		while ($row = pg_fetch_array($banco->database->result)){ 
				
			// Número
			$corpo[] = array("right", "campo", $row["seq_rdm"]);			
			// Título
			$corpo[] = array("left", "campo",$row["titulo"]);
			// Justificativa
			$corpo[] = array("left", "campo",$row["justificativa"]);
			// Tipo
			$corpo[] = array("left", "campo", $RDM->getTipoDescricao($row["tipo"]));
			// Situação
			//$corpo[] = array("left", "campo", $situacaoRDM->getDescricao($row["situacao_atual"]));
			// Abertura
			$corpo[] = array("center", "campo", $row["data_hora_prevista_execucao"]);
			// Resposável Checklist
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