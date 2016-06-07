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
require 'include/PHP/class/class.time_sheet.php';
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.tipo_chamado.php';
require 'include/PHP/class/class.equipe_ti.php';
require 'include/PHP/class/class.recurso_ti.php';
require_once 'include/PHP/class/class.parametro.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$equipe_ti 			= new equipe_ti();
$pagina				= new Pagina();
$dadosChamados 		= new chamado();
$banco 				= new time_sheet();
$empregados 		= new empregados();
$recurso_ti 		= new recurso_ti();
$tipo_chamado		= new tipo_chamado();
$parametro			= new parametro();
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
			//$this->SetTextColor(0,160,300);;

			($nome_recurso_ti != "" )? $this->Cell(150, 5, "Colaborador : $nome_recurso_ti" ,0 , 1, "L", 1) : $this->Cell(150, 5, "Colaborador : Todos" ,0 , 1, "L", 1);
			//$this->SetTextColor(300,300,300);
			//$this->SetFillColor(0,160,300);
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

($executor_nom_equipe_ti != "" )? $pdf->subtitulo = "$executor_nom_equipe_ti" : $pdf->subtitulo = "TODOS";
($executor_nom_equipe_ti != "" )? $pdf->subtitulo2 = "Chamados Atendidos por Profissional" : $pdf->subtitulo2 = "Chamados Atendidos por Profissional";

$pdf->subtitulo3 = "Chamados Abertos no Período de : $v_DTH_INICIO e $v_DTH_INICIO_FINAL";
// Cabeçalho do grid do relatório
$pdf->cabecalho = $pdf->titulo;
// Rodapé do documento
$pdf->rodape = $pdf->titulo2;

$pdf->Open();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);
//Atributos
$banco->setNUM_MATRICULA($v_NUM_MATRICULA_RECURSO);
$banco->setDTH_INICIO($v_DTH_INICIO." 00:00:00");
$banco->setDTH_INICIO_FINAL($v_DTH_INICIO_FINAL." 23:59:59");
$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);

//Seleciona as informações do bando de dados.
$banco->selectTimeSheet("SEQ_CHAMADO", $vNumPagina, 200000);

//Cabeçalho do relatório
$pdf->SetFillColor(202,202,202);
$pdf->SetFont('Arial', 'B', 9);
//$pdf->SetTextColor(0,160,300);
($nome_recurso_ti != "")? $pdf->Cell(150, 5, "Colaborador : $nome_recurso_ti" ,0 , 1, "L", 1) : $pdf->Cell(150, 5, "Colaborador : Todos" ,0 , 1, "L", 1);
//$pdf->SetTextColor(300,300,300);
//$pdf->SetFillColor(0,160,300);
$pdf->SetFont('arial', 	'B', 8);
$pdf->Cell(22, 5, "Chamado nº"	,1 , 0, "C", 1);
$pdf->Cell(60, 5, "Solicitante"	,1 , 0, "C", 1);
$pdf->Cell(156, 5, "Atividade"	,1 , 0, "C", 1);
$pdf->Cell(39, 5, "Tempo de Execução",1 , 1, "C", 1);

//$pdf->SetFillColor(280,280,280);
$pdf->SetFont('arial', 	'', 7);
$pdf->Ln(2);

if($banco->database->rows >= 0){

	$cont = 2;
	$totalChamadasConcluidas = 0;
	$seqChamado = null;

	while ($row = pg_fetch_array($banco->database->result)){

		$qtdHoras = 0;

		if($row['seq_chamado'] != $seqChamado){

			$banco2 = new time_sheet();
			$banco2->setNUM_MATRICULA($v_NUM_MATRICULA_RECURSO);
			$banco2->setDTH_INICIO($v_DTH_INICIO." 00:00:00");
			$banco2->setDTH_INICIO_FINAL($v_DTH_INICIO_FINAL." 23:59:59");
			$banco2->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
			$banco2->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
			$banco2->selectTimeSheet("SEQ_CHAMADO", $vNumPagina, 200000);

			while ($row2 = pg_fetch_array($banco2->database->result)){

				if($row['seq_chamado'] == $row2['seq_chamado']){
					//$qtdHoras += $row2["qtd_segundos_duracao"];
					$qtdHoras += $pagina->dateDiffHourPlus($row2["dth_inicio_form"], $row2["dth_fim_form"]);
				}
			}

			//Troca a cor da linha
			if($cont % 2 == 0 ){
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFillColor(240,240,240	);
			}
			else {
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFillColor(230,230,230);
			}

			if($qtdHoras == 0 ){
				//$tempoDeExecucao = $pagina->secondsToTime($row["qtd_segundos_duracao"],1);
				$tempoDeExecucao = $pagina->secondsToTime($pagina->dateDiffHourPlus($row["dth_inicio_form"], $row["dth_fim_form"]),1);
			}else {
				$tempoDeExecucao = $pagina->secondsToTime($qtdHoras,1);
			}

			$tempoEmSegundo = $dataEncerramentoEfetivoEmSegundos - $dataInicioEfetivoEmSegundos;
			$pagina->secondsToTime($tempoEmSegundo);

			$dadosChamados->select($row['seq_chamado']);
			//Seleciona o nome do solicitante pela matrícula
			$empregados->select($dadosChamados->getNUM_MATRICULA_SOLICITANTE());
			$tipo_chamado->select($row['seq_chamado']);

			//titulo
			if(strlen($dadosChamados->getTXT_CHAMADO()) > 130 ){

				$titulo  = strtolower( substr($dadosChamados->getTXT_CHAMADO(), 0, 130 ) );
				$titulo2 = strtolower( substr($dadosChamados->getTXT_CHAMADO(), 130, 130));

				if(strlen($dadosChamados->getTXT_CHAMADO()) > 260 ){
					$titulo3 = strtolower( substr($dadosChamados->getTXT_CHAMADO(), 260, 130 ));
				}
				else{$titulo3 = "";}

				if(strlen($dadosChamados->getTXT_CHAMADO()) > 390 ){
					$titulo4 = strtolower( substr($dadosChamados->getTXT_CHAMADO(), 390, 130 ));
				}
				else{$titulo4 = "";}

				if(strlen($dadosChamados->getTXT_CHAMADO()) > 520 ){
					$titulo5 = strtolower( substr($dadosChamados->getTXT_CHAMADO(), 520, 130 )).'...';
				}
				else{$titulo5 = "";}
			}
			else{
				$titulo  = strtolower( $dadosChamados->getTXT_CHAMADO() );
				$titulo2 = "";
			}

			//tipo
			if(strlen($tipo_chamado->getDSC_TIPO_CHAMADO()) > 23){
				$tipo  = substr($tipo_chamado->getDSC_TIPO_CHAMADO(), 0, 23 );
				$tipo2 = substr($tipo_chamado->getDSC_TIPO_CHAMADO(), 23 );
			}
			else{
				 $tipo  = $tipo_chamado->getDSC_TIPO_CHAMADO();
				 $tipo2 = "";
			}
			//solicitante
			$solicitante = ucwords(strtolower(($empregados->getNOME())));

			//Chamado
			$pdf->Cell(22, 6, "{$row['seq_chamado']}",0 , 0, "C", 1);
			//$pdf->Cell(26, 6, "{$row['dth_abertura']}"	,0 , 0, "C", 1);
			$pdf->Cell(60, 6, "  $solicitante"	,0 , 0, "L", 1);

			if( $titulo5 != ""){

				$pdf->Cell(156, 6, "$titulo",0 , 0, "L", 1);
				//Tempo de Execução
				$pdf->Cell(39, 6, "\t\t\t\t\t\t\t\t\t$tempoDeExecucao",0 , 1, "L", 1);

				$pdf->Ln(-1);
				$pdf->Cell(277, 3, "                                                                                                                						$titulo2",0 , 1, "L", 1);
				$pdf->Cell(277, 3, "                                                                                                               						$titulo3",0 , 1, "L", 1);
				$pdf->Cell(277, 3, "                                                                                                               						$titulo4",0 , 1, "L", 1);
				$pdf->Cell(277, 3, "                                                                                                               						$titulo5",0 , 1, "L", 1);
				$pdf->Cell(277, 2, "",0 ,1, "L", 1);
			}
			elseif( $titulo4 != ""){

				$pdf->Cell(156, 6, "$titulo",0 , 0, "L", 1);
				//Tempo de Execução
				$pdf->Cell(39, 6, "\t\t\t\t\t\t\t\t\t$tempoDeExecucao",0 , 1, "L", 1);

				$pdf->Ln(-1);
				$pdf->Cell(277, 3, "                                                                                                                						$titulo2",0 , 1, "L", 1);
				$pdf->Cell(277, 3, "                                                                                                                						$titulo3",0 , 1, "L", 1);
				$pdf->Cell(277, 3, "                                                                                                                						$titulo4",0 , 1, "L", 1);
				$pdf->Cell(277, 2, "",0 ,1, "L", 1);
			}
			elseif( $titulo3 != ""){

				$pdf->Cell(156, 6, "$titulo",0 , 0, "L", 1);
				//Tempo de Execução
				$pdf->Cell(39, 6, "\t\t\t\t\t\t\t\t\t$tempoDeExecucao",0 , 1, "L", 1);

				$pdf->Ln(-1);
				$pdf->Cell(277, 3, "                                                                                                                 						$titulo2",0 , 1, "L", 1);
				$pdf->Cell(277, 3, "                                                                                                                  						$titulo3",0 , 1, "L", 1);
				$pdf->Cell(277, 2, "",0 ,1, "L", 1);
			}
			elseif ($titulo2 != ""){
				$pdf->Cell(156, 6, "$titulo",0 , 0, "L", 1);
				//Tempo de Execução
				$pdf->Cell(39, 6, "\t\t\t\t\t\t\t\t\t$tempoDeExecucao",0 , 1, "L", 1);

				$pdf->Ln(-1);
				$pdf->Cell(277, 3, "                                                                                                                  						$titulo2",0 , 1, "L", 1);
				$pdf->Cell(277, 2, "",0 ,1, "L", 1);
			}
			else {

				$pdf->Cell(156, 6, "$titulo",0 , 0, "L", 1);
				//Tempo de Execução
				$pdf->Cell(39, 6, "\t\t\t\t\t\t\t\t\t$tempoDeExecucao",0 , 1, "L", 1);
			}

			$totalChamadasConcluidas++;
			$cont++;
		}

		$seqChamado = $row['seq_chamado'];
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