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
if($_SESSION["NUM_MATRICULA_RECURSO"] != ""){

    //============= PARA OTIMIZAR =============	
    if (!isset($_SESSION['MENU_SISTEMA'])){

            $menu = new menu();
            $menu->setseq_perfil_acesso($_SESSION["SEQ_PERFIL_ACESSO"]);
            $menu->selectParamMontaMenu();
    //	$menu->setSEQ_MENU_ACESSO_PAI();
    //	$menu->selectParam("NUM_PRIORIDADE");
            $mSel = $menu->database->rows;

            //$MENUS = array();
            $_SESSION['MENU_SISTEMA'] = array();

            if($menu->database->rows > 0){
                    $Nivel1 = null;
                    $Nivel2 = null;
                    $Nivel3 = null;
                    $Nivel4 = null;
                    $Nivel5 = null;

                    // Carregar os menus principais
                    while ($row = pg_fetch_array($menu->database->result)) { // Nível 1
                            //============= PARA OTIMIZAR =============
                            $Nivel1= new menu();
                            $Nivel1->SEQ_MENU_ACESSO = $row[0]; 
                            $Nivel1->DSC_MENU_ACESSO = $row["dsc_menu_acesso"];   
                            $Nivel1->NOM_ARQUIVO = $row["nom_arquivo"];   
                            //$Nivel1->NUM_PRIORIDADE;   
                            $Nivel1->NOM_ARQUIVO_IMAGEM_ESCURO = $row["nom_arquivo_imagem_escuro"];   
                            $Nivel1->NOM_ARQUIVO_IMAGEM_CLARO = $row["nom_arquivo_imagem_claro"]; 
                            //============= PARA OTIMIZAR =============
                            // Carregar os submenus
                            $sub_menu = new menu();
                            $sub_menu->setSEQ_MENU_ACESSO_PAI($row[0]);
                            $sub_menu->setseq_perfil_acesso($_SESSION["SEQ_PERFIL_ACESSO"]);
                            $sub_menu->selectParamMontaMenu();
                            while ($row_sub_item = pg_fetch_array($sub_menu->database->result)) { // Nível 2
                                    //============= PARA OTIMIZAR =============
                                    $Nivel2= new menu();
                                    $Nivel2->SEQ_MENU_ACESSO = $row_sub_item[0]; 
                                    $Nivel2->DSC_MENU_ACESSO = $row_sub_item["dsc_menu_acesso"];   
                                    $Nivel2->NOM_ARQUIVO = $row_sub_item["nom_arquivo"];
                                    //$MenuNivel1->NUM_PRIORIDADE; 
                                    $Nivel2->NOM_ARQUIVO_IMAGEM_ESCURO = $row_sub_item["nom_arquivo_imagem_escuro"];   
                                    $Nivel2->NOM_ARQUIVO_IMAGEM_CLARO = $row_sub_item["nom_arquivo_imagem_claro"];				
                                    $Nivel2->SEQ_MENU_ACESSO_PAI = $Nivel1->SEQ_MENU_ACESSO;
                                    $Nivel1->addItemMenu($Nivel2);
                                    //============= PARA OTIMIZAR =============
                                    $sub_sub_menu = new menu();
                                    $sub_sub_menu->setseq_perfil_acesso($_SESSION["SEQ_PERFIL_ACESSO"]);
                                    $sub_sub_menu->setSEQ_MENU_ACESSO_PAI($row_sub_item[0]);
                                    $sub_sub_menu->selectParamMontaMenu();
                                    while ($row_sub_item_sub = pg_fetch_array($sub_sub_menu->database->result)) { // Nível 3
                                            //============= PARA OTIMIZAR =============
                                            $Nivel3= new menu();
                                            $Nivel3->SEQ_MENU_ACESSO = $row_sub_item_sub[0]; 
                                            $Nivel3->DSC_MENU_ACESSO = $row_sub_item_sub["dsc_menu_acesso"];   
                                            $Nivel3->NOM_ARQUIVO = $row_sub_item_sub["nom_arquivo"];
                                            //$Nivel3->NUM_PRIORIDADE; 
                                            $Nivel3->NOM_ARQUIVO_IMAGEM_ESCURO = $row_sub_item_sub["nom_arquivo_imagem_escuro"];   
                                            $Nivel3->NOM_ARQUIVO_IMAGEM_CLARO = $row_sub_item_sub["nom_arquivo_imagem_claro"];  
                                            $Nivel3->SEQ_MENU_ACESSO_PAI = $Nivel2->SEQ_MENU_ACESSO;
                                            $Nivel2->addItemMenu($Nivel3);
                                            //============= PARA OTIMIZAR =============
                                            // Carregar os submenus
                                            $sub_sub_sub_menu = new menu();
                                            $sub_sub_sub_menu->setSEQ_MENU_ACESSO_PAI($row_sub_item_sub[0]);
                                            $sub_sub_sub_menu->setseq_perfil_acesso($_SESSION["SEQ_PERFIL_ACESSO"]);
                                            $sub_sub_sub_menu->selectParamMontaMenu();
                                            while ($row_sub_item_sub1 = pg_fetch_array($sub_sub_sub_menu->database->result)) { // Nível 4
                                                    //============= PARA OTIMIZAR =============
                                                    $Nivel4= new menu();
                                                    $Nivel4->SEQ_MENU_ACESSO = $row_sub_item_sub1[0]; 
                                                    $Nivel4->DSC_MENU_ACESSO = $row_sub_item_sub1["dsc_menu_acesso"];   
                                                    $Nivel4->NOM_ARQUIVO = $row_sub_item_sub1["nom_arquivo"];
                                                    //$Nivel3->NUM_PRIORIDADE; 
                                                    $Nivel4->NOM_ARQUIVO_IMAGEM_ESCURO = $row_sub_item_sub1["nom_arquivo_imagem_escuro"];   
                                                    $Nivel4->NOM_ARQUIVO_IMAGEM_CLARO = $row_sub_item_sub1["nom_arquivo_imagem_claro"];  
                                                    $Nivel4->SEQ_MENU_ACESSO_PAI = $Nivel3->SEQ_MENU_ACESSO;
                                                    $Nivel3->addItemMenu($Nivel4);
                                                    //============= PARA OTIMIZAR =============
                                                    $sub_sub_sub_sub_menu = new menu();
                                                    $sub_sub_sub_sub_menu->setseq_perfil_acesso($_SESSION["SEQ_PERFIL_ACESSO"]);
                                                    $sub_sub_sub_sub_menu->setSEQ_MENU_ACESSO_PAI($row_sub_item_sub1[0]);
                                                    $sub_sub_sub_sub_menu->selectParamMontaMenu();
                                                    while ($row_sub_item_sub_sub = pg_fetch_array($sub_sub_sub_sub_menu->database->result)) { // Nível 5
                                                            //============= PARA OTIMIZAR =============
                                                            $Nivel5= new menu();
                                                            $Nivel5->SEQ_MENU_ACESSO = $row_sub_item_sub_sub[0]; 
                                                            $Nivel5->DSC_MENU_ACESSO = $row_sub_item_sub_sub["dsc_menu_acesso"];   
                                                            $Nivel5->NOM_ARQUIVO = $row_sub_item_sub_sub["nom_arquivo"];
                                                            //$Nivel3->NUM_PRIORIDADE; 
                                                            $Nivel5->NOM_ARQUIVO_IMAGEM_ESCURO = $row_sub_item_sub_sub["nom_arquivo_imagem_escuro"];   
                                                            $Nivel5->NOM_ARQUIVO_IMAGEM_CLARO = $row_sub_item_sub_sub["nom_arquivo_imagem_claro"];  
                                                            $Nivel5->SEQ_MENU_ACESSO_PAI = $Nivel4->SEQ_MENU_ACESSO;
                                                            //$Nivel4->addItemMenu(serialize($Nivel5));
                                                            $Nivel4->addItemMenu($Nivel5);
                                                            //============= PARA OTIMIZAR =============
                                                    }
                                                    //============= PARA OTIMIZAR =============
                                                    //$Nivel3->addItemMenu(serialize($Nivel4));
                                                    //============= PARA OTIMIZAR =============
                                            }//FIM NIVEL 4
                                    }//FIM NIVEL 3
                                    //============= PARA OTIMIZAR =============
                                    //$Nivel2->addItemMenu(serialize($Nivel3));
                                    //============= PARA OTIMIZAR =============
                            }// FIM NIVEL 2
                            //============= PARA OTIMIZAR =============
                            //if($Nivel2 != null){
                                    //$Nivel1->addItemMenu(serialize($Nivel2));
                            //}
                            //============= PARA OTIMIZAR =============
                            $cont++;
                            //============= PARA OTIMIZAR =============
                            //$MENUS = array($Nivel1);
                            //$_SESSION['MENU_SISTEMA'][] = array( serialize($Nivel1));
                            $_SESSION['MENU_SISTEMA'][] = serialize($Nivel1);
                            //============= PARA OTIMIZAR =============
                    }//FIM NIVEL 1
            }
    }// FIM TESTE SESSAO MENU	

    require_once 'include/PHP/class/class.menu.php';
    //============= PARA OTIMIZAR =============

    $cont = 0;
    $mSel = count($_SESSION['MENU_SISTEMA']);
    if($mSel > 0){
        ?>
        <span class="preload1"></span>
        <span class="preload2"></span>
        <ul id="nav">
        <?
            // NIVEL 1
            for ($i1 = 0; $i1 < count($_SESSION['MENU_SISTEMA']); $i1++){
                    $Nivel1 = unserialize($_SESSION['MENU_SISTEMA'][$i1]);

                    ?>
                    <li class="top"><a href="<?=$Nivel1->NOM_ARQUIVO?>" class="top_link"><span><?=$Nivel1->DSC_MENU_ACESSO?></span></a>
                    <?
                    /*
                    ["<?=$Nivel1->DSC_MENU_ACESSO?>","<?=$Nivel1->NOM_ARQUIVO?>","<?=$Nivel1->NOM_ARQUIVO_IMAGEM_CLARO?>","<?=$Nivel1->NOM_ARQUIVO_IMAGEM_ESCURO?>","<?=$Nivel1->DSC_MENU_ACESSO?>"],
                    */
                    // NIVEL 2
                    if($Nivel1->ITENS_MENU != null){
                        ?>  
                        <ul class="sub">
                        <?

                            for ($i2 = 0; $i2 < count($Nivel1->ITENS_MENU); $i2++){		
                                    //$Nivel2 = unserialize($Nivel1->ITENS_MENU[$i2]);				
                                    $Nivel2 = $Nivel1->ITENS_MENU[$i2];
                                    if($Nivel2->ITENS_MENU == null){
                                        ?>
                                        <li><a href="<?=$Nivel2->NOM_ARQUIVO?>"><?=$Nivel2->DSC_MENU_ACESSO?></a>
                                        <?
                                        /*
                                         ["|<?=$Nivel2->DSC_MENU_ACESSO?>","<?=$Nivel2->NOM_ARQUIVO?>","<?=$Nivel2->NOM_ARQUIVO_IMAGEM_CLARO?>","<?=$Nivel2->NOM_ARQUIVO_IMAGEM_ESCURO?>","<?=$Nivel2->DSC_MENU_ACESSO?>"],
                                         */

                                    // NIVEL 3
                                    }elseif($Nivel2->ITENS_MENU != null){
                                        ?>
                                        <li><a href="<?=$Nivel2->NOM_ARQUIVO?>" class="fly"><?=$Nivel2->DSC_MENU_ACESSO?></a>
                                        <?
                                            ?>
                                            <ul>
                                            <?

                                            for ($i3 = 0; $i3 < count($Nivel2->ITENS_MENU); $i3++){		
                                                    //$Nivel3 = unserialize($Nivel2->ITENS_MENU[$i3]);				
                                                    $Nivel3 = $Nivel2->ITENS_MENU[$i3];
                                                    if($Nivel3->ITENS_MENU == null){
                                                        ?>
                                                        <li><a href="<?=$Nivel3->NOM_ARQUIVO?>"><?=$Nivel3->DSC_MENU_ACESSO?></a>
                                                        <?
                                                        /*
                                                         ["||<?=$Nivel3->DSC_MENU_ACESSO?>","<?=$Nivel3->NOM_ARQUIVO?>","<?=$Nivel3->NOM_ARQUIVO_IMAGEM_CLARO?>","<?=$Nivel3->NOM_ARQUIVO_IMAGEM_ESCURO?>","<?=$Nivel3->DSC_MENU_ACESSO?>"],
                                                         */
                                                    // NIVEL 4
                                                    }elseif($Nivel3->ITENS_MENU != null){
                                                        ?>
                                                        <li><a href="<?=$Nivel3->NOM_ARQUIVO?>" class="fly"><?=$Nivel3->DSC_MENU_ACESSO?></a>
                                                        <?
                                                            ?>
                                                            <ul>
                                                            <?

                                                            for ($i4 = 0; $i4 < count($Nivel3->ITENS_MENU); $i4++){	
                                                                    //$Nivel4 = unserialize($Nivel3->ITENS_MENU[$i4]);				
                                                                    $Nivel4 = $Nivel3->ITENS_MENU[$i4];
                                                                    if($Nivel4->ITENS_MENU == null){
                                                                        ?>
                                                                        <li><a href="<?=$Nivel4->NOM_ARQUIVO?>"><?=$Nivel4->DSC_MENU_ACESSO?></a>
                                                                        <?
                                                                        /*
                                                                         ["|||<?=$Nivel4->DSC_MENU_ACESSO?>","<?=$Nivel4->NOM_ARQUIVO?>","<?=$Nivel4->NOM_ARQUIVO_IMAGEM_CLARO?>","<?=$Nivel4->NOM_ARQUIVO_IMAGEM_ESCURO?>","<?=$Nivel4->DSC_MENU_ACESSO?>"],
                                                                         */
                                                                        // NIVEL 5
                                                                    }elseif($Nivel4->ITENS_MENU != null){
                                                                        ?>
                                                                        <li><a href="<?=$Nivel4->NOM_ARQUIVO?>" class="fly"><?=$Nivel4->DSC_MENU_ACESSO?></a>
                                                                        <?
                                                                            ?>
                                                                            <ul>
                                                                            <?
                                                                            for ($i5 = 0; $i5 < count($Nivel4->ITENS_MENU); $i5++){		
                                                                                    //$Nivel5 = unserialize($Nivel4->ITENS_MENU[$i5]);				
                                                                                    $Nivel5 = $Nivel4->ITENS_MENU[$i5];
                                                                                    ?>
                                                                                    <li><a href="<?=$Nivel5->NOM_ARQUIVO?>"><?=$Nivel5->DSC_MENU_ACESSO?></a></li>
                                                                                    <?
                                                                                    /*
                                                                                     ["||||<?=$Nivel5->DSC_MENU_ACESSO?>","<?=$Nivel5->NOM_ARQUIVO?>","<?=$Nivel5->NOM_ARQUIVO_IMAGEM_CLARO?>","<?=$Nivel5->NOM_ARQUIVO_IMAGEM_ESCURO?>","<?=$Nivel5->DSC_MENU_ACESSO?>"],
                                                                                     */
                                                                            }

                                                                            ?>
                                                                            </ul>
                                                                            <?
                                                                    }//FIM NIVEL 5
                                                                    ?>
                                                                    </li> <!-- li nivel 4 - class="sub" -->
                                                                    <?  
                                                            }
                                                            ?>
                                                            </ul>
                                                            <?
                                                    }//FIM NIVEL 4
                                                    ?>
                                                    </li> <!-- li nivel 3 - class="sub" -->
                                                    <?  
                                            }
                                            ?>
                                            </ul>
                                            <?
                                    }//FIM NIVEL 3
                                    ?>
                                    </li> <!-- li nivel 2 - class="sub" -->
                                    <?  
                            }
                            ?>
                            </ul> <!-- class="sub"> -->
                            <?
                    }// FIM NIVEL 2
                    $cont++;
                    ?>
                    </li> <!-- li nivel 1 - class="sub" -->
                    <?  
            }
            ?>
            </ul>
            <?
     }// FIM NIVEL 1

} else {
	print "&nbsp;";
}
?>