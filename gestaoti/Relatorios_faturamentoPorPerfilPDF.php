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
require "include/PHP/FPDF/fpdf.php";
require 'include/PHP/class/class.time_sheet.php';
require 'include/PHP/class/class.perfil_recurso_ti.php';
require 'include/PHP/class/class.recurso_ti.php';

$pagina = new Pagina();

/*TODO: NOVO PERFIL ACESSO*/
//if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
if($pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"]) || $pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){
	// Usu�rio pode ver tudo

}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Lider de equipe pode ver todas da sua equipe
	if($v_SEQ_EQUIPE_TI == ""){
		$pagina->ScriptAlert("Acesso n�o permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
}else{ // Colaborador ve somente o seu
	if($v_SEQ_EQUIPE_TI == ""){
		$pagina->ScriptAlert("Acesso n�o permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
	if($v_NUM_MATRICULA_RECURSO == ""){
		$pagina->ScriptAlert("Acesso n�o permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
}

// Montando o PDF
class PDF extends FPDF {
	var $cabecalho;     // cabecalho para as colunas
	var $titulo;
	var $subtitulo;
	var $rodape;
	// Construtor: Chama a classe FPDF
    function PDF($or = 'L') {
        $this->FPDF($or);
    }
	// define o cabecalho
    function SetCabecalho($cab) {
        $this->cabecalho = $cab;
    }
	// nomeia o relatorio
    function SetName($nomerel) {
        $this->nome = $nomerel;
    }
    
	function Header() {
		// ====================================================================================================================================
		// Informa��es do cabecalho
		// ====================================================================================================================================
		$this->AliasNbPages(); // Define o numero total de paginas para a macro {nb}
		// Logo do canto superior do PDF
		$this->Image('imagens/logo_infraero.jpg', 5, 9, 40, 11, "JPG"); // importa uma imagem

		// Nome do documento
	    $this->SetFont('Arial', 'B', 14);
		$this->SetY(0);
	    $this->Cell(277, 35, $this->titulo,0,0,"C");

		// subtitulo
		if($this->subtitulo != ""){
			$this->SetFont('Arial', 'B', 12);
			$this->Cell(0, 5, "", 0, 1);
			$this->Cell(200, 35, $this->subtitulo,0,0,"C");
		}
		// N�mero de p�gina no canto suprior direito
		$this->SetFont('Arial', '', 10);
	    $this->SetX(-30);
	    $this->Cell(30, 10, "P�gina: ".$this->PageNo()."/{nb}", 0, 1); // imprime p�gina X/Total de P�ginas

		// Linha divis�ria do cabe�alho com o corpo do documento
	    $this->SetX(-10);
	    $this->line(10, 26, $this->GetX(), 26); // Desenha uma linha

		$this->SetY(24);
		
		if($this->PageNo() != 1) $this->Ln();
    }
	// Rodap� : imprime a hora de impressao e Copyright
    function Footer() {
		// ====================================================================================================================================
		// Informa��es do rodap�
		// ====================================================================================================================================
	    $this->SetXY(-10, -5);
	    $this->line(10, $this->GetY()-2, $this->GetX(), $this->GetY()-2);
	    $this->SetX(0);
	    $this->SetFont('Courier', 'BI', 8);
	    $data = strftime("%d/%m/%Y �s %H:%M");
	    $this->Cell(200, 6, $this->rodape.$data, 0, 0, 'R');
    }
}

// ============================================================================================================================================
// Informa��es do corpo
// ============================================================================================================================================

	$pdf = new PDF('P'); // P - Portrait | L - Landscape
	// Nome - O que aparece no topo do documento
	$pdf->titulo = "Faturamento por Perfil";	
	// Cabe�alho do grid do relat�rio
	$pdf->cabecalho = "";
	// Rodap� do documento
	$pdf->rodape = "Superintend�ncia de Tecnologia da Informa��o - PRTI - ";

    $pdf->Open();
    $pdf->AddPage();

	$pdf->SetFont('Arial', '', 11);	

	$perfil_recurso_ti = new perfil_recurso_ti();
	$perfil_recurso_ti->setSEQ_PERFIL_RECURSO_TI($v_SEQ_PERFIL_RECURSO_TI);
	$perfil_recurso_ti->selectParam("NOM_PERFIL_RECURSO_TI");	
	
	$pdf->SetFillColor(202,202,202);
	$pdf->SetFontSize(9);
	$pdf->Cell(0, 5, "", 0, 1);
	$pdf->Cell(0, 5, "", 0, 0, "C", 0);
	$pdf->Ln(1);
	$pdf->Cell(40, 5, "Cargos",1, 0, "C", 1);			
	$pdf->Cell(20, 5, "Q. Vagas", 1, 0, "C", 1);
	$pdf->Cell(35, 5, "Valor mensal por posto", 1, 0, "C", 1);
	$pdf->Cell(30, 5, "Valor por dia (21)", 1, 0, "C", 1);
	$pdf->Cell(30, 5, "Valor por hora", 1, 0, "C", 1);
	$pdf->Cell(35, 5, "Valor global mensal", 1, 0, "C", 1);
	$pdf->Ln(6);	
	
	if($perfil_recurso_ti->database->rows > 0){
		while ($row = oci_fetch_array($perfil_recurso_ti->database->result, OCI_BOTH)){

			// Imprimir o cabe�alho da equipe de TI	
									
			$pdf->SetFontSize(7);		
			$pdf->Cell(0, 1, "", 0, 1);
			$pdf->SetFillColor(300,300,300);
			$pdf->Cell(40, 5, $row["NOM_PERFIL_RECURSO_TI"], 1, 0, "left", 1);

			//Q. vagas		
			$pdf->Cell(20, 5, 1, 1, 0, "C", 1);
			
			//valor mensal por posto
			$pdf->Cell(35, 5, number_format( ( 21*(8*$row["VAL_HORA"])), 2, ',', ' '), 1, 0, "C", 1);
			
			//valor por dia 21
			$pdf->Cell(30, 5, number_format(( 8*$row["VAL_HORA"]), 2, ',', ' '), 1, 0, "C", 1);
			
			//valor por hora
			$pdf->Cell(30, 5, number_format( $row["VAL_HORA"], 2, ',', ' '), 1, 0, "C", 1);
			
			//valor global mensal
			$pdf->Cell(35, 5, number_format( 2, 2, ',', ' '), 1, 0, "C", 1);
			
			//$pdf->SetFillColor(255,255,255);
						
			
			$pdf->MultiCell(100,5,"");
		}
	}
	$pdf->Output();
?>