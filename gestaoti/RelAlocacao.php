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
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Relat�rio Anal�tico de Aloca��o de Profissionais"); // Indica o t�tulo do cabe�alho da p�gina
$pagina->setAction("RelAlocacao1.php");
$pagina->setTarget("_blank");
// Itens das abas
//$aItemAba = Array( array("#", "tabact", "Pesquisa"),
//				   array("Item_configuracaoCadastro.php", "", "Adicionar") );
//$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "45%");

$pagina->LinhaCampoFormulario("TI Regional:", "right", "S",
								   $pagina->CampoTexto("v_UOR_SIGLA", "S", "TI Regional", "10", "10", "TISI").
								  $pagina->ButtonProcuraUorg("v_UOR_SIGLA", "TI")
								  , "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>
