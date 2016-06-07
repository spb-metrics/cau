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
require 'include/PHP/class/class.tipo_chamado.php';
require 'include/PHP/class/class.equipe_ti.php';
require 'include/PHP/class/class.recurso_ti.php';
require_once 'include/PHP/class/class.parametro.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$equipe_ti 	= new equipe_ti();
$banco 		= new chamado();
$recurso_ti = new recurso_ti();
$parametro = new parametro();
/*****************************************************************/

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

// =================================================================================================================================
// Informações do cabecalho
// =================================================================================================================================
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

		// Número de página no canto suprior direito
		$this->SetFont('Arial', '', 10);
 	    $this->SetX(-30);
    	$this->Cell(30, 10, "Página: ".$this->PageNo()."/{nb}", 0, 1); // imprime página X/Total de Páginas
		$this->Ln(12);
		$this->SetX(-10);
   		$this->line(10, 49, $this->GetX(), 49); // Desenha uma linha
		$this->SetY(50);
	}
	// Linha divisória do cabeçalho com o corpo do documento
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
			$this->Cell(22, 5, "Chamado nº"	,1 , 0, "C", 1);
			$this->Cell(60, 5, "Solicitante",1 , 0, "C", 1);
			$this->Cell(156, 5, "Atividade"	,1 , 0, "C", 1);
			$this->Cell(39, 5, "Tempo de Execução",1 , 1, "C", 1);

			//$this->SetFillColor(280,280,280);
			$this->SetFont('arial', '', 7);
			$this->Ln(2);
		}

   }
	// Rodapé : imprime a hora de impressao e Copyright
    function Footer() {
// =================================================================================================================================
// Informações do rodapé
// =================================================================================================================================
	    $this->SetXY(-10, -5);
	    $this->line(10, $this->GetY()-2, $this->GetX(), $this->GetY()-2);
	    $this->SetX(0);
	    $this->SetFont('Courier', 'BI', 8);
	    $data = strftime("%d/%m/%Y às %H:%M");
	    $this->Cell(200, 6, $this->rodape.$data, 0, 0, 'R');
    }
}

//==================================================================================================================================
// Informações do corpo
//==================================================================================================================================

$pdf = new PDF('L'); // P - Portrait | L - Landscape
// Nome - O que aparece no topo do documento
$pdf->titulo  = $parametro->GetValorParametro("NOM_INSTITUICAO");
$pdf->titulo2 = $parametro->GetValorParametro("NOM_AREA_TI");

($executor_nom_equipe_ti != "" )? $pdf->subtitulo = "$executor_nom_equipe_ti" : $pdf->subtitulo = "TODOS";
($executor_nom_equipe_ti != "" )? $pdf->subtitulo2 = "Chamados Atendidos por Profissional" : $pdf->subtitulo2 = "Chamados Atendidos por Profissional";

$pdf->subtitulo3 = "Chamados Abertos no Período de : $v_DTH_ABERTURA e $v_DTH_ENCERRAMENTO_EFETIVO_FINAL";
// Cabeçalho do grid do relatório
$pdf->cabecalho = $pdf->titulo;
// Rodapé do documento
$pdf->rodape = $pdf->titulo2;
$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);

// Atribuição
$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
$banco->setDTH_ENCERRAMENTO_EFETIVO_FINAL($v_DTH_ENCERRAMENTO_EFETIVO_FINAL);
$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
$banco->setNUM_MATRICULA_EXECUTOR($v_NUM_MATRICULA_RECURSO);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);

if($v_SEQ_EQUIPE_TI != "" || $v_COD_DEPENDENCIA != ""){
	$banco->AtenderChamados("SEQ_CHAMADO", $vNumPagina, 200000);
}else{
	$banco->selectParam("SEQ_CHAMADO", $vNumPagina, 200000);
}

$pdf->SetFillColor(202,202,202);
$pdf->SetFont('Arial', 'B', 9);
//$pdf->SetTextColor(0,160,300);
($nome_recurso_ti != "")? $pdf->Cell(150, 5, "Colaborador : $nome_recurso_ti" ,0 , 1, "L", 1) : $pdf->Cell(150, 5, "Colaborador : Todos" ,0 , 1, "L", 1);

$pdf->SetTextColor(300,300,300);
//$pdf->SetFillColor(0,160,300);
$pdf->SetFont('arial', 	'B', 8);
$pdf->Cell(46, 6, "Chamado",1 , 0, "C", 1);
$pdf->Cell(46, 6, "Data Abertura",1 , 0, "C", 1);
$pdf->Cell(46, 6, "Data Atribuição",1 , 0, "C", 1);
$pdf->Cell(46, 6, "Data Inicio Efetivo",1 , 0, "C", 1);
$pdf->Cell(46, 6, "Data Encerramento Efetivo",1 , 0, "C", 1);
$pdf->Cell(47, 6, "Duração do Atendimento",1 , 1, "C", 1);

//$pdf->SetFillColor(280,280,280);
$pdf->SetFont('arial', 	'', 7);
$pdf->Ln(2);

if($banco->database->rows >= 0){

	$cont = 2;
	$totalChamadasConcluidas = 0;

	while ($row = pg_fetch_array($banco->database->result)){

		if($cont % 2 == 0 ){
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFillColor(240,240,240	);
		}
		else {
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFillColor(230,230,230);
		}

		//Data de inicio efetivo
		$_split_datehour = explode(' ',$row['dth_atribuicao']);//atribuição chamado
	    $_split_data = explode("/", $_split_datehour[0]);
	    $_split_hour = explode(":", $_split_datehour[1]);
		//Converte em segundos
		@$dataInicioEfetivoEmSegundos = mktime ($_split_hour[0], $_split_hour[1], $_split_hour[2], $_split_data[1], $_split_data[2], $_split_data[0]);

		//Data de encerramento efetivo
		$_split_datehour2 = explode(' ',$row['dth_encerramento_efetivo_atribuicao']);//atribuição chamado
	    $_split_data2 = explode("/", $_split_datehour2[0]);
	    $_split_hour2 = explode(":", $_split_datehour2[1]);
		//Converte em segundos
		@$dataEncerramentoEfetivoEmSegundos =  mktime ($_split_hour2[0], $_split_hour2[1], $_split_hour2[2], $_split_data2[1], $_split_data2[2], $_split_data2[0]);
		//Converte segundos em horas
		$tempoDeExecucao = gmdate( "H:i:s", ($dataEncerramentoEfetivoEmSegundos - $dataInicioEfetivoEmSegundos ));

		//Chamado
		$pdf->Cell(46, 6, "{$row['seq_chamado']}",0 , 0, "C", 1);
		//Data de abertura
		$pdf->Cell(46, 6, "{$row['dth_abertura']}"	,0 , 0, "C", 1);
		//Data de atribuição
		$pdf->Cell(46, 6, "{$row['dth_atribuicao']}",0 , 0, "C", 1);
		//Data inicio efetivo
		$pdf->Cell(46, 6, "{$row['dth_inicio_efetivo_atribuicao']}",0 , 0, "C", 1);
		//Data encerramento efetivo
		$pdf->Cell(46, 6, "{$row['dth_encerramento_efetivo_atribuicao']}",0 , 0, "C", 1);
		//Duração do atendimento
		$pdf->Cell(47, 6, "$tempoDeExecucao",0 , 1, "C", 1);

		$totalChamadasConcluidas++;
		$cont++;
	}
}

$pdf->Ln(2);
$pdf->SetTextColor(300,300,300);
//$pdf->SetFillColor(0,160,300);
$pdf->SetFont('arial', 	'B', 8);
$pdf->Cell(277, 5, "Total de Chamados Atendidos : $totalChamadasConcluidas",1 , 0, "R", 1);
$pdf->SetTextColor(0,0,0);

$pdf->Output();

?>