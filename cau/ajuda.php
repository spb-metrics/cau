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
require '../gestaoti/include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->SettituloCabecalho("Ajuda"); // Indica o título do cabeçalho da página
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
	<?
	if($action == "tipo"){
		?><iframe frameborder="0" scrolling="no" width="495" height="315" src="ajudaCampoTipo.php"></iframe><?
	}elseif($action == "classe"){
		?><iframe frameborder="0" scrolling="no" width="495" height="315" src="ajudaCampoClasse.php"></iframe><?
	}elseif($action == "patrimonio"){
		?><iframe frameborder="0" scrolling="no" width="495" height="315" src="ajudaCampoPatrimonio.php"></iframe><?
	}
	?>

</center>
<?
$pagina->MontaRodape();
?>