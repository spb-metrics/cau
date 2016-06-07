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
require 'include/PHP/class/class.pagina.php';
require "include/PHP/FPDF/fpdf.php";
require 'include/PHP/class/class.time_sheet.php';
$pagina = new Pagina();


//if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
/*TODO: NOVO PERFIL ACESSO*/
if($pagina->isGestorTI($_SESSION["SEQ_PERFIL_ACESSO"]) || $pagina->isAdministrador($_SESSION["SEQ_PERFIL_ACESSO"])){	
	
	// Usu�rio pode ver tudo

}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Lider de equipe pode ver todas da sua equipe
	if($v_SEQ_EQUIPE_TI != ""){
		$pagina->ScriptAlert("Acesso n�o permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
}else{ // Colaborador ve somente o seu
	if($v_SEQ_EQUIPE_TI != ""){
		$pagina->ScriptAlert("Acesso n�o permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
	if($v_NUM_MATRICULA_RECURSO != ""){
		$pagina->ScriptAlert("Acesso n�o permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
}


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
		/*
	    // Inserir o cabe�alho do grid se for o caso
	    $this->SetFont('Arial', '', 10);
	    $this->SetX(10);
	    $this->Cell($this->GetStringWidth($this->cabecalho), 5, $this->cabecalho, 0, 1);

		// Impimindo a linha de cabe�alho do grid
		$this->SetFont('Arial', '', 7);
		$this->SetX(0);
		$this->Cell(0, 0, "", 0, 1, "C");
	    $this->Cell(5, 5, "Dia", 1, 0, "C");
	    $this->Cell(40, 5, "Nome", 1, 0, "C");
	    $this->Cell(7, 5, "Grau", 1, 0, "C");
	    $this->Cell(10, 5, "M�s", 1, 0, "C");
	    $this->Cell(23, 5, "C�digo", 1, 0, "C");
		$this->Cell(7, 5, "Qtd", 1, 0, "C");
		*/
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
	$pdf->titulo = "Relat�rio de Horas Trabalhadas por Chamado";
	$pdf->subtitulo = "Per�odo selecionado: de $v_DTH_INICIO a $v_DTH_INICIO_FINAL";
	// Cabe�alho do grid do relat�rio
	$pdf->cabecalho = "";
	// Rodap� do documento
	$pdf->rodape = "Superintend�ncia de Tecnologia da Informa��o - PRTI - ";

    $pdf->Open();
    $pdf->AddPage();

	$pdf->SetFont('Arial', '', 11);

	// Listar Equipes
	require 'include/PHP/class/class.equipe_ti.php';
	require 'include/PHP/class/class.recurso_ti.php';
	$equipe_ti = new equipe_ti();
	$equipe_ti->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
	$equipe_ti->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	$equipe_ti->selectParam("NOM_EQUIPE_TI");
	if($equipe_ti->database->rows > 0){
		while ($row = pg_fetch_array($equipe_ti->database->result)){
			// Imprimir o cabe�alho da equipe de TI
			$pdf->Cell(0, 5, "", 0, 1);
			$pdf->SetFillColor(202,202,202);
			$pdf->Cell(193, 5, $row["NOM_EQUIPE_TI"], 0, 0, "left", 1);
			$pdf->SetFillColor(255,255,255);

			// Listar profissionais
			$recurso_ti = new recurso_ti();
			$recurso_ti->setSEQ_EQUIPE_TI($row["SEQ_EQUIPE_TI"]);
			$recurso_ti->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
			$recurso_ti->selectParam("NOME");
			if($recurso_ti->database->rows == 0){
				$pdf->Cell(0, 5, "", 0, 1);
				$pdf->Cell(100, 5, "Nenhum recurso alocado.", 0, 0);
			}else{
				while ($row1 = pg_fetch_array($recurso_ti->database->result)){
					// Imprimir nome do recurso
					$pdf->Cell(0, 5, "", 0, 1);
					$pdf->Cell(100, 5, $row1["NOME"]." - ".$row1["NOM_PERFIL_RECURSO_TI"], 0, 0);

					// listar lan�amentos de dias trabalhados
					$time_sheet = new time_sheet();
					$time_sheet->setSEQ_EQUIPE_TI($row["SEQ_EQUIPE_TI"]);
					$time_sheet->setNUM_MATRICULA($row1["NUM_MATRICULA_RECURSO"]);
					$time_sheet->setDTH_INICIO($v_DTH_INICIO." 00:00:00");
					$time_sheet->setDTH_INICIO_FINAL($v_DTH_INICIO_FINAL." 23:59:59");
					$time_sheet->relatorioHorasTrabalhadasChamado();
					if($time_sheet->database->rows == 0){
						$pdf->SetFillColor(202,202,202);
						$pdf->Cell(0, 5, "", 0, 1);
						$pdf->Cell(3, 5, "", 0, 0, "C", 0);
						$pdf->Cell(189, 5, "Nenhum lan�amento registrado no per�odo", 0, 0, "C", 1);
					}else{
						// Tabela de resultados
						$tabela = array();
						$v_SOMA = 0;
						$v_SOMA_LANCAMENTOS = 0;
						while ($row2 = pg_fetch_array($time_sheet->database->result)){
							$v_SOMA = $v_SOMA + $row2["QTD_SEGUNDOS_DURACAO"];
							$v_SOMA_LANCAMENTOS = $v_SOMA_LANCAMENTOS + $row2["QTD_LANCAMENTOS"];
							$header = array();
							$header[] = array($row2["SEQ_CHAMADO"], "C", "", "");
							$header[] = array($pagina->secondsToTime($row2["QTD_SEGUNDOS_DURACAO"],1), "L", "", "");
							$header[] = array($row2["QTD_LANCAMENTOS"], "R", "", "");
							$tabela[] = $header;
						}

						// Imprimir os lan�amentos registrados no per�odo
						$pdf->SetFillColor(202,202,202);
						$pdf->Cell(0, 5, "", 0, 1);
						$pdf->Cell(3, 5, "", 0, 0, "C", 0);
						$pdf->Cell(63, 5, "Chamado", 0, 0, "C", 1);
						$pdf->Cell(63, 5, "Tempo Registrado", 0, 0, "C", 1);
						$pdf->Cell(63, 5, "Qtd. Lan�amentos", 0, 0, "C", 1);

						for($i=0; $i<count($tabela); $i++){
							if($i % 2 == 0) $pdf->SetFillColor(255,255,255);
							else $pdf->SetFillColor(224,224,224);
							$pdf->Cell(0, 5, "", 0, 1);
							$pdf->Cell(3, 5, "", 0, 0, "center", 0);
							for($j=0; $j<count($tabela[$i]); $j++){
								$pdf->Cell(63, 5, $tabela[$i][$j][0], 0, 0, $tabela[$i][$j][1], 1);
							}
						}
						$pdf->SetFillColor(202,202,202);
						$pdf->Cell(0, 5, "", 0, 1);
						$pdf->Cell(3, 5, "", 0, 0, "C");
						$pdf->Cell(63, 5, "Total", 0, 0, "C", 1);
						$pdf->Cell(63, 5, $pagina->secondsToHours($v_SOMA,1), 0, 0, "L", 1);
						$pdf->Cell(63, 5, $v_SOMA_LANCAMENTOS, 0, 0, "R", 1);

					}
					$pdf->MultiCell(100,5,"");
				}
			}
			$pdf->MultiCell(100,5,"");
		}
	}
	$pdf->Output();
?>