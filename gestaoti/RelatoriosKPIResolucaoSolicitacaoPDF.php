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
require_once "include/PHP/FPDF/fpdf.php";
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.equipe_ti.php';
require_once 'include/PHP/class/class.recurso_ti.php';
require_once 'include/PHP/class/class.parametro.php';
require_once 'include/PHP/class/class.kpi.php';
require_once 'include/PHP/class/class.parametro.php';
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	require_once '../cau/include/PHP/autentica.php';
}else{
	require_once 'include/PHP/autentica.php';
}
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$equipe_ti 	= new equipe_ti();
$banco 		= new chamado();
$empregados = new empregados();
$recurso_ti = new recurso_ti();
$parametro  = new parametro();
$kpi 		= new kpi();
$parametro	= new parametro();
/*****************************************************************/
/*
//$mat = substr( $V_NUM_MATRICULA_SOLICITANTE, 1);
$mat = $empregados->GetNumeroMatricula( $V_NUM_MATRICULA_SOLICITANTE );
$recurso_ti->select($mat);
$recurso_ti->getNOME();

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

// ====================================================================================================================================
// Informações do corpo
// ====================================================================================================================================
$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

$pdf = new PDF('L'); // P - Portrait | L - Landscape
// Nome - O que aparece no topo do documento
$pdf->titulo  = $parametro->GetValorParametro("NOM_INSTITUICAO");
$pdf->titulo2 = $parametro->GetValorParametro("NOM_AREA_TI");

$pdf->subtitulo = "Resolução da requisição de serviço no tempo estipulado (2º nível)";
$pdf->subtitulo2 = "Período de $v_DTH_ABERTURA a $v_DTH_ABERTURA_FINAL";

// Cabeçalho do grid do relatório
$pdf->cabecalho = $parametro->GetValorParametro("NOM_INSTITUICAO");
// Rodapé do documento
$pdf->rodape = $parametro->GetValorParametro("NOM_AREA_TI");
$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);
$pdf->SetFillColor(255,255,255);
// Configuração do gráfico
	$kpi->arFaixaOdometro = array();
$kpi->arFaixaOdometro[0][0] = "0";
$kpi->arFaixaOdometro[0][1] = "70";
$kpi->arFaixaOdometro[0][2] = "red";

$kpi->arFaixaOdometro[1][0] = "70";
$kpi->arFaixaOdometro[1][1] = "80";
$kpi->arFaixaOdometro[1][2] = "yellow";

$kpi->arFaixaOdometro[2][0] = "80";
$kpi->arFaixaOdometro[2][1] = "100";
$kpi->arFaixaOdometro[2][2] = "green";

$kpi->DTH_INICIO            = $v_DTH_ABERTURA;
$kpi->DTH_FIM               = $v_DTH_ABERTURA_FINAL;
$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
$kpi->fileName = "imagens/RelResolucaoSolicitacao.png";
$kpi->KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao();

$kpi->GraficoOdometro($kpi->valorOdometro);

$pdf->Image('imagens/RelResolucaoSolicitacao.png', 15, 50, 120, 70, "PNG"); // importa uma imagem

// Buscar dados consolidados
$vMETA = $parametro->GetValorParametro("MetaTaxaResolucaoSolicitacao");
$vVALOR = $kpi->valorOdometro;
$vDIFERENCA = $vVALOR - $vMETA;

$pdf->Ln(20);

$pdf->SetFillColor(230,230,230);
$pdf->Cell(130, 9, "", 0, 0, "C", 0);
$pdf->Cell(150, 9, "Resultado consolidado do indicador", 1, 1, "C", 1);

$pdf->Cell(130, 9, "", 0, 0, "C", 0);
$pdf->Cell(50, 9, "Meta", 1, 0, "C", 1);
$pdf->Cell(50, 9, "Valor obtido", 1, 0, "C", 1);
$pdf->Cell(50, 9, "Diferença", 1, 1, "C", 1);

$pdf->SetFillColor(255,255,255);
$pdf->Cell(130, 9, "", 0, 0, "C", 0);
$pdf->Cell(50, 9, "$vMETA %", 1, 0, "R", 1);
$pdf->Cell(50, 9, number_format($vVALOR, 2)." %", 1, 0, "R", 1);
$pdf->Cell(50, 9, number_format($vDIFERENCA, 2) ." %", 1, 1, "R", 1);

$valorMeta = ($kpi->cont * $vMETA) / 100;

$pdf->Cell(130, 9, "", 0, 0, "C", 0);
$pdf->Cell(50, 9, number_format($valorMeta, 2), 1, 0, "R", 1);
$pdf->Cell(50, 9, number_format($kpi->cont - $kpi->contForaPrazo, 2), 1, 0, "R", 1);
$pdf->Cell(50, 9, number_format($kpi->cont - $valorMeta, 2), 1, 0, "R", 1);

$pdf->Ln(30);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(20, 9, "", 0, 0, "C", 0);
$pdf->Cell(220, 9, "Chamados considerados para o cálculo", 1, 1, "C", 1);

$pdf->Cell(20, 9, "", 0, 0, "C", 0);
$pdf->Cell(30, 9, "Chamado", 1, 0, "C", 1);
$pdf->Cell(40, 9, "Abertura", 1, 0, "C", 1);
$pdf->Cell(40, 9, "1º Nível", 1, 0, "C", 1);
$pdf->Cell(40, 9, "Prev. 2º nível", 1, 0, "C", 1);
$pdf->Cell(40, 9, "Encerramento", 1, 0, "C", 1);
$pdf->Cell(30, 9, "Na Meta?", 1, 1, "C", 1);

$pdf->SetFillColor(255,255,255);

$result = $kpi->database->query($kpi->sql);

while ($row = pg_fetch_array($kpi->database->result)){
	$pdf->Cell(20, 9, "", 0, 0, "C", 0);
	$pdf->Cell(30, 9, $row["seq_chamado"], 1, 0, "C", 1);
	$pdf->Cell(40, 9, $row["dth_abertura"], 1, 0, "C", 1);
	$pdf->Cell(40, 9, $row["dth_triagem_efetiva"], 1, 0, "C", 1);

	$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
	$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

	$pdf->Cell(40, 9, $v_DTH_ENCERRAMENTO_PREVISAO, 1, 0, "C", 1);

	$pdf->Cell(40, 9, $row["dth_encerramento_efetivo"], 1, 0, "C", 1);

	if($v_COD_SLA_ATENDIMENTO == -1){
		$pdf->Cell(30, 9, "Não", 1, 1, "C", 1);
	}else{
		$pdf->Cell(30, 9, "Sim", 1, 1, "C", 1);
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