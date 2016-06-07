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
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.recurso_ti.php';
require 'include/PHP/class/class.area_atuacao.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require_once 'include/PHP/class/class.perfil_recurso_ti.php';
require_once 'include/PHP/class/class.perfil_acesso.php';
require_once 'include/PHP/class/class.area_atuacao.php';
require 'include/PHP/class/class.item_configuracao.php';
require 'include/PHP/class/class.unidades_organizacionais.php';
require 'include/PHP/class/class.servidor.php';
require 'include/PHP/class/class.equipe_servidor.php';
require 'include/PHP/class/class.equipe_ti.php';
require 'include/PHP/class/class.chamado.php';
require 'include/PHP/class/class.prioridade_chamado.php';
require 'include/PHP/class/class.subtipo_chamado.php';
require 'include/PHP/class/class.tipo_chamado.php';
require 'include/PHP/class/class.tipo_funcao_administrativa.php';
require 'include/PHP/class/class.responsavel_unidade_organizacional.php';
$equipe_ti = new equipe_ti();
$pagina = new Pagina();
$item_configuracao = new item_configuracao();
$unidades_organizacionais = new unidades_organizacionais();
$tipo_funcao_administrativa = new tipo_funcao_administrativa();

// Carregando detalhes de Sistemas de Informação
if($v_SEQ_PESSOA != ""){
	$banco = new recurso_ti();
	$recurso_ti = new recurso_ti();
	$empregados = new empregados();
	$perfil_acesso = new perfil_acesso();
	//$perfil_recurso_ti = new perfil_recurso_ti();
	$area_atuacao = new area_atuacao();
	$servidor = new servidor();
	$equipe_servidor = new equipe_servidor();
	$pagina->setMethod("post");

	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes do Profissional do SISP"); // Indica o título do cabeçalho da página
	// Itens das abas

	$pagina->ForcaAutenticacao();
        
        if($flag == "3"){
            // Gerar nova senha
            $vNovaSenha = $pagina->fGeraSenha(6, "S", "S");
            $empregados = new empregados();
            $empregados->alterarSenha($v_SEQ_PESSOA, $pagina->fEncriptSenha($vNovaSenha));
            $pagina->ScriptAlert("Senha alterada com sucesso. Nova senha: $vNovaSenha");
        }
        
        // pesquisa
	$banco->select($v_SEQ_PESSOA);
        
        // Pesquisar chamados
        $chamado = new chamado();
        $chamado->setNUM_MATRICULA_SOLICITANTE($v_SEQ_PESSOA);
        $chamado->selectParam("DTH_ABERTURA DESC");
        
	if($pagina->segurancaPerfilExclusao($_SESSION["SEQ_PERFIL_ACESSO"])){ 
              //// Apenas o adminsitrador
            // Verificar se o usuário pode ser excluído
            if($chamado->database->rows == 0){
		$aItemAba = Array( array("PessoaPesquisa.php", "", "Pesquisa"),
                                   array("PessoaCadastro.php", "", "Adicionar"),
                                   array("PessoaAlteracao.php?v_SEQ_PESSOA=$v_SEQ_PESSOA", "", "Alterar"),
                                   array("javascript: fDeletarPlus('v_SEQ_PESSOA', '$v_SEQ_PESSOA', 'PessoaPesquisa.php');", "", "Excluir"),
                                   array("javascript: fAcaoMesmaPagina('v_SEQ_PESSOA', '$v_SEQ_PESSOA', 'PessoaDetalhes.php', '3', 'Desenha gerar nova senha para o usuário?');", "", "Nova Senha"),
                                   array("#", "tabact", "Detalhes")
						 );
            }else{
                $aItemAba = Array( array("PessoaPesquisa.php", "", "Pesquisa"),
                                   array("PessoaCadastro.php", "", "Adicionar"),
                                   array("PessoaAlteracao.php?v_SEQ_PESSOA=$v_SEQ_PESSOA", "", "Alterar"),
                                   array("javascript: fAcaoMesmaPagina('v_SEQ_PESSOA', '$v_SEQ_PESSOA', 'PessoaDetalhes.php', '3', 'Desenha gerar nova senha para o usuário?');", "", "Nova Senha"),
                                   array("#", "tabact", "Detalhes")
						 );
            }
	}elseif($pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas os gestores
		// Liberar alteração apenas para o pessoal da própria equipe
		if($banco->SEQ_EQUIPE_TI == $_SESSION["SEQ_EQUIPE_TI"]){
			$aItemAba = Array( array("PessoaPesquisa.php", "", "Pesquisa"),
                                           array("PessoaCadastro.php", "", "Adicionar"),
                                           array("PessoaAlteracao.php?v_SEQ_PESSOA=$v_SEQ_PESSOA", "", "Alterar"),
                                           array("javascript: fAcaoMesmaPagina('v_SEQ_PESSOA', '$v_SEQ_PESSOA', 'PessoaDetalhes.php', '3', 'Desenha gerar nova senha para o usuário?');", "", "Nova Senha"),
                                           array("#", "tabact", "Detalhes")
							 );
		}else{
				$aItemAba = Array( array("PessoaPesquisa.php", "", "Pesquisa"),
			 			   array("PessoaCadastro.php", "", "Adicionar"),
						   array("#", "tabact", "Detalhes")
							 );
		}
	}else{
		$aItemAba = Array( array("PessoaPesquisa.php", "", "Pesquisa"),
					   array("#", "tabact", "Detalhes")
						 );
	}
	$pagina->SetaItemAba($aItemAba);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_PESSOA", $v_SEQ_PESSOA);
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Informações Gerais", 2);

	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $banco->NOME, "left", "id=".$pagina->GetIdTable(),"20%","");
	$pagina->LinhaCampoFormulario("Nome Abreviado:", "right", "N", $banco->NOME_ABREVIADO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Nome de Guerra:", "right", "N", $banco->NOME_GUERRA, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Login de rede:", "right", "N", $banco->NOM_LOGIN_REDE, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Lotação:", "right", "N", $banco->DEP_SIGLA, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("E-mail:", "right", "N", $banco->DES_EMAIL, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Telefone:", "right", "N", $banco->NUM_DDD." - ".$banco->NUM_TELEFONE, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Ramal:", "right", "N", $banco->NUM_VOIP, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Status:", "right", "N", $banco->DES_STATUS=="A"?"Ativo":"Desativado", "left", "id=".$pagina->GetIdTable());
                
        if($banco->SEQ_TIPO_FUNCAO_ADMINISTRATIVA != ""){
            $tipo_funcao_administrativa->select($banco->SEQ_TIPO_FUNCAO_ADMINISTRATIVA);
            $pagina->LinhaCampoFormulario("Função administrativa:", "right", "N", $tipo_funcao_administrativa->NOM_TIPO_FUNCAO_ADMINISTRATIVA, "left", "id=".$pagina->GetIdTable());
        }
        
        // Unidades organizacionais que é responsável
        $responsavel_unidade_organizacional = new responsavel_unidade_organizacional();
        $responsavel_unidade_organizacional->setSEQ_PESSOA($v_SEQ_PESSOA);
        $responsavel_unidade_organizacional->selectParam("1","1");
        if($responsavel_unidade_organizacional->database->rows > 0){
            $unidades = "";
            $contUnidade = 1;
            while ($rowUnidadeOrganizacional = pg_fetch_array($responsavel_unidade_organizacional->database->result)){
                $unidades .= $rowUnidadeOrganizacional["nom_unidade_organizacional"];
                if($contUnidade != $responsavel_unidade_organizacional->database->rows){
                    $unidades .= "<br>";
                }
                $contUnidade++;
            }
            $pagina->LinhaCampoFormulario("Unidades organizacionais que é responsável:", "right", "N", $unidades, "left", "id=".$pagina->GetIdTable());
        }else{
            $pagina->LinhaCampoFormulario("Unidades organizacionais que é responsável:", "right", "N", "---", "left", "id=".$pagina->GetIdTable());
        }
        
        
	//$perfil_recurso_ti->select($banco->SEQ_PERFIL_RECURSO_TI);
	//$pagina->LinhaCampoFormulario("Cargo/Função:", "right", "N", $perfil_recurso_ti->NOM_PERFIL_RECURSO_TI, "left", "id=".$pagina->GetIdTable());

	//$perfil_recurso_ti->select($banco->SEQ_PERFIL_RECURSO_TI);
	//$pagina->LinhaCampoFormulario("Equipe:", "right", "N", $equipe_ti->NOM_EQUIPE_TI, "left", "id=".$pagina->GetIdTable());
	
	/*TODO: NOVO PERFIL ACESSO*/
	
	//$perfil_acesso->select($banco->SEQ_PERFIL_ACESSO);
	///$pagina->LinhaCampoFormulario("Perfil de Acesso:", "right", "N", $perfil_acesso->NOM_PERFIL_ACESSO, "left", "id=".$pagina->GetIdTable());
	
	
	
	?>
	<script>
		function fMostra(id, idTab){
			document.getElementById("tabelaAlocacao").style.display = "none";
			document.getElementById("tabAlocacao").attributes["class"].value = "";

			//document.getElementById("tabelaItemConfiguracao").style.display = "none";
			//document.getElementById("tabItemConfiguracao").attributes["class"].value = "";

			document.getElementById(id).style.display = "block";
			document.getElementById(idTab).attributes["class"].value = "tabact";
		}
	</script>
	<?
	$aItemAba = Array(
			array("javascript: fMostra('tabelaAlocacao','tabAlocacao')", "tabact", "&nbsp;Chamados&nbsp;", "tabAlocacao")
                        //,
			//array("javascript: fMostra('tabelaItemConfiguracao','tabItemConfiguracao')", "", "&nbsp;Responsabilidade&nbsp;", "tabItemConfiguracao")
 			     );
	$pagina->SetaItemAba($aItemAba);
	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaAlocacao cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	//================================================================================================================================
	//================================================================================================================================
	$pagina->LinhaCampoFormularioColspanDestaque("Chamados que abriu", 2);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Prioridade", "10%");
	$header[] = array("Chamado", "10%");
	$header[] = array("Atividade", "20%");
	$header[] = array("Solicitação", "20%");
	$header[] = array("Situação", "15%");
	$header[] = array("Abertura", "10%");
	$header[] = array("Vencimento", "10%");
	$header[] = array("SLA", "5%");

	// Setar variáveis
//	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
        //$chamado = new chamado();
	//$chamado->setNUM_MATRICULA_SOLICITANTE($v_SEQ_PESSOA);
	//$chamado->selectParam("DTH_ABERTURA DESC");
	if($chamado->database->rows > 0){
		$corpo = array();
//		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Chamados relacionados com o patrimônio", $header);
		$vLink = "?flag=1&v_SEQ_CHAMADO_PESQUISA=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO_INFRAERO=$v_SEQ_EDIFICACAO_INFRAERO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL";
		while ($row = pg_fetch_array($chamado->database->result)){
			// Prioridade
			$prioridade_chamado = new prioridade_chamado();
			$prioridade_chamado->select($row["seq_prioridade_chamado"]);
			$corpo[] = array("left", "campo", $prioridade_chamado->DSC_PRIORIDADE_CHAMADO);

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

			// Abertura
			$corpo[] = array("center", "campo", $row["dth_abertura"]);

			// Recuperar dados do SLA
			$v_DTH_ENCERRAMENTO_PREVISAO = $chamado->fGetDTH_ENCERRAMENTO_PREVISAO($row["dth_abertura"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"], $row["seq_chamado"], $row["seq_tipo_ocorrencia"], $row["qtd_min_sla_solucao_final"]);
			//$v_COD_SLA_ATENDIMENTO = $banco->fGetCOD_SLA_ATENDIMENTO($row["seq_chamado"], $row["dth_abertura"], $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"], $row["flg_forma_medicao_tempo"]);
			$v_COD_SLA_ATENDIMENTO = $chamado->fGetCOD_SLA($row["dth_abertura"], $v_DTH_ENCERRAMENTO_PREVISAO, $row["dth_encerramento_efetivo"], $row["qtd_min_sla_atendimento"]);

			// Vencimento
			$corpo[] = array("center", "campo", "<font color=".$pagina->CorSLA($v_COD_SLA_ATENDIMENTO).">".$v_DTH_ENCERRAMENTO_PREVISAO."</font>");

			// SLA
			$corpo[] = array("center", "campo", $pagina->ImagemSLA($v_COD_SLA_ATENDIMENTO));

			$pagina->LinhaTabelaResultado($corpo, "", "style=\"cursor: pointer;\" onclick=\"location.href='ChamadoDetalhe.php".$vLink."&vNumPagina=$vNumPagina&v_SEQ_CHAMADO=".$row["seq_chamado"]."';\"");
			$corpo = "";
		}
		$pagina->FechaTabelaPadrao();
//		$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_CHAMADO=$v_SEQ_CHAMADO&v_NUM_MATRICULA_SOLICITANTE=$v_NUM_MATRICULA_SOLICITANTE&v_NUM_MATRICULA_CONTATO=$v_NUM_MATRICULA_CONTATO&v_SEQ_SITUACAO_CHAMADO=$v_SEQ_SITUACAO_CHAMADO&v_COD_SLA_ATENDIMENTO=$v_COD_SLA_ATENDIMENTO&v_SEQ_TIPO_CHAMADO=$v_SEQ_TIPO_CHAMADO&v_SEQ_SUBTIPO_CHAMADO=$v_SEQ_SUBTIPO_CHAMADO&v_SEQ_ATIVIDADE_CHAMADO=$v_SEQ_ATIVIDADE_CHAMADO&v_SEQ_PRIORIDADE_CHAMADO=$v_SEQ_PRIORIDADE_CHAMADO&v_TXT_CHAMADO=$v_TXT_CHAMADO&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA&v_SEQ_EDIFICACAO_INFRAERO=$v_SEQ_EDIFICACAO_INFRAERO&v_SEQ_LOCALIZACAO_FISICA=$v_SEQ_LOCALIZACAO_FISICA&v_COD_DEPENDENCIA_ATRIBUICAO=$v_COD_DEPENDENCIA_ATRIBUICAO&v_SEQ_EQUIPE_TI=$v_SEQ_EQUIPE_TI&v_NUM_MATRICULA_RECURSO=$v_NUM_MATRICULA_RECURSO&v_DTH_ABERTURA=$v_DTH_ABERTURA&v_DTH_ABERTURA_FINAL=$v_DTH_ABERTURA_FINAL&v_DTH_INICIO_EFETIVO=$v_DTH_INICIO_EFETIVO&v_DTH_INICIO_EFETIVO_FINAL=$v_DTH_INICIO_EFETIVO_FINAL&v_DTH_ENCERRAMENTO_EFETIVO=$v_DTH_ENCERRAMENTO_EFETIVO&v_DTH_ENCERRAMENTO_EFETIVO_FINAL=$v_DTH_ENCERRAMENTO_EFETIVO_FINAL&v_SEQ_TIPO_OCORRENCIA=$v_SEQ_TIPO_OCORRENCIA");
	}else{
		$pagina->LinhaColspan("center", "Chamados encontrados para os parâmetros informados", "2", "header");
		$pagina->LinhaColspan("left", "Nenhum chamado encontrado", "2", "campo");
		$pagina->FechaTabelaPadrao();
	}


	$pagina->FechaTabelaPadrao();

	//================================================================================================================================
	//================================================================================================================================
	/*
        $pagina->AbreTabelaPadrao("center", "100%", "id=tabelaItemConfiguracao style=\"display: none;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
        $pagina->LinhaCampoFormularioColspanDestaque("Sistemas de informação sob sua responsabilidade", 2);

	$item_configuracao = new item_configuracao();
	$item_configuracao->setNUM_MATRICULA_LIDER($v_NUM_MATRICULA_RECURSO);
	$item_configuracao->selectParam($pagina->iif($vOrderBy == "", "SIG_ITEM_CONFIGURACAO", $vOrderBy), $vNumPagina);
	if($item_configuracao->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("&nbsp;", "center", "3%", "header");
		$header[] = array("Gestor", "center", "20%", "header");
		$header[] = array("Sigla", "center", "15%", "header");
		$header[] = array("Nome", "center", "25%", "header");
		$header[] = array("Área", "center", "10%", "header");
		$tabela[] = $header;
		while ($row = pg_fetch_array($item_configuracao->database->result)){
			$header = array();
			$valor = $pagina->BotaoLupa("Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$row["seq_item_configuracao"], "Detalhes de ".$row["nom_item_configuracao"]);
			$header[] = array($valor, "center", "", "");
			$header[] = array($empregados->GetNomeEmpregado($row["num_matricula_gestor"]), "left", "", "");
			$header[] = array($row["sig_item_configuracao"], "left", "", "");
			$header[] = array($row["nom_item_configuracao"], "left", "", "");
			$header[] = array($unidades_organizacionais->GetUorSigla($row["cod_uor_area_gestora"]), "left", "", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true), 2);
	}else{
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", 2);
	}

	$pagina->FechaTabelaPadrao();
        */
	$pagina->MontaRodape();
}else{
	$pagina->mensagem("Selecione um profissional");
}

?>