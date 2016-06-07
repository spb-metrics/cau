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
require 'include/PHP/class/class.equipe_ti.php';
$pagina = new Pagina();
$banco = new equipe_ti();

if($flag == "1"){
	require 'include/PHP/class/class.empregados.oracle.php';
	$empregados = new empregados();
	// Validar matrícula do líder
	$v_NUM_MATRICULA_LIDER_MAT = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_LIDER);
	if($v_NUM_MATRICULA_LIDER_MAT == ""){
		$pagina->ScriptAlert("Matrícula do líder não encontrada");
		$flag = "";
	}

	// Validar matrícula do substituto
	$v_NUM_MATRICULA_SUBSTITUTO_MAT = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_SUBSTITUTO);
	if($v_NUM_MATRICULA_SUBSTITUTO_MAT == ""){
		$pagina->ScriptAlert("Matrícula do substituto não encontrada");
		$flag = "";
	}

	// Validar matrícula do priorizador
	if($v_NUM_MATRICULA_PRIORIZADOR != ""){
		$v_NUM_MATRICULA_PRIORIZADOR_MAT = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_PRIORIZADOR);
		if($v_NUM_MATRICULA_PRIORIZADOR_MAT == ""){
			$pagina->ScriptAlert("Matrícula do priorizador não encontrada");
			$flag = "";
		}
	}
}else{
	$v_COD_DEPENDENCIA = $_SESSION["COD_DEPENDENCIA"];
}

if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Equipe de Atendimento"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Equipe_tiPesquisa.php", "", "Pesquisa"),
						array("Equipe_tiCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "95%");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_EQUIPE_TI", "S", "Nome", "60", "120", $v_NOM_EQUIPE_TI), "left");

	$pagina->LinhaCampoFormulario("Matricula do Líder:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_LIDER", "S", "Matrícula do Líder" , "11", "11", $v_NUM_MATRICULA_LIDER, "").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_LIDER", "TI")
								  , "left");

	$pagina->LinhaCampoFormulario("Matricula do Substituto:", "right", "S",
								  $pagina->CampoTexto("v_NUM_MATRICULA_SUBSTITUTO", "S", "Matrícula do Substituto" , "11", "11", $v_NUM_MATRICULA_SUBSTITUTO, "").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_SUBSTITUTO", "TI")
								  , "left");

	$pagina->LinhaCampoFormulario("Matricula do Priorizador:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_PRIORIZADOR", "N", "Matrícula do Priorizador" , "11", "11", $v_NUM_MATRICULA_PRIORIZADOR, "").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_PRIORIZADOR", "")
								  , "left");
								  
	// Montar a combo
	require 'include/PHP/class/class.central_atendimento.php';
	$central_atendimento = new central_atendimento();
	 
	$pagina->LinhaCampoFormulario("Central de Atendimento:", "right", "S", $pagina->CampoSelect("v_SEQ_CENTRAL_ATENDIMENTO", "S", "Central de Atendimento", "S", $central_atendimento->combo(2)), "left", "v_SEQ_CENTRAL_ATENDIMENTO", "30%", "70%");
								  

	// Montar a combo
	//require 'include/PHP/class/class.dependencias.php';
	//$dependencias = new dependencias();
	//$pagina->LinhaCampoFormulario("Dependência:", "right", "S", $pagina->CampoSelect("v_COD_DEPENDENCIA", "S", "Dependência", "N", $dependencias->combo(2, $v_COD_DEPENDENCIA)), "left", "v_COD_DEPENDENCIA", "30%", "70%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	$pagina->autentica();
	
	//$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];
	
	// Incluir regstro
	$banco->setNOM_EQUIPE_TI($v_NOM_EQUIPE_TI);
	$banco->setNUM_MATRICULA_LIDER($v_NUM_MATRICULA_LIDER_MAT);
	$banco->setNUM_MATRICULA_SUBSTITUTO($v_NUM_MATRICULA_SUBSTITUTO_MAT);
	$banco->setNUM_MATRICULA_PRIORIZADOR($v_NUM_MATRICULA_PRIORIZADOR_MAT);
	$banco->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	$banco->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
	$banco->insert();
	// Código inserido: $banco->SEQ_EQUIPE_TI
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Equipe_tiPesquisa.php?v_SEQ_EQUIPE_TI=$banco->SEQ_EQUIPE_TI");
	}
}
?>
