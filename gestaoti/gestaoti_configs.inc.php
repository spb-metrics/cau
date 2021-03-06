<?php
/*
Copyright 2011 da EMBRATUR
�Este arquivo � parte do programa CAU - Central de Atendimento ao Usu�rio
�O CAU � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela 
 Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
�Este programa � distribu�do na esperan�a que possa ser� �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer� 
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
�Observe no diret�rio gestaoti/install/ a c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licensa_uso.htm". 
 Se preferir acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software 
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA� 02110-1301, USA
*/

/* Configura��es de banco de dados */
global $gestaoti_settings;
$gestaoti_settings['db_postgres_host']='localhost';
$gestaoti_settings['db_postgres_port']='5432';
$gestaoti_settings['db_postgres_name']='gestaoti';
$gestaoti_settings['db_postgres_user']='gestaoti';
$gestaoti_settings['db_postgres_pass']='gestaoti';
$gestaoti_settings['db_postgres_enconding']='LATIN1';

/* Configura��es de ambiente */
date_default_timezone_set('America/Sao_Paulo');
$gestaoti_settings['debug_mode'] = false; // true = exibe erros | false = n�o exibe erros

#############################
#     N�O MUDE ABAIXO       #
#############################
if ($gestaoti_settings['debug_mode']){
    //error_reporting(E_ALL ^ E_NOTICE);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}
?>
