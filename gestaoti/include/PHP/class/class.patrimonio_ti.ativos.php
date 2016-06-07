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
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.sqlserver.php");
}else{
	include_once("include/PHP/class/class.database.sqlserver.php");
}

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
		$this->COD_DEPENDENCIA =  $val;
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
	function setNUM_PATRIMONIO($val){
		$this->NUM_PATRIMONIO =  $val;
	}

	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql = "select  TOP 1
						i.CD_UG as COD_REGIONAL,
						i.NOME_UG as DES_REGIONAL,
						i.SIGLA_UG as SIG_REGIONAL,
						j.CD_SETOR as COD_DEPENDENCIA,
						j.NOME_SETOR as DES_DEPENDENCIA,
						j.SIGLA as SIG_DEPENDENCIA,
						a.PATRIMONIO as NUM_PATRIMONIO,
						d.CD_CONTA as COD_CATEGORIA,
						d.DESCRICAO as DES_CATEGORIA,
						e.DESCRICAO_COMPLE as NOM_BEM,
						a.LEVANTAMENTO as DAT_AQUISICAO,
						NULL as NOM_MODELO,
						NULL as NOM_FABRICANTE,
						a.SERIE as NUM_SERIE,
						NULL as NOM_COR,
						g.DESCRICAO as DSC_LOCALIZACAO,
						case a.CD_RESPONSAVEL WHEN NULL then a.CD_RESPONSAVEL ELSE f.CD_RESPONSAVEL END as NUM_MATRICULA_DETENTOR,
						case a.CD_RESPONSAVEL WHEN NULL then c.NOME ELSE h.NOME END as NOM_DETENTOR,
						j.NOME_SETOR as NOM_LOTACAO,
						b.DESCRICAO as NOM_STATUS,
						NULL as QTD_VIDA_UTIL_ESTIMADA,
						NULL as QTD_VIDA_UTIL_TRANSCORRIDA,
						a.VALOR_AQUISICAO as VAL_AQUISICAO,
						NULL as VAL_DEPRECIACAO_ACUMULADA
					from patrimonio a (nolock) LEFT OUTER JOIN responsavel c (nolock) ON (a.CD_RESPONSAVEL = c.CD_RESPONSAVEL),
					     situacao_fisica b (nolock), planoconta d (nolock), material_nota e (nolock), localizacao f (nolock),
					     endereco g (nolock), responsavel h (nolock), ug i (nolock), setor j (nolock)
					where a.CD_SITUACAO = b.CD_SITUACAO
					and a.CD_CONTA = d.CD_CONTA
					and a.CD_UG = e.CD_UG
					and a.NOTA_RECEBIMENTO = e.NOTA_RECEBIMENTO
					and a.ITEM = e.ITEM
					and a.CP_MATERIAL = e.CP_MATERIAL
					and a.CD_LOCALIZACAO = f.CD_LOCALIZACAO
					and f.CD_ENDERECO = g.CD_ENDERECO
					and f.CD_RESPONSAVEL = h.CD_RESPONSAVEL
					and f.CD_UG = i.CD_UG
					and f.CD_SETOR = j.CD_SETOR
					and a.PATRIMONIO like '%".$id."00'";
		//print $sql;
		$this->database->query($sql);
		$row = odbc_fetch_array($this->database->result);

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
		$this->NUM_MATRICULA_DETENTOR = $row["NUM_MATRICULA_DETENTOR"];
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
		$sqlSelect = "  select distinct  ";
		if($vNumPagina != ""){
			$sqlSelect .= " top $vQtdRegistros ";
		}
		$sqlSelect .= " i.CD_UG as COD_REGIONAL,
						i.NOME_UG as DES_REGIONAL,
						i.SIGLA_UG as SIG_REGIONAL,
						j.CD_SETOR as COD_DEPENDENCIA,
						j.NOME_SETOR as NOM_DEPENDENCIA,
						j.SIGLA as SIG_DEPENDENCIA,
						a.PATRIMONIO as NUM_PATRIMONIO,
						d.CD_CONTA as COD_CATEGORIA,
						d.DESCRICAO as DES_CATEGORIA,
						e.DESCRICAO_COMPLE as NOM_BEM,
						a.LEVANTAMENTO as DAT_AQUISICAO,
						NULL as NOM_MODELO,
						NULL as NOM_FABRICANTE,
						a.SERIE as NUM_SERIE,
						NULL as NOM_COR,
						g.DESCRICAO as DSC_LOCALIZACAO,
						case a.CD_RESPONSAVEL WHEN NULL then a.CD_RESPONSAVEL ELSE f.CD_RESPONSAVEL END as NUM_MATRICULA_DETENTOR,
						case a.CD_RESPONSAVEL WHEN NULL then c.NOME ELSE h.NOME END as NOM_DETENTOR,
						j.SIGLA as NOM_LOTACAO,
						b.DESCRICAO as NOM_STATUS,
						NULL as QTD_VIDA_UTIL_ESTIMADA,
						NULL as QTD_VIDA_UTIL_TRANSCORRIDA,
						a.VALOR_AQUISICAO as VAL_AQUISICAO,
						NULL as VAL_DEPRECIACAO_ACUMULADA  ";


		$sqlCorpo  = "  from patrimonio a (nolock) LEFT OUTER JOIN responsavel c (nolock) ON (a.CD_RESPONSAVEL = c.CD_RESPONSAVEL),
						     situacao_fisica b (nolock), planoconta d (nolock), material_nota e (nolock), localizacao f (nolock),
						     endereco g (nolock), responsavel h (nolock), ug i (nolock), setor j (nolock)
						where a.CD_SITUACAO = b.CD_SITUACAO
						and a.CD_CONTA = d.CD_CONTA
						and a.CD_UG = e.CD_UG
						and a.NOTA_RECEBIMENTO = e.NOTA_RECEBIMENTO
						and a.ITEM = e.ITEM
						and a.CP_MATERIAL = e.CP_MATERIAL
						and a.CD_LOCALIZACAO = f.CD_LOCALIZACAO
						and f.CD_ENDERECO = g.CD_ENDERECO
						and f.CD_RESPONSAVEL = h.CD_RESPONSAVEL
						and f.CD_UG = i.CD_UG
						and f.CD_SETOR = j.CD_SETOR
						";
		if($this->COD_REGIONAL != ""){
			$sqlCorpo .= "  and i.CD_UG = '".$this->COD_REGIONAL."' ";
		}
		if($this->COD_DEPENDENCIA != ""){
			$sqlCorpo .= " and j.CD_SETOR = '".$this->COD_DEPENDENCIA."' ";
		}
		if($this->NOM_LOTACAO != ""){
			$sqlCorpo .= " and j.SIGLA like '".$this->NOM_LOTACAO."%' ";
		}
		if($this->COD_CATEGORIA != ""){
			$sqlCorpo .= " and d.CD_CONTA = '".$this->COD_CATEGORIA."' ";
		}
		//if($this->QTD_VIDA_UTIL_TRANSCORRIDA != ""){
		//	$sqlCorpo .= " and (b.elapsed_useful_life_yrs*12+b.elapsed_useful_life_pds) >= ".$this->QTD_VIDA_UTIL_TRANSCORRIDA." ";
		//}
		//if($this->CRITICIDADE != ""){
		//	$sqlCorpo .= " and a.user_fld_19 like '".$this->CRITICIDADE."%' ";
		//}
		if($this->NUM_MATRICULA_DETENTOR != ""){
			$sqlCorpo .= " and case a.CD_RESPONSAVEL WHEN NULL then a.CD_RESPONSAVEL ELSE f.CD_RESPONSAVEL END = '".$this->NUM_MATRICULA_DETENTOR."' ";
		}
		if($this->NUM_PATRIMONIO != ""){
			$sqlCorpo .= " and a.PATRIMONIO like '%".$this->NUM_PATRIMONIO."00' ";
		}
		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);

			$sqlOrder  .= "
						and a.PATRIMONIO NOT IN (
							select top $vLimit aa.PATRIMONIO
								from patrimonio aa (nolock) LEFT OUTER JOIN responsavel cc (nolock) ON (aa.CD_RESPONSAVEL = cc.CD_RESPONSAVEL),
								     situacao_fisica bb (nolock), planoconta dd (nolock), material_nota ee (nolock), localizacao ff (nolock),
								     endereco gg (nolock), responsavel hh (nolock), ug ii (nolock), setor jj (nolock)
								where aa.CD_SITUACAO = bb.CD_SITUACAO
								and aa.CD_CONTA = dd.CD_CONTA
								and aa.CD_UG = ee.CD_UG
								and aa.NOTA_RECEBIMENTO = ee.NOTA_RECEBIMENTO
								and aa.ITEM = ee.ITEM
								and aa.CP_MATERIAL = ee.CP_MATERIAL
								and aa.CD_LOCALIZACAO = ff.CD_LOCALIZACAO
								and ff.CD_ENDERECO = gg.CD_ENDERECO
								and ff.CD_RESPONSAVEL = hh.CD_RESPONSAVEL
								and ff.CD_UG = ii.CD_UG
								and ff.CD_SETOR = jj.CD_SETOR
					     ";
				if($this->COD_REGIONAL != ""){
					$sqlOrder .= "  and ii.CD_UG = '".$this->COD_REGIONAL."' ";
				}
				if($this->COD_DEPENDENCIA != ""){
					$sqlOrder .= " and jj.CD_SETOR = '".$this->COD_DEPENDENCIA."' ";
				}
				if($this->NOM_LOTACAO != ""){
					$sqlOrder .= " and jj.SIGLA like '".$this->NOM_LOTACAO."%' ";
				}
				if($this->COD_CATEGORIA != ""){
					$sqlOrder .= " and dd.CD_CONTA = '".$this->COD_CATEGORIA."' ";
				}
				//if($this->QTD_VIDA_UTIL_TRANSCORRIDA != ""){
				//	$sqlCorpo .= " and (b.elapsed_useful_life_yrs*12+b.elapsed_useful_life_pds) >= ".$this->QTD_VIDA_UTIL_TRANSCORRIDA." ";
				//}
				//if($this->CRITICIDADE != ""){
				//	$sqlCorpo .= " and a.user_fld_19 like '".$this->CRITICIDADE."%' ";
				//}
				if($this->NUM_MATRICULA_DETENTOR != ""){
					$sqlOrder .= " and case aa.CD_RESPONSAVEL WHEN NULL then aa.CD_RESPONSAVEL ELSE ff.CD_RESPONSAVEL END = '".$this->NUM_MATRICULA_DETENTOR."' ";
				}
				if($this->NUM_PATRIMONIO != ""){
					$sqlOrder .= " and aa.PATRIMONIO like '%".$this->NUM_PATRIMONIO."00' ";
				}
				//if($orderBy != "" ){
					$sqlOrder .= " order by gg.DESCRICAO ";
				//}
				$sqlOrder  .= ") ";

			$db = new DatabaseSQLServer();
			$db->query("select count(1) as contador " . $sqlCorpo);
			$rowCount = odbc_fetch_array($db->result);
			$this->setrowCount($rowCount["contador"]);
			$db = "";
		}

		//if($orderBy != "" ){
			$sqlOrder .= " order by g.DESCRICAO ";
		//}
		//print $sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);//print $sqlSelect . $sqlCorpo . $sqlOrder;

	}

	function GetRegionais($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		if($vNumPagina != ""){
			$sqlSelect = " Select distinct top $vQtdRegistros
							       CD_UG as COD_REGIONAL,
							       NOME_UG as SIG_REGIONAL,
							       SIGLA_UG as  NOM_REGIONAL  ";
		}else{
			$sqlSelect = " Select distinct CD_UG as COD_REGIONAL,
							       NOME_UG as SIG_REGIONAL,
							       SIGLA_UG as  NOM_REGIONAL  ";
		}
		$sqlCorpo  = " FROM ug
					 ";

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);

			$sqlCorpo  .= " and CD_UG NOT IN (
								Select top $vLimit CD_UG ";
			$sqlCorpo  = "FROM ug
						  ";
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

	function GetDependencias($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);
		if($vNumPagina != ""){
			$sqlSelect = " Select top $vQtdRegistros
							       CD_SETOR as COD_DEPENDENCIA,
						           SIGLA as SIG_DEPENDENCIA,
						           NOME_SETOR as NOM_DEPENDENCIA  ";
		}else{
			$sqlSelect = " Select  CD_SETOR as COD_DEPENDENCIA,
						           SIGLA as SIG_DEPENDENCIA,
						           NOME_SETOR as NOM_DEPENDENCIA   ";
		}
		$sqlCorpo  = " FROM setor
					   WHERE EXCLUIDO = 'N'
					 ";

		if($vNumPagina != ""){
			$vLimit = $vQtdRegistros * ($vNumPagina - 1);

			$sqlCorpo  .= " and CD_SETOR NOT IN (
								Select top $vLimit CD_SETOR ";
		$sqlCorpo  = "FROM setor
						  ";
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