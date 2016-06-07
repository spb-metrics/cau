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

