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
require 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
// Configuração da págína
$pagina->SettipoPagina("O"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Acesso Negado"); // Indica o título do cabeçalho da página
$pagina->MontaCabecalho();
$pagina->LinhaVazia(3);
$pagina->AbreTabelaPadrao("left", "100%");
$pagina->LinhaColspan("center", " <b><font size=\"2\"> Você não tem acesso para acessar está funcionalidade. </font></b>" , "2", "tabelaConteudoHeader");
 
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>