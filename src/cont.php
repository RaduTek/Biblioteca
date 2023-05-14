<?php 

include_once('template_1.php');

$titlu_pagina = "Contul Meu";

if (!isset($_SESSION['user_id'])) {
    redirect('autentificare.php');
}

$stari = [
    "asteptare" => "În așteptare",
    "predat" => "Primit",
    "anulat" => "Anulat",
    "returnat" => "Înapoiat",
];


$user_id = $_SESSION['user_id'];

if (isset($_GET['salveaza'])) {
    $c_id = $_GET['salveaza'];
    $stmt = $bazadate -> prepare("SELECT * FROM biblioteca_salvate WHERE id_user = ? AND id_carte = ?");
    $stmt -> bind_param("ss", $user_id, $c_id);
    $stmt -> execute();
    $result = $stmt -> get_result();
    if ($result -> num_rows > 0) {
        $_SESSION['save_result'] = "already_saved";
    } else {
        $stmt = $bazadate -> prepare("INSERT INTO biblioteca_salvate (id_user, id_carte) VALUES (?, ?)");
        $stmt -> bind_param("ss", $user_id, $c_id);
        $stmt -> execute();
        $_SESSION['save_result'] = "saved_ok";
    }
    redirect('carti.php?salvate');
} else if (isset($_GET['elimina'])) {
    $c_id = $_GET['elimina'];
    $stmt = $bazadate -> prepare("SELECT * FROM biblioteca_salvate WHERE id_user = ? AND id_carte = ?");
    $stmt -> bind_param("ss", $user_id, $c_id);
    $stmt -> execute();
    $result = $stmt -> get_result();
    if ($result -> num_rows > 0) {
        $stmt = $bazadate -> prepare("DELETE FROM biblioteca_salvate WHERE id_user = ? AND id_carte = ?");
        $stmt -> bind_param("ss", $user_id, $c_id);
        $stmt -> execute();
        $_SESSION['save_result'] = "removed_ok";
    } else {
        $_SESSION['save_result'] = "not_saved";
    }
    redirect('carti.php?salvate');
} else if (isset($_POST['parola_noua'])) {
    $u_password = $_POST['parola_curenta'];
    $u_password_new = $_POST['parola_noua'];
    $u_password_c = $_POST['parola_noua_c'];
    $u_id = $_SESSION['user_id'];

    if ($u_password_new != $u_password_c) {
        $page_msg = "password_no_match";
    } else {
        $u_password_hash = password_hash($u_password_new, PASSWORD_DEFAULT);
    
        if (password_verify($u_password, $_SESSION['user_password'])) {
            $stmt = $bazadate -> prepare("UPDATE biblioteca_useri SET parola = ? WHERE id = ?");
            $stmt -> bind_param("ss", $u_password_hash, $u_id);
            $status = $stmt -> execute();
            if ($status) {
                $_SESSION['user_password'] = $u_password_hash;
            }
            $page_msg = "password_change_" . ($status ? "ok" : "fail");
        } else {
            $page_msg = "password_incorrect";
        }
    }

} else if (isset($_POST['email_nou'])) {
    $u_email = $_POST['email_nou'];
    $u_password = $_POST['parola_curenta'];
    $u_id = $_SESSION['user_id'];

    if (password_verify($u_password, $_SESSION['user_password'])) {
        $stmt = $bazadate -> prepare("UPDATE biblioteca_useri SET email = ? WHERE id = ?");
        $stmt -> bind_param("ss", $u_email, $u_id);
        $status = $stmt -> execute();
        if ($status) {
            $_SESSION['user_email'] = $u_email;
        }
        $page_msg = "email_change_" . ($status ? "ok" : "fail");
    } else {
        $page_msg = "password_incorrect";
    }

} else if (isset($_POST['nume_nou'])) {
    $u_nume = $_POST['nume_nou'];
    $u_password = $_POST['parola_curenta'];
    $u_id = $_SESSION['user_id'];

    if (password_verify($u_password, $_SESSION['user_password'])) {
        $stmt = $bazadate -> prepare("UPDATE biblioteca_useri SET nume = ? WHERE id = ?");
        $stmt -> bind_param("ss", $u_nume, $u_id);
        $status = $stmt -> execute();
        if ($status) {
            $_SESSION['user_nume'] = $u_nume;
        }
        $page_msg = "name_change_" . ($status ? "ok" : "fail");
    } else {
        $page_msg = "password_incorrect";
    }
}

$pagina = 'imprumuturi';

if (isset($_GET['p'])) {
    $pagina = $_GET['p'];
}

if ($pagina == 'iesire') {
    session_destroy();
    redirect('autentificare.php');
}

include_once('template_2.php');

?>

<div id="continut">
    <?php if (isset($page_msg)) {
        if ($page_msg == "password_incorrect") { ?>
            <div class="alertbox btn-red">Parola introdusă nu este corectă!</div><br/>

        <?php } else if ($page_msg == "password_no_match") { ?>
            <div class="alertbox btn-red">Parolele nu se potrivesc!</div><br/>

        <?php } else if ($page_msg == "password_change_ok") { ?>
            <div class="alertbox btn-green">Parola contului a fost schimbată cu succes!</div><br/>

        <?php } else if ($page_msg == "password_change_fail") { ?>
            <div class="alertbox btn-red">Eroare la modificarea parolei!</div><br/>

            <?php } else if ($page_msg == "email_change_ok") { ?>
            <div class="alertbox btn-green">Adresa de mail a fost schimbată cu succes!</div><br/>

        <?php } else if ($page_msg == "email_change_fail") { ?>
            <div class="alertbox btn-red">Eroare la modificarea adresei de mail!</div><br/>

        <?php } else if ($page_msg == "name_change_ok") { ?>
            <div class="alertbox btn-green">Numele a fost schimbat cu succes!</div><br/>

        <?php } else if ($page_msg == "name_change_fail") { ?>
            <div class="alertbox btn-red">Eroare la modificarea numelui!</div><br/>

        <?php } else if ($page_msg == "not_found") { ?>
            <div class="alertbox btn-red">Cartea selectată nu există!</div><br/>
        <?php }
    } ?>
    <h1>Contul Meu</h1>
    <div class="split-page" style="padding-top: 20px">
        <div class="split-small cards cards-vertical">
            <div class="card" style="padding: 10px">
                <img id="userProfileImg" src="imagini/user-placeholder.jpg" />
                <h2 style="margin-bottom: 0;"><?php echo $_SESSION['user_nume'] ?></h2>
                <table class="statTable">
                    <tbody>
                        <tr>
                            <td colspan=3 style="text-align: center; padding: 4px;">
                                <?php if ($_SESSION['user_tip'] == 'admin') echo 'Administrator'; 
                                else echo 'Utilizator'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><iconify-icon icon="fluent:bookmark-20-regular" width="20"></iconify-icon></td>
                            <td><a href="carti.php?salvate">Cărți salvate</a></td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td><iconify-icon icon="fluent:arrow-between-down-20-regular" width="20"></iconify-icon></td>
                            <td>Cărți împrumutate</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td><iconify-icon icon="fluent:checkbox-checked-20-regular" width="20"></iconify-icon></td>
                            <td>Cărți citite</td>
                            <td>0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card" style="padding: 10px">
                <table class="statTable">
                    <tbody>
                        <tr>
                            <td><iconify-icon icon="fluent:person-edit-20-regular" width="20"></iconify-icon></td>
                            <td><a href="cont.php?p=setari">Setările contului</a></td>
                        </tr>
                        <tr>
                            <td><iconify-icon icon="fluent:door-arrow-right-20-regular" width="20"></iconify-icon></td>
                            <td><a href="cont.php?p=iesire">Ieșire din cont</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="split-large padding">
            <div class="pagination" style="justify-content: start;">
                <a href="cont.php?p=imprumuturi" class="btn <?php if($pagina == 'imprumuturi') echo 'btn-active'; ?>">
                    <iconify-icon icon="fluent:arrow-between-down-20-regular" width="20"></iconify-icon>
                    <label>De înapoiat</label>
                </a>
                <a href="cont.php?p=citite" class="btn <?php if($pagina == 'citite') echo 'btn-active'; ?>">
                    <iconify-icon icon="fluent:checkbox-checked-20-regular" width="20"></iconify-icon>
                    <label>Citite</label>
                </a>
            </div>
            <div class="sub book-list">
            <?php 
                
                $sign = ($pagina == 'imprumuturi') ? '!' : ''; 
                $operand = ($pagina == 'imprumuturi') ? 'AND' : 'OR'; 

                $query = "SELECT biblioteca_imprumuturi.user, biblioteca_imprumuturi.carte, biblioteca_imprumuturi.stare, biblioteca_carti.* FROM biblioteca_imprumuturi
                    INNER JOIN biblioteca_carti ON biblioteca_carti.id=biblioteca_imprumuturi.carte
                    WHERE biblioteca_imprumuturi.user = ? AND biblioteca_imprumuturi.stare " . $sign . "= 'returnat' " . $operand . " biblioteca_imprumuturi.stare " . $sign . "= 'anulat'
                    ORDER BY biblioteca_carti.titlu";
                
                $stmt = $bazadate -> prepare($query);
                $stmt -> bind_param("s", $_SESSION['user_id']);
                $stmt -> execute();
                $result = $stmt -> get_result();
                if (($result -> num_rows) == 0) {
                    echo "Momentan niciun împrumut.";
                } 
                while ($c_data = $result->fetch_assoc()) {
            ?>
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
                            <a class="book-author">Stare: <?php echo $stari[$c_data['stare']]; ?></a><br />
                            <p class="book-description"><?php echo substr($c_data['descriere'],0,150); ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>

            <?php if ($pagina == 'setari') { ?>

                <form action="cont.php?p=setari" method="post">
                    <h2>Schimbă parola</h2>
                    <label class="form-field">
                        <div>Parola veche</div>
                        <input type="password" class="textbox" name="parola_curenta" style="width: 100%;" required />
                    </label>
                    <label class="form-field">
                        <div>Parola nouă</div>
                        <input type="password" class="textbox" name="parola_noua" style="width: 100%;" required />
                    </label>
                    <label class="form-field">
                        <div>Confirmă</div>
                        <input type="password" class="textbox" name="parola_noua_c" style="width: 100%;" required />
                    </label>
                    <button type="submit" class="btn btn-green">Schimbă parola</button>
                </form>
                <br>

                <form action="cont.php?p=setari" method="post">
                    <h2>Schimbă adresa de mail</h2>
                    <label class="form-field">
                        <div>Adresa nouă de mail</div>
                        <input type="text" class="textbox" name="email_nou" style="width: 100%;" required />
                    </label>
                    <label class="form-field">
                        <div>Parola curentă</div>
                        <input type="password" class="textbox" name="parola_curenta" style="width: 100%;" required />
                    </label>
                    <button type="submit" class="btn btn-green">Schimbă adresa de mail</button>
                </form>
                <br>

                <form action="cont.php?p=setari" method="post">
                    <h2>Schimbă numele</h2>
                    <label class="form-field">
                        <div>Nume nou</div>
                        <input type="text" class="textbox" name="nume_nou" style="width: 100%;" required />
                    </label>
                    <label class="form-field">
                        <div>Parola curentă</div>
                        <input type="password" class="textbox" name="parola_curenta" style="width: 100%;" required />
                    </label>
                    <button type="submit" class="btn btn-green">Schimbă numele</button>
                </form>
            <?php } else { ?>
            <?php } ?>
        </div>
    </div>
</div>

<?php include_once('template_3.php'); ?>
