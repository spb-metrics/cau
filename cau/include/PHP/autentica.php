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
//================================================================================================================================
//      Fun��o: Autentica Usu�rio
//      Descri��o: Verifica as Sessions do usuario logado e verifica se elas est�o de acordo com as informa��es do banco
//================================================================================================================================
session_start();
// Buscar o perfil de acesso do usu�rio
require_once '../gestaoti/include/PHP/class/class.pagina.php';
$pagina1 = new Pagina();
if($_SESSION["NUM_MATRICULA"] == ""){
	session_destroy();
	//print "<br>2>>>".$_SESSION["NUM_MATRICULA_RECURSO"];
	$pagina1->redirectTo("index.php");
	$pagina1 = "";
}
?>
