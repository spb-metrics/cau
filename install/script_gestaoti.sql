
SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 7 (class 2615 OID 24382)
-- Name: gestaoti; Type: SCHEMA; Schema: -; Owner: gestaoti
--

CREATE SCHEMA gestaoti;


ALTER SCHEMA gestaoti OWNER TO gestaoti;

--
-- TOC entry 2616 (class 0 OID 0)
-- Dependencies: 7
-- Name: SCHEMA gestaoti; Type: COMMENT; Schema: -; Owner: gestaoti
--

COMMENT ON SCHEMA gestaoti IS 'Sistema de Atendimento';


SET search_path = gestaoti, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 141 (class 1259 OID 24383)
-- Dependencies: 7
-- Name: acao_contingenciamento; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE acao_contingenciamento (
    seq_acao_contingenciamento numeric(9,0) NOT NULL,
    nom_acao_contingenciamento character varying(60) NOT NULL
);


ALTER TABLE gestaoti.acao_contingenciamento OWNER TO gestaoti;

--
-- TOC entry 2618 (class 0 OID 0)
-- Dependencies: 141
-- Name: TABLE acao_contingenciamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE acao_contingenciamento IS 'Tabela que mantém os tipos de ações de contingenciamento possíveis';


--
-- TOC entry 142 (class 1259 OID 24386)
-- Dependencies: 2190 2191 2192 2193 2194 2195 2196 2197 2198 2199 7
-- Name: agendas_entry; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE agendas_entry (
    seq_agendas_entry_id numeric(9,0) NOT NULL,
    start_time integer DEFAULT 0 NOT NULL,
    end_time integer DEFAULT 0 NOT NULL,
    entry_type integer DEFAULT 0 NOT NULL,
    repeat_id integer DEFAULT 0 NOT NULL,
    room_id integer DEFAULT 1 NOT NULL,
    "timestamp" timestamp without time zone DEFAULT now(),
    create_by character varying(80) DEFAULT ''::character varying NOT NULL,
    name character varying(80) DEFAULT ''::character varying NOT NULL,
    type character(1) DEFAULT 'E'::bpchar NOT NULL,
    description text,
    status character(1) DEFAULT 'A'::bpchar NOT NULL,
    num_pessoas integer
);


ALTER TABLE gestaoti.agendas_entry OWNER TO gestaoti;

--
-- TOC entry 143 (class 1259 OID 24402)
-- Dependencies: 7
-- Name: anexo_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE anexo_chamado (
    seq_anexo_chamado numeric(10,0) NOT NULL,
    seq_chamado numeric(10,0) NOT NULL,
    nom_arquivo_sistema character varying(60) NOT NULL,
    nom_arquivo_original character varying(60) NOT NULL,
    dth_anexo timestamp without time zone NOT NULL,
    num_matricula numeric(9,0) NOT NULL
);


ALTER TABLE gestaoti.anexo_chamado OWNER TO gestaoti;

--
-- TOC entry 2619 (class 0 OID 0)
-- Dependencies: 143
-- Name: TABLE anexo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE anexo_chamado IS 'Tabela que mantém informações sobre os arquivos anexos aos chamados.';


--
-- TOC entry 2620 (class 0 OID 0)
-- Dependencies: 143
-- Name: COLUMN anexo_chamado.seq_anexo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN anexo_chamado.seq_anexo_chamado IS 'Campo autonumérico sequencial identificador de cada anexo.';


--
-- TOC entry 2621 (class 0 OID 0)
-- Dependencies: 143
-- Name: COLUMN anexo_chamado.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN anexo_chamado.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2622 (class 0 OID 0)
-- Dependencies: 143
-- Name: COLUMN anexo_chamado.nom_arquivo_sistema; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN anexo_chamado.nom_arquivo_sistema IS 'Nome do arquivo renomeado pelo sistema.';


--
-- TOC entry 2623 (class 0 OID 0)
-- Dependencies: 143
-- Name: COLUMN anexo_chamado.nom_arquivo_original; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN anexo_chamado.nom_arquivo_original IS 'Nome original do arquivo.';


--
-- TOC entry 2624 (class 0 OID 0)
-- Dependencies: 143
-- Name: COLUMN anexo_chamado.dth_anexo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN anexo_chamado.dth_anexo IS 'Data e hora que o arquivo foi anexado ao sistema.';


--
-- TOC entry 2625 (class 0 OID 0)
-- Dependencies: 143
-- Name: COLUMN anexo_chamado.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN anexo_chamado.num_matricula IS 'Núimero da matrícula do responsável pelo arquivo.';


--
-- TOC entry 144 (class 1259 OID 24405)
-- Dependencies: 7
-- Name: anexo_rdm; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE anexo_rdm (
    seq_anexo_rdm numeric(10,0) NOT NULL,
    seq_rdm numeric(10,0),
    nom_arquivo_sistema character(60) NOT NULL,
    nom_arquivo_original character(60) NOT NULL,
    dth_anexo timestamp without time zone NOT NULL,
    num_matricula numeric(9,0) NOT NULL
);


ALTER TABLE gestaoti.anexo_rdm OWNER TO gestaoti;

--
-- TOC entry 145 (class 1259 OID 24408)
-- Dependencies: 7
-- Name: aprovacao_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE aprovacao_chamado (
    seq_aprovacao_chamado numeric(10,0) NOT NULL,
    seq_chamado numeric(10,0) NOT NULL,
    num_matricula numeric(22,0) NOT NULL,
    dth_aprovacao timestamp without time zone NOT NULL,
    dth_prevista timestamp with time zone NOT NULL,
    txt_justificativa text
);


ALTER TABLE gestaoti.aprovacao_chamado OWNER TO gestaoti;

--
-- TOC entry 2626 (class 0 OID 0)
-- Dependencies: 145
-- Name: TABLE aprovacao_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE aprovacao_chamado IS 'Tabela que mantém o histórico sobre a aprovação e o planejamento de execução de chamados de sistemas de informação.';


--
-- TOC entry 2627 (class 0 OID 0)
-- Dependencies: 145
-- Name: COLUMN aprovacao_chamado.seq_aprovacao_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado.seq_aprovacao_chamado IS 'Campo autonumérico sequencial identificador de cada registro de aprovação.';


--
-- TOC entry 2628 (class 0 OID 0)
-- Dependencies: 145
-- Name: COLUMN aprovacao_chamado.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2629 (class 0 OID 0)
-- Dependencies: 145
-- Name: COLUMN aprovacao_chamado.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado.num_matricula IS 'Número da matrícula do reaponsável.';


--
-- TOC entry 2630 (class 0 OID 0)
-- Dependencies: 145
-- Name: COLUMN aprovacao_chamado.dth_aprovacao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado.dth_aprovacao IS 'Data e hora da aprovação.';


--
-- TOC entry 2631 (class 0 OID 0)
-- Dependencies: 145
-- Name: COLUMN aprovacao_chamado.dth_prevista; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado.dth_prevista IS 'Data e hora prevista para o término do chamado.';


--
-- TOC entry 2632 (class 0 OID 0)
-- Dependencies: 145
-- Name: COLUMN aprovacao_chamado.txt_justificativa; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado.txt_justificativa IS 'Justificativa para a alteração de previsão inicial.';


--
-- TOC entry 146 (class 1259 OID 24414)
-- Dependencies: 7
-- Name: aprovacao_chamado_departamento; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE aprovacao_chamado_departamento (
    seq_aprovacao_chamado_departamento numeric(10,0) NOT NULL,
    seq_chamado numeric(10,0) NOT NULL,
    id_unidade integer,
    id_coordenacao integer
);


ALTER TABLE gestaoti.aprovacao_chamado_departamento OWNER TO gestaoti;

--
-- TOC entry 2633 (class 0 OID 0)
-- Dependencies: 146
-- Name: TABLE aprovacao_chamado_departamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE aprovacao_chamado_departamento IS 'Tabela que mantém o histórico sobre a aprovação de chamados de sistemas de informação.';


--
-- TOC entry 2634 (class 0 OID 0)
-- Dependencies: 146
-- Name: COLUMN aprovacao_chamado_departamento.seq_aprovacao_chamado_departamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado_departamento.seq_aprovacao_chamado_departamento IS 'Campo autonumérico sequencial identificador de cada registro de aprovação.';


--
-- TOC entry 2635 (class 0 OID 0)
-- Dependencies: 146
-- Name: COLUMN aprovacao_chamado_departamento.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado_departamento.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2636 (class 0 OID 0)
-- Dependencies: 146
-- Name: COLUMN aprovacao_chamado_departamento.id_unidade; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado_departamento.id_unidade IS 'unidade do solictante.';


--
-- TOC entry 2637 (class 0 OID 0)
-- Dependencies: 146
-- Name: COLUMN aprovacao_chamado_departamento.id_coordenacao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado_departamento.id_coordenacao IS 'coordenacao do solicitante.';


--
-- TOC entry 147 (class 1259 OID 24417)
-- Dependencies: 7
-- Name: aprovacao_chamado_superior; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE aprovacao_chamado_superior (
    seq_aprovacao_chamado_superior numeric(10,0) NOT NULL,
    seq_chamado numeric(10,0) NOT NULL,
    num_matricula numeric(22,0) NOT NULL
);


ALTER TABLE gestaoti.aprovacao_chamado_superior OWNER TO gestaoti;

--
-- TOC entry 2638 (class 0 OID 0)
-- Dependencies: 147
-- Name: TABLE aprovacao_chamado_superior; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE aprovacao_chamado_superior IS 'Tabela que mantém o histórico sobre a aprovação de chamados de sistemas de informação.';


--
-- TOC entry 2639 (class 0 OID 0)
-- Dependencies: 147
-- Name: COLUMN aprovacao_chamado_superior.seq_aprovacao_chamado_superior; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado_superior.seq_aprovacao_chamado_superior IS 'Campo autonumérico sequencial identificador de cada registro de aprovação.';


--
-- TOC entry 2640 (class 0 OID 0)
-- Dependencies: 147
-- Name: COLUMN aprovacao_chamado_superior.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado_superior.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2641 (class 0 OID 0)
-- Dependencies: 147
-- Name: COLUMN aprovacao_chamado_superior.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN aprovacao_chamado_superior.num_matricula IS 'Número da matrícula do reaponsável.';


--
-- TOC entry 148 (class 1259 OID 24420)
-- Dependencies: 7
-- Name: area_atuacao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE area_atuacao (
    seq_area_atuacao numeric(2,0) NOT NULL,
    nom_area_atuacao character varying(60) NOT NULL
);


ALTER TABLE gestaoti.area_atuacao OWNER TO gestaoti;

--
-- TOC entry 149 (class 1259 OID 24423)
-- Dependencies: 7
-- Name: area_envolvida; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE area_envolvida (
    seq_item_configuracao numeric(9,0) NOT NULL,
    cod_uor numeric(9,0) NOT NULL,
    num_matricula_gestor numeric(9,0)
);


ALTER TABLE gestaoti.area_envolvida OWNER TO gestaoti;

--
-- TOC entry 150 (class 1259 OID 24426)
-- Dependencies: 7
-- Name: area_externa; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE area_externa (
    seq_area_externa numeric(9,0) NOT NULL,
    nom_area_externa character varying(300) NOT NULL,
    flg_sisp character(1)
);


ALTER TABLE gestaoti.area_externa OWNER TO gestaoti;

--
-- TOC entry 151 (class 1259 OID 24429)
-- Dependencies: 7
-- Name: area_externa_envolvida; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE area_externa_envolvida (
    seq_area_externa numeric(9,0) NOT NULL,
    seq_item_configuracao numeric(9,0) NOT NULL,
    nom_contato character varying(60),
    num_telefone character varying(20)
);


ALTER TABLE gestaoti.area_externa_envolvida OWNER TO gestaoti;

--
-- TOC entry 152 (class 1259 OID 24432)
-- Dependencies: 7
-- Name: atendimento_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE atendimento_chamado (
    seq_atendimento_chamado numeric(10,0) NOT NULL,
    seq_chamado numeric(10,0) NOT NULL,
    num_matricula numeric(9,0) NOT NULL,
    dth_atendimento_chamado timestamp without time zone NOT NULL,
    txt_atendimento_chamado text NOT NULL
);


ALTER TABLE gestaoti.atendimento_chamado OWNER TO gestaoti;

--
-- TOC entry 2642 (class 0 OID 0)
-- Dependencies: 152
-- Name: TABLE atendimento_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE atendimento_chamado IS 'Tabela que mantém informações sobre as o atendimento realizado a cada chamado.';


--
-- TOC entry 2643 (class 0 OID 0)
-- Dependencies: 152
-- Name: COLUMN atendimento_chamado.seq_atendimento_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atendimento_chamado.seq_atendimento_chamado IS 'Campo autonumérico sequencial identificador de cada registro de atendimento.';


--
-- TOC entry 2644 (class 0 OID 0)
-- Dependencies: 152
-- Name: COLUMN atendimento_chamado.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atendimento_chamado.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2645 (class 0 OID 0)
-- Dependencies: 152
-- Name: COLUMN atendimento_chamado.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atendimento_chamado.num_matricula IS 'Número da matrícula do responsável pelo registro.';


--
-- TOC entry 2646 (class 0 OID 0)
-- Dependencies: 152
-- Name: COLUMN atendimento_chamado.dth_atendimento_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atendimento_chamado.dth_atendimento_chamado IS 'Data e hora de cadastro do registro.';


--
-- TOC entry 2647 (class 0 OID 0)
-- Dependencies: 152
-- Name: COLUMN atendimento_chamado.txt_atendimento_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atendimento_chamado.txt_atendimento_chamado IS 'Descrição do registro.';


--
-- TOC entry 153 (class 1259 OID 24438)
-- Dependencies: 2200 2201 7
-- Name: atividade_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE atividade_chamado (
    seq_atividade_chamado numeric(5,0) NOT NULL,
    seq_subtipo_chamado numeric(4,0) NOT NULL,
    dsc_atividade_chamado character varying(150) NOT NULL,
    qtd_min_sla_triagem numeric(5,0),
    qtd_min_sla_atendimento numeric(5,0),
    flg_atendimento_externo character(1) NOT NULL,
    flg_forma_medicao_tempo character(1),
    qtd_min_sla_solucao_final integer,
    seq_equipe_ti numeric(3,0),
    txt_atividade text,
    seq_tipo_ocorrencia numeric(4,0),
    num_matricula_aprovador numeric(22,0),
    num_matricula_aprovador_substituto numeric(22,0),
    flg_exige_aprovacao character(1),
    flg_exige_agendamento character(1),
    CONSTRAINT ckc_flg_atendimento_e_atividad CHECK ((flg_atendimento_externo = ANY (ARRAY['S'::bpchar, 'N'::bpchar]))),
    CONSTRAINT ckc_flg_forma_medicao_tempo CHECK ((flg_forma_medicao_tempo = ANY (ARRAY['C'::bpchar, 'U'::bpchar])))
);


ALTER TABLE gestaoti.atividade_chamado OWNER TO gestaoti;

--
-- TOC entry 2648 (class 0 OID 0)
-- Dependencies: 153
-- Name: TABLE atividade_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE atividade_chamado IS 'Tabela que mantem informações sobre as atividades de atendimentos possíveis para as áreas de TI da INFRAERO';


--
-- TOC entry 2649 (class 0 OID 0)
-- Dependencies: 153
-- Name: COLUMN atividade_chamado.seq_atividade_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atividade_chamado.seq_atividade_chamado IS 'Campo autonumérico sequencial identificador de cada categoria de chamados.';


--
-- TOC entry 2650 (class 0 OID 0)
-- Dependencies: 153
-- Name: COLUMN atividade_chamado.seq_subtipo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atividade_chamado.seq_subtipo_chamado IS 'Campo autonumérico sequencial identificador de cada subtipo de chamados.';


--
-- TOC entry 2651 (class 0 OID 0)
-- Dependencies: 153
-- Name: COLUMN atividade_chamado.dsc_atividade_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atividade_chamado.dsc_atividade_chamado IS 'Nome da categoria de chamados';


--
-- TOC entry 2652 (class 0 OID 0)
-- Dependencies: 153
-- Name: COLUMN atividade_chamado.qtd_min_sla_triagem; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atividade_chamado.qtd_min_sla_triagem IS 'Quantidade de minutos estabelecidos como máximo para a triagem dos chamados desta categoria.';


--
-- TOC entry 2653 (class 0 OID 0)
-- Dependencies: 153
-- Name: COLUMN atividade_chamado.qtd_min_sla_atendimento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atividade_chamado.qtd_min_sla_atendimento IS 'Quantidade de minutos estabelecidos como máximo para início do atendimento dos chamados desta categoria.';


--
-- TOC entry 2654 (class 0 OID 0)
-- Dependencies: 153
-- Name: COLUMN atividade_chamado.flg_atendimento_externo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atividade_chamado.flg_atendimento_externo IS 'Campo que identifica se os chamados desta categoria podem ser disponibilizados aos clientes em geral ou se são exclusívos para atendimentos internos da TI.';


--
-- TOC entry 2655 (class 0 OID 0)
-- Dependencies: 153
-- Name: COLUMN atividade_chamado.qtd_min_sla_solucao_final; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atividade_chamado.qtd_min_sla_solucao_final IS 'Quantidade de minutos estabelecidos como máximo a solução definitiva do problema. Aplicável apenas para incidentes.';


--
-- TOC entry 2656 (class 0 OID 0)
-- Dependencies: 153
-- Name: COLUMN atividade_chamado.seq_equipe_ti; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atividade_chamado.seq_equipe_ti IS 'Equipe de TI atribuída como padrão para os chamados da atividade';


--
-- TOC entry 2657 (class 0 OID 0)
-- Dependencies: 153
-- Name: COLUMN atividade_chamado.seq_tipo_ocorrencia; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atividade_chamado.seq_tipo_ocorrencia IS 'Tipo de ocorrência que a atividade representa: Incidente, Solicitação, Dúvida';


--
-- TOC entry 154 (class 1259 OID 24446)
-- Dependencies: 7
-- Name: atividade_rb_rdm; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE atividade_rb_rdm (
    seq_atividade_rb_rdm numeric(10,0) NOT NULL,
    seq_item_configuracao numeric(9,0),
    seq_servidor numeric(9,0),
    seq_rdm numeric(10,0) NOT NULL,
    seq_equipe_ti numeric(3,0) NOT NULL,
    descricao character(500) NOT NULL,
    ordem numeric(3,0) NOT NULL,
    data_hora_inicio_execucao timestamp without time zone,
    data_hora_fim_execucao timestamp without time zone,
    situacao numeric(2,0) NOT NULL,
    num_matricula_recurso numeric(22,0)
);


ALTER TABLE gestaoti.atividade_rb_rdm OWNER TO gestaoti;

--
-- TOC entry 155 (class 1259 OID 24452)
-- Dependencies: 7
-- Name: atividade_rb_rdm_template; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE atividade_rb_rdm_template (
    seq_atividade_rb_rdm_template numeric(10,0) NOT NULL,
    seq_item_configuracao numeric(9,0),
    seq_servidor numeric(9,0),
    seq_rdm_template numeric(10,0) NOT NULL,
    seq_equipe_ti numeric(3,0) NOT NULL,
    descricao character(500) NOT NULL,
    ordem numeric(3,0) NOT NULL
);


ALTER TABLE gestaoti.atividade_rb_rdm_template OWNER TO gestaoti;

--
-- TOC entry 156 (class 1259 OID 24458)
-- Dependencies: 7
-- Name: atividade_rdm; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE atividade_rdm (
    seq_rdm numeric(10,0) NOT NULL,
    seq_item_configuracao numeric(9,0),
    seq_servidor numeric(9,0),
    seq_equipe_ti numeric(3,0) NOT NULL,
    descricao character(500) NOT NULL,
    data_hora_prevista_execucao timestamp without time zone NOT NULL,
    seq_atividade_rdm numeric(10,0) NOT NULL,
    data_hora_inicio_execucao timestamp without time zone,
    data_hora_fim_execucao timestamp without time zone,
    situacao numeric(2,0) NOT NULL,
    ordem numeric(3,0) NOT NULL,
    num_matricula_recurso numeric(22,0)
);


ALTER TABLE gestaoti.atividade_rdm OWNER TO gestaoti;

--
-- TOC entry 157 (class 1259 OID 24464)
-- Dependencies: 7
-- Name: atividade_rdm_template; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE atividade_rdm_template (
    seq_rdm_template numeric(10,0) NOT NULL,
    seq_atividade_rdm_template numeric(10,0) NOT NULL,
    seq_item_configuracao numeric(9,0),
    seq_servidor numeric(9,0),
    seq_equipe_ti numeric(3,0) NOT NULL,
    descricao character(500) NOT NULL,
    ordem numeric(3,0) NOT NULL
);


ALTER TABLE gestaoti.atividade_rdm_template OWNER TO gestaoti;

--
-- TOC entry 158 (class 1259 OID 24470)
-- Dependencies: 7
-- Name: atribuicao_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE atribuicao_chamado (
    seq_atribuicao_chamado numeric(10,0) NOT NULL,
    seq_chamado numeric(10,0) NOT NULL,
    seq_equipe_ti numeric(3,0) NOT NULL,
    seq_situacao_chamado numeric(2,0),
    num_matricula numeric(9,0),
    txt_atividade text NOT NULL,
    dth_atribuicao timestamp without time zone NOT NULL,
    seq_equipe_atribuicao numeric(9,0),
    dth_inicio_efetivo timestamp without time zone,
    dth_encerramento_efetivo timestamp without time zone
);


ALTER TABLE gestaoti.atribuicao_chamado OWNER TO gestaoti;

--
-- TOC entry 2658 (class 0 OID 0)
-- Dependencies: 158
-- Name: TABLE atribuicao_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE atribuicao_chamado IS 'Tabela que mantém informações sobre as equipes e profissionais designados ao atendimento da cada chamado.';


--
-- TOC entry 2659 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN atribuicao_chamado.seq_atribuicao_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atribuicao_chamado.seq_atribuicao_chamado IS 'Campo autonumérico sequencial identificador de cada atribuição.';


--
-- TOC entry 2660 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN atribuicao_chamado.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atribuicao_chamado.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2661 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN atribuicao_chamado.seq_equipe_ti; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atribuicao_chamado.seq_equipe_ti IS 'Campo autonumérico sequencial identificador de cada equipe de TI.';


--
-- TOC entry 2662 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN atribuicao_chamado.seq_situacao_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atribuicao_chamado.seq_situacao_chamado IS 'Campo autonumérico sequencial identificador de cada situação.';


--
-- TOC entry 2663 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN atribuicao_chamado.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atribuicao_chamado.num_matricula IS 'Número da matrícula do colaborador.';


--
-- TOC entry 2664 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN atribuicao_chamado.txt_atividade; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atribuicao_chamado.txt_atividade IS 'Descrição das atividades atribuídas como padrão para a equipe padrão designada';


--
-- TOC entry 2665 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN atribuicao_chamado.dth_atribuicao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN atribuicao_chamado.dth_atribuicao IS 'Data e hora da atribuição.';


--
-- TOC entry 159 (class 1259 OID 24476)
-- Dependencies: 7
-- Name: avaliacao_atendimento; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE avaliacao_atendimento (
    seq_avaliacao_atendimento numeric(9,0) NOT NULL,
    nom_avaliacao_atendimento character varying(60) NOT NULL
);


ALTER TABLE gestaoti.avaliacao_atendimento OWNER TO gestaoti;

--
-- TOC entry 160 (class 1259 OID 24479)
-- Dependencies: 7
-- Name: banco_de_dados; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE banco_de_dados (
    seq_banco_de_dados numeric(2,0) NOT NULL,
    nom_banco_de_dados character varying(60) NOT NULL
);


ALTER TABLE gestaoti.banco_de_dados OWNER TO gestaoti;

--
-- TOC entry 161 (class 1259 OID 24482)
-- Dependencies: 7
-- Name: central_atendimento; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE central_atendimento (
    seq_central_atendimento numeric(9,0) NOT NULL,
    nom_central_atendimento character varying(60) NOT NULL
);


ALTER TABLE gestaoti.central_atendimento OWNER TO gestaoti;

--
-- TOC entry 162 (class 1259 OID 24485)
-- Dependencies: 2202 7
-- Name: chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE chamado (
    seq_chamado numeric(10,0) NOT NULL,
    num_matricula_solicitante numeric(22,0) NOT NULL,
    seq_situacao_chamado numeric(2,0) NOT NULL,
    seq_localizacao_fisica numeric(5,0),
    seq_prioridade_chamado numeric(2,0),
    txt_chamado text NOT NULL,
    dth_abertura timestamp without time zone NOT NULL,
    dth_triagem_efetiva timestamp without time zone,
    dth_inicio_previsao timestamp without time zone,
    dth_inicio_efetivo timestamp without time zone,
    dth_encerramento_efetivo timestamp without time zone,
    dth_agendamento timestamp without time zone,
    num_matricula_contato numeric(9,0),
    seq_item_configuracao numeric(9,0),
    num_prioridade_fila numeric(3,0),
    seq_atividade_chamado numeric(9,0),
    flg_solicitacao_atendida character(1),
    seq_avaliacao_atendimento numeric(9,0),
    num_matricula_avaliador numeric(9,0),
    txt_avaliacao text,
    seq_tipo_ocorrencia numeric(9,0) DEFAULT 1 NOT NULL,
    txt_contingenciamento text,
    txt_causa_raiz text,
    num_matricula_cadastrante numeric(22,0),
    seq_acao_contingenciamento numeric(9,0),
    txt_resolucao text,
    seq_motivo_cancelamento numeric(2,0),
    seq_avaliacao_conhecimento_tecnico numeric(9,0),
    seq_avaliacao_postura numeric(9,0),
    seq_avaliacao_tempo_espera numeric(9,0),
    seq_avaliacao_tempo_solucao numeric(9,0),
    objetivo_evento character varying(900),
    dth_reserva_evento timestamp without time zone,
    quantidade_pessoas_evento numeric(4,0),
    servicos_evento character varying(900),
    dt_inicio_utilizacao_aparelho timestamp without time zone,
    dt_fim_utilizacao_aparelho timestamp without time zone,
    flg_destinacao_chamado numeric(9,0)
);


ALTER TABLE gestaoti.chamado OWNER TO gestaoti;

--
-- TOC entry 2666 (class 0 OID 0)
-- Dependencies: 162
-- Name: TABLE chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE chamado IS 'Tabela que mantém informações sobre os chamados de solicitações de atendimentos de TI.';


--
-- TOC entry 2667 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2668 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.num_matricula_solicitante; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.num_matricula_solicitante IS 'Número da matrícula do solicitante.';


--
-- TOC entry 2669 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_situacao_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_situacao_chamado IS 'Campo autonumérico sequencial identificador de cada situação.';


--
-- TOC entry 2670 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_localizacao_fisica; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_localizacao_fisica IS 'Campo autonumérico sequencial identificador de cada local.';


--
-- TOC entry 2671 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_prioridade_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_prioridade_chamado IS 'Campo autonumérico sequencial identificador de cada prioridade.';


--
-- TOC entry 2672 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.txt_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.txt_chamado IS 'Descrição da solicitação';


--
-- TOC entry 2673 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.dth_abertura; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.dth_abertura IS 'Data e hora de abertura';


--
-- TOC entry 2674 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.dth_triagem_efetiva; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.dth_triagem_efetiva IS 'Data e hora da realização da triagem.';


--
-- TOC entry 2675 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.dth_inicio_previsao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.dth_inicio_previsao IS 'Data e hora de início previsto do atendimento conforme SLA. Campo calculado conforme a quantidade de chamados aguardando atendimento para a equipe de triagem correspondente.';


--
-- TOC entry 2676 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.dth_inicio_efetivo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.dth_inicio_efetivo IS 'Data e hora do início efetivo do atendimento.';


--
-- TOC entry 2677 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.dth_encerramento_efetivo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.dth_encerramento_efetivo IS 'Data e hora de encerrament efetivo.';


--
-- TOC entry 2678 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.dth_agendamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.dth_agendamento IS 'Data e hora de agendamento do atendimento.';


--
-- TOC entry 2679 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.num_matricula_contato; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.num_matricula_contato IS 'Nome da pessoa de contato';


--
-- TOC entry 2680 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_item_configuracao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_item_configuracao IS 'Código do sistema de informação relacionado.';


--
-- TOC entry 2681 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.num_prioridade_fila; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.num_prioridade_fila IS 'Número da prioridade do chamado na fila de atendimento';


--
-- TOC entry 2682 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_avaliacao_atendimento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_avaliacao_atendimento IS 'Resposta da avaliação do cliente sobre a pergunta: Satisfação com a solução apresentada';


--
-- TOC entry 2683 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.txt_contingenciamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.txt_contingenciamento IS 'Descrição da ação de contigenciamento tomada para solução paleativa do problema';


--
-- TOC entry 2684 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.txt_causa_raiz; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.txt_causa_raiz IS 'Descrição da causa raiz do incidente';


--
-- TOC entry 2685 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.num_matricula_cadastrante; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.num_matricula_cadastrante IS 'Número da matrícula do profissional que realizou o cadastro do chamado';


--
-- TOC entry 2686 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_acao_contingenciamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_acao_contingenciamento IS 'Ação de contigenciamento tomada para solução paleativa do problema';


--
-- TOC entry 2687 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.txt_resolucao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.txt_resolucao IS 'Informação do técnico sobre a solução do chamado';


--
-- TOC entry 2688 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_motivo_cancelamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_motivo_cancelamento IS 'Motivo do cancelamento do chamado';


--
-- TOC entry 2689 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_avaliacao_conhecimento_tecnico; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_avaliacao_conhecimento_tecnico IS 'Resposta da avaliação do cliente sobre a pergunta: Satisfação com o conhecimento do técnico';


--
-- TOC entry 2690 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_avaliacao_postura; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_avaliacao_postura IS 'Resposta da avaliação do cliente sobre a pergunta: atisfação com a postura e cordialidade do prestados de serviços';


--
-- TOC entry 2691 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_avaliacao_tempo_espera; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_avaliacao_tempo_espera IS 'Resposta da avaliação do cliente sobre a pergunta: Satisfação com o tempo de espera para atendimento';


--
-- TOC entry 2692 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN chamado.seq_avaliacao_tempo_solucao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN chamado.seq_avaliacao_tempo_solucao IS 'Resposta da avaliação do cliente sobre a pergunta: Satisfação com o tempo de solução';


--
-- TOC entry 163 (class 1259 OID 24492)
-- Dependencies: 7
-- Name: chamado_rdm; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE chamado_rdm (
    seq_chamado numeric(10,0) NOT NULL,
    seq_rdm numeric(10,0) NOT NULL
);


ALTER TABLE gestaoti.chamado_rdm OWNER TO gestaoti;

--
-- TOC entry 164 (class 1259 OID 24495)
-- Dependencies: 7
-- Name: correcao_time_sheet; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE correcao_time_sheet (
    seq_time_sheet numeric(22,0) NOT NULL,
    dth_inicio_correcao date NOT NULL,
    dth_fim_correcao date NOT NULL,
    txt_justificativa_correcao text NOT NULL,
    flg_aprovado character(1),
    num_matricula_aprovador numeric(9,0)
);


ALTER TABLE gestaoti.correcao_time_sheet OWNER TO gestaoti;

--
-- TOC entry 2693 (class 0 OID 0)
-- Dependencies: 164
-- Name: TABLE correcao_time_sheet; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE correcao_time_sheet IS 'Tabela que mantém informações sobre as solicitções de correções sobre as horas trabalhadas pelos colaboradores da PRTI';


--
-- TOC entry 2694 (class 0 OID 0)
-- Dependencies: 164
-- Name: COLUMN correcao_time_sheet.seq_time_sheet; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN correcao_time_sheet.seq_time_sheet IS 'Campo autonumérico sequencial identificador de cada registro.';


--
-- TOC entry 2695 (class 0 OID 0)
-- Dependencies: 164
-- Name: COLUMN correcao_time_sheet.dth_inicio_correcao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN correcao_time_sheet.dth_inicio_correcao IS 'Data e hora de início, solicitadas pelo colaborador como sendo corretas.';


--
-- TOC entry 2696 (class 0 OID 0)
-- Dependencies: 164
-- Name: COLUMN correcao_time_sheet.dth_fim_correcao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN correcao_time_sheet.dth_fim_correcao IS 'Data e hora de encerramento, solicitadas pelo colaborador como sendo corretas.';


--
-- TOC entry 2697 (class 0 OID 0)
-- Dependencies: 164
-- Name: COLUMN correcao_time_sheet.txt_justificativa_correcao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN correcao_time_sheet.txt_justificativa_correcao IS 'Justificativa para a correção solicitada.';


--
-- TOC entry 2698 (class 0 OID 0)
-- Dependencies: 164
-- Name: COLUMN correcao_time_sheet.flg_aprovado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN correcao_time_sheet.flg_aprovado IS 'Indicador de aprovação sobre a correções solicitada.';


--
-- TOC entry 2699 (class 0 OID 0)
-- Dependencies: 164
-- Name: COLUMN correcao_time_sheet.num_matricula_aprovador; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN correcao_time_sheet.num_matricula_aprovador IS 'Número da matrícula do responsável pela aprovação das horas solicitadas.';


--
-- TOC entry 165 (class 1259 OID 24501)
-- Dependencies: 7
-- Name: criticidade; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE criticidade (
    seq_criticidade numeric(2,0) NOT NULL,
    nom_criticidade character varying(60) NOT NULL
);


ALTER TABLE gestaoti.criticidade OWNER TO gestaoti;

--
-- TOC entry 166 (class 1259 OID 24504)
-- Dependencies: 7
-- Name: destino_triagem; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE destino_triagem (
    seq_equipe_ti numeric(3,0) NOT NULL,
    cod_dependencia numeric(4,0) NOT NULL
);


ALTER TABLE gestaoti.destino_triagem OWNER TO gestaoti;

--
-- TOC entry 2700 (class 0 OID 0)
-- Dependencies: 166
-- Name: TABLE destino_triagem; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE destino_triagem IS 'Tabela de configuração do destino de cada chamado aberto por clientes das áreas de TI na SEDE e Regionais.';


--
-- TOC entry 2701 (class 0 OID 0)
-- Dependencies: 166
-- Name: COLUMN destino_triagem.seq_equipe_ti; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN destino_triagem.seq_equipe_ti IS 'Campo autonumérico sequencial identificador de cada equipe responsável pela triagem dos chamados.';


--
-- TOC entry 2702 (class 0 OID 0)
-- Dependencies: 166
-- Name: COLUMN destino_triagem.cod_dependencia; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN destino_triagem.cod_dependencia IS 'Código da dependencia responsável pela abertura do chamado';


--
-- TOC entry 167 (class 1259 OID 24507)
-- Dependencies: 7
-- Name: edificacao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE edificacao (
    seq_edificacao numeric(3,0) NOT NULL,
    nom_edificacao character varying(60) NOT NULL,
    cod_dependencia numeric(4,0)
);


ALTER TABLE gestaoti.edificacao OWNER TO gestaoti;

--
-- TOC entry 2703 (class 0 OID 0)
-- Dependencies: 167
-- Name: TABLE edificacao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE edificacao IS 'Tabela que mantem informações sobre as edificações da INFRAERO que serão atendidas pelas áreas de TI na SEDE e Regionais.';


--
-- TOC entry 2704 (class 0 OID 0)
-- Dependencies: 167
-- Name: COLUMN edificacao.seq_edificacao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN edificacao.seq_edificacao IS 'Campo autonumérico sequencial identificador de cada edificação.';


--
-- TOC entry 2705 (class 0 OID 0)
-- Dependencies: 167
-- Name: COLUMN edificacao.nom_edificacao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN edificacao.nom_edificacao IS 'Nome da edificação';


--
-- TOC entry 2706 (class 0 OID 0)
-- Dependencies: 167
-- Name: COLUMN edificacao.cod_dependencia; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN edificacao.cod_dependencia IS 'Código da dependência onde a edificação está localizada.';


--
-- TOC entry 168 (class 1259 OID 24510)
-- Dependencies: 7
-- Name: equipe_atribuicao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE equipe_atribuicao (
    seq_equipe_atribuicao numeric(2,0) NOT NULL,
    dsc_equipe_atribuicao character varying(60) NOT NULL,
    seq_equipe_ti numeric(2,0) NOT NULL
);


ALTER TABLE gestaoti.equipe_atribuicao OWNER TO gestaoti;

--
-- TOC entry 169 (class 1259 OID 24513)
-- Dependencies: 7
-- Name: equipe_envolvida; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE equipe_envolvida (
    num_matricula_recurso numeric(9,0) NOT NULL,
    seq_item_configuracao numeric(9,0) NOT NULL,
    qtd_hora_alocada numeric(2,0) NOT NULL
);


ALTER TABLE gestaoti.equipe_envolvida OWNER TO gestaoti;

--
-- TOC entry 170 (class 1259 OID 24516)
-- Dependencies: 7
-- Name: equipe_servidor; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE equipe_servidor (
    seq_servidor numeric(9,0) NOT NULL,
    num_matricula_recurso numeric(9,0) NOT NULL,
    num_ordem numeric(2,0) NOT NULL
);


ALTER TABLE gestaoti.equipe_servidor OWNER TO gestaoti;

--
-- TOC entry 171 (class 1259 OID 24519)
-- Dependencies: 7
-- Name: equipe_ti; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE equipe_ti (
    seq_equipe_ti numeric(3,0) NOT NULL,
    nom_equipe_ti character varying(60) NOT NULL,
    num_matricula_lider numeric(9,0) NOT NULL,
    num_matricula_substituto numeric(9,0) NOT NULL,
    num_matricula_priorizador numeric(9,0),
    cod_dependencia numeric(4,0),
    seq_central_atendimento numeric(9,0)
);


ALTER TABLE gestaoti.equipe_ti OWNER TO gestaoti;

--
-- TOC entry 2707 (class 0 OID 0)
-- Dependencies: 171
-- Name: TABLE equipe_ti; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE equipe_ti IS 'Tabela que mantém informações sobre as equipes que compõem o corpo funcional das áres de TI na SEDE e Regionais.';


--
-- TOC entry 2708 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN equipe_ti.seq_equipe_ti; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN equipe_ti.seq_equipe_ti IS 'Campo autonumérico sequencial identificador de cada equipe de TI.';


--
-- TOC entry 2709 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN equipe_ti.nom_equipe_ti; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN equipe_ti.nom_equipe_ti IS 'Nome da equipe de TI';


--
-- TOC entry 2710 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN equipe_ti.num_matricula_lider; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN equipe_ti.num_matricula_lider IS 'Número da matrícula do líder da equipe';


--
-- TOC entry 2711 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN equipe_ti.num_matricula_substituto; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN equipe_ti.num_matricula_substituto IS 'Número da matrícula do substituto do líder.';


--
-- TOC entry 2712 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN equipe_ti.num_matricula_priorizador; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN equipe_ti.num_matricula_priorizador IS 'Número da matrícula do cliente responsável pela priorização das demandas da equipe.';


--
-- TOC entry 2713 (class 0 OID 0)
-- Dependencies: 171
-- Name: COLUMN equipe_ti.cod_dependencia; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN equipe_ti.cod_dependencia IS 'Dependência de atuação da equipe.';


--
-- TOC entry 172 (class 1259 OID 24522)
-- Dependencies: 7
-- Name: etapa_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE etapa_chamado (
    seq_etapa_chamado numeric(9,0) NOT NULL,
    seq_chamado numeric(9,0) NOT NULL,
    nom_etapa_chamado character varying(60) NOT NULL,
    dth_inicio_previsto timestamp without time zone NOT NULL,
    dth_fim_previsto timestamp without time zone NOT NULL,
    dth_inicio_efetivo timestamp without time zone,
    dth_fim_efetivo timestamp without time zone
);


ALTER TABLE gestaoti.etapa_chamado OWNER TO gestaoti;

--
-- TOC entry 2714 (class 0 OID 0)
-- Dependencies: 172
-- Name: TABLE etapa_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE etapa_chamado IS 'Tabela que mantém informações sobre o planejamento da execução de chamados de maior complexidade, tratados como projetos.';


--
-- TOC entry 2715 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN etapa_chamado.seq_etapa_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN etapa_chamado.seq_etapa_chamado IS 'Campo autonumérico sequencial identificador de cada registro.';


--
-- TOC entry 2716 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN etapa_chamado.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN etapa_chamado.seq_chamado IS 'Chamado relacionado';


--
-- TOC entry 2717 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN etapa_chamado.nom_etapa_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN etapa_chamado.nom_etapa_chamado IS 'Nome da etapa de execução do chamado';


--
-- TOC entry 2718 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN etapa_chamado.dth_inicio_previsto; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN etapa_chamado.dth_inicio_previsto IS 'Data de início prevista';


--
-- TOC entry 2719 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN etapa_chamado.dth_fim_previsto; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN etapa_chamado.dth_fim_previsto IS 'Data de encerramento previsto';


--
-- TOC entry 2720 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN etapa_chamado.dth_inicio_efetivo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN etapa_chamado.dth_inicio_efetivo IS 'Data de início efetiva';


--
-- TOC entry 2721 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN etapa_chamado.dth_fim_efetivo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN etapa_chamado.dth_fim_efetivo IS 'Data de encerramento efetivo';


--
-- TOC entry 173 (class 1259 OID 24525)
-- Dependencies: 7
-- Name: fase_item_configuracao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE fase_item_configuracao (
    seq_fase_item_configuracao numeric(9,0) NOT NULL,
    seq_item_configuracao numeric(9,0) NOT NULL,
    dat_inicio_fase_projeto date NOT NULL,
    seq_fase_projeto numeric(9,0) NOT NULL,
    dsc_fase_item_configuracao text,
    dat_fim_fase_projeto date NOT NULL,
    txt_observacao_fase text
);


ALTER TABLE gestaoti.fase_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 174 (class 1259 OID 24531)
-- Dependencies: 7
-- Name: fase_projeto; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE fase_projeto (
    seq_fase_projeto numeric(9,0) NOT NULL,
    nom_fase_projeto character varying(60) NOT NULL
);


ALTER TABLE gestaoti.fase_projeto OWNER TO gestaoti;

--
-- TOC entry 175 (class 1259 OID 24534)
-- Dependencies: 7
-- Name: feriado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE feriado (
    seq_feriado numeric(22,0) NOT NULL,
    dth_feriado date NOT NULL,
    nom_feriado text NOT NULL
);


ALTER TABLE gestaoti.feriado OWNER TO gestaoti;

--
-- TOC entry 2722 (class 0 OID 0)
-- Dependencies: 175
-- Name: TABLE feriado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE feriado IS 'Tabela que mantém informações sobre os feriados e datas em que a central não irá trabalhar.';


--
-- TOC entry 176 (class 1259 OID 24540)
-- Dependencies: 7
-- Name: frequencia_manutencao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE frequencia_manutencao (
    seq_frequencia_manutencao numeric(2,0) NOT NULL,
    nom_frequencia_manutencao character varying(60) NOT NULL
);


ALTER TABLE gestaoti.frequencia_manutencao OWNER TO gestaoti;

--
-- TOC entry 177 (class 1259 OID 24543)
-- Dependencies: 7
-- Name: historico_acesso_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE historico_acesso_chamado (
    seq_historico_acesso_chamado numeric(10,0) NOT NULL,
    seq_chamado numeric(10,0) NOT NULL,
    num_matricula numeric(9,0) NOT NULL,
    dth_acesso timestamp without time zone NOT NULL
);


ALTER TABLE gestaoti.historico_acesso_chamado OWNER TO gestaoti;

--
-- TOC entry 2723 (class 0 OID 0)
-- Dependencies: 177
-- Name: TABLE historico_acesso_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE historico_acesso_chamado IS 'Tabela que mantém informações sobre os acessos realizados por clientes e colaboradores às informações dos chamados';


--
-- TOC entry 2724 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN historico_acesso_chamado.seq_historico_acesso_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_acesso_chamado.seq_historico_acesso_chamado IS 'Código único sequencial, identificador de cada registro.';


--
-- TOC entry 2725 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN historico_acesso_chamado.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_acesso_chamado.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2726 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN historico_acesso_chamado.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_acesso_chamado.num_matricula IS 'Número da matrícula do colaborador que realizou o acesso.';


--
-- TOC entry 2727 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN historico_acesso_chamado.dth_acesso; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_acesso_chamado.dth_acesso IS 'Data e hora do acesso.';


--
-- TOC entry 178 (class 1259 OID 24546)
-- Dependencies: 7
-- Name: historico_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE historico_chamado (
    seq_historico_chamado numeric(10,0) NOT NULL,
    seq_chamado numeric(10,0) NOT NULL,
    num_matricula numeric(9,0) NOT NULL,
    dth_historico timestamp without time zone NOT NULL,
    seq_situacao_chamado numeric(2,0) NOT NULL,
    seq_motivo_suspencao numeric(2,0),
    txt_historico text
);


ALTER TABLE gestaoti.historico_chamado OWNER TO gestaoti;

--
-- TOC entry 2728 (class 0 OID 0)
-- Dependencies: 178
-- Name: TABLE historico_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE historico_chamado IS 'Tabela que mantém informações sobre o histórico de atendimento do chamado.';


--
-- TOC entry 2729 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN historico_chamado.seq_historico_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_chamado.seq_historico_chamado IS 'Campo autonumérico sequencial identificador de cada registro.';


--
-- TOC entry 2730 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN historico_chamado.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_chamado.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2731 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN historico_chamado.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_chamado.num_matricula IS 'Número da matrícula do responsável.';


--
-- TOC entry 2732 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN historico_chamado.dth_historico; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_chamado.dth_historico IS 'Data e hora do registro.';


--
-- TOC entry 2733 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN historico_chamado.seq_motivo_suspencao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_chamado.seq_motivo_suspencao IS 'Campo autonumérico sequencial identificador de cada motivo de suspenção.';


--
-- TOC entry 2734 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN historico_chamado.txt_historico; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN historico_chamado.txt_historico IS 'Descrição do histórico.';


--
-- TOC entry 179 (class 1259 OID 24552)
-- Dependencies: 7
-- Name: informativo; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE informativo (
    seq_informativo numeric(10,0) NOT NULL,
    num_matricula numeric(9,0) NOT NULL,
    dth_cadastro date NOT NULL,
    dth_vigencia date NOT NULL,
    txt_informativo character varying(5000) NOT NULL
);


ALTER TABLE gestaoti.informativo OWNER TO gestaoti;

--
-- TOC entry 2735 (class 0 OID 0)
-- Dependencies: 179
-- Name: TABLE informativo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE informativo IS 'Tabela que mantém informações sobre os informativos da PRTI aos seus clientes';


--
-- TOC entry 2736 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN informativo.seq_informativo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN informativo.seq_informativo IS 'Código único, identificador de cada informativo.';


--
-- TOC entry 2737 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN informativo.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN informativo.num_matricula IS 'Número da matrícula do responsável.';


--
-- TOC entry 2738 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN informativo.dth_cadastro; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN informativo.dth_cadastro IS 'Data e hora de cadastro.';


--
-- TOC entry 2739 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN informativo.dth_vigencia; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN informativo.dth_vigencia IS 'Data e hora de vigência do informativo na página inicial do sistema.';


--
-- TOC entry 2740 (class 0 OID 0)
-- Dependencies: 179
-- Name: COLUMN informativo.txt_informativo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN informativo.txt_informativo IS 'HTML do informativo.';


--
-- TOC entry 180 (class 1259 OID 24558)
-- Dependencies: 7
-- Name: informativo_publico; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE informativo_publico (
    seq_informativo numeric(10,0) NOT NULL,
    cod_dependencia numeric(22,0) NOT NULL
);


ALTER TABLE gestaoti.informativo_publico OWNER TO gestaoti;

--
-- TOC entry 2741 (class 0 OID 0)
-- Dependencies: 180
-- Name: TABLE informativo_publico; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE informativo_publico IS 'Tabela que mantem informações sobre as dependências que receberão as informações do informativo.';


--
-- TOC entry 2742 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN informativo_publico.seq_informativo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN informativo_publico.seq_informativo IS 'Código único identificador de cada informativo.';


--
-- TOC entry 2743 (class 0 OID 0)
-- Dependencies: 180
-- Name: COLUMN informativo_publico.cod_dependencia; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN informativo_publico.cod_dependencia IS 'Código da dependência.';


--
-- TOC entry 181 (class 1259 OID 24561)
-- Dependencies: 7
-- Name: inoperancia_item_configuracao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE inoperancia_item_configuracao (
    seq_inoperancia_item_config numeric(9,0) NOT NULL,
    seq_item_configuracao numeric(9,0) NOT NULL,
    dth_inicio timestamp without time zone NOT NULL,
    txt_motivo text,
    dth_fim timestamp without time zone NOT NULL,
    txt_solucao text
);


ALTER TABLE gestaoti.inoperancia_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 182 (class 1259 OID 24567)
-- Dependencies: 7
-- Name: item_configuracao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE item_configuracao (
    seq_item_configuracao numeric(9,0) NOT NULL,
    seq_tipo_item_configuracao numeric(9,0) NOT NULL,
    seq_prioridade numeric(9,0) NOT NULL,
    seq_criticidade numeric(9,0) NOT NULL,
    seq_tipo_disponibilidade numeric(9,0) NOT NULL,
    seq_equipe_ti numeric(9,0) NOT NULL,
    num_matricula_gestor numeric(22,0) NOT NULL,
    num_matricula_lider numeric(22,0) NOT NULL,
    sig_item_configuracao character varying(30) NOT NULL,
    nom_item_configuracao character varying(60) NOT NULL,
    cod_uor_area_gestora numeric(22,0) NOT NULL,
    txt_item_configuracao text NOT NULL,
    seq_servico numeric(9,0)
);


ALTER TABLE gestaoti.item_configuracao OWNER TO gestaoti;

--
-- TOC entry 183 (class 1259 OID 24573)
-- Dependencies: 7
-- Name: item_configuracao_software; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE item_configuracao_software (
    seq_item_configuracao numeric(9,0) NOT NULL,
    seq_tipo_software numeric(9,0) NOT NULL,
    seq_status_software numeric(9,0) NOT NULL,
    flg_em_manutencao character(1) NOT NULL,
    flg_peti character(1) NOT NULL,
    num_item_peti character varying(20),
    flg_descontinuado character(1) NOT NULL,
    flg_sistema_web character(1) NOT NULL,
    dsc_localizacao_documentacao character varying(200),
    val_tamanho_software numeric(5,2),
    seq_unidade_medida_software numeric(9,0),
    val_aquisicao numeric(5,2),
    seq_frequencia_manutencao numeric(9,0),
    flg_tamanho character(1)
);


ALTER TABLE gestaoti.item_configuracao_software OWNER TO gestaoti;

--
-- TOC entry 184 (class 1259 OID 24576)
-- Dependencies: 7
-- Name: janela_mudacao_item_configuracao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE janela_mudacao_item_configuracao (
    seq_janela_mudanca numeric(10,0) NOT NULL,
    seq_item_configuracao numeric(9,0) NOT NULL
);


ALTER TABLE gestaoti.janela_mudacao_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 185 (class 1259 OID 24579)
-- Dependencies: 7
-- Name: janela_mudanca; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE janela_mudanca (
    seq_janela_mudanca numeric(10,0) NOT NULL,
    dsc_janela_mudanca character varying(60) NOT NULL,
    hora_inicio_mudanca numeric(2,0) NOT NULL,
    minuto_inicio_mudanca numeric(2,0) NOT NULL,
    hora_fim_mudanca numeric(2,0) NOT NULL,
    minuto_fim_mudanca numeric(2,0) NOT NULL,
    dia_semana_inicial character varying(3) NOT NULL,
    dia_semana_final character varying(3) NOT NULL,
    limite_para_rdm numeric(10,0) NOT NULL
);


ALTER TABLE gestaoti.janela_mudanca OWNER TO gestaoti;

--
-- TOC entry 186 (class 1259 OID 24582)
-- Dependencies: 7
-- Name: janela_mudanca_servidor; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE janela_mudanca_servidor (
    seq_janela_mudanca numeric(10,0) NOT NULL,
    seq_servidor numeric(9,0) NOT NULL
);


ALTER TABLE gestaoti.janela_mudanca_servidor OWNER TO gestaoti;

--
-- TOC entry 187 (class 1259 OID 24585)
-- Dependencies: 7
-- Name: linguagem_programacao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE linguagem_programacao (
    seq_linguagem_programacao numeric(2,0) NOT NULL,
    nom_linguagem_programacao character varying(60) NOT NULL
);


ALTER TABLE gestaoti.linguagem_programacao OWNER TO gestaoti;

--
-- TOC entry 188 (class 1259 OID 24588)
-- Dependencies: 7
-- Name: localizacao_fisica; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE localizacao_fisica (
    seq_localizacao_fisica numeric(5,0) NOT NULL,
    seq_edificacao numeric(3,0) NOT NULL,
    nom_localizacao_fisica character varying(60) NOT NULL
);


ALTER TABLE gestaoti.localizacao_fisica OWNER TO gestaoti;

--
-- TOC entry 2744 (class 0 OID 0)
-- Dependencies: 188
-- Name: TABLE localizacao_fisica; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE localizacao_fisica IS 'Tabela que mantem informações sobre os locais de atendimento de cada edificação.';


--
-- TOC entry 2745 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN localizacao_fisica.seq_localizacao_fisica; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN localizacao_fisica.seq_localizacao_fisica IS 'Campo autonumérico sequencial identificador de cada local físico de atendimento.';


--
-- TOC entry 2746 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN localizacao_fisica.seq_edificacao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN localizacao_fisica.seq_edificacao IS 'Campo autonumérico sequencial identificador de cada edificação.';


--
-- TOC entry 2747 (class 0 OID 0)
-- Dependencies: 188
-- Name: COLUMN localizacao_fisica.nom_localizacao_fisica; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN localizacao_fisica.nom_localizacao_fisica IS 'Nome do local.';


--
-- TOC entry 189 (class 1259 OID 24591)
-- Dependencies: 7
-- Name: marca_hardware; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE marca_hardware (
    seq_marca_hardware numeric(2,0) NOT NULL,
    nom_marca_hardware character varying(60) NOT NULL
);


ALTER TABLE gestaoti.marca_hardware OWNER TO gestaoti;

--
-- TOC entry 190 (class 1259 OID 24594)
-- Dependencies: 7
-- Name: menu_acesso; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE menu_acesso (
    seq_menu_acesso numeric(2,0) NOT NULL,
    seq_menu_acesso_pai numeric(2,0),
    dsc_menu_acesso character varying(60) NOT NULL,
    nom_arquivo character varying(60) NOT NULL,
    num_prioridade numeric(2,0) NOT NULL,
    nom_arquivo_imagem_escuro character varying(60),
    nom_arquivo_imagem_claro character varying(60)
);


ALTER TABLE gestaoti.menu_acesso OWNER TO gestaoti;

--
-- TOC entry 191 (class 1259 OID 24597)
-- Dependencies: 7
-- Name: menu_perfil_acesso; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE menu_perfil_acesso (
    seq_perfil_acesso numeric(2,0) NOT NULL,
    seq_menu_acesso numeric(2,0) NOT NULL
);


ALTER TABLE gestaoti.menu_perfil_acesso OWNER TO gestaoti;

--
-- TOC entry 192 (class 1259 OID 24600)
-- Dependencies: 7
-- Name: motivo_cancelamento; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE motivo_cancelamento (
    seq_motivo_cancelamento numeric(2,0) NOT NULL,
    dsc_motivo_cancelamento character varying(60) NOT NULL
);


ALTER TABLE gestaoti.motivo_cancelamento OWNER TO gestaoti;

--
-- TOC entry 2748 (class 0 OID 0)
-- Dependencies: 192
-- Name: TABLE motivo_cancelamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE motivo_cancelamento IS 'Tabela que mantém informações sobre os possíveis motivos de cancelamento de chamados.';


--
-- TOC entry 2749 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN motivo_cancelamento.seq_motivo_cancelamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN motivo_cancelamento.seq_motivo_cancelamento IS 'Campo autonumérico sequencial identificador de cada motivo de cancelamento de chamados.';


--
-- TOC entry 2750 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN motivo_cancelamento.dsc_motivo_cancelamento; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN motivo_cancelamento.dsc_motivo_cancelamento IS 'Descrição do motivo';


--
-- TOC entry 193 (class 1259 OID 24603)
-- Dependencies: 7
-- Name: motivo_suspencao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE motivo_suspencao (
    seq_motivo_suspencao numeric(2,0) NOT NULL,
    dsc_motivo_suspencao character varying(60) NOT NULL
);


ALTER TABLE gestaoti.motivo_suspencao OWNER TO gestaoti;

--
-- TOC entry 2751 (class 0 OID 0)
-- Dependencies: 193
-- Name: TABLE motivo_suspencao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE motivo_suspencao IS 'Tabela que mantém informações sobre os possíveis motivos de suspensão de chamados.';


--
-- TOC entry 2752 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN motivo_suspencao.seq_motivo_suspencao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN motivo_suspencao.seq_motivo_suspencao IS 'Campo autonumérico sequencial identificador de cada motivo de suspencao.';


--
-- TOC entry 2753 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN motivo_suspencao.dsc_motivo_suspencao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN motivo_suspencao.dsc_motivo_suspencao IS 'Descrição do motivo';


--
-- TOC entry 194 (class 1259 OID 24606)
-- Dependencies: 7
-- Name: parametro; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE parametro (
    cod_parametro character varying(60) NOT NULL,
    nom_parametro character varying(100) NOT NULL,
    val_parametro character varying(1500) NOT NULL
);


ALTER TABLE gestaoti.parametro OWNER TO gestaoti;

--
-- TOC entry 2754 (class 0 OID 0)
-- Dependencies: 194
-- Name: TABLE parametro; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE parametro IS 'Tabela que mantém toda a parametrização do sistema';


--
-- TOC entry 195 (class 1259 OID 24612)
-- Dependencies: 7
-- Name: patrimonio_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE patrimonio_chamado (
    seq_chamado numeric(10,0) NOT NULL,
    num_patrimonio character varying(9) NOT NULL
);


ALTER TABLE gestaoti.patrimonio_chamado OWNER TO gestaoti;

--
-- TOC entry 2755 (class 0 OID 0)
-- Dependencies: 195
-- Name: TABLE patrimonio_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE patrimonio_chamado IS 'Tabela que mantém informações sobre os patrimônios que estão relacionados com o atendimento do chamado.';


--
-- TOC entry 2756 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN patrimonio_chamado.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN patrimonio_chamado.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2757 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN patrimonio_chamado.num_patrimonio; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN patrimonio_chamado.num_patrimonio IS 'Número do patrimônio';


--
-- TOC entry 196 (class 1259 OID 24615)
-- Dependencies: 7
-- Name: perfil_acesso; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE perfil_acesso (
    seq_perfil_acesso numeric(2,0) NOT NULL,
    nom_perfil_acesso character varying(60) NOT NULL
);


ALTER TABLE gestaoti.perfil_acesso OWNER TO gestaoti;

--
-- TOC entry 197 (class 1259 OID 24618)
-- Dependencies: 7
-- Name: perfil_recurso_ti; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE perfil_recurso_ti (
    seq_perfil_recurso_ti numeric(2,0) NOT NULL,
    nom_perfil_recurso_ti character varying(60) NOT NULL,
    val_hora numeric(5,2) NOT NULL
);


ALTER TABLE gestaoti.perfil_recurso_ti OWNER TO gestaoti;

--
-- TOC entry 198 (class 1259 OID 24621)
-- Dependencies: 2203 2204 7
-- Name: pessoa; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE pessoa (
    seq_pessoa numeric(22,0) NOT NULL,
    nom_login_rede character varying(150) NOT NULL,
    nome character varying(150) NOT NULL,
    nome_abreviado character varying(150),
    nome_guerra character varying(150),
    des_email character varying(150) NOT NULL,
    num_ddd character varying(3),
    num_telefone character varying(10),
    num_voip character varying(10),
    des_status character varying(1) NOT NULL,
    des_senha character varying(10),
    seq_unidade_organizacional numeric(22,0) NOT NULL,
    seq_pessoa_superior_hierarquico numeric(22,0),
    seq_tipo_funcao_administrativa numeric(22,0) DEFAULT NULL::numeric,
    flg_cadastro_atualizado character varying(1) DEFAULT 'N'::character varying
);


ALTER TABLE gestaoti.pessoa OWNER TO gestaoti;

--
-- TOC entry 2758 (class 0 OID 0)
-- Dependencies: 198
-- Name: TABLE pessoa; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE pessoa IS 'Tabela que matém informações sobre todos os usuários da aplicação.';


--
-- TOC entry 2759 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.seq_pessoa; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.seq_pessoa IS 'Número da matrícula';


--
-- TOC entry 2760 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.nom_login_rede; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.nom_login_rede IS 'Login da pessoa no sistema';


--
-- TOC entry 2761 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.nome; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.nome IS 'Nome da pessoa';


--
-- TOC entry 2762 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.nome_abreviado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.nome_abreviado IS 'Nome abreviado';


--
-- TOC entry 2763 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.nome_guerra; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.nome_guerra IS 'Nome de guerra da pessoa (crahcá)';


--
-- TOC entry 2764 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.des_email; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.des_email IS 'E-mail da pessoa';


--
-- TOC entry 2765 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.num_ddd; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.num_ddd IS 'DDD do telefone da pessoa';


--
-- TOC entry 2766 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.num_telefone; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.num_telefone IS 'Telefone da pessoa';


--
-- TOC entry 2767 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.num_voip; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.num_voip IS 'Ramal da pessoa';


--
-- TOC entry 2768 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.des_status; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.des_status IS 'Status da pessoa no sistema';


--
-- TOC entry 2769 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.des_senha; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.des_senha IS 'Senha criptografada de acesso ao sistema';


--
-- TOC entry 2770 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.seq_unidade_organizacional; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.seq_unidade_organizacional IS 'Código da unidade organizacional onde a pessoa está alocada';


--
-- TOC entry 2771 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.seq_pessoa_superior_hierarquico; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.seq_pessoa_superior_hierarquico IS 'Código do superior hieráquico';


--
-- TOC entry 2772 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.seq_tipo_funcao_administrativa; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.seq_tipo_funcao_administrativa IS 'Código da função administrativa da pessoa';


--
-- TOC entry 2773 (class 0 OID 0)
-- Dependencies: 198
-- Name: COLUMN pessoa.flg_cadastro_atualizado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN pessoa.flg_cadastro_atualizado IS 'Verificação se o cadastro do usuário está completo e atualizado';


--
-- TOC entry 199 (class 1259 OID 24629)
-- Dependencies: 7
-- Name: prioridade; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE prioridade (
    seq_prioridade numeric(2,0) NOT NULL,
    nom_prioridade character varying(60) NOT NULL
);


ALTER TABLE gestaoti.prioridade OWNER TO gestaoti;

--
-- TOC entry 200 (class 1259 OID 24632)
-- Dependencies: 7
-- Name: prioridade_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE prioridade_chamado (
    seq_prioridade_chamado numeric(2,0) NOT NULL,
    dsc_prioridade_chamado character varying(60) NOT NULL
);


ALTER TABLE gestaoti.prioridade_chamado OWNER TO gestaoti;

--
-- TOC entry 2774 (class 0 OID 0)
-- Dependencies: 200
-- Name: TABLE prioridade_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE prioridade_chamado IS 'Tabela que mantém informações sobre as prioridades dos chamados.';


--
-- TOC entry 2775 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN prioridade_chamado.seq_prioridade_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN prioridade_chamado.seq_prioridade_chamado IS 'Campo autonumérico sequencial identificador de cada prioridade.';


--
-- TOC entry 2776 (class 0 OID 0)
-- Dependencies: 200
-- Name: COLUMN prioridade_chamado.dsc_prioridade_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN prioridade_chamado.dsc_prioridade_chamado IS 'Nome da prioridade';


--
-- TOC entry 201 (class 1259 OID 24635)
-- Dependencies: 7
-- Name: rdm; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE rdm (
    titulo character(80) NOT NULL,
    justificativa character(80) NOT NULL,
    impacto_nao_executar character(80) NOT NULL,
    nome_resp_checklist character(80) NOT NULL,
    seq_rdm numeric(10,0) NOT NULL,
    ddd_telefone_resp_checklist numeric(2,0) NOT NULL,
    numero_telefone_resp_checklist numeric(8,0) NOT NULL,
    num_matricula_solicitante numeric(22,0) NOT NULL,
    situacao_atual numeric(2,0) NOT NULL,
    data_hora_prevista_execucao timestamp without time zone NOT NULL,
    data_hora_inicio_execucao timestamp without time zone,
    data_hora_fim_execucao timestamp without time zone,
    tipo numeric(2,0) NOT NULL,
    observacao character(500),
    data_hora_abertura timestamp without time zone NOT NULL,
    data_hora_ultima_atualizacao timestamp without time zone NOT NULL,
    email_resp_checklist character(80)
);


ALTER TABLE gestaoti.rdm OWNER TO gestaoti;

--
-- TOC entry 202 (class 1259 OID 24641)
-- Dependencies: 7
-- Name: rdm_template; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE rdm_template (
    titulo character(80) NOT NULL,
    justificativa character(80) NOT NULL,
    impacto_nao_executar character(80) NOT NULL,
    nome_resp_checklist character(80) NOT NULL,
    seq_rdm_template numeric(10,0) NOT NULL,
    ddd_telefone_resp_checklist numeric(2,0) NOT NULL,
    numero_telefone_resp_checklist numeric(8,0) NOT NULL,
    observacao character(500),
    email_resp_checklist character(80),
    seq_rdm_origem numeric(10,0)
);


ALTER TABLE gestaoti.rdm_template OWNER TO gestaoti;

--
-- TOC entry 203 (class 1259 OID 24647)
-- Dependencies: 7
-- Name: recurso_ti; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE recurso_ti (
    num_matricula_recurso numeric(22,0) NOT NULL,
    seq_equipe_ti numeric(3,0),
    seq_perfil_recurso_ti numeric(22,0) NOT NULL,
    seq_perfil_acesso numeric(22,0) NOT NULL,
    seq_area_atuacao numeric(22,0)
);


ALTER TABLE gestaoti.recurso_ti OWNER TO gestaoti;

--
-- TOC entry 2777 (class 0 OID 0)
-- Dependencies: 203
-- Name: TABLE recurso_ti; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE recurso_ti IS 'Tabela que matém informações sobre os colaboradores INFRAERO que fazem parte do corpo funcional das áreas de TI na SEDE e Regionais.';


--
-- TOC entry 2778 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN recurso_ti.seq_equipe_ti; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN recurso_ti.seq_equipe_ti IS 'Campo autonumérico sequencial identificador de cada equipe de TI.';


--
-- TOC entry 204 (class 1259 OID 24650)
-- Dependencies: 7
-- Name: recurso_ti_x_perfil_acesso; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE recurso_ti_x_perfil_acesso (
    num_matricula_recurso numeric(22,0) NOT NULL,
    seq_perfil_acesso numeric(2,0) NOT NULL
);


ALTER TABLE gestaoti.recurso_ti_x_perfil_acesso OWNER TO gestaoti;

--
-- TOC entry 205 (class 1259 OID 24653)
-- Dependencies: 7
-- Name: relac_item_configuracao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE relac_item_configuracao (
    seq_relac_item_configuracao numeric(9,0) NOT NULL,
    seq_item_configuracao_pai numeric(9,0),
    seq_tipo_relac_item_config numeric(9,0) NOT NULL,
    seq_item_configuracao_filho numeric(9,0),
    seq_servidor numeric(9,0)
);


ALTER TABLE gestaoti.relac_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 206 (class 1259 OID 24656)
-- Dependencies: 2205 2206 7
-- Name: responsavel_unidade_organizacional; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE responsavel_unidade_organizacional (
    seq_unidade_organizacional numeric(22,0) DEFAULT NULL::numeric NOT NULL,
    seq_pessoa numeric(22,0) DEFAULT NULL::numeric NOT NULL
);


ALTER TABLE gestaoti.responsavel_unidade_organizacional OWNER TO gestaoti;

--
-- TOC entry 2779 (class 0 OID 0)
-- Dependencies: 206
-- Name: TABLE responsavel_unidade_organizacional; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE responsavel_unidade_organizacional IS 'Tabela que mantém informações as pessoas responsáveis pelas unidades organizacionais da organização';


--
-- TOC entry 207 (class 1259 OID 24661)
-- Dependencies: 7
-- Name: seq_acao_contingenciamento; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_acao_contingenciamento
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_acao_contingenciamento OWNER TO gestaoti;

--
-- TOC entry 2780 (class 0 OID 0)
-- Dependencies: 207
-- Name: seq_acao_contingenciamento; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_acao_contingenciamento', 1, false);


--
-- TOC entry 208 (class 1259 OID 24663)
-- Dependencies: 7
-- Name: seq_agendas_area_id; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_agendas_area_id
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_agendas_area_id OWNER TO gestaoti;

--
-- TOC entry 2781 (class 0 OID 0)
-- Dependencies: 208
-- Name: seq_agendas_area_id; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_agendas_area_id', 1, false);


--
-- TOC entry 209 (class 1259 OID 24665)
-- Dependencies: 7
-- Name: seq_agendas_entry_id; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_agendas_entry_id
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_agendas_entry_id OWNER TO gestaoti;

--
-- TOC entry 2782 (class 0 OID 0)
-- Dependencies: 209
-- Name: seq_agendas_entry_id; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_agendas_entry_id', 1, false);


--
-- TOC entry 210 (class 1259 OID 24667)
-- Dependencies: 7
-- Name: seq_agendas_room_id; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_agendas_room_id
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_agendas_room_id OWNER TO gestaoti;

--
-- TOC entry 2783 (class 0 OID 0)
-- Dependencies: 210
-- Name: seq_agendas_room_id; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_agendas_room_id', 1, false);


--
-- TOC entry 211 (class 1259 OID 24669)
-- Dependencies: 7
-- Name: seq_anexo_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_anexo_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_anexo_chamado OWNER TO gestaoti;

--
-- TOC entry 2784 (class 0 OID 0)
-- Dependencies: 211
-- Name: seq_anexo_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_anexo_chamado', 1, false);


--
-- TOC entry 212 (class 1259 OID 24671)
-- Dependencies: 7
-- Name: seq_anexo_rdm; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_anexo_rdm
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_anexo_rdm OWNER TO gestaoti;

--
-- TOC entry 2785 (class 0 OID 0)
-- Dependencies: 212
-- Name: seq_anexo_rdm; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_anexo_rdm', 1, false);


--
-- TOC entry 213 (class 1259 OID 24673)
-- Dependencies: 7
-- Name: seq_aprovacao_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_aprovacao_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_aprovacao_chamado OWNER TO gestaoti;

--
-- TOC entry 2786 (class 0 OID 0)
-- Dependencies: 213
-- Name: seq_aprovacao_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_aprovacao_chamado', 2, true);


--
-- TOC entry 214 (class 1259 OID 24675)
-- Dependencies: 7
-- Name: seq_aprovacao_chamado_departamento; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_aprovacao_chamado_departamento
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_aprovacao_chamado_departamento OWNER TO gestaoti;

--
-- TOC entry 2787 (class 0 OID 0)
-- Dependencies: 214
-- Name: seq_aprovacao_chamado_departamento; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_aprovacao_chamado_departamento', 1, false);


--
-- TOC entry 215 (class 1259 OID 24677)
-- Dependencies: 7
-- Name: seq_aprovacao_chamado_superior; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_aprovacao_chamado_superior
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_aprovacao_chamado_superior OWNER TO gestaoti;

--
-- TOC entry 2788 (class 0 OID 0)
-- Dependencies: 215
-- Name: seq_aprovacao_chamado_superior; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_aprovacao_chamado_superior', 2, true);


--
-- TOC entry 216 (class 1259 OID 24679)
-- Dependencies: 7
-- Name: seq_area_atuacao; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_area_atuacao
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_area_atuacao OWNER TO gestaoti;

--
-- TOC entry 2789 (class 0 OID 0)
-- Dependencies: 216
-- Name: seq_area_atuacao; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_area_atuacao', 1, false);


--
-- TOC entry 217 (class 1259 OID 24681)
-- Dependencies: 7
-- Name: seq_area_externa; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_area_externa
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_area_externa OWNER TO gestaoti;

--
-- TOC entry 2790 (class 0 OID 0)
-- Dependencies: 217
-- Name: seq_area_externa; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_area_externa', 304, true);


--
-- TOC entry 218 (class 1259 OID 24683)
-- Dependencies: 7
-- Name: seq_atendimento_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_atendimento_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_atendimento_chamado OWNER TO gestaoti;

--
-- TOC entry 2791 (class 0 OID 0)
-- Dependencies: 218
-- Name: seq_atendimento_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_atendimento_chamado', 1, false);


--
-- TOC entry 219 (class 1259 OID 24685)
-- Dependencies: 7
-- Name: seq_atividade_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_atividade_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_atividade_chamado OWNER TO gestaoti;

--
-- TOC entry 2792 (class 0 OID 0)
-- Dependencies: 219
-- Name: seq_atividade_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_atividade_chamado', 1, false);


--
-- TOC entry 220 (class 1259 OID 24687)
-- Dependencies: 7
-- Name: seq_atividade_rb_rdm; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_atividade_rb_rdm
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_atividade_rb_rdm OWNER TO gestaoti;

--
-- TOC entry 2793 (class 0 OID 0)
-- Dependencies: 220
-- Name: seq_atividade_rb_rdm; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_atividade_rb_rdm', 1, false);


--
-- TOC entry 221 (class 1259 OID 24689)
-- Dependencies: 7
-- Name: seq_atividade_rb_rdm_template; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_atividade_rb_rdm_template
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_atividade_rb_rdm_template OWNER TO gestaoti;

--
-- TOC entry 2794 (class 0 OID 0)
-- Dependencies: 221
-- Name: seq_atividade_rb_rdm_template; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_atividade_rb_rdm_template', 1, false);


--
-- TOC entry 222 (class 1259 OID 24691)
-- Dependencies: 7
-- Name: seq_atividade_rdm; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_atividade_rdm
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_atividade_rdm OWNER TO gestaoti;

--
-- TOC entry 2795 (class 0 OID 0)
-- Dependencies: 222
-- Name: seq_atividade_rdm; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_atividade_rdm', 1, false);


--
-- TOC entry 223 (class 1259 OID 24693)
-- Dependencies: 7
-- Name: seq_atividade_rdm_template; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_atividade_rdm_template
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_atividade_rdm_template OWNER TO gestaoti;

--
-- TOC entry 2796 (class 0 OID 0)
-- Dependencies: 223
-- Name: seq_atividade_rdm_template; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_atividade_rdm_template', 1, false);


--
-- TOC entry 224 (class 1259 OID 24695)
-- Dependencies: 7
-- Name: seq_atribuicao_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_atribuicao_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_atribuicao_chamado OWNER TO gestaoti;

--
-- TOC entry 2797 (class 0 OID 0)
-- Dependencies: 224
-- Name: seq_atribuicao_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_atribuicao_chamado', 9, true);


--
-- TOC entry 225 (class 1259 OID 24697)
-- Dependencies: 7
-- Name: seq_avaliacao_atendimento; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_avaliacao_atendimento
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_avaliacao_atendimento OWNER TO gestaoti;

--
-- TOC entry 2798 (class 0 OID 0)
-- Dependencies: 225
-- Name: seq_avaliacao_atendimento; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_avaliacao_atendimento', 1, false);


--
-- TOC entry 226 (class 1259 OID 24699)
-- Dependencies: 7
-- Name: seq_banco_de_dados; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_banco_de_dados
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_banco_de_dados OWNER TO gestaoti;

--
-- TOC entry 2799 (class 0 OID 0)
-- Dependencies: 226
-- Name: seq_banco_de_dados; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_banco_de_dados', 1, false);


--
-- TOC entry 227 (class 1259 OID 24701)
-- Dependencies: 7
-- Name: seq_central_atendimento; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_central_atendimento
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_central_atendimento OWNER TO gestaoti;

--
-- TOC entry 2800 (class 0 OID 0)
-- Dependencies: 227
-- Name: seq_central_atendimento; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_central_atendimento', 1, false);


--
-- TOC entry 228 (class 1259 OID 24703)
-- Dependencies: 7
-- Name: seq_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_chamado OWNER TO gestaoti;

--
-- TOC entry 2801 (class 0 OID 0)
-- Dependencies: 228
-- Name: seq_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_chamado', 4, true);


--
-- TOC entry 229 (class 1259 OID 24705)
-- Dependencies: 7
-- Name: seq_criticidade; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_criticidade
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_criticidade OWNER TO gestaoti;

--
-- TOC entry 2802 (class 0 OID 0)
-- Dependencies: 229
-- Name: seq_criticidade; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_criticidade', 1, false);


--
-- TOC entry 230 (class 1259 OID 24707)
-- Dependencies: 7
-- Name: seq_edificacao_infraero; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_edificacao_infraero
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_edificacao_infraero OWNER TO gestaoti;

--
-- TOC entry 2803 (class 0 OID 0)
-- Dependencies: 230
-- Name: seq_edificacao_infraero; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_edificacao_infraero', 1, false);


--
-- TOC entry 231 (class 1259 OID 24709)
-- Dependencies: 7
-- Name: seq_equipe_atribuicao; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_equipe_atribuicao
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_equipe_atribuicao OWNER TO gestaoti;

--
-- TOC entry 2804 (class 0 OID 0)
-- Dependencies: 231
-- Name: seq_equipe_atribuicao; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_equipe_atribuicao', 1, false);


--
-- TOC entry 232 (class 1259 OID 24711)
-- Dependencies: 7
-- Name: seq_equipe_ti; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_equipe_ti
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_equipe_ti OWNER TO gestaoti;

--
-- TOC entry 2805 (class 0 OID 0)
-- Dependencies: 232
-- Name: seq_equipe_ti; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_equipe_ti', 1, false);


--
-- TOC entry 233 (class 1259 OID 24713)
-- Dependencies: 7
-- Name: seq_etapa_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_etapa_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_etapa_chamado OWNER TO gestaoti;

--
-- TOC entry 2806 (class 0 OID 0)
-- Dependencies: 233
-- Name: seq_etapa_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_etapa_chamado', 1, false);


--
-- TOC entry 234 (class 1259 OID 24715)
-- Dependencies: 7
-- Name: seq_fase_item_configuracao; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_fase_item_configuracao
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_fase_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 2807 (class 0 OID 0)
-- Dependencies: 234
-- Name: seq_fase_item_configuracao; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_fase_item_configuracao', 1, false);


--
-- TOC entry 235 (class 1259 OID 24717)
-- Dependencies: 7
-- Name: seq_fase_projeto; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_fase_projeto
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_fase_projeto OWNER TO gestaoti;

--
-- TOC entry 2808 (class 0 OID 0)
-- Dependencies: 235
-- Name: seq_fase_projeto; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_fase_projeto', 1, false);


--
-- TOC entry 236 (class 1259 OID 24719)
-- Dependencies: 7
-- Name: seq_feriado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_feriado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_feriado OWNER TO gestaoti;

--
-- TOC entry 2809 (class 0 OID 0)
-- Dependencies: 236
-- Name: seq_feriado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_feriado', 64, true);


--
-- TOC entry 237 (class 1259 OID 24721)
-- Dependencies: 7
-- Name: seq_frequencia_manutencao; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_frequencia_manutencao
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_frequencia_manutencao OWNER TO gestaoti;

--
-- TOC entry 2810 (class 0 OID 0)
-- Dependencies: 237
-- Name: seq_frequencia_manutencao; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_frequencia_manutencao', 1, false);


--
-- TOC entry 238 (class 1259 OID 24723)
-- Dependencies: 7
-- Name: seq_historico_acesso_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_historico_acesso_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_historico_acesso_chamado OWNER TO gestaoti;

--
-- TOC entry 2811 (class 0 OID 0)
-- Dependencies: 238
-- Name: seq_historico_acesso_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_historico_acesso_chamado', 81, true);


--
-- TOC entry 239 (class 1259 OID 24725)
-- Dependencies: 7
-- Name: seq_historico_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_historico_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_historico_chamado OWNER TO gestaoti;

--
-- TOC entry 2812 (class 0 OID 0)
-- Dependencies: 239
-- Name: seq_historico_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_historico_chamado', 18, true);


--
-- TOC entry 240 (class 1259 OID 24727)
-- Dependencies: 7
-- Name: seq_informativo; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_informativo
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_informativo OWNER TO gestaoti;

--
-- TOC entry 2813 (class 0 OID 0)
-- Dependencies: 240
-- Name: seq_informativo; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_informativo', 1, false);


--
-- TOC entry 241 (class 1259 OID 24729)
-- Dependencies: 7
-- Name: seq_inoperancia_item_config; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_inoperancia_item_config
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_inoperancia_item_config OWNER TO gestaoti;

--
-- TOC entry 2814 (class 0 OID 0)
-- Dependencies: 241
-- Name: seq_inoperancia_item_config; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_inoperancia_item_config', 1, false);


--
-- TOC entry 242 (class 1259 OID 24731)
-- Dependencies: 7
-- Name: seq_item_configuracao; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_item_configuracao
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 2815 (class 0 OID 0)
-- Dependencies: 242
-- Name: seq_item_configuracao; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_item_configuracao', 1, false);


--
-- TOC entry 243 (class 1259 OID 24733)
-- Dependencies: 7
-- Name: seq_janela_mudanca; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_janela_mudanca
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_janela_mudanca OWNER TO gestaoti;

--
-- TOC entry 2816 (class 0 OID 0)
-- Dependencies: 243
-- Name: seq_janela_mudanca; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_janela_mudanca', 1, false);


--
-- TOC entry 244 (class 1259 OID 24735)
-- Dependencies: 7
-- Name: seq_linguagem_programacao; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_linguagem_programacao
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_linguagem_programacao OWNER TO gestaoti;

--
-- TOC entry 2817 (class 0 OID 0)
-- Dependencies: 244
-- Name: seq_linguagem_programacao; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_linguagem_programacao', 1, false);


--
-- TOC entry 245 (class 1259 OID 24737)
-- Dependencies: 7
-- Name: seq_localizacao_fisica; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_localizacao_fisica
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_localizacao_fisica OWNER TO gestaoti;

--
-- TOC entry 2818 (class 0 OID 0)
-- Dependencies: 245
-- Name: seq_localizacao_fisica; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_localizacao_fisica', 1, false);


--
-- TOC entry 246 (class 1259 OID 24739)
-- Dependencies: 7
-- Name: seq_marca_hardware; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_marca_hardware
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_marca_hardware OWNER TO gestaoti;

--
-- TOC entry 2819 (class 0 OID 0)
-- Dependencies: 246
-- Name: seq_marca_hardware; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_marca_hardware', 1, false);


--
-- TOC entry 247 (class 1259 OID 24741)
-- Dependencies: 7
-- Name: seq_menu_acesso; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_menu_acesso
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_menu_acesso OWNER TO gestaoti;

--
-- TOC entry 2820 (class 0 OID 0)
-- Dependencies: 247
-- Name: seq_menu_acesso; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_menu_acesso', 7, true);


--
-- TOC entry 248 (class 1259 OID 24743)
-- Dependencies: 7
-- Name: seq_motivo_cancelamento; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_motivo_cancelamento
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_motivo_cancelamento OWNER TO gestaoti;

--
-- TOC entry 2821 (class 0 OID 0)
-- Dependencies: 248
-- Name: seq_motivo_cancelamento; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_motivo_cancelamento', 1, false);


--
-- TOC entry 249 (class 1259 OID 24745)
-- Dependencies: 7
-- Name: seq_motivo_suspencao; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_motivo_suspencao
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_motivo_suspencao OWNER TO gestaoti;

--
-- TOC entry 2822 (class 0 OID 0)
-- Dependencies: 249
-- Name: seq_motivo_suspencao; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_motivo_suspencao', 1, false);


--
-- TOC entry 250 (class 1259 OID 24747)
-- Dependencies: 7
-- Name: seq_perfil_acesso; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_perfil_acesso
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_perfil_acesso OWNER TO gestaoti;

--
-- TOC entry 2823 (class 0 OID 0)
-- Dependencies: 250
-- Name: seq_perfil_acesso; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_perfil_acesso', 1, false);


--
-- TOC entry 251 (class 1259 OID 24749)
-- Dependencies: 7
-- Name: seq_perfil_recurso_ti; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_perfil_recurso_ti
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_perfil_recurso_ti OWNER TO gestaoti;

--
-- TOC entry 2824 (class 0 OID 0)
-- Dependencies: 251
-- Name: seq_perfil_recurso_ti; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_perfil_recurso_ti', 1, false);


--
-- TOC entry 252 (class 1259 OID 24751)
-- Dependencies: 7
-- Name: seq_pessoa; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_pessoa
    START WITH 2
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_pessoa OWNER TO gestaoti;

--
-- TOC entry 2825 (class 0 OID 0)
-- Dependencies: 252
-- Name: seq_pessoa; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_pessoa', 9, true);


--
-- TOC entry 253 (class 1259 OID 24753)
-- Dependencies: 7
-- Name: seq_prioridade; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_prioridade
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_prioridade OWNER TO gestaoti;

--
-- TOC entry 2826 (class 0 OID 0)
-- Dependencies: 253
-- Name: seq_prioridade; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_prioridade', 1, false);


--
-- TOC entry 254 (class 1259 OID 24755)
-- Dependencies: 7
-- Name: seq_prioridade_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_prioridade_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_prioridade_chamado OWNER TO gestaoti;

--
-- TOC entry 2827 (class 0 OID 0)
-- Dependencies: 254
-- Name: seq_prioridade_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_prioridade_chamado', 1, false);


--
-- TOC entry 255 (class 1259 OID 24757)
-- Dependencies: 7
-- Name: seq_rdm; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_rdm
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_rdm OWNER TO gestaoti;

--
-- TOC entry 2828 (class 0 OID 0)
-- Dependencies: 255
-- Name: seq_rdm; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_rdm', 1, false);


--
-- TOC entry 256 (class 1259 OID 24759)
-- Dependencies: 7
-- Name: seq_rdm_template; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_rdm_template
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_rdm_template OWNER TO gestaoti;

--
-- TOC entry 2829 (class 0 OID 0)
-- Dependencies: 256
-- Name: seq_rdm_template; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_rdm_template', 1, false);


--
-- TOC entry 257 (class 1259 OID 24761)
-- Dependencies: 7
-- Name: seq_relac_item_configuracao; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_relac_item_configuracao
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_relac_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 2830 (class 0 OID 0)
-- Dependencies: 257
-- Name: seq_relac_item_configuracao; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_relac_item_configuracao', 1, false);


--
-- TOC entry 258 (class 1259 OID 24763)
-- Dependencies: 7
-- Name: seq_servidor; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_servidor
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_servidor OWNER TO gestaoti;

--
-- TOC entry 2831 (class 0 OID 0)
-- Dependencies: 258
-- Name: seq_servidor; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_servidor', 1, false);


--
-- TOC entry 259 (class 1259 OID 24765)
-- Dependencies: 7
-- Name: seq_sistema_operacional; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_sistema_operacional
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_sistema_operacional OWNER TO gestaoti;

--
-- TOC entry 2832 (class 0 OID 0)
-- Dependencies: 259
-- Name: seq_sistema_operacional; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_sistema_operacional', 1, false);


--
-- TOC entry 260 (class 1259 OID 24767)
-- Dependencies: 7
-- Name: seq_situacao_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_situacao_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_situacao_chamado OWNER TO gestaoti;

--
-- TOC entry 2833 (class 0 OID 0)
-- Dependencies: 260
-- Name: seq_situacao_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_situacao_chamado', 1, false);


--
-- TOC entry 261 (class 1259 OID 24769)
-- Dependencies: 7
-- Name: seq_situacao_rdm; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_situacao_rdm
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_situacao_rdm OWNER TO gestaoti;

--
-- TOC entry 2834 (class 0 OID 0)
-- Dependencies: 261
-- Name: seq_situacao_rdm; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_situacao_rdm', 1, false);


--
-- TOC entry 262 (class 1259 OID 24771)
-- Dependencies: 7
-- Name: seq_status_software; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_status_software
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_status_software OWNER TO gestaoti;

--
-- TOC entry 2835 (class 0 OID 0)
-- Dependencies: 262
-- Name: seq_status_software; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_status_software', 1, false);


--
-- TOC entry 263 (class 1259 OID 24773)
-- Dependencies: 7
-- Name: seq_subtipo_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_subtipo_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_subtipo_chamado OWNER TO gestaoti;

--
-- TOC entry 2836 (class 0 OID 0)
-- Dependencies: 263
-- Name: seq_subtipo_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_subtipo_chamado', 1, false);


--
-- TOC entry 264 (class 1259 OID 24775)
-- Dependencies: 7
-- Name: seq_time_sheet; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_time_sheet
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_time_sheet OWNER TO gestaoti;

--
-- TOC entry 2837 (class 0 OID 0)
-- Dependencies: 264
-- Name: seq_time_sheet; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_time_sheet', 19, true);


--
-- TOC entry 265 (class 1259 OID 24777)
-- Dependencies: 7
-- Name: seq_tipo_chamado; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_tipo_chamado
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_tipo_chamado OWNER TO gestaoti;

--
-- TOC entry 2838 (class 0 OID 0)
-- Dependencies: 265
-- Name: seq_tipo_chamado; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_tipo_chamado', 1, false);


--
-- TOC entry 266 (class 1259 OID 24779)
-- Dependencies: 7
-- Name: seq_tipo_disponibilidade; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_tipo_disponibilidade
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_tipo_disponibilidade OWNER TO gestaoti;

--
-- TOC entry 2839 (class 0 OID 0)
-- Dependencies: 266
-- Name: seq_tipo_disponibilidade; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_tipo_disponibilidade', 1, false);


--
-- TOC entry 267 (class 1259 OID 24781)
-- Dependencies: 7
-- Name: seq_tipo_funcao_administrativa; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_tipo_funcao_administrativa
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_tipo_funcao_administrativa OWNER TO gestaoti;

--
-- TOC entry 2840 (class 0 OID 0)
-- Dependencies: 267
-- Name: seq_tipo_funcao_administrativa; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_tipo_funcao_administrativa', 7, true);


--
-- TOC entry 268 (class 1259 OID 24783)
-- Dependencies: 7
-- Name: seq_tipo_item_configuracao; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_tipo_item_configuracao
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_tipo_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 2841 (class 0 OID 0)
-- Dependencies: 268
-- Name: seq_tipo_item_configuracao; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_tipo_item_configuracao', 1, false);


--
-- TOC entry 269 (class 1259 OID 24785)
-- Dependencies: 7
-- Name: seq_tipo_ocorrencia; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_tipo_ocorrencia
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_tipo_ocorrencia OWNER TO gestaoti;

--
-- TOC entry 2842 (class 0 OID 0)
-- Dependencies: 269
-- Name: seq_tipo_ocorrencia; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_tipo_ocorrencia', 1, false);


--
-- TOC entry 270 (class 1259 OID 24787)
-- Dependencies: 7
-- Name: seq_tipo_relac_item_config; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_tipo_relac_item_config
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_tipo_relac_item_config OWNER TO gestaoti;

--
-- TOC entry 2843 (class 0 OID 0)
-- Dependencies: 270
-- Name: seq_tipo_relac_item_config; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_tipo_relac_item_config', 1, false);


--
-- TOC entry 271 (class 1259 OID 24789)
-- Dependencies: 7
-- Name: seq_tipo_software; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_tipo_software
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_tipo_software OWNER TO gestaoti;

--
-- TOC entry 2844 (class 0 OID 0)
-- Dependencies: 271
-- Name: seq_tipo_software; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_tipo_software', 1, false);


--
-- TOC entry 272 (class 1259 OID 24791)
-- Dependencies: 7
-- Name: seq_unidade_medida_software; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_unidade_medida_software
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_unidade_medida_software OWNER TO gestaoti;

--
-- TOC entry 2845 (class 0 OID 0)
-- Dependencies: 272
-- Name: seq_unidade_medida_software; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_unidade_medida_software', 1, false);


--
-- TOC entry 273 (class 1259 OID 24793)
-- Dependencies: 7
-- Name: seq_unidade_organizacional; Type: SEQUENCE; Schema: gestaoti; Owner: gestaoti
--

CREATE SEQUENCE seq_unidade_organizacional
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE gestaoti.seq_unidade_organizacional OWNER TO gestaoti;

--
-- TOC entry 2846 (class 0 OID 0)
-- Dependencies: 273
-- Name: seq_unidade_organizacional; Type: SEQUENCE SET; Schema: gestaoti; Owner: gestaoti
--

SELECT pg_catalog.setval('seq_unidade_organizacional', 6, true);


--
-- TOC entry 274 (class 1259 OID 24795)
-- Dependencies: 7
-- Name: servidor; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE servidor (
    seq_servidor numeric(9,0) NOT NULL,
    seq_sistema_operacional numeric(9,0),
    seq_marca_hardware numeric(9,0),
    num_patrimonio character varying(15),
    num_ip character varying(15) NOT NULL,
    nom_servidor character varying(60) NOT NULL,
    nom_modelo character varying(60),
    dsc_servidor character varying(500),
    dsc_localizacao character varying(500),
    dsc_processador character varying(500),
    txt_observacao text,
    dat_criacao date,
    dat_alteracao date
);


ALTER TABLE gestaoti.servidor OWNER TO gestaoti;

--
-- TOC entry 275 (class 1259 OID 24801)
-- Dependencies: 7
-- Name: sistema_operacional; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE sistema_operacional (
    seq_sistema_operacional numeric(9,0) NOT NULL,
    nom_sistema_operacional character varying(60) NOT NULL
);


ALTER TABLE gestaoti.sistema_operacional OWNER TO gestaoti;

--
-- TOC entry 276 (class 1259 OID 24804)
-- Dependencies: 7
-- Name: situacao_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE situacao_chamado (
    seq_situacao_chamado numeric(2,0) NOT NULL,
    dsc_situacao_chamado character varying(60) NOT NULL
);


ALTER TABLE gestaoti.situacao_chamado OWNER TO gestaoti;

--
-- TOC entry 2847 (class 0 OID 0)
-- Dependencies: 276
-- Name: TABLE situacao_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE situacao_chamado IS 'Tabela que mantem informações sobre as situações de chamados ';


--
-- TOC entry 2848 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN situacao_chamado.seq_situacao_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN situacao_chamado.seq_situacao_chamado IS 'Campo autonumérico sequencial identificador de cada situação.';


--
-- TOC entry 2849 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN situacao_chamado.dsc_situacao_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN situacao_chamado.dsc_situacao_chamado IS 'Nome da situação';


--
-- TOC entry 277 (class 1259 OID 24807)
-- Dependencies: 7
-- Name: situacao_rdm; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE situacao_rdm (
    seq_rdm numeric(10,0) NOT NULL,
    situacao numeric(2,0) NOT NULL,
    data_hora timestamp without time zone NOT NULL,
    observacao character(500),
    seq_situacao_rdm numeric(10,0) NOT NULL,
    num_matricula_recurso numeric(22,0)
);


ALTER TABLE gestaoti.situacao_rdm OWNER TO gestaoti;

--
-- TOC entry 278 (class 1259 OID 24813)
-- Dependencies: 7
-- Name: software_banco_de_dados; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE software_banco_de_dados (
    seq_item_configuracao numeric(9,0) NOT NULL,
    seq_banco_de_dados numeric(9,0) NOT NULL
);


ALTER TABLE gestaoti.software_banco_de_dados OWNER TO gestaoti;

--
-- TOC entry 279 (class 1259 OID 24816)
-- Dependencies: 7
-- Name: software_linguagem_programacao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE software_linguagem_programacao (
    seq_item_configuracao numeric(9,0) NOT NULL,
    seq_linguagem_programacao numeric(9,0) NOT NULL
);


ALTER TABLE gestaoti.software_linguagem_programacao OWNER TO gestaoti;

--
-- TOC entry 280 (class 1259 OID 24819)
-- Dependencies: 7
-- Name: status_software; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE status_software (
    seq_status_software numeric(9,0) NOT NULL,
    nom_status_software character varying(60) NOT NULL
);


ALTER TABLE gestaoti.status_software OWNER TO gestaoti;

--
-- TOC entry 281 (class 1259 OID 24822)
-- Dependencies: 2207 7
-- Name: subtipo_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE subtipo_chamado (
    seq_subtipo_chamado numeric(4,0) NOT NULL,
    seq_tipo_chamado numeric(2,0) NOT NULL,
    dsc_subtipo_chamado character varying(60) NOT NULL,
    flg_atendimento_externo character(1) NOT NULL,
    CONSTRAINT ckc_flg_atendimento_e_subtipo_ CHECK ((flg_atendimento_externo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE gestaoti.subtipo_chamado OWNER TO gestaoti;

--
-- TOC entry 2850 (class 0 OID 0)
-- Dependencies: 281
-- Name: TABLE subtipo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE subtipo_chamado IS 'Tabela que mantém informações sobre os subtipos de chamados possíveis.';


--
-- TOC entry 2851 (class 0 OID 0)
-- Dependencies: 281
-- Name: COLUMN subtipo_chamado.seq_subtipo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN subtipo_chamado.seq_subtipo_chamado IS 'Campo autonumérico sequencial identificador de cada subtipo de chamados.';


--
-- TOC entry 2852 (class 0 OID 0)
-- Dependencies: 281
-- Name: COLUMN subtipo_chamado.seq_tipo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN subtipo_chamado.seq_tipo_chamado IS 'Campo autonumérico sequencial identificador de cada tipo de chamados.';


--
-- TOC entry 2853 (class 0 OID 0)
-- Dependencies: 281
-- Name: COLUMN subtipo_chamado.dsc_subtipo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN subtipo_chamado.dsc_subtipo_chamado IS 'Nome do subtipo de chamados';


--
-- TOC entry 2854 (class 0 OID 0)
-- Dependencies: 281
-- Name: COLUMN subtipo_chamado.flg_atendimento_externo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN subtipo_chamado.flg_atendimento_externo IS 'Campo que identifica se os chamados desta categoria podem ser disponibilizados aos clientes em geral ou se são exclusívos para atendimentos internos da TI.';


--
-- TOC entry 282 (class 1259 OID 24826)
-- Dependencies: 7
-- Name: time_sheet; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE time_sheet (
    seq_time_sheet numeric(22,0) NOT NULL,
    seq_chamado numeric(10,0) NOT NULL,
    num_matricula numeric(9,0) NOT NULL,
    dth_inicio timestamp without time zone NOT NULL,
    dth_fim timestamp without time zone
);


ALTER TABLE gestaoti.time_sheet OWNER TO gestaoti;

--
-- TOC entry 2855 (class 0 OID 0)
-- Dependencies: 282
-- Name: TABLE time_sheet; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE time_sheet IS 'Tabela que mantém informações sobre as horas efetivas de trabalho dos colaboradores das áreas de TI da INFRAERO.';


--
-- TOC entry 2856 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN time_sheet.seq_time_sheet; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN time_sheet.seq_time_sheet IS 'Campo autonumérico sequencial identificador de cada registro.';


--
-- TOC entry 2857 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN time_sheet.seq_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN time_sheet.seq_chamado IS 'Campo autonumérico sequencial identificador de cada chamado.';


--
-- TOC entry 2858 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN time_sheet.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN time_sheet.num_matricula IS 'Numero da matrícula do colaborador.';


--
-- TOC entry 2859 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN time_sheet.dth_inicio; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN time_sheet.dth_inicio IS 'Data de hora de início das atividades';


--
-- TOC entry 2860 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN time_sheet.dth_fim; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN time_sheet.dth_fim IS 'Data e hora final das atividades';


--
-- TOC entry 283 (class 1259 OID 24829)
-- Dependencies: 2208 7
-- Name: tipo_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE tipo_chamado (
    seq_tipo_chamado numeric(2,0) NOT NULL,
    dsc_tipo_chamado character varying(60) NOT NULL,
    flg_atendimento_externo character(1) NOT NULL,
    seq_central_atendimento numeric(9,0),
    flg_utilizado_sla character(1),
    CONSTRAINT ckc_flg_atendimento_e_tipo_cha CHECK ((flg_atendimento_externo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE gestaoti.tipo_chamado OWNER TO gestaoti;

--
-- TOC entry 2861 (class 0 OID 0)
-- Dependencies: 283
-- Name: TABLE tipo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE tipo_chamado IS 'Tabela que mantém informações sobre os tipos de chamados possíveis.';


--
-- TOC entry 2862 (class 0 OID 0)
-- Dependencies: 283
-- Name: COLUMN tipo_chamado.seq_tipo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN tipo_chamado.seq_tipo_chamado IS 'Campo autonumérico sequencial identificador de cada tipo de chamados.';


--
-- TOC entry 2863 (class 0 OID 0)
-- Dependencies: 283
-- Name: COLUMN tipo_chamado.dsc_tipo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN tipo_chamado.dsc_tipo_chamado IS 'Nome do tipo de chamado.';


--
-- TOC entry 2864 (class 0 OID 0)
-- Dependencies: 283
-- Name: COLUMN tipo_chamado.flg_atendimento_externo; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN tipo_chamado.flg_atendimento_externo IS 'Campo que identifica se os chamados desta categoria podem ser disponibilizados aos clientes em geral ou se são exclusívos para atendimentos internos da TI.';


--
-- TOC entry 284 (class 1259 OID 24833)
-- Dependencies: 7
-- Name: tipo_disponibilidade; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE tipo_disponibilidade (
    seq_tipo_disponibilidade numeric(9,0) NOT NULL,
    nom_tipo_disponibilidade character varying(60) NOT NULL
);


ALTER TABLE gestaoti.tipo_disponibilidade OWNER TO gestaoti;

--
-- TOC entry 285 (class 1259 OID 24836)
-- Dependencies: 7
-- Name: tipo_funcao_administrativa; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE tipo_funcao_administrativa (
    seq_tipo_funcao_administrativa numeric(9,0) NOT NULL,
    nom_tipo_funcao_administrativa character varying(60) NOT NULL
);


ALTER TABLE gestaoti.tipo_funcao_administrativa OWNER TO gestaoti;

--
-- TOC entry 286 (class 1259 OID 24839)
-- Dependencies: 7
-- Name: tipo_item_configuracao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE tipo_item_configuracao (
    seq_tipo_item_configuracao numeric(9,0) NOT NULL,
    nom_tipo_item_configuracao character varying(60) NOT NULL
);


ALTER TABLE gestaoti.tipo_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 287 (class 1259 OID 24842)
-- Dependencies: 7
-- Name: tipo_ocorrencia; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE tipo_ocorrencia (
    seq_tipo_ocorrencia numeric(9,0) NOT NULL,
    nom_tipo_ocorrencia character varying(60) NOT NULL
);


ALTER TABLE gestaoti.tipo_ocorrencia OWNER TO gestaoti;

--
-- TOC entry 288 (class 1259 OID 24845)
-- Dependencies: 7
-- Name: tipo_relac_item_configuracao; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE tipo_relac_item_configuracao (
    seq_tipo_relac_item_config numeric(9,0) NOT NULL,
    nom_tipo_relac_item_config character varying(60) NOT NULL
);


ALTER TABLE gestaoti.tipo_relac_item_configuracao OWNER TO gestaoti;

--
-- TOC entry 289 (class 1259 OID 24848)
-- Dependencies: 7
-- Name: tipo_software; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE tipo_software (
    seq_tipo_software numeric(9,0) NOT NULL,
    nom_tipo_software character varying(60) NOT NULL
);


ALTER TABLE gestaoti.tipo_software OWNER TO gestaoti;

--
-- TOC entry 290 (class 1259 OID 24851)
-- Dependencies: 7
-- Name: unidade_medida_software; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE unidade_medida_software (
    seq_unidade_medida_software numeric(9,0) NOT NULL,
    nom_unidade_medida_software character varying(60) NOT NULL
);


ALTER TABLE gestaoti.unidade_medida_software OWNER TO gestaoti;

--
-- TOC entry 291 (class 1259 OID 24854)
-- Dependencies: 2209 2210 2211 7
-- Name: unidade_organizacional; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE unidade_organizacional (
    seq_unidade_organizacional numeric(22,0) DEFAULT NULL::numeric NOT NULL,
    nom_unidade_organizacional character varying(300) DEFAULT NULL::character varying NOT NULL,
    seq_unidade_organizacional_pai numeric(22,0) DEFAULT NULL::numeric,
    sgl_unidade_organizacional character varying(20) NOT NULL
);


ALTER TABLE gestaoti.unidade_organizacional OWNER TO gestaoti;

--
-- TOC entry 2865 (class 0 OID 0)
-- Dependencies: 291
-- Name: TABLE unidade_organizacional; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE unidade_organizacional IS 'Tabela que mantém informações sobre as unidades organizacionais da organização';


--
-- TOC entry 292 (class 1259 OID 24860)
-- Dependencies: 7
-- Name: vinculo_chamado; Type: TABLE; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE TABLE vinculo_chamado (
    seq_chamado_master numeric(10,0) NOT NULL,
    seq_chamado_filho numeric(10,0) NOT NULL,
    num_matricula numeric(9,0),
    dth_vinculacao timestamp without time zone NOT NULL
);


ALTER TABLE gestaoti.vinculo_chamado OWNER TO gestaoti;

--
-- TOC entry 2866 (class 0 OID 0)
-- Dependencies: 292
-- Name: TABLE vinculo_chamado; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON TABLE vinculo_chamado IS 'Tabela que mantém informações sobre os vínculos existentes entre os chamados. Criado para a implementação da disciplina de gestão de problemas. Aplicável aos incidentes de mesma natureza.';


--
-- TOC entry 2867 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN vinculo_chamado.seq_chamado_master; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN vinculo_chamado.seq_chamado_master IS 'Código do chamado máster.';


--
-- TOC entry 2868 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN vinculo_chamado.seq_chamado_filho; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN vinculo_chamado.seq_chamado_filho IS 'Código do chamado subordinado ao máster.';


--
-- TOC entry 2869 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN vinculo_chamado.num_matricula; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN vinculo_chamado.num_matricula IS 'Número da matrícula do colaborador que realizou o vínculo.';


--
-- TOC entry 2870 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN vinculo_chamado.dth_vinculacao; Type: COMMENT; Schema: gestaoti; Owner: gestaoti
--

COMMENT ON COLUMN vinculo_chamado.dth_vinculacao IS 'Data e hora da realização do vínculo.';


--
-- TOC entry 293 (class 1259 OID 24863)
-- Dependencies: 1996 7
-- Name: viw_age_empregados; Type: VIEW; Schema: gestaoti; Owner: gestaoti
--

CREATE VIEW viw_age_empregados AS
    SELECT a.seq_pessoa AS num_matricula_recurso, a.nom_login_rede, NULL::unknown AS flg_tipo_colaborador, a.nome, a.nome_abreviado, a.nome_guerra, a.des_email, a.num_ddd, a.num_telefone, a.num_voip, a.des_status AS des_atatus, b.sgl_unidade_organizacional AS dep_sigla, c.sgl_unidade_organizacional AS uor_sigla, a.des_senha, a.seq_tipo_funcao_administrativa, a.seq_unidade_organizacional FROM pessoa a, (unidade_organizacional b LEFT JOIN unidade_organizacional c ON ((b.seq_unidade_organizacional_pai = c.seq_unidade_organizacional))) WHERE (a.seq_unidade_organizacional = b.seq_unidade_organizacional);


ALTER TABLE gestaoti.viw_age_empregados OWNER TO gestaoti;

--
-- TOC entry 294 (class 1259 OID 24868)
-- Dependencies: 1997 7
-- Name: viw_colaborador; Type: VIEW; Schema: gestaoti; Owner: gestaoti
--

CREATE VIEW viw_colaborador AS
    SELECT pessoa.nome AS nom_colaborador, pessoa.seq_pessoa AS num_matricula_colaborador, pessoa.des_email AS dsc_email, NULL::unknown AS cod_dependencia_fis, pessoa.nom_login_rede AS nom_usuario_rede FROM pessoa;


ALTER TABLE gestaoti.viw_colaborador OWNER TO gestaoti;

--
-- TOC entry 295 (class 1259 OID 24872)
-- Dependencies: 1998 7
-- Name: viw_diretoria; Type: VIEW; Schema: gestaoti; Owner: gestaoti
--

CREATE VIEW viw_diretoria AS
    SELECT unidade_organizacional.seq_unidade_organizacional AS cd_dependencia, unidade_organizacional.sgl_unidade_organizacional AS sg_dependencia, unidade_organizacional.nom_unidade_organizacional AS no_dependencia, NULL::unknown AS dep_cd_dependencia FROM unidade_organizacional;


ALTER TABLE gestaoti.viw_diretoria OWNER TO gestaoti;

--
-- TOC entry 2529 (class 0 OID 24383)
-- Dependencies: 141
-- Data for Name: acao_contingenciamento; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY acao_contingenciamento (seq_acao_contingenciamento, nom_acao_contingenciamento) FROM stdin;
1	Intervenção manual
2	Atualização direta em banco
3	Restart de serviço
4	Reboot de servidor
5	Outros
\.


--
-- TOC entry 2530 (class 0 OID 24386)
-- Dependencies: 142
-- Data for Name: agendas_entry; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY agendas_entry (seq_agendas_entry_id, start_time, end_time, entry_type, repeat_id, room_id, "timestamp", create_by, name, type, description, status, num_pessoas) FROM stdin;
\.


--
-- TOC entry 2531 (class 0 OID 24402)
-- Dependencies: 143
-- Data for Name: anexo_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY anexo_chamado (seq_anexo_chamado, seq_chamado, nom_arquivo_sistema, nom_arquivo_original, dth_anexo, num_matricula) FROM stdin;
\.


--
-- TOC entry 2532 (class 0 OID 24405)
-- Dependencies: 144
-- Data for Name: anexo_rdm; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY anexo_rdm (seq_anexo_rdm, seq_rdm, nom_arquivo_sistema, nom_arquivo_original, dth_anexo, num_matricula) FROM stdin;
\.


--
-- TOC entry 2533 (class 0 OID 24408)
-- Dependencies: 145
-- Data for Name: aprovacao_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY aprovacao_chamado (seq_aprovacao_chamado, seq_chamado, num_matricula, dth_aprovacao, dth_prevista, txt_justificativa) FROM stdin;
1	2	1	2011-07-10 20:01:03	2011-08-08 18:00:00-03	\N
2	3	1	2011-08-11 17:25:47	2011-08-12 12:00:00-03	\N
\.


--
-- TOC entry 2534 (class 0 OID 24414)
-- Dependencies: 146
-- Data for Name: aprovacao_chamado_departamento; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY aprovacao_chamado_departamento (seq_aprovacao_chamado_departamento, seq_chamado, id_unidade, id_coordenacao) FROM stdin;
\.


--
-- TOC entry 2535 (class 0 OID 24417)
-- Dependencies: 147
-- Data for Name: aprovacao_chamado_superior; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY aprovacao_chamado_superior (seq_aprovacao_chamado_superior, seq_chamado, num_matricula) FROM stdin;
1	3	1
2	3	1
\.


--
-- TOC entry 2536 (class 0 OID 24420)
-- Dependencies: 148
-- Data for Name: area_atuacao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY area_atuacao (seq_area_atuacao, nom_area_atuacao) FROM stdin;
\.


--
-- TOC entry 2537 (class 0 OID 24423)
-- Dependencies: 149
-- Data for Name: area_envolvida; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY area_envolvida (seq_item_configuracao, cod_uor, num_matricula_gestor) FROM stdin;
\.


--
-- TOC entry 2538 (class 0 OID 24426)
-- Dependencies: 150
-- Data for Name: area_externa; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY area_externa (seq_area_externa, nom_area_externa, flg_sisp) FROM stdin;
297	Tribunal Superior do Trabalho	N
303	Instituto Federal de Educação, Ciência e Tecnologia do Sudeste de Minas Gerais	S
299	Banco Nacional de Desenvolvimento Economico e Social - BNDES	N
300	Departamento de Controle do Espaço Aéreo - DECEA	N
301	Tribunal de Justiça do Estado do Rio de Janeiro	N
97	Centro Federal de Educação Tecnológica do Ceará	S
98	Centro Federal de Educação Tecnológica do Espírito Santo	S
99	Centro Federal de Educação Tecnológica do Maranhão	S
100	Centro Federal de Educação Tecnológica do Pará	S
101	Universidade Federal Tecnológica do Paraná	S
102	Centro Federal de Educação Tecnológica do Piauí	S
103	Centro Federal de Educação Tecnológica do Rio Grande do Norte	S
104	Escola Agrotécnica Federal Antônio José Teixeira - Guanambi - BA	S
105	Escola Agrotécnica Federal de Alegre	S
106	Escola Agrotécnica Federal de Alegrete	S
107	Escola Agrotécnica Federal de Araguatins	S
108	Escola Agrotécnica Federal de Barbacena	S
109	Escola Agrotécnica Federal de Barreiros	S
110	Escola Agrotécnica Federal de Belo Jardim	S
111	Escola Agrotécnica Federal de Cáceres	S
112	Escola Agrotécnica Federal de Castanhal	S
113	Escola Agrotécnica Federal de Catu	S
114	Escola Agrotécnica Federal de Ceres	S
115	Escola Agrotécnica Federal de Codó	S
116	Escola Agrotécnica Federal de Colatina	S
117	Escola Agrotécnica Federal de Colorado do Oeste	S
118	Escola Agrotécnica Federal de Concórdia	S
119	Escola Agrotécnica Federal de Crato	S
120	Escola Agrotécnica Federal de Iguatu	S
121	Escola Agrotécnica Federal de Inconfidentes Visconde de Mauá	S
122	Escola Agrotécnica Federal de Machado	S
123	Escola Agrotécnica Federal de Manaus	S
124	Escola Agrotécnica Federal de Muzambinho	S
125	Escola Agrotécnica Federal de Rio do Sul	S
126	Escola Agrotécnica Federal de Salinas Clemente Medrado	S
127	Escola Agrotécnica Federal de Santa Inês	S
128	Escola Agrotécnica Federal de Santa Teresa	S
129	Escola Agrotécnica Federal de São Cristóvão	S
130	Escola Agrotécnica Federal de São Gabriel da Cachoeira	S
131	Escola Agrotécnica Federal de São João Evangelista Nelson de Senna	S
132	Escola Agrotécnica Federal de São Luís	S
133	Escola Agrotécnica Federal de Satuba	S
134	Escola Agrotécnica Federal de Senhor do Bonfim	S
135	Escola Agrotécnica Federal de Sertão	S
136	Escola Agrotécnica Federal de Sombrio	S
137	Escola Agrotécnica Federal de Sousa	S
138	Escola Agrotécnica Federal de Uberlândia	S
139	Escola Agrotécnica Federal de Vitória de Santo Antão João Cleófas	S
140	Escola Técnica Federal de Palmas - TO	S
141	Fundação Universidade de Brasília	S
142	Fundação Universidade do Amazonas	S
143	Fundação Universidade Federal do Rio Grande	S
144	Fundação Universidade Federal de Mato Grosso	S
145	Fundação Universidade Federal de Mato Grosso do Sul	S
146	Fundação Universidade Federal de Ouro Preto	S
147	Fundação Universidade Federal de Pelotas	S
148	Fundação Universidade Federal de Rondônia	S
149	Fundação Universidade Federal de Roraima	S
150	Fundação Universidade Federal de São Carlos	S
151	Fundação Universidade Federal de São João Del Rei	S
152	Fundação Universidade Federal de Sergipe	S
153	Fundação Universidade Federal de Viçosa	S
154	Fundação Universidade Federal do Acre	S
155	Fundação Universidade Federal do Amapá	S
156	Fundação Universidade Federal do Maranhão	S
157	Fundação Universidade Federal do Piauí	S
158	Fundação Universidade Federal do Tocantins	S
159	Fundação Universidade Federal do Vale do São Francisco	S
160	Ministério das Comunicações - MC	S
161	Agência Nacional de Telecomunicações - ANATEL	S
162	Ministério do Desenvolvimento Agrário - MDA	S
163	Instituto Nacional de Colonização e Reforma Agrária - INCRA	S
164	Ministério do Desenvolvimento Social e Combate à Fome - MDS	S
165	Ministério do Desenvolvimento, Indústria e Comércio Exterior - MDIC	S
166	Fundo Nacional de Desenvolvimento - FND	S
167	Instituto Nacional da Propriedade Industrial - INPI	S
168	Instituto Nacional de Metrologia, Normalização e Qualidade Industrial - INMETRO	S
169	Superintendência da Zona Franca de Manaus - SUFRAMA	S
170	Ministério do Esporte - ME	S
171	Ministério da Fazenda - MF	S
172	Banco Central do Brasil - BCB	S
173	Comissão de Valores Mobiliários 	S
174	Superintendência de Seguros Privados	S
175	Ministério da Integração Nacional - MI	S
176	Agência de Desenvolvimento da Amazônia - ADA	S
177	Agência de Desenvolvimento do Nordeste - ADENE	S
178	Departamento Nacional de Obras Contra as Secas - DNOCS	S
179	Ministério da Justiça - MJ	S
180	Conselho Administrativo de Defesa Econômica - CADE	S
181	Fundação Nacional do Índio - FUNAI	S
182	Ministério de Minas e Energia - MME	S
183	Departamento Nacional de Produção Mineral - DNPM	S
184	Agência Nacional do Petróleo - ANP	S
185	Agência Nacional de Energia Elétrica - ANEEL	S
186	Ministério do Meio Ambiente - MMA	S
187	Agência Nacional de Águas - ANA	S
188	Instituto Brasileiro do Meio Ambiente e dos Recursos Naturais Renováveis - IBAMA	S
189	Instituto de Pesquisas Jardim Botânico do Rio de Janeiro - JBRJ	S
191	Fundação Escola Nacional de Administração Pública - ENAP 	S
192	Fundação Instituto de Pesquisa Econômica Aplicada - IPEA 	S
193	Fundação Instituto Brasileiro de Geografia e Estatística - IBGE	S
194	Ministério da Previdência Social - MPS	S
195	Instituto Nacional do Seguro Social - INSS	S
196	Ministério da Saúde - MS	S
197	Agência Nacional de Vigilância Sanitária - ANVISA 	S
198	Agência Nacional de Saúde Suplementar - ANS	S
199	Fundação Nacional de Saúde Fundação Oswaldo Cruz	S
200	Ministério das Relações Exteriores - MRE	S
201	Fundação Alexandre de Gusmão - FUNAG	S
202	Ministério do Trabalho e Emprego - MTE	S
203	Fundação Jorge Duprat Figueiredo, de Segurança e Medicina do Trabalho - FUNDACENTRO	S
204	Ministério do Turismo - MTur	S
205	Instituto Brasileiro de Turismo - EMBRATUR	S
206	Ministério dos Transportes - MT	S
207	Departamento nacional de Infraestrutura de Transportes - DNIT	S
208	Ministério da Defesa - MD	S
209	Comando da Aeronáutica	S
210	Comando da Marinha	S
211	Comando do Exército	S
212	Particular	N
213	Universidade Federal do Pampa	S
214	Instituto Federal de Brasília	S
215	Universidade Federal do ABC	S
216	Departamento de Polícia Rodoviária Federal - DPRF	S
217	Instituto Brasileiro de Museus - IBRAM	S
218	Instituto Federal de Minas Gerais	S
219	Empresa Brasileira de Pesquisa Agropecuária - EMBRAPA	N
220	Tribunal de Justiça - RJ	N
221	Receita Federal	S
222	Universidade de Brasília - UNB	S
223	Departamento de Polícia Federal - DPF	S
224	ProcerGS	N
225	Câmara dos Deputados	N
226	Tribunal de Justiça - BA	N
227	Empresa Privada	N
228	Empresa de Trens Urbanos de Porto Alegre - TRENSURB	N
229	Coordenação de Aperfeiçoamento de Pessoal de Nível Superior - CAPES	S
230	Tribunal Regional do Trabalho - TRT-SP	N
231	Tribunal Superior Eleitoral	N
232	Governo do Estado do Rio Grande do Sul	N
233	Agência Brasileira de Inteligência - ABIN	S
234	Governo do Estado do Espírito Santo - Subsecretaria de Gestão e RH	N
235	Infraero	N
236	Prefeitura Municipal de Bujari - AC	N
237	Agência Nacional de Aviação Civil - ANAC	S
238	Centro de Gerenciamento de Navegação Aérea - CGNA	S
239	Agência Estadual de Tecnologia da Informação - ATI - PE	N
240	Governo do Estado de Alagoas - ITEC	N
241	Universidade Federal dos Vales do Jequitinhonha e Mucuri	S
242	Fundação Nacional de Saúde - FUNASA	S
243	Procuradoria da República no Pará - PRPA	N
244	Tribunal Regional Eleitoral da Bahia - TRE-BA	N
245	Proderj	N
246	Tribunal de Contas da União - TCU	N
247	Instituto de Tecnologia em Informática e Informação do Estado de Alagoas - ITEC-AL	N
248	Fundação Oswaldo Cruz - FIOCRUZ	S
249	Universidade Tecnológica Federal do Paraná - UTFPR	S
250	Serpro	N
251	Hospital das Forças Armadas	S
252	DATASUS	S
253	Prefeitura Municipal de Embu	N
254	Instituto Chico Mendes de Conservação da Biodiversidade - ICMBio	S
255	Empresa Municipal de Informática - IplanRio	N
256	Universidade Federal de Pelotas	S
257	Universidade Federal do Mato Grosso do Sul	S
258	Instituto Brasileiro de Geografia e Estátistica - IBGE	S
259	Correios	N
260	Banco do Brasil - BB	N
261	Universidade de São Paulo - USP	N
262	Agência Nacional de Transportes Aquaviários - ANTAQ	S
263	Centrais Elétricas do Norte do Brasil S/A - Eletronorte	N
264	Instituto Federal de Educação, Ciência e Tecnologia de Mato Grosso  - IFTM	S
265	Instituto Nacional de Meteorologia - INMET 	S
266	Prefeitura Municipal de Sousa - PB	S
267	Instituto Federal de Alagoas	N
268	Superintendência Nacional de Previdência Complementar - PREVIC	S
269	Defensoria Pública da União - DPU	S
270	Instituto Nacional de Pesquisas da Amazônia - INPA	S
271	Universidade Federal do Oeste do Pará - UFOPA	S
272	Agência Nacional de Transportes Terreestres - ANTT	S
273	Escola Nacional de Administração Pública - ENAP	S
274	Escola Estadual Dona Alice Mendonça - São Francisco - MG	S
275	Universidade Federal do Mato Grosso	N
276	Instituto de Engenharia Nuclear - IEN	S
277	Instituto Federal do Norte de Minas Gerais - IFNMG	S
278	Secretaria de Administração de Pernambuco - SAD	S
279	Câmara de Registro - SP	N
280	Prefeitura Municipal do Rio de Janeiro - RJ	N
281	Governo do Distrito Federal - GDF	N
282	Instituto Nacional de Cardiologia - INC/SAS	N
283	Instituto Federal de Educação, Ciência e Tecnologia da Bahia - IFBA	S
284	Superintendência do Desenvolvimento do Nordeste - SUDENE	S
285	Laboratório Nacional de Computação Científica - LNCC	S
286	Universidade Federal de Ouro Preto - UFOP	S
287	Ministério Público do Trabalho - MPT	S
288	Nuclebrás Equipamentos Pesados S.A. - Nuclep	N
289	Agência Estadual de Execução dos Projetos da Copa do Mundo do Pantanal 	N
290	Instituto de Pesquisas Energéticas e Nucleares - IPEN	N
291	DATAPREV	N
292	Conselho da Justiça Federal - CJF	N
293	Escola Técnica Federal de Brasília - BSB	S
294	Instituto de Radioproteção e DoSetria - IRD	S
295	Instituto Federal de Santa Catarina - IFSC	S
296	Instituto Federal do Espírito Santo - IFES	S
302	Escola de Administração Fazendária - ESAF	S
2	Presidência da República	S
3	Advocacia Geral da União - AGU	S
4	Casa Civil da Presidência da República	S
5	Instituto Nacional de Tecnologia da Informação - ITI	S
6	Secretaria de Administração	S
7	Arquivo Nacional	S
8	Imprensa Nacional	S
9	Sistema de Proteção da Amazônia	S
10	Controladoria Geral da União - CGU	S
11	Gabinete de Segurança Institucional - GSI	S
12	Secretaria de Comunicação Social	S
13	Secretaria de Relações Institucionais	S
14	Secretaria Geral da Presidência da República	S
15	Vice-Presidência da República	S
16	Secretaria Especial de Aquicultura e Pesca	S
17	Secretaria Especial de Direitos Humanos	S
18	Secretaria Especial de Políticas de Promoção da Igualdade Racial	S
19	Secretaria Especial de Políticas Para as Mulheres	S
20	Secretaria Especial de Portos - SEP	S
21	Ministério da Agricultura, Pecuária e Abastecimento - MAPA	S
22	Ministério da Ciência e Tecnologia - MCT	S
23	Agência Espacial Brasileira	S
24	Comissão Nacional de Energia Nuclear	S
25	Conselho Nacional de Desenvolvimento Científico e Tecnológico - CNPQ	S
26	Ministério das Cidades - MCidades	S
27	Ministério da Cultura - MinC	S
28	Instituto do Patrimônio Histórico e Artístico Nacional - IPHAN	S
29	Agência Nacional do Cinema - ANCINE	S
30	Fundação Casa de Rui Barbosa - FCRB	S
31	Fundação Cultural Palmares - FCP	S
32	Fundação Nacional de Artes - FUNARTE	S
33	Fundação Biblioteca Nacional - BN	S
34	Ministério da Educação - MEC	S
35	Fundo Nacional de Desenvolvimento da Educação - FNDE	S
36	Instituto Nacional de Estudos e Pesquisas Educacionais Anísio Teixeira - INEP 	S
37	Escola de Farmácia e Odontologia de Alfenas 	S
38	Escola Superior de Agricultura de Mossoró 	S
39	Faculdade de Medicina do Triângulo Mineiro 	S
40	Faculdades Federais Integradas de Diamantina 	S
41	Colégio Pedro II	S
42	Fundação Coordenação de Aperfeiçoamento de Pessoal de Nível Superior - CAPES 	S
43	Fundação Joaquim Nabuco 	S
44	Fundação Faculdade Federal de Ciências Médicas de Porto Alegre	S
45	Universidade Federal da Bahia	S
46	Universidade Federal da Paraíba	S
47	Universidade Federal de Alagoas	S
48	Universidade Federal de Campina Grande	S
49	Universidade Federal de Goiás	S
50	Universidade Federal de Itajubá	S
51	Universidade Federal de Juiz de Fora	S
52	Universidade Federal de Lavras	S
53	Universidade Federal de Minas Gerais	S
54	Universidade Federal de Pernambuco	S
55	Universidade Federal de Santa Catarina	S
56	Universidade Federal de Santa Maria	S
57	Universidade Federal de São Paulo	S
58	Universidade Federal de Uberlândia	S
59	Universidade Federal do Ceará	S
60	Universidade Federal do Espírito Santo	S
61	Universidade Federal do Pará	S
62	Universidade Federal do Paraná	S
63	Universidade Federal do Rio de Janeiro	S
64	Universidade Federal do Rio Grande do Norte	S
65	Universidade Federal do Rio Grande do Sul	S
66	Universidade Federal Fluminense	S
67	Universidade Federal Rural da Amazônia	S
68	Universidade Federal Rural de Pernambuco	S
69	Universidade Federal Rural do Rio de Janeiro	S
70	Centro Federal de Educação Tecnológica Celso Suckow da Fonseca	S
71	Centro Federal de Educação Tecnológica da Bahia	S
72	Centro Federal de Educação Tecnológica da Paraíba	S
73	Centro Federal de Educação Tecnológica de Alagoas	S
74	Centro Federal de Educação Tecnológica de Bambuí	S
75	Centro Federal de Educação Tecnológica de Bento Gonçalves	S
76	Centro Federal de Educação Tecnológica de Campos	S
77	Centro Federal de Educação Tecnológica de Cuiabá	S
78	Centro Federal de Educação Tecnológica de Goiás	S
79	Centro Federal de Educação Tecnológica de Januária	S
80	Centro Federal de Educação Tecnológica de Mato Grosso	S
81	Centro Federal de Educação Tecnológica de Minas Gerais	S
82	Centro Federal de Educação Tecnológica de Ouro Preto	S
83	Centro Federal de Educação Tecnológica de Pelotas	S
84	Centro Federal de Educação Tecnológica de Pernambuco	S
85	Centro Federal de Educação Tecnológica de Petrolina	S
86	Centro Federal de Educação Tecnológica de Química de Nilópolis	S
87	Centro Federal de Educação Tecnológica de Rio Pomba	S
88	Centro Federal de Educação Tecnológica de Rio Verde	S
89	Centro Federal de Educação Tecnológica de Roraima	S
90	Centro Federal de Educação Tecnológica de Santa Catarina	S
91	Centro Federal de Educação Tecnológica de São Paulo	S
92	Centro Federal de Educação Tecnológica de São Vicente do Sul	S
93	Centro Federal de Educação Tecnológica de Sergipe	S
94	Centro Federal de Educação Tecnológica de Uberaba	S
95	Centro Federal de Educação Tecnológica de Urutaí	S
96	Centro Federal de Educação Tecnológica do Amazonas	S
190	Ministério do Planejamento, Orçamento e Gestão - MPOG	\N
\.


--
-- TOC entry 2539 (class 0 OID 24429)
-- Dependencies: 151
-- Data for Name: area_externa_envolvida; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY area_externa_envolvida (seq_area_externa, seq_item_configuracao, nom_contato, num_telefone) FROM stdin;
\.


--
-- TOC entry 2540 (class 0 OID 24432)
-- Dependencies: 152
-- Data for Name: atendimento_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY atendimento_chamado (seq_atendimento_chamado, seq_chamado, num_matricula, dth_atendimento_chamado, txt_atendimento_chamado) FROM stdin;
\.


--
-- TOC entry 2541 (class 0 OID 24438)
-- Dependencies: 153
-- Data for Name: atividade_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY atividade_chamado (seq_atividade_chamado, seq_subtipo_chamado, dsc_atividade_chamado, qtd_min_sla_triagem, qtd_min_sla_atendimento, flg_atendimento_externo, flg_forma_medicao_tempo, qtd_min_sla_solucao_final, seq_equipe_ti, txt_atividade, seq_tipo_ocorrencia, num_matricula_aprovador, num_matricula_aprovador_substituto, flg_exige_aprovacao, flg_exige_agendamento) FROM stdin;
148	54	ADMINISTRAÇÃO DE UM REPOSITÓRIO DOS SOFTWARES DA MICROSOFT PARA ATUALIZAÇÃO DOS SERVIDORES	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
149	55	ADMINISTRAÇÃO DE UM AMBIENTE CENTRALIZADO PARA O USO DE TERMINAIS NA REDE (THIN CLIENTS)	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
150	56	GERENCIAMENTO DOS SUBSISTEMAS DE ARMAZENAMENTO DE MASSA E DA SAN (STORAGE ÁREA NETWORK)	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
151	57	INSTALAÇÃO, CONFIGURAÇÃO, GERÊNCIA E SUPORTE DA ESTRUTURA DE VIRTUALIZAÇÃO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
152	58	ADMINISTRAÇÃO DE INSTÂNCIAS LINUX	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
199	70	INCIDENTE - CLASSIFICAÇÃO INDEFINIDA	30	90	S	U	90	\N	\N	1	\N	\N	\N	\N
154	59	MANUTENÇÃO DE POLÍTICA DE USUÁRIO E MÁQUINAS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
155	60	GERÊNCIA DOS SERVIÇOS DE IMPRESSÃO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
156	61	ADMINISTRAÇÃO DO SERVIÇO DE ARMAZENAMENTO DOS DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
157	62	ADMINISTRAÇÃO DE AMBIENTE PARA A EXECUÇÃO DE APLICAÇÕES	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
158	63	SUPORTE TÉCNICO ÀS EQUIPES DAS DEMAIS ÁREAS OPERACIONAIS	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
159	64	SISTEMA ELÉTRICO - ESTABILIZADOR, NO-BREAK, ETC.	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
160	64	SISTEMA DE AR-CONDICIONADO	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
161	64	SISTEMA DE CÂMERAS	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
162	64	SISTEMA ANTI-INCÊNDIO	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
163	64	INFRAESTRUTURA DE ATIVOS DE REDE	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
164	64	IDENTIFICAR OS INCIDENTES DE HARDWARE DE SERVIDORES E ACIONAR. ACIONAR CONTRATOS DE GARANTIA 	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
165	65	MONITORAMENTO DOS RECURSOS DE TIC DA REDE	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
166	65	MONITORAMENTO DE FALHAS DE SERVIÇOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
167	65	MONITORAMENTO DA DISPONIBILIDADE DOS SERVIDORES	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
168	65	DETECÇÃO DE PROBLEMAS EM REDES LAN E WAN (FRAME RELAY, LINHAS DEDICADAS, SATÉLITES, LPCD, SLDD, RÁDIO)	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
172	66	SOLICITAÇÕES DE CRIAÇÃO DE CONTAS DE ACESSO DE USUÁRIOS AOS SISTEMAS	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
173	67	REPASSES DE SERVIÇOS DE SUPORTE NÃO PREVISTO NO ESCOPO DESTE CONTRATO 	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
174	68	REQUISIÇÕES PARA INSTALAÇÃO, MANUTENÇÃO, ATIVAÇÃO E CERTIFICAÇÃO DE PONTOS DE REDE	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
175	68	REQUISIÇÕES PARA CONFECÇÃO DE CABO DE REDE	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
176	68	REQUISIÇÕES PARA FISCALIZAÇÃO DE OBRAS DE REDE	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
177	68	REQUISIÇÕES PARA INSTALAÇÃO, ATIVAÇÃO E MANUTENÇÃO DE FIBRA ÓPTICA INTERNA	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
179	69	REQUISIÇÕES PARA CONSERTO DE EQUIPAMENTOS DE TIC	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
107	7	CONECTIVIDADE À REDE: INTERLIGAÇÃO DE MICROS NA REDE E À INTERNET	\N	\N	N	\N	\N	2	CONECTIVIDADE À REDE: INTERLIGAÇÃO DE MICROS NA REDE E À INTERNET	2	\N	\N	\N	\N
102	15	DÚVIDA SOBRE SISTEMA OPERACIONAL	30	480	S	U	\N	3	DÚVIDA SOBRE SISTEMA OPERACIONAL	2	\N	\N	\N	\N
186	17	CONFIGURAÇÃO DE SERVIDORES DE ARQUIVOS E IMPRESSÃO, VPN, BANDA LARGA, VOIP, ACESSO A REDES SEM FIO, OUTROS	30	480	S	U	\N	\N	\N	2	\N	\N	\N	\N
187	17	INSTALAÇÃO, CONFIGURAÇÃO E SUPORTE AO USO DE APLICATIVOS DA INTERNET 	30	480	S	U	\N	\N	\N	2	\N	\N	\N	\N
188	18	CRIAÇÃO, PERMISSÕES, MUDANÇA DE SENHAS, OUTROS	30	480	S	U	\N	\N	\N	2	\N	\N	\N	\N
189	20	INFORMAR OS USUÁRIOS SOBRE PROBLEMAS OPERACIONAIS DO AMBIENTE DE TIC QUE CAUSEM IMPACTO 	30	480	S	U	\N	\N	\N	3	\N	\N	\N	\N
191	20	ELABORAR E MANTER ATUALIZADO UM SÍTIO NA INTRANET COM DICAS E SOLUÇÕES 	30	480	S	U	\N	\N	\N	3	\N	\N	\N	\N
200	70	DÚVIDA - CLASSIFICAÇÃO INDEFINIDA	30	90	S	C	\N	\N	\N	3	\N	\N	\N	\N
61	15	MOVIMENTAÇÃO E RETIRADA DE DESKTOP	30	480	S	U	\N	3	MOVIMENTAÇÃO E RETIRADA DE DESKTOP	2	\N	\N	\N	\N
183	16	INSTALAÇÃO, CONFIGURAÇÃO E SUPORTE AO MS-EXCHANGE E FERRAMENTAS DE GROUPWARE	30	480	S	U	\N	2	INSTALAÇÃO, CONFIGURAÇÃO E SUPORTE AO MS-EXCHANGE E FERRAMENTAS DE GROUPWARE 	2	\N	\N	\N	\N
169	66	ERROS IDENTIFICADOS NOS SISTEMAS CORPORATIVOS	\N	\N	S	\N	\N	4	VERIFICAR O PROBLEMA	1	\N	\N	\N	\N
83	27	ASSOCIAÇÃO DE USUÁRIOS A PERFIS	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
85	33	EXECUÇÃO DE SCRIPTS DE ALTERAÇÃO DE OBJETOS DAS BASES	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
86	28	CONFIGURAÇÃO DAS PLATAFORMAS DE BASES DE DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
87	28	ORGANIZAÇÃO E MANUTENÇÃO DAS ESTRUTURAS DE DIRETÓRIOS E ARQUIVOS EM DISCO LOCAIS / STORAGE	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
88	28	PLANEJAMENTO, CRIAÇÃO E MANTENÇÃO DAS ESTRUTURAS DE ARMAZENAMENTO DAS BASES DE DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
89	28	DESENVOLVIMENTO E MANUTENÇÃO DE TRIGGERS E STORED PROCEDURES NAS BASES DE DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
90	28	GERENCIAR E ATUALIZAR AS ESTRUTURAS DAS BASES DE DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
91	29	CRIAÇÃO DE CONSULTAS EM BASES DE DADOS (QUERIES AD HOC)	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
92	30	Desenvolvimento de scripts SQL 	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
93	30	Execução do script SQL na base de desenvolvimento 	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
94	30	Homologação do script na base de homologação 	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
95	30	Implementação do script SQL em produção 	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
96	31	ANÁLISE DE MODELOS DE DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
24	11	SIAFI GERENCIAL	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
64	15	CONFIGURAÇÃO DE PERIFÉRICOS, DESAJUSTES NOS CONTATOS DE PLACAS, CONECTORES E OUTROS	30	480	S	U	\N	3	CONFIGURAÇÃO DE PERIFÉRICOS, DESAJUSTES NOS CONTATOS DE PLACAS, CONECTORES E OUTROS	2	\N	\N	\N	\N
185	16	INSTALAÇÃO, REMOÇÃO E CONFIGURAÇÃO DE ANTI-VIRUS	30	480	S	U	\N	3	INSTALAÇÃO, REMOÇÃO E CONFIGURAÇÃO DE ANTI-VIRUS	2	\N	\N	\N	\N
190	20	ORIENTAR USUÁRIOS QUANTO ÀS NORMAS E PROCEDIMENTOS TÉCNICOS E DE SEGURANÇA 	30	480	S	U	\N	3	ORIENTAR USUÁRIOS QUANTO ÀS NORMAS E PROCEDIMENTOS TÉCNICOS E DE SEGURANÇA 	3	\N	\N	\N	\N
193	23	APOIO PARA TRANSMISSÃO DE VÍDEO VIA WEB 	30	480	S	U	\N	3	APOIO PARA TRANSMISSÃO DE VÍDEO VIA WEB 	2	\N	\N	\N	\N
194	23	SUPORTE NA REALIZAÇÃO DE VIDEOCONFERÊNCIAS	30	480	S	U	\N	3	SUPORTE NA REALIZAÇÃO DE VIDEOCONFERÊNCIAS	2	\N	\N	\N	\N
82	27	CRIAÇÃO DE PERFIS DE USUÁRIO	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
55	26	ELABORAÇÃO E/OU EXECUÇÃO DOS SCRIPTS DE CARGA DE DADOS	\N	\N	N	\N	\N	4	ELABORAÇÃO E/OU EXECUÇÃO DOS SCRIPTS DE CARGA DE DADOS	2	\N	\N	\N	\N
99	31	APROVAÇÃO DE MODELOS DE DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
66	15	VERIFICAÇÃO DE CONFORMIDADE COM AS ESPECIFICAÇÕES DE NOVOS EQUIPAMENTOS DE MICROINFORMÁTICA	30	480	S	U	\N	3	VERIFICAÇÃO DE CONFORMIDADE COM AS ESPECIFICAÇÕES DE NOVOS EQUIPAMENTOS DE MICROINFORMÁTICA	2	\N	\N	\N	\N
141	41	ADMINISTRAÇÃO DO SERVIÇO QUE PERMITE ACESSO À INTERNET (INDEVIDO/ILEGAL, IMPACTO NA PRODUTIVIDADE,ETC)	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
103	7	DHCP - SERVIÇO DE CONFIGURAÇÃO AUTOMÁTICA DA REDE NAS ESTAÇÕES DE TRABALHO	\N	\N	N	\N	\N	2	CONFIGURAÇÃO AUTOMÁTICA DA REDE NAS ESTAÇÕES DE TRABALHO	2	\N	\N	\N	\N
144	50	IMPLEMENTAÇÃO DE TÉCNICAS DE SEGURANÇA DOS SERVIDORES VISANDO DEIXÁ-LOS MENOS VULNERÁVEIS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
115	35	VÍDEO SOB DEMANDA: ADMINISTRAÇÃO DE AMBIENTE PARA A DISPONIBILIZAÇÃO DE VÍDEOS NA WEB	\N	\N	N	\N	\N	2	VÍDEO SOB DEMANDA: ADMINISTRAÇÃO DE AMBIENTE PARA A DISPONIBILIZAÇÃO DE VÍDEOS NA WEB	2	\N	\N	\N	\N
116	35	VOIP: ADMINISTRAÇÃO DA SOLUÇÃO PARA A COMUNICAÇÃO POR VOZ ATRAVÉS DA INTERNET	\N	\N	S	\N	\N	2	VOIP: ADMINISTRAÇÃO DA SOLUÇÃO PARA A COMUNICAÇÃO POR VOZ ATRAVÉS DA INTERNET	2	\N	\N	\N	\N
225	73	FALHA COM A IMPRESSORA	30	120	S	U	480	3	FALHA COM A IMPRESSORA	1	\N	\N	\N	\N
98	31	REALIZAÇÃO DE ALTERAÇÕES EM MODELOS DE DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
180	70	SOLICITAÇÃO - CLASSIFICAÇÃO INDEFINIDA	30	90	S	U	\N	\N	\N	2	\N	\N	\N	\N
108	7	PROSPECÇÃO DE NOVAS REDES E EXPANSÃO DE REDES EXISTENTES	\N	\N	N	\N	\N	2	PROSPECÇÃO DE NOVAS REDES E EXPANSÃO DE REDES EXISTENTES	2	\N	\N	\N	\N
136	43	MANUTENÇÃO DAS POLÍTICAS DE SEGURANÇA DA INFORMAÇÃO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
137	43	PROPOSIÇÃO DE DIRETRIZES E POLÍTICAS DE SEGURANÇA EM T.I.	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
138	44	ADMINISTRAÇÃO DO SERVIDOR PARA ARMAZENAMENTO E ANÁLISE DOS LOGS DOS SERVIDORES E EQUIPAMENTOS DE REDE DO CPD	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
139	45	ADMINISTRAÇÃO DA SOLUÇÃO DE DETECÇÃO DE INTRUSÃO DE REDE	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
140	46	AÇÃO REATIVA AOS INCIDENTES DE SEGURANÇA ENVOLVENDO OS ATIVOS COMPUTACIONAIS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
142	48	PROVIMENTO DE UM CANAL CRIPTOGRAFADO PARA A COMUNICAÇÃO COM OS SERVIDORES INTERNOS DA REDE 	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
118	36	AUTOMATIZAÇÃO E DOCUMENTAÇÃO DOS SERVIÇOS DE REDE	\N	\N	N	\N	\N	2	AUTOMATIZAÇÃO E DOCUMENTAÇÃO DOS SERVIÇOS DE REDE	2	\N	\N	\N	\N
120	38	ANÁLISE E DESENHO DE SOLUÇÕES DE TIC 	\N	\N	N	\N	\N	1	ANÁLISE E DESENHO DE SOLUÇÕES DE TIC 	2	\N	\N	\N	\N
122	38	PLANEJAMENTO DE MODERNIZAÇÃO DA ÁREA DE TECNOLOGIA DA INFORMAÇÃO	\N	\N	N	\N	\N	1	PLANEJAMENTO DE MODERNIZAÇÃO DA ÁREA DE TECNOLOGIA DA INFORMAÇÃO	2	\N	\N	\N	\N
123	38	AUXILIAR NA ELABORAÇÃO DE PLANO DE TRABALHO, PROJETO BÁSICO E TERMO DE REFERÊNCIA	\N	\N	N	\N	\N	1	AUXILIAR NA ELABORAÇÃO DE PLANO DE TRABALHO, PROJETO BÁSICO E TERMO DE REFERÊNCIA	2	\N	\N	\N	\N
125	38	ELABORAÇÃO DE PLANOS DE CONTINGÊNCIAS E RECUPERAÇÃO DE DESASTRES	\N	\N	N	\N	\N	1	ELABORAÇÃO DE PLANOS DE CONTINGÊNCIAS E RECUPERAÇÃO DE DESASTRES	2	\N	\N	\N	\N
30	6	ORIENTAÇÃO E ESCLARECIMENTOS SOBRE PROBLEMAS ESPECÍFICOS DOS SERVIÇOS DA REDE	30	480	S	U	\N	\N	\N	3	\N	\N	\N	\N
84	33	ELABORAÇÃO DE SCRIPTS DE ALTERAÇÃO DE OBJETOS DAS BASES	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
97	31	VERIFICAÇÃO DE CONFORMAÇÃO DE MODELOS DE DADOS COM OS PADRÕES DA EMBRATUR	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
130	13	INSTALAÇÃO, ALTERAÇÃO E EXCLUSÃO DE APLICAÇÕES NO AMBIENTE DE PRODUÇÃO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
131	13	INSTALAÇÃO, ALTERAÇÃO E EXCLUSÃO DE APLICAÇÕES NO AMBIENTE DE HOMOLOGAÇÃO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
132	13	REINICIALIZAÇÃO DOS SERVIÇOS	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
143	49	GESTÃO DE CERTIFICADOS DIGITAIS 	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
170	66	SOLICITAÇÕES DE MUDANÇAS NOS SISTEMAS CORPORATIVOS 	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
145	51	ADMINISTRAÇÃO DOS SERVIDORES HOSPEDADOS INTERNAMENTE E NO DATA CENTER	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
146	52	ATUALIZAÇÃO DOS SISTEMAS ANTI-VÍRUS DAS ESTAÇÕES DE TRABALHO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
147	53	ARQUIVAMENTO, CÓPIA DE SEGURANÇA E TRANSPORTE DE CÓPIAS DAS MÍDIAS PARA ARMAZENAMENTO EXTERNO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
69	15	INSTALAÇÃO, CONFIGURAÇÃO, ATUALIZAÇÃO E SUPORTE DE SISTEMAS OPERACIONAIS	30	960	S	U	\N	3	RESOLVER	2	\N	\N	\N	\N
126	13	DÚVIDA DE NAVEGAÇÃO DE APLICATIVOS	30	480	S	U	\N	\N	\N	3	\N	\N	\N	\N
127	13	REALIZAR TESTES DE INTEGRAÇÃO DO SISTEMA COM O AMBIENTE	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
128	13	SOLUCIONAR PROBLEMAS ATRAVÉS DE SCRIPTS DE ATENDIMENTO	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
11	3	SERVIÇOS ASSOCIADOS A SISTEMA OPERACIONAL EM DESKTOPS	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
13	3	SERVIÇOS DE SUPORTE À SOFTWARES	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
15	4	SERVIÇOS ASSOCIADOS A PERIFÉRICOS EM DESKTOPS	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
14	4	SERVIÇOS DE SUPORTE A UTILIZAÇÃO DE RECURSOS DE REDE	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
17	4	GERENCIAMENTO DE CONTAS DOS USUÁRIOS	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
18	11	SIDOR	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
19	11	SIAFI	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
20	11	CADIN	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
21	11	SIAPE	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
22	11	ComprasNET	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
23	11	SCDP	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
62	15	SUBSTITUIÇÃO DE COMPONENTES EM DESKTOPS, NOTEBOOKS E PERIFÉRICO	30	480	S	U	\N	3	SUBSTITUIÇÃO DE COMPONENTES EM DESKTOPS, NOTEBOOKS E PERIFÉRICO	2	\N	\N	\N	\N
31	7	MONITORAMENTO E GERENCIAMENTO DA REDE, SERVIDORES E SERVIÇOS	\N	\N	N	\N	\N	2	MONITORAMENTO E GERENCIAMENTO DA REDE, SERVIDORES E SERVIÇOS	2	\N	\N	\N	\N
110	7	VERIFICAÇÃO E ATIVAÇÃO DE PONTOS DE REDE	\N	\N	N	\N	\N	2	VERIFICAÇÃO E ATIVAÇÃO DE PONTOS DE REDE	2	\N	\N	\N	\N
111	7	INSTALAÇÃO/DESINSTALAÇÃO/CONFIGURAÇÃO DE SWITCHES E ROTEADORES	\N	\N	N	\N	\N	2	INSTALAÇÃO/DESINSTALAÇÃO/CONFIGURAÇÃO DE SWITCHES E ROTEADORES	2	\N	\N	\N	\N
112	7	MONITORAÇÃO E ANÁLISE DE PERFORMANCE DOS RECURSOS DA REDE	\N	\N	N	\N	\N	2	MONITORAÇÃO E ANÁLISE DE PERFORMANCE DOS RECURSOS DA REDE	2	\N	\N	\N	\N
106	7	WINS: SERVIÇO WINS PARA PERMITIR O ACESSO DE ESTAÇÕES WINDOWS 98 À REDE	\N	\N	N	\N	\N	2	WINS: SERVIÇO WINS PARA PERMITIR O ACESSO DE ESTAÇÕES WINDOWS 98 À REDE	2	\N	\N	\N	\N
67	15	ATUALIZAR O INVENTÁRIO DOS RECURSOS COMPUTACIONAIS	30	480	S	U	\N	3	ATUALIZAR O INVENTÁRIO DOS RECURSOS COMPUTACIONAIS	2	\N	\N	\N	\N
65	15	ACIONAR EMPRESAS RESPONSÁVEIS POR GARANTIAS OU CONTRATOS VIGENTES DE MANUTENÇÃO	\N	\N	S	\N	\N	3	ACIONAR EMPRESAS RESPONSÁVEIS POR GARANTIAS OU CONTRATOS VIGENTES DE MANUTENÇÃO 	2	\N	\N	\N	\N
201	15	ERRO NA INICIALIZAÇÃO DO SISTEMA OPERACIONAL	30	240	S	U	4800	3	Analisar problema	1	\N	\N	\N	\N
202	16	ERRO NO MS OFFICE OU OPEN OFFICE	30	120	S	U	480	3	ERRO NO MS OFFICE OU OPEN OFFICE	1	\N	\N	\N	\N
203	17	FALHA DE RECURSO DE REDE	30	480	S	U	4800	2	VERIFICAR O PROBLEMA	1	\N	\N	\N	\N
204	7	INDISPONIBILIDADE OU FALHA DE REDE INTRANET	30	240	S	U	480	2	VERIFICAR O PROBLEMA	1	\N	\N	\N	\N
205	7	INDISPONIBILIDADE OU FALHA DE REDE INTERNET	30	240	S	U	480	2	VERIFICAR O PROBLEMA	1	\N	\N	\N	\N
206	35	INDISPONIBILIDADE OU FALHA NA TELEFONIA VOIP	30	240	S	U	4800	2	VERIFICAR O PROBLEMA	1	\N	\N	\N	\N
207	13	ERRO DE RELATORIO DA APLICAÇÃO	30	480	S	U	4800	3	VERIFICAR O PROBLEMA	1	\N	\N	\N	\N
209	13	INDISPONIBILIDADE DE APLICAÇÃO	30	240	S	U	4800	3	VERIFICAR O PROBLEMA	1	\N	\N	\N	\N
210	13	ERRO DE LOGIN DA APLICAÇÃO	30	480	S	U	4800	3	VERIFICAR O PROBLEMA	1	\N	\N	\N	\N
211	69	INCIDENTES  DE  HARDWARE  DE  SERVIDORES  E  ACIONAR. ACIONAR CONTRATOS DE GARANTIA 	30	480	S	U	9600	\N	\N	1	\N	\N	\N	\N
212	13	DÚVIDA DE REGRA DE NEGÓCIO DE SISTEMA	30	480	S	U	\N	\N	\N	3	\N	\N	\N	\N
153	59	DEFINIÇÃO DE GRUPOS E PERMISSÕES, E ADMINISTRAÇÃO DO SERVIÇO DE DIRETÓRIO	\N	960	N	U	\N	2	DEFINIÇÃO DE GRUPOS E PERMISSÕES	2	\N	\N	\N	\N
213	15	ERRO EM SERVIÇOS OU PROCESSOS DO SISTEMA OPERACIONAL	30	240	S	U	480	3	ERRO EM SERVIÇOS OU PROCESSOS DO SISTEMA OPERACIONAL	1	\N	\N	\N	\N
60	15	INSTALAÇÃO DE DESKTOPS / NOTEBOOKS	30	960	S	U	\N	3	INSTALAÇÃO DE DESKTOPS / NOTEBOOKS	2	\N	\N	\N	\N
68	15	APOIO PARA GRAVAÇÃO EM CD-ROM E DVD-ROM, CONVERSÃO DE ARQUIVOS, BACKUP	30	480	S	U	\N	3	APOIO PARA GRAVAÇÃO EM CD-ROM E DVD-ROM, CONVERSÃO DE ARQUIVOS, BACKUP 	2	\N	\N	\N	\N
181	15	INSTALAÇÃO, CONFIGURAÇÃO, ATUALIZAÇÃO E SUPORTE DE SISTEMAS OPERACIONAIS	30	480	S	U	\N	3	INSTALAÇÃO, CONFIGURAÇÃO, ATUALIZAÇÃO E SUPORTE DE SISTEMAS OPERACIONAIS	2	\N	\N	\N	\N
63	15	ADIÇÃO OU CONFIGURAÇÃO DE COMPONENTES E IMPRESSORAS	30	480	S	U	\N	3	ADIÇÃO OU CONFIGURAÇÃO DE COMPONENTES E IMPRESSORAS	2	\N	\N	\N	\N
104	7	DNS: SERVIÇO DE NOMES E DOMÍNIOS	\N	\N	N	\N	\N	2	DNS: SERVIÇO DE NOMES E DOMÍNIOS	2	\N	\N	\N	\N
113	35	ADMINISTRAÇÃO DA INFRAESTRUTURA PARA SUPORTE E PARCEIROS NA TRANSMISSÃO DE VÍDEO VIA WEB	\N	\N	N	\N	\N	2	ADMINISTRAÇÃO DA INFRAESTRUTURA PARA SUPORTE E PARCEIROS NA TRANSMISSÃO DE VÍDEO VIA WEB	2	\N	\N	\N	\N
198	7	ACESSO A SERVIDORES OU PASTAS	\N	\N	N	\N	\N	2	ACESSO A SERVIDORES OU PASTAS	2	\N	\N	\N	\N
74	26	BACKUP DE BASES DE DADOS	\N	\N	N	\N	\N	4	BACKUP DE BASES DE DADOS	2	\N	\N	\N	\N
7	6	SUPORTE TÉCNICO ÀS EQUIPES DAS DEMAIS ÁREAS OPERACIONAIS	\N	\N	N	\N	\N	2	SUPORTE TÉCNICO ÀS EQUIPES DAS DEMAIS ÁREAS OPERACIONAIS	2	\N	\N	\N	\N
75	26	CRIAÇÃO DE BASES DE DADOS	\N	\N	N	\N	\N	4	CRIAÇÃO DE BASES DE DADOS	2	\N	\N	\N	\N
77	26	ANÁLISE DE QUERIES EM BASES DE DADOS	\N	\N	N	\N	\N	4	ANÁLISE DE QUERIES EM BASES DE DADOS	2	\N	\N	\N	\N
73	26	TUNNING DE BASES DE DADOS	\N	\N	N	\N	\N	4	TUNNING DE BASES DE DADOS	2	\N	\N	\N	\N
78	26	MIGRAÇÃO DE BASES DE DADOS	\N	\N	N	\N	\N	4	MIGRAÇÃO DE BASES DE DADOS	2	\N	\N	\N	\N
29	4	E-MAIL INSTITUCIONAL	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
100	32	MANUTENÇÃO DO AMBIENTE DE REPLICAÇÃO DE BASES DE DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
178	68	REQUISIÇÕES PARA INSTALAÇÃO E MANUTENÇÃO DE RAMAIS DE VOZ	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
34	12	ANÁLISE DE VULNERABILIDADES NOS ATIVOS DE TIC E NOS PROCESSOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
134	40	ANÁLISE FORENSE DE SISTEMAS COMPROMETIDOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
135	42	SEGMENTAÇÃO E PROTEÇÃO DA REDE, CRIAÇÃO DE REGRAS E NAT, MONITORAMENTO, VERIFICAÇÃO DE INCIDENTES DE SEGURANÇA	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
195	71	TRATAMENTO DE RISCO	30	\N	N	U	\N	\N	\N	2	\N	\N	\N	\N
196	13	ALTERAÇÃO DE LAYOUT	30	\N	N	U	\N	\N	\N	2	\N	\N	\N	\N
4	1	DEFINIÇÃO DE PROCESSOS DE TRABALHO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
3	1	AUDITORIA APF	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
43	1	AUDITORIA DE PROCESSOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
44	1	METODOLOGIA DE DESENVOLVIMENTO DE SISTEMAS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
1	2	REALIZAR TESTES FUNCIONAIS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
2	2	REALIZAR TESTES DE ACEITAÇÃO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
16	2	REALIZAR TESTES DE STESS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
45	2	REALIZAR TESTES DE PERFORMANCE	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
101	32	REPLICAÇÃO DE BASES DE DADOS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
129	13	CADASTRO, ALTERAÇÕES E DESBLOQUEIO DE SENHAS	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
5	4	PROBLEMAS COM PONTOS DE REDE	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
6	4	ADIÇÃO/ALTERAÇÃO DE LOCAL DE PONTOS DE REDE	30	\N	S	\N	\N	\N	\N	2	\N	\N	\N	\N
40	4	ACESSO VPN	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
25	4	SERVIDOR DE ARQUIVOS	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
26	4	INTRANET INSTITUCIONAL	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
27	4	ACESSO A INTERNET E SERVIÇOS CORRELATOS	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
12	4	VOIP	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
28	4	ANTIVÍRUS	30	1440	S	U	\N	\N	\N	2	\N	\N	\N	\N
59	13	ATUALIZAÇÃO DE DOCUMENTAÇÃO DOS SISTEMAS	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
70	25	APOIO E ACOMPANHAMENTO TÉCNICO EM EVENTOS, SEMINÁRIOS, PALESTRAS, SESSÕES DE VIDEOCONFERÊNCIA, ETC. 	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
71	25	CONFIGURAÇÃO E OPERAÇÃO DE REDE, SOFTWARE E EQUIPAMENTOS NECESSÁRIOS PARA O EVENTO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
72	25	LEVANTAMENTO E MONTAGEM DA INFRAESTRUTURA NECESSÁRIA PARA O EVENTO	30	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
80	27	CRIAÇÃO DE USUÁRIO	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
81	27	DESLIGAMENTO DE USUÁRIO	30	480	N	U	\N	\N	\N	2	\N	\N	\N	\N
197	13	ERRO DE FUNCIONALIDADE	30	480	S	U	4800	3	VERIFICAR O PROBLEMA	1	\N	\N	\N	\N
171	66	SOLICITAÇÕES DE ATRIBUIÇÕES DE PERMISSÕES DE ACESSO AOS SISTEMAS	\N	\N	N	\N	\N	4	Conceder atribuições de permissão de acesso 	2	\N	\N	\N	\N
233	75	CONHECER NAVEGABILIDADE DE APLICATIVO	\N	\N	N	\N	\N	4	SUPORTE A NAVEGABILIDADE DE APLICATIVO	2	\N	\N	\N	\N
234	75	REALIZAR TESTES DE INTEGRAÇÃO DO SISTEMA COM O AMBIENTE	\N	\N	N	\N	\N	4	REALIZAR TESTES DE INTEGRAÇÃO DO SISTEMA	2	\N	\N	\N	\N
182	16	INSTALAÇÃO, CONFIGURAÇÃO E SUPORTE DE APLICATIVOS MS OFFICE E OPEN OFFICE 	30	480	S	U	\N	3	INSTALAÇÃO, CONFIGURAÇÃO E SUPORTE DE APLICATIVOS MS OFFICE E OPEN OFFICE 	2	\N	\N	\N	\N
184	16	INSTALAÇÃO E CONFIGURAÇÃO DE SOFTWARES AUTORIZADOS PELA EMBRATUR	30	480	S	U	\N	3	INSTALAÇÃO E CONFIGURAÇÃO DE SOFTWARES AUTORIZADOS PELA EMBRATUR	2	\N	\N	\N	\N
214	16	DÚVIDA SOBRE MS-EXCHANGE OU MS-OUTLOOK	30	240	S	U	\N	3	DÚVIDA SOBRE MS-EXCHANGE OU MS-OUTLOOK	3	\N	\N	\N	\N
215	16	DÚVIDA SOBRE O ANTIVIRUS	30	240	S	U	\N	3	DÚVIDA SOBRE O ANTIVIRUS	3	\N	\N	\N	\N
216	16	ERRO NO ANTIVIRUS	30	120	S	U	480	3	ERRO NO ANTIVIRUS	1	\N	\N	\N	\N
217	16	ERRO MS-EXCHANGE OU MS-OUTLOOK	30	120	S	U	480	3	ERRO MS-EXCHANGE OU MS-OUTLOOK	1	\N	\N	\N	\N
218	20	INFORMAR OS USUÁRIOS SOBRE PROBLEMAS OPERACIONAIS DO AMBIENTE DE TIC QUE CAUSEM IMPACTO 	30	480	S	U	\N	3	INFORMAR OS USUÁRIOS SOBRE PROBLEMAS OPERACIONAIS DO AMBIENTE DE TIC QUE CAUSEM IMPACTO 	2	\N	\N	\N	\N
114	35	VIDEOCONFERÊNCIA: ADMINISTRAÇÃO DA INFRAESTRUTURA PARA SUPORTE NA REALIZAÇÃO DE VIDEOCONFERÊNCIAS	\N	\N	N	\N	\N	2	VÍDEO SOB DEMANDA: ADMINISTRAÇÃO DE AMBIENTE PARA A DISPONIBILIZAÇÃO DE VÍDEOS NA WEB	2	\N	\N	\N	\N
220	20	ELABORAR E MANTER ATUALIZADO UM SÍTIO NA INTRANET COM DICAS E SOLUÇÕES 	30	480	S	U	\N	3	ELABORAR E MANTER ATUALIZADO UM SÍTIO NA INTRANET COM DICAS E SOLUÇÕES 	2	\N	\N	\N	\N
192	22	INSTALAÇÃO, CONFIGURAÇÃO, ORIENTAÇÃO E SUPORTE (REDE SERPRO, COMPRASNET, OUTROS)	30	480	S	U	\N	2	INSTALAÇÃO, CONFIGURAÇÃO, ORIENTAÇÃO E SUPORTE (REDE SERPRO, COMPRASNET, OUTROS)	2	\N	\N	\N	\N
221	72	CONFIGURAÇÃO DE TERMINAL VOIP/SOFTPHONE	30	480	S	U	\N	3	CONFIGURAÇÃO DE TERMINAL VOIP/SOFTPHONE	2	\N	\N	\N	\N
222	72	DÚVIDA NA UTILIZAÇÃO/CONFIGURAÇÃO DE TERMINAL VOIP/SOFTPHONE	30	240	S	U	\N	3	DÚVIDA NA UTILIZAÇÃO/CONFIGURAÇÃO DE TERMINAL VOIP/SOFTPHONE	3	\N	\N	\N	\N
223	72	INDISPONIBILIDADE OU FALHA NA TELEFONIA VOIP	30	120	S	U	480	3	INDISPONIBILIDADE OU FALHA NA TELEFONIA VOIP	1	\N	\N	\N	\N
224	73	CONFIGURAÇÃO DE IMPRESSORA	30	240	S	U	\N	3	CONFIGURAÇÃO DE IMPRESSORA	2	\N	\N	\N	\N
117	35	RADIO ON-LINE: MANUTENÇÃO DO AMBIENTE PARA A TRANSMISSÃO DE ÁUDIO PELA INTERNET	\N	\N	S	\N	\N	2	RADIO ON-LINE: MANUTENÇÃO DO AMBIENTE PARA A TRANSMISSÃO DE ÁUDIO PELA INTERNET	2	\N	\N	\N	\N
58	7	INSTALAÇÃO E CUSTOMIZAÇÃO DE SOFTWARES DE GERENCIAMENTO DE REDES	\N	\N	N	\N	\N	2	INSTALAÇÃO E CUSTOMIZAÇÃO DE SOFTWARES DE GERENCIAMENTO DE REDES	2	\N	\N	\N	\N
105	7	NTP: SERVIÇO DE SINCRONIZAÇÃO DE RELÓGIOS DE ESTAÇÕES DE TRABALHO, SERVIDORES E EQUIPAMENTOS DE REDE	\N	\N	N	\N	\N	3	NTP: SERVIÇO DE SINCRONIZAÇÃO DE RELÓGIOS DE ESTAÇÕES DE TRABALHO, SERVIDORES E EQUIPAMENTOS DE REDE	2	\N	\N	\N	\N
109	7	OPERAÇÃO DO PONTO DE PRESENÇA DO BACKBONE DA REDE	\N	\N	N	\N	\N	2	OPERAÇÃO DO PONTO DE PRESENÇA DO BACKBONE DA REDE	2	\N	\N	\N	\N
226	7	FALHA NO DNS INTERNO OU EXTERNO	30	240	S	U	480	2	FALHA NO DNS INTERNO OU EXTERNO	1	\N	\N	\N	\N
79	26	ARQUIVAMENTO DE DADOS PARA BASES DE PRODUÇÃO	\N	\N	N	\N	\N	4	ARQUIVAMENTO DE DADOS PARA BASES DE PRODUÇÃO	2	\N	\N	\N	\N
230	26	CRIAÇÃO E CONFIGURAÇÃO DE BANCO DE DADOS DE HOMOLOGAÇÃO	\N	\N	N	\N	\N	4	CRIAÇÃO E CONFIGURAÇÃO DE BANCO DE DADOS DE HOMOLOGAÇÃO	2	\N	\N	\N	\N
228	74	INSTALAÇÃO, CONFIGURAÇÃO E SUPORTE AO USO DE APLICATIVOS DA INTERNET 	\N	\N	N	\N	\N	3	INSTALAÇÃO, CONFIGURAÇÃO E SUPORTE AO USO DE APLICATIVOS DA INTERNET 	2	\N	\N	\N	\N
227	74	SERVIDORES DE ARQUIVOS E IMPRESSÃO, VPN, BANDA LARGA, VOIP, ACESSO A REDES SEM FIO, OUTROS	\N	\N	N	\N	\N	3	SERVIDORES DE ARQUIVOS E IMPRESSÃO, VPN, BANDA LARGA, VOIP, ACESSO A REDES SEM FIO, OUTROS	2	\N	\N	\N	\N
119	36	PROCEDIMENTOS, ROTINAS E PROCESSOS RELACIONADOS COM A INFRAESTRUTURA DE TIC	\N	\N	N	\N	\N	3	PROCEDIMENTOS, ROTINAS E PROCESSOS RELACIONADOS COM A INFRAESTRUTURA DE TIC	2	\N	\N	\N	\N
229	6	ORIENTAÇÃO E ESCLARECIMENTOS SOBRE PROBLEMAS ESPECÍFICOS DOS SERVIÇOS DA REDE	\N	\N	N	\N	\N	2	ORIENTAÇÃO E ESCLARECIMENTOS SOBRE PROBLEMAS ESPECÍFICOS DOS SERVIÇOS DA REDE	2	\N	\N	\N	\N
121	38	PROSPECÇÃO DE TECNOLOGIA, AVALIAÇÃO, ESPECIFICAÇÃO DE SOLUÇÕES DE HARDWARE E SOFTWARE	\N	\N	N	\N	\N	1	PROSPECÇÃO DE TECNOLOGIA, AVALIAÇÃO, ESPECIFICAÇÃO DE SOLUÇÕES DE HARDWARE E SOFTWARE	2	\N	\N	\N	\N
124	38	ELABORAÇÃO DE ANÁLISE TÉCNICA E EMISSÃO DE RELATÓRIOS TÉCNICOS EM EQUIPAMENTOS E PROGRAMAS DA TI 	\N	\N	N	\N	\N	1	ELABORAÇÃO DE ANÁLISE TÉCNICA E EMISSÃO DE RELATÓRIOS TÉCNICOS EM EQUIPAMENTOS E PROGRAMAS DA TI 	2	\N	\N	\N	\N
231	26	FALHA NO BANCO DE DADOS	30	180	S	U	480	4	FALHA NO BANCO DE DADOS	1	\N	\N	\N	\N
232	26	FALHA NA OPERAÇÃO DE BACKUP	30	180	S	U	480	4	FALHA NA OPERAÇÃO DE BACKUP	1	\N	\N	\N	\N
235	75	SOLUCIONAR PROBLEMAS ATRAVÉS DE SCRIPTS DE ATENDIMENTO	\N	\N	N	\N	\N	4	SOLUCIONAR PROBLEMAS ATRAVÉS DE SCRIPTS	2	\N	\N	\N	\N
236	75	CADASTRO, ALTERAÇÕES E DESBLOQUEIO DE SENHAS	\N	\N	N	\N	\N	4	CADASTRO, ALTERAÇÕES E DESBLOQUEIO DE SENHAS	2	\N	\N	\N	\N
237	75	SUPORTE AO USO DE SISTEMAS CORPORATIVOS	\N	\N	S	\N	\N	4	SUPORTE AO USO DE SISTEMAS CORPORATIVOS	2	\N	\N	\N	\N
238	77	ELABORAÇÃO E/OU EXECUÇÃO DOS SCRIPTS DE CARGA DE DADOS	\N	\N	N	\N	\N	4	ELABORAÇÃO E/OU EXECUÇÃO DOS SCRIPTS	2	\N	\N	\N	\N
239	77	ANÁLISE DE QUERIES EM BASES DE DADOS	\N	\N	S	\N	\N	4	ANÁLISE DE QUERIES EM BASES DE DADOS	2	\N	\N	\N	\N
240	75	CRIAÇÃO/CONFIGURAÇÃO DE PERFIS DE USUÁRIO	\N	\N	S	\N	\N	4	CRIAÇÃO/CONFIGURAÇÃO DE PERFIS DE USUÁRIO	2	\N	\N	\N	\N
241	77	ELABORAÇÃO DE SCRIPTS DE ALTERAÇÃO DE OBJETOS DAS BASES	\N	\N	N	\N	\N	4	ELABORAÇÃO DE SCRIPTS DE ALTERAÇÃO DE OBJETOS DAS BASES	2	\N	\N	\N	\N
242	77	EXECUÇÃO DE SCRIPTS DE ALTERAÇÃO DE OBJETOS DAS BASES	\N	\N	N	\N	\N	4	EXECUÇÃO DE SCRIPTS DE ALTERAÇÃO DE OBJETOS	2	\N	\N	\N	\N
243	77	DESENVOLVIMENTO E MANUTENÇÃO DE TRIGGERS, STORED PROCEDURES 	\N	\N	N	\N	\N	4	EXECUÇÃO DE SCRIPTS DE ALTERAÇÃO DE OBJETOS	2	\N	\N	\N	\N
244	77	DESENVOLVIMENTO DE SCRIPTS SQL	\N	\N	N	\N	\N	4	DESENVOLVIMENTO DE SCRIPTS SQL	2	\N	\N	\N	\N
245	77	ANÁLISE DE MODELOS DE DADOS	\N	\N	N	\N	\N	4	ANÁLISE DE MODELOS DE DADOS	2	\N	\N	\N	\N
133	13	SUPORTE AO USO DE SISTEMAS CORPORATIVOS	\N	\N	N	\N	\N	\N	SUPORTE AOS SISTEMAS CORPORATIVO	2	\N	\N	\N	\N
246	78	VERIFICAÇÃO DE CONFORMIDADE DE MODELOS DE DADOS COM OS PADRÕES DA EMBRATUR 	\N	\N	N	\N	\N	1	VALIDAÇÃO DO MODELO DE DADOS	2	\N	\N	\N	\N
247	13	IMPLEMENTAÇÃO DE FUNCIONALIDADE DE SISTEMA	\N	\N	N	\N	\N	1	IMPLEMENTAÇÃO DE FUNCIONALIDADE DE SISTEMA	2	\N	\N	\N	\N
248	47	DESCARTE DE INFORMAÇÃO	\N	640	N	C	\N	3	DESCARTE DE INFORMAÇÃO	2	\N	\N	\N	\N
250	79	INCIDENTE - CLASSIFICAÇÃO INDEFINIDA	30	90	S	U	90	\N	\N	1	\N	\N	\N	\N
251	79	DÚVIDA - CLASSIFICAÇÃO INDEFINIDA	30	90	S	C	\N	\N	\N	3	\N	\N	\N	\N
252	80	DÚVIDA - AR - CONDICIONADO	30	90	S	C	\N	6	\N	3	\N	\N	\N	\N
253	80	SOLICITAÇÃO - AR - CONDICIONADO	30	\N	S	\N	\N	6	\N	2	\N	\N	\N	\N
254	80	LIMPEZA DO AR-CONDICIONADO	30	\N	S	\N	\N	6	\N	2	\N	\N	\N	\N
255	80	REMOÇÃO DO AR-CONDICIONADO	30	\N	S	\N	\N	6	\N	2	\N	\N	\N	\N
256	80	DEFEITO/FALHA/PROBLEMA COM O AR-CONDICIONADO	30	90	S	U	90	6	\N	1	\N	\N	\N	\N
257	81	DÚVIDA - AUDITÓRIO	30	90	S	C	\N	6	\N	3	\N	\N	\N	\N
258	81	INCIDENTE - AUDITÓRIO	30	90	S	U	90	6	\N	1	\N	\N	\N	\N
260	82	DÚVIDA - CARIMBO	30	90	S	C	\N	6	\N	3	\N	\N	\N	\N
261	82	INCIDENTE - CARIMBO	30	90	S	U	90	6	\N	1	\N	\N	\N	\N
262	82	SOLICITAÇÃO - CARIMBO	30	\N	S	\N	\N	6	\N	2	\N	\N	\N	\N
263	83	DÚVIDA - CELULAR	30	90	S	C	\N	6	\N	3	\N	\N	\N	\N
264	83	INCIDENTE - CELULAR	30	90	S	U	90	6	\N	1	\N	\N	\N	\N
266	83	REPARO NO CELULAR	30	90	S	U	90	6	\N	1	\N	\N	\N	\N
267	84	DÚVIDA - CHAVEIRO	30	90	S	C	\N	6	\N	3	\N	\N	\N	\N
268	84	CÓPIA DE CHAVE	30	\N	S	\N	\N	6	\N	2	\N	\N	\N	\N
269	84	CONFECÇÃO DE CHAVE	30	\N	S	\N	\N	6	\N	2	\N	\N	\N	\N
270	84	CONSERTO DE FECHADURA	30	90	S	U	90	6	\N	1	\N	\N	\N	\N
271	84	ABERTURA DE FECHADURA	30	90	S	U	90	6	\N	1	\N	\N	\N	\N
275	85	DÚVIDA - TRANSPORTE	30	90	S	C	\N	6	\N	3	\N	\N	\N	\N
276	85	INCIDENTE - TRANSPORTE	30	90	S	U	90	6	\N	1	\N	\N	\N	\N
277	85	SOLICITAÇÃO- TRANSPORTE - PERFIL ESPECIAL	3	90	S	U	\N	6	\N	2	\N	\N	\N	\N
273	85	TRANSPORTE SOMENTE COM HORA DE SAÍDA	30	\N	S	\N	\N	6	\N	2	104	52	1	\N
265	83	SOLICITAÇÃO DE CELULAR	30	\N	S	\N	\N	6	\N	2	104	52	1	\N
274	85	TRANSPORTE COM HORA DE SAÍDA E RETORNO	30	\N	S	\N	\N	6	\N	2	104	52	1	\N
272	85	TRANSPORTE SOMENTE COM HORA DE RETORNO	30	\N	S	\N	\N	6	\N	2	104	52	1	\N
278	81	teste2	\N	\N	S	\N	\N	6	\N	2	\N	\N	\N	\N
259	81	RESERVA DE AUDITÓRIO	30	\N	S	\N	\N	6	\N	2	\N	\N	\N	1
279	81	Sala de Reunião 01	\N	\N	N	\N	\N	6	\N	2	\N	\N	\N	1
280	80	teste 01	\N	\N	N	\N	\N	\N	\N	2	3066	3005	\N	1
281	81	Salara Reunião 02	\N	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
282	80	teste	\N	\N	N	\N	\N	\N	\N	2	\N	\N	\N	1
283	80	teste horas planejadoas	\N	\N	N	\N	\N	\N	\N	2	\N	\N	\N	\N
249	79	SOLICITAÇÃO - CLASSIFICAÇÃO INDEFINIDA	0	90	S	U	\N	\N	\N	2	\N	\N	\N	\N
42	1	AUDITORIA DE DOCUMENTAÇÃO	\N	\N	N	\N	\N	\N	\N	2	1	1	1	\N
\.


--
-- TOC entry 2542 (class 0 OID 24446)
-- Dependencies: 154
-- Data for Name: atividade_rb_rdm; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY atividade_rb_rdm (seq_atividade_rb_rdm, seq_item_configuracao, seq_servidor, seq_rdm, seq_equipe_ti, descricao, ordem, data_hora_inicio_execucao, data_hora_fim_execucao, situacao, num_matricula_recurso) FROM stdin;
\.


--
-- TOC entry 2543 (class 0 OID 24452)
-- Dependencies: 155
-- Data for Name: atividade_rb_rdm_template; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY atividade_rb_rdm_template (seq_atividade_rb_rdm_template, seq_item_configuracao, seq_servidor, seq_rdm_template, seq_equipe_ti, descricao, ordem) FROM stdin;
\.


--
-- TOC entry 2544 (class 0 OID 24458)
-- Dependencies: 156
-- Data for Name: atividade_rdm; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY atividade_rdm (seq_rdm, seq_item_configuracao, seq_servidor, seq_equipe_ti, descricao, data_hora_prevista_execucao, seq_atividade_rdm, data_hora_inicio_execucao, data_hora_fim_execucao, situacao, ordem, num_matricula_recurso) FROM stdin;
\.


--
-- TOC entry 2545 (class 0 OID 24464)
-- Dependencies: 157
-- Data for Name: atividade_rdm_template; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY atividade_rdm_template (seq_rdm_template, seq_atividade_rdm_template, seq_item_configuracao, seq_servidor, seq_equipe_ti, descricao, ordem) FROM stdin;
\.


--
-- TOC entry 2546 (class 0 OID 24470)
-- Dependencies: 158
-- Data for Name: atribuicao_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY atribuicao_chamado (seq_atribuicao_chamado, seq_chamado, seq_equipe_ti, seq_situacao_chamado, num_matricula, txt_atividade, dth_atribuicao, seq_equipe_atribuicao, dth_inicio_efetivo, dth_encerramento_efetivo) FROM stdin;
1	1	1	4	1	Realizar atendimento em 1º nível	2011-07-10 18:52:29	\N	2011-07-10 18:52:12	2011-07-10 18:52:29
2	2	1	4	1	Realizar atendimento em 1º nível	2011-07-10 18:52:58	\N	2011-07-10 18:52:32	2011-07-10 18:52:58
3	2	1	4	1	atender	2011-07-10 18:52:58	1	2011-07-10 20:01:13	2011-07-10 20:01:21
4	3	1	4	1	Realizar atendimento em 1º nível	2011-08-11 11:46:46	\N	2011-08-11 11:25:37	2011-08-11 11:46:46
6	3	1	4	1	Realizar atendimento em 1º nível	2011-08-11 15:48:26	\N	2011-08-11 15:46:02	2011-08-11 15:48:26
7	3	1	4	1	rerfd	2011-08-11 15:48:26	1	2011-08-11 17:25:56	2011-08-11 17:26:05
5	3	1	4	1	teste	2011-08-11 11:46:46	1	2011-08-11 17:26:14	2011-08-11 17:26:22
8	4	1	4	1	Realizar atendimento em 1º nível	2011-08-23 14:31:27	\N	2011-08-23 14:31:11	2011-08-23 14:31:27
9	4	1	6	\N	teste	2011-08-23 14:31:28	\N	\N	\N
\.


--
-- TOC entry 2547 (class 0 OID 24476)
-- Dependencies: 159
-- Data for Name: avaliacao_atendimento; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY avaliacao_atendimento (seq_avaliacao_atendimento, nom_avaliacao_atendimento) FROM stdin;
1	5 - Ótimo
2	4 - Bom
3	2 - Ruim
4	3 - Regular
5	1 - Péssimo
\.


--
-- TOC entry 2548 (class 0 OID 24479)
-- Dependencies: 160
-- Data for Name: banco_de_dados; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY banco_de_dados (seq_banco_de_dados, nom_banco_de_dados) FROM stdin;
2	MySQL
3	SQLServer
5	Access
1	PostgreSQL
\.


--
-- TOC entry 2549 (class 0 OID 24482)
-- Dependencies: 161
-- Data for Name: central_atendimento; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY central_atendimento (seq_central_atendimento, nom_central_atendimento) FROM stdin;
1	TI
2	Atividades Auxiliares
\.


--
-- TOC entry 2550 (class 0 OID 24485)
-- Dependencies: 162
-- Data for Name: chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY chamado (seq_chamado, num_matricula_solicitante, seq_situacao_chamado, seq_localizacao_fisica, seq_prioridade_chamado, txt_chamado, dth_abertura, dth_triagem_efetiva, dth_inicio_previsao, dth_inicio_efetivo, dth_encerramento_efetivo, dth_agendamento, num_matricula_contato, seq_item_configuracao, num_prioridade_fila, seq_atividade_chamado, flg_solicitacao_atendida, seq_avaliacao_atendimento, num_matricula_avaliador, txt_avaliacao, seq_tipo_ocorrencia, txt_contingenciamento, txt_causa_raiz, num_matricula_cadastrante, seq_acao_contingenciamento, txt_resolucao, seq_motivo_cancelamento, seq_avaliacao_conhecimento_tecnico, seq_avaliacao_postura, seq_avaliacao_tempo_espera, seq_avaliacao_tempo_solucao, objetivo_evento, dth_reserva_evento, quantidade_pessoas_evento, servicos_evento, dt_inicio_utilizacao_aparelho, dt_fim_utilizacao_aparelho, flg_destinacao_chamado) FROM stdin;
2	1	4	\N	1	testes	2011-07-10 18:51:59	2011-07-10 18:52:58	2011-07-10 18:52:59	2011-07-10 20:00:33	2011-07-10 20:01:21	\N	\N	\N	\N	2	\N	\N	\N	\N	2	\N	\N	\N	\N	Por Tiago Chaves Oliveira em 10/07/2011 18:52\ratender\r\rPor Tiago Chaves Oliveira em 10/07/2011 20:01\rteste 	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1
1	1	4	\N	1	TEste de abertura de chamado	2011-07-10 18:50:27	2011-07-10 18:52:29	2011-07-10 18:51:27	\N	2011-07-10 18:52:29	\N	\N	\N	\N	3	S	1	1	\N	2	\N	\N	\N	\N	Por Tiago Chaves Oliveira em 10/07/2011 18:52\ré isso	\N	2	1	2	1	\N	\N	\N	\N	\N	\N	1
3	1	4	\N	1	teaste sddfg	2011-08-11 11:19:30	2011-08-11 15:48:26	2011-08-11 15:49:49	2011-08-11 16:16:39	2011-08-11 17:26:22	\N	\N	\N	\N	42	\N	\N	\N	\N	2	\N	\N	\N	\N	Por Tiago Chaves Oliveira em 11/08/2011 15:48\rteste\r\rPor Tiago Chaves Oliveira em 11/08/2011 17:26\rteste\r\rPor Tiago Chaves Oliveira em 11/08/2011 17:26\rteste	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
4	1	6	\N	1	teste	2011-08-23 14:31:05	2011-08-23 14:31:27	2011-08-23 14:32:05	2011-08-23 14:31:33	\N	\N	\N	\N	\N	84	\N	\N	\N	\N	2	\N	\N	\N	\N	Por Tiago Chaves Oliveira em 23/08/2011 14:31\rteste	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1
\.


--
-- TOC entry 2551 (class 0 OID 24492)
-- Dependencies: 163
-- Data for Name: chamado_rdm; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY chamado_rdm (seq_chamado, seq_rdm) FROM stdin;
\.


--
-- TOC entry 2552 (class 0 OID 24495)
-- Dependencies: 164
-- Data for Name: correcao_time_sheet; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY correcao_time_sheet (seq_time_sheet, dth_inicio_correcao, dth_fim_correcao, txt_justificativa_correcao, flg_aprovado, num_matricula_aprovador) FROM stdin;
\.


--
-- TOC entry 2553 (class 0 OID 24501)
-- Dependencies: 165
-- Data for Name: criticidade; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY criticidade (seq_criticidade, nom_criticidade) FROM stdin;
2	Muito crítico
3	Crítico
4	Baixo
5	Médio
\.


--
-- TOC entry 2554 (class 0 OID 24504)
-- Dependencies: 166
-- Data for Name: destino_triagem; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY destino_triagem (seq_equipe_ti, cod_dependencia) FROM stdin;
\.


--
-- TOC entry 2555 (class 0 OID 24507)
-- Dependencies: 167
-- Data for Name: edificacao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY edificacao (seq_edificacao, nom_edificacao, cod_dependencia) FROM stdin;
\.


--
-- TOC entry 2556 (class 0 OID 24510)
-- Dependencies: 168
-- Data for Name: equipe_atribuicao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY equipe_atribuicao (seq_equipe_atribuicao, dsc_equipe_atribuicao, seq_equipe_ti) FROM stdin;
\.


--
-- TOC entry 2557 (class 0 OID 24513)
-- Dependencies: 169
-- Data for Name: equipe_envolvida; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY equipe_envolvida (num_matricula_recurso, seq_item_configuracao, qtd_hora_alocada) FROM stdin;
\.


--
-- TOC entry 2558 (class 0 OID 24516)
-- Dependencies: 170
-- Data for Name: equipe_servidor; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY equipe_servidor (seq_servidor, num_matricula_recurso, num_ordem) FROM stdin;
\.


--
-- TOC entry 2559 (class 0 OID 24519)
-- Dependencies: 171
-- Data for Name: equipe_ti; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY equipe_ti (seq_equipe_ti, nom_equipe_ti, num_matricula_lider, num_matricula_substituto, num_matricula_priorizador, cod_dependencia, seq_central_atendimento) FROM stdin;
4	Equipe de Sistemas	2873	2732	684	\N	1
3	Equipe de Suporte	65	2955	\N	\N	1
5	Gestão CGTI	171	684	\N	\N	1
2	Equipe de Redes	3054	2702	\N	\N	1
7	Equipe de Patrimônio	45	2658	\N	\N	2
6	Equipe de DAA	3066	52	104	\N	2
1	Equipe de Qualidade	1	1	\N	\N	1
\.


--
-- TOC entry 2560 (class 0 OID 24522)
-- Dependencies: 172
-- Data for Name: etapa_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY etapa_chamado (seq_etapa_chamado, seq_chamado, nom_etapa_chamado, dth_inicio_previsto, dth_fim_previsto, dth_inicio_efetivo, dth_fim_efetivo) FROM stdin;
\.


--
-- TOC entry 2561 (class 0 OID 24525)
-- Dependencies: 173
-- Data for Name: fase_item_configuracao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY fase_item_configuracao (seq_fase_item_configuracao, seq_item_configuracao, dat_inicio_fase_projeto, seq_fase_projeto, dsc_fase_item_configuracao, dat_fim_fase_projeto, txt_observacao_fase) FROM stdin;
\.


--
-- TOC entry 2562 (class 0 OID 24531)
-- Dependencies: 174
-- Data for Name: fase_projeto; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY fase_projeto (seq_fase_projeto, nom_fase_projeto) FROM stdin;
1	Análise de viabilidade
2	Levantamento de requisitos
3	Implementação
4	Testes
5	Implantção
\.


--
-- TOC entry 2563 (class 0 OID 24534)
-- Dependencies: 175
-- Data for Name: feriado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY feriado (seq_feriado, dth_feriado, nom_feriado) FROM stdin;
2	2011-03-07	Carnaval
3	2011-03-08	Carnaval
4	2011-04-21	Tiradentes
5	2011-04-22	Paixão de Cristo
6	2011-05-01	Dia do Trabalho
7	2011-06-23	Corpus Christi
8	2011-09-07	Independência do Brasil
9	2011-10-12	Nossa Sr.a Aparecida - Padroeira do Brasil
10	2011-11-02	Finados
11	2011-11-15	Proclamação da República
12	2011-12-25	Natal
13	2012-01-01	Confraternização Universal
14	2012-02-20	Carnaval
15	2012-02-21	Carnaval
16	2012-04-06	Paixão de Cristo
17	2012-04-21	Tiradentes
18	2012-05-01	Dia do Trabalho
19	2012-06-07	Corpus Christi
20	2012-09-07	Independência do Brasil
21	2012-10-12	Nossa Sr.a Aparecida - Padroeira do Brasil
22	2012-11-02	Finados
23	2012-11-15	Proclamação da República
24	2012-12-25	Natal
25	2013-01-01	Confraternização Universal
26	2013-02-11	Carnaval
27	2013-02-12	Carnaval
28	2013-03-29	Paixão de Cristo
29	2013-04-21	Tiradentes
30	2013-05-01	Dia do Trabalho
31	2013-05-30	Corpus Christi
32	2013-09-07	Independência do Brasil
33	2013-10-12	Nossa Sr.a Aparecida - Padroeira do Brasil
34	2013-11-02	Finados
35	2013-11-15	Proclamação da República
36	2013-12-25	Natal
37	2014-01-01	Confraternização Universal
38	2014-03-03	Carnaval
39	2014-03-04	Carnaval
40	2014-04-18	Paixão de Cristo
41	2014-04-21	Tiradentes
42	2014-05-01	Dia do Trabalho
43	2014-06-19	Corpus Christi
44	2014-09-07	Independência do Brasil
45	2014-10-12	Nossa Sr.a Aparecida - Padroeira do Brasil
46	2014-11-02	Finados
47	2014-11-15	Proclamação da República
48	2014-12-25	Natal
49	2015-01-01	Confraternização Universal
50	2015-02-16	Carnaval
51	2015-02-17	Carnaval
52	2015-04-03	Paixão de Cristo
53	2015-04-21	Tiradentes
54	2015-05-01	Dia do Trabalho
55	2015-06-04	Corpus Christi
56	2015-09-07	Independência do Brasil
57	2015-10-12	Nossa Sr.a Aparecida - Padroeira do Brasil
58	2015-11-02	Finados
59	2015-11-15	Proclamação da República
60	2015-12-25	Natal
1	2011-01-01	Confraternização Universal
\.


--
-- TOC entry 2564 (class 0 OID 24540)
-- Dependencies: 176
-- Data for Name: frequencia_manutencao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY frequencia_manutencao (seq_frequencia_manutencao, nom_frequencia_manutencao) FROM stdin;
1	Diário
2	Semanal
3	Quinzenal
4	Mensal
7	Semestral
8	Eventual
\.


--
-- TOC entry 2565 (class 0 OID 24543)
-- Dependencies: 177
-- Data for Name: historico_acesso_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY historico_acesso_chamado (seq_historico_acesso_chamado, seq_chamado, num_matricula, dth_acesso) FROM stdin;
1	1	1	2011-07-10 18:52:12
2	1	1	2011-07-10 18:52:12
3	2	1	2011-07-10 18:52:32
4	2	1	2011-07-10 18:52:33
5	2	1	2011-07-10 18:53:58
6	2	1	2011-07-10 18:53:59
7	2	1	2011-07-10 19:24:44
8	2	1	2011-07-10 19:25:08
9	2	1	2011-07-10 19:25:08
10	1	1	2011-07-10 19:26:18
11	1	1	2011-07-10 19:26:19
12	1	1	2011-07-10 19:28:35
13	1	1	2011-07-10 19:28:36
14	1	1	2011-07-10 19:29:02
15	1	1	2011-07-10 19:29:02
16	1	1	2011-07-10 19:29:17
17	1	1	2011-07-10 19:29:18
18	1	1	2011-07-10 19:30:55
19	1	1	2011-07-10 19:30:56
20	1	1	2011-07-10 19:31:20
21	1	1	2011-07-10 19:31:20
22	2	1	2011-07-10 19:32:15
23	2	1	2011-07-10 19:32:16
24	2	1	2011-07-10 19:58:21
25	2	1	2011-07-10 19:58:21
26	2	1	2011-07-10 20:00:15
27	2	1	2011-07-10 20:00:15
28	2	1	2011-07-10 20:01:03
29	2	1	2011-07-10 20:01:04
30	1	1	2011-08-11 11:18:21
31	1	1	2011-08-11 11:18:21
32	1	1	2011-08-11 11:18:39
33	1	1	2011-08-11 11:18:39
34	3	1	2011-08-11 11:19:45
35	3	1	2011-08-11 11:19:46
36	3	1	2011-08-11 11:25:37
37	3	1	2011-08-11 11:25:37
38	3	1	2011-08-11 11:50:34
39	3	1	2011-08-11 11:50:34
40	3	1	2011-08-11 11:50:52
41	3	1	2011-08-11 11:50:53
42	3	1	2011-08-11 11:50:58
43	3	1	2011-08-11 11:50:58
44	3	1	2011-08-11 15:45:54
45	3	1	2011-08-11 15:45:55
46	3	1	2011-08-11 15:46:02
47	3	1	2011-08-11 15:46:02
48	3	1	2011-08-11 15:48:35
49	3	1	2011-08-11 15:48:35
50	3	1	2011-08-11 15:49:07
51	3	1	2011-08-11 15:49:07
52	3	1	2011-08-11 16:16:46
53	3	1	2011-08-11 16:16:46
54	3	1	2011-08-11 17:00:48
55	3	1	2011-08-11 17:00:49
56	3	1	2011-08-11 17:03:10
57	3	1	2011-08-11 17:03:10
58	3	1	2011-08-11 17:08:51
59	3	1	2011-08-11 17:08:51
60	3	1	2011-08-11 17:09:27
61	3	1	2011-08-11 17:09:27
62	3	1	2011-08-11 17:09:50
63	3	1	2011-08-11 17:09:51
64	3	1	2011-08-11 17:13:44
65	3	1	2011-08-11 17:13:44
66	3	1	2011-08-11 17:13:56
67	3	1	2011-08-11 17:13:56
68	3	1	2011-08-11 17:22:10
69	3	1	2011-08-11 17:22:10
70	3	1	2011-08-11 17:22:20
71	3	1	2011-08-11 17:22:21
72	3	1	2011-08-11 17:25:47
73	3	1	2011-08-11 17:25:47
74	3	1	2011-08-11 17:26:10
75	3	1	2011-08-11 17:26:10
76	4	1	2011-08-23 14:31:10
77	4	1	2011-08-23 14:31:11
78	4	1	2011-08-23 14:31:31
79	4	1	2011-08-23 14:31:32
80	4	1	2011-08-23 14:32:31
81	4	1	2011-08-23 14:32:31
\.


--
-- TOC entry 2566 (class 0 OID 24546)
-- Dependencies: 178
-- Data for Name: historico_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY historico_chamado (seq_historico_chamado, seq_chamado, num_matricula, dth_historico, seq_situacao_chamado, seq_motivo_suspencao, txt_historico) FROM stdin;
1	1	1	2011-07-10 18:50:27	1	\N	Chamado encaminhado para
2	2	1	2011-07-10 18:51:59	1	\N	Chamado encaminhado para
3	1	1	2011-07-10 18:52:29	8	\N	Solução do chamado em 1º nível de atendimento: é isso
4	2	1	2011-07-10 18:52:58	2	\N	Chamado encaminhado para Equipe de Qualidade
5	2	1	2011-07-10 20:01:03	2	\N	Chamado aprovado. Data prevista para término: 08/08/2011 18:00:00
6	2	1	2011-07-10 20:01:13	3	\N	\N
7	2	1	2011-07-10 20:01:21	4	\N	Encerramento de atribuição (Equipe de Qualidade): teste 
8	1	1	2011-08-11 11:18:35	4	\N	Chamado avaliado pelo cliente como Atendido. 
9	3	1	2011-08-11 11:19:30	1	\N	\N
10	3	1	2011-08-11 11:46:47	2	\N	Chamado encaminhado para Equipe de Qualidade
11	3	1	2011-08-11 15:45:26	1	\N	Chamado aprovado pelo gestor com a seguinte justificativa: teste de aprovação
12	3	1	2011-08-11 15:48:26	2	\N	Chamado encaminhado para Equipe de Qualidade
13	3	1	2011-08-11 15:48:49	6	\N	Chamado aprovado pelo gestor com a seguinte justificativa: teste de aprovação
14	3	1	2011-08-11 17:25:47	2	\N	Chamado aprovado. Data prevista para término: 12/08/2011 12:00:00
15	3	1	2011-08-11 17:25:56	3	\N	\N
16	3	1	2011-08-11 17:26:22	4	\N	Encerramento de atribuição (Equipe de Qualidade): teste
17	4	1	2011-08-23 14:31:06	1	\N	Chamado encaminhado para
18	4	1	2011-08-23 14:31:28	2	\N	Chamado encaminhado para Equipe de Qualidade
\.


--
-- TOC entry 2567 (class 0 OID 24552)
-- Dependencies: 179
-- Data for Name: informativo; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY informativo (seq_informativo, num_matricula, dth_cadastro, dth_vigencia, txt_informativo) FROM stdin;
\.


--
-- TOC entry 2568 (class 0 OID 24558)
-- Dependencies: 180
-- Data for Name: informativo_publico; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY informativo_publico (seq_informativo, cod_dependencia) FROM stdin;
\.


--
-- TOC entry 2569 (class 0 OID 24561)
-- Dependencies: 181
-- Data for Name: inoperancia_item_configuracao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY inoperancia_item_configuracao (seq_inoperancia_item_config, seq_item_configuracao, dth_inicio, txt_motivo, dth_fim, txt_solucao) FROM stdin;
\.


--
-- TOC entry 2570 (class 0 OID 24567)
-- Dependencies: 182
-- Data for Name: item_configuracao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY item_configuracao (seq_item_configuracao, seq_tipo_item_configuracao, seq_prioridade, seq_criticidade, seq_tipo_disponibilidade, seq_equipe_ti, num_matricula_gestor, num_matricula_lider, sig_item_configuracao, nom_item_configuracao, cod_uor_area_gestora, txt_item_configuracao, seq_servico) FROM stdin;
\.


--
-- TOC entry 2571 (class 0 OID 24573)
-- Dependencies: 183
-- Data for Name: item_configuracao_software; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY item_configuracao_software (seq_item_configuracao, seq_tipo_software, seq_status_software, flg_em_manutencao, flg_peti, num_item_peti, flg_descontinuado, flg_sistema_web, dsc_localizacao_documentacao, val_tamanho_software, seq_unidade_medida_software, val_aquisicao, seq_frequencia_manutencao, flg_tamanho) FROM stdin;
\.


--
-- TOC entry 2572 (class 0 OID 24576)
-- Dependencies: 184
-- Data for Name: janela_mudacao_item_configuracao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY janela_mudacao_item_configuracao (seq_janela_mudanca, seq_item_configuracao) FROM stdin;
\.


--
-- TOC entry 2573 (class 0 OID 24579)
-- Dependencies: 185
-- Data for Name: janela_mudanca; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY janela_mudanca (seq_janela_mudanca, dsc_janela_mudanca, hora_inicio_mudanca, minuto_inicio_mudanca, hora_fim_mudanca, minuto_fim_mudanca, dia_semana_inicial, dia_semana_final, limite_para_rdm) FROM stdin;
\.


--
-- TOC entry 2574 (class 0 OID 24582)
-- Dependencies: 186
-- Data for Name: janela_mudanca_servidor; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY janela_mudanca_servidor (seq_janela_mudanca, seq_servidor) FROM stdin;
\.


--
-- TOC entry 2575 (class 0 OID 24585)
-- Dependencies: 187
-- Data for Name: linguagem_programacao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY linguagem_programacao (seq_linguagem_programacao, nom_linguagem_programacao) FROM stdin;
1	JAVA
2	PHP
3	ASP
4	Centura
5	OpenCMS 7.05
6	Grails
7	JSF
8	EJB3
9	Hibernate 3.0
10	JPA
11	VB
12	Access
\.


--
-- TOC entry 2576 (class 0 OID 24588)
-- Dependencies: 188
-- Data for Name: localizacao_fisica; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY localizacao_fisica (seq_localizacao_fisica, seq_edificacao, nom_localizacao_fisica) FROM stdin;
\.


--
-- TOC entry 2577 (class 0 OID 24591)
-- Dependencies: 189
-- Data for Name: marca_hardware; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY marca_hardware (seq_marca_hardware, nom_marca_hardware) FROM stdin;
1	CISCO
2	Compaq Proliant ML350 G3
3	IBM
4	DELL
\.


--
-- TOC entry 2578 (class 0 OID 24594)
-- Dependencies: 190
-- Data for Name: menu_acesso; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY menu_acesso (seq_menu_acesso, seq_menu_acesso_pai, dsc_menu_acesso, nom_arquivo, num_prioridade, nom_arquivo_imagem_escuro, nom_arquivo_imagem_claro) FROM stdin;
54	52	Itens do Parque Tecnológico	Item_configuracaoPesquisa.php	1	\N	\N
53	52	Análise de impacto	Item_configuracaoAnaliseImpacto.php	2	\N	\N
13	12	Menu do Sistema	MenuPesquisa.php	1	\N	\N
15	14	Pessoal	#	1	\N	\N
18	14	Chamados	#	3	\N	\N
19	18	Atividade	Atividade_chamadoPesquisa.php	2	\N	\N
22	14	Sistemas de Informação	#	4	\N	\N
23	22	Bancos de dados	Banco_de_dadosPesquisa.php	1	\N	\N
24	22	Criticidade	CriticidadePesquisa.php	2	\N	\N
25	18	Edificação	Edificacao_infraeroPesquisa.php	4	\N	\N
16	15	Área de Atuação	Area_atuacaoPesquisa.php	1	\N	\N
29	22	Frequência de Manutenção	Frequencia_manutencaoPesquisa.php	3	\N	\N
30	22	Linguagem de Programação	Linguagem_programacaoPesquisa.php	4	\N	\N
32	18	Localização dos usuários	Localizacao_fisicaPesquisa.php	5	\N	\N
33	14	Servidores	#	4	\N	\N
34	33	Marca e Hardware	Marca_hardwarePesquisa.php	1	\N	\N
35	18	Motivos de Suspensão	Motivo_suspencaoPesquisa.php	6	\N	\N
36	12	Perfil de Acesso	Perfil_acessoPesquisa.php	2	\N	\N
37	15	Perfil de Profissional	Perfil_recurso_tiPesquisa.php	4	\N	\N
38	18	Prioridade dos chamados	Prioridade_chamadoPesquisa.php	7	\N	\N
39	22	Prioridade do Sistema	PrioridadePesquisa.php	5	\N	\N
79	18	Motivo de Cancelamento	Motivo_cancelamentoPesquisa.php	9	\N	\N
42	33	Sistema Operacional	Sistema_operacionalPesquisa.php	2	\N	\N
43	18	Situação dos chamados	Situacao_chamadoPesquisa.php	8	\N	\N
44	22	Status dos Sistemas de Informação	Status_softwarePesquisa.php	6	\N	\N
45	22	Tipos de Relacionamento Sistema X Servidores	Tipo_relacionamento_item_configuracaoPesquisa.php	7	\N	\N
46	22	Tipo de Sistema de Informação	Tipo_softwarePesquisa.php	8	\N	\N
47	22	Unidade de medida de Sistema de Informação	Unidade_medida_softwarePesquisa.php	9	\N	\N
81	14	Mudanças	#	3	\N	\N
50	41	Abrir Chamado	ChamadoAbrir.php	3	\N	\N
61	60	Relatório de Atividade	Relatorios_Atividade.php	1	\N	\N
63	60	Chamados por Profissional	RelatoriosChamadosAbertos.php	3	\N	\N
64	60	Chamados Atendidos	RelatoriosChamadosAtendidos.php	4	\N	\N
65	60	Chamados por Colaborador	RelatoriosChamadosPorColaborador.php	5	\N	\N
66	60	Tempo de Atendimento por Atribuição	RelatoriosChamadosPorTempoDeAtribuicao.php	6	\N	\N
67	60	Tempo de Atendimento por Chamado	RelatoriosChamadosPorTempoDeExecucao.php	7	\N	\N
68	60	Chamados por Tempo de Encerramento	RelatoriosOrdensDeServicoAbertas.php	8	\N	\N
14	\N	Administração	#	8	imagens/admin_icon.png	imagens/admin_icon.png
80	41	Atendimento de 2º nível - Meus Chamados	ChamadoAtendimentoPesquisaMeusChamados.php	2	\N	\N
69	60	Relatório de Cumprimento de Metas	RelatoriosOrdensDeServicoConcluidosGeral.php	9	\N	\N
55	22	Fases do desenvolvimento	Fase_projetoPesquisa.php	10	\N	\N
57	18	Avaliação de Atendimento	Avaliacao_atendimentoPesquisa.php	10	\N	\N
48	41	Atendimento de 1º nível	ChamadoTriagemPesquisa.php	1	\N	\N
20	18	Classe de Chamados	Tipo_chamadoPesquisa.php	0	\N	\N
21	18	Subclasse de chamado	Subtipo_chamadoPesquisa.php	1	\N	\N
58	18	Tipo de Chamado	Tipo_ocorrenciaPesquisa.php	-1	\N	\N
10	\N	Inicial	principal.php	0	imagens/inicial.jpg	imagens/inicial.jpg
27	\N	Profissionais	#	3	imagens/pessoa.jpg	imagens/pessoa.jpg
52	\N	Configuração	#	4	imagens/computador.jpg	imagens/computador.jpg
31	\N	Sair	logout.php	10	imagens/exit.png	imagens/exit.png
51	41	Pesquisar	ChamadoPesquisa.php	4	\N	\N
62	60	Relatório Consolidado de Atendimento de Chamados	Relatorios_chamadoPesquisa.php	2	\N	\N
70	60	Cockpit Gerencial	TorreDeControleDesempenho.php	0	\N	\N
59	41	Gestão Operacional	ChamadoAtendimentoPesquisaGestao.php	5	\N	\N
12	14	Sistema	#	6	imagens/seguranca.jpg	imagens/seguranca.jpg
71	12	Parâmetros do Sistema	ParametroPesquisa.php	-1	\N	\N
60	\N	Relatórios	#	5	imagens/relatorios.jpg	imagens/relatorios.jpg
72	18	Ação de Contingenciamento	Acao_contingenciamentoPesquisa.php	-2	\N	\N
82	81	Janela de Mudanças	Janela_MudancaPesquisa.php	1	\N	\N
49	41	Atendimento de 2º nível	ChamadoAtendimentoPesquisa.php	3	\N	\N
83	\N	Mudanças	#	2	imagens/rdm.jpg	imagens/rdm.jpg
87	81	Templates de RDM	RDMTemplatePesquisar.php	2	\N	\N
84	83	Abrir RDM	RDMAbrir.php	1	\N	\N
85	83	Calendário de Mudanças	RDMProgramacao.php	3	\N	\N
86	83	Pesquisar	RDMPesquisar.php	4	\N	\N
73	60	Taxa de Resolução Imediata	RelatoriosKPITaxaResolucao.php	0	\N	\N
76	60	Resolução do incidente no tempo estipulado (2º nível)	RelatoriosKPIResolucaoIncidente.php	4	\N	\N
77	60	Resolução da requisição de serviço no tempo (2º nível)	RelatoriosKPIResolucaoSolicitacao.php 	5	\N	\N
78	60	Gestão de Problemas	RelatoriosProblemas.php	6	\N	\N
89	41	Aprovação	ChamadoAprovacaoPesquisa.php	3	\N	\N
75	60	Nível de Satisfação dos Usuários Atendidos	RelatoriosKPISatisfacao.php	3	\N	\N
74	60	Tempo médio de atendimento no 1º nível 	RelatoriosKPITempoMedioAtendimento.php	2	\N	\N
28	15	Equipe de Atendimento	Equipe_tiPesquisa.php	0	\N	\N
17	22	Área Externa	Area_externaPesquisa.php	2	\N	\N
26	15	Atribuição de Equipe	Equipe_atribuicaoPesquisa.php	2	\N	\N
88	18	Central de Atendimento	Central_AtendimentoPesquisa.php	-4	\N	\N
41	\N	Chamados	#	1	\N	\N
93	92	Importar Planilha	RelatorioImportPaperCut.php	0	\N	\N
91	14	Agenda	Aagenda_index.php	0	\N	\N
2	1	Unidade organizacional	Unidade_organizacionalPesquisa.php	1	\N	\N
1	14	Organização	#	5	\N	\N
3	1	Função administrativa	Tipo_funcao_administrativaPesquisa.php	2	\N	\N
4	1	Feriados (dias de semana não úteis)	FeriadoPesquisa.php	3	\N	\N
40	27	Colaboradores da TI	Recurso_tiPesquisa.php	1	\N	\N
6	27	Colaboradores da Organização	PessoaPesquisa.php	0	\N	\N
92	14	Gestao de Impressão	#	5	\N	\N
7	\N	Alterar senha	PessoaAlteracaoSenha.php	9	\N	\N
\.


--
-- TOC entry 2579 (class 0 OID 24597)
-- Dependencies: 191
-- Data for Name: menu_perfil_acesso; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY menu_perfil_acesso (seq_perfil_acesso, seq_menu_acesso) FROM stdin;
2	13
2	15
2	18
2	19
2	22
2	23
2	24
2	25
2	16
2	29
2	30
2	32
2	28
2	33
2	34
2	35
2	36
2	37
2	38
2	39
2	42
2	83
2	44
2	45
2	46
2	47
2	70
4	70
2	12
2	43
5	83
2	73
2	55
2	57
7	83
6	83
2	20
2	21
2	58
2	10
1	10
4	10
2	48
1	48
4	48
2	27
1	27
4	27
4	83
8	83
2	84
2	31
1	31
4	31
2	51
1	51
4	51
2	54
1	54
4	54
2	53
1	53
4	53
5	84
6	84
2	82
2	71
2	79
6	82
4	84
8	84
2	72
2	76
2	77
2	78
2	63
1	63
2	80
1	80
4	80
2	49
1	49
4	49
2	86
2	50
1	50
4	50
5	86
2	60
1	60
4	60
7	86
6	86
4	86
8	86
2	85
5	85
7	85
6	85
4	85
8	85
2	87
6	87
2	59
4	59
2	14
2	52
1	52
9	52
4	52
2	81
6	81
2	89
2	75
4	75
2	74
4	74
2	93
2	2
2	1
2	3
2	4
2	40
1	40
4	40
2	6
1	6
5	6
7	6
9	6
6	6
4	6
8	6
2	17
2	88
2	41
1	41
4	41
2	7
1	7
5	7
7	7
9	7
6	7
4	7
8	7
\.


--
-- TOC entry 2580 (class 0 OID 24600)
-- Dependencies: 192
-- Data for Name: motivo_cancelamento; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY motivo_cancelamento (seq_motivo_cancelamento, dsc_motivo_cancelamento) FROM stdin;
1	A pedido do usuário
2	Chamado improcedente
3	Não foi possivel estabelcer contato
\.


--
-- TOC entry 2581 (class 0 OID 24603)
-- Dependencies: 193
-- Data for Name: motivo_suspencao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY motivo_suspencao (seq_motivo_suspencao, dsc_motivo_suspencao) FROM stdin;
1	Necessidade de compra de material
2	Aguardando retorno do usuário.
3	Aguardando informações de outra equipe
4	Aguardando janela de mudança
\.


--
-- TOC entry 2582 (class 0 OID 24606)
-- Dependencies: 194
-- Data for Name: parametro; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY parametro (cod_parametro, nom_parametro, val_parametro) FROM stdin;
HoraInicioExpediente	Hora de início do expediente	08:00
COD_SITUACAO_Contingenciado	Código da situação "Contingenciado"	7
COD_SITUACAO_Em_Andamento	Código da situação "Em Andamento"	3
COD_SITUACAO_Encerrado	Código da situação "Encerrado"	4
COD_SITUACAO_Suspenso	Código da situação "Suspenso"	5
CODS_SITUACAOES_EM_ANDAMENTO	Códigos de todas as situações onde está em andamento na TI	2, 3, 5, 7
COD_TIPO_SISTEMAS_INFORMACAO	Classe de chamado para atendimento a Sistemas de Informação	3
COD_UNIDADE_GABINETE_PRESIDENCIA	COD_UNIDADE_GABINETE_PRESIDENCIA	140
COD_UNIDADE_PRESIDENCIA	COD_UNIDADE_PRESIDENCIA	139
dominioRedeDefault	Domínio de rede padrão do sistema, mostrado na tela de login. Se não usado coloque "N"	N
EmailRemetente	E-mail do remetente das mensagens enviadas pelo sistema	suporteti@orgao.gov.br
enderecoCEA	URL da interface do usuário	http://localhost/EMBRATUR/cau/
enderecoGestaoTI	URL do Gestão TI	http://localhost/EMBRATUR/gestaoti/
flg_usar_funcionalidades_patrimonio	O sistema deve usar as funcionalidades de patrimônio (S o N)	N
FUNCOES_ADM_TRANSPORTE_ESPECIAL	Função administrativa para transporte especial	1,2,3,4,5,6,8,22,25,27,28,29,30
HoraFimExpediente	Hora de encerramento do expediente	18:00
HoraFimIntervalo	Hora de encerramento do intervalo - Geralmente almoço	14:00
HoraInicioIntervalo	Hora de início do intervalo do expediente -Geralmente Almoço	12:00
LABEL_ATIVIDADE_CHAMADO	Label que será apresentado para o campo Atividade	Atividade
LABEL_SUBTIPO_CHAMADO	Label que será apresentado para o campo SubTipo de Chamado	Subclasse
LABEL_TIPO_OCORRENCIA	Label que será apresentado para o campo Tipo de Ocorrência	Tipo
mailMailer	Mailer default do sistema (smtp, mail ou sendmail)	mail
mailSMTPHost	Nome ou Ip do host SMTP para envio de e-mails	N
mailSMTPPassword	Senha do usuário de autenticação SMTP	senha
mailSMTPUsername	Usuário para acesso ao servidor SMTP	usuario
MetaTaxaResolucaoImediata	Meta do indicador - Taxa de resolução imediata	50
MetaTaxaResolucaoIncidente	Meta do indicador - Resolução do incidente no tempo estipula	90
MetaTaxaResolucaoSolicitacao	Meta do indicador - Resolução de solicitações no tempo	70
MetaTempoMedioAtendimento	Meta do indicador - Tempo médio de atendimento	20
NOM_AREA_TI	Nome da área de TI - Apresentação em Relatórios	Coordenação Geral de Tecnologia da Informação
NOM_INSTITUICAO	Nome da instituição/Empresa onde o sistema está rodando	Nome da Organização
PATH_IMGS	Camainho das imagens do GESTAOTI	imagens/
QTD_MIN_REABERTURA	Qtd de minutos úteis onde a reabertura de chamado é possível	2400
remetenteEmailCEA	Nome do remetende dos e-mails do sistema	Coordenação Geral de Tecnologia da Informação
SEQ_ATIVIDADE_CHAMADO_TRANSPORTE_ESPECIAL	Atividade de chamado para transporte  perfil especial	277
SEQ_ATIVIDADE_CHAMADO_TRIAGEM_DUVIDA	Atividade atribuída por default às dúvidas de clientes	200
SEQ_ATIVIDADE_CHAMADO_TRIAGEM_DUVIDA_CAA	Atividade atribuída por default às dúvidas de clientes\n	251
SEQ_ATIVIDADE_CHAMADO_TRIAGEM_INCIDENTE	Atividade atribuída por default aos incidentes de clientes	199
SEQ_ATIVIDADE_CHAMADO_TRIAGEM_INCIDENTE_CAA	Atividade atribuída por default aos incidentes de clientes\n	250
SEQ_ATIVIDADE_CHAMADO_TRIAGEM_SOLICITACAO	Atividade atribuída por default às solicitações de clientes	180
SEQ_ATIVIDADE_CHAMADO_TRIAGEM_SOLICITACAO_CAA	Atividade atribuída por default às solicitações de clientes\n	249
SEQ_CENTRAL_ATIVIDADES_AUXILIARES	SEQ_CENTRAL_ATIVIDADES_AUXILIARES	2
SEQ_CENTRAL_TI	SEQ_CENTRAL_TI	1
SEQ_CLASSE_CHAMADO_AR_CONDICIONADO	SEQ_CLASSE_CHAMADO_AR_CONDICIONADO	17
SEQ_CLASSE_CHAMADO_AUDITORIO	SEQ_CLASSE_CHAMADO_AUDITORIO	18
SEQ_CLASSE_CHAMADO_CARIMBO	SEQ_CLASSE_CHAMADO_CARIMBO	19
SEQ_CLASSE_CHAMADO_CELULAR	SEQ_CLASSE_CHAMADO_CELULAR	20
SEQ_CLASSE_CHAMADO_CHAVEIRO	SEQ_CLASSE_CHAMADO_CHAVEIRO	21
SEQ_CLASSE_CHAMADO_TRANSPORTE	SEQ_CLASSE_CHAMADO_TRANSPORTE	22
SEQ_TIPO_OCORRENCIA_DUVIDA	Tipo de chamado "Dúvida"	3
SEQ_TIPO_OCORRENCIA_IMPROCEDENTE	Tipo de chamado "Improcedente"	3
SEQ_TIPO_OCORRENCIA_INCIDENTE	Tipo de chamado "Incidente"	1
SEQ_TIPO_OCORRENCIA_SOLICITACAO	Tipo de chamado "Solicitação"	2
SEQ_TIPO_TIPO_CHAMADO_SLA	Classes de chamados consideradores para o cálculo do SLA	1,7, 8, 9, 3, 5, 11, 12
titulo	Título das páginas do sistema	 .:: Coordenação Geral de Tecnologia da Informação ::.
USUARIO_SUPER_ADMIN	Usuario que visualiza varias centrais de atendimento	admin
vPathPadraoCEA	Caminho do core do sistema para acesso pelo CAU	../gestaoti/
vPathUploadArquivos	Caminho para upload de anexos	/opt/lampp/htdocs/EMBRATUR/cau/anexos/
COD_PERFIL_USUARIO_ADMINISTRACAO	Código do perfil do usuário administrador	2
COD_SITUACAO_Aguardando_Avaliacao	Código da situação "Aguardando Avaliação"	8
COD_SITUACAO_Aguardando_Planejamento	Código da situação "Aguardando Planejamento"	6
COD_SITUACAO_Aguardando_Triagem	Código da situação "Aguardando Triagem"	1
COD_SITUACAO_Cancelado	Código da situação "Cancelado"	9
COD_SITUACAO_Aguardando_Atendimento	Código da situação "Aguardando Atendimento"	2
labelTopo	Texto exibido no topo do sistema	Central de Gestão de Tecnologia da Informação
ldapport	Porta de conexão no servidor LDAP para autenticação dos usuá	389
ldap_server	Servidor LDAP para autenticação dos usuários - Se não usado coloque "N"	N
COD_SITUACAO_Aguardando_Aprovacao	Código da situação "Aguardando Aprovação"\n	10
LABEL_TIPO_CHAMADO	Label que será apresentado para o campo Tipo de Chamado	Classe
\.


--
-- TOC entry 2583 (class 0 OID 24612)
-- Dependencies: 195
-- Data for Name: patrimonio_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY patrimonio_chamado (seq_chamado, num_patrimonio) FROM stdin;
\.


--
-- TOC entry 2584 (class 0 OID 24615)
-- Dependencies: 196
-- Data for Name: perfil_acesso; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY perfil_acesso (seq_perfil_acesso, nom_perfil_acesso) FROM stdin;
2	Administrador
1	Colaborador
4	Gestor de TI
5	Coordenador de TI
6	Gerente de Mudanças
7	Executor de Mudanças
8	Requisitante de Mudanças
9	Gerente de Configuração
\.


--
-- TOC entry 2585 (class 0 OID 24618)
-- Dependencies: 197
-- Data for Name: perfil_recurso_ti; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY perfil_recurso_ti (seq_perfil_recurso_ti, nom_perfil_recurso_ti, val_hora) FROM stdin;
2	Técnico de Suporte	0.00
4	Gestor de Contrato	0.00
\.


--
-- TOC entry 2586 (class 0 OID 24621)
-- Dependencies: 198
-- Data for Name: pessoa; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY pessoa (seq_pessoa, nom_login_rede, nome, nome_abreviado, nome_guerra, des_email, num_ddd, num_telefone, num_voip, des_status, des_senha, seq_unidade_organizacional, seq_pessoa_superior_hierarquico, seq_tipo_funcao_administrativa, flg_cadastro_atualizado) FROM stdin;
9	teste@teste.gov.br	Teste 1	Teste	Teste	teste@teste.gov.br	\N	\N	\N	A	IlQbUgDFHJ	6	\N	\N	N
1	admin	Administrador do sistema	Admin	Admin	admin@planejamento.gov.br	61	3436-3029	\N	A	afqovJLNPR	1	\N	\N	S
\.


--
-- TOC entry 2587 (class 0 OID 24629)
-- Dependencies: 199
-- Data for Name: prioridade; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY prioridade (seq_prioridade, nom_prioridade) FROM stdin;
1	Crítico
2	Muito crítico
3	Médio
4	Baixo
\.


--
-- TOC entry 2588 (class 0 OID 24632)
-- Dependencies: 200
-- Data for Name: prioridade_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY prioridade_chamado (seq_prioridade_chamado, dsc_prioridade_chamado) FROM stdin;
2	Urgente
3	Padrão
4	Baixo
1	Preferencial
\.


--
-- TOC entry 2589 (class 0 OID 24635)
-- Dependencies: 201
-- Data for Name: rdm; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY rdm (titulo, justificativa, impacto_nao_executar, nome_resp_checklist, seq_rdm, ddd_telefone_resp_checklist, numero_telefone_resp_checklist, num_matricula_solicitante, situacao_atual, data_hora_prevista_execucao, data_hora_inicio_execucao, data_hora_fim_execucao, tipo, observacao, data_hora_abertura, data_hora_ultima_atualizacao, email_resp_checklist) FROM stdin;
\.


--
-- TOC entry 2590 (class 0 OID 24641)
-- Dependencies: 202
-- Data for Name: rdm_template; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY rdm_template (titulo, justificativa, impacto_nao_executar, nome_resp_checklist, seq_rdm_template, ddd_telefone_resp_checklist, numero_telefone_resp_checklist, observacao, email_resp_checklist, seq_rdm_origem) FROM stdin;
\.


--
-- TOC entry 2591 (class 0 OID 24647)
-- Dependencies: 203
-- Data for Name: recurso_ti; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY recurso_ti (num_matricula_recurso, seq_equipe_ti, seq_perfil_recurso_ti, seq_perfil_acesso, seq_area_atuacao) FROM stdin;
1	1	2	1	\N
\.


--
-- TOC entry 2592 (class 0 OID 24650)
-- Dependencies: 204
-- Data for Name: recurso_ti_x_perfil_acesso; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY recurso_ti_x_perfil_acesso (num_matricula_recurso, seq_perfil_acesso) FROM stdin;
1	2
1	5
1	7
1	9
1	6
1	4
1	8
\.


--
-- TOC entry 2593 (class 0 OID 24653)
-- Dependencies: 205
-- Data for Name: relac_item_configuracao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY relac_item_configuracao (seq_relac_item_configuracao, seq_item_configuracao_pai, seq_tipo_relac_item_config, seq_item_configuracao_filho, seq_servidor) FROM stdin;
\.


--
-- TOC entry 2594 (class 0 OID 24656)
-- Dependencies: 206
-- Data for Name: responsavel_unidade_organizacional; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY responsavel_unidade_organizacional (seq_unidade_organizacional, seq_pessoa) FROM stdin;
6	1
\.


--
-- TOC entry 2595 (class 0 OID 24795)
-- Dependencies: 274
-- Data for Name: servidor; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY servidor (seq_servidor, seq_sistema_operacional, seq_marca_hardware, num_patrimonio, num_ip, nom_servidor, nom_modelo, dsc_servidor, dsc_localizacao, dsc_processador, txt_observacao, dat_criacao, dat_alteracao) FROM stdin;
\.


--
-- TOC entry 2596 (class 0 OID 24801)
-- Dependencies: 275
-- Data for Name: sistema_operacional; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY sistema_operacional (seq_sistema_operacional, nom_sistema_operacional) FROM stdin;
3	Windows 2000 Server
4	Windows 2003 Server
5	Red Hat Enterprise Linux
7	Windows NT
8	CentOS
9	Fedora Core
10	Debian
\.


--
-- TOC entry 2597 (class 0 OID 24804)
-- Dependencies: 276
-- Data for Name: situacao_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY situacao_chamado (seq_situacao_chamado, dsc_situacao_chamado) FROM stdin;
1	Aguardando Atendimento de 1º Nível
2	Aguardando Atendimento - Nível Especializado
3	Em Atendimento - Nível Especializado
4	Encerrada
5	Suspensa
8	Aguardando Avaliação
9	Cancelado
7	Contingenciado
6	Aguardando Planejamento
10	Aguardando Aprovação
\.


--
-- TOC entry 2598 (class 0 OID 24807)
-- Dependencies: 277
-- Data for Name: situacao_rdm; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY situacao_rdm (seq_rdm, situacao, data_hora, observacao, seq_situacao_rdm, num_matricula_recurso) FROM stdin;
\.


--
-- TOC entry 2599 (class 0 OID 24813)
-- Dependencies: 278
-- Data for Name: software_banco_de_dados; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY software_banco_de_dados (seq_item_configuracao, seq_banco_de_dados) FROM stdin;
\.


--
-- TOC entry 2600 (class 0 OID 24816)
-- Dependencies: 279
-- Data for Name: software_linguagem_programacao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY software_linguagem_programacao (seq_item_configuracao, seq_linguagem_programacao) FROM stdin;
\.


--
-- TOC entry 2601 (class 0 OID 24819)
-- Dependencies: 280
-- Data for Name: status_software; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY status_software (seq_status_software, nom_status_software) FROM stdin;
1	Em desenvolvimento
2	Em produçào
3	Em homologação
\.


--
-- TOC entry 2602 (class 0 OID 24822)
-- Dependencies: 281
-- Data for Name: subtipo_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY subtipo_chamado (seq_subtipo_chamado, seq_tipo_chamado, dsc_subtipo_chamado, flg_atendimento_externo) FROM stdin;
3	1	MICROCOMPUTADORES	S
11	1	SISTEMAS ADMINISTRATIVOS DO GOVERNO FEDERAL	S
70	15	CLASSIFICAÇÃO INDEFINIDA	S
71	5	GESTÃO DE RISCO	N
12	5	ANÁLISE DE VULNERABILIDADE	S
1	2	PROCESSOS/AUDITORIA	N
2	2	TESTES	N
72	7	TELEFONIA VOIP	S
13	3	SUPORTE A SISTEMA APLICATIVOS E CORPORATIVOS	S
73	7	IMPRESSORAS, SCANNERS, DATASHOW E OUTROS DISPOSITIVOS	S
74	8	SUPORTE A RECURSOS DE REDE   	S
4	1	SERVIÇOS DE REDE E PERIFÉRICOS	S
15	7	DESKTOPS (MICROS E NOTEBOOKS)	S
16	7	SUPORTE À SOFTWARES  	S
17	7	SUPORTE A RECURSOS DE REDE	S
18	7	CONTAS DOS USUÁRIOS 	S
75	14	SUPORTE A SISTEMA APLICATIVOS E CORPORATIVOS	N
20	7	DIVULGAÇÃO E INFORMES	S
25	13	APOIO A EVENTOS	N
22	7	SISTEMAS ADMINISTRATIVOS DO GOVERNO FEDERAL	S
23	7	SERVIÇOS DE VOZ, DADOS E IMAGENS	S
26	9	BASES DE DADOS (MIGRAÇÃO, TUNNING, CRIAÇÃO, ETC)	N
27	9	PRIVILÉGIOS NAS BASES	N
28	9	SERVIÇOS ASSOCIADOS A BASES DE DADOS	N
29	9	CONSULTAS EM BASES DE DADOS	N
30	9	SINCRONIZAÇÃO DE MODELOS DE DADOS COM AS BASES DE DADOS	N
31	9	SERVIÇOS ASSOCIADOS A  MODELOS DE DADOS	N
32	9	REPLICAÇÃO DE BASES DE DADOS	N
33	9	ALTERAÇÃO DE OBJETOS DAS BASES	N
78	9	SERVIÇOS ASSOCIADOS A ADMINISTRAÇÃO DE DADOS(AD)	N
77	14	SUPORTE SERVIÇOS RELACIONADOS COM BANCO DE DADOS	N
35	8	SERVIÇOS DE VOZ, DADOS E IMAGENS	S
36	8	DOCUMENTAÇÃO	S
38	8	PROSPECÇÃO E PLANEJAMENTO	S
6	8	SUPORTE TÉCNICO	S
7	8	INFRAESTRUTURA, GERÊNCIA E OPERAÇÃO DE REDE	N
40	5	ANÁLISE FORENSE 	N
41	5	CONTROLE DE ACESSO	S
42	5	FIREWALL	N
43	5	POLÍTICAS DE SEGURANÇA DA INFORMAÇÃO	N
44	5	SERVIDOR DE LOG	N
45	5	SISTEMA DE DETECÇÃO DE INTRUSÃO DE REDE	N
46	5	TRATAMENTO DE INCIDENTES DE SEGURANÇA	S
47	5	CONTROLE DE CONTEÚDO	S
48	5	ACESSO VPN	S
49	5	CERTIFICADOS DIGITAIS	S
50	5	HARDENING DE SERVIDORES	S
51	11	ADMINISTRAÇÃO DE SERVIDORES 	N
52	11	ANTIVÍRUS	N
53	11	BACKUP E BACKUP OFF-SITE	N
54	11	REPOSITÓRIO WINDOWS 	N
55	11	SERVIÇO DE TERMINAL	N
56	11	SISTEMA DE ARMAZENAMENTO	N
57	11	VIRTUALIZAÇÃO	N
58	11	MAINFRAME	N
59	11	GESTÃO DOS USUÁRIOS DA REDE	N
60	11	GERÊNCIA DOS SERVIÇOS DE IMPRESSÃO	N
61	11	SERVIDOR DE ARQUIVOS	N
62	11	SERVIDORES DE APLICAÇÃO	N
63	11	SUPORTE TÉCNICO	N
64	12	SERVIÇOS DE OPERAÇÃO	N
65	12	MONITORAMENTO DA REDE	N
66	14	REPASSES PARA PROJETOS	N
67	14	REPASSES PARA SUPORTE 	N
68	14	REPASSES PARA SERVIÇOS DE TELEFONIA E REDE	N
69	14	CONSERTO DE EQUIPAMENTOS	N
79	16	CLASSIFICAÇÃO INDEFINIDA	S
81	18	AUDITÓRIO	S
82	19	CARIMBO	S
83	20	CELULAR	S
84	21	CHAVEIRO	S
85	22	TRANSPORTE	S
80	17	AR-CONDICIONADO	S
\.


--
-- TOC entry 2603 (class 0 OID 24826)
-- Dependencies: 282
-- Data for Name: time_sheet; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY time_sheet (seq_time_sheet, seq_chamado, num_matricula, dth_inicio, dth_fim) FROM stdin;
1	1	1	2011-07-10 18:52:12	2011-07-10 18:52:12
2	1	1	2011-07-10 18:52:12	2011-07-10 18:52:29
3	2	1	2011-07-10 18:52:32	2011-07-10 18:52:33
4	2	1	2011-07-10 18:52:33	2011-07-10 18:52:58
5	2	1	2011-07-10 20:00:33	2011-07-10 20:01:03
6	2	1	2011-07-10 20:01:13	2011-07-10 20:01:21
7	3	1	2011-08-11 11:19:45	2011-08-11 11:19:46
8	3	1	2011-08-11 11:19:46	2011-08-11 11:19:52
9	3	1	2011-08-11 11:25:37	2011-08-11 11:25:37
10	3	1	2011-08-11 11:25:37	2011-08-11 11:46:46
11	3	1	2011-08-11 15:46:02	2011-08-11 15:46:02
12	3	1	2011-08-11 15:46:02	2011-08-11 15:48:26
13	3	1	2011-08-11 16:16:39	2011-08-11 16:16:48
14	3	1	2011-08-11 17:25:28	2011-08-11 17:25:47
15	3	1	2011-08-11 17:25:56	2011-08-11 17:26:05
16	3	1	2011-08-11 17:26:14	2011-08-11 17:26:22
17	4	1	2011-08-23 14:31:10	2011-08-23 14:31:11
18	4	1	2011-08-23 14:31:11	2011-08-23 14:31:27
19	4	1	2011-08-23 14:31:33	2011-08-23 14:32:32
\.


--
-- TOC entry 2604 (class 0 OID 24829)
-- Dependencies: 283
-- Data for Name: tipo_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY tipo_chamado (seq_tipo_chamado, dsc_tipo_chamado, flg_atendimento_externo, seq_central_atendimento, flg_utilizado_sla) FROM stdin;
15	CLASSIFICAÇÃO INDEFINIDA	S	1	\N
2	QUALIDADE	S	1	\N
14	REPASSE FORNECEDOR EXTERNO	N	1	\N
1	SUPORTE TÉCNICO	S	1	S
5	SEGURANÇA	S	1	S
3	SISTEMAS APLICATIVOS E CORPORATIVOS	S	1	S
7	MICROCOMPUTADORES, PERIFÉRICOS E SOFTWARES BÁSICOS	S	1	S
9	BANCO DE DADOS	N	1	S
11	SERVIDORES 	N	1	S
12	OPERACÃO E MONITORAMENTO	N	1	S
18	AUDITÓRIO	S	2	S
19	CARIMBO	S	2	S
20	CELULAR	S	2	S
21	CHAVEIRO	S	2	S
22	TRANSPORTE	S	2	S
23	testes	N	\N	N
24	testes	S	\N	N
25	testes	S	\N	N
26	teste	S	\N	N
17	AR-CONDICIONADO	S	2	S
27	teste	N	\N	S
8	INFRAESTRUTURA/SERVIÇO DE REDE	S	1	S
28	TESTE	S	2	N
16	CLASSIFICAÇÃO INDEFINIDA	S	2	N
13	APOIO A EVENTOS	S	1	S
\.


--
-- TOC entry 2605 (class 0 OID 24833)
-- Dependencies: 284
-- Data for Name: tipo_disponibilidade; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY tipo_disponibilidade (seq_tipo_disponibilidade, nom_tipo_disponibilidade) FROM stdin;
1	24 X 7
2	8 X 5
3	1 vez por semana
4	1 vez por mês
5	Eventualmente
\.


--
-- TOC entry 2606 (class 0 OID 24836)
-- Dependencies: 285
-- Data for Name: tipo_funcao_administrativa; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY tipo_funcao_administrativa (seq_tipo_funcao_administrativa, nom_tipo_funcao_administrativa) FROM stdin;
2	DAS 2
3	DAS 3
4	DAS 1
5	DAS 4
7	DAS 5
\.


--
-- TOC entry 2607 (class 0 OID 24839)
-- Dependencies: 286
-- Data for Name: tipo_item_configuracao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY tipo_item_configuracao (seq_tipo_item_configuracao, nom_tipo_item_configuracao) FROM stdin;
2	Sistema de informação
4	Servidor
1	Patrimônio
\.


--
-- TOC entry 2608 (class 0 OID 24842)
-- Dependencies: 287
-- Data for Name: tipo_ocorrencia; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY tipo_ocorrencia (seq_tipo_ocorrencia, nom_tipo_ocorrencia) FROM stdin;
1	Incidente (Erros, problemas, indisponibilidades e afins)
3	Dúvida
2	Solicitação (Pedidos, requisições)
5	Dúvida
\.


--
-- TOC entry 2609 (class 0 OID 24845)
-- Dependencies: 288
-- Data for Name: tipo_relac_item_configuracao; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY tipo_relac_item_configuracao (seq_tipo_relac_item_config, nom_tipo_relac_item_config) FROM stdin;
1	Servidor de aplicação
2	Servidor de Banco de dados
3	Servidor de arquivos
4	Servidor de WEB Services
5	Consulta ao banco de dados
6	WEB Service
7	Integração batch
\.


--
-- TOC entry 2610 (class 0 OID 24848)
-- Dependencies: 289
-- Data for Name: tipo_software; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY tipo_software (seq_tipo_software, nom_tipo_software) FROM stdin;
1	Legado
2	Squadra
\.


--
-- TOC entry 2611 (class 0 OID 24851)
-- Dependencies: 290
-- Data for Name: unidade_medida_software; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY unidade_medida_software (seq_unidade_medida_software, nom_unidade_medida_software) FROM stdin;
1	APF
2	NESMA
\.


--
-- TOC entry 2612 (class 0 OID 24854)
-- Dependencies: 291
-- Data for Name: unidade_organizacional; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY unidade_organizacional (seq_unidade_organizacional, nom_unidade_organizacional, seq_unidade_organizacional_pai, sgl_unidade_organizacional) FROM stdin;
1	Unidade padrão	\N	UP
6	Unidade de teste 2	1	UT3
\.


--
-- TOC entry 2613 (class 0 OID 24860)
-- Dependencies: 292
-- Data for Name: vinculo_chamado; Type: TABLE DATA; Schema: gestaoti; Owner: gestaoti
--

COPY vinculo_chamado (seq_chamado_master, seq_chamado_filho, num_matricula, dth_vinculacao) FROM stdin;
\.


--
-- TOC entry 2215 (class 2606 OID 24877)
-- Dependencies: 142 142
-- Name: agendas_entry_pkey; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY agendas_entry
    ADD CONSTRAINT agendas_entry_pkey PRIMARY KEY (seq_agendas_entry_id);


--
-- TOC entry 2213 (class 2606 OID 24879)
-- Dependencies: 141 141
-- Name: pk_acao_contingenciamento; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY acao_contingenciamento
    ADD CONSTRAINT pk_acao_contingenciamento PRIMARY KEY (seq_acao_contingenciamento);


--
-- TOC entry 2220 (class 2606 OID 24881)
-- Dependencies: 143 143
-- Name: pk_anexo_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY anexo_chamado
    ADD CONSTRAINT pk_anexo_chamado PRIMARY KEY (seq_anexo_chamado);


--
-- TOC entry 2222 (class 2606 OID 24883)
-- Dependencies: 144 144
-- Name: pk_anexo_rdm; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY anexo_rdm
    ADD CONSTRAINT pk_anexo_rdm PRIMARY KEY (seq_anexo_rdm);


--
-- TOC entry 2225 (class 2606 OID 24885)
-- Dependencies: 145 145
-- Name: pk_aprovacao_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY aprovacao_chamado
    ADD CONSTRAINT pk_aprovacao_chamado PRIMARY KEY (seq_aprovacao_chamado);


--
-- TOC entry 2227 (class 2606 OID 24887)
-- Dependencies: 146 146
-- Name: pk_aprovacao_chamado_departamento; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY aprovacao_chamado_departamento
    ADD CONSTRAINT pk_aprovacao_chamado_departamento PRIMARY KEY (seq_aprovacao_chamado_departamento);


--
-- TOC entry 2229 (class 2606 OID 24889)
-- Dependencies: 147 147
-- Name: pk_aprovacao_chamado_superior; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY aprovacao_chamado_superior
    ADD CONSTRAINT pk_aprovacao_chamado_superior PRIMARY KEY (seq_aprovacao_chamado_superior);


--
-- TOC entry 2231 (class 2606 OID 24891)
-- Dependencies: 148 148
-- Name: pk_area_atuacao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY area_atuacao
    ADD CONSTRAINT pk_area_atuacao PRIMARY KEY (seq_area_atuacao);


--
-- TOC entry 2233 (class 2606 OID 24893)
-- Dependencies: 149 149 149
-- Name: pk_area_envolvida; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY area_envolvida
    ADD CONSTRAINT pk_area_envolvida PRIMARY KEY (seq_item_configuracao, cod_uor);


--
-- TOC entry 2235 (class 2606 OID 24895)
-- Dependencies: 150 150
-- Name: pk_area_externa; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY area_externa
    ADD CONSTRAINT pk_area_externa PRIMARY KEY (seq_area_externa);


--
-- TOC entry 2237 (class 2606 OID 24897)
-- Dependencies: 151 151 151
-- Name: pk_area_externa_envolvida; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY area_externa_envolvida
    ADD CONSTRAINT pk_area_externa_envolvida PRIMARY KEY (seq_area_externa, seq_item_configuracao);


--
-- TOC entry 2240 (class 2606 OID 24899)
-- Dependencies: 152 152
-- Name: pk_atendimento_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY atendimento_chamado
    ADD CONSTRAINT pk_atendimento_chamado PRIMARY KEY (seq_atendimento_chamado);


--
-- TOC entry 2244 (class 2606 OID 24901)
-- Dependencies: 153 153
-- Name: pk_atividade_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY atividade_chamado
    ADD CONSTRAINT pk_atividade_chamado PRIMARY KEY (seq_atividade_chamado);


--
-- TOC entry 2246 (class 2606 OID 24903)
-- Dependencies: 154 154
-- Name: pk_atividade_rb_rdm; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY atividade_rb_rdm
    ADD CONSTRAINT pk_atividade_rb_rdm PRIMARY KEY (seq_atividade_rb_rdm);


--
-- TOC entry 2248 (class 2606 OID 24905)
-- Dependencies: 155 155
-- Name: pk_atividade_rb_rdm_template; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY atividade_rb_rdm_template
    ADD CONSTRAINT pk_atividade_rb_rdm_template PRIMARY KEY (seq_atividade_rb_rdm_template);


--
-- TOC entry 2250 (class 2606 OID 24907)
-- Dependencies: 156 156
-- Name: pk_atividade_rdm; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY atividade_rdm
    ADD CONSTRAINT pk_atividade_rdm PRIMARY KEY (seq_atividade_rdm);


--
-- TOC entry 2252 (class 2606 OID 24909)
-- Dependencies: 157 157
-- Name: pk_atividade_rdm_template; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY atividade_rdm_template
    ADD CONSTRAINT pk_atividade_rdm_template PRIMARY KEY (seq_atividade_rdm_template);


--
-- TOC entry 2257 (class 2606 OID 24911)
-- Dependencies: 158 158
-- Name: pk_atribuicao_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY atribuicao_chamado
    ADD CONSTRAINT pk_atribuicao_chamado PRIMARY KEY (seq_atribuicao_chamado);


--
-- TOC entry 2259 (class 2606 OID 24913)
-- Dependencies: 159 159
-- Name: pk_avaliacao_atendimento; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY avaliacao_atendimento
    ADD CONSTRAINT pk_avaliacao_atendimento PRIMARY KEY (seq_avaliacao_atendimento);


--
-- TOC entry 2261 (class 2606 OID 24915)
-- Dependencies: 160 160
-- Name: pk_banco_de_dados; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY banco_de_dados
    ADD CONSTRAINT pk_banco_de_dados PRIMARY KEY (seq_banco_de_dados);


--
-- TOC entry 2263 (class 2606 OID 24917)
-- Dependencies: 161 161
-- Name: pk_central_atendimento; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY central_atendimento
    ADD CONSTRAINT pk_central_atendimento PRIMARY KEY (seq_central_atendimento);


--
-- TOC entry 2271 (class 2606 OID 24919)
-- Dependencies: 162 162
-- Name: pk_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT pk_chamado PRIMARY KEY (seq_chamado);


--
-- TOC entry 2273 (class 2606 OID 24921)
-- Dependencies: 163 163 163
-- Name: pk_chamdo_rdm; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY chamado_rdm
    ADD CONSTRAINT pk_chamdo_rdm PRIMARY KEY (seq_rdm, seq_chamado);


--
-- TOC entry 2276 (class 2606 OID 24923)
-- Dependencies: 164 164
-- Name: pk_correcao_time_sheet; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY correcao_time_sheet
    ADD CONSTRAINT pk_correcao_time_sheet PRIMARY KEY (seq_time_sheet);


--
-- TOC entry 2278 (class 2606 OID 24925)
-- Dependencies: 165 165
-- Name: pk_criticidade; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY criticidade
    ADD CONSTRAINT pk_criticidade PRIMARY KEY (seq_criticidade);


--
-- TOC entry 2281 (class 2606 OID 24927)
-- Dependencies: 166 166 166
-- Name: pk_destino_triagem; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY destino_triagem
    ADD CONSTRAINT pk_destino_triagem PRIMARY KEY (seq_equipe_ti, cod_dependencia);


--
-- TOC entry 2283 (class 2606 OID 24929)
-- Dependencies: 167 167
-- Name: pk_edificacao_infraero; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY edificacao
    ADD CONSTRAINT pk_edificacao_infraero PRIMARY KEY (seq_edificacao);


--
-- TOC entry 2285 (class 2606 OID 24931)
-- Dependencies: 168 168
-- Name: pk_equipe_atribuicao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY equipe_atribuicao
    ADD CONSTRAINT pk_equipe_atribuicao PRIMARY KEY (seq_equipe_atribuicao);


--
-- TOC entry 2287 (class 2606 OID 24933)
-- Dependencies: 169 169 169
-- Name: pk_equipe_envolvida; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY equipe_envolvida
    ADD CONSTRAINT pk_equipe_envolvida PRIMARY KEY (num_matricula_recurso, seq_item_configuracao);


--
-- TOC entry 2289 (class 2606 OID 24935)
-- Dependencies: 170 170 170
-- Name: pk_equipe_servidor; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY equipe_servidor
    ADD CONSTRAINT pk_equipe_servidor PRIMARY KEY (seq_servidor, num_matricula_recurso);


--
-- TOC entry 2291 (class 2606 OID 24937)
-- Dependencies: 171 171
-- Name: pk_equipe_ti; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY equipe_ti
    ADD CONSTRAINT pk_equipe_ti PRIMARY KEY (seq_equipe_ti);


--
-- TOC entry 2294 (class 2606 OID 24939)
-- Dependencies: 172 172
-- Name: pk_etapa_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY etapa_chamado
    ADD CONSTRAINT pk_etapa_chamado PRIMARY KEY (seq_etapa_chamado);


--
-- TOC entry 2296 (class 2606 OID 24941)
-- Dependencies: 173 173
-- Name: pk_fase_item_configuracao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY fase_item_configuracao
    ADD CONSTRAINT pk_fase_item_configuracao PRIMARY KEY (seq_fase_item_configuracao);


--
-- TOC entry 2298 (class 2606 OID 24943)
-- Dependencies: 174 174
-- Name: pk_fase_projeto; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY fase_projeto
    ADD CONSTRAINT pk_fase_projeto PRIMARY KEY (seq_fase_projeto);


--
-- TOC entry 2302 (class 2606 OID 24945)
-- Dependencies: 176 176
-- Name: pk_frequencia_manutencao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY frequencia_manutencao
    ADD CONSTRAINT pk_frequencia_manutencao PRIMARY KEY (seq_frequencia_manutencao);


--
-- TOC entry 2305 (class 2606 OID 24947)
-- Dependencies: 177 177
-- Name: pk_historico_acesso_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY historico_acesso_chamado
    ADD CONSTRAINT pk_historico_acesso_chamado PRIMARY KEY (seq_historico_acesso_chamado);


--
-- TOC entry 2310 (class 2606 OID 24949)
-- Dependencies: 178 178
-- Name: pk_historico_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY historico_chamado
    ADD CONSTRAINT pk_historico_chamado PRIMARY KEY (seq_historico_chamado);


--
-- TOC entry 2312 (class 2606 OID 24951)
-- Dependencies: 179 179
-- Name: pk_informativo; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY informativo
    ADD CONSTRAINT pk_informativo PRIMARY KEY (seq_informativo);


--
-- TOC entry 2315 (class 2606 OID 24953)
-- Dependencies: 180 180 180
-- Name: pk_informativo_publico; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY informativo_publico
    ADD CONSTRAINT pk_informativo_publico PRIMARY KEY (seq_informativo, cod_dependencia);


--
-- TOC entry 2318 (class 2606 OID 24955)
-- Dependencies: 181 181
-- Name: pk_inoperancia_item_configuracao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY inoperancia_item_configuracao
    ADD CONSTRAINT pk_inoperancia_item_configuracao PRIMARY KEY (seq_inoperancia_item_config);


--
-- TOC entry 2332 (class 2606 OID 24957)
-- Dependencies: 183 183
-- Name: pk_item_configuracao_software; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY item_configuracao_software
    ADD CONSTRAINT pk_item_configuracao_software PRIMARY KEY (seq_item_configuracao);


--
-- TOC entry 2334 (class 2606 OID 24959)
-- Dependencies: 184 184 184
-- Name: pk_janela_mudacao_item_configuracao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY janela_mudacao_item_configuracao
    ADD CONSTRAINT pk_janela_mudacao_item_configuracao PRIMARY KEY (seq_janela_mudanca, seq_item_configuracao);


--
-- TOC entry 2336 (class 2606 OID 24961)
-- Dependencies: 185 185
-- Name: pk_janela_mudanca; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY janela_mudanca
    ADD CONSTRAINT pk_janela_mudanca PRIMARY KEY (seq_janela_mudanca);


--
-- TOC entry 2338 (class 2606 OID 24963)
-- Dependencies: 186 186 186
-- Name: pk_janela_mudanca_servidor; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY janela_mudanca_servidor
    ADD CONSTRAINT pk_janela_mudanca_servidor PRIMARY KEY (seq_janela_mudanca, seq_servidor);


--
-- TOC entry 2340 (class 2606 OID 24965)
-- Dependencies: 187 187
-- Name: pk_linguagem_programacao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY linguagem_programacao
    ADD CONSTRAINT pk_linguagem_programacao PRIMARY KEY (seq_linguagem_programacao);


--
-- TOC entry 2343 (class 2606 OID 24967)
-- Dependencies: 188 188
-- Name: pk_localizacao_fisica; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY localizacao_fisica
    ADD CONSTRAINT pk_localizacao_fisica PRIMARY KEY (seq_localizacao_fisica);


--
-- TOC entry 2345 (class 2606 OID 24969)
-- Dependencies: 189 189
-- Name: pk_marca_hardware; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY marca_hardware
    ADD CONSTRAINT pk_marca_hardware PRIMARY KEY (seq_marca_hardware);


--
-- TOC entry 2349 (class 2606 OID 24971)
-- Dependencies: 190 190
-- Name: pk_menu; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY menu_acesso
    ADD CONSTRAINT pk_menu PRIMARY KEY (seq_menu_acesso);


--
-- TOC entry 2353 (class 2606 OID 24973)
-- Dependencies: 191 191 191
-- Name: pk_menu_perfil_acesso; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY menu_perfil_acesso
    ADD CONSTRAINT pk_menu_perfil_acesso PRIMARY KEY (seq_perfil_acesso, seq_menu_acesso);


--
-- TOC entry 2355 (class 2606 OID 24975)
-- Dependencies: 192 192
-- Name: pk_motivo_cancelamento; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY motivo_cancelamento
    ADD CONSTRAINT pk_motivo_cancelamento PRIMARY KEY (seq_motivo_cancelamento);


--
-- TOC entry 2357 (class 2606 OID 24977)
-- Dependencies: 193 193
-- Name: pk_motivo_suspencao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY motivo_suspencao
    ADD CONSTRAINT pk_motivo_suspencao PRIMARY KEY (seq_motivo_suspencao);


--
-- TOC entry 2359 (class 2606 OID 24979)
-- Dependencies: 194 194
-- Name: pk_parametro; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY parametro
    ADD CONSTRAINT pk_parametro PRIMARY KEY (cod_parametro);


--
-- TOC entry 2362 (class 2606 OID 24981)
-- Dependencies: 195 195 195
-- Name: pk_patrimonio_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY patrimonio_chamado
    ADD CONSTRAINT pk_patrimonio_chamado PRIMARY KEY (seq_chamado, num_patrimonio);


--
-- TOC entry 2364 (class 2606 OID 24983)
-- Dependencies: 196 196
-- Name: pk_perfil_acesso; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY perfil_acesso
    ADD CONSTRAINT pk_perfil_acesso PRIMARY KEY (seq_perfil_acesso);


--
-- TOC entry 2366 (class 2606 OID 24985)
-- Dependencies: 197 197
-- Name: pk_perfil_recurso_ti; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY perfil_recurso_ti
    ADD CONSTRAINT pk_perfil_recurso_ti PRIMARY KEY (seq_perfil_recurso_ti);


--
-- TOC entry 2373 (class 2606 OID 24987)
-- Dependencies: 198 198
-- Name: pk_pessoa; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT pk_pessoa PRIMARY KEY (seq_pessoa);


--
-- TOC entry 2325 (class 2606 OID 24989)
-- Dependencies: 182 182
-- Name: pk_prioridade; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY item_configuracao
    ADD CONSTRAINT pk_prioridade PRIMARY KEY (seq_item_configuracao);


--
-- TOC entry 2379 (class 2606 OID 24991)
-- Dependencies: 199 199
-- Name: pk_prioridade1; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY prioridade
    ADD CONSTRAINT pk_prioridade1 PRIMARY KEY (seq_prioridade);


--
-- TOC entry 2381 (class 2606 OID 24993)
-- Dependencies: 200 200
-- Name: pk_prioridade_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY prioridade_chamado
    ADD CONSTRAINT pk_prioridade_chamado PRIMARY KEY (seq_prioridade_chamado);


--
-- TOC entry 2383 (class 2606 OID 24995)
-- Dependencies: 201 201
-- Name: pk_rdm; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY rdm
    ADD CONSTRAINT pk_rdm PRIMARY KEY (seq_rdm);


--
-- TOC entry 2385 (class 2606 OID 24997)
-- Dependencies: 202 202
-- Name: pk_rdm_tamplate; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY rdm_template
    ADD CONSTRAINT pk_rdm_tamplate PRIMARY KEY (seq_rdm_template);


--
-- TOC entry 2388 (class 2606 OID 24999)
-- Dependencies: 203 203
-- Name: pk_recurso_ti; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY recurso_ti
    ADD CONSTRAINT pk_recurso_ti PRIMARY KEY (num_matricula_recurso);


--
-- TOC entry 2390 (class 2606 OID 25001)
-- Dependencies: 204 204 204
-- Name: pk_recurso_ti_x_perfil_acesso; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY recurso_ti_x_perfil_acesso
    ADD CONSTRAINT pk_recurso_ti_x_perfil_acesso PRIMARY KEY (num_matricula_recurso, seq_perfil_acesso);


--
-- TOC entry 2392 (class 2606 OID 25003)
-- Dependencies: 205 205
-- Name: pk_relac_item_configuracao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY relac_item_configuracao
    ADD CONSTRAINT pk_relac_item_configuracao PRIMARY KEY (seq_relac_item_configuracao);


--
-- TOC entry 2300 (class 2606 OID 25005)
-- Dependencies: 175 175
-- Name: pk_seq_feriado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY feriado
    ADD CONSTRAINT pk_seq_feriado PRIMARY KEY (seq_feriado);


--
-- TOC entry 2396 (class 2606 OID 25007)
-- Dependencies: 274 274
-- Name: pk_servidor; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY servidor
    ADD CONSTRAINT pk_servidor PRIMARY KEY (seq_servidor);


--
-- TOC entry 2398 (class 2606 OID 25009)
-- Dependencies: 275 275
-- Name: pk_sistema_operacional; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY sistema_operacional
    ADD CONSTRAINT pk_sistema_operacional PRIMARY KEY (seq_sistema_operacional);


--
-- TOC entry 2400 (class 2606 OID 25011)
-- Dependencies: 276 276
-- Name: pk_situacao_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY situacao_chamado
    ADD CONSTRAINT pk_situacao_chamado PRIMARY KEY (seq_situacao_chamado);


--
-- TOC entry 2402 (class 2606 OID 25013)
-- Dependencies: 277 277 277
-- Name: pk_situacao_rdm; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY situacao_rdm
    ADD CONSTRAINT pk_situacao_rdm PRIMARY KEY (seq_situacao_rdm, seq_rdm);


--
-- TOC entry 2404 (class 2606 OID 25015)
-- Dependencies: 278 278 278
-- Name: pk_software_banco_de_dados; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY software_banco_de_dados
    ADD CONSTRAINT pk_software_banco_de_dados PRIMARY KEY (seq_item_configuracao, seq_banco_de_dados);


--
-- TOC entry 2406 (class 2606 OID 25017)
-- Dependencies: 279 279 279
-- Name: pk_software_linguagem_programacao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY software_linguagem_programacao
    ADD CONSTRAINT pk_software_linguagem_programacao PRIMARY KEY (seq_item_configuracao, seq_linguagem_programacao);


--
-- TOC entry 2408 (class 2606 OID 25019)
-- Dependencies: 280 280
-- Name: pk_status_software; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY status_software
    ADD CONSTRAINT pk_status_software PRIMARY KEY (seq_status_software);


--
-- TOC entry 2411 (class 2606 OID 25021)
-- Dependencies: 281 281
-- Name: pk_subtipo_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY subtipo_chamado
    ADD CONSTRAINT pk_subtipo_chamado PRIMARY KEY (seq_subtipo_chamado);


--
-- TOC entry 2415 (class 2606 OID 25023)
-- Dependencies: 282 282
-- Name: pk_time_sheet; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY time_sheet
    ADD CONSTRAINT pk_time_sheet PRIMARY KEY (seq_time_sheet);


--
-- TOC entry 2417 (class 2606 OID 25025)
-- Dependencies: 283 283
-- Name: pk_tipo_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY tipo_chamado
    ADD CONSTRAINT pk_tipo_chamado PRIMARY KEY (seq_tipo_chamado);


--
-- TOC entry 2419 (class 2606 OID 25027)
-- Dependencies: 284 284
-- Name: pk_tipo_disponibilidade; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY tipo_disponibilidade
    ADD CONSTRAINT pk_tipo_disponibilidade PRIMARY KEY (seq_tipo_disponibilidade);


--
-- TOC entry 2421 (class 2606 OID 25029)
-- Dependencies: 285 285
-- Name: pk_tipo_funcao_administrativa; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY tipo_funcao_administrativa
    ADD CONSTRAINT pk_tipo_funcao_administrativa PRIMARY KEY (seq_tipo_funcao_administrativa);


--
-- TOC entry 2423 (class 2606 OID 25031)
-- Dependencies: 286 286
-- Name: pk_tipo_item_configuracao; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY tipo_item_configuracao
    ADD CONSTRAINT pk_tipo_item_configuracao PRIMARY KEY (seq_tipo_item_configuracao);


--
-- TOC entry 2425 (class 2606 OID 25033)
-- Dependencies: 287 287
-- Name: pk_tipo_ocorrencia; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY tipo_ocorrencia
    ADD CONSTRAINT pk_tipo_ocorrencia PRIMARY KEY (seq_tipo_ocorrencia);


--
-- TOC entry 2427 (class 2606 OID 25035)
-- Dependencies: 288 288
-- Name: pk_tipo_relac_item_config; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY tipo_relac_item_configuracao
    ADD CONSTRAINT pk_tipo_relac_item_config PRIMARY KEY (seq_tipo_relac_item_config);


--
-- TOC entry 2429 (class 2606 OID 25037)
-- Dependencies: 289 289
-- Name: pk_tipo_software; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY tipo_software
    ADD CONSTRAINT pk_tipo_software PRIMARY KEY (seq_tipo_software);


--
-- TOC entry 2431 (class 2606 OID 25039)
-- Dependencies: 290 290
-- Name: pk_unidade_medida_software; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY unidade_medida_software
    ADD CONSTRAINT pk_unidade_medida_software PRIMARY KEY (seq_unidade_medida_software);


--
-- TOC entry 2438 (class 2606 OID 25041)
-- Dependencies: 292 292 292
-- Name: pk_vinculo_chamado; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY vinculo_chamado
    ADD CONSTRAINT pk_vinculo_chamado PRIMARY KEY (seq_chamado_master, seq_chamado_filho);


--
-- TOC entry 2394 (class 2606 OID 25043)
-- Dependencies: 206 206 206
-- Name: responsavel_unidade_organizacional_pkey; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY responsavel_unidade_organizacional
    ADD CONSTRAINT responsavel_unidade_organizacional_pkey PRIMARY KEY (seq_unidade_organizacional, seq_pessoa);


--
-- TOC entry 2375 (class 2606 OID 25045)
-- Dependencies: 198 198
-- Name: uni_email; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT uni_email UNIQUE (des_email);


--
-- TOC entry 2377 (class 2606 OID 25047)
-- Dependencies: 198 198
-- Name: uni_login; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT uni_login UNIQUE (nom_login_rede);


--
-- TOC entry 2434 (class 2606 OID 25049)
-- Dependencies: 291 291
-- Name: unidade_organizacional_pkey; Type: CONSTRAINT; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

ALTER TABLE ONLY unidade_organizacional
    ADD CONSTRAINT unidade_organizacional_pkey PRIMARY KEY (seq_unidade_organizacional);


--
-- TOC entry 2216 (class 1259 OID 25050)
-- Dependencies: 142
-- Name: idxendtime2; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX idxendtime2 ON agendas_entry USING btree (end_time);


--
-- TOC entry 2217 (class 1259 OID 25051)
-- Dependencies: 142
-- Name: idxstarttime2; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX idxstarttime2 ON agendas_entry USING btree (start_time);


--
-- TOC entry 2218 (class 1259 OID 25052)
-- Dependencies: 143
-- Name: index_1; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_1 ON anexo_chamado USING btree (seq_chamado);


--
-- TOC entry 2264 (class 1259 OID 25053)
-- Dependencies: 162
-- Name: index_10; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_10 ON chamado USING btree (seq_prioridade_chamado);


--
-- TOC entry 2265 (class 1259 OID 25054)
-- Dependencies: 162
-- Name: index_11; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_11 ON chamado USING btree (seq_situacao_chamado);


--
-- TOC entry 2279 (class 1259 OID 25055)
-- Dependencies: 166
-- Name: index_12; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_12 ON destino_triagem USING btree (seq_equipe_ti);


--
-- TOC entry 2306 (class 1259 OID 25056)
-- Dependencies: 178
-- Name: index_15; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_15 ON historico_chamado USING btree (seq_motivo_suspencao);


--
-- TOC entry 2307 (class 1259 OID 25057)
-- Dependencies: 178
-- Name: index_16; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_16 ON historico_chamado USING btree (seq_situacao_chamado);


--
-- TOC entry 2308 (class 1259 OID 25058)
-- Dependencies: 178
-- Name: index_17; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_17 ON historico_chamado USING btree (seq_chamado);


--
-- TOC entry 2341 (class 1259 OID 25059)
-- Dependencies: 188
-- Name: index_18; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_18 ON localizacao_fisica USING btree (seq_edificacao);


--
-- TOC entry 2223 (class 1259 OID 25060)
-- Dependencies: 145
-- Name: index_2; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_2 ON aprovacao_chamado USING btree (seq_chamado);


--
-- TOC entry 2360 (class 1259 OID 25061)
-- Dependencies: 195
-- Name: index_20; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_20 ON patrimonio_chamado USING btree (seq_chamado);


--
-- TOC entry 2409 (class 1259 OID 25062)
-- Dependencies: 281
-- Name: index_23; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_23 ON subtipo_chamado USING btree (seq_tipo_chamado);


--
-- TOC entry 2412 (class 1259 OID 25063)
-- Dependencies: 282
-- Name: index_24; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_24 ON time_sheet USING btree (seq_chamado);


--
-- TOC entry 2313 (class 1259 OID 25064)
-- Dependencies: 180
-- Name: index_27; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_27 ON informativo_publico USING btree (seq_informativo);


--
-- TOC entry 2274 (class 1259 OID 25065)
-- Dependencies: 164
-- Name: index_28; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_28 ON correcao_time_sheet USING btree (seq_time_sheet);


--
-- TOC entry 2303 (class 1259 OID 25066)
-- Dependencies: 177
-- Name: index_29; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_29 ON historico_acesso_chamado USING btree (seq_chamado);


--
-- TOC entry 2238 (class 1259 OID 25067)
-- Dependencies: 152
-- Name: index_3; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_3 ON atendimento_chamado USING btree (seq_chamado);


--
-- TOC entry 2386 (class 1259 OID 25068)
-- Dependencies: 203
-- Name: index_30; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_30 ON recurso_ti USING btree (seq_equipe_ti);


--
-- TOC entry 2253 (class 1259 OID 25069)
-- Dependencies: 158
-- Name: index_4; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_4 ON atribuicao_chamado USING btree (seq_equipe_ti);


--
-- TOC entry 2254 (class 1259 OID 25070)
-- Dependencies: 158
-- Name: index_5; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_5 ON atribuicao_chamado USING btree (seq_situacao_chamado);


--
-- TOC entry 2255 (class 1259 OID 25071)
-- Dependencies: 158
-- Name: index_6; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_6 ON atribuicao_chamado USING btree (seq_chamado);


--
-- TOC entry 2241 (class 1259 OID 25072)
-- Dependencies: 153
-- Name: index_7; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_7 ON atividade_chamado USING btree (seq_subtipo_chamado);


--
-- TOC entry 2266 (class 1259 OID 25073)
-- Dependencies: 162
-- Name: index_9; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_9 ON chamado USING btree (seq_localizacao_fisica);


--
-- TOC entry 2267 (class 1259 OID 25074)
-- Dependencies: 162
-- Name: index_dth_abertura; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_dth_abertura ON chamado USING btree (dth_abertura);


--
-- TOC entry 2367 (class 1259 OID 25075)
-- Dependencies: 198
-- Name: index_email; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_email ON pessoa USING btree (des_email);


--
-- TOC entry 2368 (class 1259 OID 25076)
-- Dependencies: 198
-- Name: index_login; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_login ON pessoa USING btree (nom_login_rede);


--
-- TOC entry 2413 (class 1259 OID 25077)
-- Dependencies: 282
-- Name: index_num_matricula; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_num_matricula ON time_sheet USING btree (num_matricula);


--
-- TOC entry 2292 (class 1259 OID 25078)
-- Dependencies: 172
-- Name: index_seq_chamado_etapa_chamado; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_chamado_etapa_chamado ON etapa_chamado USING btree (seq_chamado);


--
-- TOC entry 2435 (class 1259 OID 25079)
-- Dependencies: 292
-- Name: index_seq_chamado_filho; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_chamado_filho ON vinculo_chamado USING btree (seq_chamado_filho);


--
-- TOC entry 2436 (class 1259 OID 25080)
-- Dependencies: 292
-- Name: index_seq_chamado_master; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_chamado_master ON vinculo_chamado USING btree (seq_chamado_master);


--
-- TOC entry 2319 (class 1259 OID 25081)
-- Dependencies: 182
-- Name: index_seq_criticidade; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_criticidade ON item_configuracao USING btree (seq_criticidade);


--
-- TOC entry 2320 (class 1259 OID 25082)
-- Dependencies: 182
-- Name: index_seq_equipe_ti; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_equipe_ti ON item_configuracao USING btree (seq_equipe_ti);


--
-- TOC entry 2242 (class 1259 OID 25083)
-- Dependencies: 153
-- Name: index_seq_equipe_ti_atividade_chamado; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_equipe_ti_atividade_chamado ON atividade_chamado USING btree (seq_equipe_ti);


--
-- TOC entry 2326 (class 1259 OID 25084)
-- Dependencies: 183
-- Name: index_seq_frequencia_manutencao; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_frequencia_manutencao ON item_configuracao_software USING btree (seq_frequencia_manutencao);


--
-- TOC entry 2268 (class 1259 OID 25085)
-- Dependencies: 162
-- Name: index_seq_item_configuracao; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_item_configuracao ON chamado USING btree (seq_item_configuracao);


--
-- TOC entry 2316 (class 1259 OID 25086)
-- Dependencies: 181
-- Name: index_seq_item_configuracao1; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_item_configuracao1 ON inoperancia_item_configuracao USING btree (seq_item_configuracao);


--
-- TOC entry 2350 (class 1259 OID 25087)
-- Dependencies: 191
-- Name: index_seq_menu_acesso; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_menu_acesso ON menu_perfil_acesso USING btree (seq_menu_acesso);


--
-- TOC entry 2346 (class 1259 OID 25088)
-- Dependencies: 190
-- Name: index_seq_menu_acesso_pai; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_menu_acesso_pai ON menu_acesso USING btree (seq_menu_acesso_pai);


--
-- TOC entry 2347 (class 1259 OID 25089)
-- Dependencies: 190
-- Name: index_seq_num_prioridade; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_num_prioridade ON menu_acesso USING btree (num_prioridade);


--
-- TOC entry 2351 (class 1259 OID 25090)
-- Dependencies: 191
-- Name: index_seq_perfil_acesso; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_perfil_acesso ON menu_perfil_acesso USING btree (seq_perfil_acesso);


--
-- TOC entry 2321 (class 1259 OID 25091)
-- Dependencies: 182
-- Name: index_seq_prioridade; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_prioridade ON item_configuracao USING btree (seq_prioridade);


--
-- TOC entry 2327 (class 1259 OID 25092)
-- Dependencies: 183
-- Name: index_seq_seq_item_configuracao; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_seq_item_configuracao ON item_configuracao_software USING btree (seq_item_configuracao);


--
-- TOC entry 2328 (class 1259 OID 25093)
-- Dependencies: 183
-- Name: index_seq_seq_status_software; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_seq_status_software ON item_configuracao_software USING btree (seq_status_software);


--
-- TOC entry 2329 (class 1259 OID 25094)
-- Dependencies: 183
-- Name: index_seq_seq_tipo_software; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_seq_tipo_software ON item_configuracao_software USING btree (seq_tipo_software);


--
-- TOC entry 2322 (class 1259 OID 25095)
-- Dependencies: 182
-- Name: index_seq_tipo_disponibilidade; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_tipo_disponibilidade ON item_configuracao USING btree (seq_tipo_disponibilidade);


--
-- TOC entry 2323 (class 1259 OID 25096)
-- Dependencies: 182
-- Name: index_seq_tipo_item_configuracao; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_tipo_item_configuracao ON item_configuracao USING btree (seq_tipo_item_configuracao);


--
-- TOC entry 2269 (class 1259 OID 25097)
-- Dependencies: 162
-- Name: index_seq_tipo_ocorrencia; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_tipo_ocorrencia ON chamado USING btree (seq_tipo_ocorrencia);


--
-- TOC entry 2330 (class 1259 OID 25098)
-- Dependencies: 183
-- Name: index_seq_unidade_medida_software; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_unidade_medida_software ON item_configuracao_software USING btree (seq_unidade_medida_software);


--
-- TOC entry 2369 (class 1259 OID 25099)
-- Dependencies: 198
-- Name: index_seq_unidade_organizacional; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_seq_unidade_organizacional ON pessoa USING btree (seq_unidade_organizacional);


--
-- TOC entry 2370 (class 1259 OID 25100)
-- Dependencies: 198
-- Name: index_superior_hierarquico; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_superior_hierarquico ON pessoa USING btree (seq_pessoa_superior_hierarquico);


--
-- TOC entry 2371 (class 1259 OID 25101)
-- Dependencies: 198
-- Name: index_tipo_funcao_administrativa; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_tipo_funcao_administrativa ON pessoa USING btree (seq_tipo_funcao_administrativa);


--
-- TOC entry 2432 (class 1259 OID 25102)
-- Dependencies: 291
-- Name: index_unidade_organizacional; Type: INDEX; Schema: gestaoti; Owner: gestaoti; Tablespace: 
--

CREATE INDEX index_unidade_organizacional ON unidade_organizacional USING btree (nom_unidade_organizacional);


--
-- TOC entry 2467 (class 2606 OID 25103)
-- Dependencies: 141 2212 162
-- Name: chamado_acao_contingenciamento_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_acao_contingenciamento_fkey FOREIGN KEY (seq_acao_contingenciamento) REFERENCES acao_contingenciamento(seq_acao_contingenciamento);


--
-- TOC entry 2468 (class 2606 OID 25108)
-- Dependencies: 162 2243 153
-- Name: chamado_seq_atividade_chamado_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_seq_atividade_chamado_fkey FOREIGN KEY (seq_atividade_chamado) REFERENCES atividade_chamado(seq_atividade_chamado);


--
-- TOC entry 2469 (class 2606 OID 25113)
-- Dependencies: 162 2258 159
-- Name: chamado_seq_avaliacao_atendimento_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_seq_avaliacao_atendimento_fkey FOREIGN KEY (seq_avaliacao_atendimento) REFERENCES avaliacao_atendimento(seq_avaliacao_atendimento);


--
-- TOC entry 2470 (class 2606 OID 25118)
-- Dependencies: 2258 162 159
-- Name: chamado_seq_avaliacao_conhecimento_tecnico_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_seq_avaliacao_conhecimento_tecnico_fkey FOREIGN KEY (seq_avaliacao_conhecimento_tecnico) REFERENCES avaliacao_atendimento(seq_avaliacao_atendimento);


--
-- TOC entry 2471 (class 2606 OID 25123)
-- Dependencies: 162 2258 159
-- Name: chamado_seq_avaliacao_postura_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_seq_avaliacao_postura_fkey FOREIGN KEY (seq_avaliacao_postura) REFERENCES avaliacao_atendimento(seq_avaliacao_atendimento);


--
-- TOC entry 2472 (class 2606 OID 25128)
-- Dependencies: 2258 162 159
-- Name: chamado_seq_avaliacao_tempo_espera_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_seq_avaliacao_tempo_espera_fkey FOREIGN KEY (seq_avaliacao_tempo_espera) REFERENCES avaliacao_atendimento(seq_avaliacao_atendimento);


--
-- TOC entry 2473 (class 2606 OID 25133)
-- Dependencies: 159 162 2258
-- Name: chamado_seq_avaliacao_tempo_solucao_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_seq_avaliacao_tempo_solucao_fkey FOREIGN KEY (seq_avaliacao_tempo_solucao) REFERENCES avaliacao_atendimento(seq_avaliacao_atendimento);


--
-- TOC entry 2474 (class 2606 OID 25138)
-- Dependencies: 182 162 2324
-- Name: chamado_seq_item_configuracao_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_seq_item_configuracao_fkey FOREIGN KEY (seq_item_configuracao) REFERENCES item_configuracao(seq_item_configuracao);


--
-- TOC entry 2475 (class 2606 OID 25143)
-- Dependencies: 162 192 2354
-- Name: chamado_seq_motivo_cancelamento_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_seq_motivo_cancelamento_fkey FOREIGN KEY (seq_motivo_cancelamento) REFERENCES motivo_cancelamento(seq_motivo_cancelamento);


--
-- TOC entry 2476 (class 2606 OID 25148)
-- Dependencies: 162 2424 287
-- Name: chamado_seq_tipo_ocorrencia_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT chamado_seq_tipo_ocorrencia_fkey FOREIGN KEY (seq_tipo_ocorrencia) REFERENCES tipo_ocorrencia(seq_tipo_ocorrencia);


--
-- TOC entry 2439 (class 2606 OID 25153)
-- Dependencies: 162 2270 143
-- Name: fk_anexo_ch_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY anexo_chamado
    ADD CONSTRAINT fk_anexo_ch_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2440 (class 2606 OID 25158)
-- Dependencies: 2382 201 144
-- Name: fk_anexo_rdm; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY anexo_rdm
    ADD CONSTRAINT fk_anexo_rdm FOREIGN KEY (seq_rdm) REFERENCES rdm(seq_rdm);


--
-- TOC entry 2441 (class 2606 OID 25163)
-- Dependencies: 145 162 2270
-- Name: fk_aprovaca_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY aprovacao_chamado
    ADD CONSTRAINT fk_aprovaca_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2443 (class 2606 OID 25168)
-- Dependencies: 162 147 2270
-- Name: fk_aprovaca_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY aprovacao_chamado_superior
    ADD CONSTRAINT fk_aprovaca_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2442 (class 2606 OID 25173)
-- Dependencies: 146 162 2270
-- Name: fk_aprovaca_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY aprovacao_chamado_departamento
    ADD CONSTRAINT fk_aprovaca_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2444 (class 2606 OID 25178)
-- Dependencies: 2270 152 162
-- Name: fk_atendime_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atendimento_chamado
    ADD CONSTRAINT fk_atendime_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2445 (class 2606 OID 25183)
-- Dependencies: 281 2410 153
-- Name: fk_atividad_reference_subtipo_; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_chamado
    ADD CONSTRAINT fk_atividad_reference_subtipo_ FOREIGN KEY (seq_subtipo_chamado) REFERENCES subtipo_chamado(seq_subtipo_chamado);


--
-- TOC entry 2446 (class 2606 OID 25188)
-- Dependencies: 287 153 2424
-- Name: fk_atividad_reference_tipo_ocorrencia; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_chamado
    ADD CONSTRAINT fk_atividad_reference_tipo_ocorrencia FOREIGN KEY (seq_tipo_ocorrencia) REFERENCES tipo_ocorrencia(seq_tipo_ocorrencia);


--
-- TOC entry 2447 (class 2606 OID 25193)
-- Dependencies: 153 2290 171
-- Name: fk_atividade_chamado_reference_equipe_t; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_chamado
    ADD CONSTRAINT fk_atividade_chamado_reference_equipe_t FOREIGN KEY (seq_equipe_ti) REFERENCES equipe_ti(seq_equipe_ti);


--
-- TOC entry 2464 (class 2606 OID 25198)
-- Dependencies: 162 2270 158
-- Name: fk_atribuic_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atribuicao_chamado
    ADD CONSTRAINT fk_atribuic_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2465 (class 2606 OID 25203)
-- Dependencies: 158 2290 171
-- Name: fk_atribuic_reference_equipe_t; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atribuicao_chamado
    ADD CONSTRAINT fk_atribuic_reference_equipe_t FOREIGN KEY (seq_equipe_ti) REFERENCES equipe_ti(seq_equipe_ti);


--
-- TOC entry 2466 (class 2606 OID 25208)
-- Dependencies: 158 276 2399
-- Name: fk_atribuic_reference_situacao; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atribuicao_chamado
    ADD CONSTRAINT fk_atribuic_reference_situacao FOREIGN KEY (seq_situacao_chamado) REFERENCES situacao_chamado(seq_situacao_chamado);


--
-- TOC entry 2485 (class 2606 OID 25213)
-- Dependencies: 2270 162 172
-- Name: fk_chamado_etapa_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY etapa_chamado
    ADD CONSTRAINT fk_chamado_etapa_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2477 (class 2606 OID 25218)
-- Dependencies: 2342 162 188
-- Name: fk_chamado_reference_localiza; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT fk_chamado_reference_localiza FOREIGN KEY (seq_localizacao_fisica) REFERENCES localizacao_fisica(seq_localizacao_fisica);


--
-- TOC entry 2478 (class 2606 OID 25223)
-- Dependencies: 162 200 2380
-- Name: fk_chamado_reference_priorida; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT fk_chamado_reference_priorida FOREIGN KEY (seq_prioridade_chamado) REFERENCES prioridade_chamado(seq_prioridade_chamado);


--
-- TOC entry 2479 (class 2606 OID 25228)
-- Dependencies: 276 162 2399
-- Name: fk_chamado_reference_situacao; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado
    ADD CONSTRAINT fk_chamado_reference_situacao FOREIGN KEY (seq_situacao_chamado) REFERENCES situacao_chamado(seq_situacao_chamado);


--
-- TOC entry 2482 (class 2606 OID 25233)
-- Dependencies: 164 2414 282
-- Name: fk_correcao_reference_time_she; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY correcao_time_sheet
    ADD CONSTRAINT fk_correcao_reference_time_she FOREIGN KEY (seq_time_sheet) REFERENCES time_sheet(seq_time_sheet);


--
-- TOC entry 2483 (class 2606 OID 25238)
-- Dependencies: 166 171 2290
-- Name: fk_destino__reference_equipe_t; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY destino_triagem
    ADD CONSTRAINT fk_destino__reference_equipe_t FOREIGN KEY (seq_equipe_ti) REFERENCES equipe_ti(seq_equipe_ti);


--
-- TOC entry 2484 (class 2606 OID 25243)
-- Dependencies: 171 161 2262
-- Name: fk_equipe_seq_central_atendimento; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY equipe_ti
    ADD CONSTRAINT fk_equipe_seq_central_atendimento FOREIGN KEY (seq_central_atendimento) REFERENCES central_atendimento(seq_central_atendimento);


--
-- TOC entry 2487 (class 2606 OID 25248)
-- Dependencies: 178 162 2270
-- Name: fk_hist_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY historico_chamado
    ADD CONSTRAINT fk_hist_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2486 (class 2606 OID 25253)
-- Dependencies: 2270 177 162
-- Name: fk_historic_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY historico_acesso_chamado
    ADD CONSTRAINT fk_historic_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2488 (class 2606 OID 25258)
-- Dependencies: 2356 193 178
-- Name: fk_historic_reference_motivo_s; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY historico_chamado
    ADD CONSTRAINT fk_historic_reference_motivo_s FOREIGN KEY (seq_motivo_suspencao) REFERENCES motivo_suspencao(seq_motivo_suspencao);


--
-- TOC entry 2489 (class 2606 OID 25263)
-- Dependencies: 178 2399 276
-- Name: fk_historic_reference_situacao; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY historico_chamado
    ADD CONSTRAINT fk_historic_reference_situacao FOREIGN KEY (seq_situacao_chamado) REFERENCES situacao_chamado(seq_situacao_chamado);


--
-- TOC entry 2490 (class 2606 OID 25268)
-- Dependencies: 2311 180 179
-- Name: fk_informat_reference_informat; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY informativo_publico
    ADD CONSTRAINT fk_informat_reference_informat FOREIGN KEY (seq_informativo) REFERENCES informativo(seq_informativo);


--
-- TOC entry 2491 (class 2606 OID 25273)
-- Dependencies: 181 182 2324
-- Name: fk_item_configuracao; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY inoperancia_item_configuracao
    ADD CONSTRAINT fk_item_configuracao FOREIGN KEY (seq_item_configuracao) REFERENCES item_configuracao(seq_item_configuracao);


--
-- TOC entry 2506 (class 2606 OID 25278)
-- Dependencies: 188 167 2282
-- Name: fk_localiza_reference_edificac; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY localizacao_fisica
    ADD CONSTRAINT fk_localiza_reference_edificac FOREIGN KEY (seq_edificacao) REFERENCES edificacao(seq_edificacao);


--
-- TOC entry 2448 (class 2606 OID 25283)
-- Dependencies: 154 2387 203
-- Name: fk_num_matricula_recurso_at_rb_rdm; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rb_rdm
    ADD CONSTRAINT fk_num_matricula_recurso_at_rb_rdm FOREIGN KEY (num_matricula_recurso) REFERENCES recurso_ti(num_matricula_recurso);


--
-- TOC entry 2457 (class 2606 OID 25288)
-- Dependencies: 203 2387 156
-- Name: fk_num_matricula_recurso_at_rdm; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rdm
    ADD CONSTRAINT fk_num_matricula_recurso_at_rdm FOREIGN KEY (num_matricula_recurso) REFERENCES recurso_ti(num_matricula_recurso);


--
-- TOC entry 2510 (class 2606 OID 25293)
-- Dependencies: 2270 162 195
-- Name: fk_patrimon_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY patrimonio_chamado
    ADD CONSTRAINT fk_patrimon_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2517 (class 2606 OID 25298)
-- Dependencies: 204 196 2363
-- Name: fk_perfil_acesso; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY recurso_ti_x_perfil_acesso
    ADD CONSTRAINT fk_perfil_acesso FOREIGN KEY (seq_perfil_acesso) REFERENCES perfil_acesso(seq_perfil_acesso);


--
-- TOC entry 2511 (class 2606 OID 25303)
-- Dependencies: 198 198 2372
-- Name: fk_pessoa_pai; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT fk_pessoa_pai FOREIGN KEY (seq_pessoa_superior_hierarquico) REFERENCES pessoa(seq_pessoa);


--
-- TOC entry 2512 (class 2606 OID 25308)
-- Dependencies: 198 285 2420
-- Name: fk_pessoa_tipo_funcao_administrativa; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT fk_pessoa_tipo_funcao_administrativa FOREIGN KEY (seq_tipo_funcao_administrativa) REFERENCES tipo_funcao_administrativa(seq_tipo_funcao_administrativa);


--
-- TOC entry 2513 (class 2606 OID 25313)
-- Dependencies: 291 2433 198
-- Name: fk_pessoa_unidade_organizacional; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT fk_pessoa_unidade_organizacional FOREIGN KEY (seq_unidade_organizacional) REFERENCES unidade_organizacional(seq_unidade_organizacional);


--
-- TOC entry 2516 (class 2606 OID 25318)
-- Dependencies: 203 2290 171
-- Name: fk_recurso__reference_equipe_t; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY recurso_ti
    ADD CONSTRAINT fk_recurso__reference_equipe_t FOREIGN KEY (seq_equipe_ti) REFERENCES equipe_ti(seq_equipe_ti);


--
-- TOC entry 2518 (class 2606 OID 25323)
-- Dependencies: 2387 204 203
-- Name: fk_recurso_ti; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY recurso_ti_x_perfil_acesso
    ADD CONSTRAINT fk_recurso_ti FOREIGN KEY (num_matricula_recurso) REFERENCES recurso_ti(num_matricula_recurso);


--
-- TOC entry 2519 (class 2606 OID 25328)
-- Dependencies: 206 291 2433
-- Name: fk_responsavel_unidade_organizacional; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY responsavel_unidade_organizacional
    ADD CONSTRAINT fk_responsavel_unidade_organizacional FOREIGN KEY (seq_unidade_organizacional) REFERENCES unidade_organizacional(seq_unidade_organizacional);


--
-- TOC entry 2520 (class 2606 OID 25333)
-- Dependencies: 206 2372 198
-- Name: fk_responsavel_unidade_organizacional_pessoa; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY responsavel_unidade_organizacional
    ADD CONSTRAINT fk_responsavel_unidade_organizacional_pessoa FOREIGN KEY (seq_pessoa) REFERENCES pessoa(seq_pessoa);


--
-- TOC entry 2480 (class 2606 OID 25338)
-- Dependencies: 163 162 2270
-- Name: fk_seq_chamado_chamado_rdm; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado_rdm
    ADD CONSTRAINT fk_seq_chamado_chamado_rdm FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2527 (class 2606 OID 25343)
-- Dependencies: 292 2270 162
-- Name: fk_seq_chamado_filho; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY vinculo_chamado
    ADD CONSTRAINT fk_seq_chamado_filho FOREIGN KEY (seq_chamado_filho) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2528 (class 2606 OID 25348)
-- Dependencies: 2270 162 292
-- Name: fk_seq_chamado_master; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY vinculo_chamado
    ADD CONSTRAINT fk_seq_chamado_master FOREIGN KEY (seq_chamado_master) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2449 (class 2606 OID 25353)
-- Dependencies: 154 2290 171
-- Name: fk_seq_equipe_ti; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rb_rdm
    ADD CONSTRAINT fk_seq_equipe_ti FOREIGN KEY (seq_equipe_ti) REFERENCES equipe_ti(seq_equipe_ti);


--
-- TOC entry 2453 (class 2606 OID 25358)
-- Dependencies: 171 155 2290
-- Name: fk_seq_equipe_ti; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rb_rdm_template
    ADD CONSTRAINT fk_seq_equipe_ti FOREIGN KEY (seq_equipe_ti) REFERENCES equipe_ti(seq_equipe_ti);


--
-- TOC entry 2502 (class 2606 OID 25363)
-- Dependencies: 2324 184 182
-- Name: fk_seq_item_configuracao; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY janela_mudacao_item_configuracao
    ADD CONSTRAINT fk_seq_item_configuracao FOREIGN KEY (seq_item_configuracao) REFERENCES item_configuracao(seq_item_configuracao);


--
-- TOC entry 2450 (class 2606 OID 25368)
-- Dependencies: 2324 154 182
-- Name: fk_seq_item_configuracao; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rb_rdm
    ADD CONSTRAINT fk_seq_item_configuracao FOREIGN KEY (seq_item_configuracao) REFERENCES item_configuracao(seq_item_configuracao);


--
-- TOC entry 2454 (class 2606 OID 25373)
-- Dependencies: 155 2324 182
-- Name: fk_seq_item_configuracao; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rb_rdm_template
    ADD CONSTRAINT fk_seq_item_configuracao FOREIGN KEY (seq_item_configuracao) REFERENCES item_configuracao(seq_item_configuracao);


--
-- TOC entry 2503 (class 2606 OID 25378)
-- Dependencies: 184 2335 185
-- Name: fk_seq_janela_mudanca; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY janela_mudacao_item_configuracao
    ADD CONSTRAINT fk_seq_janela_mudanca FOREIGN KEY (seq_janela_mudanca) REFERENCES janela_mudanca(seq_janela_mudanca);


--
-- TOC entry 2504 (class 2606 OID 25383)
-- Dependencies: 185 2335 186
-- Name: fk_seq_janela_mudanca_ser; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY janela_mudanca_servidor
    ADD CONSTRAINT fk_seq_janela_mudanca_ser FOREIGN KEY (seq_janela_mudanca) REFERENCES janela_mudanca(seq_janela_mudanca);


--
-- TOC entry 2451 (class 2606 OID 25388)
-- Dependencies: 154 201 2382
-- Name: fk_seq_rdm; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rb_rdm
    ADD CONSTRAINT fk_seq_rdm FOREIGN KEY (seq_rdm) REFERENCES rdm(seq_rdm);


--
-- TOC entry 2521 (class 2606 OID 25393)
-- Dependencies: 277 201 2382
-- Name: fk_seq_rdm; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY situacao_rdm
    ADD CONSTRAINT fk_seq_rdm FOREIGN KEY (seq_rdm) REFERENCES rdm(seq_rdm);


--
-- TOC entry 2481 (class 2606 OID 25398)
-- Dependencies: 163 201 2382
-- Name: fk_seq_rdm_chamado_rdm; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY chamado_rdm
    ADD CONSTRAINT fk_seq_rdm_chamado_rdm FOREIGN KEY (seq_rdm) REFERENCES rdm(seq_rdm);


--
-- TOC entry 2515 (class 2606 OID 25403)
-- Dependencies: 202 201 2382
-- Name: fk_seq_rdm_origem; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY rdm_template
    ADD CONSTRAINT fk_seq_rdm_origem FOREIGN KEY (seq_rdm_origem) REFERENCES rdm(seq_rdm);


--
-- TOC entry 2455 (class 2606 OID 25408)
-- Dependencies: 202 155 2384
-- Name: fk_seq_rdm_template_rb; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rb_rdm_template
    ADD CONSTRAINT fk_seq_rdm_template_rb FOREIGN KEY (seq_rdm_template) REFERENCES rdm_template(seq_rdm_template);


--
-- TOC entry 2505 (class 2606 OID 25413)
-- Dependencies: 2395 274 186
-- Name: fk_seq_servidor; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY janela_mudanca_servidor
    ADD CONSTRAINT fk_seq_servidor FOREIGN KEY (seq_servidor) REFERENCES servidor(seq_servidor);


--
-- TOC entry 2452 (class 2606 OID 25418)
-- Dependencies: 274 2395 154
-- Name: fk_seq_servidor; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rb_rdm
    ADD CONSTRAINT fk_seq_servidor FOREIGN KEY (seq_servidor) REFERENCES servidor(seq_servidor);


--
-- TOC entry 2456 (class 2606 OID 25423)
-- Dependencies: 2395 155 274
-- Name: fk_seq_servidor; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rb_rdm_template
    ADD CONSTRAINT fk_seq_servidor FOREIGN KEY (seq_servidor) REFERENCES servidor(seq_servidor);


--
-- TOC entry 2514 (class 2606 OID 25428)
-- Dependencies: 203 2387 201
-- Name: fk_solicitante_rdm; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY rdm
    ADD CONSTRAINT fk_solicitante_rdm FOREIGN KEY (num_matricula_solicitante) REFERENCES recurso_ti(num_matricula_recurso);


--
-- TOC entry 2523 (class 2606 OID 25433)
-- Dependencies: 283 2416 281
-- Name: fk_subtipo__reference_tipo_cha; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY subtipo_chamado
    ADD CONSTRAINT fk_subtipo__reference_tipo_cha FOREIGN KEY (seq_tipo_chamado) REFERENCES tipo_chamado(seq_tipo_chamado);


--
-- TOC entry 2524 (class 2606 OID 25438)
-- Dependencies: 162 282 2270
-- Name: fk_time_she_reference_chamado; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY time_sheet
    ADD CONSTRAINT fk_time_she_reference_chamado FOREIGN KEY (seq_chamado) REFERENCES chamado(seq_chamado);


--
-- TOC entry 2525 (class 2606 OID 25443)
-- Dependencies: 161 2262 283
-- Name: fk_tipo_chamado_seq_central_atendimento; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY tipo_chamado
    ADD CONSTRAINT fk_tipo_chamado_seq_central_atendimento FOREIGN KEY (seq_central_atendimento) REFERENCES central_atendimento(seq_central_atendimento);


--
-- TOC entry 2526 (class 2606 OID 25448)
-- Dependencies: 291 291 2433
-- Name: fk_unidade_organizacional_pai; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY unidade_organizacional
    ADD CONSTRAINT fk_unidade_organizacional_pai FOREIGN KEY (seq_unidade_organizacional_pai) REFERENCES unidade_organizacional(seq_unidade_organizacional);


--
-- TOC entry 2492 (class 2606 OID 25453)
-- Dependencies: 182 165 2277
-- Name: item_configuracao_seq_criticidade_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao
    ADD CONSTRAINT item_configuracao_seq_criticidade_fkey FOREIGN KEY (seq_criticidade) REFERENCES criticidade(seq_criticidade);


--
-- TOC entry 2493 (class 2606 OID 25458)
-- Dependencies: 171 2290 182
-- Name: item_configuracao_seq_equipe_ti_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao
    ADD CONSTRAINT item_configuracao_seq_equipe_ti_fkey FOREIGN KEY (seq_equipe_ti) REFERENCES equipe_ti(seq_equipe_ti);


--
-- TOC entry 2494 (class 2606 OID 25463)
-- Dependencies: 182 2378 199
-- Name: item_configuracao_seq_prioridade_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao
    ADD CONSTRAINT item_configuracao_seq_prioridade_fkey FOREIGN KEY (seq_prioridade) REFERENCES prioridade(seq_prioridade);


--
-- TOC entry 2495 (class 2606 OID 25468)
-- Dependencies: 2418 182 284
-- Name: item_configuracao_seq_tipo_disponibilidade_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao
    ADD CONSTRAINT item_configuracao_seq_tipo_disponibilidade_fkey FOREIGN KEY (seq_tipo_disponibilidade) REFERENCES tipo_disponibilidade(seq_tipo_disponibilidade);


--
-- TOC entry 2496 (class 2606 OID 25473)
-- Dependencies: 2422 182 286
-- Name: item_configuracao_seq_tipo_item_configuracao_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao
    ADD CONSTRAINT item_configuracao_seq_tipo_item_configuracao_fkey FOREIGN KEY (seq_tipo_item_configuracao) REFERENCES tipo_item_configuracao(seq_tipo_item_configuracao);


--
-- TOC entry 2497 (class 2606 OID 25478)
-- Dependencies: 176 183 2301
-- Name: item_configuracao_software_seq_frequencia_manutencao_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao_software
    ADD CONSTRAINT item_configuracao_software_seq_frequencia_manutencao_fkey FOREIGN KEY (seq_frequencia_manutencao) REFERENCES frequencia_manutencao(seq_frequencia_manutencao);


--
-- TOC entry 2498 (class 2606 OID 25483)
-- Dependencies: 2324 182 183
-- Name: item_configuracao_software_seq_item_configuracao_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao_software
    ADD CONSTRAINT item_configuracao_software_seq_item_configuracao_fkey FOREIGN KEY (seq_item_configuracao) REFERENCES item_configuracao(seq_item_configuracao);


--
-- TOC entry 2499 (class 2606 OID 25488)
-- Dependencies: 183 2407 280
-- Name: item_configuracao_software_seq_status_software_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao_software
    ADD CONSTRAINT item_configuracao_software_seq_status_software_fkey FOREIGN KEY (seq_status_software) REFERENCES status_software(seq_status_software);


--
-- TOC entry 2500 (class 2606 OID 25493)
-- Dependencies: 183 2428 289
-- Name: item_configuracao_software_seq_tipo_software_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao_software
    ADD CONSTRAINT item_configuracao_software_seq_tipo_software_fkey FOREIGN KEY (seq_tipo_software) REFERENCES tipo_software(seq_tipo_software);


--
-- TOC entry 2501 (class 2606 OID 25498)
-- Dependencies: 2430 183 290
-- Name: item_configuracao_software_seq_unidade_medida_software_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY item_configuracao_software
    ADD CONSTRAINT item_configuracao_software_seq_unidade_medida_software_fkey FOREIGN KEY (seq_unidade_medida_software) REFERENCES unidade_medida_software(seq_unidade_medida_software);


--
-- TOC entry 2507 (class 2606 OID 25503)
-- Dependencies: 2348 190 190
-- Name: menu_acesso_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY menu_acesso
    ADD CONSTRAINT menu_acesso_fkey FOREIGN KEY (seq_menu_acesso_pai) REFERENCES menu_acesso(seq_menu_acesso);


--
-- TOC entry 2508 (class 2606 OID 25508)
-- Dependencies: 191 2348 190
-- Name: menu_acesso_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY menu_perfil_acesso
    ADD CONSTRAINT menu_acesso_fkey FOREIGN KEY (seq_menu_acesso) REFERENCES menu_acesso(seq_menu_acesso);


--
-- TOC entry 2522 (class 2606 OID 25513)
-- Dependencies: 277 2387 203
-- Name: num_matricula_recurso; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY situacao_rdm
    ADD CONSTRAINT num_matricula_recurso FOREIGN KEY (num_matricula_recurso) REFERENCES recurso_ti(num_matricula_recurso);


--
-- TOC entry 2509 (class 2606 OID 25518)
-- Dependencies: 191 196 2363
-- Name: perfil_acesso_fkey; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY menu_perfil_acesso
    ADD CONSTRAINT perfil_acesso_fkey FOREIGN KEY (seq_perfil_acesso) REFERENCES perfil_acesso(seq_perfil_acesso);


--
-- TOC entry 2458 (class 2606 OID 25523)
-- Dependencies: 156 2290 171
-- Name: seq_equipe_ti; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rdm
    ADD CONSTRAINT seq_equipe_ti FOREIGN KEY (seq_equipe_ti) REFERENCES equipe_ti(seq_equipe_ti);


--
-- TOC entry 2461 (class 2606 OID 25528)
-- Dependencies: 2290 171 157
-- Name: seq_equipe_ti; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rdm_template
    ADD CONSTRAINT seq_equipe_ti FOREIGN KEY (seq_equipe_ti) REFERENCES equipe_ti(seq_equipe_ti);


--
-- TOC entry 2459 (class 2606 OID 25533)
-- Dependencies: 156 2324 182
-- Name: seq_item_configuracao; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rdm
    ADD CONSTRAINT seq_item_configuracao FOREIGN KEY (seq_item_configuracao) REFERENCES item_configuracao(seq_item_configuracao);


--
-- TOC entry 2462 (class 2606 OID 25538)
-- Dependencies: 157 182 2324
-- Name: seq_item_configuracao; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rdm_template
    ADD CONSTRAINT seq_item_configuracao FOREIGN KEY (seq_item_configuracao) REFERENCES item_configuracao(seq_item_configuracao);


--
-- TOC entry 2460 (class 2606 OID 25543)
-- Dependencies: 156 274 2395
-- Name: seq_servidor; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rdm
    ADD CONSTRAINT seq_servidor FOREIGN KEY (seq_servidor) REFERENCES servidor(seq_servidor);


--
-- TOC entry 2463 (class 2606 OID 25548)
-- Dependencies: 157 2395 274
-- Name: seq_servidor; Type: FK CONSTRAINT; Schema: gestaoti; Owner: gestaoti
--

ALTER TABLE ONLY atividade_rdm_template
    ADD CONSTRAINT seq_servidor FOREIGN KEY (seq_servidor) REFERENCES servidor(seq_servidor);


--
-- TOC entry 2617 (class 0 OID 0)
-- Dependencies: 7
-- Name: gestaoti; Type: ACL; Schema: -; Owner: gestaoti
--

REVOKE ALL ON SCHEMA gestaoti FROM PUBLIC;
REVOKE ALL ON SCHEMA gestaoti FROM postgres;
GRANT ALL ON SCHEMA gestaoti TO postgres;
GRANT ALL ON SCHEMA gestaoti TO gestaoti;


-- Completed on 2012-01-24 12:13:03 BRST

--
-- PostgreSQL database dump complete
--

