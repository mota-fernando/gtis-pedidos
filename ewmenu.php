<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(8, "mi_pedidos", $Language->MenuPhrase("8", "MenuText"), "pedidoslist.php", -1, "", TRUE, FALSE, FALSE, "fa-list-alt");
$RootMenu->AddMenuItem(1, "mi_clientes", $Language->MenuPhrase("1", "MenuText"), "clienteslist.php", -1, "", TRUE, FALSE, FALSE, "fa-user");
$RootMenu->AddMenuItem(2, "mi_configurar", $Language->MenuPhrase("2", "MenuText"), "configurarlist.php", -1, "", TRUE, FALSE, FALSE, "fa-wrench");
$RootMenu->AddMenuItem(3, "mi_desconto", $Language->MenuPhrase("3", "MenuText"), "descontolist.php", -1, "", TRUE, FALSE, FALSE, "glyphicon-credit-card ");
$RootMenu->AddMenuItem(5, "mi_empresas", $Language->MenuPhrase("5", "MenuText"), "empresaslist.php", -1, "", TRUE, FALSE, FALSE, "glyphicon-tower");
$RootMenu->AddMenuItem(6, "mi_endereco", $Language->MenuPhrase("6", "MenuText"), "enderecolist.php", -1, "", TRUE, FALSE, FALSE, "fa-globe");
$RootMenu->AddMenuItem(7, "mi_marcas", $Language->MenuPhrase("7", "MenuText"), "marcaslist.php", -1, "", TRUE, FALSE, FALSE, "glyphicon-registration-mark ");
$RootMenu->AddMenuItem(10, "mi_prazos", $Language->MenuPhrase("10", "MenuText"), "prazoslist.php", -1, "", TRUE, FALSE, FALSE, "fa-hourglass");
$RootMenu->AddMenuItem(11, "mi_produtos", $Language->MenuPhrase("11", "MenuText"), "produtoslist.php", -1, "", TRUE, FALSE, FALSE, "fa-paperclip");
$RootMenu->AddMenuItem(12, "mi_representantes", $Language->MenuPhrase("12", "MenuText"), "representanteslist.php", -1, "", TRUE, FALSE, FALSE, "fa-briefcase");
$RootMenu->AddMenuItem(13, "mi_tranportadora", $Language->MenuPhrase("13", "MenuText"), "tranportadoralist.php", -1, "", TRUE, FALSE, FALSE, "fa-car");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
