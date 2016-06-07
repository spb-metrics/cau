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
/******************************************************************
 * Área de inclusão dos arquivos que serão utilizados nesta página.
 *****************************************************************/
 require 'include/PHP/class/class.pagina.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
 //$destino_triagem	= new destino_triagem();
 $pagina 			= new Pagina();
/*****************************************************************/
// Configuração da págína
$pagina->SettituloCabecalho("Requisição de Transporte Para Serviço"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	function fValidaFormLocal(){

		if(document.form.v_quantidade_formularios.value == ""){
			alert("Preencha o campo Quatidade de Formulários");
			document.form.v_quantidade_formularios.focus();
			return false;
		}
		if(document.form.v_quantidade_formularios.value == 0){
			alert("o campo Quatidade de Formulários deve ser maior que 0");
			document.form.v_quantidade_formularios.focus();
			return false;
		}
		 
		document.form.action = 'RelatorioRequisicaoTransporteParaServicoPopup.php';
		document.form.target = '_blank';
		return true;
	}
</script>
<?
$pagina->AbreTabelaPadrao("center", "80%","border=0");
$pagina->LinhaCampoFormulario("Quatidade de Formulários:", "right", "S", $pagina->CampoInt("v_quantidade_formularios", "S", "Quatidade de Formulários", "2", "", ""), "left","","50%","0%");
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Gerar Requisição de Transporte "), "2");
$pagina->FechaTabelaPadrao();

if($v_quantidade_formularios > 0){
 
}

$pagina->MontaRodape();
?>
