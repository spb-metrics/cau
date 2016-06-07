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
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.parametro.php';
require_once "include/PHP/FPDF/fpdf.php";

$pagina = new Pagina();
$parametro = new parametro();

function Encerramento_Efetivo($v_DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO){
	require_once 'include/PHP/class/class.pagina.php';
	require_once 'include/PHP/class/dateObj.class.php';
	$myDate = new dateObj();
	$pagina = new Pagina();
	if($v_DTH_ENCERRAMENTO_EFETIVO != ""){
		$vSegundosDiferenca = $pagina->dateDiffHourPlus(str_replace("-","/",$v_DTH_ENCERRAMENTO_EFETIVO), $v_DTH_ENCERRAMENTO_PREVISAO);
		if($vSegundosDiferenca < 0){ // Chamado em atraso
			$vTempoRestante = $pagina->secondsToTime($vSegundosDiferenca*-1);
			return " - Encerrado com atraso de $vTempoRestante";
		}else{
			return " - Encerrado dentro do prazo";
		}
	}else{
		$vTempoRestante = $pagina->FormatarData($myDate->diff($v_DTH_ENCERRAMENTO_PREVISAO, 'all'));
		if($pagina->dateDiffHour($v_DTH_ENCERRAMENTO_PREVISAO) < 0){ // Chamado em atraso
			return " - Em atraso de $vTempoRestante";
		}else{
			return " - Tempo restante de $vTempoRestante";
		}
	}
}

// Montando o PDF
class PDF extends FPDF {
	var $cabecalho;     // cabecalho para as colunas
	var $titulo;
	var $subtitulo;
	var $rodape;
	// Construtor: Chama a classe FPDF
    function PDF($or = 'P') {
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

	$vTitulo = "";

	$pdf = new PDF('P'); // P - Portrait | L - Landscape
	// Nome - O que aparece no topo do documento
	$pdf->titulo = "Chamados em atendimento";
	$pdf->subtitulo = $vTitulo;
	// Cabe�alho do grid do relat�rio
	$pdf->cabecalho = "";
	// Rodap� do documento
	$pdf->rodape = $parametro->GetValorParametro("NOM_INSTITUICAO")." - ".$parametro->GetValorParametro("NOM_AREA_TI")." - ";

    $pdf->Open();
    $pdf->AddPage();

	// Loop dos chamado selecionados
	for($i=0; $i<count($imprimir);$i++){
		$banco = new chamado();
		$banco->select($imprimir[$i]);

		//Troca a cor da linha
		if($i % 2 == 0 ){
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFillColor(255,255,255	);
		}
		else {
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFillColor(230,230,230);
		}

	    $pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, "Chamado:", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $banco->SEQ_CHAMADO, 0, 0,'L',1);

		if($banco->SEQ_CHAMADO_MASTER != ""){
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(0, 5, "", 0, 1);
			$pdf->Cell(30, 5, "Chamado Master:", 0, 0, 'R', 1);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(160, 5, $banco->SEQ_CHAMADO_MASTER, 0, 0, 'L', 1);
		}

		$tipo_ocorrencia = new tipo_ocorrencia();
		$tipo_ocorrencia->select($banco->SEQ_TIPO_OCORRENCIA);

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, $pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $tipo_ocorrencia->NOM_TIPO_OCORRENCIA, 0, 0, 'L', 1);

		require_once 'include/PHP/class/class.subtipo_chamado.php';
		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->select($banco->SEQ_SUBTIPO_CHAMADO);

		require_once 'include/PHP/class/class.tipo_chamado.php';
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, $pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $tipo_chamado->DSC_TIPO_CHAMADO, 0, 0, 'L', 1);

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, $pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $subtipo_chamado->DSC_SUBTIPO_CHAMADO, 0, 0, 'L', 1);

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, $pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $banco->DSC_ATIVIDADE_CHAMADO, 0, 0, 'L', 1);

		if($banco->SEQ_ITEM_CONFIGURACAO != ""){ // Mostrar o sistema de informa��o e a prioridade estabelecida pelo cliente
			require_once 'include/PHP/class/class.item_configuracao.php';
			$item_configuracao = new item_configuracao();
			$item_configuracao->select($banco->SEQ_ITEM_CONFIGURACAO);

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(0, 5, "", 0, 1);
			$pdf->Cell(30, 5, "Sistema de Informa��o:", 0, 0, 'R', 1);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(160, 5, $item_configuracao->SIG_ITEM_CONFIGURACAO." - ".$item_configuracao->NOM_ITEM_CONFIGURACAO, 0, 0, 'L', 1);
		}

		$situacao_chamado = new situacao_chamado();
		$situacao_chamado->select($banco->SEQ_SITUACAO_CHAMADO);

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, "Situa��o:", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $situacao_chamado->DSC_SITUACAO_CHAMADO, 0, 0, 'L', 1);

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, "Solicita��o:", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $banco->TXT_CHAMADO, 0, 0, 'L', 1);

		// Dados do solicitante
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		$empregados->select($banco->NUM_MATRICULA_SOLICITANTE);

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, "Solicitante:", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $empregados->NOME." - Ramal: ".$empregados->NUM_VOIP." - E-mail: ".$empregados->DES_EMAIL, 0, 0, 'L', 1);

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, "Diretoria/Lota��o:", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $empregados->DEP_SIGLA." / ".$empregados->UOR_SIGLA, 0, 0, 'L', 1);

		// Localiza��o do cliente
		if($banco->SEQ_LOCALIZACAO_FISICA != ""){
			$header = array();
			require_once 'include/PHP/class/class.localizacao_fisica.php';
			$localizacao_fisica = new localizacao_fisica();
			$localizacao_fisica->select($banco->SEQ_LOCALIZACAO_FISICA);

			require_once 'include/PHP/class/class.edificacao.php';
			$edificacao = new edificacao();
			$edificacao->select($localizacao_fisica->SEQ_EDIFICACAO);

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(0, 5, "", 0, 1);
			$pdf->Cell(30, 5, "Localiza��o:", 0, 0, 'R', 1);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(160, 5, $edificacao->NOM_EDIFICACAO." - ".$localizacao_fisica->NOM_LOCALIZACAO_FISICA, 0, 0, 'L', 1);
		}

		// Pessoa de contato
		if($banco->NUM_MATRICULA_CADASTRANTE != ""){
			$empregados = new empregados();
			$empregados->select($banco->NUM_MATRICULA_CADASTRANTE);

			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(0, 5, "", 0, 1);
			$pdf->Cell(30, 5, "Cadastrado por:", 0, 0, 'R', 1);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(160, 5, $empregados->NOME." - Ramal: ".$empregados->NUM_DDD."-".$empregados->NUM_VOIP, 0, 0, 'L', 1);

		}

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(0, 5, "", 0, 1);
		$pdf->Cell(30, 5, "Data de Abertura:", 0, 0, 'R', 1);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(160, 5, $banco->DTH_ABERTURA, 0, 0, 'L', 1);

		// Data de encerramento
		if($banco->DTH_ENCERRAMENTO_PREVISAO != ""){
			// Previs�o de encerramento
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(0, 5, "", 0, 1);
			$pdf->Cell(30, 5, "Vencimento:", 0, 0, 'R', 1);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(160, 5, $banco->DTH_ENCERRAMENTO_PREVISAO.Encerramento_Efetivo($banco->DTH_ENCERRAMENTO_EFETIVO, $banco->DTH_ENCERRAMENTO_PREVISAO), 0, 0, 'L', 1);

		}else{
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->Cell(0, 5, "", 0, 1);
			$pdf->Cell(30, 5, "Vencimento:", 0, 0, 'R', 1);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Cell(160, 5, "Aguardando previs�o", 0, 0, 'L', 1);
		}



		$pdf->Cell(0, 5, "", 0, 1,'');
		$pdf->Cell(190, 2, "", 0, 1,'',1);
	}

	$pdf->Output();
?>