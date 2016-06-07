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
// $Id: canvasex01.php,v 1.3 2002/10/23 08:17:23 aditus Exp $
include_once("include/PHP/class/jpgraph.php");
include_once("include/PHP/class/jpgraph_canvas.php");

// Setup a basic canvas we can work
$g = new CanvasGraph(400,300,'auto');
$g->SetMargin(5,11,6,11);
$g->SetShadow();
$g->SetMarginColor("teal");

// We need to stroke the plotarea and margin before we add the
// text since we otherwise would overwrite the text.
$g->InitFrame();

// Draw a text box in the middle
$txt="This\nis\na TEXT!!!";
$t = new Text($txt,200,10);
$t->SetFont(FF_ARIAL,FS_BOLD,40);

// How should the text box interpret the coordinates?
$t->Align('center','top');

// How should the paragraph be aligned?
$t->ParagraphAlign('center');

// Add a box around the text, white fill, black border and gray shadow
$t->SetBox("white","black","gray");

// Stroke the text
$t->Stroke($g->img);

// Stroke the graph
$g->Stroke();

?>

