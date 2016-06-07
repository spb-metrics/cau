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
class ativos{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $COD_REGIONAL;
	var $DES_REGIONAL;
	var $SIG_REGIONAL;
	var $COD_DEPENDENCIA;
	var $DES_DEPENDENCIA;
	var $SIG_DEPENDENCIA;
	var $NUM_PATRIMONIO;
	var $COD_CATEGORIA;
	var $NOM_BEM;
	var $DAT_AQUISICAO;
	var $NOM_MODELO;
	var $NOM_FABRICANTE;
	var $NUM_SERIE;
	var $NOM_COR;
	var $DSC_LOCALIZACAO;
	var $NUM_MATRICULA_DETENTOR;
	var $NOM_DETENTOR;
	var $NOM_LOTACAO;
	var $NOM_STATUS;
	var $QTD_VIDA_UTIL_ESTIMADA;
	var $QTD_VIDA_UTIL_TRANSCORRIDA;
	var $VAL_AQUISICAO;
	var $VAL_DEPRECIACAO_ACUMULADA;
	var $CRITICIDADE;


	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function ativos(){
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
	// **********************
	// SETTER METHODS
	// **********************
	function setrowCount($val){
		$this->rowCount = $val;
	}
	
	function setvQtdRegistros($val){
		$this->vQtdRegistros = $val;
	}
	function setCOD_REGIONAL($val){
		$this->REGIONAL =  $val;
	}
	function setCOD_DEPENDENCIA($val){
		$this->DEPENDENCIA =  $val;
	}
	function setNOM_LOTACAO($val){
		$this->NOM_LOTACAO =  $val;
	}
	function setCOD_CATEGORIA($val){
		$this->COD_CATEGORIA =  $val;
	}
	function setQTD_VIDA_UTIL_ESTIMADA($val){
		$this->QTD_VIDA_UTIL_ESTIMADA =  $val;
	}
	function setQTD_VIDA_UTIL_TRANSCORRIDA($val){
		$this->QTD_VIDA_UTIL_TRANSCORRIDA =  $val;
	}
	function setCRITICIDADE($val){
		$this->CRITICIDADE =  $val;
	}
	function setNUM_MATRICULA_DETENTOR($val){
		$this->NUM_MATRICULA_DETENTOR =  $val;
	}	
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "select
					 substring(a.ast_entity_id,1,2) as COD_REGIONAL,
					 e.desc_centro as DES_REGIONAL,
					 e.centro as SIG_REGIONAL,
					 b.dependencia as COD_DEPENDENCIA,
					 e.desc_dependencia as DES_DEPENDENCIA,
					 e.sigla_dependencia as SIG_DEPENDENCIA,
					 substring(a.ast_id,1,1)+'-'+substring(a.ast_id,4,7) as NUM_PATRIMONIO,
					 a.ast_cat_id as COD_CATEGORIA,
					 a.long_descp as NOM_BEM,
					 convert(varchar, a.date_ast_acquired, 20) as DAT_AQUISICAO,
					 a.model_or_part_nbr as NOM_MODELO,
					 a.manufacturer_id as NOM_FABRICANTE,
					 a.serial_nbr as NUM_SERIE,
					 a.user_fld_29 as NOM_COR,
					 SUBSTRING(rtrim(d.POINT_NAME),1,10) + ' - ' + substring(d.POINT_DESC,1,80) as DSC_LOCALIZACAO,
					 a.user_fld_2 as NUM_MATRICULA_DETENTOR,
					 c.NOME as NOM_DETENTOR,
					 UOR_SIGLA as NOM_LOTACAO,
					 c.STATUS as NOM_STATUS,
					 b.estimated_useful_life_yrs*12 as QTD_VIDA_UTIL_ESTIMADA,
					 (elapsed_useful_life_yrs*12+elapsed_useful_life_pds) as QTD_VIDA_UTIL_TRANSCORRIDA,
					 b.ast_tot_cost as VAL_AQUISICAO,
					 b.ltd_depr_amt as VAL_DEPRECIACAO_ACUMULADA,
					 a.user_fld_19 as CRITICIDADE
				FROM gestaoti.DBSfast..asset a(nolock)
				  inner join  DBSfast..ast_book b(nolock)
					on  a.ast_entity_id = b.ast_entity_id
				   and  a.ast_id = b.ast_id
				   and  b.ast_book_nbr = 1
				  left join INFRAERO..VIW_EMPREGADOS_NTB c (nolock)
					on  a.user_fld_2 = c.MATRICULA
				  left join DBSosst.dbo.SRG_DATA_NAVIGATION     d (NOLOCK)
					on  a.user_fld_5 = SUBSTRING(rtrim(d.POINT_NAME),1,9)
				  left join GEACrpt..gvw_desc_dependencia e(nolock)
					on  b.dependencia = e.dependencia
				where substring(a.ast_id,1,1)+'-'+substring(a.ast_id,4,7) = '$id' ";

		$this->database->query($sql);
		$row = odbc_fetch_object($this->database->result);

		$this->COD_REGIONAL = $row["COD_REGIONAL"];
		$this->DES_REGIONAL = $row["DES_REGIONAL"];
		$this->SIG_REGIONAL = $row["SIG_REGIONAL"];
		$this->COD_DEPENDENCIA = $row["COD_DEPENDENCIA"];
		$this->DES_DEPENDENCIA = $row["DES_DEPENDENCIA"];
		$this->SIG_DEPENDENCIA = $row["SIG_DEPENDENCIA"];
		$this->NUM_PATRIMONIO = $row["NUM_PATRIMONIO"];
		$this->COD_CATEGORIA = $row[""];
		$this->NOM_BEM = $row["NOM_BEM"];
		$this->DAT_AQUISICAO = $row["DAT_AQUISICAO"];
		$this->NOM_MODELO = $row["NOM_MODELO"];
		$this->NOM_FABRICANTE = $row["NOM_FABRICANTE"];
		$this->NUM_SERIE = $row["NUM_SERIE"];
		$this->NOM_COR = $row["NOM_COR"];
		$this->DSC_LOCALIZACAO = $row["DSC_LOCALIZACAO"];
		$this->NUM_MATRICULA_DETENTOR = $row[""];
		$this->NOM_DETENTOR = $row["NOM_DETENTOR"];
		$this->NOM_LOTACAO = $row["NOM_LOTACAO"];
		$this->NOM_STATUS = $row["NOM_STATUS"];
		$this->QTD_VIDA_UTIL_ESTIMADA = $row["QTD_VIDA_UTIL_ESTIMADA"];
		$this->QTD_VIDA_UTIL_TRANSCORRIDA = $row["QTD_VIDA_UTIL_TRANSCORRIDA"];
		$this->VAL_AQUISICAO = $row["VAL_AQUISICAO"];
		$this->VAL_DEPRECIACAO_ACUMULADA = $row["VAL_DEPRECIACAO_ACUMULADA"];
		$this->CRITICIDADE = $row["CRITICIDADE"];
	}	

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		if($vNumPagina != ""){
			$sqlSelect = " select distinct top $vQtdRegistros
								 substring(a.ast_entity_id,1,2) as COD_REGIONAL,
								 e.desc_centro as DES_REGIONAL,
								 e.centro as SIG_REGIONAL,
								 b.dependencia as COD_DEPENDENCIA,
								 e.desc_dependencia as DES_DEPENDENCIA,
								 e.sigla_dependencia as SIG_DEPENDENCIA,
								 substring(a.ast_id,1,1)+'-'+substring(a.ast_id,4,7) as NUM_PATRIMONIO,
								 a.ast_cat_id as COD_CATEGORIA,
								 a.long_descp as NOM_BEM,
								 convert(varchar, a.date_ast_acquired, 20) as DAT_AQUISICAO,
								 a.model_or_part_nbr as NOM_MODELO,
								 a.manufacturer_id as NOM_FABRICANTE,
								 a.serial_nbr as NUM_SERIE,
								 a.user_fld_29 as NOM_COR,
								 SUBSTRING(rtrim(d.POINT_NAME),1,10) + ' - ' + substring(d.POINT_DESC,1,80) as DSC_LOCALIZACAO,
								 a.user_fld_2 as NUM_MATRICULA_DETENTOR,
								 c.NOME as NOM_DETENTOR,
								 UOR_SIGLA as NOM_LOTACAO,
								 c.STATUS as NOM_STATUS,
								 b.estimated_useful_life_yrs*12 as QTD_VIDA_UTIL_ESTIMADA,
								 (elapsed_useful_life_yrs*12+elapsed_useful_life_pds) as QTD_VIDA_UTIL_TRANSCORRIDA,
								 b.ast_tot_cost as VAL_AQUISICAO,
								 b.ltd_depr_amt as VAL_DEPRECIACAO_ACUMULADA,
								 a.user_fld_19 as CRITICIDADE ";

		}else{
			$sqlSelect = " select distinct
								 substring(a.ast_entity_id,1,2) as COD_REGIONAL,
								 e.desc_centro as DES_REGIONAL,
								 e.centro as SIG_REGIONAL,
								 b.dependencia as COD_DEPENDENCIA,
								 e.desc_dependencia as DES_DEPENDENCIA,
								 e.sigla_dependencia as SIG_DEPENDENCIA,
								 substring(a.ast_id,1,1)+'-'+substring(a.ast_id,4,7) as NUM_PATRIMONIO,
								 a.ast_cat_id as COD_CATEGORIA,
								 a.long_descp as NOM_BEM,
								 convert(varchar, a.date_ast_acquired, 20) as DAT_AQUISICAO,
								 a.model_or_part_nbr as NOM_MODELO,
								 a.manufacturer_id as NOM_FABRICANTE,
								 a.serial_nbr as NUM_SERIE,
								 a.user_fld_29 as NOM_COR,
								 SUBSTRING(rtrim(d.POINT_NAME),1,10) + ' - ' + substring(d.POINT_DESC,1,80) as DSC_LOCALIZACAO,
								 a.user_fld_2 as NUM_MATRICULA_DETENTOR,
								 c.NOME as NOM_DETENTOR,
								 UOR_SIGLA as NOM_LOTACAO,
								 c.STATUS as NOM_STATUS,
								 b.estimated_useful_life_yrs*12 as QTD_VIDA_UTIL_ESTIMADA,
								 (elapsed_useful_life_yrs*12+elapsed_useful_life_pds) as QTD_VIDA_UTIL_TRANSCORRIDA,
								 b.ast_tot_cost as VAL_AQUISICAO,
								 b.ltd_depr_amt as VAL_DEPRECIACAO_ACUMULADA,
								 a.user_fld_19 as CRITICIDADE ";
		}
		$sqlCorpo  = " FROM gestaoti.DBSfast..asset a(nolock)
							 inner join  DBSfast..ast_book b(nolock)
								on  a.ast_entity_id = b.ast_entity_id
							   and  a.ast_id = b.ast_id
							   and  b.ast_book_nbr = 1
							 left join INFRAERO..VIW_EMPREGADOS_NTB c (nolock)
								on  a.user_fld_2 = c.MATRICULA
							 left join DBSosst.dbo.SRG_DATA_NAVIGATION     d (NOLOCK)
								on  a.user_fld_5 = SUBSTRING(rtrim(d.POINT_NAME),1,9)
							 left join GEACrpt..gvw_desc_dependencia e(nolock)
								on  b.dependencia = e.dependencia
						where (     (  b.conta =  '132020010'  and  a.ast_cat_id  like '1%' )
								 or (  b.conta =  '153050012'  and  a.ast_cat_id  like '2%' )
								   ) ";
		if($this->COD_REGIONAL != ""){
			$sqlCorpo .= "  and a.ast_entity_id like '".$this->COD_REGIONAL."%' ";
		}
		if($this->COD_DEPENDENCIA != ""){
			$sqlCorpo .= " and b.dependencia like '".$this->COD_DEPENDENCIA."%' ";
		}
		if($this->NOM_LOTACAO != ""){
			$sqlCorpo .= " and RTRIM(UOR_SIGLA) like '".$this->NOM_LOTACAO."%' ";
		}
		if($this->COD_CATEGORIA != ""){
			$sqlCorpo .= " and (   ( a.ast_cat_id  like '".$this->COD_CATEGORIA."%' )
								or ( len('".$this->COD_CATEGORIA."') = 6  and substring(a.ast_cat_id,2,6) = '".$this->COD_CATEGORIA."')
							   )  ";
		}
		if($this->QTD_VIDA_UTIL_TRANSCORRIDA != ""){
			$sqlCorpo .= " and (b.elapsed_useful_life_yrs*12+b.elapsed_useful_life_pds) >= ".$this->QTD_VIDA_UTIL_TRANSCORRIDA." ";
		}
		if($this->CRITICIDADE != ""){
			$sqlCorpo .= " and a.user_fld_19 like '".$this->CRITICIDADE."%' ";
		}
		if($this->NUM_MATRICULA_DETENTOR != ""){
			$sqlCorpo .= " and a.user_fld_2 = '".$this->NUM_MATRICULA_DETENTOR."' ";
		}
		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);

			$sqlOrder  .= "
						and a.ast_id NOT IN (
							select top $vLimit aa.ast_id
							FROM gestaoti.DBSfast..asset aa(nolock)
							  inner join  DBSfast..ast_book bb(nolock)
								on  aa.ast_entity_id = bb.ast_entity_id
							   and  aa.ast_id = bb.ast_id
							   and  bb.ast_book_nbr = 1
							  left join INFRAERO..VIW_EMPREGADOS_NTB cc (nolock)
								on  aa.user_fld_2 = cc.MATRICULA
					     ";
				if($this->COD_REGIONAL != ""){
					$sqlOrder .= "  and aa.ast_entity_id like '".$this->COD_REGIONAL."%' ";
				}
				if($this->COD_DEPENDENCIA != ""){
					$sqlOrder .= " and bb.dependencia like '".$this->COD_DEPENDENCIA."%' ";
				}
				if($this->NOM_LOTACAO != ""){
					$sqlOrder .= " and RTRIM(cc.UOR_SIGLA) like '".$this->NOM_LOTACAO."%' ";
				}
				if($this->COD_CATEGORIA != ""){
					$sqlOrder .= " and (   ( a.ast_cat_id  like '".$this->COD_CATEGORIA."%' )
										or ( len('".$this->COD_CATEGORIA."') = 6  and substring(a.ast_cat_id,2,6) = '".$this->COD_CATEGORIA."')
									   )  ";
				}
				if($this->QTD_VIDA_UTIL_TRANSCORRIDA != ""){
					$sqlOrder .= " and (bb.elapsed_useful_life_yrs*12+bb.elapsed_useful_life_pds) >= ".$this->QTD_VIDA_UTIL_TRANSCORRIDA." ";
				}
				if($this->CRITICIDADE != ""){
					$sqlOrder .= " and aa.user_fld_19 like '".$this->CRITICIDADE."%' ";
				}
				if($this->NUM_MATRICULA_DETENTOR != ""){
					$sqlOrder .= " and aa.user_fld_2 = '".$this->NUM_MATRICULA_DETENTOR."' ";
				}
				//if($orderBy != "" ){
					$sqlOrder .= " order by aa.long_descp ";
				//}
				$sqlOrder  .= ") ";
			$this->database->query("select count(1) as contador " . $sqlCorpo);
			$rowCount = odbc_fetch_array($this->database->result);
			$this->setrowCount($rowCount["contador"]);
		}

		//if($orderBy != "" ){
			$sqlOrder .= " order by a.long_descp ";
		//}
		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);//print $sqlSelect . $sqlCorpo . $sqlOrder;
		
	}

	function combo($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
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
			$this->database->query("select count(1) " . $sqlCorpo);
			$rowCount = odbc_fetch_array($this->database->result);
			$this->setrowCount($rowCount[0]);
		}

		if($orderBy != "" ){
			$sqlOrder .= " order by $orderBy ";
		}
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}
} // class : end
?>