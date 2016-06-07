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

require 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
// Configura��o da p�g�na
$pagina->SettipoPagina("O"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Meus Chamados"); // Indica o t�tulo do cabe�alho da p�gina
$pagina->setToolBarMyView(1);
$pagina->MontaCabecalho();
$pagina->LinhaVazia(3);
$pagina->AbreTabelaPadrao("left", "100%");

/*
$pagina->LinhaColspan("center", 
						"<iframe id=\"principal\" name=\"principal\" src =\"meus_chamados_painel.php\" onload=\"iframeAutoHeight(this)\" width=\"100%\" height=\"300\" scrolling=\"no\" />" , 
						"2", "tabelaConteudoHeader");
*/
$pagina->LinhaColspan("center", '
<div align="center">

<table class="hide" border="0" cellspacing="3" cellpadding="0">
<tr>
<td valign="top" width="50%">
	<iframe id="x" name="x" src ="meus_chamados_aguardando_primeiro_nivel.php" width="50%" scrolling="no" />
</td>

<td valign="top" width="50%">

 <iframe id="x" name="x" src ="meus_chamados_aguardando_primeiro_nivel.php" width="50%" scrolling="no" />

</td>
</tr>

<tr>
<td valign="top" width="50%">

 <iframe id="x" name="x" src ="meus_chamados_aguardando_primeiro_nivel.php" width="50%" scrolling="no" />

</td>

<tr>

<td colspan="2">

	<br />
	<table class="width100" cellspacing="1">
	<tr>
		<td class="small-caption" width="14%" bgcolor="#ffa0a0">Aguardando Atendimento de 1� N�vel</td>
		<td class="small-caption" width="14%" bgcolor="#ff50a8">feedback</td>
		<td class="small-caption" width="14%" bgcolor="#ffd850">acknowledged</td>
		<td class="small-caption" width="14%" bgcolor="#ffffb0">confirmed</td>
		<td class="small-caption" width="14%" bgcolor="#c8c8ff">assigned</td>
		<td class="small-caption" width="14%" bgcolor="#cceedd">resolved</td>
		<td class="small-caption" width="14%" bgcolor="#e8e8e8">closed</td>
	</tr>
	</table>

</td>
</tr>
</table>

</div>

',"2", "tabelaConteudoHeader");
 
$pagina->FechaTabelaPadrao();
 
$pagina->MontaRodape();

?>
 
