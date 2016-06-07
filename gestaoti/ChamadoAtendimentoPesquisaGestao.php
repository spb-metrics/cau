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
require_once 'include/PHP/class/class.pagina.php';
require_once 'include/PHP/class/class.chamado.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.situacao_chamado.php';
require_once 'include/PHP/class/class.subtipo_chamado.php';
require_once 'include/PHP/class/class.tipo_chamado.php';
require_once 'include/PHP/class/class.destino_triagem.php';
require_once 'include/PHP/class/class.prioridade_chamado.php';
require_once 'include/PHP/class/class.atribuicao_chamado.php';
require_once 'include/PHP/class/class.time_sheet.php';
require_once 'include/PHP/class/class.tipo_ocorrencia.php';
$destino_triagem = new destino_triagem();
$tipo_chamado = new tipo_chamado();
$subtipo_chamado = new subtipo_chamado();
$situacao_chamado = new situacao_chamado();
$pagina = new Pagina();
$banco = new chamado();
$empregados = new empregados();
$prioridade_chamado = new prioridade_chamado();
$atribuicao_chamado = new atribuicao_chamado();
$time_sheet = new time_sheet();
$pagina->ForcaAutenticacao();
// Configuração da págína
$pagina->SettituloCabecalho("Gestão Operacional de Chamados"); // Indica o título do cabeçalho da página
// Inicio do formulário
$pagina->MontaCabecalho();
$pagina->LinhaVazia(1);
$pagina->AbreTabelaResultado("center", "100%");
$header = array();
$header[] = array("Prioridade", "6%");
$header[] = array($pagina->parametro->GetValorParametro("LABEL_TIPO_OCORRENCIA"), "6%");
$header[] = array("Chamado", "6%");
$header[] = array($pagina->parametro->GetValorParametro("LABEL_ATIVIDADE_CHAMADO"), "18%");
$header[] = array("Solicitação", "19%");
$header[] = array("Situação", "10%");
$header[] = array("Profissional(is)", "10%");
$header[] = array("Abertura", "10%");
$header[] = array("Vencimento", "10%");
$header[] = array("SLA", "5%");


$SQL_EXPORT ="";
$Rows = 0;


$_SEQ_CENTRAL_ATENDIMENTO = $_SESSION["SEQ_CENTRAL_ATENDIMENTO"];

// Loop de equipes
require_once 'include/PHP/class/class.equipe_ti.php';
$equipe_ti = new equipe_ti();
$equipe_ti->setSEQ_CENTRAL_ATENDIMENTO($_SEQ_CENTRAL_ATENDIMENTO);
$equipe_ti->selectParam("2", $vNumPagina);
if($equipe_ti->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	while ($rowEquipe = pg_fetch_array($equipe_ti->database->result)){
		$banco->setSEQ_EQUIPE_TI($rowEquipe["seq_equipe_ti"]);

		$situacao_chamado = new situacao_chamado();
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $situacao_chamado->CODS_EM_ANDAMENTO;
		$banco->setSEQ_SITUACAO_CHAMADO($v_SEQ_SITUACAO_CHAMADO_EXEC);

		$banco->AtenderChamadosDistinct("DTH_ABERTURA", $vNumPagina, 10);
		
		$SQL_EXPORT = $banco->SQL_EXPORT;
		$Rows = $banco->database->rows;
	
		if($banco->database->rows > 0){
			$corpo = array();
			$pagina->LinhaHeaderTabelaResultado("Chamados em atendimento na equipe ".$rowEquipe["nom_equipe_ti"], $header);
			$vLink = "?flag=1&v_SEQ_CHAMADO_PESQUISA=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL";
			while ($row = pg_fetch_array($banco->database->result)){
				// Prioridade
				$prioridade_chamado = new $prioridade_chamado();
				$prioridade_chamado->select($row["seq_prioridade_chamado"]);
				$corpo[] = array("left", "campo", $prioridade_chamado->DSC_PRIORIDADE_CHAMADO);

				// Tipo
				$tipo_ocorrencia = new tipo_ocorrencia();
				$tipo_ocorrencia->select($row["seq_tipo_ocorrencia"]);
				$corpo[] = array("left", "campo", strlen($tipo_ocorrencia->NOM_TIPO_OCORRENCIA)<15?$tipo_ocorrencia->NOM_TIPO_OCORRENCIA:substr($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, 0, strpos($tipo_ocorrencia->NOM_TIPO_OCORRENCIA, " ")));

				// Chamado
				$corpo[] = array("right", "campo", $row["seq_chamado"]);

				// Atividade
				$subtipo_chamado = new subtipo_chamado();
				$subtipo_chamado->select($row["seq_subtipo_chamado"]);
				$tipo_chamado = new tipo_chamado();
				$tipo_chamado->select($subtipo_chamado->SEQ_TIPO_CHAMADO);
				$corpo[] = array("left", "campo", $tipo_chamado->DSC_TIPO_CHAMADO." - ".$subtipo_chamado->DSC_SUBTIPO_CHAMADO." - ".$row["dsc_atividade_chamado"]);

				// Solicitação
				$corpo[] = array("left", "campo", $pagina->iif(strlen($row["txt_chamado"])>85,substr($row["txt_chamado"],0,85)."...", $row["txt_chamado"]));

				// Situação
				$situacao_chamado = new situacao_chamado();
				$situacao_chamado->select($row["seq_situacao_chamado"]);
				$corpo[] = array("left", "campo", $situacao_chamado->DSC_SITUACAO_CHAMADO);

				// Equipe
				$situacao_chamado = new situacao_chamado();
				$situacao_chamado->select($row["seq_situacao_chamado"]);
				$corpo[] = array("left", "campo", $atribuicao_chamado->EquipeAtendimento($row["seq_chamado"]));

				// Abertura
				$corpo[] = array("center", "campo", $row["dth_abertura"]);

				// Recuperar dados do SLA
				$v_DTH_ENCERRAMENTO_PREVISAO = $banco->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
				//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
				$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

				// Vencimento
				$corpo[] = array("center", "campo", "<font color=".$pagina->CorSLA($v_COD_SLA_ATENDIMENTO).">".$v_DTH_ENCERRAMENTO_PREVISAO."</font>");

				// SLA
				$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));

				$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhe.php".$vLink."&vNumPagina=$vNumPagina&v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
				$corpo = "";
			}
			
			if($Rows > 0){			 
				$pagina->LinhaColspan("center", $pagina->fMontarExportacao("ChamadoAtendimentoPesquisaGestaoDecorator.php",$SQL_EXPORT,"Chamados_em_atendimento_na_equipe_".$rowEquipe["nom_equipe_ti"],true), count($header), "campo");
	 		}
		}else{
			$pagina->LinhaColspan("center", "Chamados em atendimento na equipe ".$rowEquipe["nom_equipe_ti"], count($header), "header");
			$pagina->LinhaColspan("left", "Nenhum chamado encontrado", count($header), "campo");
		} 
		
		
	}
	
	
}
$pagina->FechaTabelaPadrao();
$pagina->MontaRodape();
?>
