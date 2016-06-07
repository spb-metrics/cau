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
$pagina->SettituloCabecalho("Resolução do incidente no tempo estipulado (2º nível)"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	function fValidaFormLocal(){

		if(document.form.v_DTH_ABERTURA.value == ""){
			alert("Preencha o campo Data de Início");
			document.form.v_DTH_ABERTURA.focus();
			return false;
		}
		if(document.form.v_DTH_ABERTURA_FINAL.value == ""){
			alert("Preencha o campo Data de Encerramento");
			document.form.v_DTH_ABERTURA_FINAL.focus();
			return false;
		}
		if(!comparaDatas(document.form.v_DTH_ABERTURA, document.form.v_DTH_ABERTURA_FINAL)){
			alert("A data de início deve ser menor que a data final.");
		 	return false;
		}
		document.form.action = 'RelatoriosKPIResolucaoIncidentePDF.php';
		document.form.target = '_blank';
		return true;
	}
</script>
<?
$pagina->AbreTabelaPadrao("center", "100%");
$pagina->LinhaCampoFormulario("Período:", "right", "S", "de " .$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)." a ".$pagina->CampoData("v_DTH_ABERTURA_FINAL", "N", " de Encerramento efetivo", $v_DTH_ENCERRAMENTO_EFETIVO_FINAL), "left", "id=".$pagina->GetIdTable());
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Gerar Relatório "), "2");
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>
