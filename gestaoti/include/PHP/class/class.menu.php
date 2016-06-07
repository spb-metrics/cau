<?php
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
/*
* -------------------------------------------------------
* CLASSNAME:        menu
* -------------------------------------------------------
*/
//print "1";
include_once("include/PHP/class/class.database.postgres.php");
//print "2";
// **********************
// CLASS DECLARATION
// **********************
class menu{
	// class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************

	var $SEQ_MENU_ACESSO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $DSC_MENU_ACESSO;   // (normal Attribute)
	var $NOM_ARQUIVO;   // (normal Attribute)
	var $NUM_PRIORIDADE;   // (normal Attribute)
	var $NOM_ARQUIVO_IMAGEM_ESCURO;   // (normal Attribute)
	var $NOM_ARQUIVO_IMAGEM_CLARO;   // (normal Attribute)
	var $SEQ_PERFIL_ACESSO;
	var $SEQ_MENU_ACESSO_PAI;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	var $ITENS_MENU;
	
	function menu(){
		$this->database = new Database();
		$ITENS_MENU = array();
	}

	// **********************
	// GETTER METHODS
	// **********************
	function getrowCount(){
		return $this->rowCount;
	}

	function getvQtdRegistros(){
		return $this->vQtdRegistros;
	}
	function getSEQ_MENU_ACESSO(){
		return $this->SEQ_MENU_ACESSO;
	}

	function getDSC_MENU_ACESSO(){
		return $this->DSC_MENU_ACESSO;
	}

	function getNOM_ARQUIVO(){
		return $this->NOM_ARQUIVO;
	}

	function getNUM_PRIORIDADE(){
		return $this->NUM_PRIORIDADE;
	}

	function getNOM_ARQUIVO_IMAGEM_ESCURO(){
		return $this->NOM_ARQUIVO_IMAGEM_ESCURO;
	}

	function getNOM_ARQUIVO_IMAGEM_CLARO(){
		return $this->NOM_ARQUIVO_IMAGEM_CLARO;
	}

	function getSEQ_PERFIL_ACESSO(){
		return $this->SEQ_PERFIL_ACESSO;
	}

	function getSEQ_MENU_ACESSO_PAI(){
		return $this->SEQ_MENU_ACESSO_PAI;
	}

	// **********************
	// SETTER METHODS
	// **********************
	function setrowCount($val){
		$this->rowCount = $val;
	}

	function setvQtdRegistros($val){
		$this->vQtdRegistros = $val;
	}
	function setSEQ_MENU_ACESSO($val){
		$this->SEQ_MENU_ACESSO =  $val;
	}

	function setDSC_MENU_ACESSO($val){
		$this->DSC_MENU_ACESSO =  $val;
	}

	function setNOM_ARQUIVO($val){
		$this->NOM_ARQUIVO =  $val;
	}

	function setNUM_PRIORIDADE($val){
		$this->NUM_PRIORIDADE =  $val;
	}

	function setNOM_ARQUIVO_IMAGEM_ESCURO($val){
		$this->NOM_ARQUIVO_IMAGEM_ESCURO =  $val;
	}

	function setNOM_ARQUIVO_IMAGEM_CLARO($val){
		$this->NOM_ARQUIVO_IMAGEM_CLARO =  $val;
	}

	function setSEQ_PERFIL_ACESSO($val){
		$this->SEQ_PERFIL_ACESSO =  $val;
	}

	function setSEQ_MENU_ACESSO_PAI($val){
		$this->SEQ_MENU_ACESSO_PAI =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.menu_acesso WHERE SEQ_MENU_ACESSO = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result, 0);
		$this->SEQ_MENU_ACESSO = $row->seq_menu_acesso;
		$this->SEQ_MENU_ACESSO_PAI = $row->seq_menu_acesso_pai;
		$this->DSC_MENU_ACESSO = $row->dsc_menu_acesso;
		$this->NOM_ARQUIVO = $row->nom_arquivo;
		$this->NUM_PRIORIDADE = $row->num_prioridade;
		$this->NOM_ARQUIVO_IMAGEM_ESCURO = $row->nom_arquivo_imagem_escuro;
		$this->NOM_ARQUIVO_IMAGEM_CLARO = $row->nom_arquivo_imagem_claro;
	}
	
	function selectByNomeArquivo($nomeArquivo){
		$sql =  "SELECT * FROM gestaoti.menu_acesso WHERE NOM_ARQUIVO = '".$nomeArquivo."'";
		$result =  $this->database->query($sql);
		$result = $this->database->result;
		while ($row = pg_fetch_object($this->database->result)){
			//$row = pg_fetch_object($result, 0);
			$this->SEQ_MENU_ACESSO = $row->seq_menu_acesso;
			$this->SEQ_MENU_ACESSO_PAI = $row->seq_menu_acesso_pai;
			$this->DSC_MENU_ACESSO = $row->dsc_menu_acesso;
			$this->NOM_ARQUIVO = $row->nom_arquivo;
			$this->NUM_PRIORIDADE = $row->num_prioridade;
			$this->NOM_ARQUIVO_IMAGEM_ESCURO = $row->nom_arquivo_imagem_escuro;
			$this->NOM_ARQUIVO_IMAGEM_CLARO = $row->nom_arquivo_imagem_claro;
		}
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT SEQ_MENU_ACESSO, SEQ_MENU_ACESSO_PAI, DSC_MENU_ACESSO , NOM_ARQUIVO , NUM_PRIORIDADE , NOM_ARQUIVO_IMAGEM_ESCURO , NOM_ARQUIVO_IMAGEM_CLARO ";
		$sqlCorpo  = "FROM gestaoti.menu_acesso
						WHERE 1=1 ";

		if($this->SEQ_MENU_ACESSO != ""){
			$sqlCorpo .= "  and SEQ_MENU_ACESSO = $this->SEQ_MENU_ACESSO ";
		}
		if($this->SEQ_MENU_ACESSO_PAI != "" && $this->SEQ_MENU_ACESSO_PAI != "NULL"){
			$sqlCorpo .= "  and SEQ_MENU_ACESSO_PAI = $this->SEQ_MENU_ACESSO_PAI ";
		}
		if($this->SEQ_MENU_ACESSO_PAI == "NULL"){
			$sqlCorpo .= "  and SEQ_MENU_ACESSO_PAI is null ";
		}
		if($this->DSC_MENU_ACESSO != ""){
			$sqlCorpo .= "  and upper(DSC_MENU_ACESSO) like '%".strtoupper($this->DSC_MENU_ACESSO)."%'  ";
		}
		if($this->NOM_ARQUIVO != ""){
			$sqlCorpo .= "  and upper(NOM_ARQUIVO) like '%".strtoupper($this->NOM_ARQUIVO)."%'  ";
		}
		if($this->NUM_PRIORIDADE != ""){
			$sqlCorpo .= "  and NUM_PRIORIDADE = $this->NUM_PRIORIDADE ";
		}
		if($this->NOM_ARQUIVO_IMAGEM_ESCURO != ""){
			$sqlCorpo .= "  and upper(NOM_ARQUIVO_IMAGEM_ESCURO) like '%".strtoupper($this->NOM_ARQUIVO_IMAGEM_ESCURO)."%'  ";
		}
		if($this->NOM_ARQUIVO_IMAGEM_CLARO != ""){
			$sqlCorpo .= "  and upper(NOM_ARQUIVO_IMAGEM_CLARO) like '%".strtoupper($this->NOM_ARQUIVO_IMAGEM_CLARO)."%'  ";
		}

		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);
			$sqlCorpo .= " limit $vQtdRegistros offset $vLimit  ";
			// Pegar a quantidade de registros GERAL
			$this->database->query("select count(1) " . $sqlCount);
			$rowCount = pg_fetch_array($this->database->result);
			$this->setrowCount($rowCount[0]);

		}

		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	// ****************************
	// SELECT MONTA MENU
	// ****************************
	function selectParamMontaMenu($orderBy = 1){
		
		$sqlSelect ="
		SELECT    
			 a.SEQ_MENU_ACESSO, a.NOM_ARQUIVO, 
			 a.DSC_MENU_ACESSO, a.NOM_ARQUIVO_IMAGEM_ESCURO, 
			 a.NOM_ARQUIVO_IMAGEM_CLARO, a.NUM_PRIORIDADE 
		FROM 
			(
				select DISTINCT a.DSC_MENU_ACESSO, 
				a.SEQ_MENU_ACESSO, a.NOM_ARQUIVO, 
				a.NOM_ARQUIVO_IMAGEM_ESCURO, 
	 			a.NOM_ARQUIVO_IMAGEM_CLARO, a.NUM_PRIORIDADE 
				FROM gestaoti.menu_acesso a, gestaoti.menu_perfil_acesso b 
	 			where a.SEQ_MENU_ACESSO = b.SEQ_MENU_ACESSO 
		";
	//	$sqlSelect = "select a.SEQ_MENU_ACESSO, a.NOM_ARQUIVO, a.DSC_MENU_ACESSO, a.NOM_ARQUIVO_IMAGEM_ESCURO, a.NOM_ARQUIVO_IMAGEM_CLARO ";
		//$sqlCorpo = " FROM gestaoti.menu_acesso a, gestaoti.menu_perfil_acesso b
		//			  where a.SEQ_MENU_ACESSO = b.SEQ_MENU_ACESSO ";
		//echo $this->SEQ_PERFIL_ACESSO."<br>";
		if($this->SEQ_PERFIL_ACESSO != ""){
			//$sqlCorpo .= "  and b.SEQ_PERFIL_ACESSO = $this->SEQ_PERFIL_ACESSO ";
			/*TODO: NOVO PERFIL ACESSO*/
			$sqlSelect .= "  and b.SEQ_PERFIL_ACESSO in ( ";
			$count = count($this->SEQ_PERFIL_ACESSO); 
			for ($i = 0; $i < $count; $i++) {				 
				$sqlSelect .= $this->SEQ_PERFIL_ACESSO[$i][0];
				if($i+1 < $count){
				 $sqlSelect .= ", ";
				}
			}			 
			 
			$sqlSelect .= "  ) ";
			/*TODO: NOVO PERFIL ACESSO*/
		}

		if($this->SEQ_MENU_ACESSO_PAI != ""){
			$sqlSelect .= " and a.SEQ_MENU_ACESSO_pai = $this->SEQ_MENU_ACESSO_PAI ";
		}else{
			$sqlSelect .= " and a.SEQ_MENU_ACESSO_pai is null  ";
		}
		
		$sqlSelect .= "  )a  ";
		
		if($orderBy != "" ){
			$sqlSelect .= " order by NUM_PRIORIDADE ";
		}

		//print $sqlSelect.$sqlCorpo;

		$this->database->query($sqlSelect);
	}
	
	function selectParamMontaMenuOLD($orderBy = 1){
		$sqlSelect = "select a.SEQ_MENU_ACESSO, a.NOM_ARQUIVO, a.DSC_MENU_ACESSO, a.NOM_ARQUIVO_IMAGEM_ESCURO, a.NOM_ARQUIVO_IMAGEM_CLARO ";
		$sqlCorpo = " FROM gestaoti.menu_acesso a, gestaoti.menu_perfil_acesso b
					  where a.SEQ_MENU_ACESSO = b.SEQ_MENU_ACESSO ";
		//echo $this->SEQ_PERFIL_ACESSO."<br>";
		if($this->SEQ_PERFIL_ACESSO != ""){
			//$sqlCorpo .= "  and b.SEQ_PERFIL_ACESSO = $this->SEQ_PERFIL_ACESSO ";
			/*TODO: NOVO PERFIL ACESSO*/
			$sqlCorpo .= "  and b.SEQ_PERFIL_ACESSO in ( ";
			$count = count($this->SEQ_PERFIL_ACESSO); 
			for ($i = 0; $i < $count; $i++) {				 
				$sqlCorpo .= $this->SEQ_PERFIL_ACESSO[$i][0];
				if($i+1 < $count){
				 $sqlCorpo .= ", ";
				}
			}			 
			 
			$sqlCorpo .= "  ) ";
			/*TODO: NOVO PERFIL ACESSO*/
		}

		if($this->SEQ_MENU_ACESSO_PAI != ""){
			$sqlCorpo .= " and a.SEQ_MENU_ACESSO_pai = $this->SEQ_MENU_ACESSO_PAI ";
		}else{
			$sqlCorpo .= " and a.SEQ_MENU_ACESSO_pai is null  ";
		}

		if($orderBy != "" ){
			$sqlCorpo .= " order by NUM_PRIORIDADE ";
		}

		//print $sqlSelect.$sqlCorpo;

		$this->database->query($sqlSelect.$sqlCorpo);
	}

	// **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.menu_acesso WHERE SEQ_MENU_ACESSO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_MENU_ACESSO = $this->database->GetSequenceValue("gestaoti.SEQ_MENU_ACESSO"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.menu_acesso (SEQ_MENU_ACESSO,
										 DSC_MENU_ACESSO,
										 SEQ_MENU_ACESSO_PAI,
										 NOM_ARQUIVO,
										 NUM_PRIORIDADE,
										 NOM_ARQUIVO_IMAGEM_ESCURO,
										 NOM_ARQUIVO_IMAGEM_CLARO )
				VALUES (".$this->SEQ_MENU_ACESSO.",
						".$this->database->iif($this->DSC_MENU_ACESSO=="", "NULL", "'".$this->DSC_MENU_ACESSO."'").",
						".$this->database->iif($this->SEQ_MENU_ACESSO_PAI=="", "NULL", $this->SEQ_MENU_ACESSO_PAI).",
						".$this->database->iif($this->NOM_ARQUIVO=="", "NULL", "'".$this->NOM_ARQUIVO."'").",
						".$this->database->iif($this->NUM_PRIORIDADE=="", "NULL", "'".$this->NUM_PRIORIDADE."'").",
						".$this->database->iif($this->NOM_ARQUIVO_IMAGEM_ESCURO=="", "NULL", "'".$this->NOM_ARQUIVO_IMAGEM_ESCURO."'").",
						".$this->database->iif($this->NOM_ARQUIVO_IMAGEM_CLARO=="", "NULL", "'".$this->NOM_ARQUIVO_IMAGEM_CLARO."'")."
				)";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.menu_acesso
				 SET SEQ_MENU_ACESSO_PAI = ".$this->database->iif($this->SEQ_MENU_ACESSO_PAI=="", "NULL", "'".$this->SEQ_MENU_ACESSO_PAI."'").",
				     DSC_MENU_ACESSO = ".$this->database->iif($this->DSC_MENU_ACESSO=="", "NULL", "'".$this->DSC_MENU_ACESSO."'").",
					 NOM_ARQUIVO = ".$this->database->iif($this->NOM_ARQUIVO=="", "NULL", "'".$this->NOM_ARQUIVO."'").",
					 NUM_PRIORIDADE = ".$this->database->iif($this->NUM_PRIORIDADE=="", "NULL", "'".$this->NUM_PRIORIDADE."'").",
					 NOM_ARQUIVO_IMAGEM_ESCURO = ".$this->database->iif($this->NOM_ARQUIVO_IMAGEM_ESCURO=="", "NULL", "'".$this->NOM_ARQUIVO_IMAGEM_ESCURO."'").",
					 NOM_ARQUIVO_IMAGEM_CLARO = ".$this->database->iif($this->NOM_ARQUIVO_IMAGEM_CLARO=="", "NULL", "'".$this->NOM_ARQUIVO_IMAGEM_CLARO."'")."
				 WHERE SEQ_MENU_ACESSO = $id ";
		$result = $this->database->query($sql);

	}
	
	function addItemMenu ($itemMenu){
		$this->ITENS_MENU[] = $itemMenu;		
	}
	
	function getItensMenu (){
		return $this->ITENS_MENU;
	}
} // class : end
?>