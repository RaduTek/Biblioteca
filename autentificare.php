<?php 

include_once('template_1.php');

if (isset($_SESSION['user_id'])) {
    redirect('cont.php');
}

if (isset($_POST['login_form'])) {
    $login_email = $_POST['login_email'];
    $stmt = $bazadate -> prepare("SELECT * FROM biblioteca_useri WHERE email = ?");
    $stmt -> bind_param("s", $login_email);
    $stmt -> execute();
    $result = $stmt -> get_result();
    echo $result -> num_rows;
    if ($result -> num_rows == 0)
       $login_result = 'no_account';
    else if ($row = $result -> fetch_assoc()) {
        if (password_verify($_POST['login_pass'], $row['parola'])) {
            // Incepem sesiunea
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_tip'] = $row['tip'];
            $_SESSION['user_nume'] = $row['nume'];
            $_SESSION['user_password'] = $row['parola'];
            $login_result = 'ok';
            redirect('cont.php');
        } else {
            $login_result = 'wrong_pass';
        }
    } else {
        $login_result = 'sql_error';
    }
}

$titlu_pagina = "Autentifică-te";

include_once('template_2.php'); 

?>

<div id="continut">
    <h1 class="al-center border-none">Autentifică-te</h1>

    <div class="sub">
        <div class="cards" id="alert_cards">
            <?php if (isset($login_result)) { ?>
            <div class="card alertbox <?php if ($login_result == 'ok') echo 'btn-green'; else echo 'btn-red'; ?>" style="width: 280px;">
                <iconify-icon icon="fluent:error-circle-16-regular" width="24"></iconify-icon>
                <label><?php 
                if ($login_result == 'no_account' || $login_result == 'wrong_pass')
                    echo 'E-mail sau parola incorecte!';
                else if ($login_result == 'sql_error')
                    echo 'Eroare de sistem la autentificare!';
                else if ($login_result == 'ok')
                    echo 'Cont autentificat cu succes, te redirecționăm către pagina contului...';
                ?></label>
            </div>
            <?php } ?>
        </div>
        <div class="cards">
            <div class="card" style="width: 280px">
                <form action="" method="POST">
                    <input type="hidden" name="login_form">
                    <div class="form-field al-left">
                        <label>Adresa E-mail</label>
                        <input
                            class="textbox display-block width-100"
                            type="email"
                            name="login_email"
                            id="login_email"
                            required
                        />
                    </div>
                    <div class="form-field al-left">
                        <label>Parola</label>
                        <input
                            class="textbox display-block width-100"
                            type="password"
                            name="login_pass"
                            id="login_pass"
                            required
                        />
                    </div>
                    <div class="form-field al-center margin-0">
                        <button type="submit" class="btn btn-green">Autentificare</button>
                        <a href="contnou.php" class="btn">Nu ai cont?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('template_3.php'); ?>
