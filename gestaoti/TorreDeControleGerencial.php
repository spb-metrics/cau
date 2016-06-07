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
$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

require 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->SettituloCabecalho("Cockpit Gerencial"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("TorreDeControleDesempenho.php", "", "Desempenho"),
				   array("TorreDeControleChamados.php", "", "Chamados"),
//				   array("TorreDeControleRH.php", "", "RH"),
				   array("TorreDeControleGerencial.php", "tabact", "Gerencial"),
				 );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");
$pagina->AbreTabelaPadrao("center", "100%");

/*
-- Quantidade de profissionais alocados por equipe
-- Valor das horas dos profissionais alocados por equipe
-- Quantidade de Sistemas de Informação por Linguagem de Programação
-- Quantidade de Sistemas de Informação por Banco de Dados
-- Profissionais por área de atuação
*/

$tabela = array();
$header = array();
// Primeira linha de gráficos
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de profissionais alocados por equipe", "center", "82%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoBarra("kpiIframe.php?vTipoGrafico=B&vGrafico=KpiQtdProfissionaisPorEquipe", "KpiQtdProfissionaisPorEquipe").
				 $pagina->BotaoGraficoLinha("kpiIframe.php?vTipoGrafico=L&vGrafico=KpiQtdProfissionaisPorEquipe", "KpiQtdProfissionaisPorEquipe").
				 $pagina->BotaoGraficoPizza("kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdProfissionaisPorEquipe", "KpiQtdProfissionaisPorEquipe"), "center", "18%", "header");
$tabelaCampo[] = $campo;

$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Profissionais por área de atuação", "center", "82%", "header", "middle");
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
// Segunda linha de gráficos
$tabelaCampo = array();
$campo = array();
$campo[] = array("Quantidade de Sistemas de Informação por Linguagem de Programação", "center", "82%", "header", "middle");
$campo[] = array($pagina->BotaoGraficoBarra("kpiIframe.php?vTipoGrafico=B&vGrafico=KpiQtdSistemasPorLinguagem", "KpiQtdSistemasPorLinguagem").
				 $pagina->BotaoGraficoLinha("kpiIframe.php?vTipoGrafico=L&vGrafico=KpiQtdSistemasPorLinguagem", "KpiQtdSistemasPorLinguagem").
				 $pagina->BotaoGraficoPizza("kpiIframe.php?vTipoGrafico=P&vGrafico=KpiQtdSistemasPorLinguagem", "KpiQtdSistemasPorLinguagem"), "center", "18%", "header");
$tabelaCampo[] = $campo;

$tabelaCampo2 = array();
$campo2 = array();
$campo2[] = array("Quantidade de Sistemas de Informação por Banco de Dados", "center", "82%", "header", "middle");
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
