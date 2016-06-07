<?php
define('vPathPadrao','../gestaoti/');
define('titulo','Instalação do Gestão TI');
define('tituloCabecalho','Bem vindo ao Gestão TI');
define('labelTopo','Instalação do Sistema Gestão TI');
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
                Obrigado por baixar o Gestão TI. <br><br>
                Esta ferramenta irá ajudá-lo a instalar e configurar Gestão TI em seu servidor.
                <br>
                <br>
                    <font size="2" color="red">
                        POR FAVOR, não deixe de ler instruções do manual de instação do Gestão TI, disponível 
                        no <a href="http://www.softwarepublico.gov.br/">Portal do Software Público Brasileiro</a> antes de executar 
                        este script de instalação.
                    </font>
                    
                    
                    <p align="center"><input type="button" onclick="location.href='install.php'" value="Próximo passo >" id="campo_texto" /></p>

                    
    <div id="border-bottom">
    </div>
    <div id="footer">
        <p class="copyright">
        </p>
    </div><br><br><br><br><br><br><br><br><br>Para maiores informações acesse nossa comunidade no <a href="http://www.softwarepublico.gov.br/" class="smaller" target="_blank">Portal do Software Público Brasileiro </a>
                    
</form>
</body>
</html>