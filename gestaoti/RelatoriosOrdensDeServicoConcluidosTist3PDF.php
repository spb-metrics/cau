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
/******************************************************************
 * Área de inclusão dos arquivos que serão utilizados nesta página.
 *****************************************************************/
require "include/PHP/FPDF/fpdf.php";
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.chamado.php';
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.tipo_chamado.php';
require 'include/PHP/class/class.equipe_ti.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$equipe_ti 	= new equipe_ti();
$banco 		= new chamado();
/*****************************************************************/

// Montando o PDF
class PDF extends FPDF {
	var $cabecalho;     // cabecalho para as colunas
	var $titulo;
	var $titulo2;	
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
		// Informações do cabecalho
		// ====================================================================================================================================
		$this->AliasNbPages(); // Define o numero total de paginas para a macro {nb}
		// Logo do canto superior do PDF
		$this->Image('imagens/logo_infraero.jpg', 5, 9, 40, 11, "JPG"); // importa uma imagem

		// Nome do documento
	    $this->SetFont('Arial', 'B', 12);
		$this->SetY(0);
	    $this->Cell(300, 35, $this->titulo,0,0,"C");
	    
	    // Nome do documento
	    $this->SetFont('Arial', 'B', 12);
		$this->SetY(0);
		$this->Ln(5);
	    $this->Cell(300, 35, $this->titulo2,0,0,"C");

		// subtitulo
		if($this->subtitulo != ""){
			$this->SetFont('Arial', 'B', 12);
			$this->Cell(0, 5, "", 0, 1);
			$this->Cell(200, 35, $this->subtitulo,0,0,"C");
		}		

		// Linha divisória do cabeçalho com o corpo do documento
	    $this->SetX(-10);
	    $this->line(10, 26, $this->GetX(), 26); // Desenha uma linha
		$this->SetY(24);
		
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
	    $data = strftime("%d/%m/%Y às %H:%M");
	    $this->Cell(200, 6, $this->rodape.$data, 0, 0, 'R');
    }
}

// ====================================================================================================================================
// Informações do corpo
// ====================================================================================================================================

$pdf = new PDF('L'); // P - Portrait | L - Landscape
// Nome - O que aparece no topo do documento
$pdf->titulo = "Empresa Brasileira de Infra-estrutura Aeroportuária";
$pdf->titulo2 = "SUPERINTENDENCIA DE TECNOLOGIA DA INFORMAÇÃO - PRTI";	
// Cabeçalho do grid do relatório
$pdf->cabecalho = "Empresa Brasileira de Infra-estrutura Aeroportuária";
// Rodapé do documento
$pdf->rodape = "Superintendência de Tecnologia da Informação - PRTI ";
$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);			
	
$v_SEQ_EQUIPE_TI = 141;
		
// Atribuição
$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
$banco->setCOD_DEPENDENCIA_ATRIBUICAO($v_COD_DEPENDENCIA_ATRIBUICAO);	
$banco->setCOD_SLA_ATENDIMENTO($v_COD_SLA_ATENDIMENTO);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	
$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
	
if($v_SEQ_EQUIPE_TI != "" || $v_COD_DEPENDENCIA_ATRIBUICAO != ""){		
	$banco->AtenderChamados("DTH_ABERTURA", $vNumPagina, 200000);
}else{
	$banco->selectParam("DTH_ABERTURA", $vNumPagina, 200000);
}	
	
if($banco->database->rows >= 0){
		
	$minutos_15			= 0;	
	$hora_1 			= 0;
	$horas_4			= 0;
	$horas_5 			= 0;
	$maior_horas_5		= 0;
	$horas_12			= 0;		
	$total_chamados		= 0;	
		
	while ($row = oci_fetch_array($banco->database->result, OCI_BOTH)){	
			
		$total_chamados++;		
			
		//Data de abertura
		$_split_datehour = explode(' ',$row['DTH_ABERTURA']); 
	    $_split_data = explode("/", $_split_datehour[0]); 
	    $_split_hour = explode(":", $_split_datehour[1]); 
		//Converte em segundos
		$horaDeAbertura = mktime ($_split_hour[0], $_split_hour[1], $_split_hour[2], $_split_data[1], $_split_data[2], $_split_data[0]); 
					
		//Data de encerramento
		$_split_datehour2 = explode(' ',$row['DTH_ENCERRAMENTO_EFETIVO']); 
	    $_split_data2 = explode("/", $_split_datehour2[0]); 
	    $_split_hour2 = explode(":", $_split_datehour2[1]); 
		//Converte em segundos
		$horaDeEncerradamento =  mktime ($_split_hour2[0], $_split_hour2[1], $_split_hour2[2], $_split_data2[1], $_split_data2[2], $_split_data2[0]); 
					
		$qtdEmSegundos = ($horaDeEncerradamento - $horaDeAbertura);			
			
		if($qtdEmSegundos <= 900 ){
			$minutos_15++;
		}
		elseif($qtdEmSegundos > 900 && $qtdEmSegundos <= 3600 ){
			$hora_1++;
		}	
		elseif ($qtdEmSegundos > 7200 && $qtdEmSegundos <= 14400){
			$horas_4++;
		}
		elseif ($qtdEmSegundos > 14400 && $qtdEmSegundos <= 18000){
			$horas_5++;
		}
		elseif ($qtdEmSegundos > 18000 && $qtdEmSegundos <= 43200){
			$horas_12++;
		}
		else{
			$maior_horas_5++;
		}				
	}		

	@$pct_15		= round((($minutos_15	*100)/$total_chamados), 1);		
	@$pct_1			= round((($hora_1		*100)/$total_chamados), 1);
	@$pct_4 		= round((($horas_4		*100)/$total_chamados), 1);		
	@$pct_5 		= round((($horas_5		*100)/$total_chamados), 1);		
	@$pct_12 		= round((($horas_12		*100)/$total_chamados), 1);					
	@$pct_maior_5	= round((($maior_horas_5*100)/$total_chamados), 1);
		
	$hora1 			= ($minutos_15 	+ $hora_1);		
	$horas4 		= ($hora1 		+ $horas_4);	
	$horas5			= ($horas4 		+ $horas_4);	
	$horas12 		= ($horas5 		+ $horas_12);		
		
	@$pct1 		= round(($hora1  	*100)/$total_chamados, 1);
	@$pct4 		= round(($horas4 	*100)/$total_chamados, 1);	
	@$pct5		= round(($horas5	*100)/$total_chamados, 1);	
	@$pct12 	= round(($horas12	*100)/$total_chamados, 1);
					
}
		
$pdf->SetFillColor(202,202,202);
$pdf->SetFontSize(12);
$pdf->SetFont('arial', 'B');
$pdf->Cell(0, 5, "", 0, 1);
$pdf->Cell(0, 5, "", 0, 0, "C", 0);
	
$pdf->SetFillColor(300,300,300);
$pdf->Ln(4);
$pdf->Cell(300, 5, "Ordens de Serviço por Serviço : TIST-3", 0, 0, "C", 1);
$pdf->SetFont('arial');
$pdf->Ln(6);
$pdf->Cell(300, 5, "Ordens de Serviço Abertas entre $v_DTH_ABERTURA e $v_DTH_ENCERRAMENTO_EFETIVO_FINAL", 0, 0, "C", 1);
$pdf->Ln(12);	
	
$pdf->SetFillColor(0,0,0);
$pdf->Cell(277, 0, "", 0, 1, "C", 1);
	
$pdf->SetX(-10);
$pdf->line(10, 26, $pdf->GetX(), 26); // Desenha uma linha
$pdf->SetY(24);	
$pdf->Ln(28);
	
$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', 	'B', 11);
	
$pdf->Ln(2);
$pdf->Cell(277, 5, "Total de chamados concluídos : $total_chamados                                                                                            Meta Alcançada(%)                                  Meta Exigida(%)", 0, 1, "L", 1);
	
$pdf->Ln(2);
$pdf->SetFont('arial', 	'', 12);
	
$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 8, "Total de chamadas concluídas (em até 15 minutos ):                           $minutos_15 ", 0, 0, "L", 1);	
	
if(@$pct_15 < 83){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct_15, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}else{
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct_15, 0, 0, "L", 1);	
	$pdf->SetTextColor(0,0,0);	
}			
		
$pdf->Cell(72, 8, ">= 83                 ", 0, 1, "R", 1);	
$pdf->SetFillColor(225,225,225);
$pdf->Cell(175, 8, "Total de chamadas concluídas (em até 1 horas ):                                 $hora1  ", 0, 0, "L", 1);		
	
if(@$pct1 < 87){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct1, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct1, 0, 0, "L", 1);	
	$pdf->SetTextColor(0,0,0);	
}		
	
$pdf->Cell(72, 8, ">= 87                 ", 0, 1, "R", 1);	
$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 8, "Total de chamadas concluídas (em até 4 horas ):                                 $horas4  ", 0, 0, "L", 1);	
	
if($pct4  < 90){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct4, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct4, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);	
}	
	
$pdf->Cell(72, 8, ">= 90                 ", 0, 1, "R", 1);	
$pdf->SetFillColor(225,225,225);
$pdf->Cell(175, 8, "Total de chamadas concluídas (em até 5 horas ):                                 $horas5  ", 0, 0, "L", 1);	
	
if($pct1 != 100){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct5, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct1, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);	
}	
	
$pdf->Cell(72, 8, "= 100               ", 0, 1, "R", 1);	
$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 8, "Total de chamadas concluídas (em até 12 horas ):                               $horas12  ", 0, 0, "L", 1);			
	
if($pct12 < 95){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct12, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, "$pct12 ", 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);	
}
			
$pdf->Cell(72, 8, ">= 95                 ", 0, 1, "R", 1);		
$pdf->SetFillColor(225,225,225);
$pdf->Cell(175, 8, "Total de chamadas concluídas (após 5 horas ):                                    $maior_horas_5  ", 0, 0, "L", 1);	
$pdf->Cell(30, 8, "$pct_maior_5 ", 0, 0, "L", 1);	
$pdf->Cell(72, 8, "-                   ", 0, 1, "R", 1);	
$pdf->Ln(8);
	
$pdf->SetFillColor(0,0,0);
$pdf->Cell(277, 0, "", 0, 1, "C", 1);
		
$pdf->Ln(3);	
$pdf->SetFillColor(300,300,300);
$pdf->SetFillColor(300,000,000);
$pdf->Cell(7, 3, " ", 0, 0, "L", 1);
$pdf->SetFillColor(300,300,300);
$pdf->SetTextColor(300,000,000);
$pdf->SetFont('arial', 'B', 7);
$pdf->Cell(7, 3, "Meta abaixo da exigida", 0, 1, "L", 1);
	
$pdf->SetFillColor(300,300,300);
$pdf->SetFillColor(000,000,300);
$pdf->Cell(7, 3, " ", 0, 0, "L", 1);
$pdf->SetFillColor(300,300,300);
$pdf->SetTextColor(000,000,300);
$pdf->SetFont('arial', 'B', 7);
$pdf->Cell(7, 3, "Meta atendida", 0, 1, "L", 1);	
$pdf->Ln(15);	
	
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', 	'', 11);
$pdf->Cell(138, 9, "______________________________", 0, 0, "C", 1);
$pdf->Cell(139, 9, "______________________________", 0, 1, "C", 1);	
$pdf->Cell(138, 3, "GESTOR DO CONTRATO", 0, 0, "C", 1);
$pdf->Cell(139, 3, "FISCAL DO CONTRATO", 0, 1, "C", 1);	
$pdf->Cell(138, 17, "                                Data:___/___/____", 0, 0, "L", 1);	
$pdf->Cell(139, 17, "                                 Data:___/___/____", 0, 1, "L", 1);	
	
$pdf->Output();

?>