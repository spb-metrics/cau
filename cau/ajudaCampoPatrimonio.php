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
require '../gestaoti/include/PHP/class/class.pagina.php';
$pagina = new Pagina();
//$pagina->SettituloCabecalho("Reportar erro"); // Indica o t�tulo do cabe�alho da p�gina
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
$pagina->flagScriptCalendario = 0;
$pagina->flagMenuLateral = 0;
$pagina->lightbox = 0;
$pagina->cea = 1;
$pagina->SettituloCabecalho("Campo N� de patrim�nio");
$pagina->MontaCabecalho();
?>
<div align="justify">
	<br>
	<blockquote>
	&nbsp; - Preencha este campo com o n�mero existente na plaqueta de patrim�nio, caso o patrim&ocirc;nio seja da EMBRATUR.<br>
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