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
$pagina = new Pagina();
$pagina->SettituloCabecalho("Cockpit Gerencial"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("TorreDeControleDesempenho.php", "", "Desempenho"),
				   array("TorreDeControleChamados.php", "", "Chamados"),
				   array("TorreDeControleRH.php", "tabact", "RH"),
				   array("TorreDeControleGerencial.php", "", "Gerencial"),
				 );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
$pagina->AbreTabelaPadrao("center", "100%");

/*
-- Quantidade de chamados atribu�dos para cada Depend�ncia
-- Quantidade de chamados atribu�dos para cada Equipe
-- Quantidade de horas de trabalho registradas por profissional
-- Quantidade de horas de trabalho registradas por equipe de ti
*/

$tabela = array();
$header = array();
// Primeira linha de gr�ficos
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de chamados atribu�dos para cada Equipe", "center", "82%", "", "middle");
$campo[] = array($pagina->BotaoGraficoBarra("kpiIframe.php?vTipoGrafico=B&vGrafico=KpiChamadosPorEquipe", "KpiChamadosPorEquipe").
				 $pagina->BotaoGraficoLinha("kpiIframe.php?vTipoGrafico=L&vGrafico=KpiChamadosPorEquipe", "KpiChamadosPorEquipe").
				 $pagina->BotaoGraficoPizza("kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorEquipe", "KpiChamadosPorEquipe"), "center", "18%", "");
$tabelaCampo[] = $campo;

$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Quantidade de horas de trabalho registradas por equipe de ti", "center", "82%", "", "middle");
$campo2[] = array($pagina->BotaoGraficoBarra("kpiIframe.php?vTipoGrafico=B&vGrafico=KpiHorasPorEquipe", "KpiHorasPorEquipe").
				  $pagina->BotaoGraficoLinha("kpiIframe.php?vTipoGrafico=L&vGrafico=KpiHorasPorEquipe", "KpiHorasPorEquipe").
				  $pagina->BotaoGraficoPizza("kpiIframe.php?vTipoGrafico=P&vGrafico=KpiHorasPorEquipe", "KpiHorasPorEquipe"), "center", "18%", "");
$tabelaCampo2[] = $campo2;

$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "92%","",false), "center", "50%", "");
$header[] = array($pagina->Tabela($tabelaCampo2, "92%","",false), "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->Iframe("KpiChamadosPorEquipe", "kpiIframe.php?vTipoGrafico=P&vGrafico=KpiChamadosPorEquipe", "100%", "250", "no"), "center", "50%", "", "top");
$header[] = array($pagina->Iframe("KpiHorasPorEquipe", "kpiIframe.php?vTipoGrafico=B&vGrafico=KpiHorasPorEquipe", "100%", "250", "no"), "center", "50%", "", "top");
$tabela[] = $header;

$header = array();
$header[] = array("&nbsp", "center", "", "10");
$header[] = array("&nbsp", "center", "", "10");
$tabela[] = $header;

// Segunda linha de gr�ficos
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de horas de trabalho registradas por profissional", "center", "82%", "", "middle");
$campo[] = array($pagina->BotaoGraficoBarra("kpiIframe.php?vTipoGrafico=B&vGrafico=KpiHorasPorProfissional", "KpiHorasPorProfissional").
				 $pagina->BotaoGraficoLinha("kpiIframe.php?vTipoGrafico=L&vGrafico=KpiHorasPorProfissional", "KpiHorasPorProfissional").
				 $pagina->BotaoGraficoPizza("kpiIframe.php?vTipoGrafico=P&vGrafico=KpiHorasPorProfissional", "KpiHorasPorProfissional"), "center", "18%", "");
$tabelaCampo[] = $campo;

$header = array();
$header[] = array($pagina->Tabela($tabelaCampo,  "92%","",false), "center", "50%", "");
$header[] = array("", "center", "50%", "");
$tabela[] = $header;
$header = array();
$header[] = array($pagina->Iframe("KpiHorasPorProfissional", "kpiIframe.php?vTipoGrafico=B&vGrafico=KpiHorasPorProfissional", "100%", "255", "no"), "center", "50%", "", "top");
$header[] = array("", "center", "50%", "", "top");
$tabela[] = $header;

$pagina->LinhaCampoFormularioColspan("center",
		$pagina->Tabela($tabela, "100%","",false)
		, 2);

$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();
?>
