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
require 'include/PHP/class/class.pagina.php';
require "include/PHP/FPDF/fpdf.php";
require 'include/PHP/class/class.time_sheet.php';
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.chamado.php';
require 'include/PHP/class/class.parametro.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$pagina = new Pagina();
$parametro = new parametro();
/*****************************************************************/
/*
if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
	// Usu�rio pode ver tudo

}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Lider de equipe pode ver todas da sua equipe
	if($v_SEQ_EQUIPE_TI == ""){
		$pagina->ScriptAlert("Acesso n�o permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
}else{ // Colaborador ve somente o seu
	if($v_SEQ_EQUIPE_TI == ""){
		$pagina->ScriptAlert("Acesso n�o permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
	if($v_NUM_MATRICULA_RECURSO == ""){
		$pagina->ScriptAlert("Acesso n�o permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
}
*/
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
			$this->SetFont('Arial', 'B', 7.5);
			$this->Cell(0, 5, "", 0, 1);
			$this->Cell(190, 35, $this->subtitulo,0,0,"C");
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
	    $data = strftime("%d/%m/%Y �s %H:%M");
	    $this->Cell(200, 6, $this->rodape.$data, 0, 0, 'R');
    }
}

// ====================================================================================================================================
// Informa��es do corpo
// ====================================================================================================================================

$pdf = new PDF('P'); // P - Portrait | L - Landscape
// Nome - O que aparece no topo do documento
$pdf->titulo = "Relat�rio de Atividade ";
$pdf->subtitulo = "Nome: $nome      Cargo/Fun��o: $cargo      Per�odo selecionado: de $v_DTH_INICIO a $v_DTH_INICIO_FINAL";
// Cabe�alho do grid do relat�rio
$pdf->cabecalho = "";
// Rodap� do documento
$pdf->rodape = $parametro->GetValorParametro("NOM_AREA_TI");
$pdf->Open();
$pdf->AddPage();
//Criando o objeto.
$empregados = new empregados();
//$mat = substr( $V_NUM_MATRICULA_SOLICITANTE, 1);
$mat = $empregados->GetNumeroMatricula( $V_NUM_MATRICULA_SOLICITANTE );
$nome 		= $empregados->GetNomeEmpregado( $mat );
//Importando a classe perfil recurso
require 'include/PHP/class/class.perfil_recurso_ti.php';
//Criando o objeto.
$perfil = new perfil_recurso_ti();
$perfil->database->query("SELECT p.nom_perfil_recurso_ti as cargo
						  FROM gestaoti.perfil_recurso_ti p, gestaoti.recurso_ti r
						  WHERE p.seq_perfil_recurso_ti = r.seq_perfil_recurso_ti
						  and num_matricula_recurso = $mat");

$array = (pg_fetch_array($perfil->database->result));

if($array != null ){
	foreach ( $array  as $c ){
		$cargo = $c;
	}
}

$pdf->SetFont('Arial', '', 11);
//Nomes das colunas
$pdf->SetTextColor(300,300,300);
$pdf->SetFillColor(0,160,300);
$pdf->SetFontSize(6);
$pdf->SetFont('arial', 'B');
$pdf->Cell(0, 3.2, "", 0, 1);
$pdf->Cell(0, 3.2, "", 0, 0, "C", 0);
$pdf->Ln(1);
$pdf->Cell(15, 3.2, "Chamado", 1, 0, "C", 1);
$pdf->Cell(60, 3.2, "Atividade", 1, 0, "C", 1);
$pdf->Cell(25, 3.2, "Abertura do Chamando", 1, 0, "C", 1);
$pdf->Cell(20, 3.2, "Previs�o In�cio", 1, 0, "C", 1);
$pdf->Cell(23, 3.2, "In�cio Efetivo", 1, 0, "C", 1);
$pdf->Cell(22, 3.2, "T�rmino Efetivo", 1, 0, "C", 1);
$pdf->Cell(25, 3.2, "Situa��o", 1, 0, "C", 1);
$pdf->Ln(4);

$sql = "SELECT distinct chamado.seq_chamado AS SEQ_CHAMADO,
				to_char(aprovacao_chamado.dth_prevista, 'DD/MM/YYYY') AS DTH_PREVISTA,
				to_char(chamado.dth_abertura, 'DD/MM/YYYY') AS DTH_ABERTURA,
				to_char(chamado.dth_inicio_previsao, 'DD/MM/YYYY') AS DTH_INICIO_PREVISAO,
				atividade_chamado.SEQ_ATIVIDADE_CHAMADO,
				atribuicao_chamado.SEQ_ATRIBUICAO_CHAMADO,
				atividade_chamado.dsc_atividade_chamado AS DSC_ATIVIDADE_CHAMADO,
				to_char(atribuicao_chamado.dth_inicio_efetivo, 'DD/MM/YYYY') AS DTH_INICIO_EFETIVO,
				to_char(atribuicao_chamado.dth_encerramento_efetivo, 'DD/MM/YYYY') AS DTH_ENCERRAMENTO_EFETIVO
		FROM 	gestaoti.chamado, gestaoti.atividade_chamado, gestaoti.atribuicao_chamado, gestaoti.aprovacao_chamado
		WHERE	chamado.dth_abertura
		BETWEEN to_date('$v_DTH_INICIO', 'DD/MM/YYYY') AND to_date( '$v_DTH_INICIO_FINAL', 'DD/MM/YYYY') + 1
				and chamado.seq_atividade_chamado = atividade_chamado.seq_atividade_chamado
				and chamado.seq_chamado = atribuicao_chamado.seq_chamado
				and chamado.seq_chamado = aprovacao_chamado.seq_chamado
				and atribuicao_chamado.num_matricula = $mat
		ORDER BY  chamado.seq_chamado, to_char(chamado.dth_abertura, 'DD/MM/YYYY')";

$chamado_novo = new chamado();
$chamado_novo->database->query();

//$countX = $chamado_novo->database->rows;
$countX = 1;
$cont2 = 2;

while ($row2 = pg_fetch_array($chamado_novo->database->result)){

	//Troca a cor da linha
	if($cont2 % 2 == 0 ){
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFillColor(240,240,240);
	}
	else{
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFillColor(230,230,230);
	}

	$pdf->SetFontSize(6);
	$pdf->SetFont('arial');
	//C�digo do Chamado
	$pdf->Cell(15, 5, $row2["seq_chamado"], 0, 0, "C", 1);

	if( strlen($row2['dsc_atividade_chamado']) <= 50 ){
		//Descri��o da atividade do chamado
		$pdf->Cell(60, 5, $row2['dsc_atividade_chamado'], 0, 0, "C", 1);
	}else{
		//Descri��o da atividade do chamado
		$pdf->Cell(60, 5, substr($row2['dsc_atividade_chamado'], 0, 50 ).'...', 0, 0, "C", 1);
	}
	//Data de abertura
	$pdf->Cell(25, 5, $row2['dth_abertura'], 0, 0, "C", 1);
	//Data de previs�o
	$pdf->Cell(20, 5, $row2['dth_inicio_previsao'], 0, 0, "C", 1);

	//Data de inicio efetivo
	$pdf->Cell(23, 5, $row2['dth_inicio_efetivo'], 0, 0, "C", 1);

	if($row2['dth_encerramento_efetivo'] != ""){
		//Data de encerramento efetivo
		$pdf->Cell(22, 5, $row2['dth_encerramento_efetivo'], 0, 0, "C", 1);
	}else {
		$pdf->Cell(22, 5, '-----', 0, 0, "C", 1);
	}

	if($row2['dth_encerramento_efetivo'] != "" ){
		if( $row2['dth_encerramento_efetivo'] > $row2['dth_prevista'] ){
			$pdf->Cell(25, 5, 'Finalizado com atraso', 0, 0, "L", 1);
		}else{
			$pdf->Cell(25, 5, 'Finalizado no prazo', 0, 0, "L", 1);
		}
	}else{
		 $pdf->Cell(25, 5, 'Em andamento', 0, 0, "L", 1);
	}

	$pdf->MultiCell(100,5,"");
	$countX++;
	$cont2++;
}

$pdf->Ln(2);
$pdf->SetFont('arial', 'B', 6);
$pdf->SetTextColor(300,300,300);
$pdf->SetFillColor(0,160,300);
$pdf->Cell(190, 3, 'Total de Registros - 111'.$countX, 1, 0, "R", 1);
$pdf->Output();
?>