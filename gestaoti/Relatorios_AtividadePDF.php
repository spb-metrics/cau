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
require 'include/PHP/class/class.pagina.php';
require "include/PHP/FPDF/fpdf.php";
require 'include/PHP/class/class.time_sheet.php';
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.chamado.php';
require 'include/PHP/class/class.parametro.php';
$pagina = new Pagina();
$parametro = new parametro();

/*
if($_SESSION["SEQ_PERFIL_ACESSO"] == "5" || $_SESSION["SEQ_PERFIL_ACESSO"] == "2"){ // ==== Gestor PRTI pode ver tudo
	// Usuário pode ver tudo

}elseif($_SESSION["FLG_LIDER_EQUIPE"] == "S"){ // Lider de equipe pode ver todas da sua equipe
	if($v_SEQ_EQUIPE_TI == ""){
		$pagina->ScriptAlert("Acesso não permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
}else{ // Colaborador ve somente o seu
	if($v_SEQ_EQUIPE_TI == ""){
		$pagina->ScriptAlert("Acesso não permitido");
		$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
	}
	if($v_NUM_MATRICULA_RECURSO == ""){
		$pagina->ScriptAlert("Acesso não permitido");
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
		// Informações do cabecalho
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
		// Número de página no canto suprior direito
		$this->SetFont('Arial', '', 10);
	    $this->SetX(-30);
	    $this->Cell(30, 10, "Página: ".$this->PageNo()."/{nb}", 0, 1); // imprime página X/Total de Páginas

		// Linha divisória do cabeçalho com o corpo do documento
	    $this->SetX(-10);
	    $this->line(10, 26, $this->GetX(), 26); // Desenha uma linha

		$this->SetY(24);

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
	    $data = strftime("%d/%m/%Y às %H:%m");
	    $this->Cell(200, 6, $this->rodape.$data, 0, 0, 'R');
    }
}

// ====================================================================================================================================
// Informações do corpo
// ====================================================================================================================================

	$empregados = new empregados();
	//$mat = substr( $V_NUM_MATRICULA_SOLICITANTE, 1);
	$mat = $empregados->GetNumeroMatricula( $V_NUM_MATRICULA_SOLICITANTE );
	$nome = $empregados->GetNomeEmpregado( $mat );

	require 'include/PHP/class/class.perfil_recurso_ti.php';

	$perfil = new perfil_recurso_ti();
	$perfil->database->query("select p.nom_perfil_recurso_ti as cargo
							  from gestaoti.perfil_recurso_ti p, gestaoti.recurso_ti r
							  where p.seq_perfil_recurso_ti = r.seq_perfil_recurso_ti and num_matricula_recurso = $mat");

	$array = (pg_fetch_array($perfil->database->result));

	if($array != null ){
		foreach ( $array  as $c ){
			$cargo = $c;
		}
	}

	$pdf = new PDF('P'); // P - Portrait | L - Landscape
	// Nome - O que aparece no topo do documento
	$pdf->titulo = "Relatório de Atividade ";
	$pdf->subtitulo = "Nome: $nome      Cargo/Função: $cargo      Período selecionado: de $v_DTH_INICIO a $v_DTH_INICIO_FINAL";
	// Cabeçalho do grid do relatório
	$pdf->cabecalho = "";
	// Rodapé do documento
	$pdf->rodape = $parametro->GetValorParametro("NOM_AREA_TI");

    $pdf->Open();
    $pdf->AddPage();

	$pdf->SetFont('Arial', '', 11);

	//Nomes das colunas
	$pdf->SetFillColor(202,202,202);
	$pdf->SetFontSize(7);
	$pdf->SetFont('arial', 'B');
	$pdf->Cell(0, 5, "", 0, 1);
	$pdf->Cell(0, 5, "", 0, 0, "C", 0);
	$pdf->Ln(1);
	$pdf->Cell(20, 5, "Chamada",1, 0, "C", 1);
	$pdf->Cell(45, 5, "Atividade", 1, 0, "C", 1);
	$pdf->Cell(30, 5, "Abertura do Chamando", 1, 0, "C", 1);
	$pdf->Cell(25, 5, "Previsão Início", 1, 0, "C", 1);
	$pdf->Cell(23, 5, "Início Efetivo", 1, 0, "C", 1);
	$pdf->Cell(22, 5, "Término Efetivo", 1, 0, "C", 1);
	$pdf->Cell(25, 5, "Situação", 1, 0, "C", 1);
	$pdf->Ln(6);

	$chamado_novo = new chamado();

	$chamado_novo->database->query("SELECT chamado.seq_chamado AS SEQ_CHAMADO,
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

											AND chamado.seq_atividade_chamado = atividade_chamado.seq_atividade_chamado
											AND chamado.seq_chamado = atribuicao_chamado.seq_chamado
											AND chamado.seq_chamado = aprovacao_chamado.seq_chamado
											AND atribuicao_chamado.num_matricula = $mat

									ORDER BY  chamado.seq_chamado, chamado.dth_abertura");

	$count = 0;
	$cont  = 2;

	while ($row2 = pg_fetch_array($chamado_novo->database->result)){


		//Troca a cor da linha
		if($cont % 2 == 0 ){
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFillColor(240,240,240	);
		}
		else {
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFillColor(230,230,230);
		}


		$pdf->SetFontSize(7);
		$pdf->SetFont('arial');
		$pdf->Cell(0, 1, "", 0, 1);
		//$pdf->SetFillColor(300,300,300);
		//Código do Chamado
		$pdf->Cell(20, 5, $row2["seq_chamado"], 1, 0, "C", 1);

		if( strlen($row2['dsc_atividade_chamado']) <= 30 ){
			//Descrição da atividade do chamado
			$pdf->Cell(45, 5, $row2['dsc_atividade_chamado'], 1, 0, "C", 1);
		}
		else{
			$pdf->Cell(45, 5, substr($row2['dsc_atividade_chamado'], 0, 32 ).'...', 1, 0, "C", 1);
		}
		//Data de abertura
		$pdf->Cell(30, 5, $row2['dth_abertura'], 1, 0, "C", 1);
		//Data de previsão
		$pdf->Cell(25, 5, $row2['dth_inicio_previsao'], 1, 0, "C", 1);
		//Data de inicio efetivo
		$pdf->Cell(23, 5, $row2['dth_inicio_efetivo'], 1, 0, "C", 1);
		//Data de encerramento efetivo
		$pdf->Cell(22, 5, $row2['dth_encerramento_efetivo'], 1, 0, "C", 1);

		if($row2['dth_encerramento_efetivo'] != null ){

			if( $row2['dth_encerramento_efetivo'] > $row2['dth_prevista'] ){

				$pdf->Cell(25, 5, 'Finalizado com atraso', 1, 0, "C", 1);
			}
			else{
				$pdf->Cell(25, 5, 'Finalizado no prazo', 1, 0, "C", 1);
			}
		}
		else{
			 $pdf->Cell(25, 5, 'Em andamento', 1, 0, "C", 1);
		}

		$pdf->MultiCell(100,5,"");

		$count++;
		$cont++;
	}
	if( $count != 0 ){
		$pdf->Ln(5);
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(40, 5, 'Total de Registros - '.$count, 0, 0, "C", 1);
		$pdf->MultiCell(100,0,"");
		$pdf->Output();
	}
	else{
		$pdf->Ln(5);
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(40, 5, 'Total de Registros - 0', 0, 0, "C", 1);
		$pdf->MultiCell(100,0,"");
		$pdf->Output();
	}

?>