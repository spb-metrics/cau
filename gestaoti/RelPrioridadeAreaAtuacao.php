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
require 'include/PHP/class/class.item_configuracao.php';
require 'include/PHP/class/class.PRIORIDADE.php';
require 'include/PHP/class/jpgraph.php';
require 'include/PHP/class/jpgraph_bar.php';
$pagina = new Pagina();
$banco = new item_configuracao();
$item_configuracao = new item_configuracao();
$PRIORIDADE = new PRIORIDADE();

$colors=array('red','blue','green', '#CCCCCC', '#DEDEDE', '#DDDDDD', '#FAFAFA');
$groupBarPlot = array();
$contColumns = 0;

$PRIORIDADE->selectParam("2");
while ($row = oci_fetch_array($PRIORIDADE->database->result, OCI_BOTH)){
	$item_configuracao->selectAreasAtuacao($v_UOR_SIGLA);
	$vColunas = array($item_configuracao->database->rows);
	$vArrayDaVez = array($PRIORIDADE->database->rows);
	$cont = 0;
	while ($rowAreaAtuacao = oci_fetch_array($item_configuracao->database->result, OCI_BOTH)){
		$valor = $banco->selectQuantidadeItensPorAreaAtuacao($rowAreaAtuacao[0], $row[0],$v_UOR_SIGLA);
		//print "<br>area '$rowAreaAtuacao[0]' - Criticidade '$row[0]' -  valor '$valor' ";
		$vArrayDaVez[$cont] = $valor;
		$vArrayNomes[$cont] = $rowAreaAtuacao[0];
		$cont++;
	}
	$b1plot = new BarPlot($vArrayDaVez);
	$b1plot->SetShadow();
	$b1plot->value->Show();
	$b1plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
	$b1plot->value->SetAngle(45);
//	$b1plot->value->SetFormat('%.1f');
	$b1plot->SetFillColor($colors[$contColumns]);
	$groupBarPlot[$contColumns] = $b1plot;
	$contColumns++;	
}

// Create the graph. These two calls are always required
$graph = new Graph(610,300,"auto");    
$graph->SetScale("textlin");

$graph->SetShadow();
$graph->img->SetMargin(40,60,20,40);

// Create the grouped bar plot
$gbplot = new GroupBarPlot($groupBarPlot);

// ...and add it to the graPH
$graph->Add($gbplot);

$graph->title->Set("Prioridades por �rea");
$graph->xaxis->title->Set("�reas de atua��o");
$graph->yaxis->title->Set("Quantidade");
$graph->xaxis->SetTickLabels($vArrayNomes);

$graph->legend->Pos(0.02,0.5,"right","center");
$graph->legend->SetFont(FF_VERDANA, FS_NORMAL, 8);
$graph->legend->SetFillColor("#eeeeee");
$graph->legend->SetColor("black", "#336497");
$graph->legend->SetShadow("white", 0);
$graph->legend->SetLineSpacing(2);


$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

// Display the graph
$graph->Stroke();

?>
