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
require 'include/PHP/class/class.item_configuracao.php';
//require 'include/PHP/class/class.patrimonio_ti.ativos.php';
//require 'include/PHP/class/class.patrimonio_ti.catalogo.php';
require 'include/PHP/class/class.unidade_organizacional.php';
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.servidor.php';
require 'include/PHP/class/class.sistema_operacional.php';
require 'include/PHP/class/class.marca_hardware.php';
require 'include/PHP/class/class.equipe_servidor.php';
$pagina = new Pagina();
$banco = new item_configuracao();
//$bancoPatrimonio = new ativos();
//$patrimonioCategoria = new catalogo();
$unidade_organizacional = new unidade_organizacional();
$empregados = new empregados();
$servidor = new servidor();
$sistema_operacional = new sistema_operacional();
$equipe_servidor = new equipe_servidor();
$marca_hardware = new marca_hardware();

// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Pesquisa Parque Tecnológico"); // Indica o título do cabeçalho da página
// Itens das abas
$pagina->ForcaAutenticacao();
if($pagina->segurancaPerfilAlteracao($_SESSION["SEQ_PERFIL_ACESSO"])){
	$aItemAba = Array( array($PHP_SELF, "tabact", "Pesquisa"),
				   array("Item_configuracaoCadastro1.php", "", "Adicionar") );
}else{
	$aItemAba = Array( array("#", "tabact", "Pesquisa"));
}
$pagina->SetaItemAba($aItemAba);
// Inicio do formulário
$pagina->MontaCabecalho();
// Deletar registro
if($flag == "2" && $v_SEQ_ITEM_CONFIGURACAO != ""){
	require_once 'include/PHP/class/class.relacionamento_item_configuracao.php';
	$relacionamento_item_configuracao = new relacionamento_item_configuracao();
	$relacionamento_item_configuracao->setSEQ_ITEM_CONFIGURACAO_FILHO($v_SEQ_ITEM_CONFIGURACAO);
	$relacionamento_item_configuracao->selectParam();
	if($relacionamento_item_configuracao->database->rows == 0){
		$relacionamento_item_configuracao = new relacionamento_item_configuracao();
		$relacionamento_item_configuracao->deleteAll($v_SEQ_ITEM_CONFIGURACAO);

		// deletar area externa envolvida
		require_once 'include/PHP/class/class.area_externa_envolvida.php';
		$area_externa_envolvida = new area_externa_envolvida();
		$area_externa_envolvida->delete($v_SEQ_ITEM_CONFIGURACAO);

		// deletar áreas envolvidas
		require_once 'include/PHP/class/class.area_envolvida.php';
		$area_envolvida = new area_envolvida();
		$area_envolvida->delete($v_SEQ_ITEM_CONFIGURACAO);

		// Deletar relacionamentos
		require_once 'include/PHP/class/class.relacionamento_item_configuracao.php';
		$relacionamento_item_configuracao = new relacionamento_item_configuracao();
		$relacionamento_item_configuracao->delete($v_SEQ_ITEM_CONFIGURACAO);

		// Deletar equipe
		require_once 'include/PHP/class/class.equipe_envolvida.php';
		$equipe_envolvida = new equipe_envolvida();
		$equipe_envolvida->delete1($v_SEQ_ITEM_CONFIGURACAO);

		// Deletar bancos de dados
		require_once 'include/PHP/class/class.software_banco_de_dados.php';
		$software_banco_de_dados = new software_banco_de_dados();
		$software_banco_de_dados->delete($v_SEQ_ITEM_CONFIGURACAO);

		// Deletar linguagens de programação
		require_once 'include/PHP/class/class.software_linguagem_programacao.php';
		$software_linguagem_programacao = new software_linguagem_programacao();
		$software_linguagem_programacao->setSEQ_ITEM_CONFIGURACAO($banco->SEQ_ITEM_CONFIGURACAO);
		$software_linguagem_programacao->delete($v_SEQ_ITEM_CONFIGURACAO);

		// Deletar dados do sistema de informação
		require_once 'include/PHP/class/class.item_configuracao_software.php';
		$item_configuracao_software = new item_configuracao_software();
		$item_configuracao_software->delete($v_SEQ_ITEM_CONFIGURACAO);

		// Deletar histórico
		require_once 'include/PHP/class/class.fase_item_configuracao.php';
		$fase_item_configuracao = new fase_item_configuracao();
		$fase_item_configuracao->delete($v_SEQ_ITEM_CONFIGURACAO);

		// Deletar inoperância
		require_once 'include/PHP/class/class.inoperancia.php';
		$inoperancia = new inoperancia();
		$inoperancia->delete($v_SEQ_ITEM_CONFIGURACAO);

		// Apagar registro
		$banco->delete($v_SEQ_ITEM_CONFIGURACAO);
		$pagina->ScriptAlert("Registro Excluído");
		$v_SEQ_ITEM_CONFIGURACAO = "";
	}else{
		$pagina->ScriptAlert("Não é possível efetuar a exclusão pois existem outros sistemas que possuem interface com este.");
	}
}

if($flag == "2" && $v_SEQ_SERVIDOR != ""){
	$equipe_servidor->delete($v_SEQ_SERVIDOR);

	$servidor->delete($v_SEQ_SERVIDOR);
	$pagina->ScriptAlert("Registro Excluído");
	$v_SEQ_SERVIDOR = "";
}

print $pagina->CampoHidden("flag", "1");
print $pagina->CampoHidden("v_SEQ_ITEM_CONFIGURACAO", "");
print $pagina->CampoHidden("v_SEQ_SERVIDOR", "");

?>
<script language="javascript">
		function fMostraCampos(){
			if(document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO.value == "1"){ // Hardware e Infra
				// Software
				document.getElementById("v_SIG_ITEM_CONFIGURACAO").style.display = "none";
				document.getElementById("v_NOM_ITEM_CONFIGURACAO").style.display = "none";
				document.getElementById("vOrderBy").style.display = "none";
				document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";
				document.getElementById("v_NUM_MATRICULA_LIDER").style.display = "none";
				document.getElementById("v_NUM_MATRICULA_GESTOR").style.display = "none";
				document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";

				// Suporte
				//document.getElementById("v_COD_CATEGORIA").style.display = "block";
				//document.getElementById("v_QTD_VIDA_UTIL_TRANSCORRIDA").style.display = "block";
				//document.getElementById("v_CRITICIDADE").style.display = "block";
				//document.getElementById("v_COD_REGIONAL").style.display = "block";
				//document.getElementById("v_COD_DEPENDENCIA").style.display = "block";
				//document.getElementById("v_NOM_LOTACAO").style.display = "block";
				//document.getElementById("v_NUM_MATRICULA_DETENTOR").style.display = "block";
				//document.getElementById("v_NUM_PATRIMONIO").style.display = "block";

				// Servidores
				document.getElementById("v_SEQ_MARCA_HARDWARE").style.display = "none";
				document.getElementById("v_NUM_PATRIMONIO_SERVIDOR").style.display = "none";
				document.getElementById("v_NUM_IP").style.display = "none";
				document.getElementById("v_NOM_SERVIDOR").style.display = "none";
				document.getElementById("v_SEQ_SISTEMA_OPERACIONAL").style.display = "none";
			}else{
				if(document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO.value == "2"){ // Sistemas de informação
					// Suporte
					//document.getElementById("v_COD_CATEGORIA").style.display = "none";
					//document.getElementById("v_QTD_VIDA_UTIL_TRANSCORRIDA").style.display = "none";
					//document.getElementById("v_CRITICIDADE").style.display = "none";
					//document.getElementById("v_COD_REGIONAL").style.display = "none";
					//document.getElementById("v_COD_DEPENDENCIA").style.display = "none";
					//document.getElementById("v_NOM_LOTACAO").style.display = "none";
					//document.getElementById("v_NUM_MATRICULA_DETENTOR").style.display = "none";
					//document.getElementById("v_NUM_PATRIMONIO").style.display = "none";

					// Software
					document.getElementById("v_SIG_ITEM_CONFIGURACAO").style.display = "block";
					document.getElementById("v_NOM_ITEM_CONFIGURACAO").style.display = "block";
					document.getElementById("vOrderBy").style.display = "block";
					document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "block";
					document.getElementById("v_NUM_MATRICULA_LIDER").style.display = "block";
					document.getElementById("v_NUM_MATRICULA_GESTOR").style.display = "block";
					document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "block";

					// Servidores
					document.getElementById("v_SEQ_MARCA_HARDWARE").style.display = "none";
					document.getElementById("v_NUM_PATRIMONIO_SERVIDOR").style.display = "none";
					document.getElementById("v_NUM_IP").style.display = "none";
					document.getElementById("v_NOM_SERVIDOR").style.display = "none";
					document.getElementById("v_SEQ_SISTEMA_OPERACIONAL").style.display = "none";
				}else{
					if(document.form.v_SEQ_TIPO_ITEM_CONFIGURACAO.value == "4"){ // Sistemas de informação
						// Software
						document.getElementById("v_SIG_ITEM_CONFIGURACAO").style.display = "none";
						document.getElementById("v_NOM_ITEM_CONFIGURACAO").style.display = "none";
						document.getElementById("vOrderBy").style.display = "none";
						document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";
						document.getElementById("v_NUM_MATRICULA_LIDER").style.display = "none";
						document.getElementById("v_NUM_MATRICULA_GESTOR").style.display = "none";
						document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";

						// Suporte
						//document.getElementById("v_COD_CATEGORIA").style.display = "none";
						//document.getElementById("v_QTD_VIDA_UTIL_TRANSCORRIDA").style.display = "none";
						//document.getElementById("v_CRITICIDADE").style.display = "none";
						//document.getElementById("v_COD_REGIONAL").style.display = "none";
						//document.getElementById("v_COD_DEPENDENCIA").style.display = "none";
						//document.getElementById("v_NOM_LOTACAO").style.display = "none";
						//document.getElementById("v_NUM_MATRICULA_DETENTOR").style.display = "none";
						//document.getElementById("v_NUM_PATRIMONIO").style.display = "none";

						// Servidores
						document.getElementById("v_SEQ_MARCA_HARDWARE").style.display = "block";
						document.getElementById("v_NUM_PATRIMONIO_SERVIDOR").style.display = "block";
						document.getElementById("v_NUM_IP").style.display = "block";
						document.getElementById("v_NOM_SERVIDOR").style.display = "block";
						document.getElementById("v_SEQ_SISTEMA_OPERACIONAL").style.display = "block";
					}else{
						// Software
						document.getElementById("v_SIG_ITEM_CONFIGURACAO").style.display = "none";
						document.getElementById("v_NOM_ITEM_CONFIGURACAO").style.display = "none";
						document.getElementById("vOrderBy").style.display = "block";
						document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";
						document.getElementById("v_NUM_MATRICULA_LIDER").style.display = "none";
						document.getElementById("v_NUM_MATRICULA_GESTOR").style.display = "none";
						document.getElementById("v_COD_UOR_AREA_GESTORA").style.display = "none";

						// Suporte
						//document.getElementById("v_COD_CATEGORIA").style.display = "none";
						//document.getElementById("v_QTD_VIDA_UTIL_TRANSCORRIDA").style.display = "none";
						//document.getElementById("v_CRITICIDADE").style.display = "none";
						//document.getElementById("v_COD_REGIONAL").style.display = "none";
						//document.getElementById("v_COD_DEPENDENCIA").style.display = "none";
						//document.getElementById("v_NOM_LOTACAO").style.display = "none";
						//document.getElementById("v_NUM_MATRICULA_DETENTOR").style.display = "none";
						//document.getElementById("v_NUM_PATRIMONIO").style.display = "none";

						// Servidores
						document.getElementById("v_SEQ_MARCA_HARDWARE").style.display = "none";
						document.getElementById("v_NUM_PATRIMONIO_SERVIDOR").style.display = "none";
						document.getElementById("v_NUM_IP").style.display = "none";
						document.getElementById("v_NOM_SERVIDOR").style.display = "none";
						document.getElementById("v_SEQ_SISTEMA_OPERACIONAL").style.display = "none";
					}
				}
			}
		}
	</script>
<?

// Inicio da tabela de parâmetros
$pagina->AbreTabelaPadrao("center", "85%");


// Buscar dados da tabela externa
require_once 'include/PHP/class/class.tipo_item_configuracao.php';
$tipo_item_configuracao = new tipo_item_configuracao();
$aItemOption = Array();

$tipo_item_configuracao->selectParam(2);
$cont = 0;
while ($row = pg_fetch_array($tipo_item_configuracao->database->result)){
	if($row[0] <> "1"){
		$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO == $row[0],"Selected", ""), $row[1]);
		$cont++;
	}
}
// Adicionar combo no formulário
$pagina->LinhaCampoFormulario("Tipo:", "right", "S", $pagina->CampoSelectEvent("v_SEQ_TIPO_ITEM_CONFIGURACAO", "S", "Tipo item configuracao", "S", $aItemOption, "fMostraCampos();"), "left", "v_SEQ_TIPO_ITEM_CONFIGURACAO", "30%", "70%");

// ==========================================================================================================================
// Parâmetros - Hardware e Infra
// ==========================================================================================================================
// Matricula do detentor
//$pagina->LinhaCampoFormulario("Matricula do Detentor:", "right", "N",
//								  $pagina->CampoTexto("v_NUM_MATRICULA_DETENTOR", "N", "Matrícula do Gestor" , "10", "10", $v_NUM_MATRICULA_DETENTOR, "readonly").
//								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_DETENTOR")
//								  , "left", "id='v_NUM_MATRICULA_DETENTOR' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));
/*
// Regional
$aItemOption = Array();
$bancoPatrimonio->GetRegionais("2");
$cont = 0;
while ($row = odbc_fetch_array($bancoPatrimonio->database->result)){
	$aItemOption[$cont] = array($row["COD_REGIONAL"], $pagina->iif($v_COD_REGIONAL == $row["COD_REGIONAL"],"Selected", ""), $row["SIG_REGIONAL"]);
	$cont++;
}
$pagina->LinhaCampoFormulario("Empresa:", "right", "N", $pagina->CampoSelect("v_COD_REGIONAL", "N", "Regional", "S", $aItemOption), "left", "id='v_COD_REGIONAL' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));
*/

// Dependencia
$aItemOption = Array();
/*
$bancoPatrimonio = new ativos();
$bancoPatrimonio->GetDependencias("3");
$cont = 0;
while ($row = odbc_fetch_array($bancoPatrimonio->database->result)){
	$aItemOption[$cont] = array($row["COD_DEPENDENCIA"], $pagina->iif($v_COD_DEPENDENCIA == $row["COD_DEPENDENCIA"],"Selected", ""), $row["NOM_DEPENDENCIA"]);
	$cont++;
}
$pagina->LinhaCampoFormulario("Setor:", "right", "N", $pagina->CampoSelect("v_COD_DEPENDENCIA", "N", "Dependência", "S", $aItemOption), "left", "id='v_COD_DEPENDENCIA' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));


// Número do bem
$pagina->LinhaCampoFormulario("Nº do patrimônio:", "right", "N", $pagina->CampoTexto("v_NUM_PATRIMONIO", "N", "" , "9", "9", $v_NUM_PATRIMONIO, ""), "left", "id='v_NUM_PATRIMONIO' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));

// Lotação
//$pagina->LinhaCampoFormulario("Lotação:", "right", "N",
//								  $pagina->CampoTexto("v_NOM_LOTACAO", "N", "Lotação", "10", "10", $v_NOM_LOTACAO, "").
//								  $pagina->ButtonProcuraUorg("v_NOM_LOTACAO", "")
//								  , "left", "id='v_NOM_LOTACAO' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));

/*
// Catagoria
$aItemOption = Array();
$patrimonioCategoria->combo(2);
$cont = 0;
while ($row = odbc_fetch_array($patrimonioCategoria->database->result)){
	$aItemOption[$cont] = array($row["COD_CATALOGO"], $pagina->iif($v_COD_CATEGORIA == $row["COD_CATALOGO"],"Selected", ""), $row["DES_CATALOGO"]);
	$cont++;
}
$pagina->LinhaCampoFormulario("Categoria:", "right", "N", $pagina->CampoSelect("v_COD_CATEGORIA", "N", "Categoria", "S", $aItemOption), "left", "id='v_COD_CATEGORIA' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));

// Vida útil
//$pagina->LinhaCampoFormulario("Vida útil transcorrida:", "right", "N", $pagina->CampoTexto("v_QTD_VIDA_UTIL_TRANSCORRIDA", "N", "" , "5", "5", $v_QTD_VIDA_UTIL_TRANSCORRIDA, ""), "left", "id='v_QTD_VIDA_UTIL_TRANSCORRIDA' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));

// Criticidade
//$aItemOption = Array();
//$aItemOption[] = array("*", $pagina->iif($vOrderBy == "*","Selected", ""), "*");
//$aItemOption[] = array("N", $pagina->iif($vOrderBy == "N","Selected", ""), "N");
//$pagina->LinhaCampoFormulario("Criticidade:", "right", "N", $pagina->CampoSelect("v_CRITICIDADE", "N", "", "S", $aItemOption, ""), "left", "id='v_CRITICIDADE' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="1",""," style='display:none;'"));

*/

// ==========================================================================================================================
// Parâmetros - Sistema de Informação
// ==========================================================================================================================
$pagina->LinhaCampoFormulario("Matricula do Gestor:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_GESTOR", "N", "Matrícula do Gestor" , "10", "10", $v_NUM_MATRICULA_GESTOR, "readonly").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_GESTOR")
								  , "left", "id='v_NUM_MATRICULA_GESTOR' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

$pagina->LinhaCampoFormulario("Matricula do Líder:", "right", "N",
								  $pagina->CampoTexto("v_NUM_MATRICULA_LIDER", "N", "Matrícula do Líder" , "10", "10", $v_NUM_MATRICULA_LIDER, "readonly").
								  $pagina->ButtonProcuraEmpregado("v_NUM_MATRICULA_LIDER", "TI")
								  , "left", "id='v_NUM_MATRICULA_LIDER' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

$pagina->LinhaCampoFormulario("Área Gestora:", "right", "N",
								  $pagina->CampoTexto("v_COD_UOR_AREA_GESTORA", "N", "Unidade Organizacional de Codigo ti responsavel", "10", "10", $v_COD_UOR_AREA_GESTORA, "readonly").
								  $pagina->ButtonProcuraUorg("v_COD_UOR_AREA_GESTORA", "")
								  , "left", "id='v_COD_UOR_AREA_GESTORA' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

$pagina->LinhaCampoFormulario("Sigla:", "right", "N", $pagina->CampoTexto("v_SIG_ITEM_CONFIGURACAO", "N", "", "30", "30", $v_SIG_ITEM_CONFIGURACAO), "left", "id='v_SIG_ITEM_CONFIGURACAO' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_ITEM_CONFIGURACAO", "N", "Nome", "60", "60", $v_NOM_ITEM_CONFIGURACAO), "left", "id='v_NOM_ITEM_CONFIGURACAO' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

$aItemOption = Array();
$aItemOption[] = array("SIG_ITEM_CONFIGURACAO", $pagina->iif($vOrderBy == "SIG_ITEM_CONFIGURACAO","Selected", ""), "Sigla");
$aItemOption[] = array("NOM_ITEM_CONFIGURACAO", $pagina->iif($vOrderBy == "NOM_ITEM_CONFIGURACAO","Selected", ""), "Nome");
$aItemOption[] = array("SEQ_TIPO_ITEM_CONFIGURACAO", $pagina->iif($vOrderBy == "SEQ_TIPO_ITEM_CONFIGURACAO","Selected", ""), "Tipo");
$pagina->LinhaCampoFormulario("Ordenar lista por:", "right", "N", $pagina->CampoSelect("vOrderBy", "N", "", "N", $aItemOption), "left", "id='vOrderBy' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="2",""," style='display:none;'"));

// ==========================================================================================================================
// Parâmetros - Servidores
// ==========================================================================================================================
// Buscar dados da tabela externa
require_once 'include/PHP/class/class.sistema_operacional.php';
$sistema_operacional = new sistema_operacional();
$aItemOption = Array();

$sistema_operacional->selectParam(2);
$cont = 0;
while ($row = pg_fetch_array($sistema_operacional->database->result)){
	$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_SISTEMA_OPERACIONAL == $row[0],"Selected", ""), $row[1]);
	$cont++;
}
// Adicionar combo no formulário
$pagina->LinhaCampoFormulario("Sistema operacional:", "right", "N", $pagina->CampoSelect("v_SEQ_SISTEMA_OPERACIONAL", "N", "Sistema operacional", "S", $aItemOption), "left", "id='v_SEQ_SISTEMA_OPERACIONAL' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="4",""," style='display:none;'"));

// Buscar dados da tabela externa
require_once 'include/PHP/class/class.marca_hardware.php';
$marca_hardware = new marca_hardware();
$aItemOption = Array();

$marca_hardware->selectParam(2);
$cont = 0;
while ($row = pg_fetch_array($marca_hardware->database->result)){
	$aItemOption[$cont] = array($row[0], $pagina->iif($v_SEQ_MARCA_HARDWARE == $row[0],"Selected", ""), $row[1]);
	$cont++;
}
$pagina->LinhaCampoFormulario("Marca:", "right", "N", $pagina->CampoSelect("v_SEQ_MARCA_HARDWARE", "N", "Marca hardware", "S", $aItemOption), "left", "id='v_SEQ_MARCA_HARDWARE' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="4",""," style='display:none;'"));
$pagina->LinhaCampoFormulario("Nº Patrimônio:", "right", "N", $pagina->CampoTexto("v_NUM_PATRIMONIO_SERVIDOR", "N", "Número de Bem", "15", "15", $v_NUM_PATRIMONIO_SERVIDOR), "left", "id='v_NUM_PATRIMONIO_SERVIDOR' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="4",""," style='display:none;'"));
$pagina->LinhaCampoFormulario("IP:", "right", "N", $pagina->CampoTexto("v_NUM_IP", "N", "Número de Ip", "15", "15", $v_NUM_IP), "left", "id='v_NUM_IP' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="4",""," style='display:none;'"));
$pagina->LinhaCampoFormulario("Nome:", "right", "N", $pagina->CampoTexto("v_NOM_SERVIDOR", "N", "Nome", "60", "60", $v_NOM_SERVIDOR), "left", "id='v_NOM_SERVIDOR' ".$pagina->iif($v_SEQ_TIPO_ITEM_CONFIGURACAO=="4",""," style='display:none;'"));

$pagina->LinhaCampoFormularioColspan("center", $pagina->CampoButton("return fValidaForm();", " Pesquisar "), "2");
$pagina->FechaTabelaPadrao();

// ==========================================================================================================================
// Início dos resultados - Hardware
// ==========================================================================================================================
if($v_SEQ_TIPO_ITEM_CONFIGURACAO == "1"){ // Harduware

	$pagina->LinhaVazia(1);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Nº Patrimônio", "9%");
	$header[] = array("Descrição", "");
//	$header[] = array("Empresa", "15%");
	$header[] = array("Setor", "15%");
	$header[] = array("Localização", "20%");
	$header[] = array("Detentor", "20%");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
//	$bancoPatrimonio->setCOD_CATEGORIA($v_COD_CATEGORIA);
//	$bancoPatrimonio->setQTD_VIDA_UTIL_TRANSCORRIDA($v_QTD_VIDA_UTIL_TRANSCORRIDA);
//	$bancoPatrimonio->setCRITICIDADE($v_CRITICIDADE);
//	$bancoPatrimonio->setNOM_LOTACAO(strtoupper($v_NOM_LOTACAO));
	$bancoPatrimonio->setNUM_MATRICULA_DETENTOR($empregados->GetNumeroMatricula($v_NUM_MATRICULA_DETENTOR));
	$bancoPatrimonio->setNUM_PATRIMONIO($v_NUM_PATRIMONIO);
	$bancoPatrimonio->setCOD_REGIONAL($v_COD_REGIONAL);
	$bancoPatrimonio->setCOD_DEPENDENCIA($v_COD_DEPENDENCIA);
	$bancoPatrimonio->selectParam("", $vNumPagina);
	if($bancoPatrimonio->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($bancoPatrimonio->rowCount, $bancoPatrimonio->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Item encontrados para os parâmentos pesquisados", $header);
		$cont = 0;
		while ($row = odbc_fetch_array($bancoPatrimonio->database->result)){
			$corpo[] = array("center", "campo", $row["NUM_PATRIMONIO"]);
			$corpo[] = array("left", "campo", $row["NOM_BEM"]);
//			$corpo[] = array("left", "campo", $row["SIG_REGIONAL"]);
			$corpo[] = array("left", "campo", $row["NOM_DEPENDENCIA"]);
			$corpo[] = array("left", "campo", $row["DSC_LOCALIZACAO"]);
			$corpo[] = array("left", "campo", $row["NOM_DETENTOR"]);
			$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"location.href='Item_configuracaoDetalhes.php?v_NUM_PATRIMONIO=".substr($row["NUM_PATRIMONIO"], 0, strlen($row["NUM_PATRIMONIO"])-2)."';\"");
			$corpo = "";
			$cont++;
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($bancoPatrimonio->rowCount, $bancoPatrimonio->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO&v_SEQ_TIPO_ITEM_CONFIGURACAO=$v_SEQ_TIPO_ITEM_CONFIGURACAO&v_SEQ_SERVICO=$v_SEQ_SERVICO&v_NUM_MATRICULA_GESTOR=$v_NUM_MATRICULA_GESTOR&v_NUM_MATRICULA_LIDER=$v_NUM_MATRICULA_LIDER&v_SIG_ITEM_CONFIGURACAO=$v_SIG_ITEM_CONFIGURACAO&v_NOM_ITEM_CONFIGURACAO=$v_NOM_ITEM_CONFIGURACAO&v_COD_UOR_AREA_GESTORA=$v_COD_UOR_AREA_GESTORA&v_TXT_ITEM_CONFIGURACAO=$v_TXT_ITEM_CONFIGURACAO&v_SEQ_TIPO_DISPONIBILIDADE=$v_SEQ_TIPO_DISPONIBILIDADE&v_SEQ_PRIORIDADE=$v_SEQ_PRIORIDADE&v_COD_CATEGORIA=$v_COD_CATEGORIA&v_QTD_VIDA_UTIL_TRANSCORRIDA=$v_QTD_VIDA_UTIL_TRANSCORRIDA&v_CRITICIDADE=$v_CRITICIDADE&v_NOM_LOTACAO=$v_NOM_LOTACAO&v_NUM_MATRICULA_DETENTOR=$v_NUM_MATRICULA_DETENTOR&v_COD_REGIONAL=$v_COD_REGIONAL&v_COD_DEPENDENCIA=$v_COD_DEPENDENCIA");
// ==========================================================================================================================
// Início dos resultados - Sistemas de Informação
// ==========================================================================================================================
}elseif($v_SEQ_TIPO_ITEM_CONFIGURACAO == "2"){ // Sistemas de informação
	$pagina->LinhaVazia(1);

	// Inicio do grid de resultados
	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
	$header[] = array("Sigla", "13%");
	$header[] = array("Nome", "25%");
	$header[] = array("Gestor", "25%");
	$header[] = array("Líder", "25%");
	$header[] = array("Unidade Org.", "7%");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$banco->setSEQ_ITEM_CONFIGURACAO($v_SEQ_ITEM_CONFIGURACAO);
	$banco->setSEQ_TIPO_ITEM_CONFIGURACAO($v_SEQ_TIPO_ITEM_CONFIGURACAO);
	$banco->setSEQ_SERVICO($v_SEQ_SERVICO);
	$banco->setNUM_MATRICULA_GESTOR($empregados->GetNumeroMatricula($v_NUM_MATRICULA_GESTOR));
	$banco->setNUM_MATRICULA_LIDER($empregados->GetNumeroMatricula($v_NUM_MATRICULA_LIDER));
	$banco->setSIG_ITEM_CONFIGURACAO($v_SIG_ITEM_CONFIGURACAO);
	$banco->setNOM_ITEM_CONFIGURACAO($v_NOM_ITEM_CONFIGURACAO);
	$banco->setCOD_UOR_AREA_GESTORA($v_COD_UOR_AREA_GESTORA);
	$banco->setTXT_ITEM_CONFIGURACAO($v_TXT_ITEM_CONFIGURACAO);
	$banco->setSEQ_TIPO_DISPONIBILIDADE($v_SEQ_TIPO_DISPONIBILIDADE);
	$banco->setSEQ_PRIORIDADE($v_SEQ_PRIORIDADE);
	$banco->selectParam($pagina->iif($vOrderBy == "", "SIG_ITEM_CONFIGURACAO", $vOrderBy), $vNumPagina);
	if($banco->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Item encontrados para os parâmentos pesquisados", $header);
		$cont = 0;
		while ($row = pg_fetch_array($banco->database->result)){
			require_once 'include/PHP/class/class.tipo_item_configuracao.php';
			$corpo[] = array("left", "campo", $row["sig_item_configuracao"]);
			$corpo[] = array("left", "campo", $row["nom_item_configuracao"]);
			$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_gestor"]));
			$corpo[] = array("left", "campo", $empregados->GetNomeEmpregado($row["num_matricula_lider"]));
			if($row["cod_uor_area_gestora"] != ""){
				$unidade_organizacional->select($row["cod_uor_area_gestora"]);
				$corpo[] = array("center", "campo", $unidade_organizacional->SGL_UNIDADE_ORGANIZACIONAL);
			}else{
				$corpo[] = array("center", "campo", "---");
			}
			$pagina->LinhaTabelaResultado($corpo, $cont, "style=\"cursor: pointer;\" onclick=\"location.href='Item_configuracaoDetalhes.php?v_SEQ_ITEM_CONFIGURACAO=".$row["seq_item_configuracao"]."';\"");
			$corpo = "";
			$cont++;
		}
	}
	$pagina->FechaTabelaPadrao();
	$pagina->fMontaPaginacao($banco->rowCount, $banco->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_ITEM_CONFIGURACAO=$v_SEQ_ITEM_CONFIGURACAO&v_SEQ_TIPO_ITEM_CONFIGURACAO=$v_SEQ_TIPO_ITEM_CONFIGURACAO&v_SEQ_SERVICO=$v_SEQ_SERVICO&v_NUM_MATRICULA_GESTOR=$v_NUM_MATRICULA_GESTOR&v_NUM_MATRICULA_LIDER=$v_NUM_MATRICULA_LIDER&v_SIG_ITEM_CONFIGURACAO=$v_SIG_ITEM_CONFIGURACAO&v_NOM_ITEM_CONFIGURACAO=$v_NOM_ITEM_CONFIGURACAO&v_COD_UOR_AREA_GESTORA=$v_COD_UOR_AREA_GESTORA&v_TXT_ITEM_CONFIGURACAO=$v_TXT_ITEM_CONFIGURACAO&v_SEQ_TIPO_DISPONIBILIDADE=$v_SEQ_TIPO_DISPONIBILIDADE&v_SEQ_PRIORIDADE=$v_SEQ_PRIORIDADE");
// ==========================================================================================================================
// Início dos resultados - Servidores
// ==========================================================================================================================
}elseif($v_SEQ_TIPO_ITEM_CONFIGURACAO == "4"){
	$servidor = new servidor();
	$sistema_operacional = new sistema_operacional();
	$equipe_servidor = new equipe_servidor();
	$marca_hardware = new marca_hardware();

	$pagina->AbreTabelaResultado("center", "100%");
	$header = array();
//	$header[] = array("&nbsp;", "3%");
	$header[] = array("Patrimônio", "10%");
	$header[] = array("IP", "10%");
	$header[] = array("Nome", "15%");
	$header[] = array("Sist. Operacional", "20%");
	$header[] = array("Marca", "10%");
	$header[] = array("Modelo", "10%");
	$header[] = array("Descrição", "20%");

	// Setar variáveis
	$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
	$servidor->setSEQ_SERVIDOR($v_SEQ_SERVIDOR);
	$servidor->setSEQ_SISTEMA_OPERACIONAL($v_SEQ_SISTEMA_OPERACIONAL);
	$servidor->setSEQ_MARCA_HARDWARE($v_SEQ_MARCA_HARDWARE);
	$servidor->setNUM_PATRIMONIO($v_NUM_PATRIMONIO_SERVIDOR);
	$servidor->setNUM_IP($v_NUM_IP);
	$servidor->setNOM_SERVIDOR($v_NOM_SERVIDOR);
	$servidor->setNOM_MODELO($v_NOM_MODELO);
	$servidor->setDSC_SERVIDOR($v_DSC_SERVIDOR);
	$servidor->setDSC_LOCALIZACAO($v_DSC_LOCALIZACAO);
	$servidor->setDSC_PROCESSADOR($v_DSC_PROCESSADOR);
	$servidor->setTXT_OBSERVACAO($v_TXT_OBSERVACAO);
	$servidor->setDAT_CRIACAO($v_DAT_CRIACAO);
	$servidor->setDAT_ALTERACAO($v_DAT_ALTERACAO);
	$servidor->selectParam("NOM_SERVIDOR", $vNumPagina, 20);
	if($servidor->database->rows == 0){
		$pagina->LinhaCampoFormularioColspan("left", "Nenhum registro encontrado", count($header));
		$pagina->FechaTabelaPadrao();
	}else{
		$corpo = array();
		$pagina->fQuantidadeRegistros($servidor->rowCount, $servidor->vQtdRegistros, $vNumPagina, $header);
		$pagina->LinhaHeaderTabelaResultado("Servidores encontrados", $header);
		while ($row = pg_fetch_array($servidor->database->result)){
			if($pagina->segurancaPerfilExclusao($_SESSION["SEQ_PERFIL_ACESSO"])){ // Apenas o adminsitrador
				$valor = $pagina->BotaoExcluiGridPesquisa("SEQ_SERVIDOR", $row["seq_servidor"]);
			}
//			$corpo[] = array("center", "campo", $valor);
			$corpo[] = array("center", "campo", $row["num_patrimonio"]);
			$corpo[] = array("center", "campo", $row["num_ip"]);
			$corpo[] = array("left", "campo", $row["nom_servidor"]);

			if($row["seq_sistema_operacional"] != ""){
				$sistema_operacional->select($row["seq_sistema_operacional"]);
				$corpo[] = array("left", "campo", $sistema_operacional->NOM_SISTEMA_OPERACIONAL);
			}else{
				$corpo[] = array("left", "campo", "&nbsp;");
			}
			if($row["seq_marca_hardware"] != ""){
				$marca_hardware->select($row["seq_marca_hardware"]);
				$corpo[] = array("left", "campo", $marca_hardware->NOM_MARCA_HARDWARE);
			}else{
				$corpo[] = array("left", "campo", "&nbsp;");
			}

			$corpo[] = array("left", "campo", $row["nom_modelo"]);
			$corpo[] = array("left", "campo", $row["dsc_servidor"]);

			$pagina->LinhaTabelaResultado($corpo, $vCont, "style=\"cursor: pointer;\" onclick=\"location.href='ServidorDetalhes.php?v_SEQ_SERVIDOR=".$row["seq_servidor"]."';\"");
			$corpo = "";
		}
		$pagina->FechaTabelaPadrao();
		$pagina->LinhaCampoFormularioColspan("left", $pagina->fMontaPaginacao($servidor->rowCount, $servidor->vQtdRegistros, $vNumPagina, $PHP_SELF."?flag=1&v_SEQ_SERVIDOR=$v_SEQ_SERVIDOR&v_SEQ_SISTEMA_OPERACIONAL=$v_SEQ_SISTEMA_OPERACIONAL&v_SEQ_MARCA_HARDWARE=$v_SEQ_MARCA_HARDWARE&v_NUM_PATRIMONIO=$v_NUM_PATRIMONIO&v_NUM_IP=$v_NUM_IP&v_NOM_SERVIDOR=$v_NOM_SERVIDOR&v_NOM_MODELO=$v_NOM_MODELO&v_DSC_SERVIDOR=$v_DSC_SERVIDOR&v_DSC_LOCALIZACAO=$v_DSC_LOCALIZACAO&v_DSC_PROCESSADOR=$v_DSC_PROCESSADOR&v_TXT_OBSERVACAO=$v_TXT_OBSERVACAO&v_DAT_CRIACAO=$v_DAT_CRIACAO&v_DAT_ALTERACAO=$v_DAT_ALTERACAO&v_SEQ_TIPO_ITEM_CONFIGURACAO=$v_SEQ_TIPO_ITEM_CONFIGURACAO"), 20);
	}
}
$pagina->MontaRodape();
?>