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
$pagina->SettituloCabecalho("Inicial"); // Indica o título do cabeçalho da página
$pagina->MontaCabecalho();
$pagina->LinhaVazia(3);
$pagina->AbreTabelaPadrao("left", "100%");
$pagina->LinhaColspan("center", "Seja bem vindo <b><font size=\"2\">".$_SESSION["NOME"]."</font></b><br><br>
                         Perfil de acesso ao sistema: <strong>".$_SESSION["NOM_PERFIL_ACESSO"]."</strong><br>
                         Cargo/Função: <strong>".$_SESSION["NOM_PERFIL_RECURSO_TI"]."</strong><br>
                         Equipe: <strong>".$_SESSION["NOM_EQUIPE_TI"]."</strong><br>
                         Central de Atendimento: <strong>".$_SESSION["NOM_CENTRAL_ATENDIMENTO"]."</strong>" , "2", "tabelaConteudoHeader");
if($_SESSION["FLG_LIDER_EQUIPE"] == "S"){
	$pagina->LinhaCampoFormularioColspan("center", "Líder/Substituto de liderança de Equipe", "2");
}
$pagina->LinhaCampoFormularioColspan("center", "<br>Selecione uma opção no menu acima.", "2");
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();

?>