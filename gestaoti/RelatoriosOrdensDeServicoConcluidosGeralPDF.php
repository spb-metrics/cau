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
require_once 'include/PHP/class/class.parametro.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$equipe_ti 	= new equipe_ti();
$banco 		= new chamado();
$banco2		= new chamado();
$parametro	= new parametro();
/*****************************************************************/
//Recuperando o nome da equipe pelo código
$equipe_ti->select($v_SEQ_EQUIPE_TI);
$executor_nom_equipe_ti = $equipe_ti->getNOM_EQUIPE_TI();

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
$pdf->titulo  = $parametro->GetValorParametro("NOM_INSTITUICAO");
$pdf->titulo2 = $parametro->GetValorParametro("NOM_AREA_TI");
// Cabeçalho do grid do relatório
$pdf->cabecalho = $pdf->titulo;
// Rodapé do documento
$pdf->rodape = $pdf->titulo2;
$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);

// Atribuição
$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
$banco->setDTH_ABERTURA($v_DTH_ABERTURA);

$banco->AtenderChamados("DTH_ABERTURA");

	if($banco->database->rows >= 0){

		$horas_4			= 0;
		$horas_8 			= 0;
		$horas_24			= 0;
		$horas_48			= 0;
		$maior_horas_48		= 0;
		$total_horas		= 0;

		while ($row = pg_fetch_array($banco->database->result)){

			$total_horas++;

			//Data de abertura
			$_split_datehour = explode(' ',$row['dth_abertura']);
		    $_split_data = explode("/", $_split_datehour[0]);
		    $_split_hour = explode(":", $_split_datehour[1]);
			//Converte em segundos
			@$horaDeAbertura = mktime ($_split_hour[0], $_split_hour[1], $_split_hour[2], $_split_data[1], $_split_data[2], $_split_data[0]);

			//Data de encerramento
			$_split_datehour2 = explode(' ',$row['dth_encerramento_efetivo']);
		    $_split_data2 = explode("/", $_split_datehour2[0]);
		    $_split_hour2 = explode(":", $_split_datehour2[1]);
			//Converte em segundos
			@$horaDeEncerradamento =  mktime ($_split_hour2[0], $_split_hour2[1], $_split_hour2[2], $_split_data2[1], $_split_data2[2], $_split_data2[0]);

			$qtdEmSegundos = ($horaDeEncerradamento - $horaDeAbertura);

			if ($qtdEmSegundos <= 14400 ){
				$horas_4++;
			}
			elseif ($qtdEmSegundos > 14400 && $qtdEmSegundos <= 28800){
				$horas_8++;
			}
			elseif ($qtdEmSegundos > 28800 && $qtdEmSegundos <= 86400){
				$horas_24++;
			}
			elseif ($qtdEmSegundos > 86400 && $qtdEmSegundos <= 172800){
				$horas_48++;
			}
			else{
				$maior_horas_48++;
			}
		}
		//Transforma a qtd de horas em porcentagem
		@$pct_4 		= round((($horas_4 *100)/$total_horas), 1);
		@$pct_8 		= round((($horas_2 *100)/$total_horas), 1);
		@$pct_24 		= round((($horas_24*100)/$total_horas), 1);
		@$pct_48 		= round((($horas_48*100)/$total_horas), 1);
		@$pct_maior_48 	= round((($maior_horas_48*100)/$total_horas), 1);

		$pct8 		 	= ($pct_4 + $pct_8);
		$pct24 		 	= ($pct8  + $pct_24);
		$pct48 		 	= ($pct24 + $pct_48);

		$horas8 		= ($horas_4  + $horas_8);
		$horas24		= ($horas8   + $horas_24);
		$horas48 		= ($horas24  + $horas_48);
	}
	//Recuperando a data de encerramento efetivo final
	$v_DTH_ABERTURA_FINAL = $v_DTH_ENCERRAMENTO_EFETIVO_FINAL;

	// Atribuição
	$banco2->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	$banco2->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco2->setDTH_ABERTURA($v_DTH_ABERTURA);
	$banco2->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);

	$banco2->AtenderChamados("DTH_ABERTURA", $vNumPagina, 200000);

	if($banco2->database->rows >= 0){
		$totalDeChamadosAbertos	= 0;

		while ($row2 = pg_fetch_array($banco2->database->result)){
			$totalDeChamadosAbertos++;
		}
	}

$pdf->SetFillColor(202,202,202);
$pdf->SetFontSize(12);
$pdf->SetFont('arial', 'B');
$pdf->Cell(0, 5, "", 0, 1);
$pdf->Cell(0, 5, "", 0, 0, "C", 0);

//$pdf->SetFillColor(300,300,300);
$pdf->Ln(2);
$pdf->Cell(300, 5, "Ordens de Serviço por Serviço : $executor_nom_equipe_ti", 0, 0, "C", 1);
$pdf->SetFont('arial');
$pdf->Ln(6);
$pdf->Cell(300, 5, "Ordens de Serviço Abertas entre $v_DTH_ABERTURA e $v_DTH_ENCERRAMENTO_EFETIVO_FINAL", 0, 0, "C", 1);
$pdf->Ln(12);

//$pdf->SetFillColor(0,0,0);
$pdf->Cell(277, 0, "", 0, 1, "C", 1);

$pdf->SetX(-10);
$pdf->line(10, 26, $pdf->GetX(), 26); // Desenha uma linha
$pdf->SetY(24);
$pdf->Ln(28);

//$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', 	'B', 11);
$pdf->Cell(277, 5, "Total de chamados abertos no período : $totalDeChamadosAbertos", 0, 1, "L", 1);

$pdf->Cell(277, 5, "Total de chamados concluídos : $total_horas                                                                                            Meta Alcançada(%)                                  Meta Exigida(%)", 0, 1, "L", 1);
$pdf->Ln(3);
$pdf->SetFont('arial', 	'', 12);

$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 9, "Total de chamados concluídos (em até 4 horas ):                              $horas_4  ", 0, 0, "L", 1);

if($pct_4  < 50){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 9, $pct_4, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 9, "$pct_4 ", 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}

$pdf->Cell(72, 9, ">= 50                 ", 0, 1, "R", 1);

$pdf->SetFillColor(225,225,225);
$pdf->Cell(175, 9, "Total de chamados concluídos (em até 8 horas ):                              $horas8  ", 0, 0, "L", 1);

if($pct8  < 60){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 9, "$pct8 ", 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 9, "$pct8 ", 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}

$pdf->Cell(72, 9, ">= 60                 ", 0, 1, "R", 1);

$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 9, "Total de chamados concluídos (em até 24 horas ):                            $horas24  ", 0, 0, "L", 1);

if($pct24 < 70){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 9, $pct24, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 9, "$pct24 ", 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}

$pdf->Cell(72, 9, ">= 70                 ", 0, 1, "R", 1);

$pdf->SetFillColor(225,225,225);
$pdf->Cell(175, 9, "Total de chamados concluídos (em até 48 horas ):                            $horas48  ", 0, 0, "L", 1);


if($pct48 < 80){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 9, $pct48, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 9, "$pct48 ", 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}

$pdf->Cell(72, 9, ">= 80                  ", 0, 1, "R", 1);

$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 9, "Total de chamados concluídos (após 48 horas ):                               $maior_horas_48  ", 0, 0, "L", 1);

$pdf->SetTextColor(000,000,300);
$pdf->Cell(30, 9, "$pct_maior_48 ", 0, 0, "L", 1);
$pdf->SetTextColor(0,0,0);

$pdf->Cell(72, 9, " -                    ", 0, 1, "R", 1);
$pdf->Ln(8);

//$pdf->SetFillColor(0,0,0);
$pdf->Cell(277, 0, "", 0, 1, "C", 1);

$pdf->Ln(2);
//$pdf->SetFillColor(300,300,300);
//$pdf->SetFont('arial', '', 9);
//$pdf->Cell(80, 8, "Contrato: TC 139-PS/2008/0001", 1, 1, "C", 1);

$pdf->Ln(3);
//$pdf->SetFillColor(300,300,300);
//$pdf->SetFillColor(300,000,000);
$pdf->Cell(7, 3, " ", 0, 0, "L", 1);
//$pdf->SetFillColor(300,300,300);
//$pdf->SetTextColor(300,000,000);
$pdf->SetFont('arial', 'B', 7);
$pdf->Cell(7, 3, "Meta abaixo da exigida", 0, 1, "L", 1);

//$pdf->SetFillColor(300,300,300);
//$pdf->SetFillColor(000,000,300);
$pdf->Cell(7, 3, " ", 0, 0, "L", 1);
//$pdf->SetFillColor(300,300,300);
//$pdf->SetTextColor(000,000,300);
$pdf->SetFont('arial', 'B', 7);
$pdf->Cell(7, 3, "Meta atendida", 0, 1, "L", 1);
$pdf->Ln(12);

$pdf->SetTextColor(0,0,0);
//$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', 	'', 11);
$pdf->Cell(138, 9, "______________________________", 0, 0, "C", 1);
$pdf->Cell(139, 9, "______________________________", 0, 1, "C", 1);
$pdf->Cell(138, 3, "PREPOSTO", 0, 0, "C", 1);
$pdf->Cell(139, 3, $parametro->GetValorParametro("NOM_INSTITUICAO");, 0, 1, "C", 1);
$pdf->Cell(138, 17, "                                Data:___/___/____", 0, 0, "L", 1);
$pdf->Cell(139, 17, "                                 Data:___/___/____", 0, 1, "L", 1);

$pdf->Output();

?>