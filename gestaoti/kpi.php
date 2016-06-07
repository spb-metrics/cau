<?php
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
require 'include/PHP/class/class.kpi.php';
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	require_once '../cau/include/PHP/autentica.php';
}else{
	require_once 'include/PHP/autentica.php';
}
$pagina = new Pagina();
$kpi = new kpi();
$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];
// =================================================================================
// Passagem de par�metros e sele��o do gr�fico
// =================================================================================
if($vGrafico == "KpiChamadosPorSituacao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorSituacao();
}elseif($vGrafico == "KpiChamadosPorPrioridade"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorPrioridade();
}elseif($vGrafico == "KpiChamadosPorLotacao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorLotacao();
}elseif($vGrafico == "KpiChamadosPorSistemaInformacao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorSistemaInformacao();
}elseif($vGrafico == "KpiChamadosPorSLA"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorSLA();
}elseif($vGrafico == "KpiChamadosPorDependencia"){
	$kpi->KpiChamadosPorDependencia();
}elseif($vGrafico == "KpiChamadosPorEquipe"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorEquipe();
}elseif($vGrafico == "KpiChamadosPorProfissional"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorProfissional();
}elseif($vGrafico == "KpiHorasPorProfissional"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiHorasPorProfissional();
}elseif($vGrafico == "KpiHorasPorEquipe"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiHorasPorEquipe();
}elseif($vGrafico == "KpiChamadosPorAvaliacao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	if($vTipoGrafico == "ML"){
		$kpi->KpiChamadosPorAvaliacaoEvolucaoMensal();
	}else{
		$kpi->KpiChamadosPorAvaliacao();
	} 
}elseif($vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Conhecimento"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
//	$kpi->KpiChamadosPorAvaliacao_Satisfacao_Conhecimento();
	if($vTipoGrafico == "ML"){
		$kpi->KpiChamadosPorAvaliacao_Satisfacao_ConhecimentoEvolucaoMensal();
	}else{
		$kpi->KpiChamadosPorAvaliacao_Satisfacao_Conhecimento();
	} 
}elseif($vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Postura"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	if($vTipoGrafico == "ML"){
		$kpi->KpiChamadosPorAvaliacao_Satisfacao_PosturaEvolucaoMensal();
	}else{
		$kpi->KpiChamadosPorAvaliacao_Satisfacao_Postura();
	} 
	
}elseif($vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	if($vTipoGrafico == "ML"){
		$kpi->KpiChamadosPorAvaliacao_Satisfacao_Tempo_EsperaEvolucaoMensal();
	}else{
		$kpi->KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera();
	} 
	
}elseif($vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	if($vTipoGrafico == "ML"){
		$kpi->KpiChamadosPorAvaliacao_Satisfacao_Tempo_SolucaoEvolucaoMensal();
	}else{
		$kpi->KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao();
	} 
	
}elseif($vGrafico == "KpiChamadosPorAtividade"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorAtividade();
}elseif($vGrafico == "KpiChamadosPorSubtipo"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_TIPO_CHAMADO = $v_SEQ_TIPO_CHAMADO;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorSubtipo();
}elseif($vGrafico == "KpiChamadosPorTipo"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorTipo();
}elseif($vGrafico == "KpiQtdProfissionaisPorEquipe"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiQtdProfissionaisPorEquipe();
}elseif($vGrafico == "KpiValorProfissionaisPorEquipe"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiValorProfissionaisPorEquipe();
}elseif($vGrafico == "KpiQtdSistemasPorLinguagem"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiQtdSistemasPorLinguagem();
}elseif($vGrafico == "KpiQtdSistemasPorBancoDeDados"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiQtdSistemasPorBancoDeDados();
}elseif($vGrafico == "KpiQtdProfissionaisPorAreaAtuacao"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiQtdProfissionaisPorAreaAtuacao();
}elseif($vGrafico == "KpiPorcentagemChamadosEncerradosNoPrazo"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiPorcentagemChamadosEncerradosNoPrazo();
}elseif($vGrafico == "KpiPorcentagemChamadosEncerradosNoPrazoIncidente"){
	$kpi->arFaixaOdometro = array();
	$kpi->arFaixaOdometro[0][0] = "0";
	$kpi->arFaixaOdometro[0][1] = "90";
	$kpi->arFaixaOdometro[0][2] = "red";

	$kpi->arFaixaOdometro[1][0] = "90";
	$kpi->arFaixaOdometro[1][1] = "100";
	$kpi->arFaixaOdometro[1][2] = "green";

	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiPorcentagemChamadosEncerradosNoPrazoIncidente();
}elseif($vGrafico == "KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao"){
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

	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao();
}elseif($vGrafico == "KpiPorcentagemChamadosEmAndamentoNoPrazo"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiPorcentagemChamadosEmAndamentoNoPrazo();
}elseif($vGrafico == "KpiQtdChamadosPorSLAPorMes"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiQtdChamadosPorSLAPorMes();//PENDENTE
}elseif($vGrafico == "KpiQtdAvaliacoesPorMes"){
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiQtdAvaliacoesPorMes();
}elseif($vGrafico == "KpiChamadosPorTipoOcorrencia"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorTipoOcorrencia();
}elseif($vGrafico == "KpiChamadosPorSLANivel1"){
	$kpi->arFaixaOdometro = array();
	$kpi->arFaixaOdometro[0][0] = "0";
	$kpi->arFaixaOdometro[0][1] = "40";
	$kpi->arFaixaOdometro[0][2] = "red";

	$kpi->arFaixaOdometro[1][0] = "40";
	$kpi->arFaixaOdometro[1][1] = "50";
	$kpi->arFaixaOdometro[1][2] = "yellow";

	$kpi->arFaixaOdometro[2][0] = "50";
	$kpi->arFaixaOdometro[2][1] = "100";
	$kpi->arFaixaOdometro[2][2] = "green";

	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->KpiChamadosPorSLANivel1();
}elseif($vGrafico == "TempoMedioNivel1"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
	$kpi->TempoMedioNivel1();
}

// =================================================================================
// Montar o gr�fico
// =================================================================================
if($vGrafico != ""){
	// Montar o gr�fico
	if($vTipoGrafico == "L"){
		$kpi->GraficoLinhaSimples($kpi->dados, $kpi->label);
	}elseif($vTipoGrafico == "ML"){
		$Width=580;$Height=250;$LeftMargin=60; $RightMargin=20; $TopMargin=50; $BottomMargin=140;
		$kpi->GraficoMultiLinhaSimples($kpi->dados, $kpi->label,"","",$Width,$Height,$LeftMargin, $RightMargi, $TopMargin, $BottomMargin);
	}elseif($vTipoGrafico == "B"){
		$kpi->GraficoBarrasSimples($kpi->dados, $kpi->label);
	}elseif($vTipoGrafico == "P"){
		$kpi->GraficoPizzaSimples($kpi->dados, $kpi->label);
	}elseif($vTipoGrafico == "O"){
		$kpi->GraficoOdometro($kpi->valorOdometro);
	}
}
?>
