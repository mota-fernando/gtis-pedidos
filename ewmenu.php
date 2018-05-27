<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(8, "mi_pedidos", $Language->MenuPhrase("8", "MenuText"), "pedidoslist.php", -1, "", TRUE, FALSE, FALSE, "fa-list-alt");
$RootMenu->AddMenuItem(21, "mci_Cate1logo", $Language->MenuPhrase("21", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "glyphicon-hdd");
$RootMenu->AddMenuItem(10015, "mi_categoria", $Language->MenuPhrase("10015", "MenuText"), "categorialist.php", 21, "", TRUE, FALSE, FALSE, "fa-tags");
$RootMenu->AddMenuItem(7, "mi_marcas", $Language->MenuPhrase("7", "MenuText"), "marcaslist.php", 21, "", TRUE, FALSE, FALSE, "glyphicon-registration-mark ");
$RootMenu->AddMenuItem(11, "mi_produtos", $Language->MenuPhrase("11", "MenuText"), "produtoslist.php", 21, "", TRUE, FALSE, FALSE, "fa-paperclip");
$RootMenu->AddMenuItem(5, "mi_empresas", $Language->MenuPhrase("5", "MenuText"), "empresaslist.php?cmd=resetall", 21, "", TRUE, FALSE, FALSE, "glyphicon-tower");
$RootMenu->AddMenuItem(6, "mi_endereco", $Language->MenuPhrase("6", "MenuText"), "enderecolist.php", 21, "", TRUE, FALSE, FALSE, "fa-globe");
$RootMenu->AddMenuItem(9, "mi_pessoa_fisica", $Language->MenuPhrase("9", "MenuText"), "pessoa_fisicalist.php?cmd=resetall", 21, "", TRUE, FALSE, FALSE, "fa-user");
$RootMenu->AddMenuItem(12, "mi_representantes", $Language->MenuPhrase("12", "MenuText"), "representanteslist.php", 21, "", TRUE, FALSE, FALSE, "fa-briefcase");
$RootMenu->AddMenuItem(13, "mi_tranportadora", $Language->MenuPhrase("13", "MenuText"), "tranportadoralist.php", 21, "", TRUE, FALSE, FALSE, "fa-car");
$RootMenu->AddMenuItem(2, "mi_configurar", $Language->MenuPhrase("2", "MenuText"), "configurarlist.php", -1, "", TRUE, FALSE, FALSE, "fa-wrench");
$RootMenu->AddMenuItem(3, "mi_desconto", $Language->MenuPhrase("3", "MenuText"), "descontolist.php", 2, "", TRUE, FALSE, FALSE, "glyphicon-credit-card ");
$RootMenu->AddMenuItem(10, "mi_prazos", $Language->MenuPhrase("10", "MenuText"), "prazoslist.php", 2, "", TRUE, FALSE, FALSE, "fa-hourglass");
$RootMenu->AddMenuItem(10014, "mci_Relatf3rios", $Language->MenuPhrase("10014", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa-area-chart");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
