<?php

function nume_categorie($id) {
    $result = $bazadate -> query("SELECT id, nume FROM biblioteca_categorii WHERE id = '" . $id . "'");
    if ($result -> num_rows > 0) {
        $row = $result -> fetch_assoc();
        return $row['nume'];
    }
}

function nume_autor($id) {
    $result = $bazadate -> query("SELECT id, nume FROM biblioteca_autori WHERE id = '" . $id . "'");
    if ($result -> num_rows > 0) {
        $row = $result -> fetch_assoc();
        return $row['nume'];
    }
}

function listare_carte($result) {
    echo '<div class="sub book-list">';
    while ($row = $result -> fetch_assoc()) { ?>
    <div class="book">
        <div class="book-image">
            <a href="carte.php?id=<?php echo $row['id']; ?>">
                <img src="<?php echo $row['imagine']; ?>">
            </a>
        </div>
        <div class="book-details">
            <div class="book-overview">
                <div class="book-tags">
                    <?php
                    $categorii = explode(',', $row['categorii']);
                    foreach($categorii as $c) {
                        echo '<a href="' . $c . '">' . nume_categorie($c) . '</a>';
                    }
                    ?>
                </div>
                <a class="book-title" href="carte.php?id=<?php echo $row['id']; ?>"><?php echo $row['titlu']; ?></a><br />
                <a class="book-author" href="autor.php?id=<?php echo $row['autor']; ?>"><?php echo $row['autor']; ?></a><br />
                <p class="book-description">Descriere pe scurt a cărții</p>
            </div>
            <div class="book-actions">
                <button class="btn">
                    <iconify-icon icon="fluent:bookmark-20-filled" width="20"></iconify-icon>
                    <label>Salvează</label>
                </button>
                <button class="btn">
                    <iconify-icon icon="fluent:arrow-between-down-20-filled" width="20"></iconify-icon>
                    <label>Împrumută</label>
                </button>
            </div>
        </div>
    </div>
    <?php }
    echo '</div>';
}

?>