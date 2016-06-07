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
* CLASSNAME:        empregados
* -------------------------------------------------------
*
*/
if(strpos($_SERVER["SCRIPT_FILENAME"], "cau/")){
	include_once("../gestaoti/include/PHP/class/class.database.postgres.php");
}else{
	include_once("include/PHP/class/class.database.postgres.php");
}
// **********************
// CLASS DECLARATION
// **********************
class empregados{ // class : begin

	// **********************
	// ATTRIBUTE DECLARATION
	// **********************
	var $NUM_MATRICULA_RECURSO;   // KEY ATTR. WITH AUTOINCREMENT
	var $rowCount; // Quantidade de registros para paginação de resultados
	var $vQtdRegistros; // Quantidade de registros por página

	var $NOM_LOGIN_REDE;   // (normal Attribute)
	var $NOME;   // (normal Attribute)
	var $NOME_ABREVIADO;   // (normal Attribute)
	var $NOME_GUERRA;   // (normal Attribute)
	var $DEP_ID;   // (normal Attribute)
	var $DEP_SIGLA;   // (normal Attribute)
	var $UOR_ID;   // (normal Attribute)
	var $UOR_SIGLA;   // (normal Attribute)
	var $COOR_ID;   // (normal Attribute)
	var $COOR_SIGLA;   // (normal Attribute)
	var $DES_EMAIL;   // (normal Attribute)
	var $NUM_DDD;   // (normal Attribute)
	var $NUM_TELEFONE;   // (normal Attribute)
	var $NUM_VOIP;   // (normal Attribute)
	var $DES_STATUS;   // (normal Attribute)
        
        var $DES_SENHA;
        var $SEQ_UNIDADE_ORGANIZACIONAL;
        var $SEQ_PESSOA_SUPERIOR_HIERARQUICO;
        var $SEQ_TIPO_FUNCAO_ADMINISTRATIVA;
        var $FLG_CADASTRO_ATUALZIADO;

	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************

	function empregados(){
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

	function getDEP_ID(){
		return $this->DEP_ID;
	}
	function getDEP_SIGLA(){
		return $this->DEP_SIGLA;
	}
	
	function getUOR_ID(){
		return $this->UOR_ID;
	}
	function getUOR_SIGLA(){
		return $this->UOR_SIGLA;
	}
	
	function getCOOR_ID(){
		return $this->COOR_ID;
	}
	function getCOOR_SIGLA(){
		return $this->COOR_SIGLA;
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

	function getDES_STATUS(){
		return $this->DES_STATUS;
	}
        
        function getDES_SENHA(){
		return $this->DES_SENHA;
	}
        
        function getSEQ_UNIDADE_ORGANIZACIONAL(){
		return $this->SEQ_UNIDADE_ORGANIZACIONAL;
	}
        
        function getSEQ_TIPO_FUNCAO_ADMINISTRATIVA(){
		return $this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA;
	}
        
        function getSEQ_PESSOA_SUPERIOR_HIERARQUICO(){
		return $this->SEQ_PESSOA_SUPERIOR_HIERARQUICO;
	}
        
        function getFLG_CADASTRO_ATUALZIADO(){
		return $this->FLG_CADASTRO_ATUALZIADO;
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

	function setDES_STATUS($val){
		$this->DES_STATUS =  $val;
	}
                
	function setDES_SENHA($val){
		$this->DES_SENHA =  $val;
	}
        
	function setSEQ_UNIDADE_ORGANIZACIONAL($val){
		$this->SEQ_UNIDADE_ORGANIZACIONAL =  $val;
	}
        
	function setSEQ_PESSOA_SUPERIOR_HIERARQUICO($val){
		$this->SEQ_PESSOA_SUPERIOR_HIERARQUICO =  $val;
	}
        
	function setSEQ_TIPO_FUNCAO_ADMINISTRATIVA($val){
		$this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA =  $val;
	}
        
	function setFLG_CADASTRO_ATUALZIADO($val){
		$this->FLG_CADASTRO_ATUALZIADO =  $val;
	}       
        
	// **********************
	// SELECT METHOD / LOAD
	// **********************
	function select($id){
		if($id != ""){
			$sql = "SELECT  num_matricula_recurso, nom_login_rede, nome, nome_abreviado, nome_guerra, 
                                        dep_sigla, uor_sigla, des_email, num_ddd, num_telefone, num_voip, des_atatus as des_status,
                                        des_senha, seq_tipo_funcao_administrativa, seq_unidade_organizacional
                                FROM gestaoti.viw_age_empregados
                                where num_matricula_recurso = '$id'";
			//print $sql;
			$result = $this->database->query($sql);
			$result = $this->database->result;
			if(!$result) $this->error = $this->database->error;
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
			$this->DES_STATUS = $row->des_status;
                        $this->DES_SENHA = $row->des_senha;
                        $this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA = $row->seq_tipo_funcao_administrativa;
                        $this->SEQ_UNIDADE_ORGANIZACIONAL = $row->seq_unidade_organizacional;
                        //$this->FLG_CADASTRO_ATUALIZADO = $row->flg_cadastro_atualizado;
		}
	}	
	
        function login($id){
		$sql = "SELECT num_matricula_recurso, nom_login_rede, nome, nome_abreviado, nome_guerra, 
                               dep_sigla, uor_sigla, des_email, num_ddd, num_telefone, 
                               num_voip, des_atatus as des_status, des_senha, seq_tipo_funcao_administrativa
                        FROM gestaoti.viw_age_empregados
                        where lower(NOM_LOGIN_REDE) = '".mb_strtolower($id,'LATIN1')."'";
		//print $sql;
		$result = $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = $this->database->error;
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
		$this->DES_STATUS = $row->des_status;
                $this->DES_SENHA = $row->des_senha;
                $this->FLG_CADASTRO_ATUALIZADO = $row->flg_cadastro_atualizado;
                $this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA = $row->seq_tipo_funcao_administrativa;
	}

	function GetNumeroMatricula($id){
		$sql = "SELECT seq_pessoa as num_matricula_recurso
			FROM gestaoti.pessoa
			where trim(upper(NOM_LOGIN_REDE)) = '".trim(mb_strtoupper($id,'LATIN1'))."'";
		//print $sql;
                $result =  $this->database->query($sql);
		$result = $this->database->result;
		if($this->database->rows > 0){
			if(!$result) $this->error = $this->database->error;
			$row = pg_fetch_object($result);
			return $row->num_matricula_recurso;
		}else{
			return null;
		}
	}
        
	function GetNomLoginRedeMatricula($id){
		if($id != ""){
			$sql = "select NOM_LOGIN_REDE
				FROM gestaoti.pessoa
				where seq_pessoa = '$id'";
			$result =  $this->database->query($sql);
			$result = $this->database->result;
			if(!$result) $this->error = $this->database->error;
			$row = pg_fetch_object($result);
			return $row->nom_login_rede;
		}else return "";

	}
        
        function GetNomeEmpregadoByEmail($id){
		if($id != ""){
			$sql = "select nome
				FROM gestaoti.pessoa
				where trim(upper(DES_EMAIL)) = '".trim(mb_strtoupper($id,'LATIN1'))."'";
			$result =  $this->database->query($sql);
			$result = $this->database->result;
			if(!$result) $this->error = $this->database->error;
			$row = pg_fetch_object($result);
			return $row->nome;
		}else{
			return "";
		}
	}
        
	function GetNomeEmpregado($id){
		if($id != ""){
			$sql = "select nome
				FROM gestaoti.pessoa
				where seq_pessoa = '$id'";
			$result =  $this->database->query($sql);
			$result = $this->database->result;
			if(!$result) $this->error = $this->database->error;
			$row = pg_fetch_object($result);
			return $row->nome;
		}else{
			return "";
		}
	}

	function GetNomeEmail($vNumMatricula){
		$sql = "select nome as NOM_COLABORADOR,
                               seq_pessoa as NUM_MATRICULA_RECURSO,
			       des_email as DSC_EMAIL
			from gestaoti.pessoa
			where seq_pessoa = $vNumMatricula";
		//print $sql;
		$result = $this->database->query($sql);
		$result = $this->database->result;
		if(!$result) $this->error = $this->database->error;
		$row = pg_fetch_object($result);
		$this->NOME = $row->nom_colaborador;
		$this->DES_EMAIL = $row->dsc_email;
	}
        
        // ****************************
        // Funções sobre a posição hierárquica da pessoa
        // ****************************
	function SelectChefeByIDSubordinado($id){
		if($id != ""){ 
			
			$sql =" SELECT 	 
                                        b.SEQ_PESSOA as num_matricula_recurso, 
                                        b.NOME, b.nom_login_rede, b.des_email
                                FROM 
                                        gestaoti.pessoa a, gestaoti.pessoa b
                                WHERE
                                        a.seq_pessoa_superior_hierarquico = b.seq_pessoa
                                        a.seq_pessoa = '$id'";
			
			//print $sql;
			
			$result = $this->database->query($sql);
			$result = $this->database->result;
			if(!$result) $this->error = $this->database->error;
			$row = pg_fetch_object($result);
			
			$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
			$this->NOM_LOGIN_REDE = $row->nom_login_rede;
			$this->NOME = $row->nome;			 
			$this->DES_EMAIL = $row->des_email; 
		}
	}
	
	function SelectChefeByLoginSubordinado($login){
		if($id != ""){ 
			
			$sql =" SELECT 	 
                                        b.SEQ_PESSOA as num_matricula_recurso, 
                                        b.NOME, b.nom_login_rede, b.des_email
                                FROM 
                                        gestaoti.pessoa a, gestaoti.pessoa b
                                WHERE
                                        a.seq_pessoa_superior_hierarquico = b.seq_pessoa
                                        upper(a.nom_login_rede) = '".mb_strtoupper($login, 'LATIN1')."'";
			
			//print $sql;
			
			$result = $this->database->query($sql);
			$result = $this->database->result;
			if(!$result) $this->error = $this->database->error;
			$row = pg_fetch_object($result);
			
			$this->NUM_MATRICULA_RECURSO = $row->num_matricula_recurso;
			$this->NOM_LOGIN_REDE = $row->nom_login_rede;
			$this->NOME = $row->nome;			 
			$this->DES_EMAIL = $row->des_email; 
		}
	}
	
	function GetFuncaoAdministrativaByLogin($login){
		
		$sql = "select
                                a.seq_pessoa as num_matricula_recurso, 
                                a.nome, a.nom_login_rede, a.des_email,
                                a.seq_tipo_funcao_administrativa as idfuncaoadministrativa,
                                b.nom_tipo_funcao_administrativa as dsfuncaoadministrativa,
                                c.nom_unidade_organizacional as DEP_SIGLA,
                                d.nom_unidade_organizacional as UOR_SIGLA 
                        from
                                gestaoti.pessoa a, gestaoti.tipo_funcao_administrativa b,
                                gestaoti.unidade_organizacional c LEFT OUTER JOIN gestaoti.unidade_organizacional d
                                ON c.seq_unidade_organizacional_pai = d.seq_unidade_organizacional
                        where
                                a.seq_tipo_funcao_administrativa = b.seq_tipo_funcao_administrativa and
                                a.seq_unidade_organizacional = c.seq_unidade_organizacional and
                                a.nom_login_rede = '".mb_strtoupper($login, 'LATIN1')."' "; 

		$this->database->query($sql);
 		$FUNCOES =   Array();
		if($this->database->rows > 0){
			 $i = 0;
			 while ($row = pg_fetch_array($this->database->result)){
			 	$FUNCOES[$i] = $row["idfuncaoadministrativa"]; 
				$i++;
			 }
			 return $FUNCOES;
		}else{
 			return null;
 		}
	}
	
	function GetFuncaoAdministrativaByID($id){
		
		$sql = "select
                                a.seq_pessoa as num_matricula_recurso, 
                                a.nome, a.nom_login_rede, a.des_email,
                                a.seq_tipo_funcao_administrativa as idfuncaoadministrativa,
                                b.nom_tipo_funcao_administrativa as dsfuncaoadministrativa,
                                c.nom_unidade_organizacional as DEP_SIGLA,
                                d.nom_unidade_organizacional as UOR_SIGLA 
                        from
                                gestaoti.pessoa a, gestaoti.tipo_funcao_administrativa b,
                                gestaoti.unidade_organizacional c LEFT OUTER JOIN gestaoti.unidade_organizacional d
                                ON c.seq_unidade_organizacional_pai = d.seq_unidade_organizacional
                        where
                                a.seq_tipo_funcao_administrativa = b.seq_tipo_funcao_administrativa and
                                a.seq_unidade_organizacional = c.seq_unidade_organizacional and
                                a.seq_pessoa = $id "; 
         
 		
		$this->database->query($sql);
 		$FUNCOES =   Array();
		if($this->database->rows > 0){
			 $i = 0;
			 while ($row = pg_fetch_array($this->database->result)){
			 	$FUNCOES[$i] = $row["idfuncaoadministrativa"]; 
				$i++;
			 }
			 return $FUNCOES;
		}else{
 			return null;
 		}
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectParam($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select * ";
		$sqlCorpo  = " FROM (
                                    select
                                            seq_pessoa as NUM_MATRICULA_RECURSO,
                                            a.seq_pessoa,
                                            nom_login_rede as NOM_LOGIN_REDE,
                                            NULL as FLG_TIPO_COLABORADOR,
                                            NOME, NOME_ABREVIADO, NOME_GUERRA, DES_EMAIL,
                                            NUM_DDD, NUM_VOIP, NUM_TELEFONE, DES_STATUS,
                                            a.seq_unidade_organizacional,
                                            b.nom_unidade_organizacional as DEP_SIGLA,
                                            c.nom_unidade_organizacional as UOR_SIGLA 
                                    from   
                                            gestaoti.pessoa a,
                                            gestaoti.unidade_organizacional b LEFT OUTER JOIN gestaoti.unidade_organizacional c
                                            ON b.seq_unidade_organizacional_pai = c.seq_unidade_organizacional
                                    where
                                            a.seq_unidade_organizacional = b.seq_unidade_organizacional
                                    ) a
                            WHERE 1=1";
		if($this->NUM_MATRICULA_RECURSO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_RECURSO = $this->NUM_MATRICULA_RECURSO ";
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
		if($this->SEQ_UNIDADE_ORGANIZACIONAL != ""){
			$sqlCorpo .= "  and SEQ_UNIDADE_ORGANIZACIONAL = $this->SEQ_UNIDADE_ORGANIZACIONAL ";
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
		if($this->DES_STATUS != ""){
			$sqlCorpo .= "  and upper(DES_STATUS) like '%".mb_strtoupper($this->DES_STATUS,'LATIN1')."%'  ";
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

		//print "<br><br>".$sqlSelect . $sqlCorpo . $sqlOrder;
		$this->database->query($sqlSelect . $sqlCorpo . $sqlOrder);
	}
	
	function SelectAprvadoresByCoordenacao($id){
		if($id != ""){ 
                    require_once '../gestaoti/include/PHP/class/class.parametro.php';
                    //$parametro = new parametro();
                    //$FUNCOES_ADM_ESPECIAL = $parametro->GetValorParametro("FUNCOES_ADM_TRANSPORTE_ESPECIAL");		
		 
			
			$sql ="
                                SELECT    
                                        a.seq_pessoa as NUM_MATRICULA_RECURSO,
                                        a.seq_pessoa,
                                        a.nom_login_rede as NOM_LOGIN_REDE,
                                        a.NOME, a.NOME_ABREVIADO, a.NOME_GUERRA, a.DES_EMAIL, 
                                        a.seq_tipo_funcao_administrativa as idfuncaoadministrativa,
                                        c.nom_tipo_funcao_administrativa as dsfuncaoadministrativa,
                                        d.seq_unidade_organizacional as DEP_SIGLA,
                                        e.sgl_unidade_organizacional as UOR_SIGLA, 
                                        e.seq_unidade_organizacional as UOR_ID
                                from
                                        gestaoti.pessoa a, 
                                        gestaoti.responsavel_unidade_organizacional b,
                                        gestaoti.tipo_funcao_administrativa c,
                                        gestaoti.unidade_organizacional d LEFT OUTER JOIN gestaoti.unidade_organizacional e
                                        ON d.seq_unidade_organizacional_pai = e.seq_unidade_organizacional
                                where
                                        a.seq_pessoa = b.seq_pessoa and
                                        a.seq_tipo_funcao_administrativa = c.seq_tipo_funcao_administrativa and
                                        b.seq_unidade_organizacional = d.seq_unidade_organizacional and
                                        b.seq_unidade_organizacional = '$id' 
			";
			 
			$this->database->query($sql);
		}
	}
	
	function SelectAprvadoresByUnidade($id){
            $this->SelectAprvadoresByCoordenacao($id);
            /*
            if($id != ""){ 
                    require_once '../gestaoti/include/PHP/class/class.parametro.php';
                    //$parametro = new parametro();
                    //$FUNCOES_ADM_ESPECIAL = $parametro->GetValorParametro("FUNCOES_ADM_TRANSPORTE_ESPECIAL");		
		 
			
			$sql ="
			 SELECT    
                                        a.seq_pessoa as NUM_MATRICULA_RECURSO,
                                        a.nom_login_rede as NOM_LOGIN_REDE,
                                        a.NOME, a.NOME_ABREVIADO, a.NOME_GUERRA, a.DES_EMAIL, 
                                        a.seq_tipo_funcao_administrativa as idfuncaoadministrativa,
                                        c.nom_tipo_funcao_administrativa as dsfuncaoadministrativa,
                                        d.seq_unidade_organizacional as DEP_SIGLA,
                                        e.sgl_unidade_organizacional as UOR_SIGLA, 
                                        e.seq_unidade_organizacional as UOR_ID
                                from
                                        gestaoti.pessoa a, 
                                        gestaoti.responsavel_unidade_organizacional b,
                                        gestaoti.tipo_funcao_administrativa c,
                                        gestaoti.unidade_organizacional d LEFT OUTER JOIN gestaoti.unidade_organizacional e
                                        ON d.seq_unidade_organizacional_pai = e.seq_unidade_organizacional
                                where
                                        a.seq_pessoa = b.seq_pessoa and
                                        a.seq_tipo_funcao_administrativa = c.seq_tipo_funcao_administrativa and
                                        b.seq_unidade_organizacional = d.seq_unidade_organizacional and
                                        b.seq_unidade_organizacional = '$id' 
			
			";
			 
			$this->database->query($sql);
		}
	
            */
        }
		
	function SelectCoordenacaoUnidadeSobMnhaResponsabilidade($id){
		if($id != ""){ 
		
			$sql ="  
				SELECT    
                                        a.seq_pessoa as NUM_MATRICULA_RECURSO,
                                        a.nom_login_rede as NOM_LOGIN_REDE,
                                        a.NOME, a.NOME_ABREVIADO, a.NOME_GUERRA, a.DES_EMAIL, 
                                        a.seq_tipo_funcao_administrativa as idfuncaoadministrativa,
                                        c.nom_tipo_funcao_administrativa as dsfuncaoadministrativa,
                                        d.seq_unidade_organizacional as COOR_ID,
                                        e.sgl_unidade_organizacional as UOR_SIGLA, 
                                        e.seq_unidade_organizacional as UOR_ID,
                                        NULL as DEP_SIGLA
                                from
                                        gestaoti.pessoa a, 
                                        gestaoti.responsavel_unidade_organizacional b,
                                        gestaoti.tipo_funcao_administrativa c,
                                        gestaoti.unidade_organizacional d LEFT OUTER JOIN gestaoti.unidade_organizacional e
                                        ON d.seq_unidade_organizacional_pai = e.seq_unidade_organizacional
                                where
                                        a.seq_pessoa = b.seq_pessoa and
                                        a.seq_tipo_funcao_administrativa = c.seq_tipo_funcao_administrativa and
                                        b.seq_unidade_organizacional = d.seq_unidade_organizacional and
                                        a.seq_pessoa = '$id' ";
			 
			$this->database->query($sql);
		}
	}

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function selectRecursoTi($orderBy = 1, $vNumPagina = "", $vQtdRegistros = "20"){
		$this->setvQtdRegistros($vQtdRegistros);

		$sqlSelect = " select * ";
		$sqlCorpo  = " FROM ( select
                                                a.seq_pessoa as NUM_MATRICULA_RECURSO,
                                                NOM_LOGIN_REDE,
                                                NULL as FLG_TIPO_COLABORADOR,
                                                NOME,
                                                NOME_ABREVIADO,
                                                NOME_GUERRA,
                                                DES_EMAIL,
                                                NUM_DDD,
                                                NUM_TELEFONE,
                                                NUM_VOIP,
                                                DES_STATUS as DES_STATUS,
                                                b.sgl_unidade_organizacional as DEP_SIGLA,
                                                c.sgl_unidade_organizacional as UOR_SIGLA 
                                        from   
                                                gestaoti.pessoa a,
                                                gestaoti.unidade_organizacional b LEFT OUTER JOIN gestaoti.unidade_organizacional c
                                                ON b.seq_unidade_organizacional_pai = c.seq_unidade_organizacional
                                        where
                                        a.seq_unidade_organizacional = b.seq_unidade_organizacional) a, gestaoti.recurso_ti b
                              WHERE a.NUM_MATRICULA_RECURSO = b.NUM_MATRICULA_RECURSO
						";
		if($this->NUM_MATRICULA_RECURSO != ""){
			$sqlCorpo .= "  and NUM_MATRICULA_RECURSO = $this->NUM_MATRICULA_RECURSO ";
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
		if($this->DES_STATUS != ""){
			$sqlCorpo .= "  and upper(DES_STATUS) like '%".mb_strtoupper($this->DES_STATUS,'LATIN1')."%'  ";
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
        
        function combo($OrderBy, $vSelected=""){
		$aItemOption = Array();
		$this->FLG_EXIBE_IMPROCEDENTE = $this->FLG_EXIBE_IMPROCEDENTE==""?0:$this->FLG_EXIBE_IMPROCEDENTE;
		$this->selectParam($OrderBy);
		$cont = 0;
		while ($row = pg_fetch_array($this->database->result)){
			$aItemOption[$cont] = array($row[0], $vSelected == $row[0]?"Selected":"", $row["nome"]);
			$cont++;
		}
		return $aItemOption;
	}
        
        // **********************
	// DELETE
	// **********************
	function delete($id){
		$sql = "DELETE FROM gestaoti.pessoa WHERE SEQ_PESSOA = $id";
		$result = $this->database->query($sql);
	}

	// **********************
	// INSERT
	// **********************
	function insert(){
		$this->SEQ_PESSOA = $this->database->GetSequenceValue("gestaoti.SEQ_PESSOA"); // clear key for autoincrement

		$sql = "INSERT INTO gestaoti.pessoa ( seq_pessoa,
                                                      nom_login_rede,
                                                      nome,
                                                      nome_abreviado,
                                                      nome_guerra,
                                                      des_email,
                                                      num_ddd,
                                                      num_telefone,
                                                      num_voip,
                                                      des_status,
                                                      des_senha,
                                                      seq_unidade_organizacional,
                                                      seq_tipo_funcao_administrativa)
                        VALUES (".$this->SEQ_PESSOA.",
                                ".$this->database->iif($this->NOM_LOGIN_REDE=="", "NULL", "'".$this->NOM_LOGIN_REDE."'").",
                                ".$this->database->iif($this->NOME=="", "NULL", "'".$this->NOME."'").",
                                ".$this->database->iif($this->NOME_ABREVIADO=="", "NULL", "'".$this->NOME_ABREVIADO."'").",
                                ".$this->database->iif($this->NOME_GUERRA=="", "NULL", "'".$this->NOME_GUERRA."'").",
                                ".$this->database->iif($this->DES_EMAIL=="", "NULL", "'".$this->DES_EMAIL."'").",
                                ".$this->database->iif($this->NUM_DDD=="", "NULL", "'".$this->NUM_DDD."'").",
                                ".$this->database->iif($this->NUM_TELEFONE=="", "NULL", "'".$this->NUM_TELEFONE."'").",
                                ".$this->database->iif($this->NUM_VOIP=="", "NULL", "'".$this->NUM_VOIP."'").",
                                ".$this->database->iif($this->DES_STATUS=="", "NULL", "'".$this->DES_STATUS."'").",
                                ".$this->database->iif($this->DES_SENHA=="", "NULL", "'".$this->DES_SENHA."'").",
                                ".$this->database->iif($this->SEQ_UNIDADE_ORGANIZACIONAL=="", "NULL", "'".$this->SEQ_UNIDADE_ORGANIZACIONAL."'").",
                                ".$this->database->iif($this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA=="", "NULL", "'".$this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA."'")
                              .")";
                $result = $this->database->query($sql);
	}

	// **********************
	// UPDATE
	// **********************
	function update($id){
		$sql = " UPDATE gestaoti.pessoa 
                         SET  
                            NOM_LOGIN_REDE = ".$this->database->iif($this->NOM_LOGIN_REDE=="", "NULL", "'".$this->NOM_LOGIN_REDE."'").",
                            NOME = ".$this->database->iif($this->NOME=="", "NULL", "'".$this->NOME."'").",
                            NOME_ABREVIADO = ".$this->database->iif($this->NOME_ABREVIADO=="", "NULL", "'".$this->NOME_ABREVIADO."'").",
                            NOME_GUERRA = ".$this->database->iif($this->NOME_GUERRA=="", "NULL", "'".$this->NOME_GUERRA."'").",
                            DES_EMAIL = ".$this->database->iif($this->DES_EMAIL=="", "NULL", "'".$this->DES_EMAIL."'").",
                            NUM_DDD = ".$this->database->iif($this->NUM_DDD=="", "NULL", "'".$this->NUM_DDD."'").",
                            NUM_TELEFONE = ".$this->database->iif($this->NUM_TELEFONE=="", "NULL", "'".$this->NUM_TELEFONE."'").",
                            NUM_VOIP = ".$this->database->iif($this->NUM_VOIP=="", "NULL", "'".$this->NUM_VOIP."'").",
                            DES_STATUS = ".$this->database->iif($this->DES_STATUS=="", "NULL", "'".$this->DES_STATUS."'").",
                            SEQ_UNIDADE_ORGANIZACIONAL = ".$this->database->iif($this->SEQ_UNIDADE_ORGANIZACIONAL=="", "NULL", "'".$this->SEQ_UNIDADE_ORGANIZACIONAL."'").",
                            SEQ_TIPO_FUNCAO_ADMINISTRATIVA = ".$this->database->iif($this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA=="", "NULL", "'".$this->SEQ_TIPO_FUNCAO_ADMINISTRATIVA."'")."
                         WHERE SEQ_PESSOA = $id ";
		$result = $this->database->query($sql);
	}
        
        // **********************
	// UPDATE
	// **********************
	function alterarSenha($id, $v_DES_SENHA){
		$sql = " UPDATE gestaoti.pessoa 
                         SET  
                            DES_SENHA = ".$this->database->iif($v_DES_SENHA=="", "NULL", "'".$v_DES_SENHA."'")."
                         WHERE SEQ_PESSOA = $id ";
		$result = $this->database->query($sql);
	}

} // class : end
?>