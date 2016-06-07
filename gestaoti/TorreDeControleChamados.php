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
require 'include/PHP/class/class.equipe_ti.php';
$pagina = new Pagina();
$pagina->SettituloCabecalho("Cockpit Gerencial"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("TorreDeControleDesempenho.php", "", "Desempenho"),
				   array("TorreDeControleChamados.php", "tabact", "Chamados"),
//				   array("TorreDeControleRH.php", "", "RH"),
				   array("TorreDeControleGerencial.php", "", "Gerencial"),
				 );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
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
		if(vGrafico != "KpiChamadosPorSubtipo"){
			vDestino = "kpiIframe.php?vTipoGrafico="+vTipoGrafico+"&vGrafico="+vGrafico+"&v_DTH_INICIO="+v_DTH_INICIO+"&v_DTH_FIM="+v_DTH_FIM+"&v_SEQ_EQUIPE_TI="+v_SEQ_EQUIPE_TI;
		}else{
			vDestino = "kpiIframe.php?vTipoGrafico="+vTipoGrafico+"&vGrafico="+vGrafico+"&v_DTH_INICIO="+v_DTH_INICIO+"&v_DTH_FIM="+v_DTH_FIM+"&v_SEQ_EQUIPE_TI="+v_SEQ_EQUIPE_TI+"&v_SEQ_TIPO_CHAMADO="+document.form.v_SEQ_TIPO_CHAMADO.value;
		}
		document.getElementById(vGrafico).src = vDestino;
	}

	function ControlaAtualizaGrafico(vGrafico, vTipoGrafico){
		document.getElementById("vTipoGrafico_"+vGrafico).value = vTipoGrafico;
		if(vGrafico == "KpiChamadosPorSituacao"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO.value, document.form.v_DTH_FIM.value, document.form.v_SEQ_EQUIPE_TI.value);
		}
		if(vGrafico == "KpiChamadosPorPrioridade"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO1.value, document.form.v_DTH_FIM1.value, document.form.v_SEQ_EQUIPE_TI1.value);
		}
		if(vGrafico == "KpiChamadosPorLotacao"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO2.value, document.form.v_DTH_FIM2.value, document.form.v_SEQ_EQUIPE_TI2.value);
		}
		if(vGrafico == "KpiChamadosPorSistemaInformacao"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO3.value, document.form.v_DTH_FIM3.value, document.form.v_SEQ_EQUIPE_TI3.value);
		}
		if(vGrafico == "KpiChamadosPorTipo"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO4.value, document.form.v_DTH_FIM4.value, document.form.v_SEQ_EQUIPE_TI4.value);
		}
		if(vGrafico == "KpiChamadosPorTipoOcorrencia"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO5.value, document.form.v_DTH_FIM5.value, document.form.v_SEQ_EQUIPE_TI5.value);
		}
		if(vGrafico == "KpiChamadosPorSubtipo"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO6.value, document.form.v_DTH_FIM6.value, document.form.v_SEQ_EQUIPE_TI6.value);
		}
		if(vGrafico == "KpiChamadosPorEquipe"){
			AtualizaGraficoEquipePeriodo(vGrafico, document.form.v_DTH_INICIO7.value, document.form.v_DTH_FIM7.value, "");
		}
	}
</script>
<?

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

print $pagina->CampoHidden("flag", "1");

print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorSituacao", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorPrioridade", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorLotacao", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorSistemaInformacao", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorTipo", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorTipoOcorrencia", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorSubtipo", "P");
print $pagina->CampoHidden2("vTipoGrafico_KpiChamadosPorEquipe", "P");


$pagina->AbreTabelaPadrao("center", "100%","border=\"1\"");

/*
-- Quantidade de chamados por Situação
-- Quantidade de chamado por Prioridade
-- Quantidade de chamados por lotação
-- Quantidade de chamados por sistema de informação
-- Quantidade de chamados por tipo de chamado
-- Quantidade de chamados por Atividade de chamado
-- Quantidade de chamados por Subtipo de chamado
*/

$tabela = array();
$header = array();
// ====================================================================================================================================
// Primeira linha de gráficos
// ====================================================================================================================================
// Headers
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de chamados por Situação", "center", "88%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoBarraPlus("KpiChamadosPorSituacao").
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorSituacao").
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorSituacao"), "center", "12%", "header");
$tabelaCampo[] = $campo;
$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Quantidade de chamado por Prioridade", "center", "88%", "header", "middle");
$campo2[] = array($pagina->BotaoGraficoBarraPlus("KpiChamadosPorPrioridade").
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorPrioridade").
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorPrioridade"), "center", "12%", "header");
$tabelaCampo2[] = $campo2;

// Parâmetros
$v_DTH_INICIO = $pagina->add_time2(-30,false,"d/m/Y");
$v_DTH_FIM = date("d/m/Y");
$equipe_ti = new equipe_ti();
$equipe_ti->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;

$campo = array();
$campo[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Período: de ".$pagina->CampoData("v_DTH_INICIO", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorSituacao', document.form.v_DTH_INICIO.value, document.form.v_DTH_FIM.value, document.form.v_SEQ_EQUIPE_TI.value);", "Atualizar", "button", "Atualizar")
				 , "left", "88%", "escuro", "middle");
$campo[] = array("&nbsp;", "center", "12%", "escuro");
$tabelaCampo[] = $campo;

$campo2 = array();
$campo2[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI1", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Período: de ".$pagina->CampoData("v_DTH_INICIO1", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM1", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorPrioridade', document.form.v_DTH_INICIO1.value, document.form.v_DTH_FIM1.value, document.form.v_SEQ_EQUIPE_TI1.value);", "Atualizar", "button", "Atualizar")
				 , "left", "88%", "escuro", "middle");
$campo2[] = array("&nbsp;", "center", "12%", "escuro");
$tabelaCampo2[] = $campo2;

// Gráficos
$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiChamadosPorSituacao", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorSituacao&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "100%", "250", "no"), "center", "50%", "", "top");
$header[] = array($pagina->IFrame2("KpiChamadosPorPrioridade", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorPrioridade&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "97%", "250", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp;", "center", "50%", "", "top");
$header[] = array("&nbsp;", "center", "50%", "", "top");
$tabela[] = $header;



// ====================================================================================================================================
// Terceira linha de gráficos
// ====================================================================================================================================
// Headers
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de chamados por classe de chamado", "center", "88%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoBarraPlus("KpiChamadosPorTipo").
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorTipo").
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorTipo"), "center", "12%", "header");
$tabelaCampo[] = $campo;
$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Quantidade de chamados por tipo de chamado", "center", "88%", "header", "middle");
$campo2[] = array($pagina->BotaoGraficoBarraPlus("KpiChamadosPorTipoOcorrencia").
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorTipoOcorrencia").
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorTipoOcorrencia"), "center", "12%", "header");
$tabelaCampo2[] = $campo2;

// Parâmetros
$campo = array();
$campo[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI4", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Período: de ".$pagina->CampoData("v_DTH_INICIO4", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM4", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorTipo', document.form.v_DTH_INICIO4.value, document.form.v_DTH_FIM4.value, document.form.v_SEQ_EQUIPE_TI4.value);", "Atualizar", "button", "Atualizar")
				 , "left", "88%", "escuro", "middle");
$campo[] = array("&nbsp;", "center", "12%", "escuro");
$tabelaCampo[] = $campo;

$campo2 = array();
$campo2[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI5", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Período: de ".$pagina->CampoData("v_DTH_INICIO5", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM5", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorTipoOcorrencia', document.form.v_DTH_INICIO5.value, document.form.v_DTH_FIM5.value, document.form.v_SEQ_EQUIPE_TI5.value);", "Atualizar", "button", "Atualizar")
				 , "left", "88%", "escuro", "middle");
$campo2[] = array("&nbsp;", "center", "12%", "escuro");
$tabelaCampo2[] = $campo2;

// Gráficos
$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
$tabela[] = $header;

$header = array();
$header[] = array($pagina->IFrame2("KpiChamadosPorTipo", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorTipo&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "100%", "250", "no"), "center", "50%", "", "top");
$header[] = array($pagina->IFrame2("KpiChamadosPorTipoOcorrencia", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorTipoOcorrencia&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "97%", "250", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp;", "center", "50%", "", "top");
$header[] = array("&nbsp;", "center", "50%", "", "top");
$tabela[] = $header;


// ====================================================================================================================================
// Quarta linha de gráficos
// ====================================================================================================================================
// Headers
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de chamados por subclasse", "center", "88%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoBarraPlus("KpiChamadosPorSubtipo").
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorSubtipo").
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorSubtipo"), "center", "12%", "header");
$tabelaCampo[] = $campo;
$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Quantidade de chamados atribuídos para cada Equipe", "center", "88%", "header", "middle");
$campo2[] = array($pagina->BotaoGraficoBarraPlus("KpiChamadosPorEquipe").
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorEquipe").
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorEquipe"), "center", "12%", "header");
$tabelaCampo2[] = $campo2;

// Montar a combo da tabela tipo_chamado
require_once 'include/PHP/class/class.tipo_chamado.php';
$tipo_chamado = new tipo_chamado(); 
$tipo_chamado->SEQ_CENTRAL_ATENDIMENTO = $_SEQ_CENTRAL_ATENDIMENTO;
$v_SEQ_TIPO_CHAMADO = 1;

// Parâmetros
$campo = array();
$campo[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI6", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Período: de ".$pagina->CampoData("v_DTH_INICIO6", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM6", "N", " de Inicio", $v_DTH_FIM).
				 "<br>".$pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":".$pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "N", "", "N", $tipo_chamado->combo2(2, $v_SEQ_TIPO_CHAMADO)).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorSubtipo', document.form.v_DTH_INICIO6.value, document.form.v_DTH_FIM6.value, document.form.v_SEQ_EQUIPE_TI6.value);", "Atualizar", "button", "Atualizar")
				 , "left", "88%", "escuro", "middle");
$campo[] = array("&nbsp;", "center", "12%", "escuro");
$tabelaCampo[] = $campo;

$campo2 = array();
$campo2[] = array(
				 //"Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI7", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Período: de ".$pagina->CampoData("v_DTH_INICIO7", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM7", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorEquipe', document.form.v_DTH_INICIO7.value, document.form.v_DTH_FIM7.value, '');", "Atualizar", "button", "Atualizar")
				 , "left", "88%", "escuro", "middle");
$campo2[] = array("&nbsp;", "center", "12%", "escuro");
$tabelaCampo2[] = $campo2;

// Gráficos
$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiChamadosPorSubtipo", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorSubtipo&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO", "100%", "250", "no"), "center", "50%", "", "top");
$header[] = array($pagina->IFrame2("KpiChamadosPorEquipe", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorEquipe&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "97%", "250", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp;", "center", "50%", "", "top");
$header[] = array("&nbsp;", "center", "50%", "", "top");
$tabela[] = $header;



// ====================================================================================================================================
// Segunda linha de gráficos
// ====================================================================================================================================
// Headers
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de chamados por diretoria", "center", "88%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoBarraPlus("KpiChamadosPorLotacao").
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorLotacao").
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorLotacao"), "center", "12%", "header");
$tabelaCampo[] = $campo;

if ($_SEQ_CENTRAL_ATENDIMENTO == $pagina->SEQ_CENTRAL_TI){
$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Quantidade de chamados por sistema de informação", "center", "88%", "header", "middle");
$campo2[] = array($pagina->BotaoGraficoBarraPlus("KpiChamadosPorSistemaInformacao").
				 $pagina->BotaoGraficoLinhaPlus("KpiChamadosPorSistemaInformacao").
				 $pagina->BotaoGraficoPizzaPlus("KpiChamadosPorSistemaInformacao"), "center", "12%", "header");
$tabelaCampo2[] = $campo2;
}
// Parâmetros
$campo = array();
$campo[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI2", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Período: de ".$pagina->CampoData("v_DTH_INICIO2", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM2", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorLotacao', document.form.v_DTH_INICIO2.value, document.form.v_DTH_FIM2.value, document.form.v_SEQ_EQUIPE_TI2.value);", "Atualizar", "button", "Atualizar")
				 , "left", "88%", "escuro", "middle");
$campo[] = array("&nbsp;", "center", "12%", "escuro");
$tabelaCampo[] = $campo;

if ($_SEQ_CENTRAL_ATENDIMENTO == $pagina->SEQ_CENTRAL_TI){
$campo2 = array();
$campo2[] = array("Equipe: ".$pagina->CampoSelect("v_SEQ_EQUIPE_TI3", "N", "Equipe", "S", $equipe_ti->combo("NOM_EQUIPE_TI"), "Todos", "", "combo_equipe").
				 "<br>Período: de ".$pagina->CampoData("v_DTH_INICIO3", "N", " de Inicio", $v_DTH_INICIO).
				 " a ".$pagina->CampoData("v_DTH_FIM3", "N", " de Inicio", $v_DTH_FIM).
				 "&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$pagina->CampoButton("AtualizaGraficoEquipePeriodo('KpiChamadosPorSistemaInformacao', document.form.v_DTH_INICIO3.value, document.form.v_DTH_FIM3.value, document.form.v_SEQ_EQUIPE_TI3.value);", "Atualizar", "button", "Atualizar")
				 , "left", "88%", "escuro", "middle");
$campo2[] = array("&nbsp;", "center", "12%", "escuro");
$tabelaCampo2[] = $campo2;
}

// Gráficos
$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
if ($_SEQ_CENTRAL_ATENDIMENTO == $pagina->SEQ_CENTRAL_TI){
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
}
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiChamadosPorLotacao", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorLotacao&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "100%", "250", "no"), "center", "50%", "", "top");
if ($_SEQ_CENTRAL_ATENDIMENTO == $pagina->SEQ_CENTRAL_TI){
$header[] = array($pagina->IFrame2("KpiChamadosPorSistemaInformacao", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorSistemaInformacao&v_DTH_INICIO=$v_DTH_INICIO&v_DTH_FIM=$v_DTH_FIM&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI", "97%", "250", "no"), "center", "50%", "", "top");
}
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp;", "center", "50%", "", "top");
if ($_SEQ_CENTRAL_ATENDIMENTO == $pagina->SEQ_CENTRAL_TI){
$header[] = array("&nbsp;", "center", "50%", "", "top");
}
$tabela[] = $header;

$pagina->LinhaCampoFormularioColspan("center",
		$pagina->Tabela($tabela, "100%","",false)
		, 2);

$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();
?>
