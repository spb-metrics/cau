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
* Nome da Classe:	motivo_cancelamento
* Data de criação:	11.11.2009
* Nome do Arquivo:	D:\Tiago\Pessoal\pages\gestaoti/GeraPHP/include/PHP/class/class.motivo_cancelamento.php
* Nome da tabela:	janela_mudanca
* -------------------------------------------------------
*/
include_once("include/PHP/class/class.database.postgres.php");

// **********************
// DECLARAÇÃO DA CLASSE
// **********************
class Exportacao{	 

	// ***********************
	// DECLARAÇÃO DE ATRIBUTOS
	// ***********************		 
	 
	var $database; // Instance of class database
	var $error; // Descrição de erro ao efetuar ação no banco de dados

	// **********************
	// CONSTRUCTOR METHOD
	// **********************
	public function  Exportacao(){
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
	 	   
	// **********************
	// SETTER METHODS
	// **********************
	function setrowCount($val){
		$this->rowCount = $val;
	}

	function setvQtdRegistros($val){
		$this->vQtdRegistros = $val;
	}
	  

	// ****************************
	// SELECT METHOD COM PARÂMETROS
	// ****************************
	function getRows($SQL){		 
		$this->database->query($SQL);
	}
 

} // class : end
?>