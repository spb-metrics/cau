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
 require 'include/PHP/class/class.pagina.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
 //$destino_triagem	= new destino_triagem();
 $pagina 			= new Pagina();
 
$pagina->flagScriptCalendario = 0;
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;
$pagina->flagMenu = 0;
 
 

if($v_quantidade_formularios > 0){
	for ($i=0;$i<$v_quantidade_formularios;$i++){
?>
<input type="checkbox"  checked="checked" />
<table align="center" width="760" border="0">   
  <tr>
    <td width="200" height="50">  

		<table align="center"  border="1" width="200" bordercolor="black" cellpadding="0" cellspacing="0">
		<tr>
			<td >
			<img alt="" src="./imagens/logo_infraero.jpg" border="0">
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
				<font size="2">DATA</font> <br><br>
			</td>
			<td height="55" width="120" valign="top" align="left">
				<font size="2">Nº</font> <br><br>
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
				<font size="2">NOME DO REQUISITANTE </font> <br><br>
			</td>
			<td height="55" width="85" valign="top" align="left">
				<font size="2">UNIDADE</font>  <br><br>
			</td>
			<td height="55" width="85" valign="top" align="left">
				<font size="2">DIRETORIA</font>  <br><br>
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
				<td height="194" width="250" valign="top" align="left">
					<font size="2">FINALIDADE</font>  <br><br>
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
				<font size="2">VISTO REQUISITANTE</font>  <br><br>
			</td>
			<td width="150" valign="top" align="left">
				<font size="2">AUTORIZAÇÃO</font>  <br><br>
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
}

$pagina->MontaRodape();
?>
