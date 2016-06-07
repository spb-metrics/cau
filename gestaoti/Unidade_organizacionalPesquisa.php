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
require 'include/PHP/class/class.responsavel_unidade_organizacional.php';
$pagina = new Pagina();
$banco = new unidade_organizacional();
$unidade_organizacional = new unidade_organizacional();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa de Unidades Organizacionais"); // Indica o título do cabeçalho da página
// Itens das abas
$aItemAba = Array( array("Unidade_organizacionalPesquisa.php", "tabact", "Pesquisa"),
                   array("Unidade_organizacionalCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_UNIDADE_ORGANIZACIONAL);
        
        // Deletar as responsabilidades
        $responsavel_unidade_organizacional = new responsavel_unidade_organizacional();
        $responsavel_unidade_organizacional->deleteBySEQ_UNIDADE_ORGANIZACIONAL($v_SEQ_UNIDADE_ORGANIZACIONAL);
        
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_UNIDADE_ORGANIZACIONAL = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_UNIDADE_ORGANIZACIONAL", "");

/* Inicio da tabela de parâmetros */
$pagina->AbreTabelaPadrao("center", "85%");


// Montar a combo da tabela edificacao
require_once 'include/PHP/class/class.edificacao.php';
$edificacao = new edificacao();
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_UNIDADE_ORGANIZACIONAL", "N", "Nome", "60", "60", $v_NOM_UNIDADE_ORGANIZACIONAL), "left");
$pagina->LinhaCampoFormulario("Sigla:", "right", "N", $pagina->CampoTexto("v_SGL_UNIDADE_ORGANIZACIONAL", "N", "Nome", "60", "60", $v_SGL_UNIDADE_ORGANIZACIONAL), "left");
$pagina->LinhaCampoFormulario("Unidade organizacional superior:", "right", "N", $pagina->CampoSelect("v_SEQ_UNIDADE_ORGANIZACIONAL_PAI", "N", "Unidade pai", "S", $banco->combo("NOM_UNIDADE_ORGANIZACIONAL", $v_SEQ_UNIDADE_ORGANIZACIONAL_PAI)), "left");

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();

//if($flag == "1"){
//	$pagina->LinhaVazia(1);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("&nbsp;", "5%");
	$header[] = array("Nome", "30%");
	$header[] = array("Sigla", "");
	$header[] = array("Unidade superior", "");
	$header[] = array("Responsável", "");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_UNIDADE_ORGANIZACIONAL_PAI($v_SEQ_UNIDADE_ORGANIZACIONAL_PAI);
	$banco->setNOM_UNIDADE_ORGANIZACIONAL($v_NOM_UNIDADE_ORGANIZACIONAL);
    $banco->setSGL_UNIDADE_ORGANIZACIONAL($v_SGL_UNIDADE_ORGANIZACIONAL);
	$banco->selectParam("NOM_UNIDADE_ORGANIZACIONAL", $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Unidades organizacionais", $header);
		while ($row = pg_fetch_array($banco->database->result)){
			$valor = $pagina->BotaoAlteraGridPesquisa("Unidade_organizacionalAlteracao.php?v_SEQ_UNIDADE_ORGANIZACIONAL=".$row["seq_unidade_organizacional"]."");
			$valor .= $pagina->BotaoExcluiGridPesquisa("v_SEQ_UNIDADE_ORGANIZACIONAL", $row["seq_unidade_organizacional"]);
			$corpo[] = array("center", "campo", $valor);
			// Buscar dados da tabela externa
			
                        $corpo[] = array("left", "campo", $row["nom_unidade_organizacional"]);
                        $corpo[] = array("left", "campo", $row["sgl_unidade_organizacional"]);
                        
                        if($row["seq_unidade_organizacional_pai"] != ""){
                            $unidade_organizacional = new unidade_organizacional();
                            $unidade_organizacional->select($row["seq_unidade_organizacional_pai"]);
                            $corpo[] = array("left", "campo", $unidade_organizacional->NOM_UNIDADE_ORGANIZACIONAL);
                        }else{
                            $corpo[] = array("left", "campo", "---");
                        }
                        
                        // Buscar informações sobre o responsável
						$responsavel_unidade_organizacional = new responsavel_unidade_organizacional();
                        $responsavel_unidade_organizacional->setSEQ_UNIDADE_ORGANIZACIONAL($row["seq_unidade_organizacional"]);
                        $responsavel_unidade_organizacional->selectParam("1","1");
                        if($responsavel_unidade_organizacional->database->rows > 0){
                            $responsaveis = "";
                            $contUnidade = 1;
                            while ($rowUnidadeOrganizacional = pg_fetch_array($responsavel_unidade_organizacional->database->result)){
                                $responsaveis .= $rowUnidadeOrganizacional["nome"];
                                if($contUnidade != $responsavel_unidade_organizacional->database->rows){
                                    $responsaveis .= "<br>";
                                }
                                $contUnidade++;
                            }
                            $corpo[] = array("left", "campo", $responsaveis);
                        }else{
                            $corpo[] = array("left", "campo", "---");
                        }

			$pagina->LinhaTabelaResultado($corpo);
			$corpo = "";
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_UNIDADE_ORGANIZACIONAL=$v_SEQ_UNIDADE_ORGANIZACIONAL&v_SEQ_EDIFICACAO=$v_SEQ_EDIFICACAO&v_NOM_UNIDADE_ORGANIZACIONAL=$v_NOM_UNIDADE_ORGANIZACIONAL");
//}
$pagina->MontaRodape();
?>
