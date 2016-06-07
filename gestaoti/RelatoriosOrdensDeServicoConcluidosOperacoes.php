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

// Configuração da págína
$pagina->SettituloCabecalho("Operações"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
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
			alert("Preencha o campo Data de Início");
			document.form.v_DTH_ABERTURA.focus();
			return false;
		}
		if(document.form.v_DTH_ENCERRAMENTO_EFETIVO_FINAL.value == ""){
			alert("Preencha o campo Data de Encerramento");
			document.form.v_DTH_ENCERRAMENTO_EFETIVO_FINAL.focus();
			return false;
		}
		if(!comparaDatas(document.form.v_DTH_ABERTURA, document.form.v_DTH_ENCERRAMENTO_EFETIVO_FINAL)){
			alert("A data de início deve ser menor que a data final.");
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
$pagina->LinhaCampoFormulario("Período:", "right", "S",	"de " .$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)." a ".$pagina->CampoData("v_DTH_ENCERRAMENTO_EFETIVO_FINAL", "N", " de Encerramento efetivo", $v_DTH_ENCERRAMENTO_EFETIVO_FINAL), "left", "id=".$pagina->GetIdTable());
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal1();", " Gerar Relatório "), "2");
$pagina->FechaTabelaPadrao();

$pagina->MontaRodape();

?>
