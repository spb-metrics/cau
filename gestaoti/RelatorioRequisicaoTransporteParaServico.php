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
/******************************************************************
 * �rea de inclus�o dos arquivos que ser�o utilizados nesta p�gina.
 *****************************************************************/
 require 'include/PHP/class/class.pagina.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
 //$destino_triagem	= new destino_triagem();
 $pagina 			= new Pagina();
/*****************************************************************/
// Configura��o da p�g�na
$pagina->SettituloCabecalho("Requisi��o de Transporte Para Servi�o"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configura��es AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	function fValidaFormLocal(){

		if(document.form.v_quantidade_formularios.value == ""){
			alert("Preencha o campo Quatidade de Formul�rios");
			document.form.v_quantidade_formularios.focus();
			return false;
		}
		if(document.form.v_quantidade_formularios.value == 0){
			alert("o campo Quatidade de Formul�rios deve ser maior que 0");
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
$pagina->LinhaCampoFormulario("Quatidade de Formul�rios:", "right", "S", $pagina->CampoInt("v_quantidade_formularios", "S", "Quatidade de Formul�rios", "2", "", ""), "left","","50%","0%");
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Gerar Requisi��o de Transporte "), "2");
$pagina->FechaTabelaPadrao();

if($v_quantidade_formularios > 0){
 
}

$pagina->MontaRodape();
?>
