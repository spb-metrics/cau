<?php
/*
Copyright 2011 da EMBRATUR
†Este arquivo È parte do programa CAU - Central de Atendimento ao Usu·rio
†O CAU È um software livre; vocÍ pode redistribuÌ-lo e/ou modific·-lo dentro dos termos da LicenÁa P˙blica Geral GNU como publicada pela 
 FundaÁ„o do Software Livre (FSF); na vers„o 2 da LicenÁa.
†Este programa È distribuÌdo na esperanÁa que possa ser† ˙til, mas SEM NENHUMA GARANTIA; sem uma garantia implÌcita de ADEQUA«√O a qualquer† 
 MERCADO ou APLICA«√O EM PARTICULAR. Veja a LicenÁa P˙blica Geral GNU/GPL em portuguÍs para maiores detalhes.
†Observe no diretÛrio gestaoti/install/ a cÛpia da LicenÁa P˙blica Geral GNU, sob o tÌtulo "licensa_uso.htm". 
 Se preferir acesse o Portal do Software P˙blico Brasileiro no endereÁo www.softwarepublico.gov.br ou escreva para a FundaÁ„o do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA† 02110-1301, USA
*/
require 'include/PHP/class/class.pagina.php';
$pagina = new Pagina();
?>
<link href="<?=$pagina->vPathPadrao?>include/CSS/visoes.css" rel="stylesheet" type="text/css" />

<div align="center">

<table class="hide" border="0" cellspacing="3" cellpadding="0">
<tr>
<td valign="top" width="50%">
<iframe id="x" name="x" src ="meus_chamados_aguardando_primeiro_nivel.php" width="50%" scrolling="no" />
</td>

<td valign="top" width="50%">



<table class="width100" cellspacing="1">
<tr>
		<td class="form-title" colspan="2">
		<a class="subtle" href="view_all_set.php?type=1&amp;temporary=y&amp;show_status=80&amp;hide_status=80">Resolved</a> [<a class="subtle" href="view_all_set.php?type=1&amp;temporary=y&amp;show_status=80&amp;hide_status=80" target="_blank">^</a>]		(1 - 10 / 398)	</td>
</tr>


<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7179" title="[resolved] Teste Mantis">0007179</a><br /><img src="http://www.mantisbt.org/demo/images/priority_1.gif" alt="" title="high" />		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		Teste Mantis		<br />
		[Demo] Other - <b>2009-11-10 09:12</b>		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7101" title="[resolved] test test">0007101</a><br /><img src="http://www.mantisbt.org/demo/images/priority_2.gif" alt="" title="urgent" />		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		test test		<br />
		[Demo] Other - 2009-10-30 06:10		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7079" title="[resolved] TEST">0007079</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		TEST		<br />
		[Demo] Website - 2009-10-27 12:52		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7086" title="[resolved] Test 1">0007086</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		Test 1		<br />
		[Demo] GUI - 2009-10-27 12:50		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7068" title="[resolved] application is crashing">0007068</a><br /><img src="http://www.mantisbt.org/demo/images/priority_1.gif" alt="" title="high" />		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		application is crashing		<br />
		[Demo] Website - 2009-10-25 12:26		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7029" title="[resolved] test">0007029</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		test		<br />
		[Demo] GUI - 2009-10-21 05:09		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7021" title="[resolved] pas possible d'imprimer">0007021</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		pas possible d'imprimer		<br />
		[Demo] Other - 2009-10-19 14:40		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7010" title="[resolved] Test">0007010</a><br /><img src="http://www.mantisbt.org/demo/images/priority_1.gif" alt="" title="high" />		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		Test		<br />
		[Demo] Website - 2009-10-18 17:23		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=6444" title="[resolved] testicht">0006444</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		testicht		<br />
		[Demo] GUI - 2009-10-16 06:16		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=6515" title="[resolved] sdafa">0006515</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		sdafa		<br />
		[Demo] Website - 2009-10-16 06:16		</span>
	</td>
</tr>

</table>

</td>
</tr>

<tr>
<td valign="top" width="50%">



<table class="width100" cellspacing="1">
<tr>
		<td class="form-title" colspan="2">
		<a class="subtle" href="view_all_set.php?type=1&amp;temporary=y&amp;hide_status=none">Recently Modified</a> [<a class="subtle" href="view_all_set.php?type=1&amp;temporary=y&amp;hide_status=none" target="_blank">^</a>]		(1 - 10 / 3249)	</td>
</tr>


<tr bgcolor="#ffa0a0">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7184" title="[new] efre">0007184</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		efre		<br />
		[Demo] GUI - <b>2009-11-10 12:09</b>		</span>
	</td>
</tr>

<tr bgcolor="#c8c8ff">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7183" title="[assigned] ad">0007183</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		ad		<br />
		[Demo] Other - <b>2009-11-10 11:40</b>		</span>
	</td>
</tr>

<tr bgcolor="#c8c8ff">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=5982" title="[assigned] good">0005982</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		good		<br />
		[Demo] GUI - <b>2009-11-10 11:11</b>		</span>
	</td>
</tr>

<tr bgcolor="#ffd850">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=4125" title="[acknowledged] issue with filing bug">0004125</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		issue with filing bug		<br />
		[Demo] GUI - <b>2009-11-10 11:11</b>		</span>
	</td>
</tr>

<tr bgcolor="#c8c8ff">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=5752" title="[assigned] Ei toimi viel‰k‰‰n helppi;)">0005752</a><br /><img src="http://www.mantisbt.org/demo/images/priority_1.gif" alt="" title="high" />		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		Ei toimi viel‰k‰‰n helppi;)		<br />
		[Demo] Other - <b>2009-11-10 11:11</b>		</span>
	</td>
</tr>

<tr bgcolor="#c8c8ff">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=4771" title="[assigned] kljlk">0004771</a><br /><img src="http://www.mantisbt.org/demo/images/priority_3.gif" alt="" title="immediate" />		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		kljlk		<br />
		[Demo] Website - <b>2009-11-10 11:11</b>		</span>
	</td>
</tr>

<tr bgcolor="#cceedd">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7179" title="[resolved] Teste Mantis">0007179</a><br /><img src="http://www.mantisbt.org/demo/images/priority_1.gif" alt="" title="high" />		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		Teste Mantis		<br />
		[Demo] Other - <b>2009-11-10 09:12</b>		</span>
	</td>
</tr>

<tr bgcolor="#e8e8e8">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7178" title="[closed] It doesn't work with SQL Server 2200">0007178</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		It doesn't work with SQL Server 2200		<br />
		[Demo] Other - <b>2009-11-10 08:50</b>		</span>
	</td>
</tr>

<tr bgcolor="#ffa0a0">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7177" title="[new] Bild nicht geladen">0007177</a><br />&nbsp;		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		Bild nicht geladen		<br />
		[Demo] Website - 2009-11-10 06:32		</span>
	</td>
</tr>

<tr bgcolor="#e8e8e8">
		<td class="center" valign="top" width ="0" nowrap>
		<span class="small">
		<a href="view.php?id=7173" title="[closed] lost his voucher">0007173</a><br /><img src="http://www.mantisbt.org/demo/images/priority_3.gif" alt="" title="immediate" />		</span>
	</td>

		<td class="left" valign="top" width="100%">
		<span class="small">
		lost his voucher		<br />
		[Demo] GUI - 2009-11-10 06:11		</span>
	</td>
</tr>

</table>

</td>

<tr>
<td colspan="2">

	<br />
	<table class="width100" cellspacing="1">
	<tr>
		<td class="small-caption" width="14%" bgcolor="#ffa0a0">Aguardando Atendimento de 1∫ NÌvel</td>
		<td class="small-caption" width="14%" bgcolor="#ff50a8">feedback</td>
		<td class="small-caption" width="14%" bgcolor="#ffd850">acknowledged</td>
		<td class="small-caption" width="14%" bgcolor="#ffffb0">confirmed</td>
		<td class="small-caption" width="14%" bgcolor="#c8c8ff">assigned</td>
		<td class="small-caption" width="14%" bgcolor="#cceedd">resolved</td>
		<td class="small-caption" width="14%" bgcolor="#e8e8e8">closed</td>
	</tr>
	</table>

</td>
</tr>
</table>

</div>
