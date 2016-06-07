<?php
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
require 'include/PHP/class/class.kpi2.php';
$pagina = new Pagina();
$kpi = new kpi2();

// =================================================================================
// Passagem de parâmetros e seleção do gráfico
// =================================================================================
if($vGrafico == "KpiChamadosPorSituacao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorSituacao();
}elseif($vGrafico == "KpiChamadosPorPrioridade"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorPrioridade();
}elseif($vGrafico == "KpiChamadosPorLotacao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorLotacao();
}elseif($vGrafico == "KpiChamadosPorSistemaInformacao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorSistemaInformacao();
}elseif($vGrafico == "KpiChamadosPorSLA"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorSLA();
}elseif($vGrafico == "KpiChamadosPorDependencia"){
	$kpi->KpiChamadosPorDependencia();
}elseif($vGrafico == "KpiChamadosPorEquipe"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorEquipe();
}elseif($vGrafico == "KpiChamadosPorProfissional"){
	$kpi->KpiChamadosPorProfissional();
}elseif($vGrafico == "KpiHorasPorProfissional"){
	$kpi->KpiHorasPorProfissional();
}elseif($vGrafico == "KpiHorasPorEquipe"){
	$kpi->KpiHorasPorEquipe();
}elseif($vGrafico == "KpiChamadosPorAvaliacao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorAvaliacao();
}elseif($vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Conhecimento"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorAvaliacao_Satisfacao_Conhecimento();
}elseif($vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Postura"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorAvaliacao_Satisfacao_Postura();
}elseif($vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera();
}elseif($vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao();
}elseif($vGrafico == "KpiChamadosPorAtividade"){
	$kpi->KpiChamadosPorAtividade();
}elseif($vGrafico == "KpiChamadosPorSubtipo"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->SEQ_TIPO_CHAMADO = $v_SEQ_TIPO_CHAMADO;
	$kpi->KpiChamadosPorSubtipo();
}elseif($vGrafico == "KpiChamadosPorTipo"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiChamadosPorTipo();
}elseif($vGrafico == "KpiQtdProfissionaisPorEquipe"){
	$kpi->KpiQtdProfissionaisPorEquipe();
}elseif($vGrafico == "KpiValorProfissionaisPorEquipe"){
	$kpi->KpiValorProfissionaisPorEquipe();
}elseif($vGrafico == "KpiQtdSistemasPorLinguagem"){
	$kpi->KpiQtdSistemasPorLinguagem();
}elseif($vGrafico == "KpiQtdSistemasPorBancoDeDados"){
	$kpi->KpiQtdSistemasPorBancoDeDados();
}elseif($vGrafico == "KpiQtdProfissionaisPorAreaAtuacao"){
	$kpi->KpiQtdProfissionaisPorAreaAtuacao();
}elseif($vGrafico == "KpiPorcentagemChamadosEncerradosNoPrazo"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
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
	$kpi->KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao();
}elseif($vGrafico == "KpiPorcentagemChamadosEmAndamentoNoPrazo"){
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
	$kpi->KpiPorcentagemChamadosEmAndamentoNoPrazo();
}elseif($vGrafico == "KpiQtdChamadosPorSLAPorMes"){
	$kpi->KpiQtdChamadosPorSLAPorMes();
}elseif($vGrafico == "KpiQtdAvaliacoesPorMes"){
	$kpi->KpiQtdAvaliacoesPorMes();
}elseif($vGrafico == "KpiChamadosPorTipoOcorrencia"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI;
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
	$kpi->KpiChamadosPorSLANivel1();
}elseif($vGrafico == "TempoMedioNivel1"){
	$kpi->DTH_INICIO = $v_DTH_INICIO;
	$kpi->DTH_FIM = $v_DTH_FIM;
	$kpi->TempoMedioNivel1();
}

// =================================================================================
// Montar o gráfico
// =================================================================================
if($vGrafico != ""){
	// Montar o gráfico
	if($vTipoGrafico == "L"){
		$kpi->GraficoLinhaSimples($kpi->dados, $kpi->label);
	}elseif($vTipoGrafico == "B"){
		$kpi->GraficoBarrasSimples($kpi->dados, $kpi->label);
	}elseif($vTipoGrafico == "P"){
		$kpi->GraficoPizzaSimples($kpi->dados, $kpi->label);
	}elseif($vTipoGrafico == "O"){
		$kpi->GraficoOdometro($kpi->valorOdometro);
	}
}
?>
