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
//Criando alguns objetos
$equipe_ti 	= new equipe_ti();
$banco 		= new chamado();
$empregados = new empregados();
$recurso_ti = new recurso_ti();
$parametro = new parametro();
/*******************************************************************/

//Recuperando o nome pela matricula
$recurso_ti->select($v_NUM_MATRICULA_RECURSO);
$nome_recurso_ti = ucwords( strtolower($recurso_ti->getNOME()));

if($v_SEQ_EQUIPE_TI != ""){
		//Recupera o nome pela matricula
		$equipe_ti->select($v_SEQ_EQUIPE_TI);
		$executor_nom_equipe_ti = $equipe_ti->getNOM_EQUIPE_TI();
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
	   		$this->line(10, 49, $this->GetX(), 49); // Desenha uma linha
			$this->SetY(50);
		}
		// Linha divis�ria do cabe�alho com o corpo do documento
	    $this->SetX(-10);
	    $this->line(10, 26, $this->GetX(), 26); // Desenha uma linha
		$this->SetY(50);

		if(	$this->page != 1){

			global $nome_recurso_ti;

			$this->SetFillColor(202,202,202);
			$this->SetFont('Arial', 'B', 9);
			//$this->SetTextColor(0,160,300);

			($nome_recurso_ti != "" )? $this->Cell(150, 5, "Colaborador : $nome_recurso_ti" ,0 , 1, "L", 1) : $this->Cell(150, 5, "Colaborador : Todos" ,0 , 1, "L", 1);
			//$this->SetTextColor(300,300,300);
			$this->SetFillColor(202,202,202);
			$this->SetFont('arial', 	'B', 8);
			$this->Cell(19, 5, "Chamado n�"	,1 , 0, "C", 1);
			$this->Cell(26, 5, "Abertura em",1 , 0, "C", 1);
			$this->Cell(47, 5, "Solicitante",1 , 0, "C", 1);
			$this->Cell(107, 5, "Atividade",1 , 0, "C", 1);
			$this->Cell(41, 5, $pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO"),1 , 0, "C", 1);
			$this->Cell(37, 5, $pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO"),1 , 1, "C", 1);

			$this->SetFillColor(202,202,202);
			$this->SetFont('arial', 	'', 7);
			$this->Ln(2);
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
	    $data = strftime("%d/%m/%Y �s %H:%M");
	    $this->Cell(200, 6, $this->rodape.$data, 0, 0, 'R');
    }

// ====================================================================================================================================
// Informa��es do corpo
// ====================================================================================================================================

$pdf = new PDF('L'); // P - Portrait | L - Landscape
// Nome - O que aparece no topo do documento
$pdf->titulo  = $parametro->GetValorParametro("NOM_INSTITUICAO");
//$pdf->titulo2 = $parametro->GetValorParametro("NOM_AREA_TI");
$pdf->titulo2 = " ";

($executor_nom_equipe_ti != "" )? $pdf->subtitulo = "$executor_nom_equipe_ti" : $pdf->subtitulo = "TODOS";
($executor_nom_equipe_ti != "" )? $pdf->subtitulo2 = "Chamados Atendidos por Profissional" : $pdf->subtitulo2 = "Chamados Atendidos por Profissional";

$pdf->subtitulo3 = "Chamados Abertos no Per�odo de : $v_DTH_ABERTURA e $v_DTH_ENCERRAMENTO_EFETIVO_FINAL";
// Cabe�alho do grid do relat�rio
$pdf->cabecalho = $pdf->titulo;
// Rodap� do documento
//$pdf->rodape = $pdf->titulo2;
$pdf->rodape = " ";

$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);

// Atribui��o

$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
$banco->setNUM_MATRICULA_EXECUTOR($v_NUM_MATRICULA_RECURSO);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);

if($v_SEQ_EQUIPE_TI != "" || $v_COD_DEPENDENCIA != ""){
	$banco->AtenderChamados("DTH_ABERTURA", $vNumPagina, 200000);
}else{
	$banco->selectParam("DTH_ABERTURA", $vNumPagina, 200000);
}

$pdf->SetFillColor(202,202,202);
$pdf->SetFont('Arial', 'B', 9);
//$pdf->SetTextColor(0,160,300);
($nome_recurso_ti != "")? $pdf->Cell(150, 5, "Colaborador : $nome_recurso_ti" ,0 , 1, "L", 1) : $pdf->Cell(150, 5, "Colaborador : Todos" ,0 , 1, "L", 1);
//$pdf->SetTextColor(300,300,300);
//$pdf->SetFillColor(0,160,300);
$pdf->SetFont('arial', 	'B', 8);
$pdf->Cell(19, 5, "Chamado n�"	,1 , 0, "C", 1);
$pdf->Cell(26, 5, "Abertura em"	,1 , 0, "C", 1);
$pdf->Cell(47, 5, "Solicitante"	,1 , 0, "C", 1);
$pdf->Cell(107, 5, $pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO")	,1 , 0, "C", 1);
$pdf->Cell(37, 5, $pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO")		,1 , 1, "C", 1);

//$pdf->SetFillColor(280,280,280);
$pdf->SetFont('arial', 	'', 7);
$pdf->Ln(2);

if($banco->database->rows >= 0){

	$cont = 2;
	$totalChamadasConcluidas = 0;

	while ($row = pg_fetch_array($banco->database->result)){

		if($cont % 2 == 0 ){
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFillColor(240,240,240);
		}
		else {
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFillColor(230,230,230);
		}

		//Seleciona o nome do solicitante pela matr�cula
		$empregados->select($row['num_matricula_solicitante']);

		//titulo
		if(strlen($row['txt_chamado']) > 90 ){
			$titulo  = strtolower( substr($row['txt_chamado'], 0, 90 ) );
			$titulo2 = strtolower( substr($row['txt_chamado'], 90, 80));

			if(strlen($row['txt_chamado']) > 170 ){
				$titulo3 = strtolower( substr($row['txt_chamado'], 170, 80 ));
			}
			else{$titulo3 = "";}

			if(strlen($row['txt_chamado']) > 250 ){
				$titulo4 = strtolower( substr($row['txt_chamado'], 250, 80 ));
			}
			else{$titulo4 = "";}

			if(strlen($row['txt_chamado']) > 330 ){
				$titulo5 = strtolower( substr($row['txt_chamado'], 330, 80 )).'...';
			}
			else{$titulo5 = "";}
		}
		else{
			$titulo  = strtolower( $row['txt_chamado'] );
			$titulo2 = "";
		}

		//tipo
		if(strlen($row['dsc_tipo_chamado']) > 23){
			$tipo  = substr($row['dsc_tipo_chamado'], 0, 23 );
			$tipo2 = substr($row['dsc_tipo_chamado'], 23 );
		}
		else{
			 $tipo  = $row['dsc_tipo_chamado'];
			 $tipo2 = "";
		}
		//solicitante
		$solicitante = ucwords(strtolower(($empregados->getNOME())));

		if(strlen($solicitante) >= 32){
			$solicitante = ( substr($solicitante, 0, 32 ));
		}
		//Chamado
		$pdf->Cell(19, 6, "{$row['seq_chamado']}",0 , 0, "C", 1);
		$pdf->Cell(26, 6, "{$row['dth_abertura']}"	,0 , 0, "C", 1);
		$pdf->Cell(47, 6, "  $solicitante"	,0 , 0, "C", 1);

		if( $titulo5 != ""){

			$pdf->Cell(107, 6, "$titulo",0 , 0, "L", 1);

			if($tipo2 != ""){
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
				$pdf->Ln(-1);
				$pdf->Cell(277, 6, "                                                                                                                                                                                                                                                                                                  $tipo2",0 , 1, "L", 1);
			}
			else{
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
			}

			$pdf->Ln(-1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo2",0 , 1, "L", 1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo3",0 , 1, "L", 1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo4",0 , 1, "L", 1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo5",0 , 1, "L", 1);
			$pdf->Cell(277, 2, "",0 ,1, "L", 1);
		}
		elseif( $titulo4 != ""){

			$pdf->Cell(107, 6, "$titulo",0 , 0, "L", 1);

			if($tipo2 != ""){
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
				$pdf->Ln(-1);
				$pdf->Cell(277, 6, "                                                                                                                                                                                                                                                                                                  $tipo2",0 , 1, "L", 1);
			}
			else{
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
			}

			$pdf->Ln(-1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo2",0 , 1, "L", 1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo3",0 , 1, "L", 1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo4",0 , 1, "L", 1);
			$pdf->Cell(277, 2, "",0 ,1, "L", 1);
		}
		elseif( $titulo3 != ""){

			$pdf->Cell(107, 6, "$titulo",0 , 0, "L", 1);

			if($tipo2 != ""){
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
				$pdf->Ln(-1);
				$pdf->Cell(277, 6, "                                                                                                                                                                                                                                                                                                  $tipo2",0 , 1, "L", 1);
			}
			else{
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
			}

			$pdf->Ln(-1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo2",0 , 1, "L", 1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo3",0 , 1, "L", 1);
			$pdf->Cell(277, 2, "",0 ,1, "L", 1);
		}
		elseif ($titulo2 != ""){
			$pdf->Cell(107, 6, "$titulo",0 , 0, "L", 1);

			if($tipo2 != ""){
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
				$pdf->Ln(-1);
				$pdf->Cell(277, 6, "                                                                                                                                                                                                                                                                                                  $tipo2",0 , 1, "L", 1);
			}
			else{
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
			}

			$pdf->Ln(-1);
			$pdf->Cell(277, 3, "                                                                                                                                						$titulo2",0 , 1, "L", 1);
			$pdf->Cell(277, 2, "",0 ,1, "L", 1);
		}
		else {

			$pdf->Cell(107, 6, "$titulo",0 , 0, "L", 1);

			if($tipo2 != ""){
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
				$pdf->Ln(-1);
				$pdf->Cell(277, 6, "                                                                                                                                                                                                                                                                                                  $tipo2",0 , 1, "L", 1);
			}
			else{
				$pdf->Cell(41, 6, "$tipo",0 , 0, "L", 1);
				$pdf->Cell(37, 6, "{$row['dsc_subtipo_chamado']}",0 ,1, "L", 1);
			}

		}

		$totalChamadasConcluidas++;
		$cont++;
	}
}

$pdf->Ln(2);
//$pdf->SetTextColor(300,300,300);
//$pdf->SetFillColor(0,160,300);
$pdf->SetFont('arial', 	'B', 8);
$pdf->Cell(277, 5, "Total de Chamados Atendidos : $totalChamadasConcluidas",1 , 0, "R", 1);
$pdf->SetTextColor(0,0,0);

$pdf->Output();

?>