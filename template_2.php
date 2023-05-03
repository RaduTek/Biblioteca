<!DOCTYPE html>
<html lang="ro">
    <head>
        <title>
            <?php
                if (isset($titlu_pagina))
                    echo $titlu_pagina . ' - ';
                echo $titlu_aplicatie;
            ?>
        </title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />

        <link rel="preload" href="font/SegoeUIVariable.woff" as="font" type="font/woff" crossorigin>
        <link rel="preload" href="font/SegoeUIVariable.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="stylesheet" href="css/style.css" />

        <script type="text/javascript" src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
        <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>

        <script type="text/javascript" src="js/script.js"></script>
    </head>
    <body onload="bodyOnLoad()">
        <div id="sus">
            <div id="navTop">
                <div id="navSidebarToggle">
                    <button class="btn-round tt-parent" id="navSidebarToggleBtn">
                        <span class="tt tt-br tt-delay m-deschide">Deschide meniu</span>
                        <iconify-icon icon="fluent:navigation-20-filled" class="m-deschide" width="36"></iconify-icon>
                        <span class="tt tt-br tt-delay m-inchide mobile-hide">Închide meniu</span>
                        <iconify-icon icon="fluent:dismiss-20-filled" class="m-inchide mobile-hide" width="36"></iconify-icon>
                    </button>
                </div>
                <div id="navLogo">
                    <a href="index.php">
                        <?php $titlu_aplicatie_x = explode(' ', $titlu_aplicatie); ?>
                        <span id="navNumeApp"><?php echo array_shift($titlu_aplicatie_x);; ?></span>
                        <span id="navNumeSecundar"> <?php echo implode(' ', $titlu_aplicatie_x);?></span>
                    </a>
                </div>
                <div id="navSearch">
                    <form id="formSearch" action="carti.php" method="get">
                        <button id="btnHideSearch" class="tt-parent" type="reset">
                            <span class="tt tt-br tt-delay">Închide</span>
                            <iconify-icon icon="fluent:dismiss-16-regular"></iconify-icon>
                        </button>
                        <input id="inputSearch" type="text" name="cauta" placeholder="Căutare"/>
                        <button id="btnSearch" class="tt-parent" type="sumbit">
                            <span class="tt tt-bl tt-delay">Caută</span>
                            <iconify-icon icon="fluent:search-16-regular"></iconify-icon>
                        </button>
                    </form>
                </div>
                <div id="navUser">
                    <?php if (isset($_SESSION['user_id'])) { ?>  
                    <a href="cont.php">
                        <button class="btn-round tt-parent">
                            <span class="tt tt-bl tt-delay">Deschide pagina contului</span>
                            <label>Contul meu</label>
                            <iconify-icon icon="fluent:person-16-regular" width="36"></iconify-icon>
                        </button>
                    </a>
                    <?php } else { ?>
                    <a href="autentificare.php">
                        <button class="btn-round tt-parent">
                            <span class="tt tt-bl tt-delay">Intră în contul tău</span>
                            <label>Autentifică-te</label>
                            <iconify-icon icon="fluent:person-16-regular" width="36"></iconify-icon>
                        </button>
                    </a>
                    <?php } ?>
                    <button id="btnShowSearch" class="btn-round tt-parent">
                        <span class="tt tt-bl tt-delay">Căutare</span>
                        <label>Căutare</label>
                        <iconify-icon icon="fluent:search-16-regular" width="32"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
        <div id="sidebar">
            <div id="sidebarDim"></div>
            <div id="navSidebar">
                <div id="sidebarProfile">
                    <div id="sidebarProfileBack"></div>
                    <div id="sidebarProfileFore">
                        <img id="sidebarProfileImg" src="imagini/user-placeholder.jpg" />
                        <span id="sidebarProfileNume">
                            <?php if(isset($_SESSION['user_nume'])) echo $_SESSION['user_nume']; else { ?>
                            Nu ești autentificat
                            <?php } ?>
                        </span>
                    </div>
                </div>

                <?php if(isset($_SESSION['user_nume'])) { ?>
                <div class="sidebarHead">Biblioteca mea</div>
                <a href="carti.php?salvate" class="sidebarBtn">
                    <iconify-icon icon="fluent:bookmark-20-filled" width="20"></iconify-icon>
                    Salvate
                </a>
                <a href="cont.php?p=imprumuturi" class="sidebarBtn">
                    <iconify-icon icon="fluent:dual-screen-arrow-up-20-filled" width="20"></iconify-icon>
                    De înapoiat
                </a>
                <a href="cont.php?p=citite" class="sidebarBtn">
                    <iconify-icon icon="fluent:checkbox-checked-20-filled" width="20"></iconify-icon>
                    Citite
                </a>
                <?php } ?>
                
                <?php  if(isset($_SESSION['user_nume'])) if ($_SESSION['user_tip'] == 'admin') { ?>
                <div class="sidebarHead">Administrare</div>
                <a href="imprumuturi.php" class="sidebarBtn">
                    <iconify-icon icon="fluent:document-header-arrow-down-20-filled" width="20"></iconify-icon>
                    Împrumuturi
                </a>
                <a href="adaugare.php" class="sidebarBtn">
                    <iconify-icon icon="fluent:book-add-20-filled" width="20"></iconify-icon>
                    Adaugă o carte
                </a>
                <?php } ?>
                
                <div class="sidebarHead">Categorii</div>
                <a href="carti.php" class="sidebarBtn">
                    <iconify-icon icon="fluent:book-20-filled" width="20"></iconify-icon>
                    Toate cărțile
                </a>
                <?php
                $result = $bazadate -> query("SELECT id, nume FROM biblioteca_categorii");

                while($row = $result -> fetch_assoc()) {
                    echo '<a href="carti.php?categorie=' . $row['id'] . '" class="sidebarBtn">';
                    echo '<iconify-icon icon="fluent:book-20-regular" width="20"></iconify-icon>';
                    echo $row['nume'] . '</a>';
                }
                ?>
            </div>
        </div>

        <div id="pagina">