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
//================================================================================================================================
//      Função: Autentica Usuário
//      Descrição: Verifica as Sessions do usuario logado e verifica se elas estão de acordo com as informações do banco
//================================================================================================================================
session_start();
// Buscar o perfil de acesso do usuário
require_once '../gestaoti/include/PHP/class/class.pagina.php';
$pagina1 = new Pagina();
if($_SESSION["NUM_MATRICULA"] == ""){
	session_destroy();
	//print "<br>2>>>".$_SESSION["NUM_MATRICULA_RECURSO"];
	$pagina1->redirectTo("index.php");
	$pagina1 = "";
}
?>
