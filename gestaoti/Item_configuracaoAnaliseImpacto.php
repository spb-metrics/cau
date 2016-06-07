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
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.item_configuracao.php';
require 'include/PHP/class/class.servidor.php';
require 'include/PHP/class/class.relacionamento_item_configuracao.php';
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.unidade_organizacional.php';
$pagina = new Pagina();
$pagina1 = new Pagina();
$banco = new item_configuracao();
$item_configuracao = new item_configuracao();
$servidor = new servidor();
$empregados = new empregados();
$unidade_organizacional = new unidade_organizacional();
$pagina->SettituloCabecalho("An�lise de Impacto"); // Indica o t�tulo do cabe�alho da p�gina
// Inicio do formul�rio
$pagina->MontaCabecalho();

print $pagina->CampoHidden("flag", "1");

// Inicio da tabela de par�metros
$pagina->AbreTabelaPadrao("center", "85%");

// Par�metro de pesquisa
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", "");
print $pagina->CampoHidden("v_SEQ_TIPO_ITEM_CONFIGURACAO", "");
$pagina->LinhaCampoFormulario("Ativo de TI:", "right", "S",
			$pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO", "N", "" , "40", "40", $v_NOM_ITEM_CONFIGURACAO, "readonly").
			$pagina->ButtonProcuraItemConfiguracao("v_NOM_ITEM_CONFIGURACAO", "v_SEQ_ITEM_CONFIGURACAO", "v_SEQ_TIPO_ITEM_CONFIGURACAO")
			, "left", "", "30%", "70%");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();
if($flag == "1"){
	$pagina->LinhaVazia(1);
	if($v_SEQ_TIPO_ITEM_CONFIGURACAO == "1"){
		$servidor->select($v_SEQ_ITEM_CONFIGURACAO);
		$banco->GetSistemasAfetadosPorServidor($v_SEQ_ITEM_CONFIGURACAO);
		if($banco->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("center", "", 2);
			$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
		}else{
			$pagina->AbreTabelaPadrao("center", "100%");
			$header = array();
			$header[] = array("Sigla", "10%");
			$header[] = array("Nome", "20%");
			$header[] = array("Tipo de Relac.", "15%");
			$header[] = array("Gestor", "20%");
			$header[] = array("L�der", "20%");
			$header[] = array("�rea", "7%");
			$header[] = array("", "3%");
			$corpo = array();
			$pagina->LinhaHeaderTabelaResultado("Sistemas afetados pela parada do servidor ".$servidor->NOM_SERVIDOR, $header);
			$cont = 0;
			while ($row = pg_fetch_array($banco->database->result)){
				$corpo[] = array("left", "campo", $row["sig_item_configuracao"]);
				$corpo[] = array("left", "campo", $row["nom_item_configuracao"]);
				$corpo[] = array("left", "campo", $row["nom_tipo_relac_item_config"]);
				$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_gestor"]));
				$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_lider"]));
				$corpo[] = array("center", "campo", $unidade_organizacional->GetUorSigla($row["cod_uor_area_gestora"]));

				// Verificar sistemas que possuem interface
				$item_configuracao = new item_configuracao();
				// ----------------------------- 2� n�vel da an�lise -------------------------------------------------------------------
				$item_configuracao->GetSistemasAfetadosPorSistema($row["seq_item_configuracao"]);
				//print $item_configuracao->database->rows;
				if($item_configuracao->database->rows > 0){
					$corpo[] = array("center", "campo", "<a href=\"Item_configuracaoAnaliseImpacto.php?flag=1&v_SEQ_ITEM_CONFIGURACAO=".$row["seq_item_configuracao"]."&v_SEQ_TIPO_ITEM_CONFIGURACAO=2\"><img  alt=\"Clique aqui para ver os sistemas afetados pelo mal funcionamento deste\"  src=\"imagens/exclamacao_mini.gif\" border=0></a>");
				}else{
					$corpo[] = array("center", "campo", "");
				}
				$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"location.href='Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$row["seq_item_configuracao"]."';\"");
				$corpo = "";
					/*
					$tabela = array();
					$header = array();
					$header[] = array("&nbsp;", "center", "3%", "header");
					$header[] = array("Sigla", "center", "15%", "header");
					$header[] = array("Nome", "center", "30%", "header");
					$header[] = array("Tipo de Relac.", "center", "40%", "header");
					//$header[] = array("Gestor", "center", "20%", "header");
					//$header[] = array("L�der", "center", "20%", "header");
					$header[] = array("�rea", "center", "10%", "header");
					$tabela[] = $header;
					$pagina1->LinhaCampoFormularioColspan("center", $pagina1->Tabela($tabela, "90%","",true,"Sistemas afetados pelo mau funcionamento do sistema ".$row["nom_item_configuracao"],"header"), "6");
					while ($rowSistemasAfetados = pg_fetch_array($item_configuracao->database->result)){
						$tabela = array();
						$header = array();
						$header[] = array($pagina->BotaoLupa("Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$rowSistemasAfetados["SEQ_ITEM_CONFIGURACAO"], "Detalhes de ".$rowSistemasAfetados["NOM_ITEM_CONFIGURACAO"]), "left", "3%", "");
						$header[] = array($rowSistemasAfetados["SIG_ITEM_CONFIGURACAO"], "left", "15%", "");
						$header[] = array($rowSistemasAfetados["NOM_ITEM_CONFIGURACAO"], "left", "30%", "");
						$header[] = array($rowSistemasAfetados["NOM_TIPO_RELAC_ITEM_CONFIG"], "left", "40%", "");
						//$header[] = array($empregados->GetNomeEmpregado($rowSistemasAfetados["NUM_MATRICULA_GESTOR"]), "left", "20%", "");
						//$header[] = array($empregados->GetNomeEmpregado($rowSistemasAfetados["NUM_MATRICULA_LIDER"]), "left", "20%", "");
						$header[] = array($unidade_organizacional->GetUorSigla($rowSistemasAfetados["COD_UOR_AREA_GESTORA"]), "center", "10%", "");
						$tabela[] = $header;
						$pagina1->LinhaCampoFormularioColspan("center", $pagina1->Tabela($tabela, "90%","",false,"",""), "6");

						// ----------------------------- 3� n�vel da an�lise -------------------------------------------------------------------
						// Verificar sistemas que possuem interface
						$item_configuracao1 = new item_configuracao();
						$item_configuracao1->GetSistemasAfetadosPorSistema($rowSistemasAfetados["SEQ_ITEM_CONFIGURACAO"]);
						if($item_configuracao1->database->rows > 0){
							$tabela = array();
							$header = array();
							$header[] = array("&nbsp;", "center", "3%", "header");
							$header[] = array("Sigla", "center", "15%", "header");
							$header[] = array("Nome", "center", "30%", "header");
							$header[] = array("Tipo de Relac.", "center", "40%", "header");
							//$header[] = array("Gestor", "center", "20%", "header");
							//$header[] = array("L�der", "center", "20%", "header");
							$header[] = array("�rea", "center", "10%", "header");
							$tabela[] = $header;
							$pagina1->LinhaCampoFormularioColspan("center", $pagina1->Tabela($tabela, "80%","",true,"Sistemas afetados pelo mau funcionamento do sistema ".$rowSistemasAfetados["NOM_ITEM_CONFIGURACAO"],"header"), "6");
							while ($rowSistemasAfetados2 = pg_fetch_array($item_configuracao1->database->result)){
								$tabela = array();
								$header = array();
								$header[] = array($pagina->BotaoLupa("Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$rowSistemasAfetados2["SEQ_ITEM_CONFIGURACAO"], "Detalhes de ".$rowSistemasAfetados2["NOM_ITEM_CONFIGURACAO"]), "left", "3%", "");
								$header[] = array($rowSistemasAfetados2["SIG_ITEM_CONFIGURACAO"], "left", "15%", "");
								$header[] = array($rowSistemasAfetados2["NOM_ITEM_CONFIGURACAO"], "left", "30%", "");
								$header[] = array($rowSistemasAfetados2["NOM_TIPO_RELAC_ITEM_CONFIG"], "left", "40%", "");
								$header[] = array($unidade_organizacional->GetUorSigla($rowSistemasAfetados2["COD_UOR_AREA_GESTORA"]), "center", "10%", "");
								$tabela[] = $header;
								$pagina1->LinhaCampoFormularioColspan("center", $pagina1->Tabela($tabela, "80%","",false,"",""), "6");
							}
						}
					}

					*/

				$cont++;
			}
			$pagina->FechaTabelaPadrao();
		}
	}elseif($v_SEQ_TIPO_ITEM_CONFIGURACAO == "2"){
		$item_configuracao1 = new item_configuracao();
		$item_configuracao1->select($v_SEQ_ITEM_CONFIGURACAO);
		$banco->GetSistemasAfetadosPorSistema($v_SEQ_ITEM_CONFIGURACAO);
		if($banco->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("center", "", 2);
			$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
		}else{
			$pagina->AbreTabelaPadrao("center", "100%");
			$header = array();
			$header[] = array("Sigla", "10%");
			$header[] = array("Nome", "20%");
			$header[] = array("Tipo de Relac.", "15%");
			$header[] = array("Gestor", "20%");
			$header[] = array("L�der", "20%");
			$header[] = array("�rea", "7%");
			$header[] = array("", "3%");
			$corpo = array();
			$pagina->LinhaHeaderTabelaResultado("Sistemas afetados pela parada do sistema ".$item_configuracao1->NOM_ITEM_CONFIGURACAO, $header);
			$cont = 0;
			while ($row = pg_fetch_array($banco->database->result)){
				$corpo[] = array("left", "campo", $row["sig_item_configuracao"]);
				$corpo[] = array("left", "campo", $row["nom_item_configuracao"]);
				$corpo[] = array("left", "campo", $row["nom_tipo_relac_item_config"]);
				$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_gestor"]));
				$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_lider"]));
				$corpo[] = array("center", "campo", $unidade_organizacional->GetUorSigla($row["cod_uor_area_gestora"]));

				// Verificar sistemas que possuem interface
				$item_configuracao = new item_configuracao();
				// ----------------------------- 2� n�vel da an�lise -------------------------------------------------------------------
				$item_configuracao->GetSistemasAfetadosPorSistema($row["seq_item_configuracao"]);
				//print $item_configuracao->database->rows;
				if($item_configuracao->database->rows > 0){
					$corpo[] = array("center", "campo", "<a href=\"Item_configuracaoAnaliseImpacto.php?flag=1&v_SEQ_ITEM_CONFIGURACAO=".$row["seq_item_configuracao"]."&v_SEQ_TIPO_ITEM_CONFIGURACAO=2\"><img alt=\"Clique aqui para ver os sistemas afetados pelo mal funcionamento deste\" src=\"imagens/exclamacao_mini.gif\" border=0></a>");
				}else{
					$corpo[] = array("center", "campo", "");
				}
				$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"location.href='Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$row["seq_item_configuracao"]."';\"");
				$corpo = "";

				/*
					$tabela = array();
					$header = array();
					$header[] = array("&nbsp;", "center", "3%", "header");
					$header[] = array("Sigla", "center", "15%", "header");
					$header[] = array("Nome", "center", "30%", "header");
					$header[] = array("Tipo de Relac.", "center", "40%", "header");
					//$header[] = array("Gestor", "center", "20%", "header");
					//$header[] = array("L�der", "center", "20%", "header");
					$header[] = array("�rea", "center", "10%", "header");
					$tabela[] = $header;
					$pagina1->LinhaCampoFormularioColspan("center", $pagina1->Tabela($tabela, "90%","",true,"Sistemas afetados pelo mau funcionamento do sistema ".$row["NOM_ITEM_CONFIGURACAO"],"header"), "6");
					while ($rowSistemasAfetados = pg_fetch_array($item_configuracao->database->result)){
						$tabela = array();
						$header = array();
						$header[] = array($pagina->BotaoLupa("Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$rowSistemasAfetados["SEQ_ITEM_CONFIGURACAO"], "Detalhes de ".$rowSistemasAfetados["NOM_ITEM_CONFIGURACAO"]), "left", "3%", "");
						$header[] = array($rowSistemasAfetados["SIG_ITEM_CONFIGURACAO"], "left", "15%", "");
						$header[] = array($rowSistemasAfetados["NOM_ITEM_CONFIGURACAO"], "left", "30%", "");
						$header[] = array($rowSistemasAfetados["NOM_TIPO_RELAC_ITEM_CONFIG"], "left", "40%", "");
						//$header[] = array($empregados->GetNomeEmpregado($rowSistemasAfetados["NUM_MATRICULA_GESTOR"]), "left", "20%", "");
						//$header[] = array($empregados->GetNomeEmpregado($rowSistemasAfetados["NUM_MATRICULA_LIDER"]), "left", "20%", "");
						$header[] = array($unidade_organizacional->GetUorSigla($rowSistemasAfetados["COD_UOR_AREA_GESTORA"]), "center", "10%", "");
						$tabela[] = $header;
						$pagina1->LinhaCampoFormularioColspan("center", $pagina1->Tabela($tabela, "90%","",false,"",""), "6");

						// ----------------------------- 3� n�vel da an�lise -------------------------------------------------------------------
						// Verificar sistemas que possuem interface
						$item_configuracao1 = new item_configuracao();
						$item_configuracao1->GetSistemasAfetadosPorSistema($rowSistemasAfetados["SEQ_ITEM_CONFIGURACAO"]);
						if($item_configuracao1->database->rows > 0){
							$tabela = array();
							$header = array();
							$header[] = array("&nbsp;", "center", "3%", "header");
							$header[] = array("Sigla", "center", "15%", "header");
							$header[] = array("Nome", "center", "30%", "header");
							$header[] = array("Tipo de Relac.", "center", "40%", "header");
							//$header[] = array("Gestor", "center", "20%", "header");
							//$header[] = array("L�der", "center", "20%", "header");
							$header[] = array("�rea", "center", "10%", "header");
							$tabela[] = $header;
							$pagina1->LinhaCampoFormularioColspan("center", $pagina1->Tabela($tabela, "80%","",true,"Sistemas afetados pelo mau funcionamento do sistema ".$rowSistemasAfetados["NOM_ITEM_CONFIGURACAO"],"header"), "6");
							while ($rowSistemasAfetados2 = pg_fetch_array($item_configuracao1->database->result)){
								$tabela = array();
								$header = array();
								$header[] = array($pagina->BotaoLupa("Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$rowSistemasAfetados2["SEQ_ITEM_CONFIGURACAO"], "Detalhes de ".$rowSistemasAfetados2["NOM_ITEM_CONFIGURACAO"]), "left", "3%", "");
								$header[] = array($rowSistemasAfetados2["SIG_ITEM_CONFIGURACAO"], "left", "15%", "");
								$header[] = array($rowSistemasAfetados2["NOM_ITEM_CONFIGURACAO"], "left", "30%", "");
								$header[] = array($rowSistemasAfetados2["NOM_TIPO_RELAC_ITEM_CONFIG"], "left", "40%", "");
								$header[] = array($unidade_organizacional->GetUorSigla($rowSistemasAfetados2["COD_UOR_AREA_GESTORA"]), "center", "10%", "");
								$tabela[] = $header;
								$pagina1->LinhaCampoFormularioColspan("center", $pagina1->Tabela($tabela, "80%","",false,"",""), "6");
							}
						}
					}
				}
				*/
				$cont++;
			}
			$pagina->FechaTabelaPadrao();
		}
	}
}
$pagina->MontaRodape();
?>
