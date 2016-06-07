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
require 'include/PHP/class/class.equipe_ti.php';
$pagina = new Pagina();
$pagina->SettituloCabecalho("Cockpit Gerencial"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("TorreDeControleDesempenho.php", "tabact", "Desempenho"),
				   array("TorreDeControleChamados.php", "", "Chamados"),
//				   array("TorreDeControleRH.php", "", "RH"),
				   array("TorreDeControleGerencial.php", "", "Gerencial"),
				 );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
?>
<style>
	#combo_equipe {
		width: 200px;
		font-family: Verdana;
		font-size: 11px;
		color: #000000;
		border-color: #F0F0F0;
		border-style: double;
		border-width: 1px;
		background-color: #f6f5f5;
	}
</style>
<script language="javascript">
	/**
	 *
	 * @access public
	 * @return void
	 **/
	function AtualizaGraficoEquipePeriodo(vGrafico, v_DTH_INICIO, v_DTH_FIM, v_SEQ_EQUIPE_TI){
		if(v_DTH_INICIO == ""){
			alert("Preencha a data inicial");
			return;
		}
		if(v_DTH_FIM == ""){
			alert("Preencha a data final");
			return;
		}
		vTipoGrafico = document.getElementById("vTipoGrafico_"+vGrafico).value;
		vDestino = "kpiIframe.php?vTipoGrafico="+vTipoGrafico+"&vGrafico="+vGrafico+"&v_DTH_INICIO="+v_DTH_INICIO+"&v_DTH_FIM="+v_DTH_FIM+"&v_SEQ_EQUIPE_TI="+v_SEQ_EQUIPE_TI;
		document.getElementById(vGrafico).src = vDestino;
	}

	function ControlaAtualizaGrafico(vGrafico, vTipoGrafico){
		document.getElementById("vTipoGrafico_"+vGrafico).value = vTipoGrafico;

		if(vGrafico == "KpiPorcentagemChamadosEncerradosNoPrazoIncidente"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO.value, document.form.v_DTH_FIM.value, document.form.v_SEQ_EQUIPE_TI.value);
		}
		if(vGrafico == "KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO.value, document.form.v_DTH_FIM.value, document.form.v_SEQ_EQUIPE_TI.value);
		}
		if(vGrafico == "KpiChamadosPorAvaliacao"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO3.value, document.form.v_DTH_FIM3.value, document.form.v_SEQ_EQUIPE_TI3.value);
		}
		if(vGrafico == "KpiChamadosPorSLANivel1"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO5.value, document.form.v_DTH_FIM5.value, '');
		}
		if(vGrafico == "TempoMedioNivel1"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO6.value, document.form.v_DTH_FIM6.value, '');
		}
		if(vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Conhecimento"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO7.value, document.form.v_DTH_FIM7.value, document.form.v_SEQ_EQUIPE_TI7.value);
		}
		if(vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Postura"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO8.value, document.form.v_DTH_FIM8.value, document.form.v_SEQ_EQUIPE_TI8.value);
		}
		if(vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO10.value, document.form.v_DTH_FIM10.value, document.form.v_SEQ_EQUIPE_TI10.value);
		}
		if(vGrafico == "KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO9.value, document.form.v_DTH_FIM9.value, document.form.v_SEQ_EQUIPE_TI9.value);
		}
	}
</script>
<?
$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

print $pagina->CampoHidden("flag", "1");
$pagina->AbreTabelaPadrao("center", "100%");

print $pagina->CampoHidden2("vTipoGrafico_KpiPorcentagemChamadosEncerradosNoPrazoIncidente", "O");
print $pagina->CampoHidden2("vTipoGrafico_KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao", "O");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorSLA", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorAvaliacao", "P");

print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorAvaliacao_Satisfacao_Conhecimento", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorAvaliacao_Satisfacao_Postura", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao", "P");
print $pagina->CampoHidden2("vTipoGrafico_TempoMedioNivel1", "B");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorSLANivel1", "O");

/*
Indicadores de Primeiro N�vel
	Taxa de resolu��o imediata - Atendimento 1� n�vel no prazo
	Tempo m�dio de atendimento no 1� n�vel (minutos)

Indicadores de Segundo N�vel
	% de incidentes resolvidos no prazo
	% de solicita��es resolvidos no prazo
	Quantidade de chamados por Status do SLA (Em dia, Risco de atraso e Atrasado)

Indicadores de satisfa��o
	Quantidade de chamados por Avalia��o recebida, para cada pergunta feita
*/

$tabela = array();
$header = array();
// ====================================================================================================================================
// Primeira linha de gr�ficos - Indicadores de 1� n�vel
// ====================================================================================================================================
// Headers
$tabelaCampo = array();
$campo = array();
$campo[] = array("Taxa de resolu��o imediata - Atendimento 1� n�vel no prazo", "center", "100%", "header", "middle");
//$campo[] = array("&nbsp;", "center", "18%", "header");
$tabelaCampo[] = $campo;

$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Tempo m�dio de atendimento no 1� n�vel (minutos)", "center", "88%", "header", "middle");
$campo2[] = array($pagina->BotaoGraficoBarraPlus("TempoMedioNivel1").
				  $pagina->BotaoGraficoLinhaPlus("TempoMedioNivel1").
				  $pagina->BotaoGraficoPizzaPlus("TempoMedioNivel1"), "center", "12%", "header");
$tabelaCampo2[] = $campo2;

// Par�metros
$v_DTH_INICIO = $pagina->add_time2(-30,false,"d/m/Y");
$v_DTH_FIM = date("d/m/Y");
$equipe_ti = new equipe_ti();
$equipe_ti->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
$campo = array();
$campo[] = array("Per�odo: de ".$pagina->CampoData("v_DTH_INICIO5", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM5", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorSLANivel1', document.form.v_DTH_INICIO5.value, document.form.v_DTH_FIM5.value, '');", "Atualizar", "button", "Atualizar")
				 , "left", "100%", "escuro", "middle");
//$campo[] = array("&nbsp;", "center", "18%", "header");
$tabelaCampo[] = $campo;

$campo2 = array();
$campo2[] = array("Per�odo: de ".$pagina->CampoData("v_DTH_INICIO6", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM6", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('TempoMedioNivel1', document.form.v_DTH_INICIO6.value, document.form.v_DTH_FIM6.value, '');", "Atualizar", "button", "Atualizar")
				 , "left", "88%", "escuro", "middle");
$campo2[] = array("&nbsp;", "center", "12%", "escuro");
$tabelaCampo2[] = $campo2;

// Gr�ficos
$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiChamadosPorSLANivel1", "kpiIframe.php?vTipoGrafico=O&vGrafico=KpiChamadosPorSLANivel1&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=", "100%", "250", "no"), "center", "50%", "", "top");
$header[] = array($pagina->IFrame2("TempoMedioNivel1", "kpiIframe.php?vTipoGrafico=B&vGrafico=TempoMedioNivel1&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=", "97%", "250", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp;", "center", "50%", "", "top");
$header[] = array("&nbsp;", "center", "50%", "", "top");
$tabela[] = $header;

// ====================================================================================================================================
// Segunda linha de gr�ficos - Indicadores de 2� n�vel
// ====================================================================================================================================
// Headers
$tabelaCampo = array();
$campo = array();
$campo[] = array("Resolu��o do incidente no tempo estipulado (2� n�vel)", "center", "100%", "header", "middle");
//$campo[] = array("&nbsp;", "center", "18%", "header");
$tabelaCampo[] = $campo;
$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Resolu��o da requisi��o de servi�o no tempo estipulado (2� n�vel)", "center", "100%", "header", "middle");
//$campo2[] = array("&nbsp;", "center", "18%", "header");
$tabelaCampo2[] = $campo2;

// Par�metros
$equipe_ti = new equipe_ti();
$equipe_ti->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
$campo = array();
$campo[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Per�odo: de ".$pagina->CampoData("v_DTH_INICIO", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiPorcentagemChamadosEncerradosNoPrazoIncidente', document.form.v_DTH_INICIO.value, document.form.v_DTH_FIM.value, document.form.v_SEQ_EQUIPE_TI.value);", "Atualizar", "button", "Atualizar")
				 , "left", "100%", "escuro", "middle");
//$campo[] = array("&nbsp;", "center", "18%", "header");
$tabelaCampo[] = $campo;

$campo2 = array();
$campo2[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI11", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Per�odo: de ".$pagina->CampoData("v_DTH_INICIO11", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM11", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao', document.form.v_DTH_INICIO11.value, document.form.v_DTH_FIM11.value, document.form.v_SEQ_EQUIPE_TI11.value);", "Atualizar", "button", "Atualizar")
				 , "left", "100%", "escuro", "middle");
//$campo2[] = array("&nbsp;", "center", "18%", "header");
$tabelaCampo2[] = $campo2;

// Gr�ficos
$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiPorcentagemChamadosEncerradosNoPrazoIncidente", "kpiIframe.php?vTipoGrafico=O&vGrafico=KpiPorcentagemChamadosEncerradosNoPrazoIncidente&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "100%", "250", "no"), "center", "50%", "", "top");
$header[] = array($pagina->IFrame2("KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao", "kpiIframe.php?vTipoGrafico=O&vGrafico=KpiPorcentagemChamadosEncerradosNoPrazoSolicitacao&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "97%", "250", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp;", "center", "50%", "", "top");
$header[] = array("&nbsp;", "center", "50%", "", "top");
$tabela[] = $header;
// ====================================================================================================================================
// Terceira linha de gr�ficos - Indicadores de avalia��o do usu�rio
// ====================================================================================================================================
// Headers
$tabelaCampo = array();
$campo = array();
$campo[] = array("Satisfa��o com a solu��o apresentada", "center", "80%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoMultiLinhaPlus("KpiChamadosPorAvaliacao")."&nbsp;".
				 $pagina->BotaoGraficoBarraPlus("KpiChamadosPorAvaliacao")."&nbsp;".
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorAvaliacao")."&nbsp;".
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorAvaliacao"), "center", "18%", "header");
$tabelaCampo[] = $campo;

$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Satisfa��o com o conhecimento t�cnico do atendente", "center", "80%", "header", "middle");
$campo2[] = array($pagina->BotaoGraficoMultiLinhaPlus("KpiChamadosPorAvaliacao_Satisfacao_Conhecimento")."&nbsp;".
				  $pagina->BotaoGraficoBarraPlus("KpiChamadosPorAvaliacao_Satisfacao_Conhecimento")."&nbsp;".
				  $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorAvaliacao_Satisfacao_Conhecimento")."&nbsp;".
				  $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorAvaliacao_Satisfacao_Conhecimento"), "center", "18%", "header");
$tabelaCampo2[] = $campo2;

// Par�metros
$campo = array();
$campo[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI3", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Per�odo: de ".$pagina->CampoData("v_DTH_INICIO3", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM3", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorAvaliacao', document.form.v_DTH_INICIO3.value, document.form.v_DTH_FIM3.value, document.form.v_SEQ_EQUIPE_TI3.value);", "Atualizar", "button", "Atualizar")
				 , "left", "80%", "escuro", "middle");
$campo[] = array("&nbsp;", "center", "18%", "escuro");
$tabelaCampo[] = $campo;

$campo2 = array();
$campo2[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI7", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Per�odo: de ".$pagina->CampoData("v_DTH_INICIO7", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM7", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorAvaliacao_Satisfacao_Conhecimento', document.form.v_DTH_INICIO7.value, document.form.v_DTH_FIM7.value, document.form.v_SEQ_EQUIPE_TI7.value);", "Atualizar", "button", "Atualizar")
				 , "left", "80%", "escuro", "middle");
$campo2[] = array("&nbsp;", "center", "18%", "escuro");
$tabelaCampo2[] = $campo2;

// Gr�ficos
$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiChamadosPorAvaliacao", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorAvaliacao&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "100%", "305", "no"), "center", "50%", "", "top");
$header[] = array($pagina->IFrame2("KpiChamadosPorAvaliacao_Satisfacao_Conhecimento", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorAvaliacao_Satisfacao_Conhecimento&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "100%", "305", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp", "center", "", "10");
$header[] = array("&nbsp", "center", "", "10");
$tabela[] = $header;

// ====================================================================================================================================
// Quarta linha de gr�ficos - Indicadores de avalia��o do usu�rio
// ====================================================================================================================================
// Headers
$tabelaCampo = array();
$campo = array();
$campo[] = array("Satisfa��o com a postura e cordialidade do atendente", "center", "80%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoMultiLinhaPlus("KpiChamadosPorAvaliacao_Satisfacao_Postura")."&nbsp;".
				 $pagina->BotaoGraficoBarraPlus("KpiChamadosPorAvaliacao_Satisfacao_Postura")."&nbsp;".
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorAvaliacao_Satisfacao_Postura")."&nbsp;".
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorAvaliacao_Satisfacao_Postura"), "center", "18%", "header");
$tabelaCampo[] = $campo;

$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Satisfa��o com o tempo de espera", "center", "80%", "header", "middle");
$campo2[] = array($pagina->BotaoGraficoMultiLinhaPlus("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera")."&nbsp;".
				  $pagina->BotaoGraficoBarraPlus("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera")."&nbsp;".
				  $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera")."&nbsp;".
				  $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera"), "center", "18%", "header");
$tabelaCampo2[] = $campo2;

// Par�metros
$campo = array();
$campo[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI8", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Per�odo: de ".$pagina->CampoData("v_DTH_INICIO8", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM8", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorAvaliacao_Satisfacao_Postura', document.form.v_DTH_INICIO8.value, document.form.v_DTH_FIM8.value, document.form.v_SEQ_EQUIPE_TI8.value);", "Atualizar", "button", "Atualizar")
				 , "left", "80%", "escuro", "middle");
$campo[] = array("&nbsp;", "center", "18%", "escuro");
$tabelaCampo[] = $campo;

$campo2 = array();
$campo2[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI10", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Per�odo: de ".$pagina->CampoData("v_DTH_INICIO10", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM10", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera', document.form.v_DTH_INICIO10.value, document.form.v_DTH_FIM10.value, document.form.v_SEQ_EQUIPE_TI10.value);", "Atualizar", "button", "Atualizar")
				 , "left", "80%", "escuro", "middle");
$campo2[] = array("&nbsp;", "center", "18%", "escuro");
$tabelaCampo2[] = $campo2;

// Gr�ficos
$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiChamadosPorAvaliacao_Satisfacao_Postura", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorAvaliacao_Satisfacao_Postura&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "100%", "305", "no"), "center", "50%", "", "top");
$header[] = array($pagina->IFrame2("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorAvaliacao_Satisfacao_Tempo_Espera&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "100%", "305", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp", "center", "", "10");
$header[] = array("&nbsp", "center", "", "10");
$tabela[] = $header;

// ====================================================================================================================================
// Quinta linha de gr�ficos - Indicadores de avalia��o do usu�rio
// ====================================================================================================================================
// Headers
$tabelaCampo = array();
$campo = array();
$campo[] = array("Satisfa��o com o tempo de solu��o", "center", "80%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoMultiLinhaPlus("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao")."&nbsp;".
				 $pagina->BotaoGraficoBarraPlus("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao").
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao").
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao"), "center", "18%", "header");
$tabelaCampo[] = $campo;

$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("", "center", "95%", "header", "middle");
$campo2[] = array("", "center", "5%", "header");
$tabelaCampo2[] = $campo2;

// Par�metros
$campo = array();
$campo[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI9", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Per�odo: de ".$pagina->CampoData("v_DTH_INICIO9", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM9", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao', document.form.v_DTH_INICIO9.value, document.form.v_DTH_FIM9.value, document.form.v_SEQ_EQUIPE_TI9.value);", "Atualizar", "button", "Atualizar")
				 , "left", "80%", "escuro", "middle");
$campo[] = array("&nbsp;", "center", "18%", "escuro");
$tabelaCampo[] = $campo;

$campo2 = array();
$campo2[] = array("", "left", "100%", "escuro", "middle");
$campo2[] = array("&nbsp;", "center", "5%", "");
$tabelaCampo2[] = $campo2;

// Gr�ficos
$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array("", "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorAvaliacao_Satisfacao_Tempo_Solucao&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "100%", "305", "no"), "center", "50%", "", "top");
$header[] = array("", "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp", "center", "", "10");
$header[] = array("&nbsp", "center", "", "10");
$tabela[] = $header;

$pagina->LinhaCampoFormularioColspan("center",
		$pagina->Tabela($tabela, "100%","",false)
		, 2);

$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();
?>
