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
$pagina->SettituloCabecalho("Imprimir Chamados"); // Indica o t�tulo do cabe�alho da p�gina
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
$pagina->flagScriptCalendario = 0;
$pagina->lightbox = 1;
$pagina->cea = 1;
$pagina->MontaCabecalho();
$pagina->LinhaVazia(1);
?>
<div align="left"><a href="#" class="lbAction" rel="deactivate">Retornar</a></div>
<hr>
<center>
<iframe frameborder="0" scrolling="no" width="495" height="315" src="ChamadoAtendimentoPesquisaImprimirAcao.php?v_EXIBIR=<?=$v_EXIBIR?>"></iframe>
</center>
<?
$pagina->MontaRodape();
?>