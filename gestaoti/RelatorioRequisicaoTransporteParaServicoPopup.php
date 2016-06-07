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
/******************************************************************
 * �rea de inclus�o dos arquivos que ser�o utilizados nesta p�gina.
 *****************************************************************/
 require_once 'include/PHP/class/class.pagina.php';
 require_once 'include/PHP/class/class.chamado.php';
 require_once 'include/PHP/class/class.atividade_chamado.php';
 require_once 'include/PHP/class/class.subtipo_chamado.php';
 //require_once 'include/PHP/autentica.php';
 require_once 'include/PHP/class/class.empregados.oracle.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
 //$destino_triagem	= new destino_triagem();
 $pagina 			= new Pagina();
 $chamado = new chamado();
 $atividade_chamado = new atividade_chamado();
 $subtipo_chamado = new subtipo_chamado();
 $empregados = new empregados();

$pagina->flagScriptCalendario = 0;
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
 

if($v_SEQ_CHAMADO != null && $v_SEQ_CHAMADO != ""){
 $chamado->select($v_SEQ_CHAMADO);
 $empregados->select($chamado->NUM_MATRICULA_SOLICITANTE);
 
 
// APROVADOR
$APROVADOR = "";
require_once '../gestaoti/include/PHP/class/class.aprovacao_chamado_superior.php';				
$aprovacao_chamado_superior = new aprovacao_chamado_superior(); 
$aprovacao_chamado_superior->selectByIdChamado($chamado->SEQ_CHAMADO);
if($aprovacao_chamado_superior->SEQ_APROVACAO_CHAMADO_SUPERIOR){
	require_once '../gestaoti/include/PHP/class/class.empregados.oracle.php';
	$AP = new empregados();
	$AP->select($aprovacao_chamado_superior->NUM_MATRICULA);
	$APROVADOR = $AP->NOME;
 
}else{	 
 	$funcADM = $empregados->GetFuncaoAdministrativaByID($chamado->NUM_MATRICULA_SOLICITANTE); 	
 	
 	if($chamado->aprovadorDeChamados($funcADM)){ 		 
		$APROVADOR = $empregados->NOME; 		
 	}
}
				
?>
<table align="center" width="760" border="0">   
  <tr>
    <td width="200" height="50">  

		<table align="center"  border="1" width="200" bordercolor="black" cellpadding="0" cellspacing="0">
		<tr>
			<td >
			<img alt="" src="../gestaoti/imagens/logo_infraero.jpg" border="0">
			</td>
		</tr>
		</table>
 	</td>
 
    <td width="310">  		
		<table align="center"  border="1" width="300" bordercolor="black" cellpadding="0" cellspacing="0">
		<tr>
			<td align="center" valign="middle" height="55">
				REQUISI��O DE <br> TRANSPORTE PARA SERVI�O <br> 
			</td>
		</tr>
		</table>
	</td>
 
    <td width="240" >  		
		
		<table align="center"  border="1"  bordercolor="black" cellpadding="0" cellspacing="0">
		<tr>
			<td height="55" width="120" valign="top" align="left">
				<font size="2">DATA</font> <br>
				<font size="2"> <b> <?=$chamado->DTH_ABERTURA?></b> </font>
			</td>
			<td height="55" width="120" valign="top" align="left">
				<font size="2">N� CHAMADO</font> <br>
				 <font size="3"> <b><?=$chamado->SEQ_CHAMADO?></b> </font>
			</td>
		</tr>
		</table>
 	</td> 
  </tr>
</table>
<table align="center" width="760" border="0"  >   
  <tr> 
    <td  height="30" align="left"  valign="top" >   
		<table align="left"  width="435"  border="1"  bordercolor="black" cellpadding="0" cellspacing="0">
		<tr>
			<td height="55" width="250" valign="top" align="left">
				<font size="2" > NOME DO REQUISITANTE </font> <br><br>
				<?if($APROVADOR == ""){?>
			 		<font size="1"> <b> <?=$empregados->NOME?></b> </font> 
			 	<?}else if($APROVADOR != ""){?>
			 		<font size="1"> <b> <?=$APROVADOR?></b> </font>
			 	<?}?> 
			 	<br>
			</td>
			<td height="55" width="85" valign="top" align="left">
				<font size="2">UNIDADE</font>  <br><br>
				<font size="1"><b><?=$empregados->UOR_SIGLA?></b> </font><br>
			</td>
			<td height="55" width="85" valign="top" align="left">
				<font size="2">DIRETORIA</font>  <br><br>
				<font size="1"><b><?=$empregados->DEP_SIGLA?></b> </font> <br>
			</td>
		</tr>
		</table>
		
 	</td> 
 	<td   align="left" rowspan="2" width="320" valign="top">   
		<table align="left"     border="1"  bordercolor="black" cellpadding="0" cellspacing="0">
		<tr>
			<td  height="3" width="160" align="center" valign="middle">
				<font size="2">QUILOMETRAGEM</font> 
			</td> 
			<td  height="3" width="160" align="center" valign="middle">
				<font size="2">HOR�RIO</font> 
			</td> 
		</tr>
		<tr>
			<td    width="160" align="left" valign="top">
				<font size="2">SA�DA</font>  <br><br>				
				<table style="border-top:none;border-left:none;border-bottom:none;border-right:none; "  border="1" align="center"   bordercolor="black" cellpadding="0" cellspacing="0">
				<tr  >
					<td style="border-top:none;border-left:none;border-bottom:none;" width="20"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;border-right:none;" width="20" >&nbsp;</td> 
				</tr>				
				</table>
				
			</td> 
			<td    width="160" align="left" valign="top">
				<font size="2">SA�DA</font> <br><br>				
				<table style="border-top:none;border-left:none;border-bottom:none;border-right:none; "  border="1" align="center"   bordercolor="black" cellpadding="0" cellspacing="0">
				<tr  >
					<td style="border-top:none;border-left:none;border-bottom:none;" width="20"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;border-right:none;" width="20" >&nbsp;</td> 
				</tr>				
				</table>
				
			</td> 
		</tr>
		<tr>
			<td    width="160" align="left" valign="top">
				<font size="2">RETORNO</font> <br><br>				
				<table style="border-top:none;border-left:none;border-bottom:none;border-right:none; "  border="1" align="center"   bordercolor="black" cellpadding="0" cellspacing="0">
				<tr  >
					<td style="border-top:none;border-left:none;border-bottom:none;" width="20"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;border-right:none;" width="20" >&nbsp;</td> 
				</tr>				
				</table>
			</td> 
			<td    width="160" align="left" valign="top">
				<font size="2">RETORNO</font> <br><br>			
				<table style="border-top:none;border-left:none;border-bottom:none;border-right:none; "  border="1" align="center"   bordercolor="black" cellpadding="0" cellspacing="0">
				<tr  >
					<td style="border-top:none;border-left:none;border-bottom:none;" width="20"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;border-right:none;" width="20" >&nbsp;</td> 
				</tr>				
				</table>
			</td> 
		</tr>
		<tr>
			<td    width="160" align="left" valign="top">
				<font size="2">UTILIZADA</font> <br><br>				
				<table style="border-top:none;border-left:none;border-bottom:none;border-right:none; "  border="1" align="center"   bordercolor="black" cellpadding="0" cellspacing="0">
				<tr  >
					<td style="border-top:none;border-left:none;border-bottom:none;" width="20"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;border-right:none;" width="20" >&nbsp;</td> 
				</tr>				
				</table>
			</td> 
			<td  height="20" width="160" align="left" valign="top">
				<font size="2">UTILIZADA</font> <br><br> 				
				<table style="border-top:none;border-left:none;border-bottom:none;border-right:none; "  border="1" align="center"   bordercolor="black" cellpadding="0" cellspacing="0">
				<tr  >
					<td style="border-top:none;border-left:none;border-bottom:none;" width="20"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;" width="30"  >&nbsp;</td> 
					<td style="border-top:none;border-left:none;border-bottom:none;border-right:none;" width="20" >&nbsp;</td> 
				</tr>				
				</table>
			</td> 
		</tr>
		<tr>
			<td  height="20"   width="160" align="left" valign="top">
				<font size="2">PLACA DO VE�CULO</font> <br><br><br>		
				 
			</td> 
			<td  height="20"   width="160" align="left" valign="top" >
				<font size="2">NOME DO MOTORISTA</font>  <br><br><br>				
				 
			</td> 
		</tr>
	</table>
 	</td> 
 </tr>
 <tr> 
    <td   align="left" valign="top" >
	   	<table align="left"  width="435"  border="1"  bordercolor="black" cellpadding="0" cellspacing="0">
			<tr>
				<td height="188" width="250" valign="top" align="left">
					<font size="2">FINALIDADE</font>  <br><br>
					<?
						$atividade_chamado->select($chamado->SEQ_ATIVIDADE_CHAMADO);
						$subtipo_chamado->select($atividade_chamado->SEQ_SUBTIPO_CHAMADO);
					?>
					 <font size="1"> <b><?=$atividade_chamado->DSC_ATIVIDADE_CHAMADO." - ".$chamado->TXT_CHAMADO?></b> </font>
				</td> 
			</tr>
		</table>
    </td> 
 </tr>
</table>	

<table align="center" width="760" border="0">   
  <tr>
    <td   height="50">  

		<table align="left"  border="1"  bordercolor="black" cellpadding="0" cellspacing="0">
		<tr>
			<td height="50" width="150" valign="top" align="left">
				<font size="2">VISTO REQUISITANTE</font>  <br>
				<br> <font size="1"><b><?=$APROVADOR?></b></font><br><br>
			</td>
			<td width="150" valign="top" align="left">
				<font size="2">AUTORIZA��O</font><br>  <br>
			</td>
			<td width="150" valign="top" align="left">
				<font size="2">VISTO DAA/SMT</font>  <br><br>
			</td>
			<td width="165" >
				&nbsp;  <br><br>
			</td>
			<td width="165" >
				 &nbsp; <br><br>
			</td>
		</tr>
		</table>
 	 
 	</td> 
  </tr>
</table>
 
 <center>
    -------------------------------------------------------------------------------------------------------------------------------
 </center>	
 
<? 
}

$pagina->MontaRodape();
?>
