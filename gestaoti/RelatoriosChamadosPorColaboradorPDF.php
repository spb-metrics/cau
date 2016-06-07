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
$pagina 	= new pagina();
$equipe_ti 	= new equipe_ti();
$banco 		= new chamado();
$banco2		= new chamado();
$empregados = new empregados();
$recurso_ti = new recurso_ti();
$parametro = new parametro();
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
		}
		// subtitulo 3
		if($this->subtitulo3 != ""){
			$this->SetFont('Arial', '', 10);
			$this->Cell(0, 5, "", 0, 1);
			$this->Cell(300, 35, $this->subtitulo3,0,0,"C");

			// N�mero de p�gina no canto suprior direito
			$this->SetFont('Arial', '', 10);
		    $this->SetX(-30);
	    	$this->Cell(30, 10, "P�gina: ".$this->PageNo()."/{nb}", 0, 1); // imprime p�gina X/Total de P�ginas
			$this->Ln(12);
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

// =================================================================================================================================
// Informa��es do corpo
// =================================================================================================================================

$pdf = new PDF('L'); // P - Portrait | L - Landscape
// Nome - O que aparece no topo do documento
$pdf->titulo  = $parametro->GetValorParametro("NOM_INSTITUICAO");
$pdf->titulo2 = $parametro->GetValorParametro("NOM_AREA_TI");

($executor_nom_equipe_ti != "" )? $pdf->subtitulo = "Chamados por Colaborador : $executor_nom_equipe_ti" : $pdf->subtitulo = "Chamados por Colaborador : Todos";
$pdf->subtitulo2 = "Chamados Abertos entre : $v_DTH_ABERTURA e $v_DTH_ENCERRAMENTO_EFETIVO_FINAL";

// Cabe�alho do grid do relat�rio
$pdf->cabecalho = $pdf->titulo;
// Rodap� do documento
$pdf->rodape =$pdf->titulo2;
$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);

// Linha divis�ria do cabe�alho com o corpo do documento
$pdf->SetX(-10);
$pdf->line(10, 45, $pdf->GetX(), 45); // Desenha uma linha
$pdf->SetY(50);

// Atribui��o
$v_DTH_ABERTURA_FINAL = $v_DTH_ENCERRAMENTO_EFETIVO_FINAL;

$banco2->setSEQ_CHAMADO($v_SEQ_CHAMADO);
$banco2->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);
$banco2->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
$banco2->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
$banco2->setCOD_DEPENDENCIA_ATRIBUICAO($v_COD_DEPENDENCIA_ATRIBUICAO);
$banco2->setNUM_MATRICULA_EXECUTOR($v_NUM_MATRICULA_RECURSO);
$banco2->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
$banco2->setDTH_ABERTURA($v_DTH_ABERTURA);

if($v_SEQ_EQUIPE_TI != "" || $v_COD_DEPENDENCIA_ATRIBUICAO != ""){
	$banco2->AtenderChamados("NUM_MATRICULA_EXECUTOR", $vNumPagina, 200000);
}else{
	$banco2->selectParam("NUM_MATRICULA_EXECUTOR", $vNumPagina, 200000);
}

if($banco2->database->rows >= 0){

	$totalChamadasAbertos = 0;

	while (@$row2 = pg_fetch_array($banco2->database->result)){
		$totalChamadasAbertos++;
	}
}

// Atribui��o
$v_SEQ_SITUACAO_CHAMADO = 4;

$banco->setSEQ_CHAMADO($v_SEQ_CHAMADO);
$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO);
$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
$banco->setCOD_DEPENDENCIA_ATRIBUICAO($v_COD_DEPENDENCIA_ATRIBUICAO);
$banco->setNUM_MATRICULA_EXECUTOR($v_NUM_MATRICULA_RECURSO);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
$banco->setDTH_ABERTURA($v_DTH_ABERTURA);

if($v_SEQ_EQUIPE_TI != "" || $v_COD_DEPENDENCIA_ATRIBUICAO != ""){
	$banco->AtenderChamados("NUM_MATRICULA_EXECUTOR", $vNumPagina, 200000);
}else{
	$banco->selectParam("NUM_MATRICULA_EXECUTOR", $vNumPagina, 200000);
}

//$pdf->SetFillColor(0,0,0);
$pdf->Cell(150, 0, ""	,0 , 1, "C", 1);
$pdf->Ln(1);

$pdf->SetFillColor(202,202,202);
$pdf->SetFont('arial', 	'B', 8);
$pdf->Cell(100, 5, "Total de chamados abertos no per�odo: $totalChamadasAbertos"	,0 , 1, "L", 1);
$pdf->Ln(1);

$pdf->SetFillColor(202,202,202);
$pdf->Cell(150, 0, ""	,0 , 1, "L", 1);
$pdf->Ln(2);

//$pdf->SetTextColor(300,300,300);
$pdf->SetFillColor(202,202,202);
$pdf->Cell(95, 9, "Nome do Colaborador"	,1 , 0, "C", 1);
$pdf->Cell(55, 9, "Qtd de chamados atendidos"	,1 , 1, "C", 1);
$pdf->SetTextColor(0,0,0);

$pdf->SetFillColor(202,202,202);
$pdf->SetFont('arial', 	'', 10);

if($banco->database->rows >= 0){

	$totalChamadasAtendidos = 0;
	$listaMat = array();
	$listaNom = array();
	$listaDeValores = array();

	while (@$row = pg_fetch_array($banco->database->result)){

		$totalChamadasAtendidos++;
		if($pagina->arrayFind($listamat, $row['num_matricula_executor']) == 0){
			$listaMatUni[] = $row['num_matricula_executor'];
			$listaNomes[] = $row["nom_executante"];
		}
		$listaMat[] = $row['num_matricula_executor'];
		$listaNom[] = $row["nom_executante"];
	}
	//print "<br>cont1 = ".count($listamat);
	//$listaMatUni = array_unique($listaMat);
	//print "<br>cont2 = ".count($listaMatUni);
	//$listaNomes  = array_unique($listaNom);

	$listaDeValores = array();

	//foreach ($listaMatUni as $listaMatUnique ) {
	for ($i=0; $i<count($listaMatUni);$i++){
		$listaMatUnique = $listaMatUni[$i];
		$cont = 0;

		//foreach ($listaMat as $listaMatt) {
		for ($j=0; $j<count($listaMat);$j++){
			$listaMatt = $listaMat[$j];
			//print "<br>listaMatUnique=$listaMatUnique == listaMatt=$listaMatt ";
			if($listaMatUnique == $listaMatt ){
				$cont++;
			}
		}
		//print "<br>cont = ".$cont;
		$listaDeValores[] = $cont;
	}

	@$combinarArray = array_combine($listaNomes, $listaDeValores);

	if($combinarArray != ""){

		foreach ($combinarArray as $nome => $qtd ) {

			$pdf->SetFillColor(202,202,202);
			$pdf->Cell(95, 7, "$nome"	,1 , 0, "      L", 1);
			$pdf->Cell(55, 7, "$qtd"	,1 , 1, "C", 1);
		}
	}

		$pdf->Ln(2);
		$pdf->SetFont('arial', 	'B', 8);
		$pdf->SetFillColor(202,202,202);
		$pdf->Cell(150, 0, ""	,0 , 1, "L", 1);

		$pdf->Ln(1);
		$pdf->SetFillColor(202,202,202);
		$pdf->Cell(100, 5, "Total de chamados atendidos no per�odo : $totalChamadasAtendidos"	,0 , 1, "L", 1);
		$pdf->Ln(1);

		$pdf->SetFillColor(0,0,0);
		$pdf->Cell(150, 0, ""	,0 , 1, "L", 1);
	}

$pdf->Ln(10);
$pdf->SetFillColor(202,202,202);
$pdf->SetFont('arial', 	'', 7);

$pdf->Output();
?>