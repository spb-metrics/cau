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
require 'include/PHP/class/class.empregados.oracle.php';
require 'include/PHP/class/class.recurso_ti.php';
require 'include/PHP/class/class.equipe_envolvida.php';
$pagina = new Pagina();
$banco = new item_configuracao();
$empregados = new empregados();
$recurso_ti = new recurso_ti();
$equipe_envolvida = new equipe_envolvida();
// Configuração da págína
$pagina->SettipoPagina("S"); // Indica a finalidade da página - I - insert, U - update, S - select, O - Outro
$pagina->SettituloCabecalho("Relatório Analítico de Alocação de Profissionais da $v_UOR_SIGLA"); // Indica o título do cabeçalho da página
$pagina->flagMenu = 0;
$pagina->flagTopo = 0;
$pagina->setFlagCorpo(0);
$pagina->MontaCabecalho();

// Inicio do grid de resultados
$pagina->AbreTabelaResultado("center", "");
$header = array();

// Pegar todos os profissionais de TI e colocar no cabeçalho
$recurso_ti->setUOR_SIGLA($v_UOR_SIGLA);
$recurso_ti->selectParam("NOME");
$header[] = array("Nome", "400");
while ($rowProfissional = oci_fetch_array($recurso_ti->database->result, OCI_BOTH)){
	$header[] = array($rowProfissional["NOME"], "");
}
$header[] = array("Total de horas alocadas", "");
// Setar variáveis
$vNumPagina = $pagina->iif($vNumPagina == "", 1, $vNumPagina);
$banco->setUOR_SIGLA($v_UOR_SIGLA);
$banco->selectAlocacaoCompleto("SIG_ITEM_CONFIGURACAO");
print "Quantidade de Profissionais: ".$recurso_ti->database->rows;
print "<br>Quantidade de Itens do Parque Tecnológico: ".$banco->database->rows;
if($banco->database->rows == 0){
	$pagina->LinhaCampoFormularioColspan("center", "Nenhum registro encontrado", count($header));
}else{
	$corpo = array();
	$cont = 0;
	$totaldesenv = 0;
	$pagina->LinhaHeaderTextoVertical("Alocação dos Recursos de TI", $header);
	while ($row = oci_fetch_array($banco->database->result, OCI_BOTH)){
		$corpo[] = array("left", "campo", "<a style='text-decoration: none; color: #000000' title='Detalhes' href='Item_configuracaoAlteracao.php?v_SEQ_ITEM_CONFIGURACAO=".$row["SEQ_ITEM_CONFIGURACAO"]."'>".$row["SIG_ITEM_CONFIGURACAO"]."</a>");
		$valor = 0;
		$print = "";
		$recurso_ti->selectParam("NOME");
		while ($rowProfissional = oci_fetch_array($recurso_ti->database->result, OCI_BOTH)){
			$print = "";
			if($equipe_envolvida->IsAlocado($rowProfissional["NUM_MATRICULA_RECURSO"], $row["SEQ_ITEM_CONFIGURACAO"])){
				$valor = $valor + $equipe_envolvida->QTD_HORA_ALOCADA;
				$$rowProfissional["NUM_MATRICULA_RECURSO"] = $$rowProfissional["NUM_MATRICULA_RECURSO"] + $equipe_envolvida->QTD_HORA_ALOCADA;
				$print = "<a title='Alocação completa de ".$rowProfissional["NOME"]."' style='text-decoration: none; color: #000000' alt='' href='http://se10259640/gestaoti/Item_configuracaoAlocacao.php?flag=1&v_NUM_MATRICULA_RECURSO=".$rowProfissional["NOM_LOGIN_REDE"]."'>".$equipe_envolvida->QTD_HORA_ALOCADA."</a>";
			}
			$corpo[] = array("center", "campo", $print);
		}
		$corpo[] = array("right", "campo", $valor);
		$pagina->LinhaTabelaResultado($corpo, $cont);
		$corpo = "";
		$cont++;

	}
	//$recurso_ti->selectParam("NOME");
	$resultAux = $result;
	// Linha de totais
	$valor = 0;
	$corpo[] = array("left", "campo", "Totais");
	$recurso_ti->selectParam("NOME");
	while ($rowProfissional = oci_fetch_array($recurso_ti->database->result, OCI_BOTH)){
		$valor = $valor + $$rowProfissional["NUM_MATRICULA_RECURSO"];
		$print = "<a title='Alocação completa de ".$rowProfissional["NOME"]."' style='text-decoration: none; color: #000000' alt='' href='http://se10259640/gestaoti/Item_configuracaoAlocacao.php?flag=1&v_NUM_MATRICULA_RECURSO=".$rowProfissional["NOM_LOGIN_REDE"]."'>".$$rowProfissional["NUM_MATRICULA_RECURSO"]."</a>";
		$corpo[] = array("center", "campo", $print);
	}
	$corpo[] = array("right", "campo","<span title='Total de horas'>$valor</span>");
	$pagina->LinhaTabelaResultado($corpo, $cont);
	$corpo = "";
}
$pagina->FechaTabelaPadrao();
print "<hr><br>Quantidade de horas semanais para desenvolvimento: ".$equipe_envolvida->HorasAlocadas("D", $v_UOR_SIGLA);
print "<br>Quantidade de horas semanais para manutenção, atendimento e suporte: ".$equipe_envolvida->HorasAlocadas("M", $v_UOR_SIGLA);


$pagina->MontaRodape();
?>
