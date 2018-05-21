<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(1, "mi_clientes", $Language->MenuPhrase("1", "MenuText"), "clienteslist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(2, "mi_configurar", $Language->MenuPhrase("2", "MenuText"), "configurarlist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(3, "mi_desconto", $Language->MenuPhrase("3", "MenuText"), "descontolist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(4, "mi_detalhe_pedido", $Language->MenuPhrase("4", "MenuText"), "detalhe_pedidolist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(5, "mi_empresas", $Language->MenuPhrase("5", "MenuText"), "empresaslist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(6, "mi_endereco", $Language->MenuPhrase("6", "MenuText"), "enderecolist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(7, "mi_marcas", $Language->MenuPhrase("7", "MenuText"), "marcaslist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(8, "mi_pedidos", $Language->MenuPhrase("8", "MenuText"), "pedidoslist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(9, "mi_pessoa_fisica", $Language->MenuPhrase("9", "MenuText"), "pessoa_fisicalist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(10, "mi_prazos", $Language->MenuPhrase("10", "MenuText"), "prazoslist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(11, "mi_produtos", $Language->MenuPhrase("11", "MenuText"), "produtoslist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(12, "mi_representantes", $Language->MenuPhrase("12", "MenuText"), "representanteslist.php", -1, "", TRUE, FALSE, FALSE, "");
$RootMenu->AddMenuItem(13, "mi_tranportadora", $Language->MenuPhrase("13", "MenuText"), "tranportadoralist.php", -1, "", TRUE, FALSE, FALSE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
