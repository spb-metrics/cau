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
$pagina = new Pagina();
// Configura��o da p�g�na
$pagina->SettipoPagina("O"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Acesso Negado"); // Indica o t�tulo do cabe�alho da p�gina
$pagina->MontaCabecalho();
$pagina->LinhaVazia(3);
$pagina->AbreTabelaPadrao("left", "100%");
$pagina->LinhaColspan("center", " <b><font size=\"2\"> Voc� n�o tem acesso para acessar est� funcionalidade. </font></b>" , "2", "tabelaConteudoHeader");
 
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>