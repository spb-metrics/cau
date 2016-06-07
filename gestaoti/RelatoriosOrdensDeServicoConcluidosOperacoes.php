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
require 'include/PHP/class/class.chamado.php';
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.destino_triagem.php';
require 'include/PHP/class/class.prioridade_chamado.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
$destino_triagem 	= new destino_triagem();
$pagina 			= new Pagina();
$banco 				= new chamado();
/*****************************************************************/

// Configura��o da p�g�na
$pagina->SettituloCabecalho("Opera��es"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configura��es AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	function fExibirParametros(){
		if(document.getElementById("tabelaParametros").style.display == "none"){
			document.getElementById("tabelaParametros").style.display = "block";
			document.getElementById("MaisParametros").style.display = "none";
			document.getElementById("MenosParametros").style.display = "block";
		}else{
			document.getElementById("tabelaParametros").style.display = "none";
			document.getElementById("MaisParametros").style.display = "block";
			document.getElementById("MenosParametros").style.display = "none";
		}
	}

	function fValidaFormLocal(){

		if(document.form.v_DTH_ABERTURA.value == ""){
			alert("Preencha o campo Data de In�cio");
			document.form.v_DTH_ABERTURA.focus();
			return false;
		}
		if(document.form.v_DTH_ENCERRAMENTO_EFETIVO_FINAL.value == ""){
			alert("Preencha o campo Data de Encerramento");
			document.form.v_DTH_ENCERRAMENTO_EFETIVO_FINAL.focus();
			return false;
		}
		if(!comparaDatas(document.form.v_DTH_ABERTURA, document.form.v_DTH_ENCERRAMENTO_EFETIVO_FINAL)){
			alert("A data de in�cio deve ser menor que a data final.");
		 	return false;
		}
		document.form.action = 'RelatoriosOrdensDeServicoConcluidosOperacoes.php';
		document.form.target = '';
		return true;
	}

	function fValidaFormLocal1(){
		if(fValidaFormLocal()){
			document.form.action = 'RelatoriosOrdensDeServicoConcluidosOperacoesPDF.php';
			document.form.target = '_blank';
			return true;
		}else{
			return false;
		}
	}

</script>
<?
$pagina->AbreTabelaPadrao("center", "100%");
$pagina->LinhaCampoFormulario("Per�odo:", "right", "S",	"de " .$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)." a ".$pagina->CampoData("v_DTH_ENCERRAMENTO_EFETIVO_FINAL", "N", " de Encerramento efetivo", $v_DTH_ENCERRAMENTO_EFETIVO_FINAL), "left", "id=".$pagina->GetIdTable());
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal1();", " Gerar Relat�rio "), "2");
$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();

?>
