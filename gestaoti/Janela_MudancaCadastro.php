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
require 'include/PHP/class/class.janela_mudanca.php';
$pagina = new Pagina();
$banco = new janela_mudanca();
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Janela de Mudan�a"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Janela_MudancaPesquisa.php", "", "Pesquisa"),
						array("Janela_MudancaCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formul�rio
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	$pagina->AbreTabelaPadrao("center", "85%");
	
	// Descri��o da Janela de mudan�a
	$pagina->LinhaCampoFormulario("Descri��o:", "right", "S", $pagina->CampoTexto("dsc_janela_mudanca", "S", "Descri��o", "60", "60", ""), "left");

	// Dia da semana inicial da Janela de mudan�a
	$aDiasDaSemana = Array();
	$aDiasDaSemana[0] = array("Dom", "", "Domingo");
	$aDiasDaSemana[1] = array("Seg", "", "Segunda");
	$aDiasDaSemana[2] = array("Ter", "", "Ter�a");
	$aDiasDaSemana[3] = array("Qua", "", "Quarta");
	$aDiasDaSemana[4] = array("Qui", "", "Quinta");
	$aDiasDaSemana[5] = array("Sex", "", "Sexta");
	$aDiasDaSemana[6] = array("Sab", "", "S�bado");
	
	 
	$pagina->LinhaCampoFormulario("In�cio:", "right", "S", $pagina->CampoSelect("dia_semana_inicial", "S", "In�cio ", "S", $aDiasDaSemana), "left");
	
	// Dia da semana inicial da Janela de mudan�a 
	$pagina->LinhaCampoFormulario("Fim:", "right", "S", $pagina->CampoSelect("dia_semana_final", "S", "Fim ", "S", $aDiasDaSemana), "left");
	
	
	// Hora inicial da Janela de mudan�a
	$aHoras= Array();
	
 	for($i=0;$i<=23;$i++){
		if(strlen($i) == 1) {
			$hora = '0'.$i;
		} else {
			$hora = $i;
		}
		$aHoras[$i] = array($hora, "", $hora); 
   	}
	 

	
	// Minuto inicial da Janela de mudan�a
	$aMinutos= Array();
	for($i=0;$i<=59;$i++){
		if(strlen($i) == 1) {
        	$minuto = '0'.$i;
        } else {
        	$minuto = $i;
        }
        $aMinutos[$i] = array($minuto, "", $minuto);                          
	}	
	
   // $pagina->LinhaCampoFormulario("Hora Inicial: ", "right", "S", $pagina->CampoSelect("hora_inicio_mudanca", "S", "Hora inicial ", "S", $aHoras), "left");
	//$pagina->LinhaCampoFormulario("Minuto Inicial: ", "right", "S", 	$pagina->CampoSelect("minuto_inicio_mudanca", "S", "Minuto inicial ", "S", $aMinutos), "left");
	
	$pagina->LinhaCampoFormulario("Hor�rio de In�cio: ", "right", "S", 
	$pagina->CampoSelect("hora_inicio_mudanca", "S", "Hora de In�cio ", "N", $aHoras).":".
	$pagina->CampoSelect("minuto_inicio_mudanca", "S", "Minuto de In�cio ", "N", $aMinutos)
	, "left");
	 		 	 
	
	
	// Hora Final da Janela de mudan�a 	 
	//$pagina->LinhaCampoFormulario("Hora Final: ", "right", "S", $pagina->CampoSelect("hora_fim_mudanca", "S", "Hora Final ", "S", $aHoras), "left");
	// Minuto Final da Janela de mudan�a	
	//$pagina->LinhaCampoFormulario("Minuto Final: ", "right", "S", $pagina->CampoSelect("minuto_fim_mudanca", "S", "Minuto Final ", "S", $aMinutos), "left");
	$pagina->LinhaCampoFormulario("Hor�rio de Fim: ", "right", "S", 
	$pagina->CampoSelect("hora_fim_mudanca", "S", "Hora de Fim ", "N", $aHoras).":".
	$pagina->CampoSelect("minuto_fim_mudanca", "S", "Minuto de Final ", "N", $aMinutos)
	, "left");
	
	// Limite para RDM da Janela de mudan�a
	$pagina->LinhaCampoFormulario("Limite para RDM (em minutos):", "right", "S", $pagina->CampoInt("limite_para_rdm", "S", "Limite para RDM", "10", "", ""), "left");
	
	// Servidores
	require 'include/PHP/class/class.servidor.php';
	$servidor = new servidor();	
	$servidor->selectParam("2");	 
	$aServidores = Array();
	$i = 0;
	
	while ($rowServidor = pg_fetch_array($servidor->database->result)){		
		$aServidores[$i][0] = $rowServidor["seq_servidor"];
		$aServidores[$i][1] = "";
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
	
	// Sistemas de informa��o
	require 'include/PHP/class/class.item_configuracao.php';
	$sistemas = new item_configuracao();
	$sistemas->setSEQ_TIPO_ITEM_CONFIGURACAO(2);
	$sistemas->selectParam();	 
	
	$aSistemas = Array();
	$i = 0;
	
	while ($rowSistema = pg_fetch_array($sistemas->database->result)){		 
		//echo $rowSistema["seq_item_configuracao"]."-". $rowSistema["nom_item_configuracao"]."<br>";
		$aSistemas[$i][0] = $rowSistema["seq_item_configuracao"];
		$aSistemas[$i][1] = "";
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
	
	
	$pagina->LinhaColspan("center", "&nbsp;" , "2", "tabelaConteudoHeader"); 
	
	
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Incluir "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Incluir regstro

	$banco->setDsc_janela_mudanca($dsc_janela_mudanca);
	$banco->setDia_semana_inicial($dia_semana_inicial);
	$banco->setHora_inicio_mudanca($hora_inicio_mudanca);
	$banco->setMinuto_inicio_mudanca($minuto_inicio_mudanca);
	$banco->setDia_semana_final($dia_semana_final);
	$banco->setHora_fim_mudanca($hora_fim_mudanca);
	$banco->setMinuto_fim_mudanca($minuto_fim_mudanca);
	$banco->setLimite_para_rdm($limite_para_rdm);
	$banco->insert();
	// C�digo inserido: $banco->SEQ_MOTIVO_CANCELAMENTO
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		// Incluir servidores
		require_once 'include/PHP/class/class.janela_mudanca_servidor.php';
		$janela_mudanca_servidor = new janela_mudanca_servidor();
	    for ($i = 0; $i < count($servidores); $i++){
			$janela_mudanca_servidor->setSeq_janela_mudanca($banco->seq_janela_mudanca);
			$janela_mudanca_servidor->setSeq_servidor($servidores[$i]);
			$janela_mudanca_servidor->insert();
	    }
		
		// Incluir o item configuracao
		require_once 'include/PHP/class/class.janela_mudacao_item_configuracao.php';
		$janela_mudacao_item_configuracao = new janela_mudacao_item_configuracao();
	    for ($i = 0; $i < count($sistemas); $i++){
			$janela_mudacao_item_configuracao->setSeq_janela_mudanca($banco->seq_janela_mudanca);
			$janela_mudacao_item_configuracao->setSeq_item_configuracao($sistemas[$i]);
			$janela_mudacao_item_configuracao->insert();
	    }
		
		$pagina->redirectTo("Janela_MudancaPesquisa.php?seq_janela_mudanca=$banco->seq_janela_mudanca");
	}
}
?>
