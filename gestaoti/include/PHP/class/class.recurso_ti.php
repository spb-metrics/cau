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
* CLASSNAME:        recurso_ti
* -------------------------------------------------------
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
	include_once("../gestaoti/include/PHP/class/class.empregados.oracle.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
	include_once("include/PHP/class/class.empregados.oracle.php");
}

// **********************
// CLASS DECLARATION
// **********************
class recurso_ti{ // class : begin
	
	var $SQL_EXPORT;
	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NUM_MATRICULA_RECURSO;   // (normal Attribute)
	var $SEQ_PERFIL_RECURSO_TI;   // (normal Attribute)
	var $COD_UOR;   // (normal Attribute)
	var $FLG_LIDER;   // (normal Attribute)
	var $SEQ_AREA_ATUACAO;   // (normal Attribute)
	var $SEQ_PERFIL_ACESSO;
	var $SEQ_EQUIPE_TI;

	var $NOM_LOGIN_REDE;   // (normal Attribute)
	var $NOME;   // (normal Attribute)
	var $NOME_ABREVIADO;   // (normal Attribute)
	var $NOME_GUERRA;   // (normal Attribute)
	var $DEP_SIGLA;   // (normal Attribute)
	var $UOR_SIGLA;   // (normal Attribute)
	var $DES_EMAIL;   // (normal Attribute)
	var $NUM_DDD;   // (normal Attribute)
	var $NUM_TELEFONE;   // (normal Attribute)
	var $NUM_VOIP;   // (normal Attribute)
	var $DES_ATATUS;   // (normal Attribute)
	var $NOM_EQUIPE_TI;
	var $NUM_MATRICULA_PRIORIZADOR;	
	var $SEQ_CENTRAL_ATENDIMENTO;  
	var $NOM_CENTRAL_ATENDIMENTO;  
        var $DES_SENHA;
	  

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados
	var $empregados;

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	function recurso_ti(){
		$this->database = new Database();
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
	function getNUM_MATRICULA_RECURSO(){
		return $this->NUM_MATRICULA_RECURSO;
	}

	function getSEQ_PERFIL_RECURSO_TI(){
		return $this->SEQ_PERFIL_RECURSO_TI;
	}

	function getCOD_UOR(){
		return $this->COD_UOR;
	}

	function getFLG_LIDER(){
		return $this->FLG_LIDER;
	}

	function getSEQ_AREA_ATUACAO(){
		return $this->SEQ_AREA_ATUACAO;
	}

	function getNOM_LOGIN_REDE(){
		return $this->NOM_LOGIN_REDE;
	}

	function getNOME(){
		return $this->NOME;
	}

	function getNOME_ABREVIADO(){
		return $this->NOME_ABREVIADO;
	}

	function getNOME_GUERRA(){
		return $this->NOME_GUERRA;
	}

	function getDEP_SIGLA(){
		return $this->DEP_SIGLA;
	}

	function getUOR_SIGLA(){
		return $this->UOR_SIGLA;
	}

	function getDES_EMAIL(){
		return $this->DES_EMAIL;
	}

	function getNUM_DDD(){
		return $this->NUM_DDD;
	}

	function getNUM_TELEFONE(){
		return $this->NUM_TELEFONE;
	}

	function getNUM_VOIP(){
		return $this->NUM_VOIP;
	}

	function getDES_ATATUS(){
		return $this->DES_ATATUS;
	}

	function getSEQ_PERFIL_ACESSO(){
		return $this->SEQ_PERFIL_ACESSO;
	}

	function getSEQ_EQUIPE_TI(){
		return $this->SEQ_EQUIPE_TI;
	}
	
	function getSEQ_CENTRAL_ATENDIMENTO(){
		return $this->SEQ_CENTRAL_ATENDIMENTO;
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
	function setNUM_MATRICULA_RECURSO($val){
		$this->NUM_MATRICULA_RECURSO =  $val;
	}

	function setSEQ_PERFIL_RECURSO_TI($val){
		$this->SEQ_PERFIL_RECURSO_TI =  $val;
	}

	function setCOD_UOR($val){
		$this->COD_UOR =  $val;
	}

	function setFLG_LIDER($val){
		$this->FLG_LIDER =  $val;
	}

	function setNOM_LOGIN_REDE($val){
		$this->NOM_LOGIN_REDE =  $val;
	}

	function setNOME($val){
		$this->NOME =  $val;
	}

	function setNOME_ABREVIADO($val){
		$this->NOME_ABREVIADO =  $val;
	}

	function setNOME_GUERRA($val){
		$this->NOME_GUERRA =  $val;
	}

	function setDEP_SIGLA($val){
		$this->DEP_SIGLA =  $val;
	}

	function setUOR_SIGLA($val){
		$this->UOR_SIGLA =  $val;
	}

	function setDES_EMAIL($val){
		$this->DES_EMAIL =  $val;
	}

	function setNUM_DDD($val){
		$this->NUM_DDD =  $val;
	}

	function setNUM_TELEFONE($val){
		$this->NUM_TELEFONE =  $val;
	}

	function setNUM_VOIP($val){
		$this->NUM_VOIP =  $val;
	}

	function setDES_ATATUS($val){
		$this->DES_ATATUS =  $val;
	}

	function setSEQ_PERFIL_ACESSO($val){
		$this->SEQ_PERFIL_ACESSO =  $val;
	}

	function setSEQ_AREA_ATUACAO($val){
		$this->SEQ_AREA_ATUACAO =  $val;
	}
	function setSEQ_EQUIPE_TI($val){
		$this->SEQ_EQUIPE_TI =  $val;
	}

	function setSEQ_CENTRAL_ATENDIMENTO($val){
		$this->SEQ_CENTRAL_ATENDIMENTO =  $val;
	}
	
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		$sql =  " SELECT NUM_MATRICULA_RECURSO, SEQ_PERFIL_RECURSO_TI, SEQ_PERFIL_ACESSO, SEQ_AREA_ATUACAO, a.SEQ_EQUIPE_TI, 
                                 b.NOM_EQUIPE_TI
                          FROM gestaoti.recurso_ti a, gestaoti.equipe_ti b
                          WHERE a.SEQ_EQUIPE_TI = b.SEQ_EQUIPE_TI
                          and a.NUM_MATRICULA_RECURSO = '$id' ";
		$this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
		$this->SEQ_PERFIL_RECURSO_TI = $row->seq_perfil_recurso_ti;
		/*TODO: NOVO PERFIL ACESSO*/
		//$this->SEQ_PERFIL_ACESSO = $row->seq_perfil_acesso;		
		//require_once 'include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
		if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
			require_once '../gestaoti/include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
		}else{
			require_once 'include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
		}
		$recurso_ti_x_perfil_acesso = new recurso_ti_x_perfil_acesso();
		$recurso_ti_x_perfil_acesso->setNUM_MATRICULA_RECURSO($row->num_matricula_recurso);
		$recurso_ti_x_perfil_acesso->selectParam();
		
		//Verificando se já foi realizada a migração			
		if($recurso_ti_x_perfil_acesso->database->rows == 0){
			$this->SEQ_PERFIL_ACESSO = Array($row->seq_perfil_acesso);				 
		}else{				
			$i = 0;
			$SeqPerfisAcesso = Array();
			while ($rowSeq = pg_fetch_array($recurso_ti_x_perfil_acesso->database->result)){
				$SeqPerfisAcesso[$i] = $rowSeq["seq_perfil_acesso"]; 
				$i++;
			}
			$this->SEQ_PERFIL_ACESSO = $SeqPerfisAcesso;
		}
		/*TODO: NOVO PERFIL ACESSO*/
		$this->SEQ_AREA_ATUACAO = $row->seq_area_atuacao;
		$this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
		$this->NOM_EQUIPE_TI = $row->nom_equipe_ti;
		// Pegar dados do colaborador
		$this->empregados = new empregados();
		$this->empregados->select($id);
		$this->NOM_LOGIN_REDE = $this->empregados->NOM_LOGIN_REDE;
		$this->NOME = $this->empregados->NOME;
		$this->NOME_ABREVIADO = $this->empregados->NOME_ABREVIADO;
		$this->NOME_GUERRA = $this->empregados->NOME_GUERRA;
		$this->DEP_SIGLA = $this->empregados->DEP_SIGLA;
		$this->UOR_SIGLA = $this->empregados->UOR_SIGLA;
		$this->DES_EMAIL = $this->empregados->DES_EMAIL;
		$this->NUM_DDD = $this->empregados->NUM_DDD;
		$this->NUM_TELEFONE = $this->empregados->NUM_TELEFONE;
		$this->NUM_VOIP = $this->empregados->NUM_VOIP;
		$this->DES_STATUS = $this->empregados->DES_STATUS;
	}

	function login($id){
		$sql =  "   select  a.*, b.SEQ_PERFIL_RECURSO_TI, b.SEQ_PERFIL_ACESSO, b.SEQ_AREA_ATUACAO, b.SEQ_EQUIPE_TI,  c.NOM_EQUIPE_TI, 
                                    c.NUM_MATRICULA_PRIORIZADOR,d.seq_central_atendimento, d.nom_central_atendimento
                            from gestaoti.viw_age_empregados a, gestaoti.recurso_ti b, gestaoti.equipe_ti c,gestaoti.central_atendimento d
                            WHERE a.NUM_MATRICULA_RECURSO = b.NUM_MATRICULA_RECURSO
                            and b.SEQ_EQUIPE_TI = c.SEQ_EQUIPE_TI
                            and c.seq_central_atendimento = d.seq_central_atendimento 
                            and upper(a.NOM_LOGIN_REDE) = '".mb_strtoupper($id,'LATIN1')."' ";
		$this->database->query($sql);
		$result = $this->database->result;
		if($this->database->rows > 0){
			$row = pg_fetch_object($result, 0);

			$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
			$this->SEQ_PERFIL_RECURSO_TI = $row->seq_perfil_recurso_ti;
			$this->NOM_LOGIN_REDE = $row->nom_login_rede;
			$this->NOME = $row->nome;
			$this->NOME_ABREVIADO = $row->nome_abreviado;
			$this->NOME_GUERRA = $row->nome_guerra;
			$this->DEP_ID = $row->dep_id;
			$this->DEP_SIGLA = $row->dep_sigla;
			$this->UOR_ID = $row->uor_id;
			$this->UOR_SIGLA = $row->uor_sigla;
			$this->COOR_ID = $row->coor_id;
			$this->COOR_SIGLA = $row->coor_sigla;
			$this->DES_EMAIL = $row->des_email;
			$this->NUM_DDD = $row->num_ddd;
			$this->NUM_TELEFONE = $row->num_telefone;
			$this->NUM_VOIP = $row->num_voip;
			$this->DES_ATATUS = $row->des_atatus;
                        $this->DES_SENHA = $row->des_senha;
                        $this->SEQ_EQUIPE_TI = $row->seq_equipe_ti;
			$this->NOM_EQUIPE_TI = $row->nom_equipe_ti;
			$this->NUM_MATRICULA_PRIORIZADOR = $row->num_matricula_priorizador;
			$this->SEQ_CENTRAL_ATENDIMENTO = $row->seq_central_atendimento;
			$this->NOM_CENTRAL_ATENDIMENTO = $row->nom_central_atendimento;
                        
			// Buscar perfis de acesso
			if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
				require_once '../gestaoti/include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
			}else{
				require_once 'include/PHP/class/class.recurso_ti_x_perfil_acesso.php';
			}
			$recurso_ti_x_perfil_acesso = new recurso_ti_x_perfil_acesso();
			$recurso_ti_x_perfil_acesso->setNUM_MATRICULA_RECURSO($row->num_matricula_recurso);
			$recurso_ti_x_perfil_acesso->selectParam();
			
			//Verificando se já foi realizada a migração			
			if($recurso_ti_x_perfil_acesso->database->rows == 0){
				$this->SEQ_PERFIL_ACESSO = Array($row->seq_perfil_acesso);				 
			}else{				
				$i = 0;
				$SeqPerfisAcesso = Array();
				while ($rowSeq = pg_fetch_array($recurso_ti_x_perfil_acesso->database->result)){
					$SeqPerfisAcesso[$i] = $rowSeq["seq_perfil_acesso"]; 
					$i++;
				}
				$this->SEQ_PERFIL_ACESSO = $SeqPerfisAcesso;
			}
		}
	}

	function cadastro($id){
		$sql =  "select a.*
                         from gestaoti.viw_age_empregados a
                         WHERE upper(NOM_LOGIN_REDE) = '".mb_strtoupper($id,'LATIN1')."' ";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
		$this->NOM_LOGIN_REDE = $row->nom_login_rede;
		$this->NOME = $row->nome;
		$this->NOME_ABREVIADO = $row->nome_abreviado;
		$this->NOME_GUERRA = $row->nome_guerra;
		$this->DEP_SIGLA = $row->dep_sigla;
		$this->UOR_SIGLA = $row->uor_sigla;
		$this->DES_EMAIL = $row->des_email;
		$this->NUM_DDD = $row->num_ddd;
		$this->NUM_TELEFONE = $row->num_telefone;
		$this->NUM_VOIP = $row->num_voip;
		$this->DES_ATATUS = $row->des_atatus;
	}

	function GetMatriculaSuperintendente(){
            /*TODO - Código FIXO - Verificar o que esta função faz....  */
                $sql = "select NUM_MATRICULA_RECURSO from gestaoti.recurso_ti where seq_perfil_recurso_ti = 24";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		return $row->NUM_MATRICULA_RECURSO;
	}

	function GetQtdSubordinados($v_NUM_MATRICULA_RECURSO){
		$sql = "select count(1) CONT from gestaoti.recurso_ti where SEQ_EQUIPE_TI = $v_NUM_MATRICULA_RECURSO";
		$result =  $this->database->query($sql);
		$result = $this->database->result;

		$row = pg_fetch_object($result);
		return $row->CONT;
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select a.*, b.SEQ_PERFIL_RECURSO_TI, b.SEQ_PERFIL_ACESSO, b.SEQ_AREA_ATUACAO, b.SEQ_EQUIPE_TI,
                                      c.NOM_PERFIL_RECURSO_TI, d.NOM_EQUIPE_TI, e.NOM_PERFIL_ACESSO, d.NUM_MATRICULA_LIDER ";
		$sqlCorpo  = "FROM  gestaoti.viw_age_empregados a,
                                    gestaoti.recurso_ti b
                                    LEFT OUTER JOIN gestaoti.equipe_ti d ON (b.SEQ_EQUIPE_TI = d.SEQ_EQUIPE_TI),
                                    gestaoti.perfil_recurso_ti c,
                                    gestaoti.perfil_acesso e,
                                    gestaoti.equipe_ti f
                              WHERE a.NUM_MATRICULA_RECURSO = b.NUM_MATRICULA_RECURSO
                                and b.SEQ_PERFIL_RECURSO_TI = c.SEQ_PERFIL_RECURSO_TI
                                and b.SEQ_PERFIL_ACESSO = e.SEQ_PERFIL_ACESSO
                                and b.SEQ_EQUIPE_TI = f.SEQ_EQUIPE_TI
						";

		if($this->NUM_MATRICULA_RECURSO != ""){
			$sqlCorpo .= "  and a.NUM_MATRICULA_RECURSO = $this->NUM_MATRICULA_RECURSO ";
		}
		if($this->NOM_LOGIN_REDE != ""){
			$sqlCorpo .= "  and upper(NOM_LOGIN_REDE) like '%".mb_strtoupper($this->NOM_LOGIN_REDE,'LATIN1')."%'  ";
		}
		if($this->NOME != ""){
			$sqlCorpo .= "  and upper(NOME) like '%".mb_strtoupper(str_replace(" ", "%", $this->NOME),'LATIN1')."%'  ";
		}
		if($this->NOME_ABREVIADO != ""){
			$sqlCorpo .= "  and upper(NOME_ABREVIADO) like '%".mb_strtoupper($this->NOME_ABREVIADO,'LATIN1')."%'  ";
		}
		if($this->NOME_GUERRA != ""){
			$sqlCorpo .= "  and upper(NOME_GUERRA) like '%".mb_strtoupper($this->NOME_GUERRA,'LATIN1')."%'  ";
		}
		if($this->DEP_SIGLA != ""){
			$sqlCorpo .= "  and upper(DEP_SIGLA) like '%".mb_strtoupper($this->DEP_SIGLA,'LATIN1')."%'  ";
		}
		if($this->UOR_SIGLA != ""){
			$sqlCorpo .= "  and upper(UOR_SIGLA) like '%".mb_strtoupper($this->UOR_SIGLA,'LATIN1')."%'  ";
		}
		if($this->DES_EMAIL != ""){
			$sqlCorpo .= "  and upper(DES_EMAIL) like '%".mb_strtoupper($this->DES_EMAIL,'LATIN1')."%'  ";
		}
		if($this->NUM_DDD != ""){
			$sqlCorpo .= "  and upper(NUM_DDD) like '%".mb_strtoupper($this->NUM_DDD,'LATIN1')."%'  ";
		}
		if($this->NUM_TELEFONE != ""){
			$sqlCorpo .= "  and upper(NUM_TELEFONE) like '%".mb_strtoupper($this->NUM_TELEFONE,'LATIN1')."%'  ";
		}
		if($this->NUM_VOIP != ""){
			$sqlCorpo .= "  and upper(NUM_VOIP) like '%".mb_strtoupper($this->NUM_VOIP,'LATIN1')."%'  ";
		}
		if($this->DES_ATATUS != ""){
			$sqlCorpo .= "  and upper(DES_ATATUS) like '%".mb_strtoupper($this->DES_ATATUS,'LATIN1')."%'  ";
		}

		if($this->SEQ_PERFIL_RECURSO_TI != ""){
			$sqlCorpo .= "  and b.SEQ_PERFIL_RECURSO_TI = $this->SEQ_PERFIL_RECURSO_TI ";
		}
		if($this->COD_UOR != ""){
			$sqlCorpo .= "  and upper(COD_UOR) like '%".mb_strtoupper($this->COD_UOR,'LATIN1')."%'  ";
		}
		if($this->SEQ_EQUIPE_TI != ""){
			$sqlCorpo .= "  and b.SEQ_EQUIPE_TI = '$this->SEQ_EQUIPE_TI' ";
		}
		
		if($this->SEQ_CENTRAL_ATENDIMENTO != ""){
			$sqlCorpo .= "  and f.SEQ_CENTRAL_ATENDIMENTO = $this->SEQ_CENTRAL_ATENDIMENTO ";
		}

		$this->SQL_EXPORT = $sqlSelect . $sqlCorpo;
		
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

	function GetGestores($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select * ";
		$sqlCorpo  = " from (
						SELECT PAGING.*, ROWNUM PAGING_RN
      					FROM (
								select * ";
		$sqlCorpo  = "FROM  gestaoti.viw_age_empregados a,
                                    (
                                        select distinct NUM_MATRICULA_GESTOR from gestaoti.item_configuracao) b
					where a.NUM_MATRICULA_RECURSO = b.NUM_MATRICULA_GESTOR
				     ) PAGING
                                WHERE 1=1";
		if($this->NUM_MATRICULA_RECURSO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_RECURSO = $this->NUM_MATRICULA_RECURSO ";
		}
		if($this->NOM_LOGIN_REDE != ""){
			$sqlCorpo .= "  and upper(NOM_LOGIN_REDE) like '%".mb_strtoupper($this->NOM_LOGIN_REDE)."%'  ";
		}
		if($this->NOME != ""){
			$sqlCorpo .= "  and upper(NOME) like '%".mb_strtoupper(str_replace(" ", "%", $this->NOME))."%'  ";
		}
		if($this->NOME_ABREVIADO != ""){
			$sqlCorpo .= "  and upper(NOME_ABREVIADO) like '%".mb_strtoupper($this->NOME_ABREVIADO)."%'  ";
		}
		if($this->NOME_GUERRA != ""){
			$sqlCorpo .= "  and upper(NOME_GUERRA) like '%".mb_strtoupper($this->NOME_GUERRA)."%'  ";
		}
		if($this->DEP_SIGLA != ""){
			$sqlCorpo .= "  and upper(DEP_SIGLA) like '%".mb_strtoupper($this->DEP_SIGLA)."%'  ";
		}
		if($this->UOR_SIGLA != ""){
			$sqlCorpo .= "  and upper(UOR_SIGLA) like '%".mb_strtoupper($this->UOR_SIGLA)."%'  ";
		}
		if($this->DES_EMAIL != ""){
			$sqlCorpo .= "  and upper(DES_EMAIL) like '%".mb_strtoupper($this->DES_EMAIL)."%'  ";
		}
		if($this->NUM_DDD != ""){
			$sqlCorpo .= "  and upper(NUM_DDD) like '%".mb_strtoupper($this->NUM_DDD)."%'  ";
		}
		if($this->NUM_TELEFONE != ""){
			$sqlCorpo .= "  and upper(NUM_TELEFONE) like '%".mb_strtoupper($this->NUM_TELEFONE)."%'  ";
		}
		if($this->NUM_VOIP != ""){
			$sqlCorpo .= "  and upper(NUM_VOIP) like '%".mb_strtoupper($this->NUM_VOIP)."%'  ";
		}
		if($this->DES_ATATUS != ""){
			$sqlCorpo .= "  and upper(DES_ATATUS) like '%".mb_strtoupper($this->DES_ATATUS)."%'  ";
		}

		$sqlCount = $sqlCorpo;

		if($orderBy != "" ){
			$sqlCorpo .= " order by $orderBy ";
		}

		$sqlOrder = "";

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
		$sql = "DELETE FROM gestaoti.recurso_ti WHERE NUM_MATRICULA_RECURSO = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$sql = "INSERT INTO gestaoti.recurso_ti (   NUM_MATRICULA_RECURSO,
                                                            SEQ_PERFIL_RECURSO_TI,
                                                            SEQ_PERFIL_ACESSO,
                                                            SEQ_AREA_ATUACAO,
                                                            SEQ_EQUIPE_TI )
				VALUES ( ".$this->database->iif($this->NUM_MATRICULA_RECURSO=="", "NULL", "'".$this->NUM_MATRICULA_RECURSO."'").",
                                         ".$this->database->iif($this->SEQ_PERFIL_RECURSO_TI=="", "NULL", "'".$this->SEQ_PERFIL_RECURSO_TI."'").",
                                         ".$this->database->iif($this->SEQ_PERFIL_ACESSO=="", "NULL", "'".$this->SEQ_PERFIL_ACESSO."'").",
                                         ".$this->database->iif($this->SEQ_AREA_ATUACAO=="", "NULL", "'".$this->SEQ_AREA_ATUACAO."'").",
                                         ".$this->database->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'")." )";
		$result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.recurso_ti SET
                                SEQ_PERFIL_RECURSO_TI = ".$this->database->iif($this->SEQ_PERFIL_RECURSO_TI=="", "NULL", "'".$this->SEQ_PERFIL_RECURSO_TI."'").",
                                SEQ_PERFIL_ACESSO = ".$this->database->iif($this->SEQ_PERFIL_ACESSO=="", "NULL", "'".$this->SEQ_PERFIL_ACESSO."'").",
                                SEQ_AREA_ATUACAO = ".$this->database->iif($this->SEQ_AREA_ATUACAO=="", "NULL", "'".$this->SEQ_AREA_ATUACAO."'").",
                                SEQ_EQUIPE_TI = ".$this->database->iif($this->SEQ_EQUIPE_TI=="", "NULL", "'".$this->SEQ_EQUIPE_TI."'")."
                         WHERE  NUM_MATRICULA_RECURSO = $id ";
		$result = $this->database->query($sql);
	}

	function combo($OrderBy, $vSelected=""){
		$sql = "select a.NUM_MATRICULA_RECURSO, b.NOM_COLABORADOR
                        from gestaoti.recurso_ti a, gestaoti.viw_colaborador b
                        where a.NUM_MATRICULA_RECURSO = b.NUM_MATRICULA_COLABORADOR
                        and a.SEQ_EQUIPE_TI = ".$this->SEQ_EQUIPE_TI;
		if($this->NUM_MATRICULA_RECURSO != ""){
			$sql .= " and a.NUM_MATRICULA_RECURSO = ".$this->NUM_MATRICULA_RECURSO." ";
		}
		$sql .= " ORDER by b.NOM_COLABORADOR ";
		$result = $this->database->query($sql);
		$aItemOption = Array();
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row["num_matricula_recurso"], $this->iif($vSelected == $row["num_matricula_recurso"],"Selected", ""), $row["nom_colaborador"]);
			$cont++;
		}
		return $aItemOption;
	}
	
	function comboExecutordeMudancas($OrderBy, $vSelected=""){
            /*  TODO: Código do executor fixo ?????   */
            
            
		$sql = "select a.NUM_MATRICULA_RECURSO, b.NOM_COLABORADOR
                        from gestaoti.recurso_ti a, gestaoti.viw_colaborador b, gestaoti.recurso_ti_x_perfil_acesso c
                        where a.NUM_MATRICULA_RECURSO = b.NUM_MATRICULA_COLABORADOR AND
                              a.NUM_MATRICULA_RECURSO = c.NUM_MATRICULA_RECURSO AND
                              c.SEQ_PERFIL_ACESSO = 7
                        and a.SEQ_EQUIPE_TI = ".$this->SEQ_EQUIPE_TI;
		if($this->NUM_MATRICULA_RECURSO != ""){
			$sql .= " and a.NUM_MATRICULA_RECURSO = ".$this->NUM_MATRICULA_RECURSO." ";
		}
		$sql .= " ORDER by b.NOM_COLABORADOR ";
		//prin_r($sql);
		$result = $this->database->query($sql);
		$aItemOption = Array();
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row["num_matricula_recurso"], $this->iif($vSelected == $row["num_matricula_recurso"],"Selected", ""), $row["nom_colaborador"]);
			$cont++;
		}
		return $aItemOption;
	}

	function iif($Condicao, $Sim, $Nao){
		if ($Condicao){
			return $Sim;
		} else{
			return $Nao;
		}
	}

} // class : end
?>