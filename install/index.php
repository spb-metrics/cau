<?php
define('vPathPadrao','../gestaoti/');
define('titulo','Instala��o do Gest�o TI');
define('tituloCabecalho','Bem vindo ao Gest�o TI');
define('labelTopo','Instala��o do Sistema Gest�o TI');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt">
<head>
    <title><?=titulo?></title>
    <meta http-equiv="Content-Type" content="text/html; charset='iso-8859-1'" />

    <link href="<?=vPathPadrao?>include/CSS/CascadeStyleSheet.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" language="JavaScript1.2" src="<?=vPathPadrao?>include/JS/scripts.js"></script>

</head>
<body bottommargin="0" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
    <form name="form" action="" method="post">
    <div id="border-top" class="h_green">
        <div>
            <div>
                    <span class="title"><?=labelTopo?></span>
            </div>
        </div>
    </div>

    <div id="submenu_cabecalho">
      <h1><?=tituloCabecalho?></h1>
    </div>

        <div id="conteudo">
            <br>
                Obrigado por baixar o Gest�o TI. <br><br>
                Esta ferramenta ir� ajud�-lo a instalar e configurar Gest�o TI em seu servidor.
                <br>
                <br>
                    <font size="2" color="red">
                        POR FAVOR, n�o deixe de ler instru��es do manual de insta��o do Gest�o TI, dispon�vel 
                        no <a href="http://www.softwarepublico.gov.br/">Portal do Software P�blico Brasileiro</a> antes de executar 
                        este script de instala��o.
                    </font>
                    
                    
                    <p align="center"><input type="button" onclick="location.href='install.php'" value="Pr�ximo passo >" id="campo_texto" /></p>

                    
    <div id="border-bottom">
    </div>
    <div id="footer">
        <p class="copyright">
        </p>
    </div><br><br><br><br><br><br><br><br><br>Para maiores informa��es acesse nossa comunidade no <a href="http://www.softwarepublico.gov.br/" class="smaller" target="_blank">Portal do Software P�blico Brasileiro </a>
                    
</form>
</body>
</html>