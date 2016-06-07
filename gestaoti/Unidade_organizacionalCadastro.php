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
require 'include/PHP/class/class.unidade_organizacional.php';
require 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$banco = new unidade_organizacional();
$empregados = new empregados();
$unidade_organizacional = new unidade_organizacional();
if($flag == ""){
	// Configuração da págína
	$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Unidade Organizacional"); // Indica o título do cabeçalho da página
	// Itens das abas
	$aItemAba = Array( array("Unidade_organizacionalPesquisa.php", "", "Pesquisa"),
			   array("Unidade_organizacionalCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formulário
	$pagina->MontaCabecalho();

	print $pagina->CampoHidden("flag", "1");
        print $pagina->CampoHidden("aResponsavelUnidade", "");
	$pagina->AbreTabelaPadrao("center", "85%");
        
        $pagina->LinhaCampoFormulario("Nome:", "right", "S", $pagina->CampoTexto("v_NOM_UNIDADE_ORGANIZACIONAL", "S", "Nome", "60", "60", ""), "left");
        $pagina->LinhaCampoFormulario("Sigla:", "right", "S", $pagina->CampoTexto("v_SGL_UNIDADE_ORGANIZACIONAL", "S", "Nome", "60", "60", ""), "left");
	// Montar a combo da tabela edificacao
	require_once 'include/PHP/class/class.edificacao.php';
	$edificacao = new edificacao();
	$pagina->LinhaCampoFormulario("Unidade organizacional superior:", "right", "N", $pagina->CampoSelect("v_SEQ_UNIDADE_ORGANIZACIONAL_PAI", "S", "", "S", $unidade_organizacional->combo("NOM_UNIDADE_ORGANIZACIONAL", "")), "left");

	$pagina->FechaTabelaPadrao();

        //================================================================================================================================
	//================================================================================================================================
        $pagina->AbreTabelaPadrao("center", "100%", "id=tabelaResponsavel cellpadding=\"0\" cellspacing=\"0\" border=\"0\" ");
	$pagina->LinhaCampoFormularioColspanDestaque("Responsável pela unidade", 2);
	?>
	<script language="javascript">
	// ==================================================================================================================
	// AREA ENVOLVIDA ===================================================================================================
                var aResponsavelAux = new Array();
                var aResponsavel = new Array();
                
                function fValidaFormLocal(){
                    if(document.form.v_NOM_UNIDADE_ORGANIZACIONAL.value == ""){
                        alert("Preencha o campo nome.");
                        document.form.v_NOM_UNIDADE_ORGANIZACIONAL.focus();
                        return false;
                    }
                    
                    if(document.form.v_SGL_UNIDADE_ORGANIZACIONAL.value == ""){
                        alert("Preencha o campo sigla.");
                        document.form.v_SGL_UNIDADE_ORGANIZACIONAL.focus();
                        return false;
                    }
                    FormatarArrayInsercao(aResponsavel, document.form.aResponsavelUnidade);
                    return true;
                }
                
		function fAdicionaResponsavel(v_SEQ_PESSOA){
			if(v_SEQ_PESSOA.value != ""){
				if(InserirItemArray(aResponsavelAux, v_SEQ_PESSOA.value) == true){
					InserirItemArray(aResponsavel, v_SEQ_PESSOA.value);
                                        
					valor1 = v_SEQ_PESSOA.options[v_SEQ_PESSOA.selectedIndex].text;

					var tabela = document.getElementById("responsavel");

					// Primeiro testamos se a primeira linha nao eh a mensagem "Sem dados..."
					if(tabela.rows.length>1){
						if(tabela.rows[1].cells[0].innerHTML=="Sem dados a serem exibidos")
							tabela.deleteRow(1); // se for apagamos
					}


					proxLinha = tabela.rows.length; // pega o total de linhas da tabela para acrescentar a nova
					var linha = tabela.insertRow(proxLinha); // Insere uma nova linha
					var coluna1 = linha.insertCell(0);
					var colunaCancela = linha.insertCell(1);

					setRowIndex(aResponsavelAux, v_SEQ_PESSOA.value, linha.rowIndex);
					setRowIndex(aResponsavel, v_SEQ_PESSOA.value, linha.rowIndex);

					//Abaixo inserimos o conteudo nas colunas criadas
					coluna1.innerHTML=valor1;
					coluna1.setAttribute("align", "left");
					colunaCancela.innerHTML="<span onclick='fRetiraResponsavel("+linha.rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
				}else{
					alert("Responsável já adicionado");
				}
			}else{
				alert("O campo Pessoa é obrigatório");
			}
			return false;
		}
		function fRetiraResponsavel(id){
			var tabela = document.getElementById("responsavel");
			if(confirm("Tem certeza que deseja retirar a linha?")) {
				aResponsavelAux = ExcluirItemArray(aResponsavelAux, id);
				aResponsavel = ExcluirItemArray(aResponsavel, id);
				tabela.deleteRow(id);
				if(tabela.rows.length<2){
					var linha = tabela.insertRow(1); // Insere uma nova linha
					var coluna = linha.insertCell(0); // Insere uma coluna na linha
					coluna.setAttribute("cols", 3); // Define colspan = 2
					coluna.innerHTML="Sem dados a serem exibidos"; // Texto informativo
				}else{
					// Refazer a coluna de exclusão da linha
					for(i=1;i<tabela.rows.length;i++){
                                            tabela.rows[i].cells[tabela.rows[i].cells.length - 1].innerHTML="<span onclick='fRetiraAreaEnvolvida("+tabela.rows[i].rowIndex+")' style='cursor: pointer'><img src='imagens/excluir.gif' border='0'></span>";
                                        }
                                }
			}
		}
	</script>
	<?

	print $pagina->CampoHidden("aResponsavel", "");

	$tabela = array();
	$header = array();
	$header[] = array("Pessoa", "center", "", "header");
        $header[] = array("&nbsp;", "center", "5%", "header");
	$tabela[] = $header;
	$header = array();
	$header[] = array($pagina->CampoSelect("v_SEQ_PESSOA", "S", "", "S", $empregados->combo("NOME", ""))."&nbsp;".
                          $pagina->CampoButton("return fAdicionaResponsavel(document.form.v_SEQ_PESSOA);", "Adicionar", "button")
                            , "center", "", "");
	
	$tabela[] = $header;
	$pagina->LinhaCampoFormularioColspan("center", $pagina->Tabela($tabela, "100%"), 2);
	$header = array();

	$header[] = array("Responsável", "center", "85%");
	$header[] = array("  Excluir  ", "center", "15%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDinâmica("responsavel", $header, "100%"), 2);
	$header = "";
	$pagina->LinhaCampoFormularioColspan("center", "&nbsp;", 2);
        
        $pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaFormLocal();", " Incluir "), "2");
	
        $pagina->FechaTabelaPadrao();
        
	$pagina->MontaRodape();
}else{
	// Incluir regstro
	$banco->setSEQ_UNIDADE_ORGANIZACIONAL_PAI($v_SEQ_UNIDADE_ORGANIZACIONAL_PAI);
	$banco->setNOM_UNIDADE_ORGANIZACIONAL($v_NOM_UNIDADE_ORGANIZACIONAL);
        $banco->setSGL_UNIDADE_ORGANIZACIONAL($v_SGL_UNIDADE_ORGANIZACIONAL);
	
        $banco->insert();
	// Código inserido: $banco->SEQ_UNIDADE_ORGANIZACIONAL
        
        // Inserir o responsável
        if(trim($aResponsavelUnidade) != ""){
            require 'include/PHP/class/class.responsavel_unidade_organizacional.php';
            $responsavel_unidade_organizacional = new responsavel_unidade_organizacional();
            $responsavel_unidade_organizacional->setSEQ_UNIDADE_ORGANIZACIONAL($banco->SEQ_UNIDADE_ORGANIZACIONAL);
            $a_RESPONSAVEL = split(";", $aResponsavelUnidade);
            
            for ($i = 0; $i < count($a_RESPONSAVEL); $i++){
                    // Pegar variáveis
                    $v_SEQ_PESSOA = $a_RESPONSAVEL[$i];
                    $responsavel_unidade_organizacional->setSEQ_PESSOA($v_SEQ_PESSOA);
                    $responsavel_unidade_organizacional->insert();
            }
        }
        
	if($banco->error != ""){
		$pagina->mensagem("Registro não incluído. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Unidade_organizacionalPesquisa.php?flag=1&v_SEQ_UNIDADE_ORGANIZACIONAL=$banco->SEQ_UNIDADE_ORGANIZACIONAL");
	}
}
?>
