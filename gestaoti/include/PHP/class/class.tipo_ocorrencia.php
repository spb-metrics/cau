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
* CLASSNAME:        tipo_ocorrencia
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	require_once "../gestaoti/include/PHP/class/class.parametro.php";
	require_once "../gestaoti/include/PHP/class/class.database.postgres.php";
}else{
	require_once "include/PHP/class/class.database.postgres.php";
	require_once "include/PHP/class/class.parametro.php";
}

// **********************
// CLASS DECLARATION
// **********************
class tipo_ocorrencia{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $SEQ_TIPO_OCORRENCIA;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NOM_TIPO_OCORRENCIA;   // (normal Attribute)

	var $SEQ_TIPO_OCORRENCIA_IMPROCEDENTE;
	var $FLG_EXIBE_IMPROCEDENTE;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	var $parametro;

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function tipo_ocorrencia(){
		$this->database = new Database();
		$this->parametro = new parametro();

		$this->SEQ_TIPO_OCORRENCIA_INCIDENTE 	= $this->parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_INCIDENTE");
		$this->SEQ_TIPO_OCORRENCIA_IMPROCEDENTE = $this->parametro->GetValorParametro("SEQ_TIPO_OCORRENCIA_IMPROCEDENTE");
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
	function getseq_tipo_ocorrencia(){
		return $this->seq_tipo_ocorrencia;
	}

	function getnom_tipo_ocorrencia(){
		return $this->nom_tipo_ocorrencia;
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
	function setseq_tipo_ocorrencia($val){
		$this->seq_tipo_ocorrencia =  $val;
	}

	function setnom_tipo_ocorrencia($val){
		$this->nom_tipo_ocorrencia =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  "SELECT * FROM gestaoti.tipo_ocorrencia WHERE seq_tipo_ocorrencia = $id";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		$this->SEQ_TIPO_OCORRENCIA = $row->seq_tipo_ocorrencia;
		$this->NOM_TIPO_OCORRENCIA = $row->nom_tipo_ocorrencia;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = "SELECT seq_tipo_ocorrencia , nom_tipo_ocorrencia ";
		$sqlCorpo  = "FROM gestaoti.tipo_ocorrencia
						WHERE 1=1 ";

		if($this->seq_tipo_ocorrencia != ""){
			$sqlCorpo .= "  and seq_tipo_ocorrencia = $this->seq_tipo_ocorrencia ";
		}
		if($this->nom_tipo_ocorrencia != ""){
			$sqlCorpo .= "  and upper(nom_tipo_ocorrencia) like '%".strtoupper($this->nom_tipo_ocorrencia)."%'  ";
		}
		if($this->FLG_EXIBE_IMPROCEDENTE == 0){
			$sqlCorpo .= "  and seq_tipo_ocorrencia <> $this->SEQ_TIPO_OCORRENCIA_IMPROCEDENTE ";
		}

		$sqlCount = $sqlCorpo;

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

	// **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.tipo_ocorrencia WHERE seq_tipo_ocorrencia = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->seq_tipo_ocorrencia = $this->database->GetsequenceValue("gestaoti.seq_tipo_ocorrencia"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.tipo_ocorrencia (seq_tipo_ocorrencia, nom_tipo_ocorrencia )
				VALUES (".$this->seq_tipo_ocorrencia.",
						".$this->database->iif($this->nom_tipo_ocorrencia=="", "NULL", "'".$this->nom_tipo_ocorrencia."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.tipo_ocorrencia
				 SET  nom_tipo_ocorrencia = ".$this->database->iif($this->nom_tipo_ocorrencia=="", "NULL", "'".$this->nom_tipo_ocorrencia."'")."
				 WHERE seq_tipo_ocorrencia = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->FLG_EXIBE_IMPROCEDENTE = $this->FLG_EXIBE_IMPROCEDENTE==""?0:$this->FLG_EXIBE_IMPROCEDENTE;
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $vSelected == $row[0]?"Selected":"", $row[1]);
			$cont++;
		}
		return $aItemOption;
	}

} // class : end
?>