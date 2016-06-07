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
require 'include/PHP/class/class.responsavel_unidade_organizacional.php';
$pagina = new Pagina();
$banco = new unidade_organizacional();
$unidade_organizacional = new unidade_organizacional();
// Configura��o da p�g�na
$pagina->SettipoPagina("S"); // Indica a finalidade da p�gina - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa de Unidades Organizacionais"); // Indica o t�tulo do cabe�alho da p�gina
// Itens das abas
$aItemAba = Array( array("Unidade_organizacionalPesquisa.php", "tabact", "Pesquisa"),
                   array("Unidade_organizacionalCadastro.php", "", "Adicionar") );
$pagina->SetaItemAba($aItemAba);
// Inicio do formul�rio
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2"){
	$banco->delete($v_SEQ_UNIDADE_ORGANIZACIONAL);
        
        // Deletar as responsabilidades
        $responsavel_unidade_organizacional = new responsavel_unidade_organizacional();
        $responsavel_unidade_organizacional->deleteBySEQ_UNIDADE_ORGANIZACIONAL($v_SEQ_UNIDADE_ORGANIZACIONAL);
        
	$pagina->ScriptAlert("Registro Exclu�do");
	$v_SEQ_UNIDADE_ORGANIZACIONAL = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_UNIDADE_ORGANIZACIONAL", "");

/* Inicio da tabela de par�metros */
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
	$header[] = array("Respons�vel", "");

	// Setar vari�veis
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
                        
                        // Buscar informa��es sobre o respons�vel
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
