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
$pagina->SettituloCabecalho("Campo Classe de Chamado");
$pagina->MontaCabecalho();
?>
<div align="left">
	<br>
	Op&ccedil;&otilde;es dispon&iacute;veis:
	<blockquote>
	- <b>Sistemas de Informa&ccedil;&atilde;o</b>: Selecione esta op&ccedil;&atilde;o se o seu chamado se refere a utiliza&ccedil;&atilde;o de algum sistema de informa&ccedil;&atilde;o institucional. Ao selecionar esta op&ccedil;&atilde;o ser&aacute; exibida uma combobox para a sele&ccedil;&atilde;o do sistema de informa&ccedil;&atilde;o.<br>Exemplos de sistemas de informa&ccedil;&atilde;o institucionais: Aquarela, SGU, SGRH e etc.<br>
	<br>
	- <b>Suporte T&eacute;cnico</b>: Selecione esta op&ccedil;&atilde;o para qualquer necessidade que n&atilde;o esteja relacionado com a manipula&ccedil;&atilde;o de um sistema de informa&ccedil;&atilde;o institucional.<br>
	</blockquote>

	</ul>
</div>

<?
$pagina->MontaRodape();
?>