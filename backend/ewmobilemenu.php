<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(4, "mmi__menu", $Language->MenuPhrase("4", "MenuText"), "_menulist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}menu'), FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mmi_archivos", $Language->MenuPhrase("1", "MenuText"), "archivoslist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}archivos'), FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mmi_categorias_blog", $Language->MenuPhrase("2", "MenuText"), "categorias_bloglist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}categorias_blog'), FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mmi_entradas_blog", $Language->MenuPhrase("3", "MenuText"), "entradas_bloglist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}entradas_blog'), FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mmi_paginas_estaticas", $Language->MenuPhrase("6", "MenuText"), "paginas_estaticaslist.php", -1, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}paginas_estaticas'), FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mmci_Seguridad", $Language->MenuPhrase("9", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(8, "mmi_usuarios", $Language->MenuPhrase("8", "MenuText"), "usuarioslist.php", 9, "", AllowListMenu('{B4028305-4D6B-4D03-8DB3-7403E0DBC5D2}usuarios'), FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mmi_niveles", $Language->MenuPhrase("5", "MenuText"), "niveleslist.php", 9, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mmi_permisos", $Language->MenuPhrase("7", "MenuText"), "permisoslist.php", 9, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
