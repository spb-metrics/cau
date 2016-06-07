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
require 'include/PHP/class/class.tarefa.php';
require 'include/PHP/class/class.time_sheet.php';
require_once 'include/PHP/class/class.recurso_ti.php';

$pagina = new Pagina();
$banco = new tarefa();
$banco_sheet = new time_sheet();
$banco_recurso_ti = new recurso_ti();
$banco_lider = new recurso_ti();


$pagina->setMethod("post");

$v_GESTOR = $_REQUEST['GESTOR'];

//Se matricula não for especifica pegar a matricula do usuário corrente
if ($v_NUM_MATRICULA_RECURSO == "" ) {	
	$v_NUM_MATRICULA_RECURSO = $_SESSION["NUM_MATRICULA_RECURSO"];	
}	

if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Time Sheet"); // Indica o título do cabeçalho da página
	// Itens das abas
	if ($v_GESTOR == "TRUE" ) {
		$aItemAba = Array( array("Admin_TimeSheet.php", "", "Pesquisa"),
					   array("#", "tabact", "Time Sheet")					   
						 );
	} else {
		$aItemAba = Array( array("#", "tabact", "Time Sheet")					   
						 );	
	}
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();
	
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "95%");
	
	//
	$pagina->LinhaColspan("left", "<b><font size=\"2\">".$_SESSION["NOME"]."</font></b>", "2", "tabelaConteudoHeader");
	$pagina->LinhaColspan("left", "<b><font size=\"2\">Matricula:".$_SESSION["NOM_LOGIN_REDE"]."</font></b>", "2", "tabelaConteudoHeader");
	
	$pagina->LinhaVazia(1);
	
	print $pagina->CampoHidden("v_NUM_MATRICULA_RECURSO", substr($_SESSION["NOM_LOGIN_REDE"],1));
			
	// Monta dados estatísticos	
	$pagina->LinhaCampoFormularioColspanDestaque("Estatística", 2);		
	$banco->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
	$banco->selectParam("2", $vNumPagina, "999");
	
	$intTotaNaoIniciado = 0;
	$intTotaEmAndamento = 0;
	$intTotaParado = 0;
	$intTotaEncerrado = 0;	
	$intTotalGeral = 0;	
	if($banco->database->rows != 0){
	
		while ($row = pg_fetch_array($banco->database->result)){ 
			switch ($row["SEQ_STATUS_TAREFA_TI"]) 
			{
				case 1:
					++$intTotaNaoIniciado; break;
				case 2:
					++$intTotaEmAndamento; break;
				case 3:
					++$intTotaParado; break;
				case 4:
					++$intTotaEncerrado; break;
				default:
					++$intTotaNaoIniciado; break;
			}	
			++$intTotalGeral;						
		}		
	}	
	
	$intPercent1 = $intPercent2 = $intPercent3 = $intPercent4 = 0;
	if ( $intTotalGeral > 0 ) {	
		$intPercent1 = ((100*$intTotaNaoIniciado)/$intTotalGeral);
		$intPercent2 = ((100*$intTotaEmAndamento)/$intTotalGeral);
		$intPercent3 = ((100*$intTotaParado)/$intTotalGeral);
		$intPercent4 = ((100*$intTotaEncerrado)/$intTotalGeral);
	}						
		
	$arrLegenda[] = array("Não Iniciado", $intPercent1);
	$arrLegenda[] = array("Em andamento", $intPercent2);
	$arrLegenda[] = array("Parado", $intPercent3);
	$arrLegenda[] = array("Encerrado", $intPercent4);	
	$pagina->LinhaCampoFormularioColspan("center", $pagina->GraficoBarras($arrLegenda, "95%"), 2);
	
	// Monta grid	
	$arrTipo = array("Manutenção", "Projeto");
	foreach ($arrTipo as &$value) {

		$pagina->LinhaCampoFormularioColspanDestaque($value, 2);
		
		$header = array();
		$header[] = array("Tarefa", "center", "65%");
		$header[] = array("Inf.", "center", "5%");
		$header[] = array("Data", "center", "10%");
		$header[] = array("legendaClaro", "center", "5%");
		$header[] = array("legendaVerde", "center", "5%");
		$header[] = array("legendaLaranja", "center", "5%");
		$header[] = array("legendaVermelho", "center", "5%");	
		$header[] = array("Extra", "center", "5%");	
		//Permite que o Gestor aprove a hoara extra
		if ($v_GESTOR == "TRUE" ) {
			$header[] = array("Aprova", "center", "5%");	
		}			
		
		// Setar variáveis 
		if ( $value == "Manutenção" )
		{
			$v_FLG_OS = "TRUE";
			$v_FLG_PROJETO = "";
			$v_StrGrid = "manut";			
		}
		else
		{
			$v_FLG_OS = "";
			$v_FLG_PROJETO = "TRUE";
			$v_StrGrid = "proj";				
		}
		
		//Pesquisa tarefas da Matricula em questão
		$banco->setNUM_MATRICULA_RECURSO($v_NUM_MATRICULA_RECURSO);
		$banco->setFLG_OS($v_FLG_OS);	
		$banco->setFLG_PROJETO($v_FLG_PROJETO);
		$banco->selectParam("2", $vNumPagina, "999");
		if($banco->database->rows == 0){
			$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
		}else{				
			$v_IntIndex = 0;
			$coluna = array();			
			while ($row = pg_fetch_array($banco->database->result)){ 
			
				$flg_mostrar = true;
			
				$v_StrStatus1 = "";
				$v_StrStatus2 = "";
				$v_StrStatus3 = "";
				$v_StrStatus4 = "";			
				
				switch ($row["SEQ_STATUS_TAREFA_TI"]) 
				{
					case 1:
						$v_StrStatus1 = "1"; break;
					case 2:
						$v_StrStatus2 = "2"; break;
					case 3:
						$v_StrStatus3 = "3"; break;
					case 4:
						$v_StrStatus4 = "4"; break;
					default:
						$v_StrStatus1 = "1"; break;
				}			
				
				//Quando status igual a NÃO INICIADO, não há registro na tabela TS
				$banco_sheet->setSEQ_TAREFA_TI($row["SEQ_TAREFA_TI"]);
		        $banco_sheet->selectParam("1","","1");
				if ( $banco_sheet->getSEQ_TIME_SHEET() == "") {	
					//$v_StrStatus1 = "1";			
			        
				}									
				
				//Inicializa Variaveis para controle da habilitacao e dasabilitação do status
				$v_StrStatusD1 = "";
				$v_StrStatusD2 = "";
				$v_StrStatusD3 = "";
				$v_StrStatusD4 = "";	
				
				//Se for administrador pode mudar sem restrições os status
				if ($v_GESTOR == "TRUE" ) {
					//Se status ENCERRADO, não permitir voltar para Status Parado										
					if( $row["SEQ_STATUS_TAREFA_TI"]=="4" ){
						$v_StrStatusD1 = "disabled";						
						$v_StrStatusD3 = "disabled";
					}		
					//Se status Em andamento, Parado ou encerrado não permitir voltar para Status Não iniciado									
					if( $row["SEQ_STATUS_TAREFA_TI"]=="2" ||$row["SEQ_STATUS_TAREFA_TI"]=="3" || $row["SEQ_STATUS_TAREFA_TI"]=="4" ){
						$v_StrStatusD1 = "disabled";												
					}				
				}else{
					//Se status NÃO INICIADO, só pode mudar para Em andamento ou Encerrado.											
					if( $row["SEQ_STATUS_TAREFA_TI"]=="1" ){
						$v_StrStatusD3 = "disabled";
					} else {
						$v_StrStatusD1 = "disabled";
					}
					
					//Se status for diferente de NÃO INICIADO, não permitir voltar para este status											
					if( $row["SEQ_STATUS_TAREFA_TI"]!="1" ){
						$v_StrStatusD1 = "disabled";
					}		
					
					//Se status ENCERRADO, não permitir voltar outros status										
					if( $row["SEQ_STATUS_TAREFA_TI"]=="4" ){
						$v_StrStatusD1 = "disabled";
						$v_StrStatusD2 = "disabled";
						$v_StrStatusD3 = "disabled";
					}							
				}
				
				// Mostra data da Ultima Atualizacao
				$mes = substr($row["DAT_ATUALIZACAO_TAREFA"],5,2);
				$dia = substr($row["DAT_ATUALIZACAO_TAREFA"],8,2);
				$ano = substr($row["DAT_ATUALIZACAO_TAREFA"],0,4);
				if ( $ano != "0000" ) {
					$data = $dia."/".$mes."/".$ano;
				}else{
					$data = "          ";
				}					
				
				//Só exibir as tarefas encerradas do dia.
				if ( $row["SEQ_STATUS_TAREFA_TI"] == "4" ) {					
					if ( date("d/m/Y") == $data ) {
						$flg_mostrar = true;						
					} else {
						$flg_mostrar = true;
					}			
				}
				
				if ( $flg_mostrar ) {
					//Descrição
					if ( $value == "Manutenção" ) {				
						$coluna[$v_IntIndex][] = array($row["SEQ_OS"], "center", "70%", "label");
					} else {
						$coluna[$v_IntIndex][] = array($row["SEQ_EPM"], "center", "70%", "label");				
					}
					//Icone de informação
					if ($v_GESTOR == "TRUE" ) {
						$coluna[$v_IntIndex][] = array($row["SEQ_TAREFA_TI"]."&v_NUM_MATRICULA_RECURSO=".$row["NUM_MATRICULA_RECURSO"]."&GESTOR=TRUE", "center", "10%", "icon",$row["NUM_MATRICULA_RECURSO"]);				
					} else {
						$coluna[$v_IntIndex][] = array($row["SEQ_TAREFA_TI"]."&v_NUM_MATRICULA_RECURSO=".$row["NUM_MATRICULA_RECURSO"], "center", "10%", "icon",$row["NUM_MATRICULA_RECURSO"]);	
					}
					//Data
					$coluna[$v_IntIndex][] = array("&nbsp".$data."&nbsp", "center", "10%", "label","");
					//Status
					$coluna[$v_IntIndex][] = array($v_StrStatus1, "center", "5%", "radio",$v_StrStatusD1);
					$coluna[$v_IntIndex][] = array($v_StrStatus2, "center", "5%", "radio",$v_StrStatusD2);
					$coluna[$v_IntIndex][] = array($v_StrStatus3, "center", "5%", "radio",$v_StrStatusD3);
					$coluna[$v_IntIndex][] = array($v_StrStatus4, "center", "5%", "radio",$v_StrStatusD4);
					//Hora extra
					$coluna[$v_IntIndex][] = array("extra", "center", "5%", "text", $row["QTD_HORA_TOTAL_UTEIS"]);
					
					//Aprove
					if ($v_GESTOR == "TRUE" ) {
						if ($row["FLG_APROVA_EXTRA"] == "S") {
							$strChecked = "checked";
						}else{
							$strChecked = "";
						}
						$coluna[$v_IntIndex][] = array($strChecked, "center", "5%", "checkbox", "valor");
					}

					//Campos Hidden
					print $pagina->CampoHidden($v_StrGrid."id".$v_IntIndex, $row["SEQ_TAREFA_TI"]);								
					print $pagina->CampoHidden("v".$v_StrGrid."_SEQ_OS_".$v_IntIndex, $row["SEQ_OS"]);
					print $pagina->CampoHidden("v".$v_StrGrid."_SEQ_EPM_".$v_IntIndex, $row["SEQ_EPM"]);
					print $pagina->CampoHidden("v".$v_StrGrid."_DAT_CRIACAO_TAREFA_".$v_IntIndex, $row["DAT_CRIACAO_TAREFA"]);
					print $pagina->CampoHidden("v".$v_StrGrid."_DAT_ATUALIZACAO_TAREFA_".$v_IntIndex, $row["DAT_ATUALIZACAO_TAREFA"]);
					print $pagina->CampoHidden("v".$v_StrGrid."_NUM_MATRICULA_LIDER_".$v_IntIndex, $row["NUM_MATRICULA_LIDER"]);					
					
					++$v_IntIndex;	
				}												
			}
			
			$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaSimples($v_StrGrid, $header, $coluna, "95%"), 2);
		}		
		
		$header = array();
		$coluna = array();

	}	
	
	// Trata legenda
	$arrLegenda = array();
	$arrLegenda[] = array("Não Iniciado", "legendaClaro");
	$arrLegenda[] = array("Em andamento", "legendaVerde");
	$arrLegenda[] = array("Parado", "legendaLaranja");
	$arrLegenda[] = array("Encerrado", "legendaVermelho");	
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Legenda($arrLegenda, "85%"), 2);	
	
	//Ao clicar no SALVAR pedir confirmação.
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("Msg = confirm('Confirma a inclusão?');if (Msg != true){  return false; }else{return fValidaForm();}", " Atualizar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape(); 
}else{

	//$banco_sheet2 = new time_sheet();
	//$retorno = $banco_sheet2->max('1');
	//print $retorno->ultimo;
	//exit;

	//--Persiste o Dados
	$arrTipo = array("manut", "proj");
	$result = "";
	foreach ($arrTipo as &$value) {	
		$i = 0;
		$flag = "";
		while (isset($_REQUEST[''.$value .'id'.$i.''])):		
			
			$banco->select($_REQUEST[''.$value .'id'.$i.'']);	
			
			// Buscar dados do Funcionario	
			if ($banco->getNUM_MATRICULA_RECURSO() != "") {
				$banco_recurso_ti->select($banco->getNUM_MATRICULA_RECURSO());									
			}	
					
			//Só alterar registro quando o Status for modificado
			if ($banco->getSEQ_STATUS_TAREFA_TI() <> $_REQUEST[''.$value .'grupo'.$i.'']){
			
				//Inclui Tabela 'time_sheet'				
				//Mudança de status EM ANDAMENTO, criar registro na tabela TS				
				if ( $_REQUEST[''.$value .'grupo'.$i.''] == "2" ) {				
					$flag = "TRUE";					
					//Persistencia
					$banco_sheet->setSEQ_TAREFA_TI($_REQUEST[''.$value .'id'.$i.'']);
					$banco_sheet->setDAT_INICIO(date(" Y-m-d H:i:s"));

					//Inclui
					$banco_sheet->insert();
					if($banco->error != ""){
						$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
					}	
				}	
				// Mudança de status para PARADO ou ENCERRADO, alterar a tarefa em andamento com a data final.
				if ( $_REQUEST[''.$value .'grupo'.$i.''] == "3" || $_REQUEST[''.$value .'grupo'.$i.''] == "4" ) {	
					//Pega o Ultimo Registro da Tabela Time Sheet
					$banco_sheet2 = new time_sheet();
					$retorno = $banco_sheet2->max($_REQUEST[''.$value .'id'.$i.'']);						
					//Persistencia						
					$banco_sheet->setDAT_FIM(date(" Y-m-d H:i:s"));
					$banco_sheet->update($retorno->ultimo);
					if($banco->error != ""){
						$pagina->mensagem("Registro Time Sheet não incluído. O seguinte erro ocorreu:<br> $banco->error");
					} 	
				}
												
			}
						
			//-- Altera Tabela 'tarefa' 
			//Só alterar registro quando o Status for modificado			
			if (($strAprova <> $banco->getFLG_APROVA_EXTRA()) || ($banco->getSEQ_STATUS_TAREFA_TI() <> $_REQUEST[''.$value .'grupo'.$i.'']) || ($banco->getQTD_HORA_TOTAL_UTEIS() <> $_REQUEST[''.$value .'extra'.$i.''])){
															
				$banco->setSEQ_STATUS_TAREFA_TI($_REQUEST[''.$value .'grupo'.$i.'']);
				$banco->setNUM_MATRICULA_RECURSO($_REQUEST['v_NUM_MATRICULA_RECURSO']);
				$banco->setSEQ_OS($_REQUEST['v'.$value .'_SEQ_OS_'.$i.'']);
				$banco->setSEQ_EPM($_REQUEST['v'.$value .'_SEQ_EPM_'.$i.'']);
				$banco->setDAT_CRIACAO_TAREFA($_REQUEST['v'.$value .'_DAT_CRIACAO_TAREFA_'.$i.'']);	
				$banco->setQTD_HORA_TOTAL_UTEIS($_REQUEST[''.$value .'extra'.$i.'']);	
				
				//Administrador do Time Sheet
				if ($v_GESTOR == "TRUE") {
					//Aprova				
					if ($_REQUEST[''.$value .'aprova'.$i.''] == 'on') {
						$banco->setFLG_APROVA_EXTRA('S');	
						$strResultado = "Aprovado";
					}else{
						$banco->setFLG_APROVA_EXTRA('N');	
						$strResultado = "Não Aprovado";
					}
					
					//Email - Matricula do resposavel que aprovou 
					if ($_SESSION["NUM_MATRICULA_RECURSO"] != "" && $_REQUEST[''.$value .'extra'.$i.''] != "") {
						$banco_lider->select($_SESSION["NUM_MATRICULA_RECURSO"]);	

						//Envia Email		
						$destino = $banco_recurso_ti->DES_EMAIL;						
						$remetente = 	$banco_lider->DES_EMAIL;
						$assunto = "Time Sheet - Aprovação da Tarefa - ".$banco->getSEQ_TAREFA_TI();
						$mensagem = "Solicitação de Aprovação - ".$banco_recurso_ti->NOME."/n"."Tarefa: ".$banco->getSEQ_TAREFA_TI()." ".$strResultado;
						$pagina->EnviaEmail($destino, $rementente, $assunto, $mensagem);										
					}					
				}					
				
				//Data da Atualizacao
				if ( $flag == "TRUE" ) {			
					$banco->setDAT_ATUALIZACAO_TAREFA(date(" Y-m-d H:i:s"));	
				}
				else
				{
					$banco->setDAT_ATUALIZACAO_TAREFA($_REQUEST['v'.$value .'_DAT_ATUALIZACAO_TAREFA_'.$i.'']);	
				}
				
				//altera				
				$banco->update($_REQUEST[''.$value .'id'.$i.'']);
				if($banco->error != ""){
					$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
				} else {
					//Ao solicitar para ENCERRAR, pedir para preencher qtd de horas extras e enviar e-mail para o LIDER confirmar. 					
					if ($banco->getQTD_HORA_TOTAL_UTEIS() <> $_REQUEST[''.$value .'extra'.$i.'']) {		
						//Email - Buscar dados do Lider	
						if ($banco->getNUM_MATRICULA_LIDER() != "") {
							$banco_lider->select($banco->getNUM_MATRICULA_LIDER());												

							//Envia Email		
							$remetente = $banco_recurso_ti->DES_EMAIL;	
							$destino = 	$banco_lider->DES_EMAIL;
							$assunto = "Time Sheet - Aprovação da Tarefa - ".$banco->getSEQ_TAREFA_TI();
							$mensagem = "Solicitação de Aprovação - ".$banco_recurso_ti->NOME."/n"."Tarefa: ".$banco->getSEQ_TAREFA_TI();
							$pagina->EnviaEmail($destino, $rementente, $assunto, $mensagem);										
						}
					}
				}
				$flag = "";
			}				
			++$i;
			
		endwhile;
				
  	}		
	//Redireciona Página
	if ($v_GESTOR == "TRUE" ) {
		$pagina->redirectTo("TimeSheet.php?v_NUM_MATRICULA_RECURSO=".$v_NUM_MATRICULA_RECURSO."&GESTOR=TRUE");
	} else {
		$pagina->redirectTo("TimeSheet.php?v_NUM_MATRICULA_RECURSO=".$v_NUM_MATRICULA_RECURSO);	
	}

}
?>
