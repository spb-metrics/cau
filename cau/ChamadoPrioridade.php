<?php
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
require '../gestaoti/include/PHP/class/class.chamado.php';
require '../gestaoti/include/PHP/class/class.situacao_chamado.php';
$pagina = new Pagina();
$banco = new chamado();
$situacao_chamado = new situacao_chamado();
$v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND = $situacao_chamado->COD_Aguardando_Atendimento.",".$situacao_chamado->COD_Aguardando_Planejamento;
$v_SEQ_SITUACAO_CHAMADO_EXEC = $situacao_chamado->COD_Em_Andamento.",".$situacao_chamado->COD_Suspenca;
// Executar a alteração de prioridade
if($_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){
	if($flag=="1"){ // Diminuir a prioridade
		$banco->AlterarPrioridade($v_SEQ_CHAMADO, $v_NUM_PRIORIDADE_FILA, $v_SEQ_EQUIPE_TI, $v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND, "D");
		$pagina->ScriptAlert("Alteração de prioridade realzada com sucesso.");
		$pagina->redirectToJS($PHP_SELF);
	}
	if($flag=="2"){ // Para aumentar a prioridade
		$banco->AlterarPrioridade($v_SEQ_CHAMADO, $v_NUM_PRIORIDADE_FILA, $v_SEQ_EQUIPE_TI, $v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND, "A");
		$pagina->ScriptAlert("Alteração de prioridade realzada com sucesso.");
		$pagina->redirectToJS($PHP_SELF);
	}
	if($flag=="3"){ // Diminuir a prioridade
		$banco->AlterarPrioridade($v_SEQ_CHAMADO, $v_NUM_PRIORIDADE_FILA, $v_SEQ_EQUIPE_TI, $v_SEQ_SITUACAO_CHAMADO_EXEC, "D");
		$pagina->ScriptAlert("Alteração de prioridade realzada com sucesso.");
		$pagina->redirectToJS($PHP_SELF);
	}
	if($flag=="4"){ // Para aumentar a prioridade
		$banco->AlterarPrioridade($v_SEQ_CHAMADO, $v_NUM_PRIORIDADE_FILA, $v_SEQ_EQUIPE_TI, $v_SEQ_SITUACAO_CHAMADO_EXEC, "A");
		$pagina->ScriptAlert("Alteração de prioridade realzada com sucesso.");
		$pagina->redirectToJS($PHP_SELF);
	}
}

// Configuração da págína
$pagina->cea = 1;
$pagina->SettituloCabecalho("Lista de prioridades de manutenções em sistemas de informação"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();

// =========================================
// Loop das equipes relacionadas
// =========================================
require_once '../gestaoti/include/PHP/class/class.item_configuracao.php';
$item_configuracao = new item_configuracao();
if($_SESSION["FLG_CLIENTE_GESTOR"] == "S" && $_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "N"){ // Usuário é apenas gestor
	$item_configuracao->getEquipesGestor($_SESSION["NUM_MATRICULA"]);
}elseif($_SESSION["FLG_CLIENTE_GESTOR"] == "N" && $_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){ // Usuário é apenas Priorizador
	$item_configuracao->getEquipesPriorizador($_SESSION["NUM_MATRICULA"]);
}if($_SESSION["FLG_CLIENTE_GESTOR"] == "S" && $_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){ // Usuário é gestor e priorizador
	$item_configuracao->getEquipesGestorPriorizador($_SESSION["NUM_MATRICULA"]);
}
if($item_configuracao->database->rows == 0){
	print "Nenhuma equipe vinculada.";
}else{
	while ($rowEquipes = oci_fetch_array($item_configuracao->database->result, OCI_BOTH)){
		// ============================== Fila de Chamados Aguardando Atendimento ==================
		$pagina->AbreTabelaResultado("center", "100%");
		// Cabeçalho de resultados
		$header = array();
		if($_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){
			$header[] = array("&nbsp;", "4%");
			$header[] = array("Prior.", "7%");
			$header[] = array("Chamado", "7%");
			$header[] = array("Abertura", "10%");
			$header[] = array("Previsão Término", "10%");
			$header[] = array("Sistema", "26%");
			$header[] = array("Descrição", "36%");
		}else{
			$header[] = array("Prior.", "7%");
			$header[] = array("Chamado", "7%");
			$header[] = array("Abertura", "10%");
			$header[] = array("Previsão Término", "10%");
			$header[] = array("Sistema", "26%");
			$header[] = array("Descrição", "40%");
		}
		$pagina->LinhaHeaderTabelaResultado("Priorização de demanas aguardando atendimento da ".$rowEquipes["NOM_EQUIPE_TI"], $header);

		// Parâmetros da pesquisa
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
		// Cabeçalho de resultados
		$header = array();
		if($_SESSION["FLG_CLIENTE_PRIORIZADOR"] == "S"){
			$header[] = array("&nbsp;", "4%");
			$header[] = array("Prior.", "7%");
			$header[] = array("Chamado", "7%");
			$header[] = array("Abertura", "10%");
			$header[] = array("Previsão Término", "10%");
			$header[] = array("Sistema", "26%");
			$header[] = array("Descrição", "36%");
		}else{
			$header[] = array("Prior.", "7%");
			$header[] = array("Chamado", "7%");
			$header[] = array("Abertura", "10%");
			$header[] = array("Previsão Término", "10%");
			$header[] = array("Sistema", "26%");
			$header[] = array("Descrição", "40%");
		}

		$pagina->LinhaHeaderTabelaResultado("Priorização de demanas em atendimento da ".$rowEquipes["NOM_EQUIPE_TI"], $header);

		// Parâmetros da pesquisa
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
