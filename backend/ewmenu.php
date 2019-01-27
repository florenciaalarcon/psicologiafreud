<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(4, "mi__menu", $Language->MenuPhrase("4", "MenuText"), "_menulist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}menu'), FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mi_slider", $Language->MenuPhrase("10", "MenuText"), "sliderlist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}slider'), FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mi_archivos", $Language->MenuPhrase("1", "MenuText"), "archivoslist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}archivos'), FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mi_categorias_blog", $Language->MenuPhrase("2", "MenuText"), "categorias_bloglist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}categorias_blog'), FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mi_entradas_blog", $Language->MenuPhrase("3", "MenuText"), "entradas_bloglist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}entradas_blog'), FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mi_paginas_estaticas", $Language->MenuPhrase("6", "MenuText"), "paginas_estaticaslist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}paginas_estaticas'), FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mci_Seguridad", $Language->MenuPhrase("9", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(8, "mi_usuarios", $Language->MenuPhrase("8", "MenuText"), "usuarioslist.php", 9, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}usuarios'), FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mi_niveles", $Language->MenuPhrase("5", "MenuText"), "niveleslist.php", 9, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mi_permisos", $Language->MenuPhrase("7", "MenuText"), "permisoslist.php", 9, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
