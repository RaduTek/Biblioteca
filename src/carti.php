<?php include_once('template_1.php'); ?>

<?php

$titlu_pagina = "Cărți";

$nr_resultate = 0;
$nr_pagini = 0;
$nr_pe_pagina = 8;
$pagina = 1;
if (isset($_GET['p']))
    $pagina = ceil($_GET['p']);
$start_index = ($pagina - 1) * $nr_pe_pagina;

$tip_pagina = "lista";
$id_user = NULL;
if (isset($_SESSION['user_id'])) {
    $id_user = $_SESSION['user_id'];
}

if (isset($_GET['categorie'])) {
    $c_categorie = $_GET['categorie'];
    $tip_pagina = "categorie";
    $q_pagina = "categorie=" . $c_categorie;
    $stmt = $bazadate -> prepare("SELECT COUNT(id) FROM biblioteca_carti WHERE categorie = ?");
    $stmt -> bind_param("s", $c_categorie);
} else if (isset($_GET['salvate'])) {
    $tip_pagina = "salvate";
    $stmt = $bazadate -> prepare("SELECT COUNT(id_carte) FROM biblioteca_salvate WHERE id_user = ?");
    $stmt -> bind_param("s", $id_user);
} else if (isset($_GET['cauta'])) {
    $c_cautare = $_GET['cauta'];
    $tip_pagina = "cauta";
    $q_pagina = "cauta=" . $c_cautare;
    $stmt = $bazadate -> prepare("SELECT id, 
        MATCH(autor, titlu, descriere) AGAINST(?) AS relevance 
        FROM biblioteca_carti 
        HAVING relevance > 0 
        ORDER BY relevance DESC");
    $stmt -> bind_param("s", $c_cautare);
    $stmt -> execute();
    $result = $stmt -> get_result();
    $nr_resultate = intval($result -> num_rows); 
} else {
    $stmt = $bazadate -> prepare("SELECT COUNT(id) FROM biblioteca_carti");
}
if ($nr_resultate == 0) {
    $stmt -> execute();
    $result = $stmt -> get_result();
    if ($row = $result->fetch_row())
        $nr_resultate = $row[0];
}
$nr_pagini = ceil($nr_resultate / $nr_pe_pagina);

?>

<?php include_once('template_2.php'); ?>

<div id="continut">
    
    <?php if ($tip_pagina == "salvate") { 
        echo '<h1>Cărți salvate</h1>';
        if (isset($_SESSION['save_result'])) {
            if ($_SESSION['save_result'] == "already_saved") { ?>
                <div class="alertbox btn-red">Cartea este deja salvată!</div><br/>
            <?php } else if ($_SESSION['save_result'] == "saved_ok") { ?>
                <div class="alertbox btn-green">Cartea a fost salvată cu succes!</div><br/>
            <?php } else if ($_SESSION['save_result'] == "not_saved") { ?>
                <div class="alertbox btn-red">Cartea nu este salvată!</div><br/>
            <?php } else if ($_SESSION['save_result'] == "removed_ok") { ?>
                <div class="alertbox btn-green">Cartea a fost eliminată cu succes!</div><br/>
            <?php }
            unset($_SESSION['save_result']);
        }
    } else if ($tip_pagina == "categorie") {
        $stmt = $bazadate -> prepare("SELECT nume FROM biblioteca_categorii WHERE id = ?");
        $stmt -> bind_param("s", $c_categorie);
        $stmt -> execute();
        $result = $stmt -> get_result();
        if ($result -> num_rows > 0) {
            $nume_categorie = ($result -> fetch_row())[0];
        } else {
            $nume_categorie = "Categoria nu a fost găsită";
        }
        echo "<h1>$nume_categorie</h1>";
    } else if ($tip_pagina == "cauta") {
        echo '<h1>Căutare: ' . $c_cautare . '</h1>';
    } else {
        echo '<h1>Cărți</h1>';
    } ?>

    <?php if ($nr_pagini == 0) { ?>
    <div style="text-align: center"> 
        <?php if ($tip_pagina == "cauta") { ?>
        <h2 style="border: none">Nici o carte nu se potrivește căutării.</h2>
        <p>Încercați alți termeni de căutare.</p>
        <?php } else if ($tip_pagina == "salvate") { ?>
        <h2 style="border: none">Nu ați salvat nici o carte.</h2>
        <p>Cărțile pe care le salvați vor apărea aici.</p>
        <?php } else { ?>
        <h2 style="border: none">Nici o carte nu este adăugată în această categorie.</h2>
        <p>Alegeți altă categorie</p>
        <?php } ?>
    </div> 
    <?php } else { ?>
    <div class="sub book-list">
        <?php
            if ($tip_pagina == "categorie") {
                $c_categorie = $_GET['categorie'];
                if ($id_user) {
                    $stmt = $bazadate -> prepare("SELECT biblioteca_carti.*, biblioteca_categorii.nume, biblioteca_salvate.id_user FROM biblioteca_carti 
                        INNER JOIN biblioteca_categorii ON biblioteca_carti.categorie=biblioteca_categorii.id 
                        LEFT JOIN biblioteca_salvate ON biblioteca_carti.id = biblioteca_salvate.id_carte AND biblioteca_salvate.id_user = ?
                        WHERE categorie = ? 
                        ORDER BY biblioteca_carti.titlu
                        LIMIT $start_index, $nr_pe_pagina");
                    $stmt -> bind_param("ss", $id_user, $c_categorie);
                } else {
                    $stmt = $bazadate -> prepare("SELECT biblioteca_carti.*, biblioteca_categorii.nume FROM biblioteca_carti 
                        INNER JOIN biblioteca_categorii ON biblioteca_carti.categorie=biblioteca_categorii.id 
                        WHERE categorie = ? 
                        ORDER BY biblioteca_carti.titlu
                        LIMIT $start_index, $nr_pe_pagina");
                    $stmt -> bind_param("s", $c_categorie);
                }
            } else if ($tip_pagina == "salvate") {
                $stmt = $bazadate -> prepare("SELECT biblioteca_carti.*, biblioteca_salvate.id_carte, biblioteca_categorii.nume
                    FROM biblioteca_carti 
                    INNER JOIN biblioteca_categorii ON biblioteca_carti.categorie=biblioteca_categorii.id 
                    INNER JOIN biblioteca_salvate ON biblioteca_carti.id=biblioteca_salvate.id_carte
                    WHERE biblioteca_salvate.id_user = ? 
                    ORDER BY biblioteca_carti.titlu
                    LIMIT $start_index, $nr_pe_pagina");
                $stmt -> bind_param("s", $id_user);
            } else if ($tip_pagina == "cauta") {
                if ($id_user) {
                    $stmt = $bazadate -> prepare("SELECT biblioteca_carti.*, biblioteca_salvate.id_carte, biblioteca_categorii.nume, 
                        MATCH(biblioteca_carti.autor, biblioteca_carti.titlu, biblioteca_carti.descriere) AGAINST(? IN BOOLEAN MODE) AS relevance 
                        FROM biblioteca_carti 
                        INNER JOIN biblioteca_categorii ON biblioteca_carti.categorie=biblioteca_categorii.id 
                        LEFT JOIN biblioteca_salvate ON biblioteca_carti.id=biblioteca_salvate.id_carte 
                        AND biblioteca_salvate.id_user = ? 
                        HAVING relevance > 0
                        ORDER BY relevance DESC
                        LIMIT $start_index, $nr_pe_pagina");
                    $stmt -> bind_param("ss", $c_cautare, $id_user);
                } else {
                }
            } else {
                if ($id_user) {
                    $stmt = $bazadate -> prepare("SELECT biblioteca_carti.*, biblioteca_categorii.nume, biblioteca_salvate.id_user FROM biblioteca_carti 
                        INNER JOIN biblioteca_categorii ON biblioteca_carti.categorie=biblioteca_categorii.id 
                        LEFT JOIN biblioteca_salvate ON biblioteca_carti.id = biblioteca_salvate.id_carte AND biblioteca_salvate.id_user = ?
                        ORDER BY biblioteca_carti.titlu
                        LIMIT $start_index, $nr_pe_pagina");
                    $stmt -> bind_param("s", $id_user);
                } else {
                    $stmt = $bazadate -> prepare("SELECT biblioteca_carti.*, biblioteca_categorii.nume FROM biblioteca_carti 
                        INNER JOIN biblioteca_categorii ON biblioteca_carti.categorie=biblioteca_categorii.id 
                        ORDER BY biblioteca_carti.titlu
                        LIMIT $start_index, $nr_pe_pagina");
                }
            }
            $stmt -> execute();
            $result = $stmt -> get_result();
            while ($row = $result->fetch_assoc()) {
        ?>
        <div class="book">
            <div class="book-image">
                <a href="carte.php?id=<?php echo $row['id'] ?>">
                    <img src="<?php if (strlen($row['imagine']) > 1) echo $row['imagine']; else echo 'imagini/book-cover-placeholder.png'; ?>">
                </a>
            </div>
            <div class="book-details">
                <div class="book-overview">
                    <a class="book-title" href="carte.php?id=<?php echo $row['id'] ?>"><?php echo $row['titlu'] ?></a><br />
                    <a class="book-author"><?php echo $row['autor'] ?></a><br />
                    <?php if ($tip_pagina != "categorie") { ?>
                    <a class="book-category" href="carti.php?categorie=<?php echo $row['categorie'] ?>"><?php echo $row['nume'] ?></a><br />
                    <?php } ?>
                    <p class="book-description"><?php echo substr($row['descriere'],0,150); ?></p>
                </div>
                <div class="book-actions">
                    <?php if ($tip_pagina == "salvate" || (isset($row['id_user']) && $row['id_user'] == $id_user)) { ?>
                    <a class="btn" href="cont.php?elimina=<?php echo $row['id'] ?>">
                        <iconify-icon icon="fluent:bookmark-off-20-regular" width="20"></iconify-icon>
                        <label>Elimină</label>
                    </a>
                    <?php } else { ?>
                    <a class="btn" href="cont.php?salveaza=<?php echo $row['id'] ?>">
                        <iconify-icon icon="fluent:bookmark-20-regular" width="20"></iconify-icon>
                        <label>Salvează</label>
                    </a>
                    <?php } ?>
                    <a class="btn" href="imprumut.php?carte=<?php echo $row['id'] ?>">
                        <iconify-icon icon="fluent:arrow-square-down-20-regular" width="20"></iconify-icon>
                        <label>Împrumută</label>
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <div class="sub">
        <div class="pagination-field">
            <div class="summary"></div>
            <div class="pagination">
                <a 
                    class="btn" 
                    <?php if($pagina == 1) echo 'disabled'; else { ?>
                        href="?<?php if(isset($q_pagina)) echo $q_pagina . '&' ?>p=1" 
                    <?php } ?> 
                    >
                    <iconify-icon icon="fluent:chevron-left-16-filled"></iconify-icon>
                </a>
                <?php for($i=1; $i <= $nr_pagini; $i++) { ?>
                    <a 
                        class="btn"
                        href="?<?php if(isset($q_pagina)) echo $q_pagina . '&' ?>p=<?php echo $i; ?>"
                        >
                        <?php echo $i; ?>
                    </a>
                <?php } ?>
                <a 
                    class="btn" 
                    <?php if($pagina == $nr_pagini) echo 'disabled'; else { ?>
                        href="?<?php if(isset($q_pagina)) echo $q_pagina . '&' ?>p=<?php echo $nr_pagini; ?>"
                    <?php } ?>
                    >
                    <iconify-icon icon="fluent:chevron-right-16-filled"></iconify-icon>
                </a>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<?php include_once('template_3.php'); ?>
