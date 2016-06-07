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
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.destino_triagem.php';
require_once 'include/PHP/class/class.prioridade_chamado.php';
require_once 'include/PHP/class/class.atribuicao_chamado.php';
require_once 'include/PHP/class/class.time_sheet.php';
require_once 'include/PHP/class/class.tipo_ocorrencia.php';
require_once 'include/PHP/class/class.exportacao.php';
require_once 'include/PHP/GridMetaDados.php';

$destino_triagem = new destino_triagem();
$tipo_chamado = new tipo_chamado();
$subtipo_chamado = new subtipo_chamado();
$situacao_chamado = new situacao_chamado();
$pagina = new Pagina();
//$banco = new chamado();
$empregados = new empregados();
$prioridade_chamado = new prioridade_chamado();
$atribuicao_chamado = new atribuicao_chamado();
$time_sheet = new time_sheet();
$chamado = new chamado();
 
// =======================================================================================================================
// Chamados aguardando aprova��o
// =======================================================================================================================
 
		$header = array();
		$header[] = array("Prioridade", "10%");
		$header[] = array("Chamado", "10%");
		$header[] = array("Atividade", "30%");
		$header[] = array("Solicita��o", "");
		$header[] = array("Abertura", "10%");

		$banco = new Exportacao();
		$TIPO = $_REQUEST['tipo'];
	
		$value = base64_decode($_REQUEST[$_REQUEST['lookup']]);	
		//$value = base64_decode($_REQUEST['export']);
		$value = unserialize($value);
	
		$banco->getRows($value);
		
		if($banco->database->rows > 0){
			
			$corpo = array();
			
			$DADOS = new GridMetaDados(); 
			$DADOS->titulo = "Chamados Aguardando Atendimento";
			$DADOS->SetHeaders($header);
			  
			 

			while ($row = pg_fetch_array($banco->database->result)){
				// Prioridade
				$prioridade_chamado = new $prioridade_chamado();
				$prioridade_chamado->select($row["seq_prioridade_chamado"]);
				$corpo[] = array("left", "campo", $prioridade_chamado->DSC_PRIORIDADE_CHAMADO);

				// Chamado
				$corpo[] = array("right", "campo", $row["seq_chamado"]);

				// Atividade
				$subtipo_chamado = new subtipo_chamado();
				$subtipo_chamado->select($row["seq_subtipo_chamado"]);
				$tipo_chamado = new tipo_chamado();
				$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
				$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);

				// Solicita��o
				$corpo[] = array("left", "campo", "de: <b>".$empregados->GetNomeEmpregado($row["num_matricula_solicitante"])."</b><br>".
												  $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

				// Abertura
				$corpo[] = array("center", "campo", $row["dth_abertura"]);

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
?>
