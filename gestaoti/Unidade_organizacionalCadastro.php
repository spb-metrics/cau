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
require 'include/PHP/class/class.pagina.php';
require 'include/PHP/class/class.unidade_organizacional.php';
require 'include/PHP/class/class.empregados.oracle.php';
$pagina = new Pagina();
$banco = new unidade_organizacional();
$empregados = new empregados();
$unidade_organizacional = new unidade_organizacional();
if($flag == ""){
	// Configura��o da p�g�na
	$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
	$pagina->SettituloCabecalho("Cadastro de Unidade Organizacional"); // Indica o t�tulo do cabe�alho da p�gina
	// Itens das abas
	$aItemAba = Array( array("Unidade_organizacionalPesquisa.php", "", "Pesquisa"),
			   array("Unidade_organizacionalCadastro.php", "tabact", "Adicionar"));
	$pagina->SetaItemAba($aItemAba);
	// Inicio do formul�rio
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
	$pagina->LinhaCampoFormularioColspanDestaque("Respons�vel pela unidade", 2);
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
					alert("Respons�vel j� adicionado");
				}
			}else{
				alert("O campo Pessoa � obrigat�rio");
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
					// Refazer a coluna de exclus�o da linha
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

	$header[] = array("Respons�vel", "center", "85%");
	$header[] = array("  Excluir  ", "center", "15%");

	$pagina->LinhaCampoFormularioColspan("center", $pagina->TabelaDin�mica("responsavel", $header, "100%"), 2);
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
	// C�digo inserido: $banco->SEQ_UNIDADE_ORGANIZACIONAL
        
        // Inserir o respons�vel
        if(trim($aResponsavelUnidade) != ""){
            require 'include/PHP/class/class.responsavel_unidade_organizacional.php';
            $responsavel_unidade_organizacional = new responsavel_unidade_organizacional();
            $responsavel_unidade_organizacional->setSEQ_UNIDADE_ORGANIZACIONAL($banco->SEQ_UNIDADE_ORGANIZACIONAL);
            $a_RESPONSAVEL = split(";", $aResponsavelUnidade);
            
            for ($i = 0; $i < count($a_RESPONSAVEL); $i++){
                    // Pegar vari�veis
                    $v_SEQ_PESSOA = $a_RESPONSAVEL[$i];
                    $responsavel_unidade_organizacional->setSEQ_PESSOA($v_SEQ_PESSOA);
                    $responsavel_unidade_organizacional->insert();
            }
        }
        
	if($banco->error != ""){
		$pagina->mensagem("Registro n�o inclu�do. O seguinte erro ocorreu:<br> $banco->error");
	}else{
		$pagina->redirectTo("Unidade_organizacionalPesquisa.php?flag=1&v_SEQ_UNIDADE_ORGANIZACIONAL=$banco->SEQ_UNIDADE_ORGANIZACIONAL");
	}
}
?>
