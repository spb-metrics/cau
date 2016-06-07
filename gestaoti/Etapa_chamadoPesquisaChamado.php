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
	require '../gestaoti/include/PHP/class/class.pagina.php';
	require '../gestaoti/include/PHP/class/class.etapa_chamado.php';
}else{
	require 'include/PHP/class/class.pagina.php';
	require 'include/PHP/class/class.etapa_chamado.php';
}
$pagina = new Pagina();

// Realizar as ações
// Iniciar
if($flag == "1"){
	$etapa_chamado = new etapa_chamado();
	$etapa_chamado->SetInicioEfetivo($v_SEQ_ETAPA_CHAMADO);
}

// Encerrar
if($flag == "2"){
	$etapa_chamado = new etapa_chamado();
	$etapa_chamado->SetFimEfetivo($v_SEQ_ETAPA_CHAMADO);
}

if($v_SEQ_CHAMADO != ""){
	// Configuração da págína
	$pagina->flagScriptCalendario = 0;
	$pagina->flagMenu = 0;
	$pagina->flagTopo = 0;
	$pagina->MontaCabecalho();
	?>
	<script language="javascript">
		function IniciarEtapa(v_SEQ_ETAPA_CHAMADO){
			if(confirm("Confirma o início da etapa?")){
				document.form.flag.value = "1";
				document.form.v_SEQ_ETAPA_CHAMADO.value = v_SEQ_ETAPA_CHAMADO;
				document.form.submit();
			}
		}
		function EncerrarEtapa(v_SEQ_ETAPA_CHAMADO){
			if(confirm("Confirma o encerramento da etapa?")){
				document.form.flag.value = "2";
				document.form.v_SEQ_ETAPA_CHAMADO.value = v_SEQ_ETAPA_CHAMADO;
				document.form.submit();
			}
		}
	</script>
	<?
	print $pagina->CampoHidden("flag", "");
	print $pagina->CampoHidden("v_SEQ_ETAPA_CHAMADO", "");
	print $pagina->CampoHidden("v_SEQ_CHAMADO", $v_SEQ_CHAMADO);
	$pagina->AbreTabelaPadrao("center", "100%", "cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$etapa_chamado = new etapa_chamado();
	$etapa_chamado->setSEQ_CHAMADO($v_SEQ_CHAMADO);
	$etapa_chamado->selectParam("");
	if($etapa_chamado->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhuma etapa registrada", 2);
	}else{
		$tabela = array();
		$header = array();
		if($_SESSION["FLG_LIDER_EQUIPE"] == "S" && $flagReadOnly != "1"){
			$header[] = array("Ação", "center", "5%", "header");
		}
		$header[] = array("Etapa", "center", "26%", "header");
		$header[] = array("Início previsto", "center", "20%", "header");
		$header[] = array("Início efetivo", "center", "17%", "header");
		$header[] = array("Encerramento Previsto", "center", "20%", "header");
		$header[] = array("Encerramento Efetivo", "center", "17%", "header");
		$tabela[] = $header;

		while ($row = pg_fetch_array($etapa_chamado->database->result)){
			$header = array();
			if($_SESSION["FLG_LIDER_EQUIPE"] == "S" && $flagReadOnly != "1"){
				if($row["dth_inicio_efetivo"] == ""){
					$header[] = array($pagina->CampoButton("IniciarEtapa('".$row["seq_etapa_chamado"]."')", "Iniciar", "button", "envia", ""), "center", "", "");
				}elseif($row["dth_fim_efetivo"] == ""){
					$header[] = array($pagina->CampoButton("EncerrarEtapa('".$row["seq_etapa_chamado"]."')", "Encerrar", "button", "Iniciar", ""), "center", "", "");
				}else{
					$header[] = array("&nbsp;", "center", "", "");
				}
			}
			$header[] = array($row["nom_etapa_chamado"], "left", "", "campo");
			$header[] = array($row["dth_inicio_previsto"], "center", "", "campo");
			$header[] = array($row["dth_inicio_efetivo"]==""?"Não iniciado":$row["dth_inicio_efetivo"], "center", "", "campo");
			$header[] = array($row["dth_fim_previsto"], "center", "", "campo");
			$header[] = array($row["dth_fim_efetivo"]==""?"Não encerrado":$row["dth_fim_efetivo"], "center", "", "campo");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"","","1","1"), 2);
	}
	$pagina->FechaTabelaPadrao();
	print "</body></html>";
	//$pagina->MontaRodape();
}
?>
