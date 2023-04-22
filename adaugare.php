<?php 

include_once('template_1.php');

$titlu_pagina = "Adaugă o carte";

if (isset($_POST['adaugare_carte'])) {
    $c_id = random_string();
    $c_titlu = $_POST['titlu'];
    $c_autor = $_POST['autor'];
    $c_isbn = $_POST['isbn'];
    $c_categorie = $_POST['categorie'];
    $c_imagine = $_POST['imagine'];
    $c_descriere = $_POST['descriere'];

    $stmt = $bazadate -> prepare("INSERT INTO biblioteca_carti (id, titlu, autor, isbn, categorie, imagine, descriere) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("sssssss", $c_id, $c_titlu, $c_autor, $c_isbn, $c_categorie, $c_imagine, $c_descriere);
    $status = $stmt -> execute();
    $page_msg = "insert_" . ($status ? "ok" : "fail");

} else if (isset($_POST['editare_carte'])) {
    $c_id = $_POST['id'];
    $c_titlu = $_POST['titlu'];
    $c_autor = $_POST['autor'];
    $c_isbn = $_POST['isbn'];
    $c_categorie = $_POST['categorie'];
    $c_imagine = $_POST['imagine'];
    $c_descriere = $_POST['descriere'];

    $stmt = $bazadate -> prepare("UPDATE biblioteca_carti SET titlu = ?, autor = ?, isbn = ?, categorie = ?, imagine = ?, descriere = ? WHERE id = ?");
    $stmt -> bind_param("sssssss", $c_titlu, $c_autor, $c_isbn, $c_categorie, $c_imagine, $c_descriere, $c_id);
    $status = $stmt -> execute();
    $page_msg = "edit_" . ($status ? "ok" : "fail");

} else if (isset($_GET['editeaza'])) {
    $titlu_pagina = "Modifică o carte";
    $c_id = $_GET['editeaza'];
    $stmt = $bazadate -> prepare("SELECT * FROM biblioteca_carti WHERE id = ?");
    $stmt -> bind_param("s", $c_id);
    $stmt -> execute();
    $c_data;
    $result = $stmt -> get_result();
    if ($row = $result -> fetch_assoc()) {
        $c_data = $row;
    } else {
        $page_msg = 'not_found';
    }
} else if (isset($_GET['sterge'])) {
    $titlu_pagina = "Șterge o carte";
    $tip_pagina = "sterge";
    $c_id = $_GET['sterge'];
    $stmt = $bazadate -> prepare("SELECT * FROM biblioteca_carti WHERE id = ?");
    $stmt -> bind_param("s", $c_id);
    $stmt -> execute();
    $c_data;
    $result = $stmt -> get_result();
    if ($row = $result -> fetch_assoc()) {
        $c_data = $row;
    } else {
        $page_msg = 'not_found';
    }
} else if (isset($_GET['confirma_stergere'])) {
    $titlu_pagina = "Cartea a fost ștearsă cu succes!";
    $tip_pagina = "sterge";
    $c_id = $_GET['confirma_stergere'];
    $stmt = $bazadate -> prepare("DELETE FROM biblioteca_carti WHERE id = ?");
    $stmt -> bind_param("s", $c_id);
    $stmt -> execute();
    $page_msg = 'sterge_ok';
}

include_once('template_2.php');

?>

<?php if (isset($tip_pagina) && $tip_pagina == "sterge") { ?>
<div id="continut">
    <h1 style="color:red">Șterge o carte</h1>
    <?php if(isset($page_msg) && $page_msg == "not_found") { ?> 
        <p>Cartea cu ID-ul specificat nu există în baza de date.</p>
        <a href="carti.php" class="btn">Înapoi la lista de cărți</a>
    <?php } else if(isset($page_msg) && $page_msg == "sterge_ok") { ?> 
        <p>Cartea a fost ștearsă cu succes din baza de date.</p>
        <a href="carti.php" class="btn">Înapoi la lista de cărți</a>
    <?php } else { ?>
        <div class="sub book-list">
            <div class="book">
                <div class="book-image">
                    <a href="carte.php?id=<?php echo $c_data['id'] ?>">
                        <img src="<?php if (strlen($c_data['imagine']) > 1) echo $c_data['imagine']; else echo 'imagini/book-cover-placeholder.png'; ?>">
                    </a>
                </div>
                <div class="book-details">
                    <div class="book-overview">
                        <a class="book-title" href="carte.php?id=<?php echo $c_data['id'] ?>"><?php echo $c_data['titlu'] ?></a><br />
                        <a class="book-author"><?php echo $c_data['autor'] ?></a><br />
                        <p class="book-description"><?php echo substr($c_data['descriere'],0,150); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <p>Sigur ștergeți această carte?</p>
        <div class="form-field">
            <a href="adaugare.php?confirma_stergere=<?php echo $c_id ?>" class="btn btn-red">Șterge definitiv</a>
            <a href="carte.php?id=<?php echo $c_id ?>" class="btn">Anulează</a>
        </div>
    <?php } ?>
</div>
<?php } else { ?>

<div id="continut">
    <h1><?php if (isset($c_data)) echo 'Modifică'; else echo 'Adaugă'; ?> o carte</h1>
    <?php if (isset($_SESSION['user_tip']) && $_SESSION['user_tip'] = 'admin') { ?>

        
    <?php if (isset($page_msg)) {
        if ($page_msg == "insert_ok") { ?>
            <div class="alertbox btn-green">Cartea a fost adăugată cu succes!</div><br/>
        <?php } else if ($page_msg == "insert_fail") { ?>
            <div class="alertbox btn-red">Eroare la adăugarea cărții!</div><br/>
        <?php } else if ($page_msg == "edit_ok") { ?>
            <div class="alertbox btn-green">Cartea a fost modificată cu succes!</div><br/>
        <?php } else if ($page_msg == "edit_fail") { ?>
            <div class="alertbox btn-red">Eroare la modificarea cărții!</div><br/>
        <?php } else if ($page_msg == "not_found") { ?>
            <div class="alertbox btn-red">Cartea selectată nu există!</div><br/>
        <?php }
    } ?>
    <form method="POST">
        <input type="hidden" name="<?php if (isset($c_data)) echo 'editare_carte'; else echo 'adaugare_carte'; ?>">
        <?php if (isset($c_data)) { ?> 
            <input type="hidden" name="id" value="<?php echo $c_data['id']; ?>" />
        <?php } ?>
        <label class="form-field">
            Titlul cărții<br />
            <input type="text" class="textbox" name="titlu" style="width: 100%;" required <?php if (isset($c_data)) echo 'value="' . $c_data['titlu'] . '"'; ?> />
        </label>
        <label class="form-field">
            Numele autorului<br />
            <input type="text" class="textbox" name="autor" style="width: 100%;" required <?php if (isset($c_data)) echo 'value="' . $c_data['autor'] . '"'; ?> />
        </label>
        <label class="form-field">
            ISBN<br />
            <input type="text" class="textbox" name="isbn" style="width: 100%;" <?php if (isset($c_data)) echo 'value="' . $c_data['isbn'] . '"'; ?> />
        </label>
        <label class="form-field">
            Categorie<br />
            <select class="textbox" name="categorie" id="categorie" style="width: 100%;" required>
                <option value="">Fără categorie</option>
                <?php
                $result = $bazadate -> query("SELECT id, nume FROM biblioteca_categorii");

                while($row = $result -> fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">'. $row['nume'] . '</option>';
                }
                ?>
            </select>
            <?php if (isset($c_data)) { ?> 
            <script>
            var val = '<?php echo $c_data['categorie']; ?>';
            var mySelect = document.getElementById('categorie');

            for(var i, j = 0; i = mySelect.options[j]; j++) {
                if(i.value == val) {
                    mySelect.selectedIndex = j;
                    break;
                }
            }
            </script>
            <?php } ?>
        </label>
        <label class="form-field">
            Link către imagine<br />
            <input type="text" class="textbox" name="imagine" style="width: 100%;" <?php if (isset($c_data)) echo 'value="' . $c_data['imagine'] . '"'; ?> />
        </label>
        <label class="form-field">
            Descriere<br />
            <textarea class="textbox" rows="5" name="descriere" style="width: 100%;"><?php if (isset($c_data)) echo $c_data['descriere']; ?></textarea>
        </label>
        <div class="form-field">
            <button type="submit" class="btn btn-green">Salvează</button>
            <button type="reset" class="btn btn-red">Anulează</button>
        </div>
    </form>
    <?php } else { ?>
        <div class="alertbox btn-red">Trebuie să fiți autentificat cu un cont administrativ pentru a avea acces la această funcție!</div><br/>
    <?php } ?>
</div>

<?php } ?>

<?php include_once('template_3.php'); ?>
