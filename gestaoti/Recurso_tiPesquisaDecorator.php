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
require_once 'include/PHP/class/class.exportacao.php';
require_once 'include/PHP/GridMetaDados.php';
 
 
 
	$header = array();
	$header[] = array("Nome", "25%");
	$header[] = array("Perfil", "20%");
	$header[] = array("Lota��o", "10%");
	$header[] = array("Equipe", "20%");
	$header[] = array("Ramal", "10%");
	$header[] = array("Acesso", "10%");

	$banco = new Exportacao();
	$TIPO = $_REQUEST['tipo'];

	$value = base64_decode($_REQUEST[$_REQUEST['lookup']]);	
	//$value = base64_decode($_REQUEST['export']);
	$value = unserialize($value);

	$banco->getRows($value);
	 
	$corpo = array();
	
	$DADOS = new GridMetaDados(); 
	$DADOS->titulo = "Profissionais de TI";
	$DADOS->SetHeaders($header);
	 
	while ($row = pg_fetch_array($banco->database->result)){
		 
		$corpo[] = array("left", "campo", $row["nome"]);
		$corpo[] = array("left", "campo", $row["nom_perfil_recurso_ti"]);
		$corpo[] = array("center", "campo", $row["uor_sigla"]);
		$corpo[] = array("left", "campo", $row["nom_equipe_ti"]);
		$corpo[] = array("center", "campo", $row["num_voip"]);
		$corpo[] = array("left", "campo", $row["nom_perfil_acesso"]);
		
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
	 
	
?>
