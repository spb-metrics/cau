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
require_once '../gestaoti/include/PHP/class/class.pagina.php';
require_once '../gestaoti/include/PHP/class/class.chamado.php';
require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
require_once '../gestaoti/include/PHP/class/class.parametro.php';

require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php'; 
require_once '../gestaoti/include/PHP/class/class.subtipo_chamado.php';
require_once '../gestaoti/include/PHP/class/class.tipo_chamado.php';

$pagina = new Pagina();
$banco = new chamado();
$parametro = new parametro();
$situacao_chamado = new situacao_chamado();

// Configura��o da p�g�na
$pagina->cea = 1;
$pagina->SettituloCabecalho("Inicial"); // Indica o t�tulo do cabe�alho da p�gina
$pagina->MontaCabecalho(0, 1);
$pagina->LinhaVazia(1);

$banco->setNUM_MATRICULA_SOLICITANTE($_SESSION["NUM_MATRICULA_RECURSO"]);
$banco->setNUM_MATRICULA_CONTATO($_SESSION["NUM_MATRICULA_RECURSO"]);
$banco->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Avaliacao);
$banco->AcompanharChamados();

$EXIBIR_MSG_BOAS_VINDAS = false;

$pagina->AbreTabelaPadrao("center", "100%");
$pagina->LinhaCampoFormularioColspan("center", "Seja bem vindo <b><font size=\"2\">".$_SESSION["NOME"]."</font></b>", "6");
$pagina->LinhaCampoFormularioColspan("center", "<br>Selecione uma op��o no menu acima.", "6");
$pagina->FechaTabelaPadrao();

$pagina->AbreTabelaPadrao("center", "100%");
$pagina->LinhaCampoFormularioColspan("center", " &nbsp;", "6");
$pagina->FechaTabelaPadrao();

if($banco->database->rows != 0){  
	
	$pagina->AbreTabelaResultado("center", "100%");
	//$pagina->LinhaCampoFormularioColspan("center", "Seja bem vindo <b><font size=\"2\">".$_SESSION["NOME"]."</font></b>", "6");
	//$pagina->LinhaCampoFormularioColspan("center", "A ".$parametro->GetValorParametro("NOM_AREA_TI")." aguarda a sua avalia��o sobre o atendimento dos chamados abaixo.", "6");

	// Inicio do grid de resultados
	$header = array();
	$header[] = array("Chamado", "10%");
	$header[] = array("Solicita��o", "26%");
	$header[] = array("Situa��o", "15%");
	$header[] = array("Abertura", "16%");
	$header[] = array("Previs�o T�rmino", "16%");
	$header[] = array("T�rmino Efetivo", "16%");

	$corpo = array();
	$pagina->LinhaHeaderTabelaResultado("Chamados Aguardando Minha Avalia��o ", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$corpo[] = array("right", "campo", $row["seq_chamado"]);
		$corpo[] = array("left", "campo", strlen($row["txt_chamado"])>200?substr($row["txt_chamado"],0,200)."...":$row["txt_chamado"]);
		//$corpo[] = array("left", "campo", $row["dsc_atividade_chamado"]);

		// Buscar dados da tabela externa
		require_once '../gestaoti/include/PHP/class/class.situacao_chamado.php';
		$situacao_chamado = new situacao_chamado();
		$situacao_chamado->select($row["seq_situacao_chamado"]);
		$corpo[] = array("left", "campo", $situacao_chamado->DSC_SITUACAO_CHAMADO);

 		// Recuperar dados do SLA
 		$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
		//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
		$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

		$corpo[] = array("center", "campo", $row["dth_abertura"]);
		$corpo[] = array("center", "campo", $v_DTH_ENCERRAMENTO_PREVISAO);
		$corpo[] = array("center", "campo", $row["dth_encerramento_efetivo"]);
		$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
		$corpo = "";
	}
}else {
	$EXIBIR_MSG_BOAS_VINDAS = true;
}




$pagina->AbreTabelaPadrao("center", "100%");
$pagina->LinhaCampoFormularioColspan("center", " &nbsp;", "6");
$pagina->FechaTabelaPadrao();



//CHAMADOS PENDENTES DE APROVA��O

require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
$empregados= new empregados(1);
$funcADM = $empregados->GetFuncaoAdministrativaByLogin($_SESSION["NOM_LOGIN_REDE"]);
$aprovacao = new chamado();	
		
//if($aprovacao->aprovadorDeChamados($funcADM)){ 
	
	
	
	$empregados->SelectCoordenacaoUnidadeSobMnhaResponsabilidade($_SESSION["NUM_MATRICULA_RECURSO"]);
	$COOR =   Array();
	$UOR =   Array();
	
	if($empregados->database->rows != 0){ 
	 	$i = 0;
	 	while ($row = pg_fetch_array($empregados->database->result)){
	 		$COOR[$i] = $row["coor_id"];
	 		$UOR[$i] = $row["uor_id"]; 
			$i++;
	 	}
	}
	
	if($_SESSION["UOR_ID"] == $pagina->COD_UNIDADE_GABINETE_PRESIDENCIA){
		$count = count($UOR);
		$UOR[$count] = $pagina->COD_UNIDADE_PRESIDENCIA; 
	}
	

	$aprovacao->setSEQ_SITUACAO_CHAMADO($situacao_chamado->COD_Aguardando_Aprovacao);  
	$aprovacao->setNUM_MATRICULA_APROVADOR_ATIVIDADE($_SESSION["NUM_MATRICULA_RECURSO"]);
	$aprovacao->setCOOR_ID($COOR);
	$aprovacao->setUOR_ID($UOR);
	
//	if($_SESSION["COOR_ID"]!= null && $_SESSION["COOR_ID"]!= ""){
//		$aprovacao->setCOOR_ID($_SESSION["COOR_ID"]);
//	}
//	 if($_SESSION["UOR_ID"]!= null && $_SESSION["UOR_ID"]!= ""){
//		$aprovacao->setUOR_ID($_SESSION["UOR_ID"]);
//	}
		 
	$aprovacao->selectChamadosAguardandoAprovacao("DTH_ABERTURA");
	
	if($aprovacao->database->rows != 0){ 
		$pagina->AbreTabelaResultado("center", "100%");
		//$pagina->LinhaCampoFormularioColspan("center", "Seja bem vindo <b><font size=\"2\">".$_SESSION["NOME"]."</font></b>", "5");
		//$pagina->LinhaCampoFormularioColspan("center", "A ".$parametro->GetValorParametro("NOM_AREA_TI")." aguarda a sua avalia��o sobre o atendimento dos chamados abaixo.", "6");
	
		// Inicio do grid de resultados
		$header = array();
		$header[] = array("Chamado", "5%");
		$header[] = array("Solicitante", "20%");
		$header[] = array("Atividade", "20%");
		$header[] = array("Solicita��o", "30%");
		$header[] = array("Abertura", "10%");
	
		$corpo = array();
		$pagina->LinhaHeaderTabelaResultado("Chamados Aguardando Minha Aprova��o ", $header);
		while ($row = pg_fetch_array($aprovacao->database->result)){
			$corpo[] = array("right", "campo", $row["seq_chamado"]);
			$empregados = new empregados();
			$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_solicitante"]));
	
			$subtipo_chamado = new subtipo_chamado();
			$subtipo_chamado->select($row["seq_subtipo_chamado"]);
			$tipo_chamado = new tipo_chamado();
			$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
	
			$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);
			$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));
	
			// Recuperar dados do SLA
			$v_DTH_TRIAGEM_PREVISAOO = $aprovacao->fGetDTH_TRIAGEM_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_triagem"]==""?30:$row["qtd_min_sla_triagem"], $row["flg_forma_medicao_tempo"]==""?"U":$row["flg_forma_medicao_tempo"]);
			// $v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_triagem"]==""?60:$row["qtd_min_sla_triagem"], $row["flg_forma_medicao_tempo"]==""?"U":$row["flg_forma_medicao_tempo"]);
			$v_COD_SLA_ATENDIMENTO = $aprovacao->fGetCOD_SLA($row["dth_abertura"], $v_DTH_TRIAGEM_PREVISAOO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);
	
	
			$corpo[] = array("center", "campo", $row["dth_abertura"]);
			//$corpo[] = array("center", "campo", $v_DTH_TRIAGEM_PREVISAOO);
			//$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));
			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoAprovacaoDetalhe.php?v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
			$corpo = "";
		}
	}else {
		$EXIBIR_MSG_BOAS_VINDAS = true;
	}

//}
 

//$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>