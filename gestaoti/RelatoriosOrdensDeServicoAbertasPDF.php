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
/******************************************************************
 * �rea de inclus�o dos arquivos que ser�o utilizados nesta p�gina.
 *****************************************************************/
require_once "include/PHP/FPDF/fpdf.php";
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.equipe_ti.php';
require_once 'include/PHP/class/class.parametro.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$equipe_ti 	= new equipe_ti();
$banco 		= new chamado();
$parametro	= new parametro();
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
		// Informa��es do cabecalho
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

// ====================================================================================================================================
// Informa��es do corpo
// ====================================================================================================================================

$pdf = new PDF('L'); // P - Portrait | L - Landscape
// Nome - O que aparece no topo do documento
$pdf->titulo  = $parametro->GetValorParametro("NOM_INSTITUICAO");
$pdf->titulo2 = $parametro->GetValorParametro("NOM_AREA_TI");
// Cabe�alho do grid do relat�rio
$pdf->cabecalho = $pdf->titulo;
// Rodap� do documento
$pdf->rodape = $pdf->titulo2;

$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);

if($v_SEQ_EQUIPE_TI != ""){
	$equipe_ti->select($v_SEQ_EQUIPE_TI);
	$executor_nom_equipe_ti = $equipe_ti->NOM_EQUIPE_TI;
}

// Atribui��o
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

	$hora_1 			= 0;
	$horas_2 			= 0;
	$horas_3			= 0;
	$horas_4			= 0;
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
		$horaDeAbertura = mktime ($_split_hour[0], $_split_hour[1], $_split_hour[2], $_split_data[1], $_split_data[2], $_split_data[0]);

		//Data de encerramento
		$_split_datehour2 = explode(' ',$row['dth_encerramento_efetivo']);
       	$_split_data2 = explode("/", $_split_datehour2[0]);
       	$_split_hour2 = explode(":", $_split_datehour2[1]);
		//Converte em segundos
		$horaDeEncerradamento =  mktime ($_split_hour2[0], $_split_hour2[1], $_split_hour2[2], $_split_data2[1], $_split_data2[2], $_split_data2[0]);

		$qtdEmSegundos = ($horaDeEncerradamento - $horaDeAbertura);

		if($qtdEmSegundos <= 3600 ){
			$hora_1++;
		}
		elseif ($qtdEmSegundos > 3600 && $qtdEmSegundos <= 7200){
			$horas_2++;
		}
		elseif ($qtdEmSegundos > 7200 && $qtdEmSegundos <= 10800){
			$horas_3++;
		}
		elseif ($qtdEmSegundos > 10800 && $qtdEmSegundos <= 14400){
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

	@$pct_1			= round((($hora_1	*100)/$total_horas), 1);
	@$pct_2 		= round((($horas_2	*100)/$total_horas), 1);
	@$pct_3 		= round((($horas_3	*100)/$total_horas), 1);
	@$pct_4 		= round((($horas_4	*100)/$total_horas), 1);
	@$pct_24 		= round((($horas_24	*100)/$total_horas), 1);
	@$pct_48 		= round((($horas_48	*100)/$total_horas), 1);
	@$pct_maior_48 	= round((($maior_horas_48*100)/$total_horas), 1);

	$pct2 		 	= ($pct_1 + $pct_2);
	$pct3 		 	= ($pct2  + $pct_3);
	$pct4 		 	= ($pct3  + $pct_4);
	$pct24 		 	= ($pct4  + $pct_24);
	$pct48 		 	= ($pct24 + $pct_48);

	$horas2 		= ($hora_1  + $horas_2);
	$horas3 		= ($horas2  + $horas_3);
	$horas4 		= ($horas3  + $horas_4);
	$horas24		= ($horas4  + $horas_24);
	$horas48 		= ($horas24 + $horas_48);
	$maiorHoras48 	= ($horas48 + $maior_horas_48);

}

$pdf->SetFillColor(202,202,202);
$pdf->SetFontSize(12);
$pdf->SetFont('arial', 'B');
$pdf->Cell(0, 5, "", 0, 1);
$pdf->Cell(0, 5, "", 0, 0, "C", 0);

//$pdf->SetFillColor(300,300,300);
$pdf->Ln(4);
($executor_nom_equipe_ti != "" )? $pdf->Cell(300, 5, "Ordens de Servi�o por Servi�o : $executor_nom_equipe_ti", 0, 0, "C", 1):$pdf->Cell(300, 5, "Ordens de Servi�o por Servi�o : Todas", 0, 0, "C", 1);
$pdf->SetFont('arial');
$pdf->Ln(6);
$pdf->Cell(300, 5, "Ordens de Servi�o Abertas entre $v_DTH_ABERTURA e $v_DTH_ENCERRAMENTO_EFETIVO_FINAL", 0, 0, "C", 1);
$pdf->Ln(12);

//$pdf->SetFillColor(0,0,0);
$pdf->Cell(277, 0, "", 0, 1, "C", 1);

$pdf->SetX(-10);
$pdf->line(10, 26, $pdf->GetX(), 26); // Desenha uma linha
$pdf->SetY(24);
$pdf->Ln(30);

//$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', 	'B', 13);
$pdf->Cell(277, 14, "Total de Chamadas Abertas: $total_horas                                                                                                 Meta Alcan�ada(%)", 0, 1, "L", 1);

$pdf->SetFont('arial', 	'', 12);
$pdf->SetFillColor(235,235,235);
$pdf->Cell(195, 9, "Total de chamadas conclu�das (em at� 1 hora ):                                              $hora_1  ", 0, 0, "L", 1);
$pdf->Cell(82, 9, "$pct_1 ", 0, 1, "L", 1);

$pdf->SetFillColor(202,202,202);
$pdf->Cell(195, 9, "Total de chamadas conclu�das (em at� 2 horas ):                                            $horas2  ", 0, 0, "L", 1);
$pdf->Cell(82, 9, "$pct2 ", 0, 1, "L", 1);

$pdf->SetFillColor(235,235,235);
$pdf->Cell(195, 9, "Total de chamadas conclu�das (em at� 3 horas ):                                            $horas3  ", 0, 0, "L", 1);
$pdf->Cell(82, 9, "$pct3 ", 0, 1, "L", 1);

$pdf->SetFillColor(202,202,202);
$pdf->Cell(195, 9, "Total de chamadas conclu�das (em at� 4 horas ):                                            $horas4  ", 0, 0, "L", 1);
$pdf->Cell(82, 9, "$pct4 ", 0, 1, "L", 1);

$pdf->SetFillColor(235,235,235);
$pdf->Cell(195, 9, "Total de chamadas conclu�das (em at� 24 horas ):                                          $horas24  ", 0, 0, "L", 1);
$pdf->Cell(82, 9, "$pct24 ", 0, 1, "L", 1);

$pdf->SetFillColor(202,202,202);
$pdf->Cell(195, 9, "Total de chamadas conclu�das (em at� 48 horas ):                                          $horas48  ", 0, 0, "L", 1);
$pdf->Cell(82, 9, "$pct48 ", 0, 1, "L", 1);

$pdf->SetFillColor(235,235,235);
$pdf->Cell(195, 9, "Total de chamadas conclu�das (ap�s 48 horas ):                                             $maior_horas_48  ", 0, 0, "L", 1);
$pdf->Cell(82, 9, "$pct_maior_48 ", 0, 1, "L", 1);

$pdf->Ln(10);
$pdf->SetFillColor(0,0,0);
$pdf->Cell(277, 0, "", 0, 1, "C", 1);

$pdf->Output();

?>