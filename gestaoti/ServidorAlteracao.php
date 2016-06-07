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
$pagina = new Pagina();
$banco = new servidor();
$empregados = new empregados();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Alterar Servidor"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Item_configuracaoPesquisa.php", "", "Pesquisa"),
						array("ServidorCadastro.php", "", "Adicionar"),
						array("ServidorDetalhes.php?v_SEQ_SERVIDOR=$v_SEQ_SERVIDOR", "", "Detalhes"),
		 			    array("#", "tabact", "Alterar") );
	$pagina->SetaItemAba($aItemAba);
	// Pesquisar
	$banco->select($v_SEQ_SERVIDOR);

	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
	print $pagina->CampoHidden("v_SEQ_SERVIDOR", $v_SEQ_SERVIDOR);
	print $pagina->CampoHidden("v_DAT_CRIACAO", $v_DAT_CRIACAO);
	$pagina->AbreTabelaPadrao("center", "85%");

	$pagina->LinhaCampoFormulario("Patrimônio:", "right", "S", $pagina->CampoTexto("v_NUM_PATRIMONIO", "S", "Patrimônio", "15", "15", "$banco->NUM_PATRIMONIO"), "left");
	$pagina->LinhaCampoFormulario("IP:", "right", "S", $pagina->CampoTexto("v_NUM_IP", "S", "Número de Ip", "15", "15", "$banco->NUM_IP"), "left");
	$pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_SERVIDOR", "S", "Nome", "60", "60", "$banco->NOM_SERVIDOR"), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.sistema_operacional.php';
	$sistema_operacional = new sistema_operacional();
	$aItemOption = Array();

	$sistema_operacional->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($sistema_operacional->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($banco->SEQ_SISTEMA_OPERACIONAL == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Sistema operacional:", "right", "N", $pagina->CampoSelect("v_SEQ_SISTEMA_OPERACIONAL", "N", "Sistema operacional", "S", $aItemOption), "left");

	// Buscar dados da tabela externa
	require_once 'include/PHP/class/class.marca_hardware.php';
	$marca_hardware = new marca_hardware();
	$aItemOption = Array();

	$marca_hardware->selectParam(2);
	$cont = 0;
	while ($row = pg_fetch_array($marca_hardware->database->result)){
		$aItemOption[$cont] = array($row[0], $pagina->iif($banco->SEQ_MARCA_HARDWARE == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
	// Adicionar combo no formulário
	$pagina->LinhaCampoFormulario("Marca:", "right", "N", $pagina->CampoSelect("v_SEQ_MARCA_HARDWARE", "N", "Marca hardware", "S", $aItemOption), "left");
	$pagina->LinhaCampoFormulario("Modelo:", "right", "N", $pagina->CampoTexto("v_NOM_MODELO", "N", "Nome de Modelo", "60", "60", "$banco->NOM_MODELO"), "left");
	$pagina->LinhaCampoFormulario("Descrição:", "right", "N", $pagina->CampoTexto("v_DSC_SERVIDOR", "N", "Descrição", "50", "50", "$banco->DSC_SERVIDOR"), "left");
	$pagina->LinhaCampoFormulario("Localização:", "right", "N", $pagina->CampoTexto("v_DSC_LOCALIZACAO", "N", "Descrição de Localizacao", "50", "50", "$banco->DSC_LOCALIZACAO"), "left");
	$pagina->LinhaCampoFormulario("Processadores:", "right", "N", $pagina->CampoTexto("v_DSC_PROCESSADOR", "N", "Descrição de Processadores", "60", "60", "$banco->DSC_PROCESSADOR"), "left");
	$pagina->LinhaCampoFormulario("Observação:", "right", "N", $pagina->CampoTexto("v_TXT_OBSERVACAO", "N", "Descrição de Observacao", "50", "50", "$banco->TXT_OBSERVACAO"), "left");
	$pagina->FechaTabelaPadrao();
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
					coluna1.setAttribute("align", "left");
					coluna2.innerHTML=valor2;
					coluna2.setAttribute("align", "left");
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
	<hr>
	<?
	$pagina->AbreTabelaPadrao("center", "100%", "id=tabelaDependencias cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	print $pagina->CampoHidden("aEquipe", "");
	print $pagina->CampoHidden("v_NOME_EMPREGADO", "");

	$tabela = array();
	$header = array();
	$header[] = array("Colaborador", "center", "", "header");
	$header[] = array("Ordem", "center", "", "header");
	$header[] = array("&nbsp;", "center", "5%", "header");
	$tabela[] = $header;
	$header = array();
	$header[] = array($pagina->CampoTexto("v_NOM_LOGIN_REDE_EQUIPE", "N", "" , "10", "10", "", "readonly").
					  $pagina->ButtonProcuraEmpregado("v_NOM_LOGIN_REDE_EQUIPE", "", "v_NOME_EMPREGADO"), "center", "", "");
	$header[] = array($pagina->CampoTexto("v_NUM_ORDEM", "N", "" , "3", "3", ""), "center", "", "");
	$header[] = array($pagina->CampoButton("return fAdicionaItemEquipe(document.form.v_NOM_LOGIN_REDE_EQUIPE.value, document.form.v_NUM_ORDEM.value, document.form.v_NOME_EMPREGADO.value);", "Adicionar", "button"), "center", "", "");
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "50%"), 2);
	$header = array();

	$header[] = array("Login", "center", "20%");
	$header[] = array("Nome", "center", "45%");
	$header[] = array("Ordem", "center", "20%");
	$header[] = array("  Excluir  ", "center", "15%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDinâmica("equipe", $header, "85%"), 2);
	$header = "";
	$pagina->LinhaCampoFormularioColspan("center", "&nbsp;", 2);
	?>
	<script>
		<?
		// Inserir equipe existente em banco de dados na tela
		require_once 'include/PHP/class/class.equipe_servidor.php';
		require_once 'include/PHP/class/class.empregados.oracle.php';
		$equipe_envolvida = new equipe_servidor();
		$equipe_envolvida->setSEQ_SERVIDOR($v_SEQ_SERVIDOR);
		$equipe_envolvida->selectParam();
		if($equipe_envolvida->database->rows > 0){
			while ($row = pg_fetch_array($equipe_envolvida->database->result)){
				$empregados->select($row["num_matricula_recurso"]);
				?>fAdicionaItemEquipe('<?=$empregados->NOM_LOGIN_REDE?>', '<?=$row["num_ordem"]?>', '<?=$empregados->NOME?>');
				<?
			}
		}

		?>
	</script>
	<?
		?>
	<script>
		function fValidaFormLocal(){
			FormatarArrayInsercao(aEquipe, document.form.aEquipe);
			return true;
		}
	</script>
	<?
	$pagina->FechaTabelaPadrao();
	$pagina->AbreTabelaPadrao("center", "85%");
	$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm() && fValidaFormLocal();", " Alterar "), "2");
	$pagina->FechaTabelaPadrao();
	$pagina->MontaRodape();
}else{
	// Alterar regstro

	$banco->setSEQ_SISTEMA_OPERACIONAL($v_SEQ_SISTEMA_OPERACIONAL);
	$banco->setSEQ_MARCA_HARDWARE($v_SEQ_MARCA_HARDWARE);
	$banco->setNUM_PATRIMONIO($v_NUM_PATRIMONIO);
	$banco->setNUM_IP($v_NUM_IP);
	$banco->setNOM_SERVIDOR($v_NOM_SERVIDOR);
	$banco->setNOM_MODELO($v_NOM_MODELO);
	$banco->setDSC_SERVIDOR($v_DSC_SERVIDOR);
	$banco->setDSC_LOCALIZACAO($v_DSC_LOCALIZACAO);
	$banco->setDSC_PROCESSADOR($v_DSC_PROCESSADOR);
	$banco->setTXT_OBSERVACAO($v_TXT_OBSERVACAO);
	if($v_DAT_CRIACAO == '' || $v_DAT_CRIACAO == '0000-00-00'){
		$banco->setDAT_CRIACAO(date("Y-m-d"));
	}else{
		$banco->setDAT_CRIACAO($pagina->ConvDataAMD($v_DAT_CRIACAO));
	}

	$banco->setDAT_ALTERACAO(date("Y-m-d"));
	$banco->update($v_SEQ_SERVIDOR);
	if($banco->error != ""){
		$pagina->mensagem("Registro não alterado. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		// Inserir equipe
		require_once 'include/PHP/class/class.equipe_servidor.php';
		$equipe_servidor = new equipe_servidor();
		$equipe_servidor->delete($v_SEQ_SERVIDOR);
		if(trim($aEquipe) != ""){
			$a_EQUIPE_ENVOLVIDA = split(";", $aEquipe);
			$equipe_servidor->setSEQ_SERVIDOR($v_SEQ_SERVIDOR);
			for ($i = 0; $i < count($a_EQUIPE_ENVOLVIDA); $i++){
			// Pegar variáveis
				$aAux = split("\|", $a_EQUIPE_ENVOLVIDA[$i]);
				$v_NUM_MATRICULA_RECURSO = $aAux[0];
				$v_NUM_ORDEM = $aAux[1];
				// Setar variáveis
				$equipe_servidor->setNUM_MATRICULA_RECURSO($empregados->GetNumeroMatricula($v_NUM_MATRICULA_RECURSO));
				$equipe_servidor->setNUM_ORDEM($v_NUM_ORDEM);
				$equipe_servidor->insert();
			}
		}
		$pagina->redirectTo("Item_configuracaoPesquisa.php?flag=1&v_SEQ_SERVIDOR=$v_SEQ_SERVIDOR");
	}
}
?>
