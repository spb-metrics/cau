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
require 'include/PHP/class/class.atividade_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';
require 'include/PHP/class/class.tipo_ocorrencia.php';
require 'include/PHP/class/class.central_atendimento.php';

$pagina = new Pagina();
$banco = new atividade_chamado();
$subtipo_chamado = new subtipo_chamado();
$tipo_ocorrencia = new tipo_ocorrencia();
$central_atendimento = new central_atendimento();

// ============================================================================================================
// Configurações AJAX
// ============================================================================================================
function iif($Condicao, $Sim, $Nao){
    if ($Condicao){
            return $Sim;
    } else{
            return $Nao;
    }
}

require 'include/PHP/class/class.Sajax.php';
$Sajax = new Sajax();

function CarregarComboTipoChamado($v_SEQ_CENTRAL_ATENDIMENTO){
	if($v_SEQ_CENTRAL_ATENDIMENTO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.tipo_chamado.php';
		$pagina = new Pagina();
		$tipo_chamado = new tipo_chamado();
		$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
		return $pagina->AjaxFormataArrayCombo($tipo_chamado->combo2("DSC_TIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboSubtipoChamado($v_SEQ_TIPO_CHAMADO){
	if($v_SEQ_TIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.subtipo_chamado.php';
		$pagina = new Pagina();
		$subtipo_chamado = new subtipo_chamado();
		$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
		return $pagina->AjaxFormataArrayCombo($subtipo_chamado->combo2("DSC_SUBTIPO_CHAMADO"));
	}else{
		return "";
	}
}

function CarregarComboAtividade($v_SEQ_SUBTIPO_CHAMADO){
	if($v_SEQ_SUBTIPO_CHAMADO != ""){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.atividade_chamado.php';
		$pagina = new Pagina();
		$atividade_chamado = new atividade_chamado();
		$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
		return $pagina->AjaxFormataArrayCombo($atividade_chamado->combo("DSC_ATIVIDADE_CHAMADO"));
	}else{
		return "";
	}
}


$Sajax->sajax_init();
$Sajax->sajax_debug_mode = 0;
$Sajax->sajax_export("CarregarComboTipoChamado","CarregarComboSubtipoChamado", "CarregarComboAtividade");
$Sajax->sajax_handle_client_request();

if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Atividade de Chamado"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Atividade_chamadoPesquisa.php", "", "Pesquisa"),
						array("Atividade_chamadoCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	//$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];
	// ============================================================================================================
	// Configurações AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		<?
		$Sajax->sajax_show_javascript();
		?>
		// Chamada
		function do_CarregarComboTipoChamado() {
			x_CarregarComboTipoChamado(document.form.v_SEQ_CENTRAL_ATENDIMENTO.value, retorno_CarregarComboTipoChamado);
		}
		// Retorno
		function retorno_CarregarComboTipoChamado(val) {
			fEncheComboBox(val, document.form.v_SEQ_TIPO_CHAMADO);	
			do_CarregarComboSubtipoChamado();	 
		}
		
		// Chamada
		function do_CarregarComboSubtipoChamado() {
			x_CarregarComboSubtipoChamado(document.form.v_SEQ_TIPO_CHAMADO.value, retorno_CarregarComboSubtipoChamado);
		}
		// Retorno
		function retorno_CarregarComboSubtipoChamado(val) {
			fEncheComboBox(val, document.form.v_SEQ_SUBTIPO_CHAMADO);
		}
		// Chamada
		function do_CarregarComboAtividade() {
			x_CarregarComboAtividade(document.form.v_SEQ_SUBTIPO_CHAMADO.value, retorno_CarregarComboAtividade);
		}
		// Retorno
		function retorno_CarregarComboAtividade(val) {
			fEncheComboBox(val, document.form.v_SEQ_ATIVIDADE_CHAMADO);
		}

		// ==================================================== FIM AJAX ==================================================
		function ControlaCampos(){
			do_CarregarComboTipoChamado();
			if(document.form.v_SEQ_TIPO_OCORRENCIA.value == "<?=$tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE?>"){
				document.getElementById('ObrigContingenciamento').innerHTML = "<font color=red>*</font> ";
				document.getElementById('ObrigSolucao').innerHTML = "<font color=red>*</font> ";
			}else{
				document.getElementById('ObrigContingenciamento').innerHTML = "";
				document.getElementById('ObrigSolucao').innerHTML = "";
			}
		}
		function ControlaCampos2(){
			if(document.form.v_FLG_FORMA_MEDICAO_TEMPO.value != ""){
				document.getElementById('ObrigSolucao').innerHTML = "<font color=red>*</font> ";
				document.form.v_QTD_MIN_SLA_SOLUCAO_FINAL.disabled = false;
				document.form.v_QTD_MIN_SLA_ATENDIMENTO.disabled = false;
			}else{
				document.getElementById('ObrigSolucao').innerHTML = "";
				document.form.v_QTD_MIN_SLA_SOLUCAO_FINAL.value = ""
				document.form.v_QTD_MIN_SLA_SOLUCAO_FINAL.disabled = true;
				document.form.v_QTD_MIN_SLA_ATENDIMENTO.value = ""
				document.form.v_QTD_MIN_SLA_ATENDIMENTO.disabled = true;
			}
		}
		function exibirAprovadores(){
			//alert(document.form.v_EXIGE_APROVACAO.checked);
			if(document.form.v_EXIGE_APROVACAO.checked){
				document.getElementById("CAMPOS_APROVADOR").style.display = "block";

			}else{
				document.getElementById("CAMPOS_APROVADOR").style.display = "none";
				document.form.v_NUM_MATRICULA_APROVADOR.value = ""; 
				document.form.v_NUM_MATRICULA_APROVADOR_SUBSTITUTO.value = "";
			}
		}
		function fValidaFormLocal(){
			// Se não forem horas planejadas é obrigatório o preenchimento do SLA de solução
			 
			if(document.form.v_SEQ_TIPO_OCORRENCIA.value == ""){
				alert("Preencha o campo Tipo");
				document.form.v_SEQ_TIPO_OCORRENCIA.focus();
				return false;
			}
			if(document.form.v_SEQ_TIPO_CHAMADO.value == ""){
				alert("Preencha o campo Classe");
				document.form.v_SEQ_TIPO_CHAMADO.focus();
				return false;
			}
			if(document.form.v_SEQ_SUBTIPO_CHAMADO.value == ""){
				alert("Preencha o campo Subclasse");
				document.form.v_SEQ_SUBTIPO_CHAMADO.focus();
				return false;
			}
			if(document.form.v_FLG_ATENDIMENTO_EXTERNO.value == "N" && document.form.v_QTD_MIN_SLA_TRIAGEM.value != ""){
				alert("O campo OLA de Triagem deve ser preenchido apenas para atividades de Atendimento Externo");
				return false;
			}
			if(document.form.v_DSC_ATIVIDADE_CHAMADO.value == ""){
				alert("Preencha o campo Descrição");
				document.form.v_DSC_ATIVIDADE_CHAMADO.focus();
				return false;
			}

			if(document.form.v_SEQ_TIPO_OCORRENCIA.value == "<?=$tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE?>"){
				if(document.form.v_QTD_MIN_SLA_ATENDIMENTO.value == ""){
					alert("É necessário preencher o campo SLA de Contingenciamento");
					document.form.v_QTD_MIN_SLA_ATENDIMENTO.focus();
					return false;
				}
				if(document.form.v_QTD_MIN_SLA_SOLUCAO_FINAL.value == ""){
					alert("É necessário preencher o campo SLA de Solução(Minutos)");
					document.form.v_QTD_MIN_SLA_SOLUCAO_FINAL.focus();
					return false;
				}
			}

			if(document.form.v_FLG_FORMA_MEDICAO_TEMPO.value != ""){
				if(document.form.v_QTD_MIN_SLA_SOLUCAO_FINAL.value == ""){
					alert("É necessário preencher o campo SLA de Solução(Minutos)");
					document.form.v_QTD_MIN_SLA_SOLUCAO_FINAL.focus();
					return false;
				}
			}

			if(document.form.v_EXIGE_APROVACAO.checked){
				if(document.form.v_NUM_MATRICULA_APROVADOR.value == ""){
					alert("É necessário preencher o campo Matrícula do Aprovador");
					document.form.v_NUM_MATRICULA_APROVADOR.focus();
					return false;
				}
				if(document.form.v_NUM_MATRICULA_APROVADOR_SUBSTITUTO.value == ""){
					alert("É necessário preencher o campo Matrícula do Aprovador Substituto");
					document.form.v_NUM_MATRICULA_APROVADOR_SUBSTITUTO.focus();
					return false;
				}
			}
			return true;

		}
	</script>
	<?
	print $pagina->CampoHidden("flag", "1");
	//print $pagina->CampoHidden("SEQ_CENTRAL_ATENDIMENTO", $_SEQ_CENTRAL_ATENDIMENTO);
	
	$pagina->AbreTabelaPadrao("center", "95%", "  border=\"0\"");

	$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "S", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "S", "Central de Atendimento", "S", $central_atendimento->combo(2,$v_SEQ_CENTRAL_ATENDIMENTO), "Escolha", "do_CarregarComboTipoChamado()"), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");
	
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_OCORRENCIA", "S", "Tipo", "S", $tipo_ocorrencia->combo("NOM_TIPO_OCORRENCIA"), "Escolha", "ControlaCampos()"), "left", "", "25%");
 
	
	require 'include/PHP/class/class.tipo_chamado.php';
	$tipo_chamado = new tipo_chamado();
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "S", "Classe de Chamado", "S",null, "Escolha", "do_CarregarComboSubtipoChamado()"), "left", "", "25%");

	// Montar a combo da tabela subtipo_chamado
	$aItemOption = Array();
	$aItemOption[] = array("", "", "Selecione o tipo de chamado");
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_SUBTIPO_CHAMADO", "S", "Subclasse de Chamado", "N", $aItemOption, "Escolha", "do_CarregarComboAtividade()"), "left", "");

	$pagina->LinhaCampoFormulario("Atendimento externo?", "right", "S", $pagina->CampoSelect("v_FLG_ATENDIMENTO_EXTERNO", "S", "Indicador de Atendimento externo", "S", $pagina->comboSimNao($banco->FLG_ATENDIMENTO_EXTERNO)), "left");

	$aItemOption = Array();
	$aItemOption[] = array("C", "", "Horas Corridas");
	$aItemOption[] = array("U", "", "Horas Úteis");
	$pagina->LinhaCampoFormulario("Medição do tempo", "right", "S", $pagina->CampoSelect("v_FLG_FORMA_MEDICAO_TEMPO", "N", "Forma de medição do tempo", "S", $aItemOption, "Horas Planejadas", "ControlaCampos2()"), "left");

	$pagina->LinhaCampoFormulario("Descrição:", "right", "S", $pagina->CampoTexto("v_DSC_ATIVIDADE_CHAMADO", "S", "Descrição", "60", "150", ""), "left");
	$pagina->LinhaCampoFormulario("OLA de Triagem (Minutos):", "right", "N", $pagina->CampoInt("v_QTD_MIN_SLA_TRIAGEM", "N", "Quantidade de Min sla triagem", "5", ""), "left");
	$pagina->LinhaCampoFormulario("<span id=\"ObrigContingenciamento\"></span>SLA de Contigenciamento(Minutos):", "right", "N", $pagina->CampoInt("v_QTD_MIN_SLA_ATENDIMENTO", "N", "Quantidade de Min sla atendimento", "5", "", "disabled"), "left");
	$pagina->LinhaCampoFormulario("<span id=\"ObrigSolucao\"></span>SLA de Solução(Minutos):", "right", "N", $pagina->CampoInt("v_QTD_MIN_SLA_SOLUCAO_FINAL", "N", "Quantidade de Min SLA Solução", "5", "", "disabled"), "left");

	// Montar a combo
	require 'include/PHP/class/class.equipe_ti.php';
	$equipe_ti = new equipe_ti();
	$pagina->LinhaCampoFormulario("Equipe padrão:", "right", "N", $pagina->CampoSelect("v_SEQ_EQUIPE_TI", "N", "Equipe", "S", $equipe_ti->combo(2)), "left", "v_SEQ_EQUIPE_TI", "30%", "70%");

	$pagina->LinhaCampoFormulario("Atividade atribuída para a equipe padrão:", "right", "N", $pagina->CampoTexto("v_TXT_ATIVIDADE", "N", "Descrição", "60", "150", ""), "left");

	$pagina->LinhaCampoFormulario("Exige Aprovação?:", "right", "N", $pagina->CampoCheckboxSimples("v_EXIGE_APROVACAO", "1", iif($v_EXIGE_APROVACAO=="1", "checked=\"checked\"", "") ." onClick=\"exibirAprovadores()\" ",""), "left");

print "<tr align=left><td colspan=2  align=left>";	
print " <div id=\"CAMPOS_APROVADOR\" align=\"left\" style=\"display: block\">";

$pagina->AbreTabelaPadrao("left", "100%", "id=tabela1nivel cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");

	$pagina->LinhaCampoFormulario("Matrícula do Aprovador:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_APROVADOR", "S", "Matrícula do Aprovador" , "11", "11", $v_NUM_MATRICULA_APROVADOR, "").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_APROVADOR", "")
								  , "left" );

	$pagina->LinhaCampoFormulario("Matrícula do Aprovador Substituto:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_APROVADOR_SUBSTITUTO", "S", "Matrícula do Aprovador Substituto" , "11", "11", $v_NUM_MATRICULA_APROVADOR_SUBSTITUTO, "").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_APROVADOR_SUBSTITUTO", "")
								  , "left","70%","30%");
								  
$pagina->FechaTabelaPadrao();
print "</div>";		
print "</tr></td>";			
					  
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Pesquisar o tipo de chamado
	//$subtipo_chamado->select($v_SEQ_SUBTIPO_CHAMADO);
	//if($subtipo_chamado->FLG_ATENDIMENTO_EXTERNO == "N" && $v_FLG_ATENDIMENTO_EXTERNO == "S"){
	//	$pagina->mensagem("<br><br><br><br>Não é possível cadastrar a atividade como 'Atendimento Externo' pois a Subclasse de Chamado informada é de atendimento interno<br><br>Clique <a href='Subtipo_chamadoCadastro.php'>aqui</a> para retornar");
	//}else{
		// Incluir regstro
		$banco->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
		$banco->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
		$banco->setDSC_ATIVIDADE_CHAMADO(addslashes($v_DSC_ATIVIDADE_CHAMADO));
		$banco->setQTD_MIN_SLA_TRIAGEM($v_QTD_MIN_SLA_TRIAGEM);

		// Adaptação realizada para a implementação de gestão de problemas
		if($v_SEQ_TIPO_OCORRENCIA != $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE && $v_QTD_MIN_SLA_SOLUCAO_FINAL != ""){
			$banco->setQTD_MIN_SLA_ATENDIMENTO($v_QTD_MIN_SLA_SOLUCAO_FINAL);
			//$banco->setQTD_MIN_SLA_SOLUCAO_FINAL("");
		}else{
			$banco->setQTD_MIN_SLA_ATENDIMENTO($v_QTD_MIN_SLA_ATENDIMENTO);
			$banco->setQTD_MIN_SLA_SOLUCAO_FINAL($v_QTD_MIN_SLA_SOLUCAO_FINAL);
		}

		$banco->setFLG_ATENDIMENTO_EXTERNO($v_FLG_ATENDIMENTO_EXTERNO);
		$banco->setFLG_FORMA_MEDICAO_TEMPO($v_FLG_FORMA_MEDICAO_TEMPO);
		$banco->setSEQ_EQUIPE_TI($v_SEQ_EQUIPE_TI);
		$banco->setTXT_ATIVIDADE(addslashes($v_TXT_ATIVIDADE));
		
		$banco->setFLG_EXIGE_APROVACAO($v_EXIGE_APROVACAO);
		
		require 'include/PHP/class/class.empregados.oracle.php';
		$empregados = new empregados();
		// Validar matrícula do líder
		$v_NUM_MATRICULA_APROVADOR_MAT = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_APROVADOR);
		if($v_NUM_MATRICULA_APROVADOR_MAT == ""){
			$pagina->ScriptAlert("Matrícula do líder não encontrada");
			$flag = "";
		}
	
		// Validar matrícula do substituto
		$v_NUM_MATRICULA_APROVADOR_SUBSTITUTO_MAT = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_APROVADOR_SUBSTITUTO);
		if($v_NUM_MATRICULA_APROVADOR_SUBSTITUTO_MAT == ""){
			$pagina->ScriptAlert("Matrícula do substituto não encontrada");
			$flag = "";
		}
		$banco->setNUM_MATRICULA_APROVADOR($v_NUM_MATRICULA_APROVADOR_MAT);
		$banco->setNUM_MATRICULA_APROVADOR_SUBSTITUTO($v_NUM_MATRICULA_APROVADOR_SUBSTITUTO_MAT);
		$banco->insert();
		// Código inserido: $banco->SEQ_ATIVIDADE_CHAMADO
		if($banco->error != ""){
			$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
		}else{
			$pagina->redirectTo("Atividade_chamadoPesquisa.php?v_SEQ_ATIVIDADE_CHAMADO=$banco->SEQ_ATIVIDADE_CHAMADO");
		}
	//}
}
?>
