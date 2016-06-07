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
$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

require 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->SettituloCabecalho("Cockpit Gerencial"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("TorreDeControleDesempenho.php", "", "Desempenho"),
				   array("TorreDeControleChamados.php", "", "Chamados"),
//				   array("TorreDeControleRH.php", "", "RH"),
				   array("TorreDeControleGerencial.php", "tabact", "Gerencial"),
				 );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
$pagina->AbreTabelaPadrao("center", "100%");

/*
-- Quantidade de profissionais alocados por equipe
-- Valor das horas dos profissionais alocados por equipe
-- Quantidade de Sistemas de Informa��o por Linguagem de Programa��o
-- Quantidade de Sistemas de Informa��o por Banco de Dados
-- Profissionais por �rea de atua��o
*/

$tabela = array();
$header = array();
// Primeira linha de gr�ficos
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de profissionais alocados por equipe", "center", "82%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoBarra("kpiIframe.php?vTipoGrafico=B&vGrafico=KpiQtdProfissionaisPorEquipe", "KpiQtdProfissionaisPorEquipe").
				 $pagina->BotaoGraficoLinha("kpiIframe.php?vTipoGrafico=L&vGrafico=KpiQtdProfissionaisPorEquipe", "KpiQtdProfissionaisPorEquipe").
				 $pagina->BotaoGraficoPizza("kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdProfissionaisPorEquipe", "KpiQtdProfissionaisPorEquipe"), "center", "18%", "header");
$tabelaCampo[] = $campo;

$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Profissionais por �rea de atua��o", "center", "82%", "header", "middle");
$campo2[] = array($pagina->BotaoGraficoBarra("kpiIframe.php?vTipoGrafico=B&vGrafico=KpiQtdProfissionaisPorAreaAtuacao", "KpiQtdProfissionaisPorAreaAtuacao").
				 $pagina->BotaoGraficoLinha("kpiIframe.php?vTipoGrafico=L&vGrafico=KpiQtdProfissionaisPorAreaAtuacao", "KpiQtdProfissionaisPorAreaAtuacao").
				 $pagina->BotaoGraficoPizza("kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdProfissionaisPorAreaAtuacao", "KpiQtdProfissionaisPorAreaAtuacao"), "center", "18%", "header");
$tabelaCampo2[] = $campo2;

$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiQtdProfissionaisPorEquipe", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdProfissionaisPorEquipe", "100%", "250", "no"), "center", "50%", "", "top");
$header[] = array($pagina->IFrame2("KpiQtdProfissionaisPorAreaAtuacao", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdProfissionaisPorAreaAtuacao", "100%", "255", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp", "center", "", "10");
$header[] = array("&nbsp", "center", "", "10");
$tabela[] = $header;

if ($_SEQ_CENTRAL_ATENDIMENTO == $pagina->SEQ_CENTRAL_TI){
// Segunda linha de gr�ficos
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de Sistemas de Informa��o por Linguagem de Programa��o", "center", "82%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoBarra("kpiIframe.php?vTipoGrafico=B&vGrafico=KpiQtdSistemasPorLinguagem", "KpiQtdSistemasPorLinguagem").
				 $pagina->BotaoGraficoLinha("kpiIframe.php?vTipoGrafico=L&vGrafico=KpiQtdSistemasPorLinguagem", "KpiQtdSistemasPorLinguagem").
				 $pagina->BotaoGraficoPizza("kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdSistemasPorLinguagem", "KpiQtdSistemasPorLinguagem"), "center", "18%", "header");
$tabelaCampo[] = $campo;

$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Quantidade de Sistemas de Informa��o por Banco de Dados", "center", "82%", "header", "middle");
$campo2[] = array($pagina->BotaoGraficoBarra("kpiIframe.php?vTipoGrafico=B&vGrafico=KpiQtdSistemasPorBancoDeDados", "KpiQtdSistemasPorBancoDeDados").
				  $pagina->BotaoGraficoLinha("kpiIframe.php?vTipoGrafico=L&vGrafico=KpiQtdSistemasPorBancoDeDados", "KpiQtdSistemasPorBancoDeDados").
				  $pagina->BotaoGraficoPizza("kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdSistemasPorBancoDeDados", "KpiQtdSistemasPorBancoDeDados"), "center", "18%", "header");
$tabelaCampo2[] = $campo2;

$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "97%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "97%","",false), "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->IFrame2("KpiQtdSistemasPorLinguagem", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdSistemasPorLinguagem", "100%", "255", "no"), "center", "50%", "", "top");
$header[] = array($pagina->IFrame2("KpiQtdSistemasPorBancoDeDados", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdSistemasPorBancoDeDados", "100%", "255", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp", "center", "", "10");
$header[] = array("&nbsp", "center", "", "10");
$tabela[] = $header;
}
$pagina->LinhaCampoFormularioColspan("center",
		$pagina->Tabela($tabela, "100%","",false)
		, 2);

$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();
?>
