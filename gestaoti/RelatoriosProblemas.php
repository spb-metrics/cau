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
 require_once 'include/PHP/class/class.parametro.php';
 require_once 'include/PHP/class/class.chamado.php';
 require_once 'include/PHP/class/class.prioridade_chamado.php';
 require_once 'include/PHP/class/class.subtipo_chamado.php';
 require_once 'include/PHP/class/class.tipo_chamado.php';
 require_once 'include/PHP/class/class.vinculo_chamado.php';
/*****************************************************************/

/******************************************************************
 * Criando os objetos.
 *****************************************************************/
 //$destino_triagem	= new destino_triagem();
 $pagina 			= new Pagina();
 $parametro			= new parametro();
/*****************************************************************/
// Configuração da págína
$pagina->SettituloCabecalho("Relatório de Gestão de Problemas"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();
print $pagina->CampoHidden("flag", "1");
// ============================================================================================================
// Configurações AJAX JAVASCRIPTS
// ============================================================================================================
?>
<script language="javascript">
	function fValidaFormLocal(){

		if(document.form.v_DTH_ABERTURA.value == ""){
			alert("Preencha o campo Data de Início");
			document.form.v_DTH_ABERTURA.focus();
			return false;
		}
		if(document.form.v_DTH_ABERTURA_FINAL.value == ""){
			alert("Preencha o campo Data de Encerramento");
			document.form.v_DTH_ABERTURA_FINAL.focus();
			return false;
		}
		if(!comparaDatas(document.form.v_DTH_ABERTURA, document.form.v_DTH_ABERTURA_FINAL)){
			alert("A data de início deve ser menor que a data final.");
		 	return false;
		}
		//document.form.action = 'RelatoriosKPIResolucaoSolicitacaoPDF.php';
		//document.form.target = '_blank';
		return true;
	}
</script>
<?
$pagina->AbreTabelaPadrao("center", "100%");
$pagina->LinhaCampoFormulario("Período:", "right", "S", "de " .$pagina->CampoData("v_DTH_ABERTURA", "N", " de Abertura", $v_DTH_ABERTURA)." a ".$pagina->CampoData("v_DTH_ABERTURA_FINAL", "N", " de Encerramento efetivo", $v_DTH_ABERTURA_FINAL), "left", "id=".$pagina->GetIdTable());
$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Gerar Relatório "), "2");
$pagina->FechaTabelaPadrao();

if($flag == "1"){
	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Chamado", "5%");
//	$header[] = array("Atividade", "20%");
	$header[] = array("Solicitação", "17%");
	$header[] = array("Solução de Contingenciamento", "17%");
	$header[] = array("Solução Final", "17%");
	$header[] = array("Cauza Raiz", "17%");
	$header[] = array("Chamados Vinculados", "10%");
	$header[] = array("Abertura", "5%");
	$header[] = array("Conclusão", "5%");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);

	$banco = new chamado();

	$banco->setSEQ_TIPO_OCORRENCIA($parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_INCIDENTE"));
	$banco->setDTH_ENCERRAMENTO_EFETIVO($v_DTH_ABERTURA);
	$banco->setDTH_ABERTURA($v_DTH_ABERTURA);
	$banco->setDTH_ABERTURA_FINAL($v_DTH_ABERTURA_FINAL);

	$banco->selectParam("DTH_ABERTURA DESC", $vNumPagina);
	if($banco->database->rows > 0){
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Incidentes resolvidos no período selecionado", $header);
		$vLink = "?flag=1&v_SEQ_CHAMADO_PESQUISA=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL";
		while ($row = pg_fetch_array($banco->database->result)){
			$vinculo_chamado = new vinculo_chamado();
			$vinculo_chamado->setSEQ_CHAMADO_FILHO($row["seq_chamado"]);
			$vinculo_chamado->selectParam();
			if($vinculo_chamado->database->rows == 0){
				// Chamado
				$corpo[] = array("right", "campo", $row["seq_chamado"]);

				/*
				// Atividade
				$subtipo_chamado = new subtipo_chamado();
				$subtipo_chamado->select($row["seq_subtipo_chamado"]);
				$tipo_chamado = new tipo_chamado();
				$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
				$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);
				*/

				// Solicitação
				$corpo[] = array("left", "campo", $row["txt_chamado"]);
				$corpo[] = array("left", "campo", $row["txt_contingenciamento"]);
				$corpo[] = array("left", "campo", $row["txt_resolucao"]);
				$corpo[] = array("left", "campo", $row["txt_causa_raiz"]);

				$vinculo_chamado = new vinculo_chamado();
				$vinculo_chamado->setSEQ_CHAMADO_MASTER($row["seq_chamado"]);
				$vinculo_chamado->selectParam();
				if($vinculo_chamado->database->rows > 0){
					$vVinculos = "";
					$count = 0;
					while ($row2 = pg_fetch_array($vinculo_chamado->database->result)){
						$vVinculos .= $row2["seq_chamado_filho"];
						$count++;
						if($count != $vinculo_chamado->database->rows) $vVinculos .= ", ";
					}

					$corpo[] = array("left", "campo", $vVinculos);
				}else{
					$corpo[] = array("left", "campo", "&nbsp;");
				}


				// Abertura
				$corpo[] = array("center", "campo", $row["dth_abertura"]);

				// Recuperar dados do SLA
				$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
				//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
				$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

				// Vencimento
				$corpo[] = array("center", "campo", "<font color=".$pagina->CorSLA($v_COD_SLA_ATENDIMENTO).">".$row["dth_encerramento_efetivo"]."</font>");

				// SLA
				//$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));

				$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhe.php".$vLink."&vNumPagina=$vNumPagina&v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
				$corpo = "";
			}
		}
		$pagina->FechaTabelaPadrao();
		$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL&v_SEQ_TIPO_OCORRENCIA=$v_SEQ_TIPO_OCORRENCIA");
	}else{
		$pagina->LinhaColspan("center", "Chamados encontrados para os parâmetros informados", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}
	$pagina->FechaTabelaPadrao();
}

$pagina->MontaRodape();
?>
