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
require 'include/PHP/class/class.servidor.php';
require_once 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.item_configuracao.php';
require 'include/PHP/class/class.unidades_organizacionais.php';
$pagina = new Pagina();
$banco = new servidor();
$empregados = new empregados();
$item_configuracao = new item_configuracao();
$unidades_organizacionais = new unidades_organizacionais();

if($v_SEQ_SERVIDOR != ""){
	$pagina->ForcaAutenticacao();
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Detalhes do Servidor"); // Indica o título do cabeçalho da página
	// Itens das abas
	if($pagina->segurancaPerfilExclusao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas o adminsitrador
		$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
						array("ServidorCadastro.php", "", "Adicionar"),
						array("ServidorAlteracao.php?v_SEQ_SERVIDOR=$v_SEQ_SERVIDOR", "", "Alterar"),
						array("javascript: fDeletarPlus('v_SEQ_SERVIDOR', '$v_SEQ_SERVIDOR', 'Item_configuracaoPesquisa.php');", "", "Excluir"),
		 			    array("#", "tabact", "Detalhes") );
	}elseif($pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas o adminsitrador
		$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
		 			   array("Item_configuracaoCadastro1.php", "", "Adicionar"),
					   array("Item_configuracaoAlteracao.php?v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO", "", "Alterar"),
					   array("#", "tabact", "Detalhes")
						 );
	}else{
		$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
					   array("#", "tabact", "Detalhes")
						 );
	}
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_SERVIDOR);

	// Inicio do formulário
	$pagina->MontaCabecalho();
	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_SERVIDOR", $v_SEQ_SERVIDOR);

	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaGeral cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");

	$pagina->LinhaCampoFormulario("Patrimônio:", "right", "N", $banco->NUM_PATRIMONIO, "left", "id=".$pagina->GetIdTable(),"30%");
	$pagina->LinhaCampoFormulario("IP:", "right", "N", $banco->NUM_IP, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Nome:", "right", "N", $banco->NOM_SERVIDOR, "left", "id=".$pagina->GetIdTable());

	// Buscar dados da tabela externa
	if($banco->SEQ_SISTEMA_OPERACIONAL != ""){
		require_once 'include/PHP/class/class.sistema_operacional.php';
		$sistema_operacional = new sistema_operacional();
		$sistema_operacional->select($banco->SEQ_SISTEMA_OPERACIONAL);
		$pagina->LinhaCampoFormulario("Sistema operacional:", "right", "N", $sistema_operacional->NOM_SISTEMA_OPERACIONAL, "left", "id=".$pagina->GetIdTable());
	}

	if($banco->SEQ_MARCA_HARDWARE != ""){
		// Buscar dados da tabela externa
		require_once 'include/PHP/class/class.marca_hardware.php';
		$marca_hardware = new marca_hardware();
		$marca_hardware->select($banco->SEQ_MARCA_HARDWARE);
		$pagina->LinhaCampoFormulario("Marca:", "right", "N", $marca_hardware->NOM_MARCA_HARDWARE, "left", "id=".$pagina->GetIdTable());
	}

	$pagina->LinhaCampoFormulario("Modelo:", "right", "N", $banco->NOM_MODELO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $banco->DSC_SERVIDOR, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Localização:", "right", "N", $banco->DSC_LOCALIZACAO, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Processadores:", "right", "N", $banco->DSC_PROCESSADOR, "left", "id=".$pagina->GetIdTable());
	$pagina->LinhaCampoFormulario("Observação:", "right", "N", $banco->TXT_OBSERVACAO, "left", "id=".$pagina->GetIdTable());

	?>
	<script>
		function fMostra(id, idTab){
			document.getElementById("tabelaAlocacao").style.display = "none";
			document.getElementById("tabAlocacao").attributes["class"].value = "";

			document.getElementById("tabelaSistemas").style.display = "none";
			document.getElementById("tabSistemas").attributes["class"].value = "";

			document.getElementById(id).style.display = "block";
			document.getElementById(idTab).attributes["class"].value = "tabact";
		}
	</script>
	<?

	$aItemAba = Array(
			array("javascript: fMostra('tabelaAlocacao','tabAlocacao')", "tabact", "&nbsp;Alocação&nbsp;", "tabAlocacao"),
			array("javascript: fMostra('tabelaSistemas','tabSistemas')", "", "&nbsp;Sistemas&nbsp;", "tabSistemas"),
 			     );
	$pagina->SetaItemAba($aItemAba);
	$pagina->LinhaColspan("center",$pagina->MontaAbaInterna(), 2,"");

	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaAlocacao\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");

	?>
		<script>
		// Declara arrays de controle das tabelas 1-N
		var aEquipe = new Array();
		var aEquipeAux = new Array();

		function fAdicionaItemEquipe(v_NOM_LOGIN_REDE_EQUIPE, v_NUM_ORDEM, v_NOME_EMPREGADO){
			if(v_NOM_LOGIN_REDE_EQUIPE != "" && v_NUM_ORDEM != ""){
				if(InserirItemArray(aEquipeAux, v_NOM_LOGIN_REDE_EQUIPE) == true){
					InserirItemArray(aEquipe, v_NOM_LOGIN_REDE_EQUIPE+"|"+v_NUM_ORDEM);


					valor1 = v_NOM_LOGIN_REDE_EQUIPE;
					valor2 = v_NOME_EMPREGADO;
					valor3 = v_NUM_ORDEM;

					var tabela = document.getElementById("equipe");

					// Primeiro testamos se a primeira linha nao eh a mensagem "Sem dados..."
					if(tabela.rows.length>1){
						if(tabela.rows[1].cells[0].innerHTML=="Sem dados a serem exibidos")
							tabela.deleteRow(1); // se for apagamos
					}

					proxLinha = tabela.rows.length; // pega o total de linhas da tabela para acrescentar a nova
					var linha = tabela.insertRow(proxLinha); // Insere uma nova linha
					var coluna1 = linha.insertCell(0);
					var coluna2 = linha.insertCell(1);
					var coluna3 = linha.insertCell(2);
					var colunaCancela = linha.insertCell(3);

					setRowIndex(aEquipeAux, v_NOM_LOGIN_REDE_EQUIPE, linha.rowIndex);
					setRowIndex(aEquipe, v_NOM_LOGIN_REDE_EQUIPE+"|"+v_NUM_ORDEM, linha.rowIndex);

					//Abaixo inserimos o conteudo nas colunas criadas
					coluna1.innerHTML=valor1;
					coluna1.setAttribute("align", "left", "id=".$pagina->GetIdTable());
					coluna2.innerHTML=valor2;
					coluna2.setAttribute("align", "left", "id=".$pagina->GetIdTable());
					coluna3.innerHTML=valor3;
					colunaCancela.innerHTML="<span onclick='fRetiraItemEquipe("+linha.rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
				}else{
					alert("Colaborador já incluído");
				}
			}else{
				alert("Os campos Matricula colaborador e Quantidade de horas semanais são obrigatórios");
			}
			return false;
		}
		function fRetiraItemEquipe(id){
			var tabela = document.getElementById("equipe");
			if(confirm("Tem certeza que deseja retirar a linha?")) {
				aEquipeAux = ExcluirItemArray(aEquipeAux, id);
				aEquipe = ExcluirItemArray(aEquipe, id);
				tabela.deleteRow(id);
				if(tabela.rows.length<2){
					var linha = tabela.insertRow(1); // Insere uma nova linha
					var coluna = linha.insertCell(0); // Insere uma coluna na linha
					coluna.setAttribute("colspan", 4); // Define colspan = 2
					coluna.innerHTML="Sem dados a serem exibidos"; // Texto informativo
				}else{
					// Refazer a coluna de exclusão da linha
					for(i=1;i<tabela.rows.length;i++){
						tabela.rows[i].cells[tabela.rows[i].cells.length - 1].innerHTML="<span onclick='fRetiraItemEquipe("+tabela.rows[i].rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
               		}
	            }
			}
		}
	</script>
	<?
	$pagina->LinhaCampoFormularioColspanDestaque("Equipe Envolvida", 0);
	$header = array();

	$tabela = array();
	$header = array();
	$header[] = array("&nbsp;", "center", "3%", "header");
	$header[] = array("Login", "center", "20%", "header");
	$header[] = array("Nome", "center", "45%", "header");
	$header[] = array("Ordem", "center", "20%", "header");
	$tabela[] = $header;
	require_once 'include/PHP/class/class.equipe_servidor.php';
	require_once 'include/PHP/class/class.empregados.oracle.php';
	$equipe_envolvida = new equipe_servidor();
	$equipe_envolvida->setSEQ_SERVIDOR($v_SEQ_SERVIDOR);
	$equipe_envolvida->selectParam();
	if($equipe_envolvida->database->rows > 0){
		while ($row = pg_fetch_array($equipe_envolvida->database->result)){
			$empregados->select($row["num_matricula_recurso"]);
			$header = array();
			$header[] = array($pagina->BotaoLupa("Recurso_tiDetalhes.php?v_NUM_MATRICULA_RECURSO=".$row["num_matricula_recurso"], "Detalhes do Profissional "), "left", "", "");
			$header[] = array($empregados->NOM_LOGIN_REDE, "center", "15%", "");
			$header[] = array($empregados->NOME, "center", "30%", "");
			$header[] = array($row["num_ordem"], "center", "40%", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"",""), "6");
	}else{
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", "6");
	}
	$pagina->FechaTabelaPadrao();

	$pagina->AbreTabelaPadrao("center", "100%", "id=\"tabelaSistemas\"  style=\"display: none;\"  cellpadding=\"0\" cellspacing=\"0\" border=\"0\"");
	$pagina->LinhaCampoFormularioColspanDestaque("Sistemas de Informação Relacionados", 0);

	$item_configuracao->GetSistemasAfetadosPorServidor($v_SEQ_SERVIDOR);
	if($item_configuracao->database->rows > 0){
		$tabela = array();
		$header = array();
		$header[] = array("&nbsp;", "center", "3%", "header");
		$header[] = array("Sigla", "center", "25%", "header");
		$header[] = array("Nome", "center", "35%", "header");
		$header[] = array("Tipo de Relac.", "center", "30%", "header");
		$header[] = array("Área", "center", "10%", "header");
		$tabela[] = $header;
		while ($rowSistemasAfetados = pg_fetch_array($item_configuracao->database->result)){
			$header = array();
			$header[] = array($pagina->BotaoLupa("Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$rowSistemasAfetados["seq_item_configuracao"], "Detalhes de ".$rowSistemasAfetados["nom_item_configuracao"]), "left", "3%", "");
			$header[] = array($rowSistemasAfetados["sig_item_configuracao"], "left", "15%", "");
			$header[] = array($rowSistemasAfetados["nom_item_configuracao"], "left", "30%", "");
			$header[] = array($rowSistemasAfetados["nom_tipo_relac_item_config"], "left", "40%", "");
			$header[] = array($unidades_organizacionais->GetUorSigla($rowSistemasAfetados["cod_uor_area_gestora"]), "center", "10%", "");
			$tabela[] = $header;
		}
		$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%","",true,"",""), "6");
	}else{
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", "6");
	}

	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	$pagina->mensagem("Selecione um servidor para detalhar.");
}
?>
