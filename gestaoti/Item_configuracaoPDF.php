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
		// Informações do cabecalho  																										   
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
		// Número de página no canto suprior direito
		$this->SetFont('Arial', '', 10); 
	    $this->SetX(-30); 
	    $this->Cell(30, 10, "Página: ".$this->PageNo()."/{nb}", 0, 1); // imprime página X/Total de Páginas 
		
		// Linha divisória do cabeçalho com o corpo do documento
	    $this->SetX(-10); 
	    $this->line(10, 26, $this->GetX(), 26); // Desenha uma linha 
		
		$this->SetY(24); 
		/*
	    // Inserir o cabeçalho do grid se for o caso
	    $this->SetFont('Arial', '', 10); 
	    $this->SetX(10); 
	    $this->Cell($this->GetStringWidth($this->cabecalho), 5, $this->cabecalho, 0, 1); 
	    
		// Impimindo a linha de cabeçalho do grid
		$this->SetFont('Arial', '', 7); 
		$this->SetX(0);
		$this->Cell(0, 0, "", 0, 1, "C"); 
	    $this->Cell(5, 5, "Dia", 1, 0, "C"); 
	    $this->Cell(40, 5, "Nome", 1, 0, "C");
	    $this->Cell(7, 5, "Grau", 1, 0, "C");
	    $this->Cell(10, 5, "Mês", 1, 0, "C"); 
	    $this->Cell(23, 5, "Código", 1, 0, "C");
		$this->Cell(7, 5, "Qtd", 1, 0, "C");
		*/
		if($this->PageNo() != 1) $this->Ln();
    } 
	// Rodapé : imprime a hora de impressao e Copyright 
    function Footer() { 
		// ====================================================================================================================================
		// Informações do rodapé	  																										   
		// ====================================================================================================================================
	    $this->SetXY(-10, -5); 
	    $this->line(10, $this->GetY()-2, $this->GetX(), $this->GetY()-2); 
	    $this->SetX(0); 
	    $this->SetFont('Courier', 'BI', 8); 
	    $data = strftime("%d/%m/%Y às %H:%m"); 
	    $this->Cell(200, 6, $this->rodape.$data, 0, 0, 'R'); 
    }
}

// ====================================================================================================================================
// Informações do corpo		  																										   
// ====================================================================================================================================
	
	$vTitulo = "GCE";

	
	$pdf = new PDF('P'); // P - Portrait | L - Landscape 
	// Nome - O que aparece no topo do documento
	$pdf->titulo = "Relatório de Item do Parque Tecnológico da INFRAERO";
	$pdf->subtitulo = $vTitulo;
	// Cabeçalho do grid do relatório
	$pdf->cabecalho = "";
	// Rodapé do documento
	$pdf->rodape = "Superintendência de Tecnologia da Informação - PRTI - ";
	
    $pdf->Open(); 
    $pdf->AddPage(); 
	
    $pdf->SetFont('Arial', 'B', 11); 
	$pdf->Cell(0, 5, "", 0, 1);
	$pdf->Cell(100, 5, "Informações Básicas:", 0, 0);
	
	$pdf->SetFont('Arial', '', 11); 
	
	
	
	$pdf->Output(); 
?>