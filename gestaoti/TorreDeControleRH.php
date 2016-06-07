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
$pagina = new Pagina();
$pagina->SettituloCabecalho("Cockpit Gerencial"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("TorreDeControleDesempenho.php", "", "Desempenho"),
				   array("TorreDeControleChamados.php", "", "Chamados"),
				   array("TorreDeControleRH.php", "tabact", "RH"),
				   array("TorreDeControleGerencial.php", "", "Gerencial"),
				 );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
$pagina->AbreTabelaPadrao("center", "100%");

/*
-- Quantidade de chamados atribuídos para cada Dependência
-- Quantidade de chamados atribuídos para cada Equipe
-- Quantidade de horas de trabalho registradas por profissional
-- Quantidade de horas de trabalho registradas por equipe de ti
*/

$tabela = array();
$header = array();
// Primeira linha de gráficos
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de chamados atribuídos para cada Equipe", "center", "82%", "", "middle");
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

// Segunda linha de gráficos
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
