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

require 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
// Configuração da págína
$pagina->SettipoPagina("O"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Meus Chamados"); // Indica o título do cabeçalho da página
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
		<td class="small-caption" width="14%" bgcolor="#ffa0a0">Aguardando Atendimento de 1º Nível</td>
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
 
