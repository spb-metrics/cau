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
require_once 'include/PHP/class/class.parametro.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.tipo_ocorrencia.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.perfil_acesso.php';
$pagina = new Pagina();
$banco = new parametro();

if($flag == "1"){
	// Alterar os registros
	$banco->selectParam("1");
	if($banco->database->rows > 0){
		$parametro = new parametro();
		while ($row = pg_fetch_array($banco->database->result)){
			if($_POST["v_VAL_PARAMETRO_".$row["cod_parametro"]] != ""){
				$parametro->setNOM_PARAMETRO($row["nom_parametro"]);
				$parametro->setVAL_PARAMETRO(addslashes($_POST["v_VAL_PARAMETRO_".$row["cod_parametro"]]));
				$parametro->update($row["cod_parametro"]);
			}
		}
	}
}

// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Parametriza��o do Sistema"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
				   array("ParametroCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->method = "post";
$pagina->MontaCabecalho();
?>
<script language="javascript">
	function fValidaFormLocal(){
		return confirm("Confirma a atualiza��o dos par�metros do sistema? Esta a��o pode impactar no funcionamento de todo o sistema!");
	}
</script>
<?
print $pagina->CampoHidden("v_COD_PARAMETRO", "");
print $pagina->CampoHidden("flag", "1");

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
//$header[] = array("&nbsp;", "5%");
$header[] = array("C�digo", "30%");
$header[] = array("Descri��o", "40%");
$header[] = array("Valor", "30%");
$banco->selectParam("1");
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	//$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
	$pagina->LinhaHeaderTabelaResultado("Par�metros do Sistema", $header);
	while ($row = pg_fetch_array($banco->database->result)){
		$valor = $pagina->BotaoAlteraGridPesquisa("ParametroAlteracao.php?v_COD_PARAMETRO=".$row["cod_parametro"]."");
		$valor .= $pagina->BotaoExcluiGridPesquisa("v_COD_PARAMETRO", $row["cod_parametro"]);
		//$corpo[] = array("center", "campo", $valor);
		$corpo[] = array("left", "campo", $row["cod_parametro"]);
		$corpo[] = array("left", "campo", $row["nom_parametro"]);

		if( $row["cod_parametro"] == "COD_SITUACAO_Suspenso" ||
			$row["cod_parametro"] == "COD_SITUACAO_Encerrado" ||
			$row["cod_parametro"] == "COD_SITUACAO_Em_Andamento" ||
			$row["cod_parametro"] == "COD_SITUACAO_Contingenciado" ||
			$row["cod_parametro"] == "COD_SITUACAO_Aguardando_Planejamento" ||
			$row["cod_parametro"] == "COD_SITUACAO_Aguardando_Avaliacao" ||
			$row["cod_parametro"] == "COD_SITUACAO_Aguardando_Atendimento" ||
			$row["cod_parametro"] == "COD_SITUACAO_Cancelado" ||
			$row["cod_parametro"] == "COD_SITUACAO_Aguardando_Triagem"){
			// Montar a combo da tabela situacao_chamado
			$situacao_chamado = new situacao_chamado();
			$corpo[] = array("left", "campo", $pagina->CampoSelect("v_VAL_PARAMETRO_".$row["cod_parametro"], "S", $row["nom_parametro"], "N", $situacao_chamado->combo(2, $row["val_parametro"]), $propriedade=""));
		}elseif($row["cod_parametro"] == "SEQ_TIPO_OCORRENCIA_DUVIDA" ||
				$row["cod_parametro"] == "SEQ_TIPO_OCORRENCIA_IMPROCEDENTE" ||
				$row["cod_parametro"] == "SEQ_TIPO_OCORRENCIA_INCIDENTE" ||
				$row["cod_parametro"] == "SEQ_TIPO_OCORRENCIA_SOLICITACAO"){
			// Montar a combo da tabela situacao_chamado
			$tipo_ocorrencia = new tipo_ocorrencia();
			$tipo_ocorrencia->FLG_EXIBE_IMPROCEDENTE = 1;
			$corpo[] = array("left", "campo", $pagina->CampoSelect("v_VAL_PARAMETRO_".$row["cod_parametro"], "S", $row["nom_parametro"], "N", $tipo_ocorrencia->combo(2, $row["val_parametro"]), $propriedade=""));
		}elseif($row["cod_parametro"] == "COD_TIPO_SISTEMAS_INFORMACAO"){
			// Montar a combo da tabela situacao_chamado
			$tipo_chamado = new tipo_chamado();
			$corpo[] = array("left", "campo", $pagina->CampoSelect("v_VAL_PARAMETRO_".$row["cod_parametro"], "S", $row["nom_parametro"], "N", $tipo_chamado->combo2(2, $row["val_parametro"]), $propriedade=""));
		}elseif($row["cod_parametro"] == "COD_PERFIL_USUARIO_ADMINISTRACAO"){
			// Montar a combo da tabela situacao_chamado
			$perfil_acesso = new perfil_acesso();
			$corpo[] = array("left", "campo", $pagina->CampoSelect("v_VAL_PARAMETRO_".$row["cod_parametro"], "S", $row["nom_parametro"], "N", $perfil_acesso->combo(2, $row["val_parametro"]), $propriedade=""));
		}else{
			$corpo[] = array("left", "campo", $pagina->CampoTexto("v_VAL_PARAMETRO_".$row["cod_parametro"], "S", "", 50, 1500, $row["val_parametro"], $propriedade=""));
		}
		$pagina->LinhaTabelaResultado($corpo);
		$corpo = "";
	}
}
$pagina->LinhaCampoFormularioColspan("center", "<br>".$pagina->CampoButton("return fValidaForm() && fValidaFormLocal();", " Alterar "), count($header));
$pagina->FechaTabelaPadrao();
//$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_TIPO_SOFTWARE=$v_SEQ_TIPO_SOFTWARE&v_NOM_TIPO_SOFTWARE=$v_NOM_TIPO_SOFTWARE");
$pagina->MontaRodape();
?>
