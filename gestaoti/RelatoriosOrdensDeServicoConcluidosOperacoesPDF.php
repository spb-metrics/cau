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
require 'include/PHP/class/class.recurso_ti.php';
require_once 'include/PHP/class/class.parametro.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
//Criando alguns objetos
$equipe_ti 	= new equipe_ti();
$banco 		= new chamado();
$empregados = new empregados();
$recurso_ti = new recurso_ti();
$parametro  = new parametro();
/*****************************************************************/
//Recuperando o nome pela matricula
//$recurso_ti->select($v_NUM_MATRICULA_RECURSO);
//$recurso_ti->getNOME();

if($v_SEQ_EQUIPE_TI != ""){
		//Recupera o nome pela matricula
		$equipe_ti->select($v_SEQ_EQUIPE_TI);
		$executor_nom_equipe_ti = $equipe_ti->NOM_EQUIPE_TI;
}

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
			$this->SetFont('Arial', 'B', 10);
			$this->Ln(5);
			$this->Cell(0, 5, "", 0, 1);
			$this->Cell(300, 35, $this->subtitulo,0,0,"C");
		}
		// subtitulo 2
		if($this->subtitulo2 != ""){
			$this->SetFont('Arial', '', 10);
			$this->Cell(0, 5, "", 0, 1);
			$this->Cell(300, 35, $this->subtitulo2,0,0,"C");

			// Número de página no canto suprior direito
			$this->SetFont('Arial', '', 10);
		    $this->SetX(-30);
	    	//$this->Cell(30, 20, "Página: ".$this->PageNo()."/{nb}", 0, 1); // imprime página X/Total de Páginas

	    	//$this->Ln(15);
			$this->SetX(-10);
	   		$this->line(10, 48, $this->GetX(), 48); // Desenha uma linha
			$this->SetY(50);
		}
		// Linha divisória do cabeçalho com o corpo do documento
	    $this->SetX(-10);
	    $this->line(10, 26, $this->GetX(), 26); // Desenha uma linha
		$this->SetY(50);

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

$pdf->subtitulo = "Chamados Atendidos pela TIST-1 (Operações)";
$pdf->subtitulo2 = "Ordens de Serviço Abertas entre : $v_DTH_ABERTURA e $v_DTH_ENCERRAMENTO_EFETIVO_FINAL";

// Cabeçalho do grid do relatório
$pdf->cabecalho = $pdf->titulo;
// Rodapé do documento
$pdf->rodape = $pdf->titulo2;
$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);

// Atribuição
//$v_SEQ_EQUIPE_TI = 1;

$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);

if($v_SEQ_EQUIPE_TI != "" ){
	$banco->AtenderChamados("DTH_ABERTURA", $vNumPagina, 200000);
}else{
	$banco->selectParam("DTH_ABERTURA", $vNumPagina, 200000);
}

if($banco->database->rows >= 0){

	$minutos_20			= 0;
	$hora_1 			= 0;
	$horas_2 			= 0;
	$totalCritico		= 0;
	$totalRotina		= 0;

	while ($row = pg_fetch_array($banco->database->result)){

		if($row['seq_prioridade_chamado'] == 3 || $row['seq_prioridade_chamado'] == 4){

			$totalCritico++;

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

			if($qtdEmSegundos <= 1200 ){
				$minutos_20++;
			}
			elseif($qtdEmSegundos <= 3600 ){
				$hora_1++;
			}
		}
		else{

			$totalRotina++;

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

			if ( $qtdEmSegundos <= 7200){
				$horas_2++;
			}
		}
	}

	$horas1_20		= ($minutos_20 + $hora_1 );

	@$pct_20		= round((($minutos_20	*100)/$totalCritico), 1);
	@$pct_1			= round((($hora_1		*100)/$totalCritico), 1);
	@$pct_2 		= round((($horas_2 		*100)/$totalRotina ), 1);

	$pct1_20		= ( $pct_20 + $pct_1);
}

$pdf->Ln(4);

$pdf->SetFillColor(235,235,235);
$pdf->SetFont('arial', 	'B', 9);
$pdf->Cell(95, 4, "Total de chamados Abertos(Críticos) :                  $totalCritico ", 0, 1, "L", 1);
$pdf->Cell(95, 4, "Total de chamados Abertos(Rotina)   :                  $totalRotina ", 0, 1, "L", 1);
$pdf->Ln(6);

$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(0);
$pdf->Cell(277	, 0, "", 0, 1, "C", 1);
$pdf->Ln(3);

$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', 	'B', 10);

$pdf->Cell(277, 5, "                                                                                                                                                                    Meta Alcançada(%)                                             Meta Exigida(%)", 0, 1, "L", 1);

$pdf->Ln(2);
$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 8, "Total de chamados concluídos (em até 20 minutos ):                              $minutos_20  ", 0, 0, "L", 1);

if(@$pct_20 < 80){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct_20, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, "$pct_20 ", 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}

$pdf->Cell(72, 8, ">= 80                    ", 0, 1, "R", 1);

$pdf->SetFillColor(225,225,225);
$pdf->Cell(175, 8, "Total de chamados concluídos (em até 1 hora ):                                      $horas1_20  ", 0, 0, "L", 1);

if(@$pct1_20 < 100){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct1_20, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, $pct1_20, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}

$pdf->Cell(72, 8, ">= 100                  ", 0, 1, "R", 1);

$pdf->SetFillColor(235,235,235);
$pdf->Cell(175, 8, "Total de chamados concluídos (em até 2 horas ):                                    $horas_2 ", 0, 0, "L", 1);

if(@$pct_2 < 100){
	$pdf->SetTextColor(300,000,000);
	$pdf->Cell(30, 8, $pct_2, 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}
else {
	$pdf->SetTextColor(000,000,300);
	$pdf->Cell(30, 8, "$pct_2 ", 0, 0, "L", 1);
	$pdf->SetTextColor(0,0,0);
}

$pdf->Cell(72, 8, ">= 100                  ", 0, 1, "R", 1);
$pdf->Ln(8);

$pdf->SetFillColor(0,0,0);
$pdf->Cell(277, 0, "", 0, 1, "C", 1);

$pdf->Ln(2);
$pdf->SetFillColor(300,300,300);
$pdf->SetFont('arial', '', 9);
$pdf->Cell(80, 8, "Contrato: TC 102 PS/2008/0001", 1, 1, "C", 1);

$pdf->Ln(3);
$pdf->SetFillColor(300,300,300);
//$pdf->Cell(120, 5, " ", 0, 0, "L", 1);
$pdf->SetFillColor(300,000,000);
$pdf->Cell(7, 3, " ", 0, 0, "L", 1);
$pdf->SetFillColor(300,300,300);
$pdf->SetTextColor(300,000,000);
$pdf->SetFont('arial', 'B', 7);
$pdf->Cell(7, 3, "Meta abaixo da exigida", 0, 1, "L", 1);

$pdf->SetFillColor(300,300,300);
//$pdf->Cell(120, 5, " ", 0, 0, "L", 1);
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
$pdf->Cell(138, 3, "PRODUÇÃO / IOS", 0, 0, "C", 1);
$pdf->Cell(139, 3, "Fiscal / INFRAERO", 0, 1, "C", 1);
$pdf->Cell(138, 17, "                                Data:___/___/____", 0, 0, "L", 1);
$pdf->Cell(139, 17, "                                 Data:___/___/____", 0, 1, "L", 1);

$pdf->Output();

?>