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
require 'include/PHP/class/class.item_configuracao.php';
//require 'include/PHP/class/class.empregados.php';
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.recurso_ti.php';
require 'include/PHP/class/class.equipe_envolvida.php';
$pagina = new Pagina();
$banco = new item_configuracao();
$empregados = new empregados();
$recurso_ti = new recurso_ti();
$equipe_envolvida = new equipe_envolvida();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Relatório Analítico de Alocação de Profissionais"); // Indica o título do cabeçalho da página
$pagina->setAction("RelAlocacao1.php");
$pagina->setTarget("_blank");
// Itens das abas
//$aItemAba = Array( array("#", "tabact", "Pesquisa"),
//				   array("Item_configuracaoCadastro.php", "", "Adicionar") );
//$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "45%");

$pagina->LinhaCampoFormulario("TI Regional:", "right", "S",
								   $pagina->CampoTexto("v_UOR_SIGLA", "S", "TI Regional", "10", "10", "TISI").
								  $pagina->ButtonProcuraUorg("v_UOR_SIGLA", "TI")
								  , "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>
