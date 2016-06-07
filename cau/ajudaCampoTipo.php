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
require '../gestaoti/include/PHP/class/class.pagina.php';
$pagina = new Pagina();
//$pagina->SettituloCabecalho("Reportar erro"); // Indica o título do cabeçalho da página
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
$pagina->flagScriptCalendario = 0;
$pagina->flagMenuLateral = 0;
$pagina->lightbox = 0;
$pagina->cea = 1;
$pagina->SettituloCabecalho("Campo Tipo de Chamado");
$pagina->MontaCabecalho();
?>
<div align="left">
	<br>
	Op&ccedil;&otilde;es dispon&iacute;veis:
	<blockquote>
	- <b>Incidente</b>: Selecione esta op&ccedil;&atilde;o se voc&ecirc; estiver enfrentando qualquer dificuldade na realiza&ccedil;&atilde;o de seus trabalhos em fun&ccedil;&atilde;o de um problema tecnol&oacute;gico, seja em sistemas, seja em qualquer hadware.<br>
	<br>
	- <b>Solicita&ccedil;&atilde;o</b>: Selecione esta op&ccedil;&atilde;o caso necessite de uma a&ccedil;&atilde;o da CST para solu&ccedil;&atilde;o de uma necessidade pontual ligada a um recurso tecnol&oacute;gico disponibilizado pela Institui&ccedil;&atilde;o para apoiar as suas atividades.<br>
	<br>
	- <b>D&uacute;vida</b>: Selecione esta op&ccedil;&atilde;o para a solu&ccedil;&atilde;o de d&uacute;vidas pontuais sobre a utiliza&ccedil;&atilde;o de recursos tecnol&oacute;gicos disponibilizados pela Institui&ccedil;&atilde;o para apoiar as suas atividades.
	</blockquote>
</div>

<?
$pagina->MontaRodape();
?>