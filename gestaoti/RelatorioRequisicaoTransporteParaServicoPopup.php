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
/******************************************************************
 * Área de inclusão dos arquivos que serão utilizados nesta página.
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
				REQUISIÇÃO DE <br> TRANSPORTE PARA SERVIÇO <br> 
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
				<font size="2">Nº CHAMADO</font> <br>
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
				<font size="2">HORÁRIO</font> 
			</td> 
		</tr>
		<tr>
			<td    width="160" align="left" valign="top">
				<font size="2">SAÍDA</font>  <br><br>				
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
				<font size="2">SAÍDA</font> <br><br>				
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
				<font size="2">PLACA DO VEÍCULO</font> <br><br><br>		
				 
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
				<font size="2">AUTORIZAÇÃO</font><br>  <br>
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
