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
// Chamados aguardando aprovação
// =======================================================================================================================
 
		$header = array();
		$header[] = array("Prioridade", "10%");
		$header[] = array("Chamado", "10%");
		$header[] = array("Atividade", "30%");
		$header[] = array("Solicitação", "");
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

				// Solicitação
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
