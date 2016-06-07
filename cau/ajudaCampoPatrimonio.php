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
$pagina->SettituloCabecalho("Campo Nº de patrimônio");
$pagina->MontaCabecalho();
?>
<div align="justify">
	<br>
	<blockquote>
	&nbsp; - Preencha este campo com o número existente na plaqueta de patrimônio, caso o patrim&ocirc;nio seja da EMBRATUR.<br>
	<br>
	&nbsp; - Para os patrim&ocirc;nios do Minist&eacute;rio do Turismo, identificados na plaqueta com o texto "MTur", deve-se preencher neste campo o n&uacute;mero existente na etiqueta de papel colada pr&oacute;xima &agrave; plaqueta, identificada com o t&iacute;tuli "EMBRATUR/MTur".
	<br>
	<br>
	&nbsp;Em caso de problemas com o preenchimento deste campo, informe o n&uacute;mero do patrim&ocirc;nio no corpo do chamado.
	</blockquote>

</div>

<?
$pagina->MontaRodape();
?>