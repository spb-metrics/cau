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

$pagina = new Pagina();
// Montando o PDF
class PDF extends FPDF { 
	var $cabecalho;     // cabecalho para as colunas 
	var $titulo;
	var $subtitulo;
	var $rodape;
	// Construtor: Chama a classe FPDF 
    function PDF($or = 'P') { 
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
	    $this->SetFont('Arial', 'B', 12);
		$this->SetY(0); 
	    $this->Cell(200, 35, $this->titulo,0,0,"C"); 
		
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
		/*
	    // Inserir o cabe�alho do grid se for o caso
	    $this->SetFont('Arial', '', 10); 
	    $this->SetX(10); 
	    $this->Cell($this->GetStringWidth($this->cabecalho), 5, $this->cabecalho, 0, 1); 
	    
		// Impimindo a linha de cabe�alho do grid
		$this->SetFont('Arial', '', 7); 
		$this->SetX(0);
		$this->Cell(0, 0, "", 0, 1, "C"); 
	    $this->Cell(5, 5, "Dia", 1, 0, "C"); 
	    $this->Cell(40, 5, "Nome", 1, 0, "C");
	    $this->Cell(7, 5, "Grau", 1, 0, "C");
	    $this->Cell(10, 5, "M�s", 1, 0, "C"); 
	    $this->Cell(23, 5, "C�digo", 1, 0, "C");
		$this->Cell(7, 5, "Qtd", 1, 0, "C");
		*/
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
	    $data = strftime("%d/%m/%Y �s %H:%m"); 
	    $this->Cell(200, 6, $this->rodape.$data, 0, 0, 'R'); 
    }
}

// ====================================================================================================================================
// Informa��es do corpo		  																										   
// ====================================================================================================================================
	
	$vTitulo = "GCE";

	
	$pdf = new PDF('P'); // P - Portrait | L - Landscape 
	// Nome - O que aparece no topo do documento
	$pdf->titulo = "Relat�rio de Item do Parque Tecnol�gico da INFRAERO";
	$pdf->subtitulo = $vTitulo;
	// Cabe�alho do grid do relat�rio
	$pdf->cabecalho = "";
	// Rodap� do documento
	$pdf->rodape = "Superintend�ncia de Tecnologia da Informa��o - PRTI - ";
	
    $pdf->Open(); 
    $pdf->AddPage(); 
	
    $pdf->SetFont('Arial', 'B', 11); 
	$pdf->Cell(0, 5, "", 0, 1);
	$pdf->Cell(100, 5, "Informa��es B�sicas:", 0, 0);
	
	$pdf->SetFont('Arial', '', 11); 
	
	
	
	$pdf->Output(); 
?>