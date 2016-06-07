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
require_once 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
$pagina->ForcaAutenticacao();

$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

if($v_SEQ_CHAMADO != ""){
	// ============================================================================================================
	// Realizar o cadastro do chamado
	// ============================================================================================================
	if($flag == "1"){
		// Validar campos
		$camposValidados = 1;

		require_once 'include/PHP/class/class.subtipo_chamado.php';
		require_once 'include/PHP/class/class.tipo_chamado.php';
		$subtipo_chamado = new subtipo_chamado();
		$tipo_chamado = new tipo_chamado();

		$mensagemErro = "";
		if($v_SEQ_TIPO_OCORRENCIA == ""){
			$camposValidados = 0;
			$mensagemErro = $pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA");
		}
		if($v_SEQ_TIPO_CHAMADO == ""){
			$camposValidados = 0;
			$mensagemErro = $pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO");
		}
		if($v_SEQ_SUBTIPO_CHAMADO == ""){
			$camposValidados = 0;
			$mensagemErro .= $pagina->iif($mensagemErro=="",$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO"), ", ".$pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO"));
		}
		if($v_SEQ_ATIVIDADE_CHAMADO == ""){
			$camposValidados = 0;
			$mensagemErro .= $pagina->iif($mensagemErro=="",$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO"), ", ".$pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO"));
		}
		/*
		if($v_SEQ_LOCALIZACAO_FISICA == ""){
			$camposValidados = 0;
			$mensagemErro .= $pagina->iif($mensagemErro=="","Localiza��o", ", Localiza��o");
		}
		*/
		if($v_SEQ_PRIORIDADE_CHAMADO == ""){
			$camposValidados = 0;
			$vErroCampos .= $pagina->iif($mensagemErro=="","Prioridade", ", Prioridade");
		}
		if($v_SEQ_TIPO_CHAMADO == $tipo_chamado->COD_TIPO_SISTEMAS_INFORMACAO){
			if($v_SEQ_ITEM_CONFIGURACAO == ""){
				$camposValidados = 0;
				$mensagemErro .= $pagina->iif($mensagemErro=="","Sistema de informa��o", ", Sistema de informa��o");
			}
		}else{
			$v_SEQ_ITEM_CONFIGURACAO = "";
		}
		if($camposValidados == 1){
			// Alterar chamado
			require_once 'include/PHP/class/class.chamado.php';
			$chamado = new chamado();
			$chamado->setSEQ_ATIVIDADE_CHAMADO($v_SEQ_ATIVIDADE_CHAMADO);
			$chamado->setSEQ_LOCALIZACAO_FISICA($v_SEQ_LOCALIZACAO_FISICA);
			$chamado->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
			$chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
			$chamado->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
			
			$chamado->setOBJETIVO_EVENTO($v_TXT_OBEJTIVO_EVENTO);	 
			 
			 
			//A $v_DATA_RESERVA_EVENTO
			if($v_DATA_RESERVA_EVENTO != null){
				$data = split("/",$v_DATA_RESERVA_EVENTO);
				$d = $data[0];
				$m = $data[1];
				$a = $data[2];
			
				$hora = split(":",$v_HORA_RESERVA_EVENTO);
				$hr = $hora[0];
				$minuto =  $hora[1];			 
				$DTH_RESERVA_EVENTO = mktime($hr,$minuto, 0,$m,$d,$a);
			
				$chamado->setDTH_RESERVA_EVENTO(date("Y-m-d H:i:s",$DTH_RESERVA_EVENTO));
				
			}
			
			
			$chamado->setQUANTIDADE_PESSOAS_EVENTO($v_QUANTIDADE_PESSOAS);
			$chamado->setSERVICOS_EVENTO($v_TXT_SERVICOS); 
			
			if($v_DATA_INICIO_UTILIZACAO_APARELHO != null){
				$data = split("/",$v_DATA_INICIO_UTILIZACAO_APARELHO);
				$d = $data[0];
				$m = $data[1];
				$a = $data[2];			 
				$v_DATA_INICIO_UTILIZACAO_APARELHO = mktime(0,0, 0,$m,$d,$a);
				$chamado->setDT_INICIO_UTILIZACAO_APARELHO(date("Y-m-d H:i:s",$v_DATA_INICIO_UTILIZACAO_APARELHO)); 
			}
			if($v_DATA_FIM_UTILIZACAO_APARELHO != null){
				 
				$data = split("/",$v_DATA_FIM_UTILIZACAO_APARELHO);
				$d = $data[0];
				$m = $data[1];
				$a = $data[2];			 
				$v_DATA_FIM_UTILIZACAO_APARELHO = mktime(0,0, 0,$m,$d,$a);
				
				$chamado->setDT_FIM_UTILIZACAO_APARELHO(date("Y-m-d H:i:s",$v_DATA_FIM_UTILIZACAO_APARELHO));
			}
			
			$chamado->update($v_SEQ_CHAMADO);

			// Deletar patrimonios
			require_once 'include/PHP/class/class.patrimonio_chamado.php';
			$patrimonio_chamado = new patrimonio_chamado();
			$patrimonio_chamado->delete($v_SEQ_CHAMADO);

			// Incluir patrimonios
			if($v_LISTA_PATRIMONIOS != ""){
				$aNUM_PATRIMONIO = split(",", $v_LISTA_PATRIMONIOS);
				for($i=0; $i<count($aNUM_PATRIMONIO);$i++){
					$patrimonio_chamado = new patrimonio_chamado();
					$patrimonio_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$patrimonio_chamado->setNUM_PATRIMONIO($aNUM_PATRIMONIO[$i]);
					$patrimonio_chamado->insert();
				}
			}

			// Replicar altera��es para os chamados filhos
			require_once 'include/PHP/class/class.vinculo_chamado.php';
			$vinculo_chamado = new vinculo_chamado();
			$vinculo_chamado->setSEQ_CHAMADO_MASTER($v_SEQ_CHAMADO);
			$vinculo_chamado->selectParam();
			if($vinculo_chamado->database->rows > 0){
				while ($row = pg_fetch_array($vinculo_chamado->database->result)){
					// Alterar chamamdo
					$chamado = new chamado();
					$chamado->setSEQ_ATIVIDADE_CHAMADO($v_SEQ_ATIVIDADE_CHAMADO);
					$chamado->setSEQ_LOCALIZACAO_FISICA($v_SEQ_LOCALIZACAO_FISICA);
					$chamado->setSEQ_PRIORIDADE_CHAMADO($v_SEQ_PRIORIDADE_CHAMADO);
					$chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
					$chamado->update($row["seq_chamado_filho"]);
				}
			}

			// Incluir anexos
			require_once 'include/PHP/class/class.anexo_chamado.php';
			// Arquivo 1
			if($v_NOM_ARQUIVO_ORIGINAL_1 != ""){
				//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['size']/2048) <= 2048){
					// Inserir registro
					$anexo_chamado = new anexo_chamado();
					$anexo_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name']);
					$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['name'])));
					$anexo_chamado->insert();				
					if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
						$mensagemErro .= "Registro alterado com sucesso, mas ocorreu um erro no momento do upload do arquivo n� 1. ($pagina->vPathUploadArquivosCEA) - ($anexo_chamado->NOM_ARQUIVO_SISTEMA) - (".$_FILES['v_NOM_ARQUIVO_ORIGINAL_1']['tmp_name'].")";
					}
				//}else{
				//	$mensagemErro .= "Registro alterado, mas n�o foi poss�vel carregar a imagem por exceder o tamanho de 2 Mb.";
				//}
			}
			// Arquivo 2
			if($v_NOM_ARQUIVO_ORIGINAL_2 != ""){
				//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['size']/2048) <= 2048){
					// Inserir registro
					$anexo_chamado = new anexo_chamado();
					$anexo_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name']);
					$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['name'])));
					$anexo_chamado->insert();
					if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_2']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
						$mensagemErro .= "Registro Inclu�do com sucesso, mas ocorreu um erro no momento do upload do arquivo n� 2.";
					}
				//}else{
				//	$mensagemErro .= "Registro inclu�do, mas n�o foi poss�vel carregar a imagem por exceder o tamanho de 2 Mb.";
				//}
			}
			// Arquivo 3
			if($v_NOM_ARQUIVO_ORIGINAL_3 != ""){
				//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['size']/2048) <= 2048){
					// Inserir registro
					$anexo_chamado = new anexo_chamado();
					$anexo_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name']);
					$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['name'])));
					$anexo_chamado->insert();
					if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_3']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
						$mensagemErro .= "Registro Inclu�do com sucesso, mas ocorreu um erro no momento do upload do arquivo n� 3.";
					}
				//}else{
				//	$mensagemErro .= "Registro inclu�do, mas n�o foi poss�vel carregar a imagem por exceder o tamanho de 2 Mb.";
				//}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_4 != ""){
				//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['size']/2048) <= 2048){
					// Inserir registro
					$anexo_chamado = new anexo_chamado();
					$anexo_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name']);
					$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['name'])));
					$anexo_chamado->insert();
					if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_4']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
						$mensagemErro .= "Registro Inclu�do com sucesso, mas ocorreu um erro no momento do upload do arquivo n� 4.";
					}
				//}else{
				//	$mensagemErro .= "Registro inclu�do, mas n�o foi poss�vel carregar a imagem por exceder o tamanho de 2 Mb.";
				//}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_5 != ""){
				//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['size']/2048) <= 2048){
					// Inserir registro
					$anexo_chamado = new anexo_chamado();
					$anexo_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name']);
					$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['name'])));
					$anexo_chamado->insert();
					if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_5']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
						$mensagemErro .= "Registro Inclu�do com sucesso, mas ocorreu um erro no momento do upload do arquivo n� 5.";
					}
				//}else{
				//	$mensagemErro .= "Registro inclu�do, mas n�o foi poss�vel carregar a imagem por exceder o tamanho de 2 Mb.";
				//}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_6 != ""){
				//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['size']/2048) <= 2048){
					// Inserir registro
					$anexo_chamado = new anexo_chamado();
					$anexo_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name']);
					$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['name'])));
					$anexo_chamado->insert();
					if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_6']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
						$mensagemErro .= "Registro Inclu�do com sucesso, mas ocorreu um erro no momento do upload do arquivo n� 6.";
					}
				//}else{
				//	$mensagemErro .= "Registro inclu�do, mas n�o foi poss�vel carregar a imagem por exceder o tamanho de 2 Mb.";
				//}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_7 != ""){
				//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['size']/2048) <= 2048){
					// Inserir registro
					$anexo_chamado = new anexo_chamado();
					$anexo_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name']);
					$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['name'])));
					$anexo_chamado->insert();
					if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_7']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
						$mensagemErro .= "Registro Inclu�do com sucesso, mas ocorreu um erro no momento do upload do arquivo n� 7.";
					}
				//}else{
				//	$mensagemErro .= "Registro inclu�do, mas n�o foi poss�vel carregar a imagem por exceder o tamanho de 2 Mb.";
				//}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_8 != ""){
				//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['size']/2048) <= 2048){
					// Inserir registro
					$anexo_chamado = new anexo_chamado();
					$anexo_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name']);
					$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['name'])));
					$anexo_chamado->insert();
					if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_8']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
						$mensagemErro .= "Registro Inclu�do com sucesso, mas ocorreu um erro no momento do upload do arquivo n� 8.";
					}
				//}else{
				//	$mensagemErro .= "Registro inclu�do, mas n�o foi poss�vel carregar a imagem por exceder o tamanho de 2 Mb.";
				//}
			}
			if($v_NOM_ARQUIVO_ORIGINAL_9 != ""){
				//if(($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['size']/2048) <= 2048){
					// Inserir registro
					$anexo_chamado = new anexo_chamado();
					$anexo_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
					$anexo_chamado->setNOM_ARQUIVO_ORIGINAL($_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name']);
					$anexo_chamado->setNUM_MATRICULA($_SESSION["NUM_MATRICULA_RECURSO"]);
					$anexo_chamado->setEXTENCAO_ARQUIVO(end(explode(".", $_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['name'])));
					$anexo_chamado->insert();
					if(!$pagina->MandaArquivo($anexo_chamado->NOM_ARQUIVO_SISTEMA, $_FILES['v_NOM_ARQUIVO_ORIGINAL_9']['tmp_name'], $pagina->vPathUploadArquivosCEA) == 1){
						$mensagemErro .= "Registro Inclu�do com sucesso, mas ocorreu um erro no momento do upload do arquivo n� 9.";
					}
				//}else{
				//	$mensagemErro .= "Registro inclu�do, mas n�o foi poss�vel carregar a imagem por exceder o tamanho de 2 Mb.";
				//}
			}

			// Redirecionar para a p�gina de confirma��o
			$pagina->redirectTo("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO&mensagemErro=$mensagemErro");

		}else{
			$mensagemErro .= "Os seguintes campos s�o obrigat�rios: ".$mensagemErro;
		}
	}
	// ============================================================================================================
	// In�cio da p�gina
	// ============================================================================================================
	require_once 'include/PHP/class/class.chamado.php';
	require_once 'include/PHP/class/class.situacao_chamado.php';
	require_once 'include/PHP/class/class.tipo_chamado.php';
	require_once 'include/PHP/class/class.tipo_ocorrencia.php';
	$tipo_ocorrencia = new tipo_ocorrencia();
	$tipo_chamado = new tipo_chamado();
	$banco = new chamado();
	$situacao_chamado = new situacao_chamado();
	// Verificar se o profissional possui um lan�amento no Time Sheet em aberto para o chamado
	require_once 'include/PHP/class/class.time_sheet.php';
	$time_sheet = new time_sheet();
	$v_FLG_ATENDIMENTO_INICIADO = $time_sheet->VerificarInicioAtividadeChamado($v_SEQ_CHAMADO, $_SESSION["NUM_MATRICULA_RECURSO"]);
	if($v_FLG_ATENDIMENTO_INICIADO != "1"){
		// Redirecionar o profissional para a tela de atendimento
		$pagina->ScriptAlert("Inicie o atendimento do chamado antes de realizar uma a��o.");
		$pagina->redirectToJS("ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO");
	}

	// ============================================================================================================
	// Configura��es AJAX
	// ============================================================================================================
	require_once 'include/PHP/class/class.Sajax.php';
	$Sajax = new Sajax();

	function CarregarComboTipoChamado($v_SEQ_TIPO_OCORRENCIA,$v_SEQ_CENTRAL_ATENDIMENTO){
		if($v_SEQ_TIPO_OCORRENCIA != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.tipo_chamado.php';
			$pagina = new Pagina();
			$tipo_chamado = new tipo_chamado();
			$tipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
			$tipo_chamado->setSEQ_CENTRAL_ATENDIMENTO($v_SEQ_CENTRAL_ATENDIMENTO);
			return $pagina->AjaxFormataArrayCombo($tipo_chamado->combo("DSC_TIPO_CHAMADO"));
		}else{
			return "";
		}
	}

	function CarregarComboSubtipoChamado($v_SEQ_TIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
		if($v_SEQ_TIPO_CHAMADO != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.subtipo_chamado.php';
			$pagina = new Pagina();
			$subtipo_chamado = new subtipo_chamado();
			$subtipo_chamado->setSEQ_TIPO_CHAMADO($v_SEQ_TIPO_CHAMADO);
			$subtipo_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
			//$subtipo_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
			return $pagina->AjaxFormataArrayCombo($subtipo_chamado->combo("DSC_SUBTIPO_CHAMADO"));
		}else{
			return "";
		}
	}

	function CarregarComboAtividade($v_SEQ_SUBTIPO_CHAMADO, $v_SEQ_TIPO_OCORRENCIA){
		if($v_SEQ_SUBTIPO_CHAMADO != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.atividade_chamado.php';
			$pagina = new Pagina();
			$atividade_chamado = new atividade_chamado();
			$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($v_SEQ_SUBTIPO_CHAMADO);
			$atividade_chamado->setSEQ_TIPO_OCORRENCIA($v_SEQ_TIPO_OCORRENCIA);
			//$atividade_chamado->setFLG_ATENDIMENTO_EXTERNO("N");
			return $pagina->AjaxFormataArrayCombo($atividade_chamado->combo("DSC_ATIVIDADE_CHAMADO"));
		}else{
			return "";
		}
	}

	function CarregarComboEdificacao($v_COD_DEPENDENCIA){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.edificacao.php';
		$pagina = new Pagina();
		$edificacao = new edificacao();
		$edificacao->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
		return $pagina->AjaxFormataArrayCombo($edificacao->comboSimples("NOM_EDIFICACAO"));
	}

	function CarregarComboLocalFisico($v_SEQ_EDIFICACAO){
		if($v_SEQ_EDIFICACAO != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.localizacao_fisica.php';
			$pagina = new Pagina();
			$localizacao_fisica = new localizacao_fisica();
			$localizacao_fisica->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
			return $pagina->AjaxFormataArrayCombo($localizacao_fisica->combo("NOM_LOCALIZACAO_FISICA"));
		}else{
			return "";
		}
	}

	function ValidarPessoaContato($v_NUM_MATRICULA_CONTATO){
		if($v_NUM_MATRICULA_CONTATO != ""){
			require_once 'include/PHP/class/class.pagina.php';
			require_once 'include/PHP/class/class.empregados.oracle.php';
			$pagina = new Pagina();
			$empregados = new empregados();
			$primeiraLetra = substr(strtoupper($v_NUM_MATRICULA_CONTATO), 0, 1);
			if(!is_numeric($primeiraLetra)){
				$v_NUM_MATRICULA_CONTATO = $empregados->GetNumeroMatricula($v_NUM_MATRICULA_CONTATO);
			}
			$empregados->select($v_NUM_MATRICULA_CONTATO);
			if($empregados->NOME != ""){
				return $v_NUM_MATRICULA_CONTATO."|".$empregados->NOME."|".$empregados->NUM_DDD."-".$empregados->NUM_VOIP;
			}else{
				return "";
			}
		}else{
			return "";
		}
	}

	function ValidarPatrimonio($v_NUM_PATRIMONIO){
		require_once 'include/PHP/class/class.patrimonio_ti.ativos.php';
		$ativos = new ativos();
		$ativos->select($v_NUM_PATRIMONIO);
		if($ativos->NUM_PATRIMONIO != ""){
			return $ativos->NOM_BEM."|".$ativos->NOM_MODELO."|".$ativos->DSC_LOCALIZACAO."|".$v_NUM_PATRIMONIO;
		}else{
			return "";
		}
	}

	function CarregarComboSistemaInformacao(){
		require_once 'include/PHP/class/class.pagina.php';
		require_once 'include/PHP/class/class.item_configuracao.php';
		$pagina = new Pagina();
		$item_configuracao = new item_configuracao();
		//$item_configuracao->setNUM_MATRICULA_GESTOR($_SESSION["NUM_MATRICULA_RECURSO"]);
		return $pagina->AjaxFormataArrayCombo($item_configuracao->combo("SIG_ITEM_CONFIGURACAO"));
	}

	function BuscarInfoAtividade($v_SEQ_ATIVIDADE_CHAMADO){
		if($v_SEQ_ATIVIDADE_CHAMADO == ""){
			return "Selecione a atividade";
		}else{
			require_once 'include/PHP/class/class.atividade_chamado.php';
			require_once 'include/PHP/class/class.tipo_ocorrencia.php';

			$atividade_chamado = new atividade_chamado();
			$tipo_ocorrencia = new tipo_ocorrencia();
			$atividade_chamado->select($v_SEQ_ATIVIDADE_CHAMADO);
			$vInfoAtividade = "";
			if($atividade_chamado->QTD_MIN_SLA_ATENDIMENTO == ""){
				$vInfoAtividade = "Medi��o de tempo: Horas Planejadas. ";
			}else{
				if($atividade_chamado->FLG_FORMA_MEDICAO_TEMPO == "C"){
					$vInfoAtividade = "Medi��o de tempo: Horas Corridas. ";
				}else{
					$vInfoAtividade = "Medi��o de tempo: Horas �teis. ";
				}
				if($tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE == $atividade_chamado->SEQ_TIPO_OCORRENCIA){
					$vInfoAtividade .= "Tempo de Contingenciamento M�ximo: ".$atividade_chamado->QTD_MIN_SLA_ATENDIMENTO." min. Solu��o Definitiva: ".$atividade_chamado->QTD_MIN_SLA_SOLUCAO_FINAL." min. ";
				}else{
					$vInfoAtividade .= "Tempo de Atendimento M�ximo: ".$atividade_chamado->QTD_MIN_SLA_ATENDIMENTO." min. ";
				}
			}

			if($atividade_chamado->FLG_ATENDIMENTO_EXTERNO == "S"){
				$vInfoAtividade .= "Atendimento Externo.";
			}else{
				$vInfoAtividade .= "Atividade Interna.";
			}

			return rawurlencode($vInfoAtividade);
		}
	}

	$Sajax->sajax_init();
	$Sajax->sajax_debug_mode = 0;
	$Sajax->sajax_export("CarregarComboSubtipoChamado", "CarregarComboAtividade", "CarregarComboEdificacao", "CarregarComboLocalFisico", "ValidarPessoaContato", "ValidarPatrimonio", "CarregarComboTipoChamado", "CarregarComboSistemaInformacao", "BuscarInfoAtividade");
	$Sajax->sajax_handle_client_request();

	// ============================================================================================================
	// Configura��o da p�g�na
	// ============================================================================================================
	$pagina->SettituloCabecalho("Alterar Chamado"); // Indica o t�tulo do cabe�alho da p�gina
	$pagina->cea = 1;
	$pagina->method = "post";

	require_once 'include/PHP/class/class.chamado.php';
	$banco = new chamado();
	$banco->select($v_SEQ_CHAMADO);

	require_once 'include/PHP/class/class.situacao_chamado.php';
	$situacao_chamado = new situacao_chamado();

	require_once 'include/PHP/class/class.tipo_ocorrencia.php';
	$tipo_ocorrencia = new tipo_ocorrencia();

	$aItemAba = Array();
	$aItemAba[] = array("#", "", "Detalhes", "onclick=\"AcessarAcao('ChamadoAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "tabact", "Alterar", "onclick=\"AcessarAcao('ChamadoAlterar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Atribuir", "onclick=\"AcessarAcao('ChamadoAtribuir.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Atendimento", "onclick=\"AcessarAcao('ChamadoRegistroAtendimento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	$aItemAba[] = array("#", "", "Suspender", "onclick=\"AcessarAcao('ChamadoSuspender.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Cancelar", "onclick=\"AcessarAcao('ChamadoCancelar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");
	$aItemAba[] = array("#", "", "Devolver 1� n�vel", "onclick=\"AcessarAcao('ChamadoDevolver1Nivel.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO')\"");

	// Se for poss�vel realizar o contigenciamento do chamado
	if($banco->SEQ_TIPO_OCORRENCIA == $tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
		if($banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Aguardando_Atendimento || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Em_Andamento || $banco->SEQ_SITUACAO_CHAMADO == $situacao_chamado->COD_Suspenca ){
			$aItemAba[] = array("#", "", "Contingenciar", "onclick=\"AcessarAcao('ChamadoContingenciar.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
		}
		$aItemAba[] = array("#", "", "Vincular", "onclick=\"AcessarAcao('ChamadoVincular.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	}

	// Adicionar a aba Encerrar caso tenha o prazo do chamado definido
	if($banco->DTH_ENCERRAMENTO_PREVISAO != ""){
		$aItemAba[] = array("#", "", "Encerrar", "onclick=\"AcessarAcao('ChamadoEncerramento.php?v_SEQ_CHAMADO=$v_SEQ_CHAMADO');\"");
	}

	$pagina->SetaItemAba($aItemAba);
	//$pagina->estiloTabBar = "tabbarMenor";
	$pagina->flagScriptCalendario = 0;

	$pagina->MontaCabecalho(1);

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $banco->SEQ_CHAMADO);
	print $pagina->CampoHidden("SEQ_CENTRAL_ATENDIMENTO", $_SEQ_CENTRAL_ATENDIMENTO);

	// ============================================================================================================
	// Configura��es AJAX JAVASCRIPTS
	// ============================================================================================================
	?>
	<script language="javascript">
		<?
		$Sajax->sajax_show_javascript();
		?>
		// Chamada
		function do_CarregarComboTipoChamado() {
			x_CarregarComboTipoChamado(document.form.v_SEQ_TIPO_OCORRENCIA.value,document.form.SEQ_CENTRAL_ATENDIMENTO.value, retorno_CarregarComboTipoChamado);
		}
		// Retorno
		function retorno_CarregarComboTipoChamado(val) {
			fEncheComboBox(val, document.form.v_SEQ_TIPO_CHAMADO);
			do_CarregarComboSubtipoChamado();
		}

		// Chamada
		function do_CarregarComboSubtipoChamado() {
			if(document.form.v_SEQ_TIPO_CHAMADO.value == "<?=$tipo_chamado->COD_TIPO_SISTEMAS_INFORMACAO?>"){
				document.getElementById("combo_sistema").style.display = "block";
				do_CarregarComboSistemaInformacao();
			}else{
				document.getElementById("combo_sistema").style.display = "none";
			}
			x_CarregarComboSubtipoChamado(document.form.v_SEQ_TIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboSubtipoChamado);
		}
		// Retorno
		function retorno_CarregarComboSubtipoChamado(val) {
			fEncheComboBox(val, document.form.v_SEQ_SUBTIPO_CHAMADO);
			do_CarregarComboAtividade();
		}
		// Chamada
		function do_CarregarComboAtividade() {
			x_CarregarComboAtividade(document.form.v_SEQ_SUBTIPO_CHAMADO.value, document.form.v_SEQ_TIPO_OCORRENCIA.value, retorno_CarregarComboAtividade);
		}
		// Retorno
		function retorno_CarregarComboAtividade(val) {
			fEncheComboBox(val, document.form.v_SEQ_ATIVIDADE_CHAMADO);
		}

		// Chamada
		function do_BuscarInfoAtividade() {
			x_BuscarInfoAtividade(document.form.v_SEQ_ATIVIDADE_CHAMADO.value, retorno_BuscarInfoAtividade);
		}
		// Retorno
		function retorno_BuscarInfoAtividade(val) {
			document.getElementById('info_atividade').innerHTML = url_decode(val);
		}

		// Chamada
		function do_CarregarComboEdificacao() {
			x_CarregarComboEdificacao(document.form.v_COD_DEPENDENCIA.value, retorno_CarregarComboEdificacao);
		}
		// Retorno
		function retorno_CarregarComboEdificacao(val) {
			fEncheComboBox(val, document.form.v_SEQ_EDIFICACAO);
		}
		// Chamada
		function do_CarregarComboLocalFisico() {
			x_CarregarComboLocalFisico(document.form.v_SEQ_EDIFICACAO.value, retorno_CarregarComboLocalFisico);
		}
		// Retorno
		function retorno_CarregarComboLocalFisico(val) {
			fEncheComboBox(val, document.form.v_SEQ_LOCALIZACAO_FISICA);
		}
		// Chamada
		function do_ValidarPessoaContato() {
			if(document.form.v_NUM_MATRICULA_CONTATO.value != ""){
				window.dados_pessoa_contato.innerHTML = "carregando....";
				v_NUM_MATRICULA_CONTATO = document.form.v_NUM_MATRICULA_CONTATO.value.replace(/A-Z/i, '');
				v_NUM_MATRICULA_CONTATO = v_NUM_MATRICULA_CONTATO.replace( /[^0-9\.]/, '' );
				x_ValidarPessoaContato(v_NUM_MATRICULA_CONTATO, retorno_ValidarPessoaContato);
			}
		}
		// Retorno
		function retorno_ValidarPessoaContato(val) {
			// Separar os valores retornados
			if(val != ""){
				//  $v_NUM_MATRICULA_CONTATO."|".$empregados->NOME."|".$empregados->NUM_DDD."-".$empregados->NUM_VOIP;
				v_NUM_MATRICULA_CONTATO = val.substr(0, val.indexOf("|"));
				StringRestante = val.substr(val.indexOf("|")+1, val.length);
				v_NOME = StringRestante.substr(0, StringRestante.indexOf("|"));
				StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
				v_TELEFONE = StringRestante;
				// Adicionar resultado ao formul�rio
				document.form.v_NUM_MATRICULA_CONTATO_REAL.value = v_NUM_MATRICULA_CONTATO;
				window.dados_pessoa_contato.innerHTML = "Nome: <b>"+v_NOME+"</b> - Ramal: <b>"+v_TELEFONE+"</b>";
			}else{
				alert("Pessoa n�o encontrada. Clique na imagem de lupa para efetuar uma pesquisa.");
				window.dados_pessoa_contato.innerHTML = "Preencha este campo caso o atendimento n�o seja direcionado a pessoa autenticada no sistema.";
				document.form.v_NUM_MATRICULA_CONTATO.value = "";
			}
		}
		// Chamada
		function do_ValidarPatrimonio(){
			if(document.form.v_NUM_PATRIMONIO.value != ""){
				if(!VerificarExistenciaValorCombo(document.form.v_PATRIMONIOS, document.form.v_NUM_PATRIMONIO.value)){
					document.getElementById("dados_patrimonio").innerHTML = "carregando....";
					x_ValidarPatrimonio(document.form.v_NUM_PATRIMONIO.value, retorno_ValidarPatrimonio);
				}else{
					alert("Patrim�nio j� adionado ao chamado");
				}
			}
		}
		// Retorno
		function retorno_ValidarPatrimonio(val) {
			// Separar os valores retornados
			document.getElementById("dados_patrimonio").innerHTML = "Preencha este campo com o n�mero existente na plaqueta de patrim�nio.";
			if(val != ""){
				//  $ativos->NOM_BEM."|".$ativos->NOM_MODELO."|".$ativos->NOM_DETENTOR
				v_NOM_BEM = val.substr(0, val.indexOf("|"));
				StringRestante = val.substr(val.indexOf("|")+1, val.length);
				v_NOM_MODELO = StringRestante.substr(0, StringRestante.indexOf("|"));
				StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
				v_NOM_DETENTOR = StringRestante.substr(0, StringRestante.indexOf("|"));
				StringRestante = StringRestante.substr(StringRestante.indexOf("|")+1, StringRestante.length);
				v_NUM_PATRIMONIO = StringRestante;
				// Adicionar resultado ao formul�rio
				document.getElementById("comboPatrimonio").style.display = "block";
				ValorCombo = v_NUM_PATRIMONIO+" - "+v_NOM_BEM+" - Local: "+v_NOM_DETENTOR;
				fAdicionaValorCombo(v_NUM_PATRIMONIO, ValorCombo, document.form.v_PATRIMONIOS);
				document.form.v_NUM_PATRIMONIO.value = "";
			}else{
				alert("Patrim�nio n�o encontrado.");
				document.getElementById("v_NUM_PATRIMONIO").value = "";
			}
		}
		// Chamada
		function do_CarregarComboSistemaInformacao() {
			x_CarregarComboSistemaInformacao(retorno_CarregarComboSistemaInformacao);
		}
		// Retorno
		function retorno_CarregarComboSistemaInformacao(val) {
			fEncheComboBox(val, document.form.v_SEQ_ITEM_CONFIGURACAO);
			<?
			if($banco->SEQ_ITEM_CONFIGURACAO != ""){
				?>
				document.form.v_SEQ_ITEM_CONFIGURACAO.value = "<?=$banco->SEQ_ITEM_CONFIGURACAO?>";
				<?
			}
			?>
		}
		// ==================================================== FIM AJAX =====================================

		// =======================================================================
		// Controlar a sa�da �s a��es do chamado
		// =======================================================================
		function AcessarAcao(vDestino){
			validarSaida = false;
			window.location.href = vDestino;
		}

		function ExcluirPatrimonio(){
			retorno = fExcluirValorCombo(document.form.v_PATRIMONIOS);
			if(document.form.v_PATRIMONIOS.options.length == 0){
				document.getElementById("comboPatrimonio").style.display = "none";
			}
		}

		function AnexaNovoArquivo($ID){
			if(document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).value != ""){
				document.getElementById("Newfile"+$ID).style.display = "none";
				$novo = $ID + 1;
				document.getElementById("file"+$novo).style.display = "block";
				document.getElementById("Newfile"+$novo).style.display = "block";
			}else{
				alert("� necess�rio anexar um arquivo antes de adionar um novo.");
				document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).focus();
			}

		}

		function ExcluirArquivo($ID){
			document.getElementById("v_NOM_ARQUIVO_ORIGINAL_"+$ID).value = "";
			document.getElementById("file"+$ID).style.display = "none";
			document.getElementById("Newfile"+$ID).style.display = "none";
		}

		function fValidaFormLocal(){
			// Verificar se a pessoa de contato foi validada
			//if(document.form.v_NUM_MATRICULA_CONTATO.value != "" &&  document.form.v_NUM_MATRICULA_CONTATO_REAL.value == ""){
			//	do_ValidarPessoaContato();
			//}

			// Validar campos
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
				document.form.v_SEQ_TIPO_CHAMADO.focus();
				return false;
			}
			if(document.form.v_SEQ_ATIVIDADE_CHAMADO.value == ""){
				alert("Preencha o campo Atividade");
				document.form.v_SEQ_TIPO_CHAMADO.focus();
				return false;
			}
			/*
			if(document.form.v_SEQ_LOCALIZACAO_FISICA.value == ""){
				alert("Preencha o campo Localiza��o");
				document.form.v_SEQ_TIPO_CHAMADO.focus();
				return false;
			}
			*/
			if(document.form.v_SEQ_PRIORIDADE_CHAMADO.value == ""){
				alert("Preencha o campo Prioridade");
				document.form.v_SEQ_PRIORIDADE_CHAMADO.focus();
				return false;
			}

			// Selecionar todos os patrim�nios

			vPatrimonios = "";
			for (i = 0; i < document.form.v_PATRIMONIOS.options.length; i++){
				vPatrimonios = vPatrimonios + document.form.v_PATRIMONIOS.options[i].value;
				if(i!=document.form.v_PATRIMONIOS.options.length-1){
					vPatrimonios = vPatrimonios+",";
				}
			 }
			 document.form.v_LISTA_PATRIMONIOS.value=vPatrimonios;

			return true;
		}

		// =======================================================================
		// Controle de Sa�da da P�gina
		// =======================================================================
		// Gest�o de Eventos
		// Cross browser event handling for IE 5+, NS6+ and Gecko
		function addEvent(elm, evType, fn, useCapture){
			if (elm.addEventListener){
				// Gecko
				elm.addEventListener(evType, fn, useCapture);
				return true;
			}
			else if (elm.attachEvent){
				// Internet Explorer
				var r = elm.attachEvent('on' + evType, fn);
				return r;
			}else{
				// nutscrape?
				elm['on' + evType] = fn;
			}
		}

		function removeEvent(elm, evType, fn, useCapture){
            if (elm.removeEventListener) {
                // Gecko
                elm.removeEventListener(evType, fn, useCapture);
                return true;
            }
            else
                if (elm.attachEvent) {
                    // Internet Explorer
                    var r = elm.detachEvent('on' + evType, fn);
                    return r;
                }
                else {
                    // FF, NS etc..
                    elm['on' + evType] = '';
                }
        }

		// Add Listeners
		function addListeners(e){
			// Before unload listener
			addEvent(window, 'beforeunload', exitAlert, false);
		}
		// Flag de valida��o da sa�da do fomul�rio
		var validarSaida = true;
		// Exit Alert
		function exitAlert(e){
			//alert("Exit = "+validarSaida);
			if(validarSaida) {
				// default warning message
				var msg = "Tem certeza que deseja sair da tela de atendimento antes de parar o atendimento do chamado?";

				// set event
				if (!e) { e = window.event; }
				if (e) { e.returnValue = msg; }
				// return warning message
				return msg;
			}
		}

		// Initialise
		addEvent(window, 'load', addListeners, false);
	</script>
	<style>
		#combo_multiple {
			font-family: Verdana;
			width: 615px;
			size: 3;
			font-size: 10px;
			color: #000000;
			border-color: #000000;
			border-style: double;
			border-width: 1px;
			background-color: #f6f5f5;
	}
	#CampoSelect {
		font-family: Verdana;
		width: 400px;
		font-size: 10px;
		color: #000000;
		border-color: #000000;
		border-style: double;
		border-width: 1px;
		background-color: #f6f5f5;
	}
	</style>
	<?
	if($mensagemErro != ""){
		$pagina->ScriptAlert($mensagemErro);
	}

	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Dados do chamado", 2);
	// ============================================================================================================
	// Dados do chamado
	// ============================================================================================================
	$pagina->LinhaCampoFormulario("N�mero:", "right", "S", $banco->SEQ_CHAMADO, "left", "id=".$pagina->GetIdTable(), "20%");

	// Montar a combo da tabela tipo_chamado
	$tipo_ocorrencia->FLG_EXIBE_IMPROCEDENTE = 1;
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA").":", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_OCORRENCIA", "S", "Classe", "S", $tipo_ocorrencia->combo(1, $banco->SEQ_TIPO_OCORRENCIA), "Escolha", "do_CarregarComboTipoChamado()", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");

	// Montar a combo da tabela subtipo_chamado
	require_once 'include/PHP/class/class.subtipo_chamado.php';
	$subtipo_chamado = new subtipo_chamado();
	$subtipo_chamado->select($banco->SEQ_SUBTIPO_CHAMADO);
	$subtipo_chamado->SEQ_CENTRAL_ATENDIMENTO =  $_SEQ_CENTRAL_ATENDIMENTO;

	// Montar a combo da tabela tipo_chamado
	require_once 'include/PHP/class/class.tipo_chamado.php';
	$tipo_chamado = new tipo_chamado();
	$tipo_chamado->setSEQ_TIPO_OCORRENCIA($banco->SEQ_TIPO_OCORRENCIA);
	$tipo_chamado->SEQ_CENTRAL_ATENDIMENTO =  $_SEQ_CENTRAL_ATENDIMENTO;
//	$tipo_chamado->setFLG_ATENDIMENTO_EXTERNO("S");
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_TIPO_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_TIPO_CHAMADO", "S", "Tipo de Chamado", "S", $tipo_chamado->combo(2, $subtipo_chamado->SEQ_TIPO_CHAMADO), "Escolha", "do_CarregarComboSubtipoChamado()", "CampoSelect"), "left", "id=".$pagina->GetIdTable(), "20%");

	// Montar a combo da tabela subtipo_chamado
	$subtipo_chamado = new subtipo_chamado();
	$subtipo_chamado->setSEQ_TIPO_OCORRENCIA($banco->SEQ_TIPO_OCORRENCIA);
	$subtipo_chamado->setSEQ_TIPO_CHAMADO($subtipo_chamado->SEQ_TIPO_CHAMADO);
	$subtipo_chamado->SEQ_CENTRAL_ATENDIMENTO =  $_SEQ_CENTRAL_ATENDIMENTO;
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_SUBTIPO_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_SUBTIPO_CHAMADO", "S", "Subtipo de Chamado", "N", $subtipo_chamado->combo("DSC_SUBTIPO_CHAMADO", $banco->SEQ_SUBTIPO_CHAMADO), "Escolha", "do_CarregarComboAtividade()", "CampoSelect"), "left", "id=".$pagina->GetIdTable());

	// Montar a combo da tabela atividade
	require_once 'include/PHP/class/class.atividade_chamado.php';
	$atividade_chamado = new atividade_chamado();
	$atividade_chamado->setSEQ_TIPO_OCORRENCIA($banco->SEQ_TIPO_OCORRENCIA);
	$atividade_chamado->setSEQ_SUBTIPO_CHAMADO($banco->SEQ_SUBTIPO_CHAMADO);
	$atividade_chamado->SEQ_CENTRAL_ATENDIMENTO =  $_SEQ_CENTRAL_ATENDIMENTO;
	$pagina->LinhaCampoFormulario($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO").":", "right", "S", $pagina->CampoSelect("v_SEQ_ATIVIDADE_CHAMADO", "S", "Atividade", "N", $atividade_chamado->combo("DSC_ATIVIDADE_CHAMADO", $banco->SEQ_ATIVIDADE_CHAMADO), "Escolha", "do_BuscarInfoAtividade()", "CampoSelect"), "left", "id=".$pagina->GetIdTable());

	$pagina->LinhaCampoFormulario("Informa��es sobre a atividade:", "right", "N","<span id=\"info_atividade\">Selecione a atividade</span>", "left", "id=".$pagina->GetIdTable());

	// Desci��o do chamado
	$pagina->LinhaCampoFormulario("Solicita��o:", "right", "N", $banco->TXT_CHAMADO, "left", "id=".$pagina->GetIdTable());

	
	//SOLICIACAO DE CELULAR
	if($banco->DT_INICIO_UTILIZACAO_APARELHO != "" && $banco->DT_INICIO_UTILIZACAO_APARELHO != null){
		//FORMATAR DATA
		$array_teste = split(" ",$banco->DT_INICIO_UTILIZACAO_APARELHO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		 
		
		$DT_INICIO_UTILIZACAO_APARELHO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		//FORMATAR DATA
		$array_teste = split(" ",$banco->DT_FIM_UTILIZACAO_APARELHO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		
		$DT_FIM_UTILIZACAO_APARELHO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		 $pagina->LinhaCampoFormulario("Per�dodo de Utiliza��o do Aparelho:", "right", "S",
				$pagina->CampoData("v_DATA_INICIO_UTILIZACAO_APARELHO", "N", "Data de in�cio de Utiliza��o do Aparelho", date("d/m/Y",$DT_INICIO_UTILIZACAO_APARELHO) ,"")
				." ". $pagina->CampoData("v_DATA_FIM_UTILIZACAO_APARELHO", "N", "Data fim de Utiliza��o do Aparelho", date("d/m/Y",$DT_FIM_UTILIZACAO_APARELHO),"")			 
				, "left", "id=".$pagina->GetIdTable() );
	}
	
	//SOLICIACAO DE AUDIORIO
	if($banco->QUANTIDADE_PESSOAS_EVENTO != "" && $banco->QUANTIDADE_PESSOAS_EVENTO != null){
		
		$pagina->LinhaCampoFormulario("Objetivo do Evento:", "right", "S",
									  $pagina->CampoTextArea("v_TXT_OBEJTIVO_EVENTO", "S", "Objetivo do Evento", "99", "3",$banco->OBJETIVO_EVENTO, "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable() );
		 
		
		//FORMATAR DATA
		$array_teste = split(" ",$banco->DTH_RESERVA_EVENTO);
		$data_array_teste = split("-",$array_teste[0]);
		$hora_array_teste = split(":",$array_teste[1]);
		
		$dia_teste = $data_array_teste[2];
		$mes_teste = $data_array_teste[1];
		$ano_teste = $data_array_teste[0];
		
		//$hora_array_teste = split(":",$dataHoraAtual);
		$h_teste = $hora_array_teste[0];
		$min_teste =  $hora_array_teste[1];
		  
		 
		$DTH_RESERVA_EVENTO = mktime($h_teste,$min_teste, 0,$mes_teste,$dia_teste,$ano_teste);
		
		
		$v_HORA_RESERVA_EVENTO = date("H:i",$DTH_RESERVA_EVENTO);
		$v_DATA_RESERVA_EVENTO = date("d/m/Y",$DTH_RESERVA_EVENTO);
		
		$pagina->LinhaCampoFormulario("Data/Hora Reserva:", "right", "S",
				$pagina->CampoData("v_DATA_RESERVA_EVENTO", "N", "Data de execu��o ", $v_DATA_RESERVA_EVENTO,"")
				." Hora ". $pagina->CampoHora("v_HORA_RESERVA_EVENTO", "N", "Hora de execu��o ", $v_HORA_RESERVA_EVENTO,"")			 
				, "left", "id=".$pagina->GetIdTable(),"0%","80%");
				
		$pagina->LinhaCampoFormulario("Quantidade de Pessoas:", "right", "S", $pagina->CampoInt("v_QUANTIDADE_PESSOAS", "S", "Quantidade de Pessoas", "10", $banco->QUANTIDADE_PESSOAS_EVENTO, ""),
		 "left", "id=".$pagina->GetIdTable() );
	
									  
		$pagina->LinhaCampoFormulario("Servi�os:", "right", "S",
									  $pagina->CampoTextArea("v_TXT_SERVICOS", "S", "Servi�os", "99", "3", $banco->SERVICOS_EVENTO, "onkeyup=\"ContaCaracteres(500, this, document.getElementById('conta_caracteres'))\"").
									  "<br><span id=\"conta_caracteres\">500</span> Caracteres restantes"
									  , "left", "id=".$pagina->GetIdTable() );
	}
	// Localiza��o
//	require 'include/PHP/class/class.dependencias.php';
//	$dependencias = new dependencias();
	if($banco->SEQ_LOCALIZACAO_FISICA != ""){
		require_once 'include/PHP/class/class.localizacao_fisica.php';
		$localizacao_fisica = new localizacao_fisica();
		$localizacao_fisica->select($banco->SEQ_LOCALIZACAO_FISICA);
		$v_SEQ_EDIFICACAO = $localizacao_fisica->SEQ_EDIFICACAO;

		require_once 'include/PHP/class/class.edificacao.php';
		$edificacao = new edificacao();
		$edificacao->select($v_SEQ_EDIFICACAO);
//		$v_COD_DEPENDENCIA = $edificacao->COD_DEPENDENCIA;

		$aItemOptionEdificacao = Array();
		$edificacao = new edificacao();
//		$edificacao->setCOD_DEPENDENCIA($v_SEQ_EDIFICACAO);
		$aItemOptionEdificacao = $edificacao->combo("NOM_EDIFICACAO", $v_SEQ_EDIFICACAO);

		$localizacao_fisica = new localizacao_fisica();
		$localizacao_fisica->setSEQ_EDIFICACAO($v_SEQ_EDIFICACAO);
		$aItemOptionLocal = $localizacao_fisica->combo("NOM_LOCALIZACAO_FISICA", $banco->SEQ_LOCALIZACAO_FISICA);
	}else{
//		$aItemOptionEdificacao = Array();
//		$aItemOptionEdificacao[] = array("", "", "Selecione a depend�ncia");

		$aItemOptionLocal = Array();
		$aItemOptionLocal[] = array("", "", "Selecione a edifica��o");
	}

	$pagina->LinhaCampoFormulario("Localiza��o:", "right", "N",
//					$pagina->CampoSelect("v_COD_DEPENDENCIA", "S", "Depend�ncia", "S", $dependencias->comboSimples(2, $v_COD_DEPENDENCIA), "Escolha", "do_CarregarComboEdificacao()")." ".
					$pagina->CampoSelect("v_SEQ_EDIFICACAO", "S", "Edifica��o", "S", $aItemOptionEdificacao, "Escolha", "do_CarregarComboLocalFisico()")." ".
					$pagina->CampoSelect("v_SEQ_LOCALIZACAO_FISICA", "S", "Localiza��o F�sica", "N", $aItemOptionLocal)
					, "left", "id=".$pagina->GetIdTable());

	// Prioridade
	// Montar a combo da tabela atividade
	require_once 'include/PHP/class/class.prioridade_chamado.php';
	$prioridade_chamado = new prioridade_chamado();
	$pagina->LinhaCampoFormulario("Prioridade:", "right", "S", $pagina->CampoSelect("v_SEQ_PRIORIDADE_CHAMADO", "S", "Prioridade", "N", $prioridade_chamado->combo("DSC_PRIORIDADE_CHAMADO", $banco->SEQ_PRIORIDADE_CHAMADO)), "left", "id=".$pagina->GetIdTable());


	// Contato
	/*
	print $pagina->CampoHidden("v_NUM_MATRICULA_CONTATO_REAL", "");
	$pagina->LinhaCampoFormulario("Mat. pessoa de contato:", "right", "N",
									  $pagina->CampoTexto("v_NUM_MATRICULA_CONTATO", "N", "Matr�cula da pessoa de contato" , "10", "10", $banco->NUM_MATRICULA_CONTATO, "onBlur=\"do_ValidarPessoaContato()\"").
									  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_CONTATO").
									  "&nbsp;
									  <span id=\"dados_pessoa_contato\">
									  	Preencha este campo caso o atendimento n�o seja direcionado a pessoa autenticada no sistema.
									  </span>
									  "
									  , "left", "id=".$pagina->GetIdTable());
	*/
	// Patrom�nios
	// Verificar patrim�nios j� cadastrados
print $pagina->CampoHidden("v_LISTA_PATRIMONIOS", "");
if($pagina->flg_usar_funcionalidades_patrimonio == "S"){
	require_once 'include/PHP/class/class.patrimonio_chamado.php';
	$patrimonio_chamado = new patrimonio_chamado();
	$patrimonio_chamado->setSEQ_CHAMADO($banco->SEQ_CHAMADO);
	$patrimonio_chamado->selectParam();
	if($patrimonio_chamado->database->rows > 0){
		$vComboPatrimonios = "<br><span id=\"dados_patrimonio\" style=\"display: none\">
							  	Preencha este campo com o n�mero existente na plaqueta de patrim�nio.
							  </span>
							  <span id=\"comboPatrimonio\">
								<select id=\"combo_multiple\" name=\"v_PATRIMONIOS\" multiple> ";
		require_once 'include/PHP/class/class.patrimonio_ti.ativos.php';
		while ($row = pg_fetch_array($patrimonio_chamado->database->result)){
			//ValorCombo = v_NUM_PATRIMONIO+" - "+v_NOM_BEM+" - Detentor: "+v_NOM_DETENTOR;
			$ativos = new ativos();
			$ativos->select($row["num_patrimonio"]);
			$vComboPatrimonios .= "<option value=\"".$row["num_patrimonio"]."\">".$row["num_patrimonio"]." - ".$ativos->NOM_BEM." - Local: ".$ativos->DSC_LOCALIZACAO."</option>";
		}
	}else{
		$vComboPatrimonios = "<span id=\"dados_patrimonio\">
							  	Preencha este campo com o n�mero existente na plaqueta de patrim�nio.
							  </span>
							  <span id=\"comboPatrimonio\" style=\"display: none\">
							  <select id=\"combo_multiple\" name=\"v_PATRIMONIOS\" multiple> ";
	}
	$vComboPatrimonios .= "</select>
								<div align=right>".$pagina->CampoButton("ExcluirPatrimonio()", "Excluir patrim�nios selecionados", "button")."</div>
						  </span>";
    
	

	$pagina->LinhaCampoFormulario("N� de patrim�nio:", "right", "N",
									  $pagina->CampoTexto("v_NUM_PATRIMONIO", "N", "N�mero do patrimonio" , "10", "10", "", "")."&nbsp;".
									  $pagina->CampoButton("do_ValidarPatrimonio()", "Adicionar", "button").
									  "&nbsp;
									  $vComboPatrimonios
									  "
									  , "left", "id=".$pagina->GetIdTable());
}
	// Anexos
	$pagina->LinhaCampoFormulario("Anexo(s):", "right", "N",
									  $pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_1", "N", "", "40").
									  "
									  <span id=\"Newfile1\">
									  	<a href=\"javascript: AnexaNovoArquivo(1)\">Anexar outro arquivo</a>
									  </span>
						  <!-- ================================================================================= -->
									  <span id=\"file2\" style=\"display: none\">
											".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_2", "N", "", "40")."
											<a href=\"javascript: ExcluirArquivo(2)\">Excluir</a>
									  </span>
									  <span id=\"Newfile2\" style=\"display: none\">
									  	<a href=\"javascript: AnexaNovoArquivo(2)\">Anexar outro arquivo</a>
									  </span>
						  <!-- ================================================================================= -->
									  <span id=\"file3\" style=\"display: none\">
											".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_3", "N", "", "40")."
											<a href=\"javascript: ExcluirArquivo(3)\">Excluir</a>
									  </span>
									  <span id=\"Newfile3\" style=\"display: none\">
									  	<a href=\"javascript: AnexaNovoArquivo(3)\">Anexar outro arquivo</a>
									  </span>
						  <!-- ================================================================================= -->
									  <span id=\"file4\" style=\"display: none\">
											".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_4", "N", "", "40")."
											<a href=\"javascript: ExcluirArquivo(4)\">Excluir</a>
									  </span>
									  <span id=\"Newfile4\" style=\"display: none\">
									  	<a href=\"javascript: AnexaNovoArquivo(4)\">Anexar outro arquivo</a>
									  </span>
						  <!-- ================================================================================= -->
									  <span id=\"file5\" style=\"display: none\">
											".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_5", "N", "", "40")."
											<a href=\"javascript: ExcluirArquivo(5)\">Excluir</a>
									  </span>
									  <span id=\"Newfile5\" style=\"display: none\">
									  	<a href=\"javascript: AnexaNovoArquivo(5)\">Anexar outro arquivo</a>
									  </span>
						  <!-- ================================================================================= -->
									  <span id=\"file6\" style=\"display: none\">
											".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_6", "N", "", "40")."
											<a href=\"javascript: ExcluirArquivo(6)\">Excluir</a>
									  </span>
									  <span id=\"Newfile6\" style=\"display: none\">
									  	<a href=\"javascript: AnexaNovoArquivo(6)\">Anexar outro arquivo</a>
									  </span>
						  <!-- ================================================================================= -->
									  <span id=\"file7\" style=\"display: none\">
											".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_7", "N", "", "40")."
											<a href=\"javascript: ExcluirArquivo(7)\">Excluir</a>
									  </span>
									  <span id=\"Newfile7\" style=\"display: none\">
									  	<a href=\"javascript: AnexaNovoArquivo(7)\">Anexar outro arquivo</a>
									  </span>
						  <!-- ================================================================================= -->
									  <span id=\"file8\" style=\"display: none\">
											".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_8", "N", "", "40")."
											<a href=\"javascript: ExcluirArquivo(8)\">Excluir</a>
									  </span>
									  <span id=\"Newfile8\" style=\"display: none\">
									  	<a href=\"javascript: AnexaNovoArquivo(8)\">Anexar outro arquivo</a>
									  </span>
						  <!-- ================================================================================= -->
									  <span id=\"file9\" style=\"display: none\">
											".$pagina->CampoFile("v_NOM_ARQUIVO_ORIGINAL_9", "N", "", "40")."
											<a href=\"javascript: ExcluirArquivo(9)\">Excluir</a>
									  </span>
									  "
									  , "left", "id=".$pagina->GetIdTable());

	// Montar a combo de Sistemas de Informa��o
	if($banco->SEQ_ITEM_CONFIGURACAO == ""){
		$aItemOption = Array();
		$aItemOption[] = array("", "", "Selecione o Sistema de Informa��o");
		$pagina->LinhaCampoFormulario("Sistema de Informa��o:", "right", "S", $pagina->CampoSelect("v_SEQ_ITEM_CONFIGURACAO", "S", "Sistema de Informa��o", "N", $aItemOption), "left", " style=\"display: none\" id=\"combo_sistema\" class=".$pagina->GetIdTable());
	}else{
		require_once 'include/PHP/class/class.item_configuracao.php';
		$item_configuracao = new item_configuracao();
		$pagina->LinhaCampoFormulario("Sistema de Informa��o:", "right", "S", $pagina->CampoSelect("v_SEQ_ITEM_CONFIGURACAO", "S", "Sistema de Informa��o", "N", $item_configuracao->combo("SIG_ITEM_CONFIGURACAO", $banco->SEQ_ITEM_CONFIGURACAO)), "left", " id=\"combo_sistema\" class=".$pagina->GetIdTable());
	}

	$pagina->LinhaCampoFormularioColspan("center", "<hr><div align=left><font color=red>*</font> - Preenchimento obrigat�rio</div>", "2");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("removeEvent(window, 'beforeunload', exitAlert, false); return fValidaFormLocal(); ", " Enviar "), "2");
	$pagina->FechaTabelaPadrao();
	?>
	<script language="javascript">
	// Inicializar campo matr�cula da pessoa de contato
		<?
		if($banco->NUM_MATRICULA_CONTATO != ""){
			?>
			do_ValidarPessoaContato();
			<?
		}
		?>
	</script>
	<?
	$pagina->MontaRodape();
}else{
	$pagina->ScriptAlert("Selecione o chamado.");
	$pagina->redirectToJS("ChamadoAtendimentoPesquisa.php");
}
?>