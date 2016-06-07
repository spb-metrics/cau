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
require_once "include/PHP/FPDF/fpdf.php";
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.destino_triagem.php';
require_once 'include/PHP/class/class.prioridade_chamado.php';
require_once 'include/PHP/class/class.edificacao.php';
require_once 'include/PHP/class/class.equipe_ti.php';
require_once 'include/PHP/class/class.atividade_chamado.php';
require_once 'include/PHP/class/class.parametro.php';

$atividade_chamado 		= new atividade_chamado();
$equipe_ti 				= new equipe_ti();
$edificacao 	= new edificacao();
$destino_triagem 		= new destino_triagem();
$tipo_chamado 			= new tipo_chamado();
$subtipo_chamado 		= new subtipo_chamado();
$situacao_chamado 		= new situacao_chamado();
$pagina 				= new Pagina();
$banco 					= new chamado();
$empregados 			= new empregados();
$prioridade_chamado 	= new prioridade_chamado();
$pagina 				= new Pagina();
$parametro				= new parametro();

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

	$pdf = new PDF('P'); // P - Portrait | L - Landscape
	// Nome - O que aparece no topo do documento
	$pdf->titulo = "Relat�rio de Chamados";

	// Cabe�alho do grid do relat�rio
	$pdf->cabecalho = "Relat�rio de Chamados";
	// Rodap� do documento
	$pdf->rodape = $parametro->GetValorParametro("NOM_AREA_TI");

    $pdf->Open();
    $pdf->AddPage();

	$pdf->SetFont('Arial', '', 11);

	//Recurar Nomes
	if($v_SEQ_SITUACAO_CHAMADO != ""){
		$situacao_chamado->select($v_SEQ_SITUACAO_CHAMADO);
		$situacao = $situacao_chamado->getDSC_SITUACAO_CHAMADO();
	}

	if($v_SEQ_TIPO_CHAMADO != ""){
		$tipo_chamado->select($v_SEQ_TIPO_CHAMADO);
		$tipo = $tipo_chamado->getDSC_TIPO_CHAMADO();
	}

	if($v_SEQ_SUBTIPO_CHAMADO){
		$subtipo_chamado->select($v_SEQ_SUBTIPO_CHAMADO);
		$subtipo = $subtipo_chamado->getDSC_SUBTIPO_CHAMADO();
	}

	if($v_SEQ_PRIORIDADE_CHAMADO != ""){
		$prioridade_chamado->select($v_SEQ_PRIORIDADE_CHAMADO);
		$prioridade = $prioridade_chamado->getDSC_PRIORIDADE_CHAMADO();
	}

	if($v_COD_DEPENDENCIA_ATRIBUICAO){
		$edificacao->select($v_COD_DEPENDENCIA_ATRIBUICAO);
		$executor_nom_edificacao = $edificacao->getNOM_EDIFICACAO();
	}

	if($v_SEQ_EQUIPE_TI != ""){
		$equipe_ti->select($v_SEQ_EQUIPE_TI);
		$executor_nom_equipe_ti = $equipe_ti->getNOM_EQUIPE_TI();
	}
	if($v_SEQ_ATIVIDADE_CHAMADO != ""){
		$atividade_chamado->select($v_SEQ_ATIVIDADE_CHAMADO);
		$atividade = $atividade_chamado->getDSC_ATIVIDADE_CHAMADO();
	}

	// Setar vari�veis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);

	$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	if($v_NUM_MATRICULA_SOLICITANTE != ""){
		$banco->setNUM_MATRICULA_SOLICITANTE($empregados->GetNumeroMatricula($v_NUM_MATRICULA_SOLICITANTE));
	}
	if($v_NUM_MATRICULA_CONTATO != ""){
		$banco->setNUM_MATRICULA_CONTATO($empregados->GetNumeroMatricula($v_NUM_MATRICULA_CONTATO));
	}

	$banco->setSEQ_SITUACAO_CHAMADO		($v_SEQ_SITUACAO_CHAMADO);
	$banco->setCOD_SLA_ATENDIMENTO		($v_COD_SLA_ATENDIMENTO);
	$banco->setSEQ_TIPO_CHAMADO			($v_SEQ_TIPO_CHAMADO);
	$banco->setSEQ_SUBTIPO_CHAMADO		($v_SEQ_SUBTIPO_CHAMADO);
	$banco->setSEQ_ATIVIDADE_CHAMADO	($v_SEQ_ATIVIDADE_CHAMADO);
	$banco->setSEQ_PRIORIDADE_CHAMADO	($v_SEQ_PRIORIDADE_CHAMADO);

	// Atribui��o
	$banco->setCOD_DEPENDENCIA_ATRIBUICAO($v_COD_DEPENDENCIA_ATRIBUICAO);
	$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$banco->setNUM_MATRICULA_EXECUTOR($v_NUM_MATRICULA_RECURSO);

	$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
	$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);

	$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
	$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);

	$banco->setDTH_INICIO_EFETIVO($v_DTH_INICIO_EFETIVO);
	$banco->setDTH_INICIO_EFETIVO_FINAL($v_DTH_INICIO_EFETIVO);

	$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ENCERRAMENTO_EFETIVO);
	$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);

	if($v_SEQ_EQUIPE_TI != "" || $v_COD_DEPENDENCIA_ATRIBUICAO != ""){
		$banco->AtenderChamados("DTH_ABERTURA", $vNumPagina, 10);
	}else{
		$banco->selectParam("DTH_ABERTURA", $vNumPagina, 200000);
	}

	$pdf->SetFillColor(202,202,202);
	//$pdf->SetFillColor(300,300,300);
	$pdf->SetFontSize(10);
	$pdf->SetFont('arial', 'B');
	$pdf->Cell(0, 5, "", 0, 1);
	$pdf->Cell(0, 5, "", 0, 0, "C", 0);
	$pdf->Ln(1);

	$pdf->Cell(190, 1, "", 0, 0, "R", 1);
	$pdf->Ln(2);
	$pdf->Cell(190, 2, "", 0, 0, "R", 1);

	$pdf->Ln(2);
	//$pdf->Cell(8, 5, "", 0, 0, "R", 1);
	$pdf->Cell(23, 5, "Situa��o :", 0, 0, "R", 1);
	$pdf->SetFont('arial');
	($v_SEQ_SITUACAO_CHAMADO != "") ? $pdf->Cell(167, 5, "$situacao", 0, 0, "L", 1) : $pdf->Cell(167, 5, " Todas", 0, 0, "L", 1);

	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B');
	$pdf->Cell(23, 5, "Classe :", 0, 0, "R", 1);
	$pdf->SetFont('arial');
	($v_SEQ_TIPO_CHAMADO != "") ? $pdf->Cell(167, 5, "$tipo", 0, 0, "L", 1) : $pdf->Cell(167, 5, "Todos", 0, 0, "L", 1) ;

	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B');
	$pdf->Cell(23, 5, "Subclasse :", 0, 0, "R", 1);
	$pdf->SetFont('arial');
	($v_SEQ_SUBTIPO_CHAMADO != "") ? $pdf->Cell(167, 5, "$subtipo", 0, 0, "L", 1) : $pdf->Cell(167, 5, "Todos", 0, 0, "L", 1);

	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B');
	$pdf->Cell(23, 5, "Atividade :", 0, 0, "R", 1);
	$pdf->SetFont('arial');
	($v_SEQ_ATIVIDADE_CHAMADO != "") ? $pdf->Cell(167, 5, "$atividade", 0, 0, "L", 1) : $pdf->Cell(167, 5, "Todas", 0, 0, "L", 1);


	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B');
	$pdf->Cell(23, 5, "Prioridade :", 0, 0, "R", 1);
	$pdf->SetFont('arial');
	($v_SEQ_PRIORIDADE_CHAMADO != "") ? $pdf->Cell(167, 5, "$prioridade", 0, 0, "L", 1) : $pdf->Cell(167, 5, "Todas", 0, 0, "L", 1);

	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B');
	$pdf->Cell(23, 5, "Executor :", 0, 0, "R", 1);
	$pdf->SetFont('arial');
	($v_SEQ_EQUIPE_TI != "") ? $pdf->Cell(167, 5, "$executor_nom_equipe_ti", 0, 0, "L", 1) : $pdf->Cell(167, 5, " Todos", 0, 0, "L", 1);

	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B');
	$pdf->Cell(23, 5, "Per�odo  :", 0, 0, "R", 1);
	$pdf->SetFont('arial');
	$pdf->Cell(167, 5, "$v_DTH_ABERTURA a $v_DTH_ABERTURA_FINAL", 0, 0, "L", 1);

	$pdf->Ln(5);
	$pdf->Cell(190, 2, "", 0, 0, "R", 1);
	$pdf->Ln(3);
	$pdf->Cell(190, 1, "", 0, 0, "R", 1);
	$pdf->Ln(6);

	if($banco->database->rows >= 0){

		$emDia 	= 0;
		$atraso = 0;
		$risco	= 0;

	while ($row = pg_fetch_array($banco->database->result)){
		$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);

		switch ($v_COD_SLA_ATENDIMENTO){

			case '1':
				$emDia++;
			break;
			case '0':
				$risco++;
			break;
			default:
				$atraso++;
			break;
		}
	}

	$TotalEmDia = ( $emDia + $risco );

	//Nomes das colunas
	$pdf->SetFontSize(10);
	$pdf->SetFont('arial', 'B');
	$pdf->Cell(0, 5, "", 0, 1);
	$pdf->Cell(0, 5, "", 0, 0, "C", 0);
	$pdf->Ln(1);

	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(40, 15, '', 0, 0, "C", 1);
	$pdf->SetFillColor(202,202,202);
	$pdf->Cell(60, 15, "Chamados em dia",1, 0, "C", 1);

	//$pdf->SetFillColor(202,202,202);
	$pdf->Cell(40, 15, "$TotalEmDia",1, 0, "C", 1);
	$pdf->Ln(15);

	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(40, 15, '', 0, 0, "C", 1);
	$pdf->SetFillColor(202,202,202);
	$pdf->Cell(60, 15, "Chamados atrasados", 1, 0, "C", 1);
	$pdf->SetFillColor(202,202,202);
	$pdf->Cell(40, 15, "$atraso", 1, 0, "C", 1);

	$pdf->Ln(15);
	$pdf->SetFontSize(15);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(40, 15, '', 0, 0, "C", 1);
	$pdf->SetFillColor(202,202,202);
	$pdf->Cell(60, 15, "Total", 1, 0, "C", 1);
	$pdf->SetFillColor(202,202,202);
	$pdf->Cell(40, 15, "$banco->rowCount", 1, 0, "C", 1);

}
$pdf->Output();

?>