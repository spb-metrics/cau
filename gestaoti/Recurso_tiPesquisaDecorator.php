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
require_once 'include/PHP/class/class.exportacao.php';
require_once 'include/PHP/GridMetaDados.php';
 
 
 
	$header = array();
	$header[] = array("Nome", "25%");
	$header[] = array("Perfil", "20%");
	$header[] = array("Lotação", "10%");
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
