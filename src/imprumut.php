<?php 

include_once('template_1.php');

$titlu_pagina = "Împrumută cartea";

include_once('template_2.php');

$page_msg = 'not_found';

if (isset($_GET['carte'])) {
    $c_id = $_GET['carte'];
    $stmt = $bazadate -> prepare("SELECT * FROM biblioteca_carti WHERE id = ?");
    $stmt -> bind_param("s", $c_id);
    $stmt -> execute();
    $c_data;
    $result = $stmt -> get_result();
    if ($row = $result -> fetch_assoc()) {
        $c_data = $row;
        $page_msg = 'book_found';
    } else {
        $page_msg = 'book_not_found';
    }
} else if (isset($_POST['id'])) {
    $c_id = random_string(8);
    $c_user = $_SESSION['user_id'];
    $c_carte = $_POST['id'];
    $c_inceput = $_POST['inceput'];
    $c_sfarsit = $_POST['sfarsit'];
    $c_stare = "asteptare";

    $stmt = $bazadate -> prepare("INSERT INTO biblioteca_imprumuturi (id, user, carte, inceput, sfarsit, stare) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("ssssss", $c_id, $c_user, $c_carte, $c_inceput, $c_sfarsit, $c_stare);
    $status = $stmt -> execute();
    $page_msg = "insert_" . ($status ? "ok" : "fail");
}

?>

<div id="continut">

<?php if($page_msg == "book_not_found") { ?> 
    <h1>Împrumută cartea</h1>
    <div class="alertbox btn-red">
        Cartea cu ID-ul specificat nu a fost găsită.
    </div><br>
    <a href="carti.php" class="btn">Înapoi la lista de cărți</a>
<?php } else if ($page_msg == "not_found") { ?> 
    <h1>Împrumută cartea</h1>
    <div class="alertbox btn-red">
        Împrumutul cu ID-ul specificat nu a fost găsit.
    </div><br>
    <a href="cont.php?imprumuturi" class="btn">Înapoi la lista de împrumutului</a>
<?php } else if ($page_msg == "insert_ok") { ?> 
    <h1>Împrumută cartea</h1>
    <div class="alertbox btn-green">
        Împrumutul a fost realizat cu succes!
    </div><br>
    <a href="cont.php?imprumuturi" class="btn">Mergi spre lista de împrumutului</a>
<?php } else if ($page_msg == "insert_fail") { ?> 
    <h1>Împrumută cartea</h1>
    <div class="alertbox btn-red">
        Eroare la înregistrarea împrumutului!
    </div><br>
    <a href="cont.php?imprumuturi" class="btn">Mergi spre lista de împrumutului</a>
<?php } else if ($page_msg == "book_found") { ?>
    <h1>Împrumută cartea</h1>
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
    <form action="imprumut.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $_GET['carte']; ?>">
        <label class="form-field">
            Data de început<br />
            <input type="date" class="textbox" name="inceput" style="width: 100%;" required />
        </label>
        <label class="form-field">
            Data finalizării<br />
            <input type="date" class="textbox" name="sfarsit" style="width: 100%;" required />
        </label>
        <div class="form-field">
            <button type="submit" class="btn btn-green">Împrumută</button>
        </div>
    </form>
<?php } ?>

</div>

<?php include_once('template_3.php'); ?>
