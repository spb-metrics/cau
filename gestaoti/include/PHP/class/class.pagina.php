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
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	require_once "../gestaoti/include/PHP/class/class.parametro.php";
	require_once '../gestaoti/include/PHP/GridMetaDados.php';
}else{
	require_once 'include/PHP/class/class.parametro.php';
	require_once 'include/PHP/GridMetaDados.php';
}

// Classe página
// Responsável por montar todas as páginas do sistema
Class Pagina{
	var $titulo;
	var $tipoPagina; // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	var $flagTopo;   // Indica se a página contem topo
	var $flagMenu; 	// Indica se a página contem menu
	var $flagAutetica; // Indica se a página contem autenticação
	var $nomePadraoFuncionalidade;
	var $tituloCabecalho; // Indica o título do cabeçalho da página
	var $flagScriptCalendario;
	var $aItemAba; // Itens do array
	var $cont;
	var $cor;
	var $vPathUploadImagens;
	var $vPathUploadArquivosCEA;
	var $vPathImagens;
	var $action;
	var $target;
	var $method;
	var $ajax;
	var $FlagCorpo;
	var $cea;
	var $vPathPadrao;
	var $estiloTabBar;
	var $remetenteEmailCEA;
	var $enderecoGestaoTI;
	var $enderecoCEA;
	var $labelTopo;
	var $lightbox;
	var $smtpHost;
	var $parametro;
	var $nom_area_ti;
        var $ldap_server;
	var $SEQ_CLASSE_CHAMADO_AR_CONDICIONADO;
        var $SEQ_CLASSE_CHAMADO_CELULAR;
        var $SEQ_CLASSE_CHAMADO_CARIMBO;
        var $SEQ_CLASSE_CHAMADO_CHAVEIRO;
        var $SEQ_CLASSE_CHAMADO_AUDITORIO;
        var $SEQ_CLASSE_CHAMADO_TRANSPORTE;
        var $SEQ_CENTRAL_ATIVIDADES_AUXILIARES;
        var $SEQ_TIPO_OCORRENCIA_DUVIDA;
        var $SEQ_TIPO_OCORRENCIA_INCIDENTE;   	
        var $SEQ_TIPO_OCORRENCIA_SOLICITACAO;
	var $SEQ_TIPO_OCORRENCIA_IMPROCEDENTE;
	var $SEQ_ATIVIDADE_CHAMADO_TRIAGEM_DUVIDA_CAA;
	var $SEQ_ATIVIDADE_CHAMADO_TRIAGEM_INCIDENTE_CAA; 
	var $SEQ_ATIVIDADE_CHAMADO_TRIAGEM_SOLICITACAO_CAA;
	var $COD_UNIDADE_PRESIDENCIA;
	var $COD_UNIDADE_GABINETE_PRESIDENCIA;	
	var $SEQ_CENTRAL_TI;
        var $flg_usar_funcionalidades_patrimonio;

	function Pagina(){
            $this->tipoPagina 		  	= ""; // Não utilizado
            $this->vPathUploadImagens 		= ""; // Não utilizado
            $this->vPathImagens 		= ""; // Não utilizado
            $this->tituloCabecalho 		= "";
            $this->nomePadraoFuncionalidade     = "";
            $this->cor 				= "claro";
            $this->action 			= "";
            $this->target 			= "_self";
            $this->method 			= "get";
            $this->estiloTabBar 		= "tabbar";
            $this->vPathPadrao 			= "";
            $this->aItemAba 			= Array();
            $this->FlagCorpo 			= 1;
            $this->flagTopo 			= 1;   // Indica se a página contem topo
            $this->flagMenu 			= 1; 	// Indica se a página contem menu
            $this->flagAutetica 		= 1; // Indica se a página contem autenticação
            $this->lightbox 			= 0;
            $this->flagScriptCalendario 	= 1;
            $this->cont 			= 0;
            $this->ajax 			= 0;
            $this->cea 				= 0; 

            // Configurações gerais
            $this->parametro = new parametro();
            $this->titulo   		  = $this->parametro->GetValorParametro("titulo");
            $this->smtpHost 		  = $this->parametro->GetValorParametro("smtpHost");
            $this->remetenteEmailCEA  	  = $this->parametro->GetValorParametro("remetenteEmailCEA");
            $this->labelTopo 		  = $this->parametro->GetValorParametro("labelTopo");
            $this->EmailRemetente 	  = $this->parametro->GetValorParametro("EmailRemetente");
            $this->vPathUploadArquivosCEA = $this->parametro->GetValorParametro("vPathUploadArquivos");
            $this->enderecoGestaoTI 	  = $this->parametro->GetValorParametro("enderecoGestaoTI");
            $this->enderecoCEA 		  = $this->parametro->GetValorParametro("enderecoCEA");
            $this->nom_area_ti 		  = $this->parametro->GetValorParametro("NOM_AREA_TI");
            $this->vPathImagens 	  = $this->parametro->GetValorParametro("PATH_IMGS");
            $this->ldap_server            = $this->parametro->GetValorParametro("ldap_server");
            $this->flg_usar_funcionalidades_patrimonio = $this->parametro->GetValorParametro("flg_usar_funcionalidades_patrimonio");


            $this->SEQ_CENTRAL_ATIVIDADES_AUXILIARES 	= $this->parametro->GetValorParametro("SEQ_CENTRAL_ATIVIDADES_AUXILIARES");		 
            $this->SEQ_CLASSE_CHAMADO_AR_CONDICIONADO 	= $this->parametro->GetValorParametro("SEQ_CLASSE_CHAMADO_AR_CONDICIONADO");
            $this->SEQ_CLASSE_CHAMADO_CELULAR 		= $this->parametro->GetValorParametro("SEQ_CLASSE_CHAMADO_CELULAR");
            $this->SEQ_CLASSE_CHAMADO_CARIMBO 		= $this->parametro->GetValorParametro("SEQ_CLASSE_CHAMADO_CARIMBO");
            $this->SEQ_CLASSE_CHAMADO_CHAVEIRO 		= $this->parametro->GetValorParametro("SEQ_CLASSE_CHAMADO_CHAVEIRO");
            $this->SEQ_CLASSE_CHAMADO_AUDITORIO 	= $this->parametro->GetValorParametro("SEQ_CLASSE_CHAMADO_AUDITORIO");
            $this->SEQ_CLASSE_CHAMADO_TRANSPORTE 	= $this->parametro->GetValorParametro("SEQ_CLASSE_CHAMADO_TRANSPORTE");
    	
    	
            $this->SEQ_TIPO_OCORRENCIA_DUVIDA 		= $this->parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_DUVIDA");
            $this->SEQ_TIPO_OCORRENCIA_INCIDENTE	= $this->parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_INCIDENTE");   	
            $this->SEQ_TIPO_OCORRENCIA_SOLICITACAO	= $this->parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_SOLICITACAO");
            $this->SEQ_TIPO_OCORRENCIA_IMPROCEDENTE	= $this->parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_IMPROCEDENTE");

            $this->SEQ_ATIVIDADE_CHAMADO_TRIAGEM_DUVIDA_CAA 		= $this->parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_DUVIDA_CAA");
            $this->SEQ_ATIVIDADE_CHAMADO_TRIAGEM_INCIDENTE_CAA		= $this->parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_INCIDENTE_CAA");   	
            $this->SEQ_ATIVIDADE_CHAMADO_TRIAGEM_SOLICITACAO_CAA	= $this->parametro->GetValorParametro("SEQ_ATIVIDADE_CHAMADO_TRIAGEM_SOLICITACAO_CAA");
		 
            $this->SEQ_CENTRAL_TI 	= $this->parametro->GetValorParametro("SEQ_CENTRAL_TI");	

            $this->COD_UNIDADE_PRESIDENCIA          = $this->parametro->GetValorParametro("COD_UNIDADE_PRESIDENCIA");	
            $this->COD_UNIDADE_GABINETE_PRESIDENCIA = $this->parametro->GetValorParametro("COD_UNIDADE_GABINETE_PRESIDENCIA");	
	}
	// SET =====================================================
	function Settitulo($val){
		$this->titulo = $val;
	}
	function SettipoPagina($val){
		$this->tipoPagina = $val;
	}
	function setflagTopo($val){
		$this->flagTopo = $val;
	}
	function setflagMenu($val){
		$this->flagMenu = $val;
	}
	function setflagAutetica($val){
		$this->flagAutetica = $val;
	}
	function setnomePadraoFuncionalidade($val){
		$this->nomePadraoFuncionalidade = $val;
	}
	function settituloCabecalho($val){
		$this->tituloCabecalho = $val;
	}
	function setflagScriptCalendario($val){
		$this->flagScriptCalendario = $val;
	}
	function setaItemAba($val){
		$this->aItemAba = $val;
	}
	function setAction($val){
		$this->action = $val;
	}
	function setTarget($val){
		$this->target = $val;
	}
	function setMethod($val){
		$this->method = $val;
	}
	function setFlagCorpo($val){
		$this->FlagCorpo = $val;
	}
	// GET =====================================================
	function Gettitulo(){
		return $this->titulo;
	}
	function GettipoPagina(){
		return $this->tipoPagina;
	}
	function GetflagTopo(){
		return $this->flagTopo;
	}
	function GetflagMenu(){
		return $this->flagMenu;
	}
	function GetflagAutetica(){
		return $this->flagAutetica;
	}
	function GetnomePadraoFuncionalidade(){
		return $this->nomePadraoFuncionalidade;
	}
	function GettituloCabecalho(){
		return $this->tituloCabecalho;
	}
	function GetaItemAba(){
		return $this->aItemAba;
	}

	//================================================================================================================
	// Método que monta a página
	//================================================================================================================
	function MontaCabecalho($vMultipart=0, $bodyComplement=""){
		 if($this->flagAutetica == 1){
		 	if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
				require_once '../cau/include/PHP/autentica.php';
			}else{
				require_once 'include/PHP/autentica.php';
			}
		 }  
		 
		if(!strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
			 require_once 'include/PHP/class/class.seguranca.php';
			$seguranca = new Seguranca($_SERVER["SCRIPT_FILENAME"]);
			$seguranca->validarAcesso();
			
		}
		 
		
		 if($this->cea == "1"){
		 	$this->vPathPadrao = $this->parametro->GetValorParametro("vPathPadraoCEA");
		 }else{
		 	$this->vPathPadrao = "";
		 }

		 // Montar início da página
		 ?>
		 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt">

		<head>
			<title><?=$this->titulo?></title>
			<meta http-equiv="Content-Type" content="text/html; charset='iso-8859-1'" />
			
			<link href="<?=$this->vPathPadrao?>include/CSS/CascadeStyleSheet.css" rel="stylesheet" type="text/css" />
			<?
			if ($this->lightbox == 1) {
				?>
				<link rel="stylesheet" href="<?=$this->vPathPadrao?>include/CSS/lightbox.css" media="screen,projection" type="text/css" />
				<script type="text/javascript" src="<?=$this->vPathPadrao?>include/JS/prototype.js"></script>
				<script type="text/javascript" src="<?=$this->vPathPadrao?>include/JS/lightbox.js"></script>
				<?
		    }

			if($this->flagScriptCalendario == 1){ ?>
				<!-- <link rel="STYLESHEET" type="text/css" href="<?=$this->vPathPadrao?>include/CSS/calendar.css"> -->
                                
                                  <!-- calendar stylesheet -->
                                  <link rel="stylesheet" type="text/css" media="all" href="<?=$this->vPathPadrao?>include/JS/jscalendar-0.9.3/calendar-win2k-cold-1.css" title="win2k-cold-1" />

                                  <!-- main calendar program -->
                                  <script type="text/javascript" src="<?=$this->vPathPadrao?>include/JS/jscalendar-0.9.3/calendar.js"></script>

                                  <!-- language for the calendar -->
                                  <script type="text/javascript" src="<?=$this->vPathPadrao?>include/JS/jscalendar-0.9.3/lang/calendar-pt.js"></script>

                                  <!-- the following script defines the Calendar.setup helper function, which makes
                                       adding a calendar a matter of 1 or 2 lines of code. -->
                                  <script type="text/javascript" src="<?=$this->vPathPadrao?>include/JS/jscalendar-0.9.3/calendar-setup.js"></script>
                                
			<?
			}
			?>
			<?
			if($this->flagMenu == 1){
			?>
				<!--
                                <script type="text/javascript" language="JavaScript1.2" src="<?=$this->vPathPadrao?>include/JS/apymenu.js"></script>
				<script type="text/javascript" language="JavaScript1.2" src="<?=$this->vPathPadrao?>include/JS/data1.js"></script>
                                -->
                                <link rel="stylesheet" type="text/css" href="<?=$this->vPathPadrao?>include/CSS/pro_dropdown_2.css" />
                                <script src="<?=$this->vPathPadrao?>include/JS/stuHover.js" type="text/javascript"></script>
			<?
			}
			?>
			<script type="text/javascript" language="JavaScript1.2" src="<?=$this->vPathPadrao?>include/JS/scripts.js"></script>
			<?
			if($this->flagScriptCalendario == 1){ ?>
				<!-- <script language="JScript" src="<?=$this->vPathPadrao?>include/JS/simplecalendar.js"></script> -->
			<?
			}
			?>
		</head>
		<body bottommargin="0" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" <?=$bodyComplement?>>
		<?
			if($vMultipart == 0){
				?><form name="form" action="<?=$this->action?>" method="<?=$this->method?>" target="<?=$this->target?>"><?
			}else{
				?><form name="form" action="" method="post" enctype="multipart/form-data"><?
			}
			if($this->flagTopo == 1){ ?>
				<div id="border-top" class="h_green">
				<div>
						<div>
							<span class="title"><?=$this->labelTopo?></span>
						</div>
					</div>
				</div>
		<? } ?>
		<? if($this->flagMenu == 1){ ?>
			<div id="menu_principal">
			<? require_once 'include/PHP/menu.php'; ?>
			</div>
		<? } ?>
		<? if($this->tituloCabecalho != ""){ ?>
			<div id="submenu_cabecalho">
			  <h1><?=$this->tituloCabecalho?></h1>
			</div>
		<? } ?>
		<? if($this->FlagCorpo == 1){
			?><div id="conteudo"><?
				if(count($this->aItemAba) > 0){ ?>
					<div id="<?=$this->estiloTabBar?>">
						 <ul>
						 <? for($i=0;$i<count($this->aItemAba);$i++){
							 ?><li <?=$this->aItemAba[$i][3]?>><a href="<?=$this->aItemAba[$i][0]?>" class="<?=$this->aItemAba[$i][1]?>"><?=$this->aItemAba[$i][2]?></a></li><?
						    } ?>
						 </ul>
					</div><?
				}
		  }
	}

	// Montar rodapé
	function  MontaRodape(){
		?>
			<div id="border-bottom"><div><div></div></div>
			</div>
			<div id="footer">
				<p class="copyright">
					</p>

			</div>
		</form>
		</body>
		</html>
		<?
	}

	function MontaAbaInterna(){
		$return = "
		<div id=\"tabbarInterna\">
			 <ul> ";
		for($i=0;$i<count($this->aItemAba);$i++){
			$return .= "<li ".$this->aItemAba[$i][4]."><a id=\"".$this->aItemAba[$i][3]."\" href=\"".$this->aItemAba[$i][0]."\" class=\"".$this->aItemAba[$i][1]."\">".$this->aItemAba[$i][2]."</a></li>";
		}
		$return .= " </ul>
		</div> ";
		return $return;
	}

	function ForcaAutenticacao(){
		
		if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
			require_once '../cau/include/PHP/autentica.php';
		}else{
			require_once 'include/PHP/autentica.php';
		}
		
	}

	function segurancaPerfilExclusao($v_SEQ_PERFIL_ACESSO){
		/*TODO: NOVO PERFIL ACESSO*/
		$count = count($v_SEQ_PERFIL_ACESSO);
		for ($i = 0; $i < $count; $i++) {
		    if($v_SEQ_PERFIL_ACESSO[$i][0]== 2){
		    	return true;
		    }
		}
		return false;
		/*TODO: NOVO PERFIL ACESSO*/
//		if($v_SEQ_PERFIL_ACESSO == 2){
//			return true;
//		}else{
//			return false;
//		}
	}

	function segurancaPerfilAlteracao($v_SEQ_PERFIL_ACESSO){
		/*TODO: NOVO PERFIL ACESSO*/
		$count = count($v_SEQ_PERFIL_ACESSO);
		for ($i = 0; $i < $count; $i++) {
			if($v_SEQ_PERFIL_ACESSO[$i][0] == 2 || $v_SEQ_PERFIL_ACESSO[$i][0] == 3 || 
			   $v_SEQ_PERFIL_ACESSO[$i][0] == 4 || $v_SEQ_PERFIL_ACESSO[$i][0] == 5){
		    	return true;
		    }
		}
		return false;
		/*TODO: NOVO PERFIL ACESSO*/
//		if($v_SEQ_PERFIL_ACESSO == 2 || $v_SEQ_PERFIL_ACESSO == 3 || $v_SEQ_PERFIL_ACESSO == 4 || $v_SEQ_PERFIL_ACESSO == 5){
//			return true;
//		}else{
//			return false;
//		}
	}
	
	function autentica(){
		  
	 	if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
			require_once '../cau/include/PHP/autentica.php';
		}else{
			require_once 'include/PHP/autentica.php';
		}
	  
	}

	// Montar alert de mensagem
	function ScriptAlert($msg){
		?><script>alert("<? print $msg ?>");</script><?
	}

	// ==============================================================================================================================
	// Métodos para montar campos dos formulários do sistema ========================================================================
	// ==============================================================================================================================
	function CampoHidden($nome, $valor){
		return "<input type=\"hidden\" name=\"$nome\" value=\"$valor\">";
	}
	function CampoHidden2($nome,   $value ){
		return "<input  type=\"hidden\" name=\"$nome\"  value=\"$value\" id=\"$nome\">";
	}

	function CampoSelect($nome, $obrigatorio, $msgObrigatorio, $flagOptionVazio, $aItemOption, $vValorVazio="Escolha", $vOnchange="", $id="campo_texto" ){
		$retorno = "<select obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" name=\"$nome\" id=\"$id\" onchange=\"$vOnchange\"> \n";
		if($flagOptionVazio == "S"){
			$retorno .= "<option value=\"\">$vValorVazio</option> ";
		}
		for($i=0;$i<count($aItemOption);$i++){
			$retorno .= "<option value=\"".$aItemOption[$i][0]."\" ".$aItemOption[$i][1].">".$aItemOption[$i][2]."</option>";
		}
		$retorno .= "</select>";
		return $retorno;
	}
	
	function CampoSelectMultiple($nome, $obrigatorio, $msgObrigatorio, $flagOptionVazio, $aItemOption, $vValorVazio="Escolha", $vOnchange="", $id="campo_texto", $size="5"){
		$retorno = "<select multiple obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" name=\"$nome\" id=\"$id\" onchange=\"$vOnchange\" size=\"$size\"> \n";
		/*if($flagOptionVazio == "S"){
			$retorno .= "<option value=\"\">$vValorVazio</option> ";
		}*/
		for($i=0;$i<count($aItemOption);$i++){
			$retorno .= "<option value=\"".$aItemOption[$i][0]."\" ".$aItemOption[$i][1].">".$aItemOption[$i][2]."</option>";
		}
		$retorno .= "</select>";
		return $retorno;
	}

	function CampoSelectEvent($nome, $obrigatorio, $msgObrigatorio, $flagOptionVazio, $aItemOption, $acao){
		$retorno = "<select onchange=\"$acao\"  obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" name=\"$nome\" id=\"campo_texto\"> \n";
		if($flagOptionVazio == "S"){
			$retorno .= "<option value=\"\">Escolha</option> ";
		}
		for($i=0;$i<count($aItemOption);$i++){
			$retorno .= "<option value=\"".$aItemOption[$i][0]."\" ".$aItemOption[$i][1].">".$aItemOption[$i][2]."</option>";
		}
		$retorno .= "</select>";
		return $retorno;
	}

	function CampoCheckbox($aItemOption, $nome){
		for($i=0;$i<count($aItemOption);$i++){
			$retorno .= "<input type=\"checkbox\" name=\"$nome\" value=\"".$aItemOption[$i][0]."\" ".$aItemOption[$i][1].">".$aItemOption[$i][2]."<br>";
		}
		return $retorno;
	}

	function CampoCheckboxSimples($nome, $value, $complemento, $label){
		$retorno .= "<input type=\"checkbox\" name=\"$nome\" value=\"".$value."\" ".$complemento.">".$label;
		return $retorno;
	}

	function CampoTexto($nome, $obrigatorio, $msgObrigatorio, $size, $maxlength, $value, $propriedade=""){
		return "<input $propriedade name=\"$nome\" obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" type=\"text\" size=\"$size\" maxlength=\"$maxlength\" value=\"$value\" id=\"campo_texto\">";
	}

	function CampoFile($nome, $obrigatorio, $msgObrigatorio, $size){
		return "<input name=\"$nome\" id=\"$nome\" obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" type=\"file\" size=\"$size\" class=\"campo_file\">";
	}
	function CampoFile2($nome, $obrigatorio, $msgObrigatorio, $size, $value){
		return "<input name=\"$nome\" obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" type=\"file\" size=\"$size\" id=\"campo_texto\" src=\"$value\">";
	}

	function CampoPassword($nome, $obrigatorio, $msgObrigatorio, $size, $maxlength, $value){
		return "<input name=\"$nome\" obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" type=\"password\" size=\"$size\" maxlength=\"$maxlength\" value=\"$value\" id=\"campo_texto\">";
	}

	function CampoData($nome, $obrigatorio, $msgObrigatorio, $value, $complemento=""){
		/*
		return "<input onkeyup=\"preenche(this);\" onblur=\"ValidaData(this)\" obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" type=\"text\" size=\"10\" maxlength=\"10\" name=\"$nome\"  value=\"$value\" id=\"campo_texto\" $complemento>
				<a href=\"javascript: void(0);\"
				   onmouseover=\"if (timeoutId) clearTimeout(timeoutId);window.status='Mostrar Calendário';return true;\"
				   onmouseout=\"if (timeoutDelay) calendarTimeout();window.status='';\"
				   onclick=\"g_Calendar.show(event,'form.$nome','myCallback',false,'dd/mm/yyyy'); return false;\">
				   <img src=\"".$this->vPathPadrao."imagens/calendar.gif\" name=\"imgCalendar\" width=\"34\" height=\"21\" border=\"0\" alt=\"Escolha a data\"></a>";
             * 
             */
            
            return "<input type=\"text\" name=\"$nome\" id=\"f_date_$nome\" readonly=\"1\" size=\"10\" maxlength=\"10\" name=\"$nome\"  value=\"$value\" $complemento  class=\"campo_texto\" /><img src=\"".$this->vPathPadrao."include/JS/jscalendar-0.9.3/img.gif\" id=\"f_trigger_$nome\" style=\"cursor: pointer; border: 0px solid red;\" title=\"Seletor de datas\" onmouseover=\"this.style.background='red';\" onmouseout=\"this.style.background=''\" />
            
            <script type=\"text/javascript\">
                Calendar.setup({
                    inputField     :    \"f_date_$nome\",     // id of the input field
                    ifFormat       :    \"dd/mm/y\",      // format of the input field
                    button         :    \"f_trigger_$nome\",  // trigger for the calendar (button ID)
                    align          :    \"Tl\",           // alignment (defaults to \"Bl\")
                    singleClick    :    true
                });
            </script>
            ";
	}

	function CampoTextArea($nome, $obrigatorio, $msgObrigatorio, $cols, $rows, $value, $complemento=""){
		return "<textarea $complemento name=\"$nome\" obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" cols=\"$cols\" rows=\"$rows\" id=\"campo_texto\">$value</textarea>";
	}

	function CampoHora($nome, $obrigatorio, $msgObrigatorio, $value){
		return "<input id=\"campo_texto\" onblur=\"ValidaHora(this)\" name=\"$nome\" obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"$value\" id=\"campo_texto\" onkeypress=\"valida_horas(this)\">";
	}

	function CampoMoney($nome, $obrigatorio, $msgObrigatorio, $value){
		return "<input id=\"campo_texto\" name=\"$nome\" id=\"$nome\" onKeyUp=\"this.value=limpa_string_decimal(this)\" obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" type=\"text\" size=\"8\" maxlength=\"8\" value=\"$value\">";
	}

	function CampoInt($nome, $obrigatorio, $msgObrigatorio, $vTamanho, $value, $Complemento=""){
		return "<input $Complemento id=\"campo_texto\" name=\"$nome\" obrigatorio=\"$obrigatorio\" msg=\"$msgObrigatorio\" type=\"text\" size=\"$vTamanho\" maxlength=\"$vTamanho\" value=\"$value\" id=\"campo_texto\" onkeypress=\"campo_numerico(this)\">";
	}

	// ==============================================================================================================================
	// Botões =======================================================================================================================
	// ==============================================================================================================================
	function ButtonProcuraEmpregado($vCampoOrigem, $v_UOR_SIGLA="", $v_CAMPO_NOME=""){
		return "<span title=\"Selecionar colaborador\" style=\"cursor: pointer;\" onclick=\"window.open('".$this->vPathPadrao."EmpregadosPesquisaPopupOracle.php?vCampoOrigem=$vCampoOrigem&v_UOR_SIGLA=$v_UOR_SIGLA&v_CAMPO_NOME=$v_CAMPO_NOME', 'procColaborador', 'height=300, width=810, scrollbars=yes, status=yes, resizable=yes, location=no, toolbar=no, menubar=no');\"><img src=\"".$this->vPathPadrao."imagens/procurar.jpg\" name=\"imgProcurar\" border=\"0\" alt=\"Selecionar Colaborador\"></span>";
	}

	function ButtonProcuraRecursoTi($vCampoOrigem, $v_UOR_SIGLA="", $v_CAMPO_NOME=""){
		return "<span title=\"Selecionar Profissional de TI \" style=\"cursor: pointer;\" onclick=\"window.open('".$this->vPathPadrao."Recurso_tiPesquisaPopup.php?vCampoOrigem=$vCampoOrigem&v_UOR_SIGLA=$v_UOR_SIGLA&v_CAMPO_NOME=$v_CAMPO_NOME', 'procColaborador', 'height=300, width=810, scrollbars=yes, status=yes, resizable=yes, location=no, toolbar=no, menubar=no');\"><img src=\"".$this->vPathPadrao."imagens/procurar.jpg\" name=\"imgProcurar\" border=\"0\" alt=\"Selecionar Profissional de TI\"></span>";
	}

	function ButtonLimpar($vCampoOrigem){
		return "<span title=\"Limpar campo\" style=\"cursor: pointer;\" onclick=\"document.form.$vCampoOrigem.value = ''\"><img src=\"imagens/limpar.gif\" name=\"imgLimpar\" border=\"0\" alt=\"Limpar\"></span>";
	}

	function ButtonProcuraUorg($vCampoOrigem, $v_UOR_SIGLA=""){
		return "<span title=\"Selecionar unidades organizacionais\" style=\"cursor: pointer;\" onclick=\"window.open('Unidades_organizacionaisPesquisaPopup.php?vCampoOrigem=$vCampoOrigem&v_UOR_SIGLA=$v_UOR_SIGLA', 'procUorg', 'height=300, width=810, scrollbars=yes, status=yes, resizable=yes, location=no, toolbar=no, menubar=no');\"><img src=\"".$this->vPathPadrao."imagens/procurar.jpg\" name=\"imgProcurar\" border=\"0\" alt=\"Selecionar Unidades Organizacionais\"></span>";
	}

	function ButtonProcuraItemConfiguracao($vCampoNome, $vCampoCodigo, $vCampoCodigoTipo, $v_SEQ_TIPO_ITEM_CONFIGURACAO=""){
		return "<span title=\"Selecionar do parque tecnológico\" style=\"cursor: pointer;\" onclick=\"window.open('Item_configuracaoPesquisaPopup.php?vCampoNome=$vCampoNome&vCampoCodigo=$vCampoCodigo&vCampoCodigoTipo=$vCampoCodigoTipo&v_SEQ_TIPO_ITEM_CONFIGURACAO=$v_SEQ_TIPO_ITEM_CONFIGURACAO', 'procItemConfiguracao', 'height=500, width=810, scrollbars=yes, status=no, location=no, resizable=yes, toolbar=no, menubar=no');\"><img src=\"imagens/procurar.jpg\" name=\"imgProcurar\" border=\"0\" alt=\"Selecionar do parque tecnológico\"></span>";
	}

	function ButtonRetornaValorPopUp($vCampoOrigem, $v_valor){
		return "<span title=\"Selecionar item \" style=\"cursor: pointer;\" onclick=\" window.opener.document.form.$vCampoOrigem.value='$v_valor'; window.opener.document.form.$vCampoOrigem.focus(); window.close();  \"><img src=\"imagens/procurar.jpg\" name=\"imgProcurar\" border=\"0\" alt=\"Selecionar item\"></span>";
	}

	function ButtonRetornaValorPopUpNomeCodigo($vCampoNome, $valorNome, $vCampoCodigo, $valorCodigo){
		return "<span title=\"Selecionar item \" style=\"cursor: pointer;\" onclick=\" window.opener.document.form.$vCampoCodigo.value='$valorCodigo'; window.opener.document.form.$vCampoNome.value='$valorNome'; window.close();  \"><img src=\"imagens/procurar.jpg\" name=\"imgProcurar\" border=\"0\" alt=\"Selecionar item\"></span>";
	}

	function ButtonRetornaValorPopUpNomeCodigoCodigo($vCampoNome, $valorNome, $vCampoCodigo, $valorCodigo, $vCampoCodigo2, $valorCodigo2){
		return "<span title=\"Selecionar item \" style=\"cursor: pointer;\" onclick=\" window.opener.document.form.$vCampoCodigo.value='$valorCodigo'; window.opener.document.form.$vCampoCodigo2.value='$valorCodigo2'; window.opener.document.form.$vCampoNome.value='$valorNome'; window.close();  \"><img src=\"imagens/procurar.jpg\" name=\"imgProcurar\" border=\"0\" alt=\"Selecionar item\"></span>";
	}
	
	function ButtonRetornaValorTemplateRDM($vNomeFuncaoRetorno, $idTemplateRDM){
		return "<span title=\"Selecionar item \" style=\"cursor: pointer;\" onclick=\" window.opener.$vNomeFuncaoRetorno($idTemplateRDM);  window.close();  \"><img src=\"imagens/procurar.jpg\" name=\"imgProcurar\" border=\"0\" alt=\"Selecionar item\"></span>";
	}

	function CampoButton($onclick, $value, $type="submit", $name="enviar", $vComplemento=""){
		return "<input $vComplemento obrigatorio=\"N\" msg=\"\" onclick=\"$onclick\" type=\"$type\" name=\"$name\" value=\"$value\" id=\"campo_texto\">";
	}

	function BotaoTimeSheetGridPesquisa($vDestino){
		return "<a href=\"$vDestino\"><img src=\"imagens/timesheet.gif\" alt=\"Time Sheet\" border=\"0\"></a>";
	}

	function BotaoImprimir($vDestino, $Complemento=""){
		return "<a $Complemento class=\"lbOn\" href=\"$vDestino\"><img src=\"imagens/imprimir.png\" alt=\"Imprimir\" border=\"0\"></a>";
	}

	function BotaoParaCima($vDestino, $alt=""){
		return "<a href=\"$vDestino\"><img src=\"".$this->vPathPadrao."imagens/seta_cima.gif\" alt=\"$alt\" border=\"0\"></a>";
	}

	function BotaoParaBaixo($vDestino, $alt=""){
		return "<a href=\"$vDestino\"><img src=\"".$this->vPathPadrao."imagens/seta_baixo.gif\" alt=\"$alt\" border=\"0\"></a>";
	}

	function BotaoGraficoBarra($vDestino, $IframeName){
		return "<span style=\"cursor: pointer;\" onclick=\"document.getElementById('$IframeName').src = '$vDestino'; \"><img heigth=20 width=20 src=\"".$this->vPathPadrao."imagens/ico_bar.gif\" alt=\"Gráfico de Barras\" title=\"Gráfico de Barras\" border=\"0\"></span>";
	}

	function BotaoGraficoLinha($vDestino, $IframeName){
		return "<span style=\"cursor: pointer;\" onclick=\"document.getElementById('$IframeName').src = '$vDestino'; \"><img heigth=20 width=20 src=\"".$this->vPathPadrao."imagens/ico_line.gif\" alt=\"Gráfico de Linhas\" title=\"Gráfico de Linhas\" border=\"0\"></span>";
	}

	function BotaoGraficoPizza($vDestino, $IframeName){
		return "<span style=\"cursor: pointer;\" onclick=\"document.getElementById('$IframeName').src = '$vDestino'; \"><img heigth=20 width=20 src=\"".$this->vPathPadrao."imagens/ico_pie.jpg\" alt=\"Gráfico de Pizza\" title=\"Gráfico de Pizza\" border=\"0\"></span>";
	}

	function BotaoGraficoPizzaPlus($IframeName){
		return "<span style=\"cursor: pointer;\" onclick=\"ControlaAtualizaGrafico('$IframeName', 'P')\"><img heigth=20 width=20 src=\"".$this->vPathPadrao."imagens/ico_pie.jpg\" alt=\"Gráfico de Pizza\" title=\"Gráfico de Pizza\" border=\"0\"></span>";
	}
	function BotaoGraficoBarraPlus($IframeName){
		return "<span style=\"cursor: pointer;\" onclick=\"ControlaAtualizaGrafico('$IframeName', 'B')\"><img heigth=20 width=20 src=\"".$this->vPathPadrao."imagens/ico_bar.gif\" alt=\"Gráfico de Barras\" title=\"Gráfico de Barras\" border=\"0\"></span>";
	}
	function BotaoGraficoLinhaPlus($IframeName){
		return "<span style=\"cursor: pointer;\" onclick=\"ControlaAtualizaGrafico('$IframeName', 'L')\"><img heigth=20 width=20 src=\"".$this->vPathPadrao."imagens/ico_line.gif\" alt=\"Gráfico de Linhas\" title=\"Gráfico de Linhas\" border=\"0\"></span>";
	}
	function BotaoGraficoMultiLinhaPlus($IframeName){
		return "<span style=\"cursor: pointer;\" onclick=\"ControlaAtualizaGrafico('$IframeName', 'ML')\"><img heigth=20 width=20 src=\"".$this->vPathPadrao."imagens/ico_multline.jpg\" alt=\"Gráfico de Multi Linhas\" title=\"Gráfico de Multi Linhas\" border=\"0\"></span>";
	}


	function BotaoTimeSheetAprovar($vDestino){
		return "<a href=\"$vDestino\"><img src=\"imagens/ponto_verde.gif\" alt=\"Aprovar\" border=\"0\"></a>";
	}
	function BotaoTimeSheetReprovar($vDestino){
		return "<a href=\"$vDestino\"><img src=\"imagens/ponto_red.gif\" alt=\"Reprovar\" border=\"0\"></a>";
	}

	function BotaoAlteraGridPesquisa($vDestino){
		return "<a href=\"$vDestino\"><img src=\"imagens/alterar.gif\" alt=\"Alterar\" border=\"0\"></a>";
	}

	function BotaoLupa($vDestino, $alt){
		return "<a href=\"$vDestino\"><img src=\"imagens/procurar.jpg\" alt=\"$alt\" border=\"0\"></a>";
	}

	function BotaoAltear($vDestino, $alt){
		return "<a href=\"$vDestino\"><img src=\"imagens/alterar.gif\" alt=\"$alt\" border=\"0\"></a>";
	}

	function BotaoAlteraGridPesquisaIframe($vDestino, $alt="Alterar"){
		return "<a href=\"$vDestino\" target=\"_parent\"><img src=\"imagens/alterar.gif\" alt=\"$alt\" border=\"0\"></a>";
	}

	function BotaoExcluiGridPesquisa($vVariavel, $vValor){
		return "<a href=\"#\" onclick=\"fDeletar(document.form.$vVariavel,$vValor);\"><img src=\"imagens/excluir.gif\" alt=\"Excluir\" border=\"0\"></a>";
	}
	function BotaoExcluiGridPesquisa1($vDestino){
		return "<a href=\"$vDestino\"><img src=\"imagens/excluir.gif\" alt=\"Excluir\" border=\"0\"></a>";
	}

	function BotaoAdicionarRegistro($vDestino){
		return "<a href=\"$vDestino\"><img src=\"imagens/adicionar.gif\" alt=\"Adicionar\" border=\"0\"></a>";
	}
	
	function ButtonProcuraChamados($vNomeFuncaoRetorno){
		return "<span title=\"Selecionar Chamados\" style=\"cursor: pointer;\" onclick=\"window.open('ChamadoPesquisaPopup.php?vNomeFuncaoRetorno=$vNomeFuncaoRetorno', 'procItemConfiguracao', 'height=500, width=810, scrollbars=yes, status=no, location=no, resizable=yes, toolbar=no, menubar=no');\"><img src=\"imagens/mais.jpg\" name=\"imgProcurar\" border=\"0\" alt=\"Selecionar Chamados\"></span>";
	}
	function ButtonSelecionarTemplateDeRDM($vNomeFuncaoRetorno){
		return "<span title=\"Selecionar Template de RDM\" style=\"cursor: pointer;\" onclick=\"window.open('RDMTemplatePesquisarPopup.php?vNomeFuncaoRetorno=$vNomeFuncaoRetorno', 'procItemConfiguracao', 'height=500, width=810, scrollbars=yes, status=no, location=no, resizable=yes, toolbar=no, menubar=no');\"><img src=\"imagens/ic_rdm_template.gif\" name=\"ic_rdm_template\" border=\"0\" alt=\"Selecionar Template de RDM\"></span>";
	}
	

	// ==============================================================================================================================
	// Métodos para padronização de laout ===========================================================================================
	// ==============================================================================================================================
	function Imagem($src){
		return "<img src=\"$src\" border=0 />";
	}

	function AbreTabelaPadrao($align, $width, $vComplemento=""){
		?>
		<table align="<?=$align?>" width="<?=$width?>" <?=$vComplemento?>>
		<?
	}
	function AbreTabelaResultado($align, $width){ 
		
		?>
		<table align="<?=$align?>" width="<?=$width?>" border="0" cellspacing="1" cellpadding="1">
		<?
	}
	
	function fMontarExportacao($page,$sql,$lookup,$outString = false){
	   $lookup = str_replace(" ","_",$lookup);
	   
		if(!$outString){
			$value = serialize($sql);
			$value = base64_encode($value);		
			
			$URL = "";	 	
			 	
			?>
				 
			<input  type="hidden" name="<?=$lookup?>"  value="<?=$value?>" id="<?=$lookup?>"/>
			
			<div class="exportlinks" > Opções para exportação de dados:			
			
				<? 
					//$URL = dirname($_SERVER['PHP_SELF'])."/".$page."?lookup=".$lookup."&tipo=XLS"; 
					$URL = dirname($_SERVER['PHP_SELF'])."/".$page;
					if (!strpos($URL, "?")){
		 		 		$URL = $URL ."?";
		 		 	}
		 		 	
		 		 	if (strpos($URL, "&")){
		 		 		$URL = $URL ."&";
		 		 	}
		 		 	
					$URL = $URL . "lookup=".$lookup."&tipo=XLS"; 
				
				?>
				
				<img src="imagens/ico_file_excel.png" style="cursor:hand;" border=0 onclick="javascript:fExportGrid('<?=$URL?>');" /> Excel | 
			 	 
				<? 
					//$URL = dirname($_SERVER['PHP_SELF'])."/".$page."?lookup=".$lookup."&tipo=PDF"; 
					$URL = "";
					$URL = dirname($_SERVER['PHP_SELF'])."/".$page;
					
					if (!strpos($URL, "?")){
		 		 		$URL = $URL ."?";
		 		 	}
		 		 	
		 		 	if (strpos($URL, "&")){
		 		 		$URL = $URL ."&";
		 		 	}
		 		 	
					$URL = $URL . "lookup=".$lookup."&tipo=PDF"; 
					
					
				?>
				<img src="imagens/ico_file_pdf.png" style="cursor:hand;"  border=0 onclick="javascript:fExportGrid('<?=$URL?>');" /> PDF					
			 
			</div>
			<?	 
		}else if($outString){
			$value = serialize($sql);
			$value = base64_encode($value);	
			
			$RET = "";
			$RET .=	"<input  type=\"hidden\" name=\"".$lookup."\"  value=\"".$value."\" id=\"".$lookup."\" />";
			$RET .= "<div class=\"exportlinks\" > Opções para exportação de dados:"	;
			
			$URL = dirname($_SERVER['PHP_SELF'])."/".$page;
			if (!strpos($URL, "?")){
 		 		$URL = $URL ."?";
 		 	}
 		 	
 		 	if (strpos($URL, "&")){
 		 		$URL = $URL ."&";
 		 	}
 		 	
			$URL = $URL . "lookup=".$lookup."&tipo=XLS"; 
			
			$RET .= "<img src=\"imagens/ico_file_excel.png\" style=\"cursor:hand;\" border=0 onclick=\"javascript:fExportGrid('".$URL."');\" /> Excel | ";

			$URL = "";
			$URL = dirname($_SERVER['PHP_SELF'])."/".$page;
			
			if (!strpos($URL, "?")){
 		 		$URL = $URL ."?";
 		 	}
 		 	
 		 	if (strpos($URL, "&")){
 		 		$URL = $URL ."&";
 		 	}
 		 	
			$URL = $URL . "lookup=".$lookup."&tipo=PDF"; 
			
			$RET .= "<img src=\"imagens/ico_file_pdf.png\" style=\"cursor:hand;\"  border=0 onclick=\"javascript:fExportGrid('".$URL."');\" /> PDF ";
			$RET .= "</div>";
			
			return $RET;
		}
		
	}
	
	
	 
	function FechaTabelaPadrao(){
		?></table><? 
	}

	function LinhaCampoFormulario($titulo, $tituloAlign, $obrigatorio, $conteudoCampo, $conteudoAlign, $id="", $wid1="", $wid2=""){
		?>
		<tr <?=$id?>>
			<td align="<?=$tituloAlign?>" id="label" width="<?=$wid1?>">
			<? if($obrigatorio == "S"){ ?>
					<font color="#FF0000">*</font>
			<? } ?>
			<?=$titulo?>
			</td>
			<td id="campo" align="<?=$conteudoAlign?>"  width="<?=$wid2?>"><?=$conteudoCampo?></td>
		</tr>
		<?
	}

	function fIncluiEspacos($Qtd){
		$vRetorno = "";
		for($i=0;$i<$Qtd;$i++){
			$vRetorno .= "&nbsp;&nbsp;&nbsp;";
		}
		return $vRetorno;
	}

	function LinhaCampoFormularioColspan($align, $conteudoCampo, $colspan){
		?>
		<tr align="<?=$align?>"><td colspan="<?=$colspan?>"><?=$conteudoCampo?></td></tr>
		<?
	}

	function LinhaCampoFormularioColspanDestaque($conteudoCampo, $colspan, $style=""){
		?>
		<tr <?=$style?> id="header"><td height="30" valign="middle" colspan="<?=$colspan?>"><div align="left"><strong><font size=2><?=$conteudoCampo?></strong></font></div></td></tr>
		<?
	}
	function LinhaCampoFormularioColspanDestaqueRight($conteudoCampo, $colspan, $style=""){
		?>
		<tr <?=$style?> id="header"><td height="30" valign="middle" colspan="<?=$colspan?>"><div align="right"><strong><font size=2><?=$conteudoCampo?></strong></font></div></td></tr>
		<?
	}
	function LinhaCampoFormularioColspanDestaqueCenter($conteudoCampo, $colspan, $style=""){
		?>
		<tr <?=$style?> id="header"><td height="30" valign="middle" colspan="<?=$colspan?>"><div align="center"><strong><font size=2><?=$conteudoCampo?></strong></font></div></td></tr>
		<?
	}
	

	function LinhaColspan($align, $conteudoCampo, $colspan, $id){
		?>
		<tr align="<?=$align?>"><td colspan="<?=$colspan?>" id="<?=$id?>"><?=$conteudoCampo?></td></tr>
		<?
	}

	function LinhaHeaderTabelaResultado($titulo, $aTitulo){
		 
		if($titulo != ""){
			?>
			<tr>
			   <td colspan="<?=count($aTitulo)?>" id="header"><?=$titulo?></td>
		    </tr>
		    <tr>
			<?
		}
		for($i=0;$i<count($aTitulo);$i++){
			?><td id="header" width="<?=$aTitulo[$i][1]?>"><?=$aTitulo[$i][0]?></td><?
		}
		?>
	    </tr>
		<?
	}

	function LinhaHeaderTextoVertical($titulo, $aTitulo){
		?>
		<tr>
		   <td colspan="<?=count($aTitulo)?>" id="header"><?=$titulo?></td>
	    </tr>
	    <tr>
		<?
		for($i=0;$i<count($aTitulo);$i++){
			if($i == 0){
				?><td valign="middle" id="header" width="<?=$aTitulo[$i][1]?>"><?=$aTitulo[$i][0]?></td><?
			}else{
				?><td valign="bottom" id="header" width="<?=$aTitulo[$i][1]?>"><div align="left" id="textoempeh"><?=$aTitulo[$i][0]?></div></td><?
			}
		}
		?>
	    </tr>
		<?
	}
	function IFrame($name, $src, $width, $height, $scrolling="auto"){
		return "<IFRAME name=\"$name\" src=\"$src\" width=\"$width\" height=\"$height\" scrolling=\"$scrolling\" frameBorder=\"0\" allowtransparency=\"true\"></IFRAME>";
	}
	
	function IFrame2($name, $src, $width, $height, $scrolling="auto"){
		return "<IFRAME name=\"$name\" id=\"$name\" src=\"$src\" width=\"$width\" height=\"$height\" scrolling=\"$scrolling\" frameBorder=\"0\" allowtransparency=\"true\"></IFRAME>";
	}

	function GetIdTable(){
		if($this->cont % 2 == 0) $cor = "claro";
		else $cor = "escuro";
		$this->cont++;
		return $cor;
	}

	function NovaLinhaGridPesquisa(){
		if($this->cont % 2 == 0) $cor = "claro";
		else $cor = "escuro";
		?><tr id="<?print $cor?>"><?
		$this->cont++;
	}

	function LinhaTabelaResultado($aConteudo, $cont=0, $conplemento=""){
		if($this->cont % 2 == 0) $cor = "claro";
		else $cor = "escuro";
		?>
	    <tr id="<?=$cor?>" <?=$conplemento?>>
			<?
			for($i=0;$i<count($aConteudo);$i++){
				?><td align="<?=$aConteudo[$i][0]?>" id="<?=$aConteudo[$i][1]?>"><?=$aConteudo[$i][2]?></td><?
			}
			?>
	    </tr>
		<?
		$this->cont++;		
		 
	}

	function LinhaVazia($quantidade){
		for($i=0;$i<$quantidade;$i++){
			print "<br>";
		}
	}

	function comboSimNao($vSelected="",$bLinha1=true){
		$aItemOption = Array();
		$aItemOption[] = array("S", $this->iif($vSelected == "S","Selected", ""), "Sim");
		$aItemOption[] = array("N", $this->iif($vSelected == "N","Selected", ""), "Não");
		return $aItemOption;
	}

	function mensagem($vMensagem, $cea=""){
		// Configuração da págína
		$this->SettituloCabecalho("Mensagem");
		$this->MontaCabecalho(0, $cea);
		print "<br>".$vMensagem;
		print "<br><br><a href=\"javascript:history.back(1) \">Voltar</a>";
		$this->MontaRodape();
	}

	// ==============================================================================================================================
	// Métodos úteis ================================================================================================================
	// ==============================================================================================================================

	//================================================================================================================================
	//      Função: redirectTo
	//      Descrição: REdireciona a página para a página espacificada
	//================================================================================================================================
	function redirectTo($newUrl){
	 // TODO: protocol possibilities need to be extended to indclude any other
	 // desried protocols - ftp:// mnp:// whatever...

	 $newUrl = trim($newUrl);
	 if (!(strpos($newUrl, "http://") === 0 || strpos($newUrl, "https://") === 0))
	 {
	     $newUrl = "http"
	             . ($_SERVER['HTTPS'] == "on" ? "s" : "")
	             . "://"
	             . $_SERVER['HTTP_HOST']
	             . (strpos($newUrl, "/") === 0? $newUrl : dirname($_SERVER['PHP_SELF']) ."/". $newUrl);
	 }

	 header("Location: " . $newUrl);
	exit();
	}

	function redirectToJS($newUrl){
		?>
		<script language="javascript">
			window.location.href="<?=$newUrl?>";
		</script>
		<?
	}

	//================================================================================================================================
	//      Função: ConvDataHoraT
	//      Descrição: converte Data MM_DD_AAAA hh:mm:ss para AAAA/MM/DD hh:mm:ss
	//================================================================================================================================
	   function ConvDataHoraT($mData){
			// Format data Inicio
			$mes = substr($mData,5,2);
			$dia = substr($mData,8,2);
			$ano = substr($mData,0,4);
			$hora = substr($mData,11);

			$dataHora = $dia."/".$mes."/".$ano." ".$hora;
			if ($dataHora == "// ") {
				$dataHora = "";
			}
			return $dataHora;
	  }

	//================================================================================================================================
	//      Função: ConvDataMDA_DMA
	//      Descrição: converte Data MM_DD_AAAA para AAAA_MM_DD
	//================================================================================================================================
	   function ConvDataMDA_DMA($mData, $mSeparador="-"){
	      $mMes = substr($mData,0,2);
	      $mDia = substr($mData,2,2);
	      $mAno = substr($mData,4,4);
		  return ($mDia.$mSeparador.$mMes.$mSeparador.$mAno);
	  }
	//================================================================================================================================
	//      Função: ConvDataAMD
	//      Descrição: Converte Data de DD-MM-AAAA para AAAA-MM-DD
	//================================================================================================================================
	   function ConvDataAMD($mData, $mSeparador="-")
	   {
	      if (empty($mData))
		  {
		     $mDia = "00";
		     $mMes = "00";
		     $mAno = "0000";
		  }
		  else
		  {
		     $mDia = substr($mData,0,2);
		     $mMes = substr($mData,3,2);
		     $mAno = substr($mData,6,4);
		  }
	      return ($mAno.$mSeparador.$mMes.$mSeparador.$mDia);                       // retorna $Val com valor atribuido
	   }

	//================================================================================================================================
	//      Função: ConvDataMDA_DMA
	//      Descrição: Converte Data de AAAA-MM-DD  para DD-MM-AAAA
	//================================================================================================================================
	   function ConvDataDMA($mData, $mSeparador="-"){
	      if (empty($mData)){
		     $mDia = "";
		     $mMes = "";
		     $mAno = "";
		  } else{
		     $mDia = substr($mData,8,2);
		     $mMes = substr($mData,5,2);
		     $mAno = substr($mData,0,4);
		  }
		  // retorna $Val com valor atribuido
	      return ($mDia.$mSeparador.$mMes.$mSeparador.$mAno);
	   }

	// ===================================== Função PARA CRIPTOGRAFAR SENHA ==================================
	function fEncriptSenha($vSenha){
		$vSenhaTemp = $vSenha;
		if($vTamanho < 10){
		    for ($i=$vTamanho; strlen($vSenhaTemp)<10; $i++){
				$vSenhaTemp = $vSenhaTemp . " ";
			}
		}
		$vTamanho = strlen($vSenhaTemp);
		$vSenhaTemp = strtoupper($vSenhaTemp);
		$vContador = 0;
		while ($vContador < 10){
		    if ($vContador == 0){
				$vFator = ord(substr($vSenhaTemp, $vContador, 1)) / 2;
		    }
		    $vAscii = ord( substr($vSenhaTemp, $vContador ,1) ) + $vFator;
			if(chr($vAscii) == "\\") $vCodSen = $vCodSen . "|";
		    else $vCodSen = $vCodSen . chr($vAscii);
		    $vFator = $vFator + 2;
		    $vContador = $vContador + 1;
		}
	    return $vCodSen;
	}

	// ======================================= Função que gera senha =========================================================
	function fGeraSenha($quantidade, $in_numeros, $in_letras){
	  // Faixa de números em ASCii de 48 a 57
	  // Faixa de letras maiusculas - de 65 a 90
	  $Senha = "";
	  $qtNumeros = 0;
	  $qtLetras = 0;
	  if ($in_numeros == "S" && $in_letras == "S"){
	  	 if($quantidade%2 == 0){
	  	    $qtNumeros = $quantidade/2;
		    $qtLetras  = $quantidade/2;
		 } else{
		 	$qtNumeros = round($quantidade/2);
			$qtLetras  = round($quantidade/2)-1;
		 }
	  }

	  for($i=0;$i<$quantidade;$i++){
		 if ($in_numeros == "S" && $in_letras == "S"){
		  	 if($i%2 == 0 && $qtNumeros>0){
			 	$Senha .= chr(rand(48,57));
				$qtNumeros--;
			 }else{
			 	$Senha .= chr(rand(65,90));
				$qtLetras--;
			 }
		  } else{
		 	 if ($in_numeros == "S"){
			 	$Senha .= chr(rand(48,57));
			 }
			 if ($in_letras == "S"){
			 	$Senha .= chr(rand(65,90));
			 }
		  }
	  }
	  return $Senha;
	}

	function add_time2 ($add,$data=false,$formato="d/m/Y H:i:s"){
	   if ($data) $data = strtotime($this->ConvDataAMD($data));
	   if ($data) return date ($formato, mktime(
		date("H",$data),
		date("i",$data),
		date("s",$data),
		date("m",$data),
	    (date("d",$data)+$add),
		date("y",$data)
	    ));
	   else return date ($formato, strtotime ("+".$add." day"));
	}

	function add_time ($add,$data=false){
	   if ($data) $data = strtotime($this->ConvDataAMD($data));
	   if ($data) return date ("d/m/Y", mktime(
		date("H",$data),
		date("i",$data),
		date("s",$data),
		date("m",$data),
	    (date("d",$data)+$add),
		date("y",$data)
	    ));
	   else return date ("Y-m-d", strtotime ("+".$add." day"));
	}

	function add_minutos ($add,$data=false,$formato="d/m/Y H:i:s"){
	   if ($data) $data = strtotime($this->ConvDataAMD($data)." ".substr($data,11,8));

	   if ($data) return date ($formato, mktime(
		date("H",$data),
		(date("i",$data)+$add),
		date("s",$data),
		date("m",$data),
	    date("d",$data),
		date("y",$data)
	    ));

	   else return date ($formato, strtotime ("+".$add." minutes"));
	}

	function add_minutos_uteis($addMinutos, $DataInicio, $horaIniExpediente, $horaIniIntervalo, $horaFimIntervalo, $horaFimExpediente, $feriados){

		//print "<br><b>DataInicio = $DataInicio  | addMinutos = $addMinutos | horas uteis => ".($addMinutos/60)."</b><br>".chr(13);
		// Iniciar a data de retorno
		$dataTrabalho = strtotime($this->ConvDataAMD($DataInicio)." ".substr($DataInicio,11,8));
		$dataFinal = $dataTrabalho;

		// Calcular a quantidade de horas úteis no dia
		$periodoutil1 = $this->dateDiffHourPlus(substr($DataInicio,0,10)." ".$horaIniExpediente, substr($DataInicio,0,10)." ".$horaIniIntervalo) / 60;
		$periodoutil2 = $this->dateDiffHourPlus(substr($DataInicio,0,10)." ".$horaFimIntervalo, substr($DataInicio,0,10)." ".$horaFimExpediente) / 60;
		//print "periodoutil1 = ".$periodoutil1;
		//print "<br>periodoutil2 = ".$periodoutil2;

		while($addMinutos > 0){
			// Verificar se a data de início é dia útil
			if(date("N", $dataFinal) < 6) { // Se sábado, pular para o próximo dia de manhã
				// =======================================================================================
				// Verificar se o dia é feriado
				if($this->arrayFind($feriados, substr(date("d/m/Y H:i:s", $dataFinal),0,10)) == 1){
					//print "<br><br>Feriado = ".add_time(1,date("d/m/Y",$dataFinal)).chr(13);
					$dataFinal = strtotime($this->ConvDataAMD($this->add_time(1,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
				}else{
					//print "<br>entrou<br>";
					// =======================================================================================
					// Verificar se a hora é anterior ao inicio do expediente
					$aux = $this->dateDiffHourPlus(date("d/m/Y H:i:s", $dataFinal), substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaIniExpediente) / 60;
					if($aux >=0 && $addMinutos > 0){
						// Pular para o fim do intervalo
						if($addMinutos > $periodoutil1){
							$dataFinal = strtotime($this->ConvDataAMD(date("d/m/Y",$dataFinal))." ".$horaFimIntervalo);
							$addMinutos -= $periodoutil1;
						}else{
							$dtAux = $this->add_minutos($addMinutos,date("d/m/Y H:i:s",$dataFinal));
							$dataFinal = strtotime($this->ConvDataAMD($dtAux)." ".substr($dtAux,11,8));
							$addMinutos = 0;
						}
						//print "<br><br>anterior ao inicio do expediente = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos." | horas uteis => ".($addMinutos/60)."".chr(13);
					}


					// =======================================================================================
					// Verificar se a hora é anterior ao inicio do intervalo
					$aux = $this->dateDiffHourPlus(date("d/m/Y H:i:s", $dataFinal), substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaIniIntervalo) / 60;
					if($aux >=0 && $addMinutos > 0){
						// Pular para o fim do intervalo
						if($addMinutos > $aux){
							$dataFinal = strtotime($this->ConvDataAMD(date("d/m/Y",$dataFinal))." ".$horaFimIntervalo);
							$addMinutos -= $aux;
						}else{
							$dtAux = $this->add_minutos($addMinutos,date("d/m/Y H:i:s",$dataFinal));
							$dataFinal = strtotime($this->ConvDataAMD($dtAux)." ".substr($dtAux,11,8));
							$addMinutos = 0;
						}
						//print "<br><br>anterior ao inicio do intervalo = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos." | horas uteis => ".($addMinutos/60)."".chr(13);
					}

					// =======================================================================================
					// Verificar se a hora é anterior ao fim do intervalo
					$aux = $this->dateDiffHourPlus(date("d/m/Y H:i:s", $dataFinal), substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaFimIntervalo) / 60;
					if($aux >=0 && $addMinutos > 0){
						// Pular para o fim do intervalo
						$dataFinal = strtotime($this->ConvDataAMD(date("d/m/Y",$dataFinal))." ".$horaFimIntervalo);
						//print "<br><br>anterior ao fim do intervalo = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos."".chr(13);
					}

					// =======================================================================================
					// Verificar se a hora é anterior ao fim do Expediente
					$aux = $this->dateDiffHourPlus(date("d/m/Y H:i:s", $dataFinal), substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaFimExpediente) / 60;
					if($aux >=0 && $addMinutos > 0){
						// Pular para o dia seguinte
						if($addMinutos > $periodoutil2){
							$dataFinal = strtotime($this->ConvDataAMD($this->add_time(1,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
							//$addMinutos -= $periodoutil2;
							$addMinutos -= $aux;
						}else{
							$dtAux = $this->add_minutos($addMinutos,date("d/m/Y H:i:s",$dataFinal));
							$dataFinal = strtotime($this->ConvDataAMD($dtAux)." ".substr($dtAux,11,8));
							$addMinutos = 0;
						}
						//print "<br><br>anterior ao fim do Expediente = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos." | horas uteis => ".($addMinutos/60)."".chr(13);
					}

					// =======================================================================================
					// Verificar se a hora é posterior ao fim do Expediente
					$aux = $this->dateDiffHourPlus(substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaFimExpediente, date("d/m/Y H:i:s", $dataFinal)) / 60;
					//print "<br>aux=$aux";
					if($aux >=0 && $addMinutos > 0){
						$dataFinal = strtotime($this->ConvDataAMD($this->add_time(1,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
						//print "<br><br>posterior ao fim do Expediente = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos." | horas uteis => ".($addMinutos/60)."".chr(13);
					}
				}
			}
			// =======================================================================================
			// Se o dia inicial for sábado
			if(date("N", $dataFinal) == 6) { // Se sábado, pular para segunda de manhã
				//print "<br><br>Sabado = ".add_time(2,date("d/m/Y",$dataFinal)).chr(13);
				$dataFinal = strtotime($this->ConvDataAMD($this->add_time(2,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
			}

			// =======================================================================================
			// Se o dia inicial for domingo
			if(date("N", $dataFinal) == 7) { // Se sábado, pular para segunda de manhã
				//print "<br><br>Domingo = ".add_time(1,date("d/m/Y",$dataFinal)).chr(13);
				$dataFinal = strtotime($this->ConvDataAMD($this->add_time(1,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
			}

			// Data preparada no padrão -> Próximo dia útil as 08:00 - Pronto para o loop
			//print "<br><br><b>".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos."</b>".chr(13);
		} // while
		return date("d/m/Y H:i:s",$dataFinal);
	}

	function arrayFind($array, $find){
		$aux = 0;
		for($i = 0; $i < count($array); $i++){
			if($array[$i] == $find){
			//	print "<br> >>>>>>>>>>>>>>>>>".$array[$i]." - ".$find.chr(13);
				$aux = 1;
			}
		}
		//print "<br> >>>>".$aux.chr(13);
		return $aux;
	}

	function dateDiff($dia, $mes, $ano){
		$day = $dia;
		$month = $mes;
		$year = $ano;
		return (int)((mktime (0,0,0,$month,$day,$year) - time(void))/86400);
	}

	function dateDiffHour($data){
		$data = strtotime($this->ConvDataAMD($data)." ".substr($data,11,8));
		return
					(mktime( date("H",$data),
							date("i",$data),
							date("s",$data),
							date("m",$data),
			    			date("d",$data),
							date("y",$data)
			    		  ) - time(void))/86400;
	}

	function dateDiffHourPlus($data1, $data2){
		$data1 = strtotime($this->ConvDataAMD($data1)." ".substr($data1,11,8));
		$data2 = strtotime($this->ConvDataAMD($data2)." ".substr($data2,11,8));
		$retorno = $data2 - $data1;
/*
		$retorno = (mktime( date("H",$data1),
							 date("i",$data1),
							 date("s",$data1),
							 date("m",$data1),
			    			 date("d",$data1),
							 date("y",$data1)
			    		   )
					 -
					 mktime( date("H",$data2),
							 date("i",$data2),
							 date("s",$data2),
							 date("m",$data2),
			    			 date("d",$data2),
							 date("y",$data2)
			    		  )
					);
		print ">>".$retorno;
*/
		//print "<br>data1=$data1 | data2=$data2 | retorno=".$retorno;
		return $retorno;
	}

	function dateDiffMinutes($data1, $data2){
		$data1 = strtotime($this->ConvDataAMD($data1)." ".substr($data1,11,8));
		$data2 = strtotime($this->ConvDataAMD($data2)." ".substr($data2,11,8));
		$retorno = $data2 - $data1;
		return $retorno/60;
		//return date("i",$retorno);
	}

	function dateDiffMinutosUteis($DataInicio, $DataFim, $horaIniExpediente, $horaIniIntervalo, $horaFimIntervalo, $horaFimExpediente, $feriados){
		$v_QTD_MINUTOS_UTEIS = 0;
		$addMinutos = $this->dateDiffMinutes($DataInicio, $DataFim);
		//print " <br>========================================================================
		//		<br>Início da função dateDiffMinutosUteis
		//		<br> DataInicio=$DataInicio | DataFim=$DataFim | addMinutos=$addMinutos ";
		// Iniciar a data de retorno
		$dataTrabalho = strtotime($this->ConvDataAMD($DataInicio)." ".substr($DataInicio,11,8));
		$dataFinal = $dataTrabalho;

		// Calcular a quantidade de horas úteis no dia
		$periodoutil1 = $this->dateDiffHourPlus(substr($DataInicio,0,10)." ".$horaIniExpediente, substr($DataInicio,0,10)." ".$horaIniIntervalo) / 60;
		$periodoutil2 = $this->dateDiffHourPlus(substr($DataInicio,0,10)." ".$horaFimIntervalo, substr($DataInicio,0,10)." ".$horaFimExpediente) / 60;
		//print "<br>periodoutil1 = ".$periodoutil1;
		//print "<br>periodoutil2 = ".$periodoutil2;

		while($addMinutos > 0){
			// Verificar se a data de início é dia útil
			if(date("N", $dataFinal) < 6) { // Se sábado, pular para o próximo dia de manhã
				// =======================================================================================
				// Verificar se o dia é feriado
				if($this->arrayFind($feriados, substr(date("d/m/Y H:i:s", $dataFinal),0,10)) == 1){
					//print "<br><br>Feriado = ".add_time(1,date("d/m/Y",$dataFinal)).chr(13);
					$addMinutos -= $this->dateDiffMinutes($dataFinal, $this->add_time(1,date("d/m/Y",$dataFinal)));
					$dataFinal = strtotime($this->ConvDataAMD($this->add_time(1,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
				}else{
					// =======================================================================================
					// Verificar se a hora é anterior ao inicio do expediente
					$aux = $this->dateDiffHourPlus(date("d/m/Y H:i:s", $dataFinal), substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaIniExpediente) / 60;
					if($aux >=0 && $addMinutos > 0){
						// Pular para o fim do intervalo
						if($addMinutos > $periodoutil1){
							$dataFinal = strtotime($this->ConvDataAMD(date("d/m/Y",$dataFinal))." ".$horaFimIntervalo);
							$addMinutos -= $periodoutil1;
							$v_QTD_MINUTOS_UTEIS += $periodoutil1;
						}else{
							$dtAux = $this->add_minutos($addMinutos,date("d/m/Y H:i:s",$dataFinal));
							$dataFinal = strtotime($this->ConvDataAMD($dtAux)." ".substr($dtAux,11,8));
							$v_QTD_MINUTOS_UTEIS += $addMinutos;
							$addMinutos = 0;
						}
						//print "<br><br>anterior ao inicio do expediente = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos." | v_QTD_MINUTOS_UTEIS => $v_QTD_MINUTOS_UTEIS";
					}


					// =======================================================================================
					// Verificar se a hora é anterior ao inicio do intervalo
					$aux = $this->dateDiffHourPlus(date("d/m/Y H:i:s", $dataFinal), substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaIniIntervalo) / 60;
					if($aux >=0 && $addMinutos > 0){
						// Pular para o fim do intervalo
						if($addMinutos > $aux){
							$dataFinal = strtotime($this->ConvDataAMD(date("d/m/Y",$dataFinal))." ".$horaFimIntervalo);
							$addMinutos -= $aux;
							$v_QTD_MINUTOS_UTEIS += $aux;
						}else{
							$dtAux = $this->add_minutos($addMinutos,date("d/m/Y H:i:s",$dataFinal));
							$dataFinal = strtotime($this->ConvDataAMD($dtAux)." ".substr($dtAux,11,8));
							$v_QTD_MINUTOS_UTEIS += $addMinutos;
							$addMinutos = 0;
						}
						//print "<br><br>anterior ao inicio do intervalo = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos." | v_QTD_MINUTOS_UTEIS => $v_QTD_MINUTOS_UTEIS";
					}

					// =======================================================================================
					// Verificar se a hora é anterior ao fim do intervalo
					$aux = $this->dateDiffHourPlus(date("d/m/Y H:i:s", $dataFinal), substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaFimIntervalo) / 60;
					if($aux >=0 && $addMinutos > 0){
						// Pular para o fim do intervalo
						$addMinutos -= $this->dateDiffMinutes(date("d/m/Y",$dataFinal)." ".$horaIniIntervalo, date("d/m/Y",$dataFinal)." ".$horaFimIntervalo);
						$dataFinal = strtotime($this->ConvDataAMD(date("d/m/Y",$dataFinal))." ".$horaFimIntervalo);
						//print "<br><br>anterior ao fim do intervalo = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos."".chr(13)." | v_QTD_MINUTOS_UTEIS => $v_QTD_MINUTOS_UTEIS";;
					}

					// =======================================================================================
					// Verificar se a hora é anterior ao fim do Expediente
					$aux = $this->dateDiffHourPlus(date("d/m/Y H:i:s", $dataFinal), substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaFimExpediente) / 60;
					if($aux >=0 && $addMinutos > 0){
						// Pular para o dia seguinte
						if($addMinutos > $periodoutil2){
							$dataFinal = strtotime($this->ConvDataAMD($this->add_time(1,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
							$addMinutos -= $periodoutil2;
							//print "<br>periodoutil2=$periodoutil2";
							$v_QTD_MINUTOS_UTEIS += $periodoutil2;
						}else{
							$dtAux = $this->add_minutos($addMinutos,date("d/m/Y H:i:s",$dataFinal));
							$dataFinal = strtotime($this->ConvDataAMD($dtAux)." ".substr($dtAux,11,8));
							$v_QTD_MINUTOS_UTEIS += $addMinutos;
							$addMinutos = 0;
						}
						//print "<br><br>anterior ao fim do Expediente = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos." | v_QTD_MINUTOS_UTEIS => $v_QTD_MINUTOS_UTEIS";
					}

					// =======================================================================================
					// Verificar se a hora é posterior ao fim do Expediente
					$aux = $this->dateDiffHourPlus(substr(date("d/m/Y H:i:s", $dataFinal),0,10)." ".$horaFimExpediente, date("d/m/Y H:i:s", $dataFinal)) / 60;
					//print "<br>aux=$aux";
					if($aux >=0 && $addMinutos > 0){
						$addMinutos -= $this->dateDiffMinutes(date("d/m/Y H:i:s",$dataFinal), $this->add_time(1,date("d/m/Y",$dataFinal))." ".$horaIniExpediente);
						$dataFinal = strtotime($this->ConvDataAMD($this->add_time(1,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
						//print "<br><br>posterior ao fim do Expediente = ".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos." | v_QTD_MINUTOS_UTEIS => $v_QTD_MINUTOS_UTEIS";
					}
				}
			}
			// =======================================================================================
			// Se o dia inicial for sábado
			if(date("N", $dataFinal) == 6) { // Se sábado, pular para segunda de manhã
				//print "<br><br>Sabado = ".add_time(2,date("d/m/Y",$dataFinal)).chr(13);
				$addMinutos -= $this->dateDiffMinutes(date("d/m/Y H:i:s",$dataFinal), $this->add_time(2,date("d/m/Y",$dataFinal))." ".$horaIniExpediente);
				$dataFinal = strtotime($this->ConvDataAMD($this->add_time(2,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
			}

			// =======================================================================================
			// Se o dia inicial for domingo
			if(date("N", $dataFinal) == 7) { // Se sábado, pular para segunda de manhã
				//print "<br><br>Domingo = ".add_time(1,date("d/m/Y",$dataFinal)).chr(13);
				$addMinutos -= $this->dateDiffMinutes(date("d/m/Y H:i:s",$dataFinal), $this->add_time(1,date("d/m/Y",$dataFinal))." ".$horaIniExpediente);
				$dataFinal = strtotime($this->ConvDataAMD($this->add_time(1,date("d/m/Y",$dataFinal)))." ".$horaIniExpediente);
			}

			// Data preparada no padrão -> Próximo dia útil as 08:00 - Pronto para o loop
			//print "<br><br><b>".date("d/m/Y H:i:s",$dataFinal)." - Minutos uteis restantes = ".$addMinutos."</b>".chr(13);

		} // while
		//print "<br>========================== SAINDO ==========================================<br>";
		return $v_QTD_MINUTOS_UTEIS;
	}

	function secondsToTime($segundos, $flgMostraSegundos=0) {
		$dias = intval($segundos/86400);
		$segundos -= $dias*86400;

		$horas = intval($segundos/3600);
		$segundos -= $horas*3600;

		$minutos = intval($segundos/60);
		$segundos -= $minutos*60;

		$vTempo = "";
		if($dias > 0){
			if($dias == 1){
				$vTempo = $dias." dia";
			}else{
				$vTempo = $dias." dias";
			}
		}
		if($horas > 0){
			$vTempo .= $this->iif($vTempo!="",", ","");
			if($horas == 1){
				$vTempo .= $horas." hora";
			}else{
				$vTempo .= $horas." horas";
			}

		}
		if($minutos > 0){
			$vTempo .= $this->iif($vTempo!="",", ","");
			if($minutos == 1){
				$vTempo .= $minutos." minuto";
			}else{
				$vTempo .= $minutos." minutos";
			}
		}
		if($flgMostraSegundos == 1){
			if($segundos > 0){
				$vTempo .= $this->iif($vTempo!="",", ","");
				if($segundos == 1){
					$vTempo .= $segundos." segundo";
				}else{
					$vTempo .= $segundos." segundos";
				}
			}
		}
		return $vTempo;
	}

	function secondsToTimeAbreviado($segundos, $flgMostraSegundos=0) {
		$dias = intval($segundos/86400);
		$segundos -= $dias*86400;

		$horas = intval($segundos/3600);
		$segundos -= $horas*3600;

		$minutos = intval($segundos/60);
		$segundos -= $minutos*60;

		$vTempo = "";
		if($dias > 0){
			$vTempo = $dias."d ";
		}
		if($horas > 0){
			$vTempo .= $this->iif($vTempo!="",", ","");
			$vTempo .= $horas."h ";
		}
		if($minutos > 0){
			$vTempo .= $this->iif($vTempo!="",", ","");
			$vTempo .= $minutos."m ";
		}
		if($flgMostraSegundos == 1){
			if($segundos > 0){
				$vTempo .= $this->iif($vTempo!="",", ","");
				$vTempo .= $segundos."s ";
			}
		}
		return $vTempo;
	}

	function secondsToHours($segundos, $flgMostraSegundos=0) {
		$horas = intval($segundos/3600);
		$segundos -= $horas*3600;

		$minutos = intval($segundos/60);
		$segundos -= $minutos*60;

		$vTempo = "";
		if($horas > 0){
			if($horas == 1){
				$vTempo .= $horas." hora";
			}else{
				$vTempo .= $horas." horas";
			}

		}
		if($minutos > 0){
			$vTempo .= $this->iif($vTempo!="",", ","");
			if($minutos == 1){
				$vTempo .= $minutos." minuto";
			}else{
				$vTempo .= $minutos." minutos";
			}
		}
		if($flgMostraSegundos == 1){
			if($segundos > 0){
				$vTempo .= $this->iif($vTempo!="",", ","");
				if($segundos == 1){
					$vTempo .= $segundos." segundo";
				}else{
					$vTempo .= $segundos." segundos";
				}
			}
		}
		return $vTempo;
	}

	function FormatarData($diff, $flgMostraSegundos=0){
		$vTempoRestante = "";
		if($diff['days'] > 0){
			if($diff['days'] == 1){
				$vTempoRestante = $diff['days']." dia";
			}else{
				$vTempoRestante = $diff['days']." dias";
			}

		}
		if($diff['hours'] > 0){
			$vTempoRestante .= $this->iif($vTempoRestante!="",", ","");
			if($diff['hours'] == 1){
				$vTempoRestante .= $diff['hours']." hora";
			}else{
				$vTempoRestante .= $diff['hours']." horas";
			}

		}
		if($diff['minutes'] > 0){
			$vTempoRestante .= $this->iif($vTempoRestante!="",", ","");
			if($diff['minutes'] == 1){
				$vTempoRestante .= $diff['minutes']." minuto";
			}else{
				$vTempoRestante .= $diff['minutes']." minutos";
			}
		}
		if($flgMostraSegundos == 1){
			if($diff['secondes'] > 0){
				$vTempoRestante .= $this->iif($vTempoRestante!="",", ","");
				if($diff['secondes'] == 1){
					$vTempoRestante .= $diff['secondes']." segundo";
				}else{
					$vTempoRestante .= $diff['secondes']." segundos";
				}
			}
		}
		return $vTempoRestante;
	}

	function FormatarDataResumido($diff){
		$vTempoRestante = "";
		if($diff['days'] > 0){
			$vTempoRestante = $diff['days']."d";
		}
		if($diff['hours'] > 0){
			$vTempoRestante .= $this->iif($vTempoRestante!="",", ","");
			$vTempoRestante .= $diff['hours']."h";
		}
		if($diff['minutes'] > 0){
			$vTempoRestante .= $this->iif($vTempoRestante!="",":","");
			$vTempoRestante .= $diff['minutes']."m";
		}
		/*
		if($diff['secondes'] > 0){
			$vTempoRestante .= $this->iif($vTempoRestante!="",":","");
			$vTempoRestante .= $diff['secondes']."s";
		}
		*/
		return $vTempoRestante;
	}

	function dateDiffPlus($vData){ // Formato Y-m-d
		$day = substr($vData,8,2);
		$month = substr($vData,5,2);
		$year = substr($vData,0,4);
		if($year > date("Y")){ // Ano maior
			return 1;
		}else{
			$num = $year + $month + $day;
			$num2 = date("Y") + date("m") + date("d");
			//print "<br>num2 -> $num2 -  num1 -> $num<br>";
			return $num - $num2;
		}
	}

	function date_business_day_diff($start_date, $end_date) // Recebe no formato Y-m-d
	{
	   $business_days = 0;
	   $start_date = strtotime($start_date);
	   $end_date = strtotime($end_date);
	   $vFlag = 1;
	   if ($start_date < $end_date) {
	       $date1 = $start_date;
	       $date2 = $end_date;
	   } else {
	       $date1 = $end_date;
	       $date2 = $start_date;
		   $vFlag = -1;
	   }
	   while ($date1 < $date2) {
	       $thedate = getdate($date1);
	       // Skip saturday or sunday.
	       if (($thedate["wday"] != '0') and ($thedate["wday"] != '6')) {
	           $business_days++;
	       }
	       $date1 += 86400; // Add a day.
	   }
	   return $business_days * $vFlag;
	}

	function fRetornaDiaSemanaAbreviado($start_date) // Recebe no formato Y-m-d
	{
	   $start_date = strtotime($start_date);
	   $thedate = getdate($start_date);
	   if ($thedate["wday"] == '0') {
	           return "Dom";
	   }elseif ($thedate["wday"] == '6') {
	           return "Sab";
	   }elseif ($thedate["wday"] == '1') {
	           return "Seg";
	   }elseif ($thedate["wday"] == '2') {
	           return "Ter";
	   }elseif ($thedate["wday"] == '3') {
	           return "Qua";
	   }elseif ($thedate["wday"] == '4') {
	           return "Qui";
	   }elseif ($thedate["wday"] == '5') {
	           return "Sex";
	   }
	}

	 Function CompletaCom0($Strring){
	 	if (strlen($Strring) == 1) return "0".$Strring;
		else  return $Strring;
	 }

	 Function CompletaCom0Plus($Strring,$QtdCasas=2){
		for($i=strlen($Strring);$i<$QtdCasas;$i++){
			$Strring = "0".$Strring;
		}
		return $Strring;
	 }

	 function fFormataTelafone($nNuTel){
	 	if(strlen($nNuTel) == 7){
			return substr($nNuTel,0,3)."-".substr($nNuTel,3,8);
		} elseif(strlen($nNuTel) == 8){
			return substr($nNuTel,0,4)."-".substr($nNuTel,4,9);
		}else{
			return $nNuTel;
		}
	 }

	 Function fFormataCEP($String){
	 	return substr($String,0,5)."-".substr($String,5,3);
	 }

	 function fFormataAtividade($String){
	 	$aSplit = explode(" ",$String);
		$count = count($aSplit);
		$var = "";
		for($i=0;$i<$count;$i++){
		    if ($i!=0){
				$var .= " ";
			}
			if(strlen($aSplit[$i]) > 2){
				$var .= substr($aSplit[$i],0,1).strtolower(substr($aSplit[$i],1,strlen($aSplit[$i])));
			} else{
				$var .= strtolower($aSplit[$i]);
			}
		}
		return $var;
	 }

	 function MandaArquivo($imagem_nome, $imagem_tmp_name, $vPath){
		if (!move_uploaded_file($imagem_tmp_name, $vPath.$imagem_nome)) {
		 	   //echo "<script>alert(\"Erro enviado o arquivo ".$vPath.$imagem_nome."\");</script>";
		    return 0;
		} else {
	// 	   echo "<script>alert(\"Enviado o arquivo ".$vArquivo."\");</script>";
			return 1;
		}
	 }

	 function fRetornaExtencaoArquivo($vTipoArquivo){
		// Montar array com os tipos de arquivos e sua respectiva extenção
		$aArquivosType = array("application/x-shockwave-flash", "image/pjpeg", "image/gif", "image/jpeg", "image/pjpeg");
		$aArquivosExt  = array("SWF", "JPG", "GIF", "JPEG", "JPG");
		for($i=0; $i< count($aArquivosType); $i++){
			if(strtolower($vTipoArquivo) == strtolower($aArquivosType[$i])){
				return strtolower($aArquivosExt[$i]);
			}
		}
		return "";
	 }

	 function MandaMensagemSimples($Email, $EmailAssunto, $EmailMensagem, $UsuarioEmail){
		 if ( !mail($Email, $EmailAssunto, $EmailMensagem, "From: $UsuarioEmail\r\nReply-to: $UsuarioEmail\r\n")){
			return 0;
		 } else{
			return 1;
		 }
	  }

	function fetchURL( $url ) {
	   $url_parsed = parse_url($url);
	   $host = $url_parsed["host"];
	   $port = $url_parsed["port"];
	   if ($port==0) $port = 80;
	   $path = $url_parsed["path"];
	   if ($url_parsed["query"] != "") $path .= "?".$url_parsed["query"];
	   $out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
	   $fp = fsockopen($host, $port, $errno, $errstr, 30);
	   fwrite($fp, $out);
	   $body = false;
	   while (!feof($fp)) {
	       $s = fgets($fp, 1024);
	       if ( $body ) $in .= $s;
	       if ( $s == "\r\n" ) $body = true;
	   }
	   fclose($fp);
	   return $in;
	}
	function fGetUltimoDia($vMes, $vAno){
		if($vMes == 12){
			$vMes = 1;
			$vAno++;
		}else{
			$vMes++;
		}
		return date("d",mktime (0,0,0,$vMes,0,$vAno));
	}
	function char2html($str,$espaco = 0) {
	  $caractere = Array('«','»','¡','¿','À','à','Á','á','Â','â','Ã','ã','Ä','ä','Å','å','Æ','æ','Ç','ç','Ð','ð','È','è','É','é','Ê','ê','Ë','ë','Ì','ì','Í','í','Î','î','Ï','ï','Ñ','ñ','Ò','ò','Ó','ó','Ô','ô','Õ','õ','Ö','ö','Ø','ø','Ù','ù','Ú','ú','Û','û','Ü','ü','Ý','ý','ÿ','Þ','þ','ß','§','¶','µ','¦','±','·','¨','¸','ª','º','¬','­','¯','°','¹','²','³','¼','½','¾','×','÷','¢','£','¤','¥');
	  $htmlentidade = Array('&laquo;','&raquo;','&iexcl;','&iquest;','&Agrave;','&agrave;','&Aacute;','&aacute;','&Acirc;','&acirc;','&Atilde;','&atilde;','&Auml;','&auml;','&Aring;','&aring;','&AElig;','&aelig;','&Ccedil;','&ccedil;','&ETH;','&eth;','&Egrave;','&egrave;','&Eacute;','&eacute;','&Ecirc;','&ecirc;','&Euml;','&euml;','&Igrave;','&igrave;','&Iacute;','&iacute;','&Icirc;','&icirc;','&Iuml;','&iuml;','&Ntilde;','&ntilde;','&Ograve;','&ograve;','&Oacute;','&oacute;','&Ocirc;','&ocirc;','&Otilde;','&otilde;','&Ouml;','&ouml;','&Oslash;','&oslash;','&Ugrave;','&ugrave;','&Uacute;','&uacute;','&Ucirc;','&ucirc;','&Uuml;','&uuml;','&Yacute;','&yacute;','&yuml;','&THORN;','&thorn;','&szlig;','&sect;','&para;','&micro;','&brvbar;','&plusmn;','&middot;','&uml;','&cedil;','&ordf;','&ordm;','&not;','&shy;','&macr;','&deg;','&sup1;','&sup2;','&sup3;','&frac14;','&frac12;','&frac34;','&times;','&divide;','&cent;','&pound;','&curren;','&yen;');
	  $remonta = "";
	  $remonta = str_replace($caractere,$htmlentidade,$str);
	  if($espaco != 0) $remonta = str_replace(' ','&nbsp;',$remonta);
	  return $remonta;
	}

	function display_img($arch)
	{
	  $imgsize = GetImageSize ($arch);
	//  w - 100
	//  h - 76
	  $w = $imgsize[0];
	  $h = $imgsize[1];
	  if ($h > $w){
	  	$x = $h;
		$h = $w;
		$w = $x;
	  }

	  $width = ($w * 100)/ 1024;
	  $height = ($h * 76)/ 768;

	  echo "<img src=\"$arch\" width=\"$width\" height=\"$height\">";
	}

	function mostraImagem($arch, $vComplemento="", $heightPadrao, $widthPadrao){
		$imgsize = GetImageSize ($arch);
		$w = $imgsize[0];
		$h = $imgsize[1];
		if ($h > $w){ // se a altura for maior que a largura
			$height = $heightPadrao; //89;
			$ratio = $height / $h;
			$width = $w * $ratio;
	  	}else{
			$width = $widthPadrao; //119;
			// Faz o Calculo para Definir o Tamanho da Imagem
			$ratio = $width / $w;
			$height = $h * $ratio;
		}
		return "<img src=\"$arch\" width=\"$width\" height=\"$height\" $vComplemento>";
	}

	function fRetornaNomeMes($vMes){
		if($vMes == 1){
			return "Janeiro";
		}elseif($vMes == 2){
			return "Fevereiro";
		}elseif($vMes == 3){
			return "Março";
		}elseif($vMes == 4){
			return "Abril";
		}elseif($vMes == 5){
			return "Maio";
		}elseif($vMes == 6){
			return "Junho";
		}elseif($vMes == 7){
			return "Julho";
		}elseif($vMes == 8){
			return "Agosto";
		}elseif($vMes == 9){
			return "Setembro";
		}elseif($vMes == 10){
			return "Outubro";
		}elseif($vMes == 11){
			return "Novembro";
		}elseif($vMes == 12){
			return "Dezembro";
		}
	}
	function fRetornaNomeMesAbreviado($vMes){
		if($vMes == 1){
			return "Jan";
		}elseif($vMes == 2){
			return "Fev";
		}elseif($vMes == 3){
			return "Mar";
		}elseif($vMes == 4){
			return "Abr";
		}elseif($vMes == 5){
			return "Mai";
		}elseif($vMes == 6){
			return "Jun";
		}elseif($vMes == 7){
			return "Jul";
		}elseif($vMes == 8){
			return "Ago";
		}elseif($vMes == 9){
			return "Set";
		}elseif($vMes == 10){
			return "Out";
		}elseif($vMes == 11){
			return "Nov";
		}elseif($vMes == 12){
			return "Dez";
		}
	}

	function iif($Condicao, $Sim, $Nao){
   		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
   }
	function fMontaPaginacao($vQtdTotal, $vQtdRegPagina, $vNuPagina, $vNomPagina){
		$vQtdPaginasPesquisa = ceil($vQtdTotal / $vQtdRegPagina);
	//	if(($vQtdTotal % $vQtdRegPagina) > ($vQtdRegPagina / 2)){$vQtdPaginasPesquisa += 1; }
		if($vNuPagina < 10){
			$pagMax = 10;
			$pagMin = 1;
		} else{
			$pagMax = $vNuPagina + 5;
			$pagMin = $vNuPagina - 5;
		}
		$vQtdPaginasPesquisa = $this->iif($vQtdPaginasPesquisa==0,1,$vQtdPaginasPesquisa);
		$heigth = 18;//20
		$width = 16;//16
		?>
		<tr>
		  <td id="paginacao" valign="bottom">
		  <hr>
		  		 <?if($vQtdTotal + 1 > $vQtdRegPagina){?>
		  		 	<? if (($vNuPagina-1) >= 1){?>
				   	<a  alt="Primeira Página" href="<?=$vNomPagina?>&vNumPagina=1"> 
			  			<img title="Primeira Página" heigth="<?=$heigth?>" width="<?=$width?>"  src="<?=$this->vPathPadrao."imagens/seta_primeira_pagina.gif"?>"  border="0">	 
			  		</a>
			  		<?}else{?>
			  			<img title="Primeira Página" heigth="<?=$heigth?>" width="<?=$width?>"  src="<?=$this->vPathPadrao."imagens/seta_primeira_pagina.gif"?>"  border="0">
			  		<?}?>
		  		 		&nbsp;
			  		<? if (($vNuPagina-1)>= 1){?>
			  		<a  alt="Página Anterior" href="<?=$vNomPagina?>&vNumPagina=<?=$vNuPagina -1?>"> 
			  			<img title="Página Anterior" heigth="<?=$heigth?>" width="<?=$width?>"  src="<?=$this->vPathPadrao."imagens/seta_pagina_anterior.gif"?>"  border="0">	
			  		</a>
			  		 <?}else{?>
			  		 	 <img title="Página Anterior" heigth="<?=$heigth?>" width="<?=$width?>"  src="<?=$this->vPathPadrao."imagens/seta_pagina_anterior.gif"?>"  border="0">
			  		 <?}?>
			   	<?}else{?> 
			   		<img title="Primeira Página" heigth="<?=$heigth?>" width="<?=$width?>"   src="<?=$this->vPathPadrao."imagens/seta_primeira_pagina.gif"?>"  border="0">
			   		&nbsp;
			   		<img title="Página Anterior" heigth="<?=$heigth?>" width="<?=$width?>"   src="<?=$this->vPathPadrao."imagens/seta_pagina_anterior.gif"?>"  border="0">
			   	<?}?>
			   	
		  	  <?
			  for($i=1; $i<=$vQtdPaginasPesquisa; $i++){
			  	 if($i <= $pagMax && $i >= $pagMin){
				  	 if($i == $vNuPagina){
					 	?>
						 &nbsp;<b><font size="2" color="#000000"><?=$i?>&nbsp;</font> </b>
						 <?
					 }else {
					  	 ?>
						 &nbsp;<font size="2" ><a href="<?=$vNomPagina?>&vNumPagina=<?=$i?>"><?=$i?></font></a>&nbsp;
						 <?
					 }
					 if($i < $vQtdPaginasPesquisa && ($i + 1) <= $pagMax){
					 	print "|";
					 }
				  }
			  }
			  ?>
			   
			   <?if($vQtdTotal + 1 > $vQtdRegPagina){?>
			   			
			   			<? if (($vNuPagina +1)<= $vQtdPaginasPesquisa){?>
			   			 <a  alt="Próxima Página" href="<?=$vNomPagina?>&vNumPagina=<?=$vNuPagina+1?>"> 
							<img  heigth="<?=$heigth?>" width="<?=$width?>"  title="Próxima Página" src="<?=$this->vPathPadrao."imagens/seta_proxima_pagina.gif"?>"  border="0">						 
						 </a>
						 <?}else{?> 
							 <img  heigth="<?=$heigth?>" width="<?=$width?>"  title="Próxima Página" src="<?=$this->vPathPadrao."imagens/seta_proxima_pagina.gif"?>"  border="0">
						 <?}?>
						 &nbsp;			
						 <? if (($vNuPagina+1)<= $vQtdPaginasPesquisa){?>			 
						 <a  alt="Última página" href="<?=$vNomPagina?>&vNumPagina=<?=$vQtdPaginasPesquisa?>"> 
							<img heigth="<?=$heigth?>" width="<?=$width?>"   title="Última página" src="<?=$this->vPathPadrao."imagens/seta_ultima_pagina.gif"?>"  border="0">						 
						 </a>
						 <?}else{?>
						 	<img heigth="<?=$heigth?>" width="<?=$width?>"   title="Última página" src="<?=$this->vPathPadrao."imagens/seta_ultima_pagina.gif"?>"  border="0"> 
						 <?}?>
				<?}else{?> 
			   		<img  title="Próxima Página"  heigth="<?=$heigth?>" width="<?=$width?>"  src="<?=$this->vPathPadrao."imagens/seta_proxima_pagina.gif"?>"  border="0">
			   		 &nbsp;
			   		<img title="Última página"  heigth="<?=$heigth?>" width="<?=$width?>"   src="<?=$this->vPathPadrao."imagens/seta_ultima_pagina.gif"?>"  border="0">	
			  	<?}?>
			  
		  </td>
	    </tr>
		<?
	}
	
	function fMontaPaginacao_old($vQtdTotal, $vQtdRegPagina, $vNuPagina, $vNomPagina){
		$vQtdPaginasPesquisa = ceil($vQtdTotal / $vQtdRegPagina);
	//	if(($vQtdTotal % $vQtdRegPagina) > ($vQtdRegPagina / 2)){$vQtdPaginasPesquisa += 1; }
		if($vNuPagina < 10){
			$pagMax = 10;
			$pagMin = 1;
		} else{
			$pagMax = $vNuPagina + 5;
			$pagMin = $vNuPagina - 5;
		}
		$vQtdPaginasPesquisa = $this->iif($vQtdPaginasPesquisa==0,1,$vQtdPaginasPesquisa);
		?>
		<tr>
		  <td id="paginacao">
		  <hr>
		  		Página
		  	  <?
			  for($i=1; $i<=$vQtdPaginasPesquisa; $i++){
			  	 if($i <= $pagMax && $i >= $pagMin){
				  	 if($i == $vNuPagina){
					 	?>
						 &nbsp;<?=$i?>&nbsp;
						 <?
					 }else {
					  	 ?>
						 &nbsp;<a href="<?=$vNomPagina?>&vNumPagina=<?=$i?>"><?=$i?></a>&nbsp;
						 <?
					 }
					 if($i < $vQtdPaginasPesquisa && ($i + 1) <= $pagMax){
					 	print "|";
					 }
				  }
			  }
			  ?>
		  </td>
	    </tr>
		<?
	}
	function fQuantidadeRegistros($vQtdTotal, $vQtdRegPagina, $vNuPagina, $aTitulo){
		$regStart = (($vNuPagina - 1) * $vQtdRegPagina) + 1;
		$regEnd = $this->iif($vQtdTotal > ($regStart + $vQtdRegPagina - 1),($regStart + ($vQtdRegPagina - 1)),  $vQtdTotal);
		?>
		<tr>
		  <td id="qtdRegistros" colspan="<?=count($aTitulo)?>">
		  	  Resultados <?=$regStart?> - <?=$regEnd?> de <?=$vQtdTotal?> registros encontrados
		  </td>
	    </tr>
		<?
	}

	function valida_ldap($usr, $pwd){
		//$ldap_server = $this->parametro->GetValorParametro("ldap_server");
                $ldap_server = $this->ldap_server;
		$ldapport    = $this->parametro->GetValorParametro("ldapport");
		$auth_user   = $usr;
		$auth_pass   = $pwd;

		// Tenta se conectar com o servidor
		$connect = ldap_connect($ldap_server, $ldapport) or die("Não foi possível conectar a $ldaphost");
		// Tenta autenticar no servidor
		if (!($bind = @ldap_bind($connect, $auth_user, $auth_pass))) {
			// se não validar retorna false
			return FALSE;
		} else {
			// se validar retorna true
			return TRUE;
		}
	} // fim função conectar ldap

	function Tabela($aLinhas, $width="60%", $vComplemento="", $FlagIdAutomatico = false, $titulo="", $classTitulo="", $cellpading=0, $cellspacing=0){
		$return = "
		<table width=\"$width\" $vComplemento cellpading=\"$cellpading\" cellspacing=\"$cellspacing\"> ".chr(13);
			if($titulo != ""){
				$return .= "<tr><td colspan=\"".count($aLinhas[0])."\" id=\"$classTitulo\">$titulo</td></tr>".chr(13);
			}
			for($i=0; $i<count($aLinhas); $i++){
				if($FlagIdAutomatico)$return .= "<tr id=".$this->GetIdTable().">".chr(13);
				else $return .= "<tr>";
				for($j=0; $j<count($aLinhas[$i]); $j++){
					$return .= "<td id=\"".$aLinhas[$i][$j][3]."\" align=\"".$aLinhas[$i][$j][1]."\" valign=\"".$aLinhas[$i][$j][4]."\" width=\"".$aLinhas[$i][$j][2]."\">".$aLinhas[$i][$j][0]."</td>".chr(13);
				}
				$return .= "</tr>".chr(13);
			}
			$return .= "</table>".chr(13);

		return $return;
	}

	function TabelaDinâmica($vID, $aColunas, $width="60%"){
		$return = "
		<table id=\"$vID\" width=\"$width\" class=\"TabelaDinamica\">
			<tbody>
				<tr id=\"header\"> ";

				for($i=0;$i<count($aColunas);$i++){
					$return .= "<td align=\"".$aColunas[$i][1]."\" width=\"".$aColunas[$i][2]."\">".$aColunas[$i][0]."</td>";
				}
				$return .= "</tr>
				<tr>
					<td colspan=".count($aColunas).">Sem dados a serem exibidos</td>
				</tr>
			</tbody>
		</table>
		<br>
		";

		return $return;
	}

	function diasemana($data)
	{  // Traz o dia da semana para qualquer data informada
		$dia =  substr($data,0,2);
		$mes =  substr($data,3,2);
		$ano =  substr($data,6,9);
		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
		switch($diasemana){
						case"0": $diasemana = "Domingo";       break;
						case"1": $diasemana = "Segunda-Feira"; break;
						case"2": $diasemana = "Terça-Feira";   break;
						case"3": $diasemana = "Quarta-Feira";  break;
						case"4": $diasemana = "Quinta-Feira";  break;
						case"5": $diasemana = "Sexta-Feira";   break;
						case"6": $diasemana = "Sábado";        break;
					 }
		echo "$diasemana";
	}

	//Funcao para converter a data
	function converter_data($data)
	{
		$data = trim($data);
		if(preg_match("@^\d{4}-\d{2}-\d{2}$@", $data)) {
			return $data;
		}
		if(preg_match("@^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$@", $data)) {
			return $data;
		}
		if(preg_match("@^\d{2}/\d{2}/\d{4}$@", $data)) {
			return implode("-", array_reverse(explode("/", $data)));
		}
		else {
			echo "data invalida";
			exit;
		}
	}

	//Valida Data
	function valida_data($data)
	{
		$data = trim($data);
		if(preg_match("@^\d{2}/\d{2}/\d{4}$@", $data)) {
			$arr = explode("/", $data);
			return !checkdate($arr[1], $arr[0], $arr[2]) ? false : true;
		}
		if(preg_match("@^\d{4}-\d{2}-\d{2}$@", $data)) {
			$arr = explode("-", $data);
			return !checkdate($arr[1], $arr[2], $arr[1]) ? false : true;
		}
		if(preg_match("@^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$@", $data)) {
			$arr = explode("-", $data);
			$dia = substr($arr[2], 0, 2);
			return !checkdate($arr[1], $dia, $arr[0]) ? false : true;
		}
		return false;
	}

	//Funcao para somar a data
	function soma_data($data, $add)
	{
		$add  = trim($add);
		$data = trim($data);
		$data = preg_replace("#(agora|hoje|atual)#i", date("Y-m-d H:i:s"), $data);
		$data = $this->converter_data($data);
		if($this->valida_data($data)) {
			$add = preg_replace("#semana[s]?#i", "week", $add);
			$add = preg_replace("#m(e|[ê])s(es)?#i", "month", $add);
			$add = preg_replace("#ano[s]?#i", "year", $add);
			$add = preg_replace("#dia[s]?#i", "days", $add);
			$add = preg_replace("#hora[s]?#i", "hours", $add);
			$add = preg_replace("#minuto[s]?#i", "minutes", $add);
			$add = preg_replace("#segundo[s]?#i", "seconds", $add);

			//$data = getdate(strtotime("$data +$add"));
			setlocale(LC_TIME,"portuguese");

			$data = strftime("%A %B %Y %c %j", strtotime("$data +$add"));
			$arr  = explode(" ", $data);
			$datas["semana"] = $arr[0];

			$arr2 = explode("/", $arr[3]);
			$dia = $arr2[0];
			$mes = $arr2[1];
			$ano = $arr2[2];
			$datas["dia"]     = sprintf("%02d", $dia);
			$datas["mes"][1]  = $arr[1];
			$datas["mes"][2]  = sprintf("%02d", $mes);
			$datas["ano"]     = $ano;
			$arr3             = explode(":", $arr[4]);
			$datas["hora"]    = $arr3[0];
			$datas["minuto"]  = $arr3[1];
			$datas["segundo"] = $arr3[2];
			$datas["dia_ano"] = $arr[5];

			return $datas;
		} else {
			echo "Data invalida";
		}
	}

	//Diferenca entre datas
	// formato do parametros "2008-04-08 08:16:13"
	function CalculaDifDataHora($data1, $data2){

		$horas = 0;

		$data1 = substr($data1,0,10);
		$data2 = substr($data2,0,10);

		$hora_1 = substr($data1,11,2);
		$min_1 = substr($data1,14,2);
		$sec_1 = substr($data1,17,2);

		$hora_2 = substr($data2,11,2);
		$min_2 = substr($data2,14,2);
		$sec_2 = substr($data2,17,2);

		$data1Aux = $data1;

		print $data2."---<br>";
		while (str_replace('-','',$data1Aux) <= str_replace('-','',$data2)){
			print "<br>semana  ".$this->diasemana($data1Aux)." ".$data1Aux;
			//Mesmo dia
			if (str_replace('-','',$data1Aux) == str_replace('-','',$data2)) {
					$horaAux = (int) $hora_1 + (int) $min_1 / 60;
					if ($horaAux <= 8) {
						print "oi";
						$horas += 8;
					} else {
						if ($horaAux < 12) {
							$horas += (16 - $horaAux);// 'Horas início do expediente + o almoço
						} else {
							if ($horaAux >= 12 & $horaAux <= 13.5) {
								$horas += 4;
							} else {
								if ($horaAux < 17.5) {
									$horas += (17.5 - $horaAux);
								}
							}
						}
					}

			} else {
				$horas += 8; //Horas Uteis
			}
			$arrDate = $this->soma_data("$data1Aux","1 dias");
			$data1Aux = $arrDate['ano']."-".$arrDate['mes'][2]."-".$arrDate['dia'];
			print "<br> dia:".$data1Aux;

		}
		print "<br> horas".$horas;


	}

	function EnviaEmail($destino, $rementente, $assunto, $mensagem){

		$headers = "Content-Type: text/html; charset=iso-8859-1";
		$headers.="From:".$rementente."";

		@mail("$destino", "$assunto", "$mensagem","$headers");

	}

	function Legenda($aLegenda, $width="60%"){

				for($i=0;$i<count($aLegenda);$i++){

					//$return .= " ".$aLegenda[$i][1]." ";
					switch ($aLegenda[$i][1])
					{
						case "legendaVerde":
							$return .= "<img src=\"../../gestaoti/imagens/ponto_verde.gif\" />"; break;
						case "legendaClaro":
							$return .= "<img src=\"../../gestaoti/imagens/ponto_claro.gif\" />"; break;
						case "legendaLaranja":
							$return .= "<img src=\"../../gestaoti/imagens/ponto_laranja_full.gif\" />"; break;
						case "legendaBranco":
							$return .= "<img src=\"../../gestaoti/imagens/ponto_branco.gif\" />"; break;
						case "legendaAzul":
							$return .= "<img src=\"../../gestaoti/imagens/ponto_azul.gif\" />"; break;
						case "legendaVermelho":
							$return .= "<img src=\"../../gestaoti/imagens/ponto_red.gif\" />"; break;
						default:
							$return .= ""; break;
					}
					$return .= " ".$aLegenda[$i][0]." ";

				}

		return $return;
	}

	function GraficoBarras($aLegenda, $width="60%"){
		$return = "
		<table id=\"$vID\" width=\"$width\" class=\"TabelaDinamica\">
			<tbody>";
				$k = 1;
				for($i=0;$i<count($aLegenda);$i++){
					$return .= "<tr id=\"header\">";
					$return .= "<td align=\"left\" width=\"40%\">".$aLegenda[$i][0]."</td>";
					$return .= "<td align=\"left\" width=\"60%\">";
					for ($j=0;$j<((int) ($aLegenda[$i][1]));$j++) {
						$return .= "<img src=\"../../gestaoti/imagens/barra".$k.".jpg\" />";
					}
					$return .= "".$aLegenda[$i][1]."%";
					++$k;
					if ($k == 5){$k=1;}
					$return .= "</td>";
					$return .= "</tr>";
				}
				$return .= "</tr>
			</tbody>
		</table>
		<br>
		";

		return $return;
	}

	function TabelaSimples($vID, $aCabec, $aRegistro, $width="60%"){
		$return = "
		<table id=\"$vID\" width=\"$width\" class=\"TabelaDinamica\">
			<tbody>
				<tr id=\"header\"> ";

				//Trata Cabecalho
				for($i=0;$i<count($aCabec);$i++){
						//Trata Cabecalho das legendas
						switch ($aCabec[$i][0])
						{
						 	case "legendaVerde":
								$return .= "<td align=\"".$aCabec[$i][1]."\" width=\"".$aCabec[$i][2]."\"><img src=\"../../gestaoti/imagens/ponto_verde.gif\" /></td>"; break;
						 	case "legendaClaro":
								$return .= "<td align=\"".$aCabec[$i][1]."\" width=\"".$aCabec[$i][2]."\"><img src=\"../../gestaoti/imagens/ponto_claro.gif\" /></td>"; break;
						 	case "legendaLaranja":
								$return .= "<td align=\"".$aCabec[$i][1]."\" width=\"".$aCabec[$i][2]."\"><img src=\"../../gestaoti/imagens/ponto_laranja_full.gif\" /></td>"; break;
						 	case "legendaBranco":
								$return .= "<td align=\"".$aCabec[$i][1]."\" width=\"".$aCabec[$i][2]."\"><img src=\"../../gestaoti/imagens/ponto_branco.gif\" /></td>"; break;
						 	case "legendaAzul":
								$return .= "<td align=\"".$aCabec[$i][1]."\" width=\"".$aCabec[$i][2]."\"><img src=\"../../gestaoti/imagens/ponto_azul.gif\" /></td>"; break;
						 	case "legendaVermelho":
								$return .= "<td align=\"".$aCabec[$i][1]."\" width=\"".$aCabec[$i][2]."\"><img src=\"../../gestaoti/imagens/ponto_red.gif\" /></td>"; break;
							default:
								$return .= "<td align=\"".$aCabec[$i][1]."\" width=\"".$aCabec[$i][2]."\">".$aCabec[$i][0]."</td>"; break;
						}
				}

				$return .= "</tr>";

				for($i=0;$i<count($aRegistro);$i++){

					$return .= "<tr id=\"escuro\"> ";

					for($j=0;$j<count($aCabec);$j++){
						//Mostra Registros da Tabela
						switch ($aRegistro[$i][$j][3])
						{
							//Aprova
							case "checkbox":
								$strColuna = "<div align=\"center\"><input type=\"checkbox\" name=\"".$vID."aprova".$i."\" ".$aRegistro[$i][$j][0]."></div>"; break;
							//Descrição da Tarefa
						 	case "label":
								$strColuna = "<div align=\"left\">".$aRegistro[$i][$j][0]."</div>"; break;
							//Link para mais detalhe - icone
						 	case "icon":
								$strColuna = "<a href=\"Detalhe_Tarefa.php?v_SEQ_TAREFA_TI=".$aRegistro[$i][$j][0]."\"><img src=\"../../gestaoti/imagens/details.gif\" border=\"0\"/></a>"; break;
							//Data Inicial
						 	case "textDataInic":
								$strColuna = "<input type=\"text\" name=\"".$vID."dataInic".$i."\" value=\"".$aRegistro[$i][$j][0]."\" maxlength=\"19\" size=\"19\" onKeyPress=\"DataHora(event, this)\" onBlur=\"if(VerificaData(this.value.substring(0,10))==false){this.value='';} if(verifica_hora(this.value.substring(11))==false){this.value='';}\" >"; break;
							//Data Final
						 	case "textDataFim":
								$strColuna = "<input type=\"text\" name=\"".$vID."dataFim".$i."\" value=\"".$aRegistro[$i][$j][0]."\" maxlength=\"19\" size=\"19\" onKeyPress=\"DataHora(event, this)\" onBlur=\"if(VerificaData(this.value.substring(0,10))==false){this.value='';} if(verifica_hora(this.value.substring(11))==false){this.value='';}\">"; break;
							//Quantidade de Horas Extras
						 	case "text":
								//Mostrar text caso o status estiver como Encerrado
								//Ao solicitar para ENCERRAR, pedir para preencher qtd de horas extras
								if ($aRegistro[$i][6][0] == "4"){
									$ocultaDiv = "";
								} else {
									$ocultaDiv = "";
									$ocultaDiv = "style=\"display:none;\"";
								}
								$strColuna = "<div id=\"".$vID."entra".$i."\"  ".$ocultaDiv."><input type=\"text\" name=\"".$vID."extra".$i."\" value=\"".$aRegistro[$i][7][4]."\" size=3 onKeyPress=\"SoNumeros();\"></div>"; break;
							//Status da Tarefa	- Radio
							case "radio":

								$strChecked = "";
								if ( $aRegistro[$i][$j][0] != "" )
								{
									$strChecked = "checked";
								}

								$trataRadio = "
								    j = 0;
								    for (i=0;i<document.forms[0].elements.length;i++)
									{
								      if(document.forms[0].elements[i].type == 'radio' & document.forms[0].elements[i].name == '".$vID."grupo".$i."')
									  {
									  	++j;
										document.forms[0].elements[i].value = '';
										if (document.forms[0].elements[i].checked){
											document.forms[0].elements[i].value = j;
										}
									  }
									}

								";

								$trataRadio .= "
									if (this.value == 4) {
										document.getElementById('".$vID."entra".$i."').style.display='';
									} else {
										document.getElementById('".$vID."entra".$i."').style.display='none';
										document.forms[0].elements['".$vID."extra".$i."'].value = '';
									}
								";

								$strColuna = "<input type=\"radio\" name=\"".$vID."grupo".$i."\" onClick=\"$trataRadio\" value=\"".$aRegistro[$i][$j][0]."\" ".$strChecked." ".$aRegistro[$i][$j][4].">"; break;
							//Dado sem formatacao
							case "input":

								$strColuna = $aRegistro[$i][$j][0]; break;
							//Default
							default:

								$strColuna = $aRegistro[$i][$j][0]; break;
						}
						$return .= "<td align=\"".$aRegistro[$i][$j][1]."\" width=\"".$aRegistro[$i][$j][2]."\">".$strColuna."</td>";

					}
					$return .= "</tr>";
				}


			$return .= "</tbody>
		</table>
		<br>
		";

		return $return;
	}
	// Recebe um array padrão de combo e transforma em um array padrão AJAX para carregar combo sem reload na página
	function AjaxFormataArrayCombo($aCombo){
		// $aItemOption[] = array($CODIGO, $SELECTED, $DESCRICAO); // EXEMPLO DE ARRAY COMBO
		for($i=0; $i<count($aCombo); $i++) {
			$aRetorno[] = $aCombo[$i][0]."|".rawurlencode($aCombo[$i][2]);
		}
		return $aRetorno;
	}

	function CorSLA($COD_SLA){
		if($COD_SLA == 0){
			return "#FF935E";
		}elseif($COD_SLA == ""){
			return "gray";
		}elseif($COD_SLA == 1){
			return "green";
		}elseif($COD_SLA == -1){
			return "red";
		}
	}
	function ImagemSLA($COD_SLA){
		if(!is_numeric($COD_SLA)) return "<img src='imagens/cinza.gif' alt='SLA Não estabelecido' border=0>";
		if($COD_SLA == 0){
			return "<img src='imagens/amarelo.gif' alt='Risco de Atraso' border=0>";
		}elseif($COD_SLA == 1){
			return "<img src='imagens/verde.gif' alt='Em Dia' border=0>";
		}elseif($COD_SLA == -1){
			return "<img src='imagens/vermelho.gif' alt='Atrasado' border=0>";
		}
	}

	// Return: 0  - PINGOU
	//		   1  - NÃO PINGOU
	function ping($host){
		$host = escapeshellarg($host);
		exec("ping $host", $list, $retorno);
		//print "retorno = $retorno<br>";
		return $retorno;
	}

	/*TODO: NOVO PERFIL ACESSO*/
	function isAdministrador($v_SEQ_PERFIL_ACESSO){
		 
		$count = count($_SESSION["SEQ_PERFIL_ACESSO"]);
		for ($i = 0; $i < $count; $i++) {
		    if($_SESSION["SEQ_PERFIL_ACESSO"][$i][0]== 2){
		    	return true;
		    }
		}
		return false;	 
	}
	
	function isColaborador($v_SEQ_PERFIL_ACESSO){
		 
		$count = count($_SESSION["SEQ_PERFIL_ACESSO"]);
		for ($i = 0; $i < $count; $i++) {
		    if($_SESSION["SEQ_PERFIL_ACESSO"][$i][0]== 1){
		    	return true;
		    }
		}
		return false;	 
	}
	
	function isGestorTI($v_SEQ_PERFIL_ACESSO){
		 
		$count = count($_SESSION["SEQ_PERFIL_ACESSO"]);
		for ($i = 0; $i < $count; $i++) {
		    if($_SESSION["SEQ_PERFIL_ACESSO"][$i][0]== 4){
		    	return true;
		    }
		}
		return false;	 
	}
	
	function isCoordenadorTI($v_SEQ_PERFIL_ACESSO){
		 
		$count = count($_SESSION["SEQ_PERFIL_ACESSO"]);
		for ($i = 0; $i < $count; $i++) {
		    if($_SESSION["SEQ_PERFIL_ACESSO"][$i][0]== 5){
		    	return true;
		    }
		}
		return false;	 
	}
	
	function isGerenteDeMudancas($v_SEQ_PERFIL_ACESSO){
		 
		$count = count($_SESSION["SEQ_PERFIL_ACESSO"]);
		for ($i = 0; $i < $count; $i++) {
		    if($_SESSION["SEQ_PERFIL_ACESSO"][$i][0]== 6){
		    	return true;
		    }
		}
		return false;	 
	}
	
	function isExecutorDeMudancas($v_SEQ_PERFIL_ACESSO){
		 
		$count = count($_SESSION["SEQ_PERFIL_ACESSO"]);
		for ($i = 0; $i < $count; $i++) {
		    if($_SESSION["SEQ_PERFIL_ACESSO"][$i][0]== 7){
		    	return true;
		    }
		}
		return false;	 
	}
	function isRequisitanteDeMudancas($v_SEQ_PERFIL_ACESSO){
		 
		$count = count($_SESSION["SEQ_PERFIL_ACESSO"]);
		for ($i = 0; $i < $count; $i++) {
		    if($_SESSION["SEQ_PERFIL_ACESSO"][$i][0]== 8){
		    	return true;
		    }
		}
		return false;	 
	}
	
	/*TODO: NOVO PERFIL ACESSO*/
	function montarToolBarMyView(){
		?>
		<table class="width100_new" cellspacing="0" valign="top">
	    <tr >
	    	<td class="menu" >	    		 
	    		<a href="meus_chamados.php">Meus Chamados</a> |
	    		<a href="view_all_bug_page.php">Chamados da minha Equipe</a>
			</td>
		</tr>
		</table> 
		<? 	
	}
}


?>