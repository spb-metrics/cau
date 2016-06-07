<?php
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
/*
* -------------------------------------------------------
* Nome da Classe:	chamado
* Nome da tabela:	chamado
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	require_once "../gestaoti/include/PHP/class/class.database.postgres.php";
	require_once "../gestaoti/include/PHP/class/class.pagina.php";
	require_once "../gestaoti/include/PHP/class/class.aprovacao_chamado.php";
	require_once "../gestaoti/include/PHP/class/class.historico_chamado.php";
	require_once "../gestaoti/include/PHP/class/class.tipo_ocorrencia.php";
	require_once "../gestaoti/include/PHP/class/class.vinculo_chamado.php";
	require_once "../gestaoti/include/PHP/class/class.situacao_chamado.php";
}else{
	require_once "include/PHP/class/class.database.postgres.php";
	require_once "include/PHP/class/class.pagina.php";
	require_once "include/PHP/class/class.aprovacao_chamado.php";
	require_once "include/PHP/class/class.historico_chamado.php";
	require_once "include/PHP/class/class.tipo_ocorrencia.php";
	require_once "include/PHP/class/class.vinculo_chamado.php";
	require_once "include/PHP/class/class.situacao_chamado.php";
}
// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class chamado{
	// class : begin
	
	var $SQL_EXPORT;

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************
	var $SEQ_CHAMADO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NUM_MATRICULA_SOLICITANTE;   // (normal Attribute)
	var $SEQ_ATIVIDADE_CHAMADO;   // (normal Attribute)
	var $SEQ_SITUACAO_CHAMADO;   // (normal Attribute)
	var $SEQ_LOCALIZACAO_FISICA;   // (normal Attribute)
	var $SEQ_PRIORIDADE_CHAMADO;   // (normal Attribute)
	var $TXT_CHAMADO;   // (normal Attribute)
	var $DTH_ABERTURA;   // (normal Attribute)
	var $DTH_ABERTURA_FINAL;   // (normal Attribute)
	var $DTH_TRIAGEM_EFETIVA;   // (normal Attribute)
	var $DTH_TRIAGEM_EFETIVA_FINAL;   // (normal Attribute)
	var $DTH_INICIO_PREVISAO;   // (normal Attribute)
	var $DTH_INICIO_PREVISAO_FINAL;   // (normal Attribute)
	var $DTH_INICIO_EFETIVO;   // (normal Attribute)
	var $DTH_INICIO_EFETIVO_FINAL;   // (normal Attribute)
	var $DTH_ENCERRAMENTO_EFETIVO;   // (normal Attribute)
	var $DTH_ENCERRAMENTO_EFETIVO_FINAL;   // (normal Attribute)
	var $DTH_AGENDAMENTO;   // (normal Attribute)
	var $DTH_AGENDAMENTO_FINAL;   // (normal Attribute)
	var $NUM_MATRICULA_CONTATO;   // (normal Attribute)
	var $SEQ_ITEM_CONFIGURACAO;   // (normal Attribute)
	var $SEQ_TIPO_OCORRENCIA;
	var $NUM_PRIORIDADE_FILA;   // (normal Attribute)
	var $QTD_MIN_SLA_TRIAGEM;
	var $QTD_MIN_SLA_ATENDIMENTO;
	var $QTD_MIN_SLA_SOLUCAO_FINAL;
	var $MIN_INICIO_PREVISAO;
	var $DTH_ENCERRAMENTO_PREVISAO;
	var $MIN_ENCERRAMENTO_PREVISAO;
	var $SEQ_TIPO_CHAMADO;
	var $SEQ_SUBTIPO_CHAMADO;
	var $DSC_ATIVIDADE_CHAMADO;
	var $PESQUISA_TRIAGEM;
	var $PESQUISA_ATENDIMENTO;
	var $COD_DEPENDENCIA;
	var $SEQ_EQUIPE_TI;
	var $NUM_MATRICULA_EXECUTOR;
	var $NUM_MATRICULA_NAO_EXECUTOR;
	var $COD_SLA_ATENDIMENTO;
	var $FLG_ATENDIMENTO_EXTERNO;
	var $COD_DEPENDENCIA_LOCALIZACAO;
	var $SEQ_edificacao;
	var $COD_DEPENDENCIA_ATRIBUICAO;

	var $DSC_TIPO_CHAMADO;
	var $DSC_SUBTIPO_CHAMADO;
	var $NOM_CLIENTE;
	var $EMAIL_CLIENTE;
	var $aprovacao_chamado;
	var $aDtFeriado;
	var $FLG_FORMA_MEDICAO_TEMPO;
	var $NUM_PATRIMONIO;
	var $TXT_CONTINGENCIAMENTO;
	var $TXT_CAUSA_RAIZ;
	var $NUM_MATRICULA_CADASTRANTE;
	var $SEQ_ACAO_CONTINGENCIAMENTO;
	var $FLG_VINCULO;
	var $SEQ_CHAMADO_MASTER;
	var $NOM_TIPO_OCORRENCIA;
	var $TXT_RESOLUCAO;

	var $FLG_SOLICITACAO_ATENDIDA;
	var $NUM_MATRICULA_AVALIADOR;
	var $TXT_AVALIACAO;
	var $SEQ_AVALIACAO_ATENDIMENTO;
	var $SEQ_AVALIACAO_CONHECIMENTO_TECNICO;
	var $SEQ_AVALIACAO_POSTURA;
	var $SEQ_AVALIACAO_TEMPO_ESPERA;
	var $SEQ_AVALIACAO_TEMPO_SOLUCAO;

	var $HoraInicioExpediente;
	var $HoraInicioIntervalo;
	var $HoraFimIntervalo;
	var $HoraFimExpediente;

	var $database; // Instance of class database
	var $situacao_chamado;
	var $historico_chamado;
	var $vinculo_chamado;
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	var $pagina;
	
	var $SEQ_MOTIVO_CANCELAMENTO;
	
	var $parametro;
	var $OBJETIVO_EVENTO;
 	var $DTH_RESERVA_EVENTO;
  	var $QUANTIDADE_PESSOAS_EVENTO;
  	var $SERVICOS_EVENTO;
 	var $DT_INICIO_UTILIZACAO_APARELHO;
  	var $DT_FIM_UTILIZACAO_APARELHO;
  	var $SEQ_CENTRAL_ATENDIMENTO;
  	
  	
  	var $NUM_MATRICULA_APROVADOR_ATIVIDADE;   
  	var $FLG_DESTINACAO_CHAMADO; 
  	var $UOR_ID;    
	var $COOR_ID; 
  	 

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function chamado(){
		$this->database = new Database();
		$this->pagina = new pagina();
		$this->aDtFeriado           = $this->ArrayFeriados();
		$this->HoraInicioExpediente = $this->pagina->parametro->GetValorParametro("HoraInicioExpediente");
		$this->HoraInicioIntervalo  = $this->pagina->parametro->GetValorParametro("HoraInicioIntervalo");
		$this->HoraFimIntervalo     = $this->pagina->parametro->GetValorParametro("HoraFimIntervalo");
		$this->HoraFimExpediente    = $this->pagina->parametro->GetValorParametro("HoraFimExpediente");

		$this->tipo_ocorrencia = new tipo_ocorrencia();
		
		$this->parametro = new parametro();
	}

	// **********************
	// GETTER METHODS
	// **********************
	function getrowCount(){
		return $this->rowCount;
	}

	function getvQtdRegistros(){
		return $this->vQtdRegistros;
	}

	function getSEQ_CHAMADO(){
		return $this->SEQ_CHAMADO;
	}

	function getNUM_MATRICULA_SOLICITANTE(){
		return $this->NUM_MATRICULA_SOLICITANTE;
	}

	function getSEQ_ATIVIDADE_CHAMADO(){
		return $this->SEQ_ATIVIDADE_CHAMADO;
	}

	function getSEQ_SITUACAO_CHAMADO(){
		return $this->SEQ_SITUACAO_CHAMADO;
	}

	function getSEQ_LOCALIZACAO_FISICA(){
		return $this->SEQ_LOCALIZACAO_FISICA;
	}

	function getSEQ_PRIORIDADE_CHAMADO(){
		return $this->SEQ_PRIORIDADE_CHAMADO;
	}

	function getTXT_CHAMADO(){
		return $this->TXT_CHAMADO;
	}

	function getDTH_ABERTURA(){
		return $this->DTH_ABERTURA;
	}

	function getDTH_TRIAGEM_EFETIVA(){
		return $this->DTH_TRIAGEM_EFETIVA;
	}

	function getDTH_INICIO_PREVISAO(){
		return $this->DTH_INICIO_PREVISAO;
	}

	function getDTH_INICIO_EFETIVO(){
		return $this->DTH_INICIO_EFETIVO;
	}

	function getDTH_ENCERRAMENTO_EFETIVO(){
		return $this->DTH_ENCERRAMENTO_EFETIVO;
	}

	function getDTH_AGENDAMENTO(){
		return $this->DTH_AGENDAMENTO;
	}

	function getNUM_MATRICULA_CONTATO(){
		return $this->NUM_MATRICULA_CONTATO;
	}

	function getSEQ_ITEM_CONFIGURACAO(){
		return $this->SEQ_ITEM_CONFIGURACAO;
	}

	function getNUM_PRIORIDADE_FILA(){
		return $this->NUM_PRIORIDADE_FILA;
	}

	function getFLG_SOLICITACAO_ATENDIDA(){
		return $this->FLG_SOLICITACAO_ATENDIDA;
	}
	function getNUM_MATRICULA_AVALIADOR(){
		return $this->NUM_MATRICULA_AVALIADOR;
	}
	function getTXT_AVALIACAO(){
		return $this->TXT_AVALIACAO;
	}
	function getSEQ_AVALIACAO_ATENDIMENTO(){
		return $this->SEQ_AVALIACAO_ATENDIMENTO;
	}
	function getSEQ_TIPO_OCORRENCIA(){
		return $this->SEQ_TIPO_OCORRENCIA;
	}
	function getFLG_FORMA_MEDICAO_TEMPO(){
		return $this->FLG_FORMA_MEDICAO_TEMPO;
	}
	function getNUM_MATRICULA_CADASTRANTE(){
		return $this->NUM_MATRICULA_CADASTRANTE;
	}

	function getOBJETIVO_EVENTO(){
		return $this->OBJETIVO_EVENTO;
	}
	function getDTH_RESERVA_EVENTO(){
		return $this->DTH_RESERVA_EVENTO;
	}
	 
	function getQUANTIDADE_PESSOAS_EVENTO(){
		return $this->QUANTIDADE_PESSOAS_EVENTO;
	}
	function getSERVICOS_EVENTO(){
		return $this->SERVICOS_EVENTO;
	}
	function getDT_INICIO_UTILIZACAO_APARELHO(){
		return $this->DT_INICIO_UTILIZACAO_APARELHO;
	}
	function getDT_FIM_UTILIZACAO_APARELHO(){
		return $this->DT_FIM_UTILIZACAO_APARELHO;
	}
	function getSEQ_CENTRAL_ATENDIMENTO(){
		return $this->SEQ_CENTRAL_ATENDIMENTO;
	}
	
	function getNUM_MATRICULA_APROVADOR_ATIVIDADE(){
		return $this->NUM_MATRICULA_APROVADOR_ATIVIDADE;
	}
	function getFLG_DESTINACAO_CHAMADO(){
		return $this->FLG_DESTINACAO_CHAMADO;
	}
	function getUOR_ID(){
		return $this->UOR_ID;
	}
	function getCOOR_ID(){
		return $this->COOR_ID;
	}
  	 	 
	 
	// **********************
	// SETTER METHODS
	// **********************
	function setrowCount($val){
		$this->rowCount = $val;
	}

	function setvQtdRegistros($val){
		$this->vQtdRegistros = $val;
	}

	function setSEQ_CHAMADO($val){
		$this->SEQ_CHAMADO =  $val;
	}

	function setNUM_MATRICULA_SOLICITANTE($val){
		$this->NUM_MATRICULA_SOLICITANTE =  $val;
	}

	function setSEQ_ATIVIDADE_CHAMADO($val){
		$this->SEQ_ATIVIDADE_CHAMADO =  $val;
	}

	function setSEQ_SITUACAO_CHAMADO($val){
		$this->SEQ_SITUACAO_CHAMADO =  $val;
	}

	function setSEQ_LOCALIZACAO_FISICA($val){
		$this->SEQ_LOCALIZACAO_FISICA =  $val;
	}

	function setSEQ_PRIORIDADE_CHAMADO($val){
		$this->SEQ_PRIORIDADE_CHAMADO =  $val;
	}

	function setTXT_CHAMADO($val){
		$this->TXT_CHAMADO =  $val;
	}

	function setDTH_ABERTURA($val){
		$this->DTH_ABERTURA =  $val;
	}

	function setDTH_ABERTURA_FINAL($val){
		$this->DTH_ABERTURA_FINAL =  $val;
	}

	function setDTH_TRIAGEM_EFETIVA($val){
		$this->DTH_TRIAGEM_EFETIVA =  $val;
	}

	function setDTH_TRIAGEM_EFETIVA_FINAL($val){
		$this->DTH_TRIAGEM_EFETIVA_FINAL =  $val;
	}

	function setDTH_INICIO_PREVISAO($val){
		$this->DTH_INICIO_PREVISAO =  $val;
	}

	function setDTH_INICIO_PREVISAO_FINAL($val){
		$this->DTH_INICIO_PREVISAO_FINAL =  $val;
	}

	function setDTH_INICIO_EFETIVO($val){
		$this->DTH_INICIO_EFETIVO =  $val;
	}

	function setDTH_INICIO_EFETIVO_FINAL($val){
		$this->DTH_INICIO_EFETIVO_FINAL =  $val;
	}

	function setDTH_ENCERRAMENTO_EFETIVO($val){
		$this->DTH_ENCERRAMENTO_EFETIVO =  $val;
	}

	function setDTH_ENCERRAMENTO_EFETIVO_FINAL($val){
		$this->DTH_ENCERRAMENTO_EFETIVO_FINAL =  $val;
	}

	function setDTH_AGENDAMENTO($val){
		$this->DTH_AGENDAMENTO =  $val;
	}

	function setDTH_AGENDAMENTO_FINAL($val){
		$this->DTH_AGENDAMENTO_FINAL =  $val;
	}

	function setNUM_MATRICULA_CONTATO($val){
		$this->NUM_MATRICULA_CONTATO =  $val;
	}

	function setSEQ_ITEM_CONFIGURACAO($val){
		$this->SEQ_ITEM_CONFIGURACAO =  $val;
	}

	function setNUM_PRIORIDADE_FILA($val){
		$this->NUM_PRIORIDADE_FILA =  $val;
	}

	function setPESQUISA_TRIAGEM($val){
		$this->PESQUISA_TRIAGEM = $val;
	}
	function setCOD_DEPENDENCIA($val){
		$this->COD_DEPENDENCIA = $val;
	}

	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI = $val;
	}
	function setNUM_MATRICULA_EXECUTOR($val){
		$this->NUM_MATRICULA_EXECUTOR = $val;
	}

	function setPESQUISA_ATENDIMENTO($val){
		$this->PESQUISA_ATENDIMENTO = $val;
	}
	function setNUM_MATRICULA_NAO_EXECUTOR($val){
		$this->NUM_MATRICULA_NAO_EXECUTOR = $val;
	}
	function setCOD_SLA_ATENDIMENTO($val){
		$this->COD_SLA_ATENDIMENTO = $val;
	}

	function setSEQ_TIPO_CHAMADO($val){
		$this->SEQ_TIPO_CHAMADO = $val;
	}

	function setSEQ_SUBTIPO_CHAMADO($val){
		$this->SEQ_SUBTIPO_CHAMADO = $val;
	}

	function setCOD_DEPENDENCIA_LOCALIZACAO($val){
		$this->COD_DEPENDENCIA_LOCALIZACAO = $val;
	}

	function setSEQ_edificacao($val){
		$this->SEQ_edificacao = $val;
	}
	function setCOD_DEPENDENCIA_ATRIBUICAO($val){
		$this->COD_DEPENDENCIA_ATRIBUICAO = $val;
	}
	function setFLG_SOLICITACAO_ATENDIDA($val){
		$this->FLG_SOLICITACAO_ATENDIDA = $val;;
	}
	function setNUM_MATRICULA_AVALIADOR($val){
		$this->NUM_MATRICULA_AVALIADOR = $val;;
	}
	function setTXT_AVALIACAO($val){
		$this->TXT_AVALIACAO = $val;;
	}
	function setSEQ_AVALIACAO_ATENDIMENTO($val){
		$this->SEQ_AVALIACAO_ATENDIMENTO = $val;
	}
	function setSEQ_TIPO_OCORRENCIA($val){
		$this->SEQ_TIPO_OCORRENCIA = $val;
	}
	function setFLG_FORMA_MEDICAO_TEMPO($val){
		$this->FLG_FORMA_MEDICAO_TEMPO = $val;
	}
	function setNUM_PATRIMONIO($val){
		$this->NUM_PATRIMONIO = $val;
	}
	function setTXT_CONTINGENCIAMENTO($val){
		$this->TXT_CONTINGENCIAMENTO = $val;
	}
	function setTXT_CAUSA_RAIZ($val){
		$this->TXT_CAUSA_RAIZ = $val;
	}
	function setNUM_MATRICULA_CADASTRANTE($val){
		$this->NUM_MATRICULA_CADASTRANTE = $val;
	}
	function setSEQ_ACAO_CONTINGENCIAMENTO($val){
		$this->SEQ_ACAO_CONTINGENCIAMENTO = $val;
	}
	function setFLG_VINCULO($val){
		$this->FLG_VINCULO = $val;
	}
	function setSEQ_CHAMADO_MASTER($val){
		$this->SEQ_CHAMADO_MASTER = $val;
	}

	function setTXT_RESOLUCAO($val){
		$this->TXT_RESOLUCAO = $val;
	}

	function setSEQ_AVALIACAO_CONHECIMENTO_TECNICO($val){
		$this->SEQ_AVALIACAO_CONHECIMENTO_TECNICO = $val;
	}
	function setSEQ_AVALIACAO_POSTURA($val){
		$this->SEQ_AVALIACAO_POSTURA = $val;
	}
	function setSEQ_AVALIACAO_TEMPO_ESPERA($val){
		$this->SEQ_AVALIACAO_TEMPO_ESPERA = $val;
	}
	function setSEQ_AVALIACAO_TEMPO_SOLUCAO($val){
		$this->SEQ_AVALIACAO_TEMPO_SOLUCAO = $val;
	}
	
	function setOBJETIVO_EVENTO($val){
		$this->OBJETIVO_EVENTO = $val;
	}
	function setDTH_RESERVA_EVENTO($val){
		$this->DTH_RESERVA_EVENTO = $val;
	}
	function setQUANTIDADE_PESSOAS_EVENTO($val){
		$this->QUANTIDADE_PESSOAS_EVENTO = $val;
	}
	function setSERVICOS_EVENTO($val){
		$this->SERVICOS_EVENTO = $val;
	}
	function setDT_INICIO_UTILIZACAO_APARELHO($val){
		$this->DT_INICIO_UTILIZACAO_APARELHO = $val;
	}
	function setDT_FIM_UTILIZACAO_APARELHO($val){
		$this->DT_FIM_UTILIZACAO_APARELHO = $val;
	} 
	function setSEQ_CENTRAL_ATENDIMENTO($val){
		$this->SEQ_CENTRAL_ATENDIMENTO = $val;
	} 	
	function setNUM_MATRICULA_APROVADOR_ATIVIDADE($val){
		$this->NUM_MATRICULA_APROVADOR_ATIVIDADE = $val;
	} 
	function setFLG_DESTINACAO_CHAMADO($val){
		$this->FLG_DESTINACAO_CHAMADO = $val;
	} 
	
	function setUOR_ID($val){
		$this->UOR_ID = $val;
	} 
	function setCOOR_ID($val){
		$this->COOR_ID = $val;
	} 
	 
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT SEQ_CHAMADO, NUM_MATRICULA_SOLICITANTE, b.SEQ_ATIVIDADE_CHAMADO, SEQ_SITUACAO_CHAMADO, SEQ_LOCALIZACAO_FISICA,
                               SEQ_PRIORIDADE_CHAMADO, TXT_CHAMADO, to_char(DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA,
                               DTH_ABERTURA as DTH_ABERTURA_DATA, to_char(DTH_TRIAGEM_EFETIVA, 'dd/mm/yyyy hh24:mi:ss') as DTH_TRIAGEM_EFETIVA,
                               DTH_TRIAGEM_EFETIVA as DTH_TRIAGEM_EFETIVA_DATA, to_char(DTH_INICIO_PREVISAO, 'dd-mm-yyyy hh24:mi:ss') as DTH_INICIO_PREVISAO,
                               DTH_INICIO_PREVISAO as DTH_INICIO_PREVISAO_DATA , to_char(a.DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO,
                               a.DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA , to_char(a.DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO,
                               a.DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA , to_char(DTH_AGENDAMENTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_AGENDAMENTO,
                               DTH_AGENDAMENTO as DTH_AGENDAMENTO_DATA , NUM_MATRICULA_CONTATO , SEQ_ITEM_CONFIGURACAO , NUM_PRIORIDADE_FILA,
                               b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_ATENDIMENTO, b.QTD_MIN_SLA_SOLUCAO_FINAL, b.DSC_ATIVIDADE_CHAMADO, b.SEQ_SUBTIPO_CHAMADO,
                               b.FLG_ATENDIMENTO_EXTERNO, a.SEQ_TIPO_OCORRENCIA, a.TXT_CONTINGENCIAMENTO, a.TXT_CAUSA_RAIZ, a.TXT_RESOLUCAO,
                               a.NUM_MATRICULA_CADASTRANTE, SEQ_ACAO_CONTINGENCIAMENTO, b.FLG_FORMA_MEDICAO_TEMPO, SEQ_AVALIACAO_ATENDIMENTO,
                               SEQ_AVALIACAO_CONHECIMENTO_TECNICO, SEQ_AVALIACAO_POSTURA, SEQ_AVALIACAO_TEMPO_ESPERA, SEQ_AVALIACAO_TEMPO_SOLUCAO,
                               FLG_SOLICITACAO_ATENDIDA, NUM_MATRICULA_AVALIADOR, TXT_AVALIACAO, SEQ_MOTIVO_CANCELAMENTO,
                               a.objetivo_evento,a.quantidade_pessoas_evento,a.servicos_evento,a.dth_reserva_evento,
                               a.dt_inicio_utilizacao_aparelho,a.dt_fim_utilizacao_aparelho,FLG_DESTINACAO_CHAMADO	   
                        FROM gestaoti.chamado a, gestaoti.atividade_chamado b
                        WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
                        and SEQ_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->SEQ_CHAMADO = $row->seq_chamado;
		$this->NUM_MATRICULA_SOLICITANTE = $row->num_matricula_solicitante;
		$this->SEQ_ATIVIDADE_CHAMADO = $row->seq_atividade_chamado;
		$this->SEQ_SITUACAO_CHAMADO = $row->seq_situacao_chamado;
		$this->SEQ_LOCALIZACAO_FISICA = $row->seq_localizacao_fisica;
		$this->SEQ_PRIORIDADE_CHAMADO = $row->seq_prioridade_chamado;
		$this->TXT_CHAMADO = $row->txt_chamado;
		$this->DTH_ABERTURA = $row->dth_abertura;
		$this->DTH_TRIAGEM_EFETIVA = $row->dth_triagem_efetiva;
		$this->DTH_INICIO_EFETIVO = $row->dth_inicio_efetivo;
		$this->DTH_ENCERRAMENTO_EFETIVO = $row->dth_encerramento_efetivo;
		$this->DTH_AGENDAMENTO = $row->dth_agendamento;
		$this->NUM_MATRICULA_CONTATO = $row->num_matricula_contato;
		$this->SEQ_ITEM_CONFIGURACAO = $row->seq_item_configuracao;
		$this->NUM_PRIORIDADE_FILA = $row->num_prioridade_fila;
		$this->SEQ_SUBTIPO_CHAMADO = $row->seq_subtipo_chamado;
		$this->DSC_ATIVIDADE_CHAMADO = $row->dsc_atividade_chamado;
		$this->FLG_ATENDIMENTO_EXTERNO = $row->flg_atendimento_externo;
		$this->FLG_SOLICITACAO_ATENDIDA = $row->flg_solicitacao_atendida;
		$this->DTH_INICIO_PREVISAO = $row->dth_inicio_previsao;
		$this->QTD_MIN_SLA_TRIAGEM =  $row->qtd_min_sla_triagem;
		$this->QTD_MIN_SLA_ATENDIMENTO =  $row->qtd_min_sla_atendimento;
		$this->QTD_MIN_SLA_SOLUCAO_FINAL =  $row->qtd_min_sla_solucao_final;
		$this->MIN_INICIO_PREVISAO = $row->min_inicio_previsao;
		$this->MIN_ENCERRAMENTO_PREVISAO = $row->min_encerramento_previsao;
		$this->SEQ_TIPO_OCORRENCIA = $row->seq_tipo_ocorrencia;
		$this->FLG_FORMA_MEDICAO_TEMPO = $row->flg_forma_medicao_tempo;
		$this->TXT_CONTINGENCIAMENTO = $row->txt_contingenciamento;
		$this->TXT_CAUSA_RAIZ = $row->txt_causa_raiz;
		$this->TXT_RESOLUCAO = $row->txt_resolucao;
		$this->NUM_MATRICULA_CADASTRANTE = $row->num_matricula_cadastrante;
		$this->SEQ_ACAO_CONTINGENCIAMENTO = $row->seq_acao_contingenciamento;
		$this->SEQ_AVALIACAO_ATENDIMENTO = $row->seq_avaliacao_atendimento;
		$this->SEQ_AVALIACAO_CONHECIMENTO_TECNICO = $row->seq_avaliacao_conhecimento_tecnico;
		$this->SEQ_AVALIACAO_POSTURA = $row->seq_avaliacao_postura;
		$this->SEQ_AVALIACAO_TEMPO_ESPERA = $row->seq_avaliacao_tempo_espera;
		$this->SEQ_AVALIACAO_TEMPO_SOLUCAO = $row->seq_avaliacao_tempo_solucao;
		$this->NUM_MATRICULA_AVALIADOR = $row->num_matricula_avaliador;
		$this->TXT_AVALIACAO = $row->txt_avaliacao;
		$this->SEQ_MOTIVO_CANCELAMENTO = $row->seq_motivo_cancelamento;
		
		$this->OBJETIVO_EVENTO = $row->objetivo_evento;
		$this->QUANTIDADE_PESSOAS_EVENTO = $row->quantidade_pessoas_evento;
		$this->SERVICOS_EVENTO = $row->servicos_evento;		
		$this->DTH_RESERVA_EVENTO = $row->dth_reserva_evento;
		$this->DT_INICIO_UTILIZACAO_APARELHO  = $row->dt_inicio_utilizacao_aparelho;
		$this->DT_FIM_UTILIZACAO_APARELHO = $row->dt_fim_utilizacao_aparelho;
		$this->FLG_DESTINACAO_CHAMADO = $row->flg_destinacao_chamado;
		
		

		// Carregar campos calculados
		$this->DTH_ENCERRAMENTO_PREVISAO = $this->fGetDTH_ENCERRAMENTO_PREVISAO($row->dth_abertura, $row->qtd_min_sla_atendimento, $row->flg_forma_medicao_tempo, $this->SEQ_CHAMADO, $this->SEQ_TIPO_OCORRENCIA, $row->qtd_min_sla_solucao_final);

		// Buscar qual é o chamado master
		$this->SEQ_CHAMADO_MASTER = $this->GetSEQ_CHAMADO_MASTER($this->SEQ_CHAMADO);
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function email($id){
		$sql = "SELECT b.DSC_TIPO_CHAMADO, c.DSC_SUBTIPO_CHAMADO, d.DSC_ATIVIDADE_CHAMADO, e.NOM_COLABORADOR as NOM_CLIENTE, TXT_CHAMADO,
                           e.DSC_EMAIL as DSC_EMAIL_CLIENTE, to_char(DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA, TXT_RESOLUCAO,
                           to_char(a.DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO, f.NOM_TIPO_OCORRENCIA
                        FROM gestaoti.chamado a, gestaoti.tipo_chamado b, gestaoti.subtipo_chamado c, gestaoti.atividade_chamado d,
                             gestaoti.viw_colaborador e, gestaoti.tipo_ocorrencia f
                        WHERE a.SEQ_ATIVIDADE_CHAMADO = d.SEQ_ATIVIDADE_CHAMADO
                        and d.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
                        and c.SEQ_TIPO_CHAMADO = b.SEQ_TIPO_CHAMADO
                        and a.SEQ_TIPO_OCORRENCIA = f.SEQ_TIPO_OCORRENCIA
                        and a.NUM_MATRICULA_SOLICITANTE = e.NUM_MATRICULA_COLABORADOR
                        and a.SEQ_CHAMADO = $id";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		$this->DSC_TIPO_CHAMADO = $row->dsc_tipo_chamado;
		$this->DSC_SUBTIPO_CHAMADO = $row->dsc_subtipo_chamado;
		$this->DSC_ATIVIDADE_CHAMADO = $row->dsc_atividade_chamado;
		$this->NOM_CLIENTE = $row->nom_cliente;
		$this->EMAIL_CLIENTE = $row->dsc_email_cliente;
		$this->TXT_CHAMADO = $row->txt_chamado;
		$this->TXT_RESOLUCAO = $row->txt_resolucao;
		$this->DTH_ABERTURA = $row->dth_abertura;
		$this->DTH_ENCERRAMENTO_EFETIVO = $row->dth_encerramento_efetivo;
		$this->NOM_TIPO_OCORRENCIA = $row->nom_tipo_ocorrencia;

	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select * ";
		$sqlCorpo  = " FROM (
                                    SELECT SEQ_CHAMADO, NUM_MATRICULA_SOLICITANTE, b.SEQ_ATIVIDADE_CHAMADO, SEQ_SITUACAO_CHAMADO, a.SEQ_LOCALIZACAO_FISICA,
                                           SEQ_PRIORIDADE_CHAMADO, TXT_CHAMADO, TXT_CONTINGENCIAMENTO, TXT_CAUSA_RAIZ, TXT_RESOLUCAO, NUM_MATRICULA_CADASTRANTE, SEQ_ACAO_CONTINGENCIAMENTO,
                                           to_char(DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA, DTH_ABERTURA as DTH_ABERTURA_DATA,
                                           to_char(DTH_TRIAGEM_EFETIVA, 'dd/mm/yyyy hh24:mi:ss') as DTH_TRIAGEM_EFETIVA, DTH_TRIAGEM_EFETIVA as DTH_TRIAGEM_EFETIVA_DATA,
                                           to_char(DTH_INICIO_PREVISAO, 'dd-mm-yyyy hh24:mi:ss') as DTH_INICIO_PREVISAO, DTH_INICIO_PREVISAO as DTH_INICIO_PREVISAO_DATA,
                                           to_char(a.DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO, a.DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA,
                                           to_char(a.DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO, a.DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA,
                                           to_char(DTH_AGENDAMENTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_AGENDAMENTO, DTH_AGENDAMENTO as DTH_AGENDAMENTO_DATA,
                                           NUM_MATRICULA_CONTATO , SEQ_ITEM_CONFIGURACAO , NUM_PRIORIDADE_FILA, b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_ATENDIMENTO, b.QTD_MIN_SLA_SOLUCAO_FINAL,
                                           (DTH_ABERTURA - DTH_INICIO_PREVISAO)*1440 as MIN_INICIO_PREVISAO,
                                               b.DSC_ATIVIDADE_CHAMADO,
                                           b.SEQ_SUBTIPO_CHAMADO, c.DSC_SUBTIPO_CHAMADO, c.SEQ_TIPO_CHAMADO, d.DSC_TIPO_CHAMADO, b.FLG_ATENDIMENTO_EXTERNO,
                                           e.COD_DEPENDENCIA_FIS as COD_DEPENDENCIA, e.NOM_COLABORADOR as NOM_SOLICITANTE, e.DSC_EMAIL, e.NOM_USUARIO_REDE,
                                           f.SEQ_edificacao, g.COD_DEPENDENCIA as COD_DEPENDENCIA_LOCALIZACAO,
                                           a.SEQ_TIPO_OCORRENCIA, b.FLG_FORMA_MEDICAO_TEMPO,d.seq_central_atendimento,
                                           a.objetivo_evento,a.quantidade_pessoas_evento,a.servicos_evento,
                                           a.dth_reserva_evento,a.dt_inicio_utilizacao_aparelho,a.dt_fim_utilizacao_aparelho,
                                           b.NUM_MATRICULA_APROVADOR,b.NUM_MATRICULA_APROVADOR_SUBSTITUTO,flg_destinacao_chamado
                                    FROM gestaoti.chamado a
                                            LEFT OUTER JOIN gestaoti.localizacao_fisica f on (a.SEQ_LOCALIZACAO_FISICA = f.SEQ_LOCALIZACAO_FISICA)
                                            LEFT OUTER JOIN gestaoti.edificacao g on (f.SEQ_edificacao = g.SEQ_edificacao),
                                            gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d,
                                            gestaoti.viw_colaborador e
                                     WHERE a.NUM_MATRICULA_SOLICITANTE = e.NUM_MATRICULA_COLABORADOR
                                       and a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
                                       and b.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
                                       and c.SEQ_TIPO_CHAMADO = d.SEQ_TIPO_CHAMADO ";
		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}
		$sqlCorpo .= ") PAGING
						WHERE 1=1 ";

		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->NUM_MATRICULA_SOLICITANTE != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_SOLICITANTE = $this->NUM_MATRICULA_SOLICITANTE ";
		}
		if($this->SEQ_ATIVIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_ATIVIDADE_CHAMADO = $this->SEQ_ATIVIDADE_CHAMADO ";
		}
		if($this->SEQ_SITUACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SITUACAO_CHAMADO in ($this->SEQ_SITUACAO_CHAMADO)";
		}
		//if($this->COD_SLA_ATENDIMENTO == "NULL"){
		//	$sqlCorpo .= "  and COD_SLA_ATENDIMENTO is null ";
		//}elseif($this->COD_SLA_ATENDIMENTO != ""){
		//	$sqlCorpo .= "  and COD_SLA_ATENDIMENTO = ".$this->COD_SLA_ATENDIMENTO." ";
		//}
		if($this->SEQ_LOCALIZACAO_FISICA != ""){
			$sqlCorpo .= "  and SEQ_LOCALIZACAO_FISICA = $this->SEQ_LOCALIZACAO_FISICA ";
		}
		if($this->SEQ_PRIORIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_PRIORIDADE_CHAMADO = $this->SEQ_PRIORIDADE_CHAMADO ";
		}
		if($this->SEQ_TIPO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_CHAMADO = $this->SEQ_TIPO_CHAMADO ";
		}
		if($this->SEQ_SUBTIPO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SUBTIPO_CHAMADO = $this->SEQ_SUBTIPO_CHAMADO ";
		}
		if($this->TXT_CHAMADO != ""){
			$sqlCorpo .= "  and upper(TXT_CHAMADO) like '%".mb_strtoupper($this->TXT_CHAMADO,'LATIN1')."%'  ";
		}
		if($this->DTH_ABERTURA != "" && $this->DTH_ABERTURA_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA >= to_timestamp('".$this->DTH_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ABERTURA != "" && $this->DTH_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA between to_timestamp('".$this->DTH_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_timestamp('".$this->DTH_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ABERTURA == "" && $this->DTH_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA <= to_timestamp('".$this->DTH_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_TRIAGEM_EFETIVA != "" && $this->DTH_TRIAGEM_EFETIVA_FINAL == "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA >= to_timestamp('".$this->DTH_TRIAGEM_EFETIVA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_TRIAGEM_EFETIVA != "" && $this->DTH_TRIAGEM_EFETIVA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA between to_timestamp('".$this->DTH_TRIAGEM_EFETIVA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_timestamp('".$this->DTH_TRIAGEM_EFETIVA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_TRIAGEM_EFETIVA == "" && $this->DTH_TRIAGEM_EFETIVA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA <= to_timestamp('".$this->DTH_TRIAGEM_EFETIVA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_PREVISAO != "" && $this->DTH_INICIO_PREVISAO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA >= to_timestamp('".$this->DTH_INICIO_PREVISAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_PREVISAO != "" && $this->DTH_INICIO_PREVISAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA between to_timestamp('".$this->DTH_INICIO_PREVISAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_timestamp('".$this->DTH_INICIO_PREVISAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_PREVISAO == "" && $this->DTH_INICIO_PREVISAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA <= to_timestamp('".$this->DTH_INICIO_PREVISAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_EFETIVO != "" && $this->DTH_INICIO_EFETIVO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA >= to_timestamp('".$this->DTH_INICIO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_EFETIVO != "" && $this->DTH_INICIO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA between to_timestamp('".$this->DTH_INICIO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_timestamp('".$this->DTH_INICIO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_EFETIVO == "" && $this->DTH_INICIO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA <= to_timestamp('".$this->DTH_INICIO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO != "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA >= to_timestamp('".$this->DTH_ENCERRAMENTO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO != "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA between to_timestamp('".$this->DTH_ENCERRAMENTO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_timestamp('".$this->DTH_ENCERRAMENTO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO == "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA <= to_timestamp('".$this->DTH_ENCERRAMENTO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_AGENDAMENTO != "" && $this->DTH_AGENDAMENTO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA >= to_timestamp('".$this->DTH_AGENDAMENTO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_AGENDAMENTO != "" && $this->DTH_AGENDAMENTO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA between to_timestamp('".$this->DTH_AGENDAMENTO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_timestamp('".$this->DTH_AGENDAMENTO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_AGENDAMENTO == "" && $this->DTH_AGENDAMENTO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA <= to_timestamp('".$this->DTH_AGENDAMENTO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->NUM_MATRICULA_CONTATO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_CONTATO = $this->NUM_MATRICULA_CONTATO ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}

		if($this->NUM_PRIORIDADE_FILA == "NOTNULL"){
			$sqlCorpo .= "  and NUM_PRIORIDADE_FILA is not null";
		}elseif($this->NUM_PRIORIDADE_FILA != ""){
			$sqlCorpo .= "  and NUM_PRIORIDADE_FILA = $this->NUM_PRIORIDADE_FILA ";
		}

		if($this->PESQUISA_TRIAGEM == "EMDIA"){
			$sqlCorpo .= "  and FLG_ATENDIMENTO_EXTERNO = 'S'
							and current_date between DTH_ABERTURA_DATA and DTH_TRGM_PRVS_RISCO_ATRS_DT ";
		}
		if($this->PESQUISA_TRIAGEM == "RISCOATRASO"){
			$sqlCorpo .= "  and FLG_ATENDIMENTO_EXTERNO = 'S'
							and current_date between DTH_TRGM_PRVS_RISCO_ATRS_DT and DTH_TRIAGEM_PREVISAO_DATA ";
		}
		if($this->PESQUISA_TRIAGEM == "ATRASO"){
			$sqlCorpo .= "  and FLG_ATENDIMENTO_EXTERNO = 'S'
							and DTH_TRIAGEM_PREVISAO_DATA < current_date ";
		}
		if($this->COD_DEPENDENCIA != "" ){
			$sqlCorpo .= "  and COD_DEPENDENCIA in (".$this->COD_DEPENDENCIA.") ";
		}
		if($this->COD_DEPENDENCIA_LOCALIZACAO != "" ){
			$sqlCorpo .= "  and COD_DEPENDENCIA_LOCALIZACAO = $this->COD_DEPENDENCIA_LOCALIZACAO ";
		}
		if ($this->SEQ_edificacao != "") {
			$sqlCorpo .= "  and SEQ_edificacao = $this->SEQ_edificacao ";
		}
		if ($this->SEQ_TIPO_OCORRENCIA != "") {
			$sqlCorpo .= "  and SEQ_TIPO_OCORRENCIA = $this->SEQ_TIPO_OCORRENCIA ";
		}
		if ($this->SEQ_edificacao != "") {
			$sqlCorpo .= "  and FLG_FORMA_MEDICAO_TEMPO = '$this->FLG_FORMA_MEDICAO_TEMPO' ";
		}
		if($this->NUM_PATRIMONIO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO in (select SEQ_CHAMADO
                                                            from gestaoti.patrimonio_chamado
                                                            where NUM_PATRIMONIO like '%$this->NUM_PATRIMONIO' )  ";
		}
		if($this->FLG_VINCULO != ""){
			$sqlCorpo .= "  and PAGING.SEQ_CHAMADO <> ".$this->FLG_VINCULO."
                                        and not exists (select 1 from gestaoti.vinculo_chamado
                                                        where SEQ_CHAMADO_FILHO = PAGING.SEQ_CHAMADO)
                                        and not exists (select 1 from gestaoti.vinculo_chamado
                                                        where SEQ_CHAMADO_MASTER = PAGING.SEQ_CHAMADO)  ";
		}

		if($this->SEQ_CHAMADO_MASTER != ""){
			$sqlCorpo .= "  and exists (select 1 from gestaoti.vinculo_chamado
                                                    where SEQ_CHAMADO_FILHO = PAGING.SEQ_CHAMADO
                                                    and SEQ_CHAMADO_MASTER = ".$this->SEQ_CHAMADO_MASTER.")  ";
		}

		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and exists (select 1 from gestaoti.atribuicao_chamado
                                                    where SEQ_CHAMADO = PAGING.SEQ_CHAMADO
                                                    and SEQ_EQUIPE_TI = ".$this->SEQ_EQUIPE_TI.")  ";
		}
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
				$sqlCorpo .= "  and seq_central_atendimento = '$this->SEQ_CENTRAL_ATENDIMENTO' ";
		}
		
		if($this->NUM_MATRICULA_APROVADOR_ATIVIDADE != ""){
				$sqlCorpo .= "  and (NUM_MATRICULA_APROVADOR = '$this->NUM_MATRICULA_APROVADOR_ATIVIDADE'  OR 
				NUM_MATRICULA_APROVADOR_SUBSTITUTO = '$this->NUM_MATRICULA_APROVADOR_ATIVIDADE' )";
		}

		$sqlCount = $sqlCorpo;
		
		$this->SQL_EXPORT = $sqlSelect . $sqlCorpo;

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlCorpo .= " limit $vQtdRegistros offset $vLimit  ";
			// Pegar a quantidade de registros GERAL
			$this->database->query("select count(1) " . $sqlCount);
			$rowCount = pg_fetch_array($this->database->result);
			$this->setrowCount($rowCount[0]);

		}

		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}
	
	function selectChamadosAguardandoAprovacao($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		 
		$sqlCorpo  = " 	SELECT a.SEQ_CHAMADO, NUM_MATRICULA_SOLICITANTE, b.SEQ_ATIVIDADE_CHAMADO, SEQ_SITUACAO_CHAMADO, a.SEQ_LOCALIZACAO_FISICA,
                                       SEQ_PRIORIDADE_CHAMADO, TXT_CHAMADO, TXT_CONTINGENCIAMENTO, TXT_CAUSA_RAIZ, TXT_RESOLUCAO, NUM_MATRICULA_CADASTRANTE, SEQ_ACAO_CONTINGENCIAMENTO,
                                       to_char(DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA, DTH_ABERTURA as DTH_ABERTURA_DATA,
                                       to_char(DTH_TRIAGEM_EFETIVA, 'dd/mm/yyyy hh24:mi:ss') as DTH_TRIAGEM_EFETIVA, DTH_TRIAGEM_EFETIVA as DTH_TRIAGEM_EFETIVA_DATA,
                                       to_char(DTH_INICIO_PREVISAO, 'dd-mm-yyyy hh24:mi:ss') as DTH_INICIO_PREVISAO, DTH_INICIO_PREVISAO as DTH_INICIO_PREVISAO_DATA,
                                       to_char(a.DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO, a.DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA,
                                       to_char(a.DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO, a.DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA,
                                       to_char(DTH_AGENDAMENTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_AGENDAMENTO, DTH_AGENDAMENTO as DTH_AGENDAMENTO_DATA,
                                       NUM_MATRICULA_CONTATO , SEQ_ITEM_CONFIGURACAO , NUM_PRIORIDADE_FILA, b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_ATENDIMENTO, b.QTD_MIN_SLA_SOLUCAO_FINAL,
                                       (DTH_ABERTURA - DTH_INICIO_PREVISAO)*1440 as MIN_INICIO_PREVISAO,
                                           b.DSC_ATIVIDADE_CHAMADO,
                                       b.SEQ_SUBTIPO_CHAMADO, c.DSC_SUBTIPO_CHAMADO, c.SEQ_TIPO_CHAMADO, d.DSC_TIPO_CHAMADO, b.FLG_ATENDIMENTO_EXTERNO,
                                       e.COD_DEPENDENCIA_FIS as COD_DEPENDENCIA, e.NOM_COLABORADOR as NOM_SOLICITANTE, e.DSC_EMAIL, e.NOM_USUARIO_REDE,
                                       f.SEQ_edificacao, g.COD_DEPENDENCIA as COD_DEPENDENCIA_LOCALIZACAO,
                                       a.SEQ_TIPO_OCORRENCIA, b.FLG_FORMA_MEDICAO_TEMPO,d.seq_central_atendimento,
                                       a.objetivo_evento,a.quantidade_pessoas_evento,a.servicos_evento,
                                       a.dth_reserva_evento,a.dt_inicio_utilizacao_aparelho,a.dt_fim_utilizacao_aparelho,
                                       b.NUM_MATRICULA_APROVADOR,b.NUM_MATRICULA_APROVADOR_SUBSTITUTO,flg_destinacao_chamado
                                FROM gestaoti.chamado a
                                        LEFT OUTER JOIN gestaoti.localizacao_fisica f on (a.SEQ_LOCALIZACAO_FISICA = f.SEQ_LOCALIZACAO_FISICA)
                                        LEFT OUTER JOIN gestaoti.edificacao g on (f.SEQ_edificacao = g.SEQ_edificacao)
                                        LEFT OUTER JOIN gestaoti.aprovacao_chamado_departamento acd  on (a.SEQ_CHAMADO = acd.seq_chamado) ,
                                        gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d,
                                        gestaoti.viw_colaborador e ";
		
		//if($this->NUM_MATRICULA_APROVADOR_ATIVIDADE != ""){
		//	$sqlCorpo .= ", gestaoti.aprovacao_chamado_superior acs ";
		//}
		
		$sqlCorpo .= " WHERE a.NUM_MATRICULA_SOLICITANTE = e.NUM_MATRICULA_COLABORADOR
                                 and a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
                                 and b.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
                                 and c.SEQ_TIPO_CHAMADO = d.SEQ_TIPO_CHAMADO "; 
		 
		if($this->SEQ_SITUACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SITUACAO_CHAMADO in ($this->SEQ_SITUACAO_CHAMADO)";
		} 
	 	
		if($this->NUM_MATRICULA_APROVADOR_ATIVIDADE != ""){
				$sqlCorpo .= "  and (
				 (NUM_MATRICULA_APROVADOR = '$this->NUM_MATRICULA_APROVADOR_ATIVIDADE'  OR 
				  NUM_MATRICULA_APROVADOR_SUBSTITUTO = '$this->NUM_MATRICULA_APROVADOR_ATIVIDADE' ) ";
				
				if($this->UOR_ID != ""){ 
					
					$count = count($this->UOR_ID);
					$UOR_IDs="";
					for ($i = 0; $i < $count; $i++) {
						if($this->UOR_ID[$i]!=""&& $this->UOR_ID[$i]!= null){
					      $UOR_IDs .= $this->UOR_ID[$i];
					      if(($i+1< $count) && $this->UOR_ID[$i+1]!=""&& $this->UOR_ID[$i+1]!= null){
					      	$UOR_IDs.=",";
					      }
						}
					      
					}
					if($UOR_IDs!=""){
						$sqlCorpo .=" OR (acd.id_unidade in (".$UOR_IDs.") )";
					}
				}
				
				if($this->COOR_ID != ""){
					$count = count($this->COOR_ID);
					$COOR_IDs="";
					for ($i = 0; $i < $count; $i++) {
                                            if($this->COOR_ID[$i]!=""&& $this->COOR_ID[$i]!= null){
                                                $COOR_IDs .= $this->COOR_ID[$i];
                                                if(($i+1< $count) && $this->COOR_ID[$i+1]!=""&& $this->COOR_ID[$i+1]!= null){
                                                    $COOR_IDs.=",";
                                                }
                                            }
					      
					      
					}
					if($COOR_IDs!=""){
						$sqlCorpo .="OR (acd.id_coordenacao in (".$COOR_IDs."))";
					}
				    
				}
				
				$sqlCorpo .= ")";
		}

		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}
		
		$sqlCount = $sqlCorpo;
		
		$this->SQL_EXPORT = $sqlSelect . $sqlCorpo;

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlCorpo .= " limit $vQtdRegistros offset $vLimit  ";
			// Pegar a quantidade de registros GERAL
			$this->database->query("select count(1) " . $sqlCount);
			$rowCount = pg_fetch_array($this->database->result);
			$this->setrowCount($rowCount[0]);

		}

		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query( $sqlCorpo);
	}

	// ****************************
	// SELECT ACOMPANHAR CHAMADOS
	// ****************************
	function AcompanharChamados($vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select * ";
		$sqlCorpo  = " FROM (
                                    select * from(
                                        SELECT SEQ_CHAMADO, NUM_MATRICULA_SOLICITANTE, b.SEQ_ATIVIDADE_CHAMADO, SEQ_SITUACAO_CHAMADO, SEQ_LOCALIZACAO_FISICA,
                                               SEQ_PRIORIDADE_CHAMADO, TXT_CHAMADO, TXT_CONTINGENCIAMENTO, a.TXT_CAUSA_RAIZ, TXT_RESOLUCAO, a.NUM_MATRICULA_CADASTRANTE,
                                                   a.SEQ_ACAO_CONTINGENCIAMENTO, to_char(DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA,
                                               DTH_ABERTURA as DTH_ABERTURA_DATA, to_char(DTH_TRIAGEM_EFETIVA, 'dd/mm/yyyy hh24:mi:ss') as DTH_TRIAGEM_EFETIVA,
                                               DTH_TRIAGEM_EFETIVA as DTH_TRIAGEM_EFETIVA_DATA, to_char(DTH_INICIO_PREVISAO, 'dd-mm-yyyy hh24:mi:ss') as DTH_INICIO_PREVISAO,
                                               DTH_INICIO_PREVISAO as DTH_INICIO_PREVISAO_DATA , to_char(a.DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO,
                                               a.DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA , to_char(a.DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO,
                                               a.DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA , to_char(DTH_AGENDAMENTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_AGENDAMENTO,
                                               DTH_AGENDAMENTO as DTH_AGENDAMENTO_DATA , NUM_MATRICULA_CONTATO , SEQ_ITEM_CONFIGURACAO , NUM_PRIORIDADE_FILA,
                                               b.DSC_ATIVIDADE_CHAMADO, a.SEQ_TIPO_OCORRENCIA, b.FLG_FORMA_MEDICAO_TEMPO, b.QTD_MIN_SLA_SOLUCAO_FINAL, b.QTD_MIN_SLA_ATENDIMENTO ";
		$sqlCorpo  .= "	FROM gestaoti.chamado a, gestaoti.atividade_chamado b
				WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
	                        and a.NUM_MATRICULA_SOLICITANTE = ".$this->NUM_MATRICULA_SOLICITANTE."
                                UNION
                                SELECT SEQ_CHAMADO, NUM_MATRICULA_SOLICITANTE, b.SEQ_ATIVIDADE_CHAMADO, SEQ_SITUACAO_CHAMADO, SEQ_LOCALIZACAO_FISICA,
                                       SEQ_PRIORIDADE_CHAMADO, TXT_CHAMADO, TXT_CONTINGENCIAMENTO, a.TXT_CAUSA_RAIZ, a.TXT_RESOLUCAO, a.NUM_MATRICULA_CADASTRANTE,
                                           a.SEQ_ACAO_CONTINGENCIAMENTO, to_char(DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA,
                                       DTH_ABERTURA as DTH_ABERTURA_DATA, to_char(DTH_TRIAGEM_EFETIVA, 'dd/mm/yyyy hh24:mi:ss') as DTH_TRIAGEM_EFETIVA,
                                       DTH_TRIAGEM_EFETIVA as DTH_TRIAGEM_EFETIVA_DATA, to_char(DTH_INICIO_PREVISAO, 'dd-mm-yyyy hh24:mi:ss') as DTH_INICIO_PREVISAO,
                                       DTH_INICIO_PREVISAO as DTH_INICIO_PREVISAO_DATA , to_char(a.DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO,
                                       a.DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA , to_char(a.DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO,
                                       a.DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA , to_char(DTH_AGENDAMENTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_AGENDAMENTO,
                                       DTH_AGENDAMENTO as DTH_AGENDAMENTO_DATA , NUM_MATRICULA_CONTATO , SEQ_ITEM_CONFIGURACAO , NUM_PRIORIDADE_FILA,
                                       b.DSC_ATIVIDADE_CHAMADO, a.SEQ_TIPO_OCORRENCIA, b.FLG_FORMA_MEDICAO_TEMPO, b.QTD_MIN_SLA_SOLUCAO_FINAL, b.QTD_MIN_SLA_ATENDIMENTO ";
		$sqlCorpo  .= "	FROM gestaoti.chamado a, gestaoti.atividade_chamado b
				WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
	                        and a.NUM_MATRICULA_CONTATO = ".$this->NUM_MATRICULA_SOLICITANTE."
                                ) alias
                                order by SEQ_CHAMADO DESC
							";

		$sqlCorpo .= ") PAGING
						WHERE 1=1 ";

		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->TXT_CHAMADO != ""){
			$sqlCorpo .= "  and upper(TXT_CHAMADO) like '%".mb_strtoupper($this->TXT_CHAMADO,'LATIN1')."%'  ";
		}
		if($this->DTH_ABERTURA != "" && $this->DTH_ABERTURA_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA >= to_date('".$this->DTH_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ABERTURA != "" && $this->DTH_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA between to_date('".$this->DTH_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ABERTURA == "" && $this->DTH_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA <= to_date('".$this->DTH_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_TRIAGEM_EFETIVA != "" && $this->DTH_TRIAGEM_EFETIVA_FINAL == "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA >= to_date('".$this->DTH_TRIAGEM_EFETIVA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_TRIAGEM_EFETIVA != "" && $this->DTH_TRIAGEM_EFETIVA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA between to_date('".$this->DTH_TRIAGEM_EFETIVA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_TRIAGEM_EFETIVA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_TRIAGEM_EFETIVA == "" && $this->DTH_TRIAGEM_EFETIVA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA <= to_date('".$this->DTH_TRIAGEM_EFETIVA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_PREVISAO != "" && $this->DTH_INICIO_PREVISAO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA >= to_date('".$this->DTH_INICIO_PREVISAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_PREVISAO != "" && $this->DTH_INICIO_PREVISAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA between to_date('".$this->DTH_INICIO_PREVISAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_INICIO_PREVISAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_PREVISAO == "" && $this->DTH_INICIO_PREVISAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA <= to_date('".$this->DTH_INICIO_PREVISAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_EFETIVO != "" && $this->DTH_INICIO_EFETIVO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA >= to_date('".$this->DTH_INICIO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_EFETIVO != "" && $this->DTH_INICIO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA between to_date('".$this->DTH_INICIO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_INICIO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_EFETIVO == "" && $this->DTH_INICIO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA <= to_date('".$this->DTH_INICIO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO != "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA >= to_date('".$this->DTH_ENCERRAMENTO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO != "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA between to_date('".$this->DTH_ENCERRAMENTO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_ENCERRAMENTO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO == "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA <= to_date('".$this->DTH_ENCERRAMENTO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_AGENDAMENTO != "" && $this->DTH_AGENDAMENTO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA >= to_date('".$this->DTH_AGENDAMENTO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_AGENDAMENTO != "" && $this->DTH_AGENDAMENTO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA between to_date('".$this->DTH_AGENDAMENTO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_AGENDAMENTO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_AGENDAMENTO == "" && $this->DTH_AGENDAMENTO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA <= to_date('".$this->DTH_AGENDAMENTO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->SEQ_SITUACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SITUACAO_CHAMADO in ($this->SEQ_SITUACAO_CHAMADO)";
		}
				if ($this->SEQ_TIPO_OCORRENCIA != "") {
			$sqlCorpo .= "  and SEQ_TIPO_OCORRENCIA = $this->SEQ_TIPO_OCORRENCIA ";
		}
		if ($this->SEQ_edificacao != "") {
			$sqlCorpo .= "  and FLG_FORMA_MEDICAO_TEMPO = '$this->FLG_FORMA_MEDICAO_TEMPO' ";
		}

		$sqlCount = $sqlCorpo;

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlCorpo .= " limit $vQtdRegistros offset $vLimit  ";
			// Pegar a quantidade de registros GERAL
			$this->database->query("select count(1) " . $sqlCount);
			$rowCount = pg_fetch_array($this->database->result);
			$this->setrowCount($rowCount[0]);

		}

		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	// ******************
	// SELECT ATENDIMENTO
	// ******************
	function AtenderChamados($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select * ";
		$sqlCorpo  = " FROM (
                                    SELECT a.SEQ_CHAMADO, a.NUM_MATRICULA_SOLICITANTE, b.SEQ_ATIVIDADE_CHAMADO, a.SEQ_LOCALIZACAO_FISICA,
                                           a.SEQ_PRIORIDADE_CHAMADO, a.TXT_CHAMADO, TXT_CONTINGENCIAMENTO, TXT_CAUSA_RAIZ, TXT_RESOLUCAO, NUM_MATRICULA_CADASTRANTE,
                                           SEQ_ACAO_CONTINGENCIAMENTO,
                                                           to_char(DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA, DTH_ABERTURA as DTH_ABERTURA_DATA,
                                           to_char(DTH_TRIAGEM_EFETIVA, 'dd/mm/yyyy hh24:mi:ss') as DTH_TRIAGEM_EFETIVA, DTH_TRIAGEM_EFETIVA as DTH_TRIAGEM_EFETIVA_DATA,
                                           to_char(DTH_INICIO_PREVISAO, 'dd-mm-yyyy hh24:mi:ss') as DTH_INICIO_PREVISAO,
                                           DTH_INICIO_PREVISAO as DTH_INICIO_PREVISAO_DATA,
                                           to_char(a.DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO,
                                           a.DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA,
                                           to_char(a.DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO,
                                           a.DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA,
                                           to_char(DTH_AGENDAMENTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_AGENDAMENTO, DTH_AGENDAMENTO as DTH_AGENDAMENTO_DATA,
                                           NUM_MATRICULA_CONTATO , SEQ_ITEM_CONFIGURACAO , NUM_PRIORIDADE_FILA, b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_SOLUCAO_FINAL, b.QTD_MIN_SLA_ATENDIMENTO,
                                           (DTH_ABERTURA - DTH_INICIO_PREVISAO)*1440 as MIN_INICIO_PREVISAO,
                                           b.DSC_ATIVIDADE_CHAMADO,
                                           b.SEQ_SUBTIPO_CHAMADO, c.DSC_SUBTIPO_CHAMADO, c.SEQ_TIPO_CHAMADO, d.DSC_TIPO_CHAMADO, b.FLG_ATENDIMENTO_EXTERNO,
                                           e.COD_DEPENDENCIA_FIS as COD_DEPENDENCIA, e.NOM_COLABORADOR as NOM_EXECUTANTE, e.DSC_EMAIL, e.NOM_USUARIO_REDE,
                                           f.TXT_ATIVIDADE, f.NUM_MATRICULA as NUM_MATRICULA_EXECUTOR, f.SEQ_SITUACAO_CHAMADO, f.SEQ_EQUIPE_TI, f.SEQ_ATRIBUICAO_CHAMADO,
                                           to_char(f.dth_atribuicao, 'dd/mm/yyyy hh24:mi:ss') as dth_atribuicao,
                                                           to_char(f.dth_inicio_efetivo, 'dd/mm/yyyy hh24:mi:ss') as dth_inicio_efetivo_atribuicao,
                                                           to_char(f.dth_encerramento_efetivo, 'dd/mm/yyyy hh24:mi:ss') as dth_encerramento_efetivo_atribuicao,
                                           g.COD_DEPENDENCIA as COD_DEPENDENCIA_ATRIBUICAO, h.SEQ_edificacao, i.COD_DEPENDENCIA as COD_DEPENDENCIA_LOCALIZACAO,
                                           a.SEQ_TIPO_OCORRENCIA, b.FLG_FORMA_MEDICAO_TEMPO , d.seq_central_atendimento
                                      FROM gestaoti.chamado a
                                                LEFT OUTER JOIN gestaoti.localizacao_fisica h ON (a.SEQ_LOCALIZACAO_FISICA = h.SEQ_LOCALIZACAO_FISICA)
                                                LEFT OUTER JOIN gestaoti.edificacao i ON (h.SEQ_edificacao = i.SEQ_edificacao),
                                           gestaoti.atividade_chamado b, gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d,
                                           gestaoti.atribuicao_chamado f  LEFT OUTER JOIN gestaoti.viw_colaborador e ON (f.NUM_MATRICULA = e.NUM_MATRICULA_COLABORADOR),
                                           gestaoti.equipe_ti g
                                     WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
                                       and b.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
                                       and c.SEQ_TIPO_CHAMADO = d.SEQ_TIPO_CHAMADO
                                       and a.SEQ_CHAMADO = f.SEQ_CHAMADO
                                       and f.SEQ_EQUIPE_TI = g.SEQ_EQUIPE_TI ";
		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}
		$sqlCorpo .= ") PAGING
						WHERE 1=1 ";
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
		}
		if($this->NUM_MATRICULA_EXECUTOR != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_EXECUTOR = $this->NUM_MATRICULA_EXECUTOR ";
		}
		if($this->NUM_MATRICULA_NAO_EXECUTOR == "NENHUM"){
			$sqlCorpo .= "  and NUM_MATRICULA_EXECUTOR is null ";
		}elseif($this->NUM_MATRICULA_NAO_EXECUTOR != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_EXECUTOR <> ".$this->NUM_MATRICULA_NAO_EXECUTOR."
							and NUM_MATRICULA_EXECUTOR is not null ";
		}
		//if($this->COD_SLA_ATENDIMENTO == "NULL"){
		//	$sqlCorpo .= "  and COD_SLA_ATENDIMENTO is null ";
		//}elseif($this->COD_SLA_ATENDIMENTO != ""){
		//	$sqlCorpo .= "  and COD_SLA_ATENDIMENTO = ".$this->COD_SLA_ATENDIMENTO." ";
		//}
		if($this->NUM_MATRICULA_SOLICITANTE != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_SOLICITANTE = $this->NUM_MATRICULA_SOLICITANTE ";
		}
		if($this->SEQ_ATIVIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_ATIVIDADE_CHAMADO = $this->SEQ_ATIVIDADE_CHAMADO ";
		}
		if($this->SEQ_SITUACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SITUACAO_CHAMADO in ($this->SEQ_SITUACAO_CHAMADO)";
		}
		if($this->SEQ_LOCALIZACAO_FISICA != ""){
			$sqlCorpo .= "  and SEQ_LOCALIZACAO_FISICA = $this->SEQ_LOCALIZACAO_FISICA ";
		}
		if($this->SEQ_PRIORIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_PRIORIDADE_CHAMADO = $this->SEQ_PRIORIDADE_CHAMADO ";
		}
		if($this->SEQ_TIPO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_CHAMADO = $this->SEQ_TIPO_CHAMADO ";
		}
		if($this->SEQ_SUBTIPO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SUBTIPO_CHAMADO = $this->SEQ_SUBTIPO_CHAMADO ";
		}
		if($this->TXT_CHAMADO != ""){
			$sqlCorpo .= "  and upper(TXT_CHAMADO) like '%".mb_strtoupper($this->TXT_CHAMADO,'LATIN1')."%'  ";
		}
		if($this->DTH_ABERTURA != "" && $this->DTH_ABERTURA_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA >= '".$this->pagina->ConvDataAMD($this->DTH_ABERTURA)." 00:00:00' ";
		}
		if($this->DTH_ABERTURA != "" && $this->DTH_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA between '".$this->pagina->ConvDataAMD($this->DTH_ABERTURA)." 00:00:00' and '".$this->pagina->ConvDataAMD($this->DTH_ABERTURA_FINAL)." 23:59:59' ";
		}
		if($this->DTH_ABERTURA == "" && $this->DTH_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA <= '".$this->pagina->ConvDataAMD($this->DTH_ABERTURA_FINAL)." 23:59:59' ";
		}
		if($this->DTH_TRIAGEM_EFETIVA != "" && $this->DTH_TRIAGEM_EFETIVA_FINAL == "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA >= '".$this->pagina->ConvDataAMD($this->DTH_TRIAGEM_EFETIVA)." 00:00:00' ";
		}
		if($this->DTH_TRIAGEM_EFETIVA != "" && $this->DTH_TRIAGEM_EFETIVA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA between '".$this->pagina->ConvDataAMD($this->DTH_TRIAGEM_EFETIVA)." 00:00:00' and '".$this->pagina->ConvDataAMD($this->DTH_TRIAGEM_EFETIVA_FINAL)." 23:59:59' ";
		}
		if($this->DTH_TRIAGEM_EFETIVA == "" && $this->DTH_TRIAGEM_EFETIVA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA <= '".$this->pagina->ConvDataAMD($this->DTH_TRIAGEM_EFETIVA_FINAL)." 23:59:59' ";
		}
		if($this->DTH_INICIO_PREVISAO != "" && $this->DTH_INICIO_PREVISAO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA >= '".$this->pagina->ConvDataAMD($this->DTH_INICIO_PREVISAO)." 00:00:00' ";
		}
		if($this->DTH_INICIO_PREVISAO != "" && $this->DTH_INICIO_PREVISAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA between '".$this->pagina->ConvDataAMD($this->DTH_INICIO_PREVISAO)." 00:00:00' and '".$this->pagina->ConvDataAMD($this->DTH_INICIO_PREVISAO_FINAL)." 23:59:59' ";
		}
		if($this->DTH_INICIO_PREVISAO == "" && $this->DTH_INICIO_PREVISAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA <= '".$this->pagina->ConvDataAMD($this->DTH_INICIO_PREVISAO_FINAL)." 23:59:59' ";
		}
		if($this->DTH_INICIO_EFETIVO != "" && $this->DTH_INICIO_EFETIVO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA >= '".$this->pagina->ConvDataAMD($this->DTH_INICIO_EFETIVO)." 00:00:00' ";
		}
		if($this->DTH_INICIO_EFETIVO != "" && $this->DTH_INICIO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA between '".$this->pagina->ConvDataAMD($this->DTH_INICIO_EFETIVO)." 00:00:00' and '".$this->pagina->ConvDataAMD($this->DTH_INICIO_EFETIVO_FINAL)." 23:59:59' ";
		}
		if($this->DTH_INICIO_EFETIVO == "" && $this->DTH_INICIO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA <= '".$this->pagina->ConvDataAMD($this->DTH_INICIO_EFETIVO_FINAL)." 23:59:59' ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO != "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA >= '".$this->pagina->ConvDataAMD($this->DTH_ENCERRAMENTO_EFETIVO)." 00:00:00' ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO != "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA between '".$this->pagina->ConvDataAMD($this->DTH_ENCERRAMENTO_EFETIVO)." 00:00:00' and '".$this->pagina->ConvDataAMD($this->DTH_ENCERRAMENTO_EFETIVO_FINAL)." 23:59:59' ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO == "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA <= '".$this->pagina->ConvDataAMD($this->DTH_ENCERRAMENTO_EFETIVO_FINAL)." 23:59:59' ";
		}
		if($this->DTH_AGENDAMENTO != "" && $this->DTH_AGENDAMENTO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA >= '".$this->pagina->ConvDataAMD($this->DTH_AGENDAMENTO)." 00:00:00' ";
		}
		if($this->DTH_AGENDAMENTO != "" && $this->DTH_AGENDAMENTO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA between '".$this->pagina->ConvDataAMD($this->DTH_AGENDAMENTO)." 00:00:00' and '".$this->pagina->ConvDataAMD($this->DTH_AGENDAMENTO_FINAL)." 23:59:59' ";
		}
		if($this->DTH_AGENDAMENTO == "" && $this->DTH_AGENDAMENTO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA <= '".$this->pagina->ConvDataAMD($this->DTH_AGENDAMENTO_FINAL)." 23:59:59' ";
		}
		if($this->NUM_MATRICULA_CONTATO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_CONTATO = $this->NUM_MATRICULA_CONTATO ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->NUM_PRIORIDADE_FILA != ""){
			$sqlCorpo .= "  and NUM_PRIORIDADE_FILA = $this->NUM_PRIORIDADE_FILA ";
		}
		if($this->PESQUISA_ATENDIMENTO == "EMDIA"){
			$sqlCorpo .= "  and current_date between DTH_ABERTURA_DATA and DTH_ENC_PRVS_RISCO_ATRASO_DT ";
		}
		if($this->PESQUISA_ATENDIMENTO == "RISCOATRASO"){
			$sqlCorpo .= "  and current_date between DTH_ENC_PRVS_RISCO_ATRASO_DT and DTH_ENCERRAMENTO_PREVISAO_DATA ";
		}
		if($this->PESQUISA_ATENDIMENTO == "ATRASO"){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_PREVISAO_DATA < current_date ";
		}
		if($this->COD_DEPENDENCIA_LOCALIZACAO != "" ){
			$sqlCorpo .= "  and COD_DEPENDENCIA_LOCALIZACAO = $this->COD_DEPENDENCIA_LOCALIZACAO ";
		}
		if ($this->SEQ_edificacao != "") {
			$sqlCorpo .= "  and SEQ_edificacao = $this->SEQ_edificacao ";
		}
		if($this->COD_DEPENDENCIA_ATRIBUICAO != "" ){
			$sqlCorpo .= "  and COD_DEPENDENCIA_ATRIBUICAO = $this->COD_DEPENDENCIA_ATRIBUICAO ";
		}
		if ($this->SEQ_TIPO_OCORRENCIA != "") {
			$sqlCorpo .= "  and SEQ_TIPO_OCORRENCIA = $this->SEQ_TIPO_OCORRENCIA ";
		}
		if ($this->SEQ_edificacao != "") {
			$sqlCorpo .= "  and FLG_FORMA_MEDICAO_TEMPO = '$this->FLG_FORMA_MEDICAO_TEMPO' ";
		}
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and seq_central_atendimento = '$this->SEQ_CENTRAL_ATENDIMENTO' ";
		}

		$this->SQL_EXPORT = $sqlSelect . $sqlCorpo;

		$sqlCount = $sqlCorpo;

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlCorpo .= " limit $vQtdRegistros offset $vLimit  ";
			// Pegar a quantidade de registros GERAL
			$this->database->query("select count(1) " . $sqlCount);
			$rowCount = pg_fetch_array($this->database->result);
			$this->setrowCount($rowCount[0]);

		}

		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}
	// ******************
	// SELECT ATENDIMENTO
	// ******************
	function AtenderChamadosDistinct($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select * ";
		$sqlCorpo  = " FROM (
                                SELECT distinct a.SEQ_CHAMADO,
                                       a.SEQ_PRIORIDADE_CHAMADO,
                                       b.SEQ_SUBTIPO_CHAMADO,
                                       b.DSC_ATIVIDADE_CHAMADO,
                                       a.TXT_CHAMADO, a.TXT_CONTINGENCIAMENTO, a.TXT_CAUSA_RAIZ, a.TXT_RESOLUCAO, a.NUM_MATRICULA_CADASTRANTE, a.SEQ_ACAO_CONTINGENCIAMENTO,
                                       a.SEQ_SITUACAO_CHAMADO,
                                       to_char(DTH_ABERTURA, 'dd/mm/yyyy hh24:mi:ss') as DTH_ABERTURA, DTH_ABERTURA as DTH_ABERTURA_DATA,
                                       a.NUM_MATRICULA_SOLICITANTE, b.SEQ_ATIVIDADE_CHAMADO, a.SEQ_LOCALIZACAO_FISICA,
                                       to_char(DTH_INICIO_PREVISAO, 'dd-mm-yyyy hh24:mi:ss') as DTH_INICIO_PREVISAO,
                                       DTH_INICIO_PREVISAO as DTH_INICIO_PREVISAO_DATA,
                                       to_char(a.DTH_INICIO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_INICIO_EFETIVO,
                                       a.DTH_INICIO_EFETIVO as DTH_INICIO_EFETIVO_DATA,
                                       to_char(a.DTH_ENCERRAMENTO_EFETIVO, 'dd/mm/yyyy hh24:mi:ss') as DTH_ENCERRAMENTO_EFETIVO,
                                       a.DTH_ENCERRAMENTO_EFETIVO as DTH_ENCERRAMENTO_EFETIVO_DATA,
                                       to_char(DTH_AGENDAMENTO, 'dd/mm/yyyy hh24:mi:ss') as DTH_AGENDAMENTO, DTH_AGENDAMENTO as DTH_AGENDAMENTO_DATA,
                                       NUM_MATRICULA_CONTATO , SEQ_ITEM_CONFIGURACAO , NUM_PRIORIDADE_FILA, b.QTD_MIN_SLA_TRIAGEM, b.QTD_MIN_SLA_SOLUCAO_FINAL, b.QTD_MIN_SLA_ATENDIMENTO,
                                       (DTH_ABERTURA - DTH_INICIO_PREVISAO)*1440 as MIN_INICIO_PREVISAO,
                                        c.DSC_SUBTIPO_CHAMADO, c.SEQ_TIPO_CHAMADO, d.DSC_TIPO_CHAMADO, b.FLG_ATENDIMENTO_EXTERNO,
                                       g.COD_DEPENDENCIA as COD_DEPENDENCIA_ATRIBUICAO, h.SEQ_edificacao, i.COD_DEPENDENCIA as COD_DEPENDENCIA_LOCALIZACAO,
                                       a.SEQ_TIPO_OCORRENCIA, b.FLG_FORMA_MEDICAO_TEMPO, d.seq_central_atendimento
                                 FROM gestaoti.chamado a LEFT OUTER JOIN
                                      gestaoti.localizacao_fisica h on (a.SEQ_LOCALIZACAO_FISICA = h.SEQ_LOCALIZACAO_FISICA)
                                      LEFT OUTER JOIN gestaoti.edificacao i on (h.SEQ_edificacao = i.SEQ_edificacao),
                                      gestaoti.atividade_chamado b,
                                      gestaoti.subtipo_chamado c, gestaoti.tipo_chamado d,
                                      gestaoti.atribuicao_chamado f, gestaoti.equipe_ti g
                                 WHERE a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
                                   and b.SEQ_SUBTIPO_CHAMADO = c.SEQ_SUBTIPO_CHAMADO
                                   and c.SEQ_TIPO_CHAMADO = d.SEQ_TIPO_CHAMADO
                                   and a.SEQ_CHAMADO = f.SEQ_CHAMADO
                                   and f.SEQ_EQUIPE_TI = g.SEQ_EQUIPE_TI ";

		if($this->NUM_MATRICULA_EXECUTOR != ""){
			$sqlCorpo .= "  and f.NUM_MATRICULA = $this->NUM_MATRICULA_EXECUTOR ";
		}
		if($this->NUM_MATRICULA_NAO_EXECUTOR == "NENHUM"){
			$sqlCorpo .= "  and f.NUM_MATRICULA is null ";
		}elseif($this->NUM_MATRICULA_NAO_EXECUTOR != ""){
			$sqlCorpo .= "  and f.NUM_MATRICULA <> ".$this->NUM_MATRICULA_NAO_EXECUTOR."
							and f.NUM_MATRICULA is not null ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and f.SEQ_EQUIPE_TI = $this->SEQ_EQUIPE_TI ";
			if($this->SEQ_SITUACAO_CHAMADO != ""){
				$sqlCorpo .= "  and f.SEQ_SITUACAO_CHAMADO in ($this->SEQ_SITUACAO_CHAMADO)";
			}
		}

		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}
		$sqlCorpo .= ") PAGING
						WHERE 1=1 ";
		if($this->SEQ_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_CHAMADO = $this->SEQ_CHAMADO ";
		}

		//if($this->COD_SLA_ATENDIMENTO == "NULL"){
		//	$sqlCorpo .= "  and COD_SLA_ATENDIMENTO is null ";
		//}elseif($this->COD_SLA_ATENDIMENTO != ""){
		//	$sqlCorpo .= "  and COD_SLA_ATENDIMENTO = ".$this->COD_SLA_ATENDIMENTO." ";
		//}
		if($this->NUM_MATRICULA_SOLICITANTE != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_SOLICITANTE = $this->NUM_MATRICULA_SOLICITANTE ";
		}
		if($this->SEQ_ATIVIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_ATIVIDADE_CHAMADO = $this->SEQ_ATIVIDADE_CHAMADO ";
		}
		if($this->SEQ_SITUACAO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SITUACAO_CHAMADO in ($this->SEQ_SITUACAO_CHAMADO)";
		}
		if($this->SEQ_LOCALIZACAO_FISICA != ""){
			$sqlCorpo .= "  and SEQ_LOCALIZACAO_FISICA = $this->SEQ_LOCALIZACAO_FISICA ";
		}
		if($this->SEQ_PRIORIDADE_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_PRIORIDADE_CHAMADO = $this->SEQ_PRIORIDADE_CHAMADO ";
		}
		if($this->SEQ_TIPO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_TIPO_CHAMADO = $this->SEQ_TIPO_CHAMADO ";
		}
		if($this->SEQ_SUBTIPO_CHAMADO != ""){
			$sqlCorpo .= "  and SEQ_SUBTIPO_CHAMADO = $this->SEQ_SUBTIPO_CHAMADO ";
		}
		if($this->TXT_CHAMADO != ""){
			$sqlCorpo .= "  and upper(TXT_CHAMADO) like '%".mb_strtoupper($this->TXT_CHAMADO,'LATIN1')."%'  ";
		}
		if($this->DTH_ABERTURA != "" && $this->DTH_ABERTURA_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA >= to_date('".$this->DTH_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ABERTURA != "" && $this->DTH_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA between to_date('".$this->DTH_ABERTURA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ABERTURA == "" && $this->DTH_ABERTURA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ABERTURA_DATA <= to_date('".$this->DTH_ABERTURA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_TRIAGEM_EFETIVA != "" && $this->DTH_TRIAGEM_EFETIVA_FINAL == "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA >= to_date('".$this->DTH_TRIAGEM_EFETIVA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_TRIAGEM_EFETIVA != "" && $this->DTH_TRIAGEM_EFETIVA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA between to_date('".$this->DTH_TRIAGEM_EFETIVA." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_TRIAGEM_EFETIVA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_TRIAGEM_EFETIVA == "" && $this->DTH_TRIAGEM_EFETIVA_FINAL != "" ){
			$sqlCorpo .= "  and DTH_TRIAGEM_EFETIVA_DATA <= to_date('".$this->DTH_TRIAGEM_EFETIVA_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_PREVISAO != "" && $this->DTH_INICIO_PREVISAO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA >= to_date('".$this->DTH_INICIO_PREVISAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_PREVISAO != "" && $this->DTH_INICIO_PREVISAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA between to_date('".$this->DTH_INICIO_PREVISAO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_INICIO_PREVISAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_PREVISAO == "" && $this->DTH_INICIO_PREVISAO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_PREVISAO_DATA <= to_date('".$this->DTH_INICIO_PREVISAO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_EFETIVO != "" && $this->DTH_INICIO_EFETIVO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA >= to_date('".$this->DTH_INICIO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_EFETIVO != "" && $this->DTH_INICIO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA between to_date('".$this->DTH_INICIO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_INICIO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_INICIO_EFETIVO == "" && $this->DTH_INICIO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_INICIO_EFETIVO_DATA <= to_date('".$this->DTH_INICIO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO != "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA >= to_date('".$this->DTH_ENCERRAMENTO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO != "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA between to_date('".$this->DTH_ENCERRAMENTO_EFETIVO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_ENCERRAMENTO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_ENCERRAMENTO_EFETIVO == "" && $this->DTH_ENCERRAMENTO_EFETIVO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_ENCERRAMENTO_EFETIVO_DATA <= to_date('".$this->DTH_ENCERRAMENTO_EFETIVO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_AGENDAMENTO != "" && $this->DTH_AGENDAMENTO_FINAL == "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA >= to_date('".$this->DTH_AGENDAMENTO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_AGENDAMENTO != "" && $this->DTH_AGENDAMENTO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA between to_date('".$this->DTH_AGENDAMENTO." 00:00:00', 'dd/mm/yyyy hh24:mi:ss') and to_date('".$this->DTH_AGENDAMENTO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->DTH_AGENDAMENTO == "" && $this->DTH_AGENDAMENTO_FINAL != "" ){
			$sqlCorpo .= "  and DTH_AGENDAMENTO_DATA <= to_date('".$this->DTH_AGENDAMENTO_FINAL." 23:59:59', 'dd/mm/yyyy hh24:mi:ss') ";
		}
		if($this->NUM_MATRICULA_CONTATO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_CONTATO = $this->NUM_MATRICULA_CONTATO ";
		}
		if($this->SEQ_ITEM_CONFIGURACAO != ""){
			$sqlCorpo .= "  and SEQ_ITEM_CONFIGURACAO = $this->SEQ_ITEM_CONFIGURACAO ";
		}
		if($this->NUM_PRIORIDADE_FILA != ""){
			$sqlCorpo .= "  and NUM_PRIORIDADE_FILA = $this->NUM_PRIORIDADE_FILA ";
		}
		if($this->PESQUISA_ATENDIMENTO == "EMDIA"){
		///	$sqlCorpo .= "  and current_date between DTH_ABERTURA_DATA and DTH_ENC_PRVS_RISCO_ATRASO_DT ";
		}
		if($this->PESQUISA_ATENDIMENTO == "RISCOATRASO"){
		//	$sqlCorpo .= "  and current_date between DTH_ENC_PRVS_RISCO_ATRASO_DT and DTH_ENCERRAMENTO_PREVISAO_DATA ";
		}
		if($this->PESQUISA_ATENDIMENTO == "ATRASO"){
		//	$sqlCorpo .= "  and DTH_ENCERRAMENTO_PREVISAO_DATA < current_date ";
		}
		if($this->COD_DEPENDENCIA_LOCALIZACAO != "" ){
			$sqlCorpo .= "  and COD_DEPENDENCIA_LOCALIZACAO = $this->COD_DEPENDENCIA_LOCALIZACAO ";
		}
		if ($this->SEQ_edificacao != "") {
			$sqlCorpo .= "  and SEQ_edificacao = $this->SEQ_edificacao ";
		}
		if($this->COD_DEPENDENCIA_ATRIBUICAO != "" ){
			$sqlCorpo .= "  and COD_DEPENDENCIA_ATRIBUICAO = $this->COD_DEPENDENCIA_ATRIBUICAO ";
		}
		if ($this->SEQ_TIPO_OCORRENCIA != "") {
			$sqlCorpo .= "  and SEQ_TIPO_OCORRENCIA = $this->SEQ_TIPO_OCORRENCIA ";
		}
		if ($this->SEQ_edificacao != "") {
			$sqlCorpo .= "  and FLG_FORMA_MEDICAO_TEMPO = '$this->FLG_FORMA_MEDICAO_TEMPO' ";
		}
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
				$sqlCorpo .= "  and seq_central_atendimento = '$this->SEQ_CENTRAL_ATENDIMENTO' ";
		}
		
		$this->SQL_EXPORT = $sqlSelect . $sqlCorpo;
		
		$sqlCount = $sqlCorpo;

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlCorpo .= " limit $vQtdRegistros offset $vLimit  ";
			// Pegar a quantidade de registros GERAL
			$this->database->query("select count(1) " . $sqlCount);
			$rowCount = pg_fetch_array($this->database->result);
			$this->setrowCount($rowCount[0]);

		}

		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	function fGetDTH_TRIAGEM_RISCO_ATRASO( $v_DTH_ABERTURA, $v_QTD_MIN_SLA_TRIAGEM, $v_FLG_FORMA_MEDICAO_TEMPO){
		// to_char(DTH_ABERTURA + (((b.QTD_MIN_SLA_TRIAGEM/4)*3)/60/24),'dd-mm-yyyy hh24:mi:ss') as ,
		$vAdd = (int)($v_QTD_MIN_SLA_TRIAGEM/4)*3;
		if($v_FLG_FORMA_MEDICAO_TEMPO == "C"){
			return $this->pagina->add_minutos ($vAdd, $v_DTH_ABERTURA);
		}else{
			return $this->pagina->add_minutos_uteis($vAdd, $v_DTH_ABERTURA, $this->HoraInicioExpediente, $this->HoraInicioIntervalo, $this->HoraFimIntervalo, $this->HoraFimExpediente, $this->aDtFeriado);
		}
	}

	function fGetDTH_TRIAGEM_PREVISAO($v_DTH_ABERTURA, $v_QTD_MIN_SLA_TRIAGEM, $v_FLG_FORMA_MEDICAO_TEMPO){
		//  to_char(DTH_ABERTURA + (b.QTD_MIN_SLA_TRIAGEM/60/24),'dd-mm-yyyy hh24:mi:ss') as DTH_TRIAGEM_PREVISAO,
		if($v_FLG_FORMA_MEDICAO_TEMPO == "C"){
			return $this->pagina->add_minutos($v_QTD_MIN_SLA_TRIAGEM,$v_DTH_ABERTURA);
		}else{
			return $this->pagina->add_minutos_uteis($v_QTD_MIN_SLA_TRIAGEM, $v_DTH_ABERTURA, $this->HoraInicioExpediente, $this->HoraInicioIntervalo, $this->HoraFimIntervalo, $this->HoraFimExpediente, $this->aDtFeriado);
		}
	}

	function fGetDTH_ENC_RISCO_ATRASO($v_DTH_ABERTURA, $v_QTD_MIN_SLA_ATENDIMENTO, $v_FLG_FORMA_MEDICAO_TEMPO){
		//to_char(DTH_ABERTURA + (((b.QTD_MIN_SLA_ATENDIMENTO/4)*3)/60/24),'dd/mm/yyyy hh24:mi:ss') as DTH_ENC_RISCO_ATRASO,
		$vAdd = (int)($v_QTD_MIN_SLA_ATENDIMENTO/4)*3;
		if($v_FLG_FORMA_MEDICAO_TEMPO == ""){
			return $this->pagina->add_minutos ($vAdd, $v_DTH_ABERTURA);
		}else{
			return $this->pagina->add_minutos_uteis($vAdd, $v_DTH_ABERTURA, $this->HoraInicioExpediente, $this->HoraInicioIntervalo, $this->HoraFimIntervalo, $this->HoraFimExpediente, $this->aDtFeriado);
		}
	}

	// ====================================================================================================================================
	// Método para o cálculo de SLA de cada chamado
	function fGetDTH_ENCERRAMENTO_PREVISAO($v_DTH_ABERTURA, $v_QTD_MIN_SLA_ATENDIMENTO, $v_FLG_FORMA_MEDICAO_TEMPO, $v_SEQ_CHAMADO, $v_SEQ_TIPO_OCORRENCIA, $v_QTD_MIN_SLA_SOLUCAO_FINAL){
		if($v_QTD_MIN_SLA_ATENDIMENTO == ""){ // SLA Pós estabelecido
			// Buscar aprovação
			$this->aprovacao_chamado = new aprovacao_chamado();
			$this->aprovacao_chamado->GetUltimoAprovacao($v_SEQ_CHAMADO);
			return $this->aprovacao_chamado->DTH_PREVISTA;
		}else{
			$v_DTH_ENCERRAMENTO_PREVISAO = "";

			// =============================================== Gestão de problemas ============================================
			// Caso o chamado seja incidente, verificar se já foi contigenciado para buscar o SLA de Contingencia ou de Solução
			$this->historico_chamado = new historico_chamado();
			// Padrão do SLA é o de contingenciamento
			$v_MIN_SLA = $v_QTD_MIN_SLA_ATENDIMENTO;

			if($v_SEQ_TIPO_OCORRENCIA == $this->tipo_ocorrencia->SEQ_TIPO_OCORRENCIA_INCIDENTE){
				if($this->historico_chamado->GetFlgAtendimentoContingenciado($v_SEQ_CHAMADO)){
					// Caso seja incidente e tenha sido contingenciado, consideramos o SLA para solução definitiva
					$v_MIN_SLA = $v_QTD_MIN_SLA_SOLUCAO_FINAL;
				}
			}

			// Cálculo do tempo de atendimento em minutos corridos
			if($v_FLG_FORMA_MEDICAO_TEMPO == "C"){
				$v_DTH_ENCERRAMENTO_PREVISAO = $this->pagina->add_minutos($v_MIN_SLA, $v_DTH_ABERTURA);

				// Buscar quantidade de minitos na situação Suspensa para adicionar
				$vQtdMinutosSuspensa = $this->historico_chamado->GetQtdMinutosSuspensaMinutosCorridos($v_SEQ_CHAMADO);
				$v_DTH_ENCERRAMENTO_PREVISAO = $vQtdMinutosSuspensa>0?$this->pagina->add_minutos_uteis($vQtdMinutosSuspensa, $v_DTH_ENCERRAMENTO_PREVISAO, $this->HoraInicioExpediente, $this->HoraInicioIntervalo, $this->HoraFimIntervalo, $this->HoraFimExpediente, $this->aDtFeriado):$v_DTH_ENCERRAMENTO_PREVISAO;
			}else{ // Cálculo do tempo em minutos úteis
				$v_DTH_ENCERRAMENTO_PREVISAO = $this->pagina->add_minutos_uteis($v_MIN_SLA, $v_DTH_ABERTURA, $this->HoraInicioExpediente, $this->HoraInicioIntervalo, $this->HoraFimIntervalo, $this->HoraFimExpediente, $this->aDtFeriado);

				// Buscar quantidade de minitos na situação Suspensa para adicionar
				$vQtdMinutosSuspensa = $this->historico_chamado->GetQtdMinutosSuspensaMinutosUteis($v_SEQ_CHAMADO, $this->HoraInicioExpediente, $this->HoraInicioIntervalo, $this->HoraFimIntervalo, $this->HoraFimExpediente, $this->aDtFeriado);
				$v_DTH_ENCERRAMENTO_PREVISAO = $vQtdMinutosSuspensa>0?$this->pagina->add_minutos_uteis($vQtdMinutosSuspensa, $v_DTH_ENCERRAMENTO_PREVISAO, $this->HoraInicioExpediente, $this->HoraInicioIntervalo, $this->HoraFimIntervalo, $this->HoraFimExpediente, $this->aDtFeriado):$v_DTH_ENCERRAMENTO_PREVISAO;
			}
			return $v_DTH_ENCERRAMENTO_PREVISAO;
		}
	}

	function fGetCOD_SLA($v_DTH_ABERTURA, $v_DTH_ENCERRAMENTO_PREVISAO, $v_DTH_ENCERRAMENTO_EFETIVO, $v_QTD_MIN_SLA_ATENDIMENTO){
		  // Código do atendimento do SLA
          // SE O CHAMADO NÃO ESTIVER EM ATENDIMENTO
          if($v_DTH_ENCERRAMENTO_EFETIVO != ""){
				if($v_QTD_MIN_SLA_ATENDIMENTO == ""){
					// Buscar a data prevista
					if($this->pagina->dateDiffHourPlus($v_DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO) >= 0){
						return 1;
					}else{
						return -1;
					}
				}elseif($this->pagina->dateDiffHourPlus($v_DTH_ENCERRAMENTO_EFETIVO, $v_DTH_ENCERRAMENTO_PREVISAO) >= 0){
					return 1;
				}else{
					return -1;
				}
		  }else{  // SE O CHAMADO AINDA ESTIVER EM ATENDIMENTO
				if($v_QTD_MIN_SLA_ATENDIMENTO == ""){
					$v_DTH_PREVISTA = $v_DTH_ENCERRAMENTO_PREVISAO;
					if($v_DTH_PREVISTA == ""){
						return "";
					}else{
						$diffAtual = $this->pagina->dateDiffHourPlus(date("d/m/Y H:i:s"), $v_DTH_PREVISTA);
						if($diffAtual > 0){
							$diffTotal = $this->pagina->dateDiffHourPlus($v_DTH_ABERTURA, $v_DTH_PREVISTA);
							$aux = $diffAtual / $diffTotal;
							if($aux <= 0.25){
								return 0;
							}else{
								return 1;
							}
						}else{
							return -1;
						}
					}
				}else{
					$diffAtual = $this->pagina->dateDiffHourPlus(date("d/m/Y H:i:s"), $v_DTH_ENCERRAMENTO_PREVISAO);
					if($diffAtual > 0){
						$diffTotal = $this->pagina->dateDiffHourPlus($v_DTH_ABERTURA, $v_DTH_ENCERRAMENTO_PREVISAO);
						$aux = $diffAtual / $diffTotal;
						if($aux <= 0.25){
							return 0;
						}else{
							return 1;
						}
					}else{
						return -1;
					}
				}
		  }
	}

	// Método que identifica qual é a situação do SLA de cada chamado
	// 0 - Risco de atraso
	// -1 Atrasado
	// 1  Em dia
	// Método em não está sendo usado por exigir o cálculo de sla pela segunda vez a cada carregamento
	function fGetCOD_SLA_ATENDIMENTO($v_SEQ_CHAMADO, $v_DTH_ABERTURA, $v_DTH_ENCERRAMENTO_EFETIVO, $v_QTD_MIN_SLA_ATENDIMENTO, $v_FLG_FORMA_MEDICAO_TEMPO, $v_SEQ_TIPO_OCORRENCIA, $v_QTD_MIN_SLA_SOLUCAO_FINAL){
		  // Código do atendimento do SLA
          // SE O CHAMADO NÃO ESTIVER EM ATENDIMENTO
          if($v_DTH_ENCERRAMENTO_EFETIVO != ""){
				if($v_QTD_MIN_SLA_ATENDIMENTO == ""){
					// Buscar a data prevista
					if($this->pagina->dateDiffHourPlus($v_DTH_ENCERRAMENTO_EFETIVO, $this->fGetDTH_PREVISTA($v_SEQ_CHAMADO)) >= 0){
						return 1;
					}else{
						return -1;
					}
				}elseif($this->pagina->dateDiffHourPlus($v_DTH_ENCERRAMENTO_EFETIVO, $this->fGetDTH_ENCERRAMENTO_PREVISAO($v_DTH_ABERTURA, $v_QTD_MIN_SLA_ATENDIMENTO, $v_FLG_FORMA_MEDICAO_TEMPO, $v_SEQ_CHAMADO, $v_SEQ_TIPO_OCORRENCIA, $v_QTD_MIN_SLA_SOLUCAO_FINAL)) >= 0){
					return 1;
				}else{
					return -1;
				}
		  }else{  // SE O CHAMADO AINDA ESTIVER EM ATENDIMENTO
				if($v_QTD_MIN_SLA_ATENDIMENTO == ""){
					$v_DTH_PREVISTA = $this->fGetDTH_PREVISTA($v_SEQ_CHAMADO);
					if($v_DTH_PREVISTA == ""){
						return "";
					}else{
						$diffAtual = $this->pagina->dateDiffHourPlus(date("d/m/Y H:i:s"), $v_DTH_PREVISTA);
						if($diffAtual > 0){
							$diffTotal = $this->pagina->dateDiffHourPlus($v_DTH_ABERTURA, $v_DTH_PREVISTA);
							$aux = $diffAtual / $diffTotal;
							if($aux <= 0.25){
								return 0;
							}else{
								return 1;
							}
						}else{
							return -1;
						}
					}
				}else{
					$diffAtual = $this->pagina->dateDiffHourPlus(date("d/m/Y H:i:s"), $this->fGetDTH_ENCERRAMENTO_PREVISAO($v_DTH_ABERTURA, $v_QTD_MIN_SLA_ATENDIMENTO, $v_FLG_FORMA_MEDICAO_TEMPO, $v_SEQ_CHAMADO, $v_SEQ_TIPO_OCORRENCIA, $v_QTD_MIN_SLA_SOLUCAO_FINAL));
					if($diffAtual > 0){
						$diffTotal = $this->pagina->dateDiffHourPlus($v_DTH_ABERTURA, $this->fGetDTH_ENCERRAMENTO_PREVISAO($v_DTH_ABERTURA, $v_QTD_MIN_SLA_ATENDIMENTO, $v_FLG_FORMA_MEDICAO_TEMPO, $v_SEQ_CHAMADO, $v_SEQ_TIPO_OCORRENCIA, $v_QTD_MIN_SLA_SOLUCAO_FINAL));
						$aux = $diffAtual / $diffTotal;
						if($aux <= 0.25){
							return 0;
						}else{
							return 1;
						}
					}else{
						return -1;
					}
				}
		  }
	}

	function ArrayFeriados(){
		$sql = "select to_char(DTH_FERIADO, 'dd/mm/yyyy') as dtferiado
			from gestaoti.feriado
			order by DTH_FERIADO";
		$aItemOption = Array();
		$this->database->query($sql);
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[] = $row["dtferiado"];
		}
		return $aItemOption;
	}

	function fGetDTH_PREVISTA($v_SEQ_CHAMADO){
		// Buscar aprovação
		$this->aprovacao_chamado = new aprovacao_chamado();
		$this->aprovacao_chamado->GetUltimoAprovacao($v_SEQ_CHAMADO);
		return $this->aprovacao_chamado->DTH_PREVISTA;
	}

	function fGetDSC_SLA_ATENDIMENTO($v_COD_SLA_ATENDIMENTO){
		if($v_COD_SLA_ATENDIMENTO == 1){
			return "Em dia";
		}elseif($v_COD_SLA_ATENDIMENTO == 0){
			return "Risco de atraso";
		}elseif($v_COD_SLA_ATENDIMENTO == -1){
			return "Atrasado";
		}
	}

	function GetSEQ_CHAMADO_MASTER($v_SEQ_CHAMADO){
		$this->situacao_chamado = new situacao_chamado();
		$this->vinculo_chamado = new vinculo_chamado();
		$this->vinculo_chamado->setSEQ_CHAMADO_FILHO($v_SEQ_CHAMADO);
		$this->vinculo_chamado->setSEQ_SITUACAO_CHAMADO_MASTER($this->situacao_chamado->CODS_EM_ANDAMENTO);
		$this->vinculo_chamado->selectParam();
		if($this->vinculo_chamado->database->rows > 0){
			$row = pg_fetch_array($this->vinculo_chamado->database->result);
			return $row["seq_chamado_master"];
		}else{
			return "";
		}

	}

	// **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.chamado WHERE SEQ_CHAMADO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_CHAMADO = $this->database->GetSequenceValue("gestaoti.SEQ_CHAMADO");

		$sql = "INSERT INTO gestaoti.chamado( SEQ_CHAMADO,
                                                      NUM_MATRICULA_SOLICITANTE,
                                                      SEQ_ATIVIDADE_CHAMADO,
                                                      SEQ_SITUACAO_CHAMADO,
                                                      SEQ_LOCALIZACAO_FISICA,
                                                      SEQ_PRIORIDADE_CHAMADO,
                                                      TXT_CHAMADO,
                                                      DTH_ABERTURA,
                                                      DTH_INICIO_PREVISAO,
                                                      NUM_MATRICULA_CONTATO,
                                                      SEQ_ITEM_CONFIGURACAO,
                                                      NUM_PRIORIDADE_FILA,
                                                      SEQ_TIPO_OCORRENCIA,
                                                      NUM_MATRICULA_CADASTRANTE,
                                                      OBJETIVO_EVENTO,
                                                      DTH_RESERVA_EVENTO,
                                                      QUANTIDADE_PESSOAS_EVENTO,
                                                      SERVICOS_EVENTO,
                                                      DT_INICIO_UTILIZACAO_APARELHO,
                                                      DT_FIM_UTILIZACAO_APARELHO,
                                                      FLG_DESTINACAO_CHAMADO
                                            )
                             VALUES (".$this->iif($this->SEQ_CHAMADO=="", "NULL", "'".$this->SEQ_CHAMADO."'").",
                                             ".$this->iif($this->NUM_MATRICULA_SOLICITANTE=="", "NULL", "'".$this->NUM_MATRICULA_SOLICITANTE."'").",
                                             ".$this->iif($this->SEQ_ATIVIDADE_CHAMADO=="", "NULL", "'".$this->SEQ_ATIVIDADE_CHAMADO."'").",
                                             ".$this->iif($this->SEQ_SITUACAO_CHAMADO=="", "NULL", "'".$this->SEQ_SITUACAO_CHAMADO."'").",
                                             ".$this->iif($this->SEQ_LOCALIZACAO_FISICA=="", "NULL", "'".$this->SEQ_LOCALIZACAO_FISICA."'").",
                                             ".$this->iif($this->SEQ_PRIORIDADE_CHAMADO=="", "NULL", "'".$this->SEQ_PRIORIDADE_CHAMADO."'").",
                                             ".$this->iif($this->TXT_CHAMADO=="", "NULL", "'".$this->TXT_CHAMADO."'").",
                                             '".date("Y-m-d H:i:s")."',
                                             ".$this->iif($this->DTH_INICIO_PREVISAO=="", "NULL", "'".$this->DTH_INICIO_PREVISAO."'").",
                                             ".$this->iif($this->NUM_MATRICULA_CONTATO=="", "NULL", "'".$this->NUM_MATRICULA_CONTATO."'").",
                                             ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'").",
                                             ".$this->iif($this->NUM_PRIORIDADE_FILA=="", "NULL", "'".$this->NUM_PRIORIDADE_FILA."'").",
                                             ".$this->iif($this->SEQ_TIPO_OCORRENCIA=="", "NULL", "'".$this->SEQ_TIPO_OCORRENCIA."'").",
                                             ".$this->iif($this->NUM_MATRICULA_CADASTRANTE=="", "NULL", "'".$this->NUM_MATRICULA_CADASTRANTE."'").",
                                             ".$this->iif($this->OBJETIVO_EVENTO=="", "NULL", "'".$this->OBJETIVO_EVENTO."'").",
                                             ".$this->iif($this->DTH_RESERVA_EVENTO=="", "NULL", "'".$this->DTH_RESERVA_EVENTO."'").",
                                             ".$this->iif($this->QUANTIDADE_PESSOAS_EVENTO=="", "NULL", "'".$this->QUANTIDADE_PESSOAS_EVENTO."'").",
                                             ".$this->iif($this->SERVICOS_EVENTO=="", "NULL", "'".$this->SERVICOS_EVENTO."'").",
                                             ".$this->iif($this->DT_INICIO_UTILIZACAO_APARELHO=="", "NULL", "'".$this->DT_INICIO_UTILIZACAO_APARELHO."'").",
                                             ".$this->iif($this->DT_FIM_UTILIZACAO_APARELHO=="", "NULL", "'".$this->DT_FIM_UTILIZACAO_APARELHO."'").",
                                             ".$this->iif($this->FLG_DESTINACAO_CHAMADO=="", "NULL", "'".$this->FLG_DESTINACAO_CHAMADO."'")."
                                            ) ";
		//print $sql;
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.chamado
                         SET SEQ_ATIVIDADE_CHAMADO = ".$this->iif($this->SEQ_ATIVIDADE_CHAMADO=="", "NULL", "'".$this->SEQ_ATIVIDADE_CHAMADO."'").",
                                 SEQ_LOCALIZACAO_FISICA = ".$this->iif($this->SEQ_LOCALIZACAO_FISICA=="", "NULL", "'".$this->SEQ_LOCALIZACAO_FISICA."'").",
                                 SEQ_PRIORIDADE_CHAMADO = ".$this->iif($this->SEQ_PRIORIDADE_CHAMADO=="", "NULL", "'".$this->SEQ_PRIORIDADE_CHAMADO."'").",
                                 NUM_MATRICULA_CONTATO = ".$this->iif($this->NUM_MATRICULA_CONTATO=="", "NULL", "'".$this->NUM_MATRICULA_CONTATO."'").",
                                 SEQ_TIPO_OCORRENCIA = ".$this->iif($this->SEQ_TIPO_OCORRENCIA=="", "NULL", "'".$this->SEQ_TIPO_OCORRENCIA."'").",

                                 OBJETIVO_EVENTO = ".$this->iif($this->OBJETIVO_EVENTO=="", "NULL", "'".$this->OBJETIVO_EVENTO."'").",
                                 DTH_RESERVA_EVENTO = ".$this->iif($this->DTH_RESERVA_EVENTO=="", "NULL", "'".$this->DTH_RESERVA_EVENTO."'").",
                                 QUANTIDADE_PESSOAS_EVENTO = ".$this->iif($this->QUANTIDADE_PESSOAS_EVENTO=="", "NULL", "'".$this->QUANTIDADE_PESSOAS_EVENTO."'").",
                                 SERVICOS_EVENTO = ".$this->iif($this->SERVICOS_EVENTO=="", "NULL", "'".$this->SERVICOS_EVENTO."'").",
                                 DT_INICIO_UTILIZACAO_APARELHO = ".$this->iif($this->DT_INICIO_UTILIZACAO_APARELHO=="", "NULL", "'".$this->DT_INICIO_UTILIZACAO_APARELHO."'").",
                                 DT_FIM_UTILIZACAO_APARELHO = ".$this->iif($this->DT_FIM_UTILIZACAO_APARELHO=="", "NULL", "'".$this->DT_FIM_UTILIZACAO_APARELHO."'").",

                                SEQ_ITEM_CONFIGURACAO = ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'")."
                        WHERE SEQ_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}
	
	function encaminharChamado($id){
		$sql = " UPDATE gestaoti.chamado
				 SET SEQ_ATIVIDADE_CHAMADO = ".$this->iif($this->SEQ_ATIVIDADE_CHAMADO=="", "NULL", "'".$this->SEQ_ATIVIDADE_CHAMADO."'")."
				WHERE SEQ_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}
	function atualizarDataInicioPrevisto($id){
		$sql = " UPDATE gestaoti.chamado
				 SET DTH_INICIO_PREVISAO = ".$this->iif($this->DTH_INICIO_PREVISAO=="", "NULL", "'".$this->DTH_INICIO_PREVISAO."'")." 
				WHERE SEQ_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}

	// *****************************
	// UPDATE de TRIAGEM DE CHAMADOS
	// *****************************
	function triagem($id){
		$sql = " UPDATE gestaoti.chamado
                         SET SEQ_ATIVIDADE_CHAMADO = ".$this->iif($this->SEQ_ATIVIDADE_CHAMADO=="", "NULL", "'".$this->SEQ_ATIVIDADE_CHAMADO."'").",
                                 SEQ_SITUACAO_CHAMADO = ".$this->iif($this->SEQ_SITUACAO_CHAMADO=="", "NULL", "'".$this->SEQ_SITUACAO_CHAMADO."'").",
                                 SEQ_PRIORIDADE_CHAMADO = ".$this->iif($this->SEQ_PRIORIDADE_CHAMADO=="", "NULL", "'".$this->SEQ_PRIORIDADE_CHAMADO."'").",
                                 DTH_TRIAGEM_EFETIVA = '".date("Y-m-d H:i:s")."',
                                 SEQ_TIPO_OCORRENCIA = ".$this->iif($this->SEQ_TIPO_OCORRENCIA=="", "NULL", "'".$this->SEQ_TIPO_OCORRENCIA."'").",
                                 SEQ_ITEM_CONFIGURACAO = ".$this->iif($this->SEQ_ITEM_CONFIGURACAO=="", "NULL", "'".$this->SEQ_ITEM_CONFIGURACAO."'")."
                        WHERE SEQ_CHAMADO = $id ";
		$result = $this->database->query($sql);
	}
	// *********************************
	// UPDATE de ATUALIZAÇÃO DE SITUACAO
	// *********************************
	function AtualizaSituacao($v_SEQ_CHAMADO, $v_SEQ_SITUACAO_CHAMADO){
		$sql = " UPDATE gestaoti.chamado
                         SET SEQ_SITUACAO_CHAMADO = $v_SEQ_SITUACAO_CHAMADO
                         WHERE SEQ_CHAMADO = $v_SEQ_CHAMADO ";
		$result = $this->database->query($sql);
	}

	// *********************************
	// UPDATE de ATUALIZAÇÃO do texto de contigenciamento de incidentes
	// *********************************
	function AtualizaContingenciamento($v_SEQ_CHAMADO, $v_SEQ_ACAO_CONTINGENCIAMENTO, $v_TXT_CONTINGENCIAMENTO){
		$sql = " UPDATE gestaoti.chamado
                         SET SEQ_ACAO_CONTINGENCIAMENTO = $v_SEQ_ACAO_CONTINGENCIAMENTO,
                             TXT_CONTINGENCIAMENTO = '".addslashes($v_TXT_CONTINGENCIAMENTO)."'
                        WHERE SEQ_CHAMADO = $v_SEQ_CHAMADO ";
		$result = $this->database->query($sql);
	}

	// *********************************
	// UPDATE de ATUALIZAÇÃO do texto de contigenciamento de incidentes
	// *********************************
	function AtualizaTxtCausaRaiz($v_SEQ_CHAMADO, $v_TXT_CAUSA_RAIZ){
		$sql = " UPDATE gestaoti.chamado
                         SET TXT_CAUSA_RAIZ = '".addslashes($v_TXT_CAUSA_RAIZ)."'
                         WHERE SEQ_CHAMADO = $v_SEQ_CHAMADO ";
		$result = $this->database->query($sql);
	}

	// *********************************
	// UPDATE de ATUALIZAÇÃO do texto de contigenciamento de incidentes
	// *********************************
	function AtualizaTxtSolucao($v_SEQ_CHAMADO, $v_TXT_RESOLUCAO){
		$sql = " UPDATE gestaoti.chamado
                         SET TXT_RESOLUCAO = '".addslashes($v_TXT_RESOLUCAO)."'
                         WHERE SEQ_CHAMADO = $v_SEQ_CHAMADO ";
		$result = $this->database->query($sql);
	}

	// ***********************************************
	// UPDATE de ATUALIZAÇÃO DA DATA EFETIVA DE INÍCIO
	// ***********************************************
	function AtualizaDTH_INICIO_EFETIVO($v_SEQ_CHAMADO){
		$sql = " UPDATE gestaoti.chamado
                         SET DTH_INICIO_EFETIVO = '".date("Y-m-d H:i:s")."'
                         WHERE SEQ_CHAMADO = $v_SEQ_CHAMADO ";
		$result = $this->database->query($sql);
	}

	// ***********************************************
	// UPDATE de ATUALIZAÇÃO DA DATA EFETIVA DE INÍCIO
	// ***********************************************
	function AtualizaDTH_ENCERRAMENTO_EFETIVO($v_SEQ_CHAMADO){
		$sql = " UPDATE gestaoti.chamado
                         SET DTH_ENCERRAMENTO_EFETIVO = '".date("Y-m-d H:i:s")."'
                         WHERE SEQ_CHAMADO = $v_SEQ_CHAMADO ";
		$result = $this->database->query($sql);
	}

	function ReabrirChamado($v_SEQ_CHAMADO){
		$sql = " UPDATE gestaoti.chamado
				 SET DTH_ENCERRAMENTO_EFETIVO = NULL
				WHERE SEQ_CHAMADO = $v_SEQ_CHAMADO ";
		$result = $this->database->query($sql);
	}

	// ***********************************************
	// Avaliar Chamado
	// ***********************************************
	function AvaliarChamado(){
		$sql = " UPDATE gestaoti.chamado
                         SET seq_avaliacao_atendimento          = ".$this->iif($this->SEQ_AVALIACAO_ATENDIMENTO==""			, "NULL", "'".$this->SEQ_AVALIACAO_ATENDIMENTO."'").",
                             num_matricula_avaliador 			= ".$this->iif($this->NUM_MATRICULA_AVALIADOR==""			, "NULL", "'".$this->NUM_MATRICULA_AVALIADOR."'").",
                             flg_solicitacao_atendida 			= ".$this->iif($this->FLG_SOLICITACAO_ATENDIDA==""			, "NULL", "'".$this->FLG_SOLICITACAO_ATENDIDA."'").",
                             seq_avaliacao_conhecimento_tecnico = ".$this->iif($this->SEQ_AVALIACAO_CONHECIMENTO_TECNICO=="", "NULL", "'".$this->SEQ_AVALIACAO_CONHECIMENTO_TECNICO."'").",
                             seq_avaliacao_postura 				= ".$this->iif($this->SEQ_AVALIACAO_POSTURA==""				, "NULL", "'".$this->SEQ_AVALIACAO_POSTURA."'").",
                             seq_avaliacao_tempo_espera 		= ".$this->iif($this->SEQ_AVALIACAO_TEMPO_ESPERA==""		, "NULL", "'".$this->SEQ_AVALIACAO_TEMPO_ESPERA."'").",
                             seq_avaliacao_tempo_solucao 		= ".$this->iif($this->SEQ_AVALIACAO_TEMPO_SOLUCAO==""		, "NULL", "'".$this->SEQ_AVALIACAO_TEMPO_SOLUCAO."'").",
                             txt_avaliacao = ".$this->iif($this->TXT_AVALIACAO=="", "NULL", "'".$this->TXT_AVALIACAO."'")."
                        WHERE SEQ_CHAMADO = ".$this->SEQ_CHAMADO;
		$result = $this->database->query($sql);
	}

	function CalcularTempoEspera(){
//		$sql = "select sum(b.QTD_MIN_SLA_TRIAGEM) as QTD_MIN_ESPERA_PREVISTA
//				FROM gestaoti.chamado a, gestaoti.atividade_chamado b, gestaoti.viw_colaborador c
//				where a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
//				and a.NUM_MATRICULA_SOLICITANTE = c.NUM_MATRICULA_COLABORADOR
//				and c.COD_DEPENDENCIA_FIS = $v_COD_DEPENDENCIA
//				and a.SEQ_SITUACAO_CHAMADO = 1 ";
		$sql = "select sum(b.QTD_MIN_SLA_TRIAGEM) as QTD_MIN_ESPERA_PREVISTA
                        FROM gestaoti.chamado a, gestaoti.atividade_chamado b
                        where a.SEQ_ATIVIDADE_CHAMADO = b.SEQ_ATIVIDADE_CHAMADO
                        and a.SEQ_SITUACAO_CHAMADO = 1 ";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_object($result, 0);
		if($row->qtd_min_espera_prevista == 0){
			return 15;
		}else{
			return $row->qtd_min_espera_prevista;
		}
	}

	// =======================================================================================
	// Métodos de controle de prioridade
	// =======================================================================================

	// GetultimaPosicaoFila
	// Método que verifica a posição da próxima vaga na fila de prioridades
	// $v_FLG_FILA - existem 2 filas
	//		- Fila 1 - Aguardando atendimento e
	//		- Fila 2 - Em atendimento
	function GetultimaPosicaoFila($v_SEQ_EQUIPE_TI, $v_FLG_FILA="1", $v_SEQ_CHAMADO=""){
		$this->situacao_chamado = new situacao_chamado();
		if($v_FLG_FILA == "1"){
			$v_SEQ_SITUACAO_CHAMADO = $this->situacao_chamado->COD_Aguardando_Atendimento.",".$this->situacao_chamado->COD_Aguardando_Planejamento;
		}else{
			$v_SEQ_SITUACAO_CHAMADO = $this->situacao_chamado->COD_Em_Andamento.",".$this->situacao_chamado->COD_Suspenca;
		}
		$sql = "select nvl(max(a.NUM_PRIORIDADE_FILA),0) + 1 as PROXIMA_POSICAO_FILA
                        FROM gestaoti.chamado a, gestaoti.atribuicao_chamado b
                        where a.SEQ_CHAMADO = b.SEQ_CHAMADO
                        and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO)
                        and b.SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI ";
		if($v_SEQ_CHAMADO != ""){
			$sql .= " and a.SEQ_CHAMADO <> $v_SEQ_CHAMADO ";
		}
		$result = $this->database->query($sql);
		$row = pg_fetch_array($this->database->result);
		//print "$sql - Retorno = ".$row[0];
		return $row[0];
	}

	function AlterarPrioridade($v_SEQ_CHAMADO, $v_NUM_PRIORIDADE_FILA, $v_SEQ_EQUIPE_TI, $v_SEQ_SITUACAO_CHAMADO_PESQUISA, $flagOperacao){
		if($flagOperacao == "A"){ // A - Aumenta a prioridade
			$v_NUM_PRIORIDADE_FILA_REF = $v_NUM_PRIORIDADE_FILA - 1;
		}elseif($flagOperacao == "D"){ // D - Diminui a prioridade
			$v_NUM_PRIORIDADE_FILA_REF = $v_NUM_PRIORIDADE_FILA + 1;
		}

		// Descobrir o chamado que possui a prioridade mais alta
		$sql = "select distinct a.SEQ_CHAMADO
                        FROM gestaoti.chamado a, gestaoti.atribuicao_chamado b
                        where a.seq_chamado = b.seq_chamado
                        and a.NUM_PRIORIDADE_FILA = ".$v_NUM_PRIORIDADE_FILA_REF."
                        and b.SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI
                        and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_PESQUISA)";
		//print $sql."<br>";
		$result = $this->database->query($sql);
		$result = $this->database->result;
		$row = pg_fetch_array($result);
		$v_SEQ_CHAMADO_PRIORIDADE_REF = $row[0];

		// Atualizar o chamado que perdeu prioridade
		if($v_SEQ_CHAMADO_PRIORIDADE_REF != ""){
			$sql = "update chamado
                                set NUM_PRIORIDADE_FILA = $v_NUM_PRIORIDADE_FILA
                                where SEQ_CHAMADO = $v_SEQ_CHAMADO_PRIORIDADE_REF";
			//print $sql."<br>";
			$result = $this->database->query($sql);
		}

		// Aumentar a prioridade do chamado solicitado
		$sql = "update chamado
                        set NUM_PRIORIDADE_FILA = $v_NUM_PRIORIDADE_FILA_REF
                        where SEQ_CHAMADO = $v_SEQ_CHAMADO";
		//print $sql."<br>";
		$result = $this->database->query($sql);
	}

	// RetirarChamadoFila
	// Método que recoloca o chamado em outra fila de prioridades e reposiciona todos os chamados subsequentes
	// $v_FLG_FILA - existem 2 filas
	//		- Fila 1 - Aguardando atendimento e
	//		- Fila 2 - Em atendimento
	function RetirarChamadoFila($v_SEQ_CHAMADO, $v_SEQ_EQUIPE_TI, $v_NUM_PRIORIDADE_FILA, $v_FLG_FILA){
		$this->situacao_chamado = new situacao_chamado();
		$db = new database();
		$v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND = $this->situacao_chamado->COD_Aguardando_Atendimento.",".$this->situacao_chamado->COD_Aguardando_Planejamento;
		$v_SEQ_SITUACAO_CHAMADO_EXEC = $this->situacao_chamado->COD_Em_Andamento.",".$this->situacao_chamado->COD_Suspenca;
		if($v_FLG_FILA == 1){
			// Atualizar posição do chamado na nova fila
			$sql = "update chamado
                                set NUM_PRIORIDADE_FILA = ".$this->GetultimaPosicaoFila($v_SEQ_EQUIPE_TI, "2", $v_SEQ_CHAMADO)."
                                where SEQ_CHAMADO = $v_SEQ_CHAMADO";
			$db->query($sql);

			// Montar SQL de atualização dos demais chamados da fila anterior
			$sqlPrio = "select distinct a.SEQ_CHAMADO, a.NUM_PRIORIDADE_FILA
                                    FROM gestaoti.chamado a, gestaoti.atribuicao_chamado b
                                    where a.seq_chamado = b.seq_chamado
                                    and b.SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI
                                    and a.NUM_PRIORIDADE_FILA > $v_NUM_PRIORIDADE_FILA
                                    and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_AGUARD_ATEND)
                                    order by NUM_PRIORIDADE_FILA";
		}elseif($v_FLG_FILA == "2"){ // Caso o chamado esteja sendo encerrado ou esteja sendo retirado das filas
			$sql = "update chamado
					set NUM_PRIORIDADE_FILA = NULL
					where SEQ_CHAMADO = $v_SEQ_CHAMADO";
			$result = $this->database->query($sql);

			// Montar SQL de atualização dos demais chamados da fila enterior
			$sqlPrio = "select distinct a.SEQ_CHAMADO, a.NUM_PRIORIDADE_FILA
                                    FROM gestaoti.chamado a, gestaoti.atribuicao_chamado b
                                    where a.seq_chamado = b.seq_chamado
                                    and b.SEQ_EQUIPE_TI = $v_SEQ_EQUIPE_TI
                                    and a.NUM_PRIORIDADE_FILA > $v_NUM_PRIORIDADE_FILA
                                    and a.SEQ_SITUACAO_CHAMADO in ($v_SEQ_SITUACAO_CHAMADO_EXEC)
                                    and a.DTH_ENCERRAMENTO_EFETIVO is null
                                    order by NUM_PRIORIDADE_FILA";
		}

		// Atualizar a fila - trazer todos os chamados com prioridade mais baixa para cima
		$result = $this->database->query($sqlPrio);
		while ($row = pg_fetch_array($this->database->result)){
			$v_NUM_PRIORIDADE_FILA_NOVA = $row["num_prioridade_fila"] - 1;
			$sql = "update chamado
                                set NUM_PRIORIDADE_FILA = ".$v_NUM_PRIORIDADE_FILA_NOVA."
                                where SEQ_CHAMADO = ".$row["seq_chamado"];
			$db->query($sql);
		}
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $this->iif($vSelected == $row[0],"Selected", ""), $row[1]);
			$cont++;
		}
		return $aItemOption;
	}

	function CountAvaliacao($v_CAMPO, $v_VALOR, $v_DTH_INICIO, $v_DTH_FIM, $v_SEQ_EQUIPE_TI="", $v_SEQ_CENTRAL_ATENDIMENTO=""){
		$sql = "select count(1) as QTD_CHAMADOS
                        FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d,
                        gestaoti.tipo_chamado e
                        where  a.$v_CAMPO = '$v_VALOR'
                        and a.SEQ_AVALIACAO_ATENDIMENTO = b.SEQ_AVALIACAO_ATENDIMENTO
                        and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
                        and c.seq_subtipo_chamado = d.seq_subtipo_chamado
                        and d.SEQ_TIPO_CHAMADO = e.SEQ_TIPO_CHAMADO 
                        and e.FLG_UTILIZADO_SLA='S'
                        and DTH_ABERTURA between to_date('".$v_DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
                        and                      to_date('".$v_DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 
                        and e.seq_central_atendimento = ".$v_SEQ_CENTRAL_ATENDIMENTO." ";
		if($$v_SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (   select 1
                                                  FROM gestaoti.atribuicao_chamado
                                                  where seq_chamado = a.seq_chamado
                                                  and seq_equipe_ti = ".$v_SEQ_EQUIPE_TI.")

					";
		}
		$result = $this->database->query($sql);
		$row = pg_fetch_array($this->database->result);
		return $row[0];
	}

	function RelatorioAvaliacao($v_DTH_INICIO, $v_DTH_FIM, $v_SEQ_EQUIPE_TI="", $v_SEQ_CENTRAL_ATENDIMENTO=""){
		$sql = "select SEQ_CHAMADO, a.SEQ_AVALIACAO_ATENDIMENTO, SEQ_AVALIACAO_CONHECIMENTO_TECNICO, SEQ_AVALIACAO_POSTURA,
                               SEQ_AVALIACAO_TEMPO_ESPERA, SEQ_AVALIACAO_TEMPO_SOLUCAO, FLG_SOLICITACAO_ATENDIDA, NUM_MATRICULA_AVALIADOR
                        FROM gestaoti.chamado a, gestaoti.avaliacao_atendimento b, gestaoti.atividade_chamado c, gestaoti.subtipo_chamado d,
                        gestaoti.tipo_chamado e
                        where a.SEQ_AVALIACAO_ATENDIMENTO is not null
                        and a.SEQ_AVALIACAO_ATENDIMENTO = b.SEQ_AVALIACAO_ATENDIMENTO
                        and a.SEQ_ATIVIDADE_CHAMADO = c.SEQ_ATIVIDADE_CHAMADO
                        and c.seq_subtipo_chamado = d.seq_subtipo_chamado
                        and d.SEQ_TIPO_CHAMADO = e.SEQ_TIPO_CHAMADO 
                        and e.FLG_UTILIZADO_SLA='S'
                        and DTH_ABERTURA between to_date('".$v_DTH_INICIO." 01:00:00','dd/mm/yyyy hh24:mi:ss')
                        and                        to_date('".$v_DTH_FIM." 23:59:59','dd/mm/yyyy hh24:mi:ss') 

                        and e.seq_central_atendimento = ".$v_SEQ_CENTRAL_ATENDIMENTO." ";
		if($$v_SEQ_EQUIPE_TI != ""){
			$sql .= "  and exists (select 1
                                               FROM gestaoti.atribuicao_chamado
                                               where seq_chamado = a.seq_chamado
                                               and seq_equipe_ti = ".$v_SEQ_EQUIPE_TI.")

					";
		}
		$this->database->query($sql);
	}

	function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
	}
	
// *********************************
	// UPDATE de MOTIVO CANCELAMENTO
	// *********************************
	function AtualizaMotivoCancelamento($v_SEQ_CHAMADO, $SEQ_MOTIVO_CANCELAMENTO){
		$sql = " UPDATE gestaoti.chamado
                         SET SEQ_MOTIVO_CANCELAMENTO = $SEQ_MOTIVO_CANCELAMENTO
                         WHERE SEQ_CHAMADO = $v_SEQ_CHAMADO ";
		$result = $this->database->query($sql);
	}
	
	function transporteEspecial($FUNCOES_USUARIO){
		// Pegar atividade default TI
		require_once '../gestaoti/include/PHP/class/class.parametro.php';
		$parametro = new parametro();
		$FUNCOES_ADM_TRANSPORTE_ESPECIAL = $parametro->GetValorParametro("FUNCOES_ADM_TRANSPORTE_ESPECIAL");		
		$FUNCOES = split(",",$FUNCOES_ADM_TRANSPORTE_ESPECIAL);
		
//		for ($i=0;$i < sizeof($FUNCOES);  $i++){
//			if($FUNCAO==$FUNCOES[$i]){
//				return true;
//			}
//		}
		
		for ($x=0;$x < sizeof($FUNCOES_USUARIO);  $x++){
			for ($i=0;$i < sizeof($FUNCOES);  $i++){
				if($FUNCOES_USUARIO[$x]==$FUNCOES[$i]){
					return true;
				}
			}
		}
		return false;
	}
	
	
	function aprovadorDeChamados($FUNCOES_USUARIO){
		// Pegar atividade default TI
		require_once '../gestaoti/include/PHP/class/class.parametro.php';
		$parametro = new parametro();
		$FUNCOES_ADM_TRANSPORTE_ESPECIAL = $parametro->GetValorParametro("FUNCOES_ADM_TRANSPORTE_ESPECIAL");		
		$FUNCOES = split(",",$FUNCOES_ADM_TRANSPORTE_ESPECIAL);
		
		for ($x=0;$x < sizeof($FUNCOES_USUARIO);  $x++){
			for ($i=0;$i < sizeof($FUNCOES);  $i++){
				if($FUNCOES_USUARIO[$x]==$FUNCOES[$i]){
					return true;
				}
			}
		}
		return false; 
	}

} // class : end
?>