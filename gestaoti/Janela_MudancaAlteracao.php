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
function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
}
 
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.janela_mudanca.php';
$pagina = new Pagina();
$banco = new janela_mudanca();


if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alteração de Janela de Mudança"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Janela_MudancaPesquisa.php", "", "Pesquisa"),
						array("Janela_MudancaCadastro.php", "", "Adicionar"),
		 			    array("Janela_MudancaAlteracao.php", "tabact", "Alterar"));
	$pagina->SetaItemAba($aItemAba);
	
	// Pesquisar
	$banco->select($seq_janela_mudanca);
	
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("seq_janela_mudanca", $seq_janela_mudanca);
	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	
	// Descrição da Janela de mudança
	$pagina->LinhaCampoFormulario("Descrição:", "right", "S", $pagina->CampoTexto("dsc_janela_mudanca", "S", "Descrição", "60", "60", "$banco->dsc_janela_mudanca"), "left");

	// Dia da semana inicial da Janela de mudança
	echo $banco->iif($banco->dia_semana_inicial=="Dom", "Selected", "");
	$aDiasDaSemanaInicial = Array();
	$aDiasDaSemanaInicial[0] = array("Dom", $banco->iif($banco->dia_semana_inicial=="Dom", "Selected", ""), "Dom");
	$aDiasDaSemanaInicial[1] = array("Seg", $banco->iif($banco->dia_semana_inicial=="Seg", "Selected", ""), "Seg");
	$aDiasDaSemanaInicial[2] = array("Ter", $banco->iif($banco->dia_semana_inicial=="Ter", "Selected", ""), "Ter");
	$aDiasDaSemanaInicial[3] = array("Qua", $banco->iif($banco->dia_semana_inicial=="Qua", "Selected", ""), "Qua");
	$aDiasDaSemanaInicial[4] = array("Qui", $banco->iif($banco->dia_semana_inicial=="Qui", "Selected", ""), "Qui");
	$aDiasDaSemanaInicial[5] = array("Sex", $banco->iif($banco->dia_semana_inicial=="Sex", "Selected", ""), "Sex");
	$aDiasDaSemanaInicial[6] = array("Sab", $banco->iif($banco->dia_semana_inicial=="Sab", "Selected", ""), "Sab");
	
	 
	$pagina->LinhaCampoFormulario("Início:", "right", "S", $pagina->CampoSelect("dia_semana_inicial", "S", "Início ", "S", $aDiasDaSemanaInicial), "left");
	
	// Dia da semana inicial da Janela de mudança 
	$aDiasDaSemanaFinal = Array();
	$aDiasDaSemanaFinal[0] = array("Dom", $banco->iif($banco->dia_semana_final=="Dom", "Selected", ""), "Dom");
	$aDiasDaSemanaFinal[1] = array("Seg", $banco->iif($banco->dia_semana_final=="Seg", "Selected", ""), "Seg");
	$aDiasDaSemanaFinal[2] = array("Ter", $banco->iif($banco->dia_semana_final=="Ter", "Selected", ""), "Ter");
	$aDiasDaSemanaFinal[3] = array("Qua", $banco->iif($banco->dia_semana_final=="Qua", "Selected", ""), "Qua");
	$aDiasDaSemanaFinal[4] = array("Qui", $banco->iif($banco->dia_semana_final=="Qui", "Selected", ""), "Qui");
	$aDiasDaSemanaFinal[5] = array("Sex", $banco->iif($banco->dia_semana_final=="Sex", "Selected", ""), "Sex");
	$aDiasDaSemanaFinal[6] = array("Sab", $banco->iif($banco->dia_semana_final=="Sab", "Selected", ""), "Sab");
	
	$pagina->LinhaCampoFormulario("Fim:", "right", "S", $pagina->CampoSelect("dia_semana_final", "S", "Fim ", "S", $aDiasDaSemanaFinal), "left");
	
	
	// Hora inicial da Janela de mudança
	$aHoraInicial= Array();
	
 	for($i=0;$i<=23;$i++){
		if(strlen($i) == 1) {
			$hora = '0'.$i;
		} else {
			$hora = $i;
		}
		$aHoraInicial[$i] = array($hora, $banco->iif($banco->hora_inicio_mudanca==$i, "Selected", ""), $hora); 
   	}
	  	
	// Minuto inicial da Janela de mudança
	$aMinutoInicial= Array();
	for($i=0;$i<=59;$i++){
		if(strlen($i) == 1) {
        	$minuto = '0'.$i;
        } else {
        	$minuto = $i;
        }
        $aMinutoInicial[$i] = array($minuto, $banco->iif($banco->minuto_inicio_mudanca==$i, "Selected", ""), $minuto);                          
	}	
	
    //$pagina->LinhaCampoFormulario("Hora Inicial: ", "right", "S", $pagina->CampoSelect("hora_inicio_mudanca", "S", "Hora inicial ", "S", $aHoras), "left");
	//$pagina->LinhaCampoFormulario("Minuto Inicial: ", "right", "S", 	$pagina->CampoSelect("minuto_inicio_mudanca", "S", "Minuto inicial ", "S", $aMinutos), "left");
	
	$pagina->LinhaCampoFormulario("Horário de Início: ", "right", "S", 
	$pagina->CampoSelect("hora_inicio_mudanca", "S", "Hora de Início", "N", $aHoraInicial).":".
	$pagina->CampoSelect("minuto_inicio_mudanca", "S", "Minuto de Início", "N", $aMinutoInicial)
	, "left");
	 		 	 
	
	
	// Hora Final da Janela de mudança 	 
	//$pagina->LinhaCampoFormulario("Hora Final: ", "right", "S", $pagina->CampoSelect("hora_fim_mudanca", "S", "Hora Final ", "S", $aHoras), "left");
	// Minuto Final da Janela de mudança	
	//$pagina->LinhaCampoFormulario("Minuto Final: ", "right", "S", $pagina->CampoSelect("minuto_fim_mudanca", "S", "Minuto Final ", "S", $aMinutos), "left");
	// Hora inicial da Janela de mudança
	$aHoraFinal= Array();
	
 	for($i=0;$i<=23;$i++){
		if(strlen($i) == 1) {
			$hora = '0'.$i;
		} else {
			$hora = $i;
		}
		$aHoraFinal[$i] = array($hora, $banco->iif($banco->hora_fim_mudanca==$i, "Selected", ""), $hora); 
   	}
	 	
	// Minuto inicial da Janela de mudança
	$aMinutoFinal= Array();
	for($i=0;$i<=59;$i++){
		if(strlen($i) == 1) {
        	$minuto = '0'.$i;
        } else {
        	$minuto = $i;
        }
        $aMinutoFinal[$i] = array($minuto, $banco->iif($banco->minuto_fim_mudanca==$i, "Selected", ""), $minuto);                          
	}	
	$pagina->LinhaCampoFormulario("Horário de Fim: ", "right", "S", 
	$pagina->CampoSelect("hora_fim_mudanca", "S", "Hora de Fim ", "N", $aHoraFinal).":".
	$pagina->CampoSelect("minuto_fim_mudanca", "S", "Minuto de Final ", "N", $aMinutoFinal)
	, "left");
	
	// Limite para RDM da Janela de mudança
	//print 'limite_para_rdm: '.$banco->limite_para_rdm;
	$pagina->LinhaCampoFormulario("Limite para RDM (em minutos):", "right", "S", $pagina->CampoInt("limite_para_rdm", "S", "Limite para RDM", "10", "$banco->limite_para_rdm", ""), "left");
	
	// 	Servidores
	require 'include/PHP/class/class.servidor.php';
	$servidor = new servidor();	
	$servidor->selectParam("2");	 
	$aServidores = Array();
	$i = 0;
	 
	require_once 'include/PHP/class/class.janela_mudanca_servidor.php';
	$janela_mudanca_servidor = new janela_mudanca_servidor();
	 
	
	while ($rowServidor = pg_fetch_array($servidor->database->result)){		
		$aServidores[$i][0] = $rowServidor["seq_servidor"];		
		$janela_mudanca_servidor->setSeq_janela_mudanca($banco->seq_janela_mudanca);
		$janela_mudanca_servidor->setSeq_servidor($rowServidor["seq_servidor"]);
		$janela_mudanca_servidor->selectParam();
		if($janela_mudanca_servidor->database->rows == 0){
				$aServidores[$i][1] = "";
		}else{
				$aServidores[$i][1] = "Selected";
		}		 
		$aServidores[$i][2] = $rowServidor["nom_servidor"];		 
		$i++;		
	} 
	
	$size = 0;
	if($i>20){
		$size = $i/2;
	}else{
		$size = $i;
	}
	
	 
	$pagina->LinhaCampoFormulario("Servidores:", "right", "S", $pagina->CampoSelectMultiple("servidores[]", "S", "Servidores ", "S", $aServidores,"","","","$size"), "left");
	
	// Sistemas de informação
	require 'include/PHP/class/class.item_configuracao.php';
	$sistemas = new item_configuracao();
	$sistemas->setSEQ_TIPO_ITEM_CONFIGURACAO(2);
	$sistemas->selectParam();	 
	
	$aSistemas = Array();
	$i = 0;
	
	require_once 'include/PHP/class/class.janela_mudacao_item_configuracao.php';
	$janela_mudacao_item_configuracao = new janela_mudacao_item_configuracao();
	
	while ($rowSistema = pg_fetch_array($sistemas->database->result)){		 
		//echo $rowSistema["seq_item_configuracao"]."-". $rowSistema["nom_item_configuracao"]."<br>";
		$aSistemas[$i][0] = $rowSistema["seq_item_configuracao"];
		 
		$janela_mudacao_item_configuracao->setSeq_janela_mudanca($banco->seq_janela_mudanca);
		$janela_mudacao_item_configuracao->setSeq_item_configuracao($rowSistema["seq_item_configuracao"]);
		$janela_mudacao_item_configuracao->selectParam();
		
		if($janela_mudacao_item_configuracao->database->rows == 0){
				$aSistemas[$i][1] = "";
		}else{
				$aSistemas[$i][1] = "Selected";
		}		
		$aSistemas[$i][2] = $rowSistema["nom_item_configuracao"];		 
		$i++;		
	} 
	
	$size = 0;
	if($i>20){
		$size = $i/2;
	}else{
		$size = $i;
	}
	 
	$pagina->LinhaCampoFormulario("Sistemas:", "right", "S", $pagina->CampoSelectMultiple("sistemas[]", "S", "Sistemas ", "S", $aSistemas,"","","","$size"), "left");
	
	//$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro
	$banco->setSeq_janela_mudanca($seq_janela_mudanca);
	$banco->setDsc_janela_mudanca($dsc_janela_mudanca);
	$banco->setDia_semana_inicial($dia_semana_inicial);
	$banco->setHora_inicio_mudanca($hora_inicio_mudanca);
	$banco->setMinuto_inicio_mudanca($minuto_inicio_mudanca);
	$banco->setDia_semana_final($dia_semana_final);
	$banco->setHora_fim_mudanca($hora_fim_mudanca);
	$banco->setMinuto_fim_mudanca($minuto_fim_mudanca);
	$banco->setLimite_para_rdm($limite_para_rdm);
	$banco->update($seq_janela_mudanca);
	// Código inserido: $banco->SEQ_MOTIVO_CANCELAMENTO
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		
		// Incluir servidores
		require_once 'include/PHP/class/class.janela_mudanca_servidor.php';
		$janela_mudanca_servidor = new janela_mudanca_servidor();
		$janela_mudanca_servidor->deleteBySeq_janela_mudanca($banco->seq_janela_mudanca);
		
	    for ($i = 0; $i < count($servidores); $i++){
			$janela_mudanca_servidor->setSeq_janela_mudanca($banco->seq_janela_mudanca);
			$janela_mudanca_servidor->setSeq_servidor($servidores[$i]);
			$janela_mudanca_servidor->insert();
	    }
		
		// Incluir o item configuracao
		require_once 'include/PHP/class/class.janela_mudacao_item_configuracao.php';
		$janela_mudacao_item_configuracao = new janela_mudacao_item_configuracao();
		$janela_mudacao_item_configuracao->deleteBySeq_janela_mudanca($banco->seq_janela_mudanca);
		
	    for ($i = 0; $i < count($sistemas); $i++){
			$janela_mudacao_item_configuracao->setSeq_janela_mudanca($banco->seq_janela_mudanca);
			$janela_mudacao_item_configuracao->setSeq_item_configuracao($sistemas[$i]);
			$janela_mudacao_item_configuracao->insert();
	    }
		$pagina->redirectTo("Janela_MudancaPesquisa.php?seq_janela_mudanca=$banco->seq_janela_mudanca");
	}
}
?>
