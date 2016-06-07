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
require 'include/PHP/class/class.atribuicao_chamado.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$equipe_ti 		= new equipe_ti();
$banco 			= new atribuicao_chamado();
$banco2			= new atribuicao_chamado();
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

// Atribuição
$v_SEQ_EQUIPE_TI = 223;
$banco->setDTH_ATRIBUICAO($v_DTH_ATRIBUICAO);
$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);	

$banco->selectParam("SEQ_CHAMADO");

if($banco->database->rows >= 0){

	$hora_1 			= 0;
	$horas_2 			= 0;
	$horas_4			= 0;
	$horas_24			= 0;
	$horas_48			= 0;	
	$maior_horas_48		= 0;	
	$total_chamados		= 0;
	$seqChamado			= 0;	
		
	while ($row = oci_fetch_array($banco->database->result, OCI_BOTH)){	
	
		if($row['DTH_ENCERRAMENTO_EFETIVO'] != ""){
			
			if($row['SEQ_CHAMADO'] != $seqChamado ){
												
				$minAtribuicaoEmSegundo		= 0;
				$maxEncerramentoEmSegundo	= 0;
				$dthEncerramEfetivoVazio	= 0;
				$arrEncerramEfetivo 		= array();						
				
				$banco2->setSEQ_CHAMADO($row['SEQ_CHAMADO']);
				$banco2->setDTH_ATRIBUICAO($v_DTH_ATRIBUICAO);
				$banco2->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
				$banco2->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);	
				
				$banco2->selectParam("SEQ_CHAMADO");			
				
				while ($row2 = oci_fetch_array($banco2->database->result, OCI_BOTH)){
					
					$arrEncerramEfetivo[] = $row2['DTH_ENCERRAMENTO_EFETIVO'];
								
					if($row2['DTH_ENCERRAMENTO_EFETIVO'] != ""){				
												
						//Data da atribuição
						$dataHora 			= explode(' ',	$row['DTH_ATRIBUICAO']); 
					    $data 				= explode("/", 	$dataHora[0]); 
					    $hora 				= explode(":", 	$dataHora[1]); 					    
					    //Converte em segundos						
						@$horaDeAtribuicao 	= mktime ($hora[0], $hora[1], $hora[2], $data[1], $data[0], $data[2]);
						
						//Data da atribuição
						$dataHora2 			= explode(' ',	$row2['DTH_ATRIBUICAO']); 
					    $data2 				= explode("/", 	$dataHora2[0]); 
					    $hora2				= explode(":", 	$dataHora2[1]); 					    
					    //Converte em segundos						
						@$horaDeAtribuicao2 = mktime ($hora2[0], $hora2[1], $hora2[2], $data2[1], $data2[0], $data2[2]);
						
						//Recupera o menor valor em segundos
						if($horaDeAtribuicao > $horaDeAtribuicao2){
							
							$minAtribuicaoEmSegundo = $horaDeAtribuicao2;
							$horaDeAtribuicao 		= $horaDeAtribuicao;					
						}				
						
						//Data de encerramento efetivo
						$dataHora 				= explode(' ',	$row['DTH_ENCERRAMENTO_EFETIVO']); 
					    $data 					= explode("/", 	$dataHora[0]); 
					    $hora 					= explode(":", 	$dataHora[1]); 					    
					    //Converte em segundos						
						@$horaDeEncerradamento 	= mktime ($hora[0], $hora[1], $hora[2], $data[1], $data[0], $data[2]);
						
						
						//Data de encerramento efetivo
						$dataHora2 				= explode(' ',	$row2['DTH_ENCERRAMENTO_EFETIVO']); 
					    $data2 					= explode("/", 	$dataHora2[0]); 
					    $hora2 					= explode(":", 	$dataHora2[1]); 					    
					    //Converte em segundos						
						@$horaDeEncerradamento2	= mktime ($hora2[0], $hora2[1], $hora2[2], $data2[1], $data2[0], $data2[2]);
												
						//Recupera o maior valor em segundos
						if($horaDeEncerradamento < $horaDeEncerradamento2){
							
							$maxEncerramentoEmSegundo 	= $horaDeEncerradamento2;
							$horaDeEncerradamento		= $horaDeEncerradamento2;					
						}					

					}
				
				}
				
				foreach ($arrEncerramEfetivo as $v) {
					if($v == ""){
						$dthEncerramEfetivoVazio = 1;
						break;
					}
				}	
				
				if ($dthEncerramEfetivoVazio == 0) {				
					
					$total_chamados++;
					
					if($minAtribuicaoEmSegundo == 0 ){
						$minAtribuicaoEmSegundo = $horaDeAtribuicao;
					}
					
					if($maxEncerramentoEmSegundo == 0 ){
						$maxEncerramentoEmSegundo = $horaDeEncerradamento;
					}					
					//Quantidade em segundos
					$qtdEmSegundos = ($maxEncerramentoEmSegundo - $minAtribuicaoEmSegundo);				
					
					if($qtdEmSegundos <= 3600 ){
					$hora_1++;
					}
					elseif ($qtdEmSegundos > 3600 && $qtdEmSegundos <= 7200){
						$horas_2++;
					}
					elseif ($qtdEmSegundos > 7200 && $qtdEmSegundos <= 14400){
						$horas_4++;
					}
					elseif ($qtdEmSegundos > 14400 && $qtdEmSegundos <= 86400){
						$horas_24++;
					}
					elseif ($qtdEmSegundos > 86400 && $qtdEmSegundos <= 172800){
						$horas_48++;
					}
					else{
						$maior_horas_48++;
					}
				}	
			}			
			$seqChamado = $row['SEQ_CHAMADO'];		
		}		
	}			

	//Transforma a qtd de horas em porcentagem
	@$pct_1			= round((($hora_1   *100)/$total_chamados), 1);
	@$pct_2 		= round((($horas_2  *100)/$total_chamados), 1);
	@$pct_4 		= round((($horas_4  *100)/$total_chamados), 1);
	@$pct_24 		= round((($horas_24 *100)/$total_chamados), 1);
	@$pct_48 		= round((($horas_48 *100)/$total_chamados), 1);			
	@$pct_maior_48 	= round((($maior_horas_48*100)/$total_chamados), 1);
	
	$horas2 		= ($hora_1 	+ $horas_2);	
	$horas4 		= ($horas2 	+ $horas_4);	
	$horas24		= ($horas4 	+ $horas_24);	
	$horas48 		= ($horas24 + $horas_48);				
	
	@$pct2 		= round(($horas2  *100)/$total_chamados, 1);
	@$pct4 		= round(($horas4  *100)/$total_chamados, 1);	
	@$pct24		= round(($horas24 *100)/$total_chamados, 1);	
	@$pct48 	= round(($horas48 *100)/$total_chamados, 1);	
			
}	
	
$v_SEQ_EQUIPE_TI = 223;
$banco3	= new atribuicao_chamado();
$banco3->setDTH_ATRIBUICAO($v_DTH_ATRIBUICAO);
$banco3->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
$banco3->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);

$banco3->selectParam("SEQ_CHAMADO");
			
if($banco3->database->rows >= 0){	
	$seqChamado = 0;
	$totalDeChamadosAbertos	= 0;
	
	while ($row3 = oci_fetch_array($banco3->database->result, OCI_BOTH)){	
		
		if($row3['SEQ_CHAMADO'] != $seqChamado){
			$totalDeChamadosAbertos++;					
		}		
		$seqChamado = $row3['SEQ_CHAMADO'];
	}		
}	
	
$pdf->SetFillColor(202,202,202);
$pdf->SetFontSize(12);
$pdf->SetFont('arial', 'B');
$pdf->Cell(0, 5, "", 0, 1);
$pdf->Cell(0, 5, "", 0, 0, "C", 0);
	
$pdf->SetFillColor(300,300,300);
$pdf->Ln(2);
$pdf->Cell(300, 5, "Ordens de Serviço por Serviço : TIST-2", 0, 0, "C", 1);
$pdf->SetFont('arial');
$pdf->Ln(6);
$pdf->Cell(300, 5, "Ordens de Serviço Abertas entre $v_DTH_ATRIBUICAO e $v_DTH_ENCERRAMENTO_EFETIVO", 0, 0, "C", 1);
$pdf->Ln(12);	
	
$pdf->SetFillColor(0,0,0);
$pdf->Cell(277, 0, "", 0, 1, "C", 1);
	
$pdf->SetX(-10);
$pdf->line(10, 26, $pdf->GetX(), 26); // Desenha uma linha
$pdf->SetY(24);	
$pdf->Ln(28);
	
$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', 	'B', 11);
$pdf->Cell(277, 5, "Total de chamados abertos no período : $totalDeChamadosAbertos", 0, 1, "L", 1);
	
$pdf->Cell(277, 5, "Total de chamados concluídos : $total_chamados                                                                                            Meta Alcançada(%)                                  Meta Exigida(%)", 0, 1, "L", 1);
	
$pdf->Ln(3);
$pdf->SetFont('arial', 	'', 12);
	
$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 8, "Total de chamados concluídos (em até 1 hora ):                                $hora_1  ", 0, 0, "L", 1);
		
if(@$pct_1 < 30){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct_1, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct_1, 0, 0, "L", 1);	
	$pdf->SetTextColor(0,0,0);	
}
			
$pdf->Cell(72, 8, ">= 30                 ", 0, 1, "R", 1);

$pdf->SetFillColor(225,225,225);
$pdf->Cell(175, 8, "Total de chamados concluídos (em até 2 horas ):                              $horas2  ", 0, 0, "L", 1);
		
if($pct2  < 30){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct2, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct2, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);	
}
		
$pdf->Cell(72, 8, ">= 30                 ", 0, 1, "R", 1);	

$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 8, "Total de chamados concluídos (em até 4 horas ):                              $horas4  ", 0, 0, "L", 1);
		
if($pct4  < 40){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct4, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct4, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);	
}
		
$pdf->Cell(72, 8, ">= 40                 ", 0, 1, "R", 1);	

$pdf->SetFillColor(225,225,225);
$pdf->Cell(175, 8, "Total de chamados concluídos (em até 24 horas ):                            $horas24  ", 0, 0, "L", 1);
	
if($pct24 < 45){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct24, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct24, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);	
}
		
$pdf->Cell(72, 8, ">= 45                 ", 0, 1, "R", 1);

$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 8, "Total de chamados concluídos (em até 48 horas ):                            $horas48  ", 0, 0, "L", 1);
	
if($pct48 < 50){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct48, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct48, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);	
}
		
$pdf->Cell(72, 8, ">= 50                 ", 0, 1, "R", 1);
	
$pdf->SetFillColor(225,225,225);
$pdf->Cell(175, 8, "Total de chamados concluídos (após 48 horas ):                               $maior_horas_48  ", 0, 0, "L", 1);	
		
$pdf->SetTextColor(0,0,0);
$pdf->Cell(30, 8, $pct_maior_48, 0, 0, "L", 1);	
$pdf->SetTextColor(0,0,0);	

$pdf->Cell(72, 8, " -                   ", 0, 1, "R", 1);	
$pdf->Ln(8);

$pdf->SetFillColor(0,0,0);
$pdf->Cell(277, 0, "", 0, 1, "C", 1);
	
$pdf->Ln(2);
$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', '', 9);
$pdf->Cell(80, 8, "Contrato: TC 135 PS/2008/0001", 1, 1, "C", 1);
	
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
$pdf->Ln(12);	
	
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', 	'', 11);
$pdf->Cell(138, 9, "______________________________", 0, 0, "C", 1);
$pdf->Cell(139, 9, "______________________________", 0, 1, "C", 1);	
$pdf->Cell(138, 3, "Preposto / Ziva", 0, 0, "C", 1);
$pdf->Cell(139, 3, "Fiscal do Contrato", 0, 1, "C", 1);	
$pdf->Cell(138, 17, "                                Data:___/___/____", 0, 0, "L", 1);	
$pdf->Cell(139, 17, "                                 Data:___/___/____", 0, 1, "L", 1);	
	
$pdf->Output();

?>