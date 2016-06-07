<?php
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
require '../gestaoti/include/PHP/class/class.pagina.php';
require '../gestaoti/include/PHP/class/class.chamado.php';
require '../gestaoti/include/PHP/class/class.situacao_chamado.php';
$pagina = new Pagina();
$banco = new chamado();
$situacao_chamado = new situacao_chamado();
$v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND = $situacao_chamado->COD_Aguardando_Atendimento.",".$situacao_chamado->COD_Aguardando_Planejamento;
$v_SEQ_SITUACAO_CHAMADO_EXEC = $situacao_chamado->COD_Em_Andamento.",".$situacao_chamado->COD_Suspenca;
// Executar a altera��o de prioridade
if($_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){
	if($flag=="1"){ // Diminuir a prioridade
		$banco->AlterarPrioridade($v_SEQ_CHAMADO, $v_NUM_PRIORIDADE_FILA, $v_SEQ_EQUIPE_TI, $v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND, "D");
		$pagina->ScriptAlert("Altera��o de prioridade realzada com sucesso.");
		$pagina->redirectToJS($PHP_SELF);
	}
	if($flag=="2"){ // Para aumentar a prioridade
		$banco->AlterarPrioridade($v_SEQ_CHAMADO, $v_NUM_PRIORIDADE_FILA, $v_SEQ_EQUIPE_TI, $v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND, "A");
		$pagina->ScriptAlert("Altera��o de prioridade realzada com sucesso.");
		$pagina->redirectToJS($PHP_SELF);
	}
	if($flag=="3"){ // Diminuir a prioridade
		$banco->AlterarPrioridade($v_SEQ_CHAMADO, $v_NUM_PRIORIDADE_FILA, $v_SEQ_EQUIPE_TI, $v_SEQ_SITUACAO_CHAMADO_EXEC, "D");
		$pagina->ScriptAlert("Altera��o de prioridade realzada com sucesso.");
		$pagina->redirectToJS($PHP_SELF);
	}
	if($flag=="4"){ // Para aumentar a prioridade
		$banco->AlterarPrioridade($v_SEQ_CHAMADO, $v_NUM_PRIORIDADE_FILA, $v_SEQ_EQUIPE_TI, $v_SEQ_SITUACAO_CHAMADO_EXEC, "A");
		$pagina->ScriptAlert("Altera��o de prioridade realzada com sucesso.");
		$pagina->redirectToJS($PHP_SELF);
	}
}

// Configura��o da p�g�na
$pagina->cea = 1;
$pagina->SettituloCabecalho("Lista de prioridades de manuten��es em sistemas de informa��o"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();

// =========================================
// Loop das equipes relacionadas
// =========================================
require_once '../gestaoti/include/PHP/class/class.item_configuracao.php';
$item_configuracao = new item_configuracao();
if($_SESSION["FLG_CLIENTE_GESTOR"] == "S" && $_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "N"){ // Usu�rio � apenas gestor
	$item_configuracao->getEquipesGestor($_SESSION["NUM_MATRICULA"]);
}elseif($_SESSION["FLG_CLIENTE_GESTOR"] == "N" && $_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){ // Usu�rio � apenas Priorizador
	$item_configuracao->getEquipesPriorizador($_SESSION["NUM_MATRICULA"]);
}if($_SESSION["FLG_CLIENTE_GESTOR"] == "S" && $_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){ // Usu�rio � gestor e priorizador
	$item_configuracao->getEquipesGestorPriorizador($_SESSION["NUM_MATRICULA"]);
}
if($item_configuracao->database->rows == 0){
	print "Nenhuma equipe vinculada.";
}else{
	while ($rowEquipes = oci_fetch_array($item_configuracao->database->result, OCI_BOTH)){
		// ============================== Fila de Chamados Aguardando Atendimento ==================
		$pagina->AbreTabelaResultado("center", "100%");
		// Cabe�alho de resultados
		$header = array();
		if($_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){
			$header[] = array("&nbsp;", "4%");
			$header[] = array("Prior.", "7%");
			$header[] = array("Chamado", "7%");
			$header[] = array("Abertura", "10%");
			$header[] = array("Previs�o T�rmino", "10%");
			$header[] = array("Sistema", "26%");
			$header[] = array("Descri��o", "36%");
		}else{
			$header[] = array("Prior.", "7%");
			$header[] = array("Chamado", "7%");
			$header[] = array("Abertura", "10%");
			$header[] = array("Previs�o T�rmino", "10%");
			$header[] = array("Sistema", "26%");
			$header[] = array("Descri��o", "40%");
		}
		$pagina->LinhaHeaderTabelaResultado("Prioriza��o de demanas aguardando atendimento da ".$rowEquipes["NOM_EQUIPE_TI"], $header);

		// Par�metros da pesquisa
		$banco->setNUM_PRIORIDADE_FILA("NOTNULL");
		$banco->setSEQ_EQUIPE_TI($rowEquipes["SEQ_EQUIPE_TI"]);
		$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND);
		$banco->selectParam("NUM_PRIORIDADE_FILA");
		if($banco->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("center", "Nenhum chamado priorizado aguardando atendimento", count($header));
		}else{
			$corpo = array();
			$cont = 1;
			while ($row = oci_fetch_array($banco->database->result, OCI_BOTH)){
				if($_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){
					if($banco->database->rows > 1){
						if($cont == 1){
							$corpo[] = array("right", "campo",
								$pagina->BotaoParaBaixo($PHP_SELF."?flag=1&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."&v_NUM_PRIORIDADE_FILA=".$row["NUM_PRIORIDADE_FILA"]."&v_SEQ_EQUIPE_TI=".$rowEquipes["SEQ_EQUIPE_TI"], "Diminuir a prioridade do chamado")
							);
						}elseif($cont == $banco->database->rows){
							$corpo[] = array("left", "campo",
								$pagina->BotaoParaCima($PHP_SELF."?flag=2&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."&v_NUM_PRIORIDADE_FILA=".$row["NUM_PRIORIDADE_FILA"]."&v_SEQ_EQUIPE_TI=".$rowEquipes["SEQ_EQUIPE_TI"], "Aumentar a prioridade do chamado")
							);
						}else{
							$corpo[] = array("center", "campo",
								$pagina->BotaoParaCima($PHP_SELF."?flag=2&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."&v_NUM_PRIORIDADE_FILA=".$row["NUM_PRIORIDADE_FILA"]."&v_SEQ_EQUIPE_TI=".$rowEquipes["SEQ_EQUIPE_TI"], "Aumentar a prioridade do chamado")."&nbsp;".
								$pagina->BotaoParaBaixo($PHP_SELF."?flag=1&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."&v_NUM_PRIORIDADE_FILA=".$row["NUM_PRIORIDADE_FILA"]."&v_SEQ_EQUIPE_TI=".$rowEquipes["SEQ_EQUIPE_TI"], "Diminuir a prioridade do chamado")
							);
						}
					}else{
						$corpo[] = array("left", "campo","&nbsp;");
					}
				}

				$corpo[] = array("right", "campo", $row["NUM_PRIORIDADE_FILA"]);
				$corpo[] = array("right", "campo", $row["SEQ_CHAMADO"]);
				$corpo[] = array("center", "campo", $row["DTH_ABERTURA"]);
				$corpo[] = array("center", "campo", $row["DTH_ENCERRAMENTO_PREVISAO"]);

				$item_configuracao = new item_configuracao();
				$item_configuracao->select($row["SEQ_ITEM_CONFIGURACAO"]);
				$corpo[] = array("left", "campo", $item_configuracao->SIG_ITEM_CONFIGURACAO);

				$corpo[] = array("left", "campo", $row["TXT_CHAMADO"]);

				$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."';\"");
				$corpo = "";

				$cont++;
			}
		}
		$pagina->FechaTabelaPadrao();

		$pagina->LinhaVazia(1);

		// ============================== Fila de Chamados Em Atendimento ==================
		$pagina->AbreTabelaResultado("center", "100%");
		// Cabe�alho de resultados
		$header = array();
		if($_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){
			$header[] = array("&nbsp;", "4%");
			$header[] = array("Prior.", "7%");
			$header[] = array("Chamado", "7%");
			$header[] = array("Abertura", "10%");
			$header[] = array("Previs�o T�rmino", "10%");
			$header[] = array("Sistema", "26%");
			$header[] = array("Descri��o", "36%");
		}else{
			$header[] = array("Prior.", "7%");
			$header[] = array("Chamado", "7%");
			$header[] = array("Abertura", "10%");
			$header[] = array("Previs�o T�rmino", "10%");
			$header[] = array("Sistema", "26%");
			$header[] = array("Descri��o", "40%");
		}

		$pagina->LinhaHeaderTabelaResultado("Prioriza��o de demanas em atendimento da ".$rowEquipes["NOM_EQUIPE_TI"], $header);

		// Par�metros da pesquisa
		$banco->setNUM_PRIORIDADE_FILA("NOTNULL");
		$banco->setSEQ_EQUIPE_TI($rowEquipes["SEQ_EQUIPE_TI"]);
		$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO_EXEC);
		$banco->selectParam("NUM_PRIORIDADE_FILA");
		if($banco->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("center", "Nenhum chamado priorizado em atendimento", count($header));
		}else{
			$corpo = array();

			$cont = 1;
			while ($row = oci_fetch_array($banco->database->result, OCI_BOTH)){
				if($_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){
					if($banco->database->rows > 1){
						if($cont == 1){
							$corpo[] = array("right", "campo",
								$pagina->BotaoParaBaixo($PHP_SELF."?flag=3&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."&v_NUM_PRIORIDADE_FILA=".$row["NUM_PRIORIDADE_FILA"]."&v_SEQ_EQUIPE_TI=".$rowEquipes["SEQ_EQUIPE_TI"], "Diminuir a prioridade do chamado")
							);
						}elseif($cont == $banco->database->rows){
							$corpo[] = array("left", "campo",
								$pagina->BotaoParaCima($PHP_SELF."?flag=4&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."&v_NUM_PRIORIDADE_FILA=".$row["NUM_PRIORIDADE_FILA"]."&v_SEQ_EQUIPE_TI=".$rowEquipes["SEQ_EQUIPE_TI"], "Aumentar a prioridade do chamado")
							);
						}else{
							$corpo[] = array("center", "campo",
								$pagina->BotaoParaCima($PHP_SELF."?flag=4&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."&v_NUM_PRIORIDADE_FILA=".$row["NUM_PRIORIDADE_FILA"]."&v_SEQ_EQUIPE_TI=".$rowEquipes["SEQ_EQUIPE_TI"], "Aumentar a prioridade do chamado")."&nbsp;".
								$pagina->BotaoParaBaixo($PHP_SELF."?flag=3&v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."&v_NUM_PRIORIDADE_FILA=".$row["NUM_PRIORIDADE_FILA"]."&v_SEQ_EQUIPE_TI=".$rowEquipes["SEQ_EQUIPE_TI"], "Diminuir a prioridade do chamado")
							);
						}
					}else{
						$corpo[] = array("left", "campo","&nbsp;");
					}
				}

				$corpo[] = array("right", "campo", $row["NUM_PRIORIDADE_FILA"]);
				$corpo[] = array("right", "campo", $row["SEQ_CHAMADO"]);
				$corpo[] = array("center", "campo", $row["DTH_ABERTURA"]);
				$corpo[] = array("center", "campo", $row["DTH_ENCERRAMENTO_PREVISAO"]);

				$item_configuracao = new item_configuracao();
				$item_configuracao->select($row["SEQ_ITEM_CONFIGURACAO"]);
				$corpo[] = array("left", "campo", $item_configuracao->SIG_ITEM_CONFIGURACAO);

				$corpo[] = array("left", "campo", $row["TXT_CHAMADO"]);

				$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhesCEA.php?v_SEQ_CHAMADO=".$row["SEQ_CHAMADO"]."';\"");
				$corpo = "";

				$cont++;
			}
		}
		$pagina->FechaTabelaPadrao();
	}
}
$pagina->MontaRodape();
?>
