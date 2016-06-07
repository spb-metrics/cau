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
require_once 'include/PHP/class/class.parametro.php';
require_once 'include/PHP/class/class.kpi.php';
require_once 'include/PHP/class/class.parametro.php';
require_once 'include/PHP/class/class.avaliacao_atendimento.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	require_once '../cau/include/PHP/autentica.php';
}else{
	require_once 'include/PHP/autentica.php';
}
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$pagina					= new pagina();
$banco 					= new chamado();
$parametro  			= new parametro();
$kpi 					= new kpi();
$avaliacao_atendimento  = new avaliacao_atendimento();
$empregados				= new empregados();
/*****************************************************************/
/*

if($v_SEQ_EQUIPE_TI != ""){
	//Recupera o nome pela matricula
	$equipe_ti->select($v_SEQ_EQUIPE_TI);
	$executor_nom_equipe_ti = $equipe_ti->getNOM_EQUIPE_TI();
}
*/
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

			// N�mero de p�gina no canto suprior direito
			$this->SetFont('Arial', '', 10);
		    $this->SetX(-30);
	    	$this->Cell(30, 20, "P�gina: ".$this->PageNo()."/{nb}", 0, 1); // imprime p�gina X/Total de P�ginas

	    	//$this->Ln(15);
			$this->SetX(-10);
	   		$this->line(10, 48, $this->GetX(), 48); // Desenha uma linha
			$this->SetY(50);
		}
		// Linha divis�ria do cabe�alho com o corpo do documento
	    $this->SetX(-10);
	    $this->line(10, 26, $this->GetX(), 26); // Desenha uma linha
		$this->SetY(50);

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

// ====================================================================================================================================
// Informa��es do corpo
// ====================================================================================================================================
$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

$pdf = new PDF('L'); // P - Portrait | L - Landscape
// Nome - O que aparece no topo do documento
$pdf->titulo  = $parametro->GetValorParametro("NOM_INSTITUICAO");
//$pdf->titulo2 = $parametro->GetValorParametro("NOM_AREA_TI");
$pdf->titulo2 = " ";

$pdf->subtitulo = "N�vel de Satisfa��o dos Usu�rios Atendidos";
$pdf->subtitulo2 = "Per�odo de $v_DTH_ABERTURA a $v_DTH_ABERTURA_FINAL";

// Cabe�alho do grid do relat�rio
$pdf->cabecalho = $parametro->GetValorParametro("NOM_INSTITUICAO");
// Rodap� do documento
//$pdf->rodape = $parametro->GetValorParametro("NOM_AREA_TI");
$pdf->rodape = " ";
$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);
$pdf->SetFillColor(255,255,255);
// Configura��o do gr�fico
$kpi->DTH_INICIO            = $v_DTH_ABERTURA;
$kpi->DTH_FIM               = $v_DTH_ABERTURA_FINAL;
$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
// ====================================================================================================================================
// Gr�fico 1
$pdf->SetFillColor(230,230,230);
$pdf->Cell(1, 5, "", 0, 0, "C", 0);
	$pdf->Cell(276, 5, "Questionamento 1 - Satisfa��o com a solu��o apresentada", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$pdf->Cell(205, 5, "Quantidade de chamados por N�vel de Satifa��o", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $row["nom_avaliacao_atendimento"], 1, 0, "C", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);

	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $banco->CountAvaliacao("seq_avaliacao_atendimento", $row["seq_avaliacao_atendimento"], $kpi->DTH_INICIO, $kpi->DTH_FIM,"",$_SEQ_CENTRAL_ATENDIMENTO), 1, 0, "R", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);

$kpi->fileName = "imagens/RelSatisfacao1.png";
$kpi->KpiChamadosPorAvaliacao();
$kpi->GraficoPizzaSimples($kpi->dados, $kpi->label, "", "blue", 290, 150, 0, 0, 0, 0);
$pdf->Image('imagens/RelSatisfacao1.png', 11, 55, 70, 35, "PNG"); // importa uma imagem

// ====================================================================================================================================
// Gr�fico 2
$pdf->Ln(23);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(1, 5, "", 0, 0, "C", 0);
	$pdf->Cell(276, 5, "Questionamento 2 - Satisfa��o com o conhecimento t�cnico do prestador de servi�o", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$pdf->Cell(205, 5, "Quantidade de chamados por N�vel de Satifa��o", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $row["nom_avaliacao_atendimento"], 1, 0, "C", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);

	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $banco->CountAvaliacao("seq_avaliacao_conhecimento_tecnico", $row["seq_avaliacao_atendimento"], $kpi->DTH_INICIO, $kpi->DTH_FIM,"",$_SEQ_CENTRAL_ATENDIMENTO), 1, 0, "R", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);

$kpi->fileName = "imagens/RelSatisfacao2.png";
$kpi->KpiChamadosPorAvaliacao_Satisfacao_Conhecimento();
$kpi->GraficoPizzaSimples($kpi->dados, $kpi->label, "", "blue", 290, 150, 0, 0, 0, 0);
$pdf->Image('imagens/RelSatisfacao2.png', 11, 98, 70, 35, "PNG"); // importa uma imagem

// ====================================================================================================================================
// Gr�fico 3
$pdf->Ln(23);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(1, 5, "", 0, 0, "C", 0);
	$pdf->Cell(276, 5, "Questionamento 3 - Satisfa��o com a postura e cordialidade do prestador de servi�o", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$pdf->Cell(205, 5, "Quantidade de chamados por N�vel de Satifa��o", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $row["nom_avaliacao_atendimento"], 1, 0, "C", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);

	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $banco->CountAvaliacao("seq_avaliacao_postura", $row["seq_avaliacao_atendimento"], $kpi->DTH_INICIO, $kpi->DTH_FIM,"",$_SEQ_CENTRAL_ATENDIMENTO), 1, 0, "R", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);

$kpi->fileName = "imagens/RelSatisfacao3.png";
$kpi->KpiChamadosPorAvaliacao_Satisfacao_Postura();
$kpi->GraficoPizzaSimples($kpi->dados, $kpi->label, "", "blue", 290, 150, 0, 0, 0, 0);
$pdf->Image('imagens/RelSatisfacao3.png', 11, 141, 70, 35, "PNG"); // importa uma imagem

// ====================================================================================================================================
// P�gina 2
// ====================================================================================================================================
// Gr�fico 4
$pdf->Ln(30);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(1, 5, "", 0, 0, "C", 0);
$pdf->SetY(50);
	$pdf->Cell(1, 5, "", 0, 0, "C", 0);
	$pdf->Cell(276, 5, "Questionamento 4 - Satisfa��o com o tempo de espera para atendimento", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$pdf->Cell(205, 5, "Quantidade de chamados por N�vel de Satifa��o", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $row["nom_avaliacao_atendimento"], 1, 0, "C", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);

	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $banco->CountAvaliacao("seq_avaliacao_tempo_espera", $row["seq_avaliacao_atendimento"], $kpi->DTH_INICIO, $kpi->DTH_FIM,"",$_SEQ_CENTRAL_ATENDIMENTO), 1, 0, "R", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);

$kpi->fileName = "imagens/RelSatisfacao4.png";
$kpi->KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera();
$kpi->GraficoPizzaSimples($kpi->dados, $kpi->label, "", "blue", 290, 150, 0, 0, 0, 0);
$pdf->Image('imagens/RelSatisfacao4.png', 11, 55, 70, 35, "PNG"); // importa uma imagem

// ====================================================================================================================================
// Gr�fico 5
$pdf->Ln(23);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(1, 5, "", 0, 0, "C", 0);
	$pdf->Cell(276, 5, "Questionamento 5 - Satisfa��o com o tempo de solu��o", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$pdf->Cell(205, 5, "Quantidade de chamados por N�vel de Satifa��o", 1, 1, "C", 1);
$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $row["nom_avaliacao_atendimento"], 1, 0, "C", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);

	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(72, 5, "", 0, 0, "C", 0);
	$avaliacao_atendimento->selectParam("nom_avaliacao_atendimento");
	while ($row = pg_fetch_array($avaliacao_atendimento->database->result)){
		$pdf->Cell(41, 5, $banco->CountAvaliacao("seq_avaliacao_tempo_solucao", $row["seq_avaliacao_atendimento"], $kpi->DTH_INICIO, $kpi->DTH_FIM,"",$_SEQ_CENTRAL_ATENDIMENTO), 1, 0, "R", 1);
	}
	$pdf->Cell(0, 5, "", 0, 1, "C", 0);
$kpi->fileName = "imagens/RelSatisfacao5.png";
$kpi->KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao();
$kpi->GraficoPizzaSimples($kpi->dados, $kpi->label, "", "blue", 290, 150, 0, 0, 0, 0);
$pdf->Image('imagens/RelSatisfacao5.png', 11, 98, 70, 35, "PNG"); // importa uma imagem

$pdf->Ln(30);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(1, 5, "", 0, 0, "C", 0);
$pdf->Cell(270, 5, "Chamados avaliados no per�odo", 1, 1, "C", 1);

$pdf->Cell(1, 5, "", 0, 0, "C", 0);
$pdf->Cell(20, 5, "Chamado", 1, 0, "C", 1);
$pdf->Cell(100, 5, "Usu�rio", 1, 0, "C", 1);
$pdf->Cell(30, 5, "Quest. 1", 1, 0, "C", 1);
$pdf->Cell(30, 5, "Quest. 2", 1, 0, "C", 1);
$pdf->Cell(30, 5, "Quest. 3", 1, 0, "C", 1);
$pdf->Cell(30, 5, "Quest. 4", 1, 0, "C", 1);
$pdf->Cell(30, 5, "Quest. 5", 1, 1, "C", 1);

$pdf->SetFillColor(255,255,255);

$banco->RelatorioAvaliacao($kpi->DTH_INICIO, $kpi->DTH_FIM,"",$_SEQ_CENTRAL_ATENDIMENTO);

while ($row = pg_fetch_array($banco->database->result)){
	$pdf->Cell(1, 5, "", 0, 0, "C", 0);
	$pdf->Cell(20, 5, $row["seq_chamado"], 1, 0, "C", 1);

	$pdf->Cell(100, 5, $empregados->GetNomeEmpregado($row["num_matricula_avaliador"]), 1, 0, "L", 1);

	$avaliacao_atendimento->select($row["seq_avaliacao_atendimento"]);
	$pdf->Cell(30, 5, $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, 1, 0, "C", 1);

	if($row["seq_avaliacao_conhecimento_tecnico"] != ""){
		$avaliacao_atendimento->select($row["seq_avaliacao_conhecimento_tecnico"]);
		$pdf->Cell(30, 5, $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, 1, 0, "C", 1);
	}else{
		$pdf->Cell(30, 5, "N�o Avaliado", 1, 0, "C", 1);
	}

	if($row["seq_avaliacao_postura"] != ""){
		$avaliacao_atendimento->select($row["seq_avaliacao_postura"]);
		$pdf->Cell(30, 5, $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, 1, 0, "C", 1);
	}else{
		$pdf->Cell(30, 5, "N�o Avaliado", 1, 0, "C", 1);
	}

	if($row["seq_avaliacao_tempo_espera"] != ""){
		$avaliacao_atendimento->select($row["seq_avaliacao_tempo_espera"]);
		$pdf->Cell(30, 5, $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, 1, 0, "C", 1);
	}else{
		$pdf->Cell(30, 5, "N�o Avaliado", 1, 0, "C", 1);
	}

	if($row["seq_avaliacao_tempo_solucao"] != ""){
		$avaliacao_atendimento->select($row["seq_avaliacao_tempo_solucao"]);
		$pdf->Cell(30, 5, $avaliacao_atendimento->NOM_AVALIACAO_ATENDIMENTO, 1, 1, "C", 1);
	}else{
		$pdf->Cell(30, 5, "N�o Avaliado", 1, 1, "C", 1);
	}
}

$pdf->SetFillColor(255,255,255);

$pdf->Ln(20);

$pdf->SetFont('arial', 	'', 11);
$pdf->Cell(138, 9, "______________________________", 0, 0, "C", 1);
$pdf->Cell(139, 9, "______________________________", 0, 1, "C", 1);
$pdf->Cell(138, 3, "GESTOR DO CONTRATO", 0, 0, "C", 1);
$pdf->Cell(139, 3, "FISCAL DO CONTRATO", 0, 1, "C", 1);
$pdf->Cell(138, 17, "                                Data:___/___/____", 0, 0, "L", 1);
$pdf->Cell(139, 17, "                                 Data:___/___/____", 0, 1, "L", 1);

$pdf->Output();

?>