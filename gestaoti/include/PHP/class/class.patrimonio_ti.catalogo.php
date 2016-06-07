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
*
* -------------------------------------------------------
* CLASSNAME:        	patrimonio_ti.catalogo
* FOR SQLSERVER TABLE:  ast_cat
* FOR SQLSERVER DB:     DBSfast
* -------------------------------------------------------
*
*/
include_once("include/PHP/class/class.database.sqlserver.php");

// **********************
// CLASS DECLARATION
// **********************
class catalogo{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $COD_CATALOGO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página
	var $SEQ_CATALOGO;
	var $DES_CATALOGO;   // (normal Attribute)

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function catalogo(){
		$this->database = new DatabaseSQLServer();
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
	function getCOD_CATALOGO(){
		return $this->COD_CATALOGO;
	}

	function getDES_CATALOGO(){
		return $this->DES_CATALOGO;
	}

	function getSEQ_CATALOGO(){
		return $this->SEQ_CATALOGO;
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
	function setCOD_CATALOGO($val){
		$this->COD_CATALOGO =  $val;
	}

	function setDES_CATALOGO($val){
		$this->DES_CATALOGO =  $val;
	}

	function setSEQ_CATALOGO($val){
		$this->SEQ_CATALOGO =  $val;
	}	
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "SELECT a.ast_cat_id as SEQ_CATALOGO,
					   substring(a.ast_cat_id,2,6) as COD_CATALOGO,
					   long_descp as DES_CATALOGO
				FROM gestaoti.DBSfast..ast_cat a (nolock)
				WHERE substring(a.ast_cat_id,2,6) = '$id'";

		$this->database->query($sql);
		$row = odbc_fetch_object($this->database->result);

		$this->COD_CATALOGO = $row->COD_CATALOGO;
		$this->DES_CATALOGO = $row->DES_CATALOGO;
		$this->SEQ_CATALOGO = $row->SEQ_CATALOGO;
	}	

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		if($vNumPagina != ""){
			$sqlSelect = " Select distinct top $vQtdRegistros substring(a.ast_cat_id,2,6) as COD_CATALOGO,
								  long_descp as DES_CATALOGO ";
		}else{
			$sqlSelect = " Select substring(a.ast_cat_id,2,6) as COD_CATALOGO,
								  long_descp as DES_CATALOGO ";
		}
		$sqlCorpo  = " FROM gestaoti.DBSfast..ast_cat a (nolock)
							 inner join DBSfast..ast_cat_book b (nolock)
							 on a.ast_entity_id = b.ast_entity_id
							 and a.ast_cat_id = b.ast_cat_id
						where (
									(  b.conta =  '132020010'  and  a.ast_cat_id  like '1%' ) -- Infraero
								or  (  b.conta =  '153050012'  and  a.ast_cat_id  like '2%' ) -- União
							   )


						";

		if($this->DES_CATALOGO != ""){
			$sqlCorpo .= "  and upper(a.long_descp) like  '%".strtoupper($this->DES_CATALOGO)."%' ";
		}

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1) * 2;

			$sqlOrder  .= " and a.ast_cat_id NOT IN (
								Select top $vLimit aa.ast_cat_id ";
		$sqlCorpo  = "FROM gestaoti.DBSfast..ast_cat aa (nolock)
									 inner join DBSfast..ast_cat_book bb (nolock)
									 on aa.ast_entity_id = bb.ast_entity_id
									 and aa.ast_cat_id = bb.ast_cat_id
								where (
											(  conta =  '132020010'  and  aa.ast_cat_id  like '1%' ) -- Infraero
										or  (  conta =  '153050012'  and  aa.ast_cat_id  like '2%' ) -- União
									  ) ";
				if($this->DES_CATALOGO != ""){
					$sqlOrder .= "  and upper(aa.long_descp) like  '%".strtoupper($this->DES_CATALOGO)."%' ";
				}

				if($orderBy != "" ){
					$sqlOrder .= " order by aa.long_descp ";
				}
				$sqlOrder  .= ") ";

			// Iniciar nova sessão para realizar a pesquisa de quantidade
			$db = new DatabaseSQLServer();
			$db->query("select count(1) as contador " . $sqlCorpo);
			$rowCount = odbc_fetch_array($db->result);
			$this->setrowCount($rowCount["contador"]/2);
			$db = "";
		}

		if($orderBy != "" ){
			$sqlOrder .= " order by a.long_descp ";
		}
		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}

	function combo($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		if($vNumPagina != ""){
			$sqlSelect = " Select distinct top $vQtdRegistros substring(a.ast_cat_id,2,6) as COD_CATALOGO,
								  long_descp as DES_CATALOGO ";
		}else{
			$sqlSelect = " Select distinct substring(a.ast_cat_id,2,6) as COD_CATALOGO,
								  long_descp as DES_CATALOGO ";
		}
		$sqlCorpo  = " FROM gestaoti.DBSfast..ast_cat a (nolock)
							 inner join DBSfast..ast_cat_book b (nolock)
							 on a.ast_entity_id = b.ast_entity_id
							 and a.ast_cat_id = b.ast_cat_id
						where (
									(  b.conta =  '132020010'  and  a.ast_cat_id  like '1%' ) -- Infraero
								or  (  b.conta =  '153050012'  and  a.ast_cat_id  like '2%' ) -- União
							   )


						";

		if($this->DES_CATALOGO != ""){
			$sqlCorpo .= "  and uuper(long_descp) like  '%".strtoupper($this->DES_CATALOGO)."%' ";
		}

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);

			$sqlCorpo  .= " and a.ast_cat_id NOT IN (
								Select top $vLimit aa.ast_cat_id ";
		$sqlCorpo  = "FROM gestaoti.DBSfast..ast_cat aa (nolock)
									 inner join DBSfast..ast_cat_book bb (nolock)
									 on aa.ast_entity_id = bb.ast_entity_id
									 and aa.ast_cat_id = bb.ast_cat_id
								where (
											(  conta =  '132020010'  and  aa.ast_cat_id  like '1%' ) -- Infraero
										or  (  conta =  '153050012'  and  aa.ast_cat_id  like '2%' ) -- União
										)
								)";
				if($orderBy != "" ){
					$sqlCorpo .= " order by $orderBy ";
				}
				$sqlCorpo  .= ") ";

			$db = new DatabaseSQLServer();
			$db->query("select count(1) as contador " . $sqlCorpo);
			$rowCount = odbc_fetch_array($db->result);
			$this->setrowCount($rowCount["contador"]/2);
			$db = "";
		}

		if($orderBy != "" ){
			$sqlOrder .= " order by $orderBy ";
		}
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}
} // class : end
?>