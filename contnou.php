<?php

include_once('template_1.php');

if (isset($_SESSION['user_id'])) {
    redirect('cont.php');
}

if (isset($_POST['signup_form'])) {
    $signup_nume = $_POST['signup_nume'];
    $signup_email = $_POST['signup_email'];
    if ($_POST['signup_pass'] !== $_POST['signup_pass2'])
        $signup_result = 'wrong_pass2';
    else if ($_POST['signup_accept'] !== "yes")
        $signup_result = 'no_accept';
    else {
        $stmt = $bazadate -> prepare("SELECT email FROM biblioteca_useri WHERE email = ?");
        $stmt -> bind_param("s", $signup_email);
        $stmt -> execute();
        $result = $stmt -> get_result();
        if ($result -> num_rows > 0)
            $signup_result = 'already_exists';
    }

    if (!isset($signup_result)) {
        $stmt = $bazadate -> prepare("INSERT INTO biblioteca_useri (id, email, parola, tip, nume) VALUES(?, ?, ?, 'user', ?)");
        $user_random_id = random_string();
        $password_hashed = password_hash($_POST['signup_pass'], PASSWORD_DEFAULT);
        $stmt -> bind_param("ssss", $user_random_id, $signup_email, $password_hashed, $signup_nume);
        if ($stmt -> execute()) {
            // Incepem sesiunea
            $_SESSION['user_id'] = $user_random_id;
            $_SESSION['user_email'] = $signup_email;
            $_SESSION['user_tip'] = 'user';
            $_SESSION['user_nume'] = $signup_nume;

            $signup_result = 'ok';
            redirect('cont.php');
        } else
            $signup_result = 'sql_error';
    }
}

$titlu_pagina = "Înregistrează-te";

include_once('template_2.php');

?>

<div id="continut">
    <h1 class="al-center border-none">Înregistrează-te</h1>

    <div class="sub">
        <div class="cards" id="alert_cards">
            <?php if (isset($signup_result)) { ?>
            <div class="card alertbox <?php if ($signup_result == 'ok') echo 'btn-green'; else echo 'btn-red'; ?>" style="width: 320px;">
                <iconify-icon icon="fluent:error-circle-16-regular" width="24"></iconify-icon>
                <label><?php 
                if ($signup_result == 'wrong_pass2')
                    echo 'Parolele nu se potrivesc!';
                else if ($signup_result == 'no_accept')
                    echo 'Trebuie să accepți Termenii și Condițiile!';
                else if ($signup_result == 'already_exists')
                    echo 'Un cont cu aceeași adresă de e-mail există!';
                else if ($signup_result == 'sql_error')
                    echo 'Eroare de sistem la înregistrare!';
                else if ($signup_result == 'ok')
                    echo 'Cont înregistrat cu succes, te redirecționăm către pagina contului...';
                ?></label>
            </div>
            <?php } ?>
        </div>
        <div class="cards">
            <div class="card" style="width: 320px">
                <form action="" method="POST" onsubmit="return checkPwd()">
                    <input type="hidden" name="signup_form">
                    <div class="form-field al-left">
                        <label>Nume</label>
                        <input
                            class="textbox display-block width-100"
                            type="text"
                            name="signup_nume"
                            id="signup_nume"
                            required
                        />
                    </div>
                    <div class="form-field al-left">
                        <label>Adresa E-mail</label>
                        <input
                            class="textbox display-block width-100"
                            type="email"
                            name="signup_email"
                            id="signup_email"
                            required
                        />
                    </div>
                    <div class="form-field al-left">
                        <label>Parola</label>
                        <input
                            class="textbox display-block width-100"
                            type="password"
                            minlength="8"
                            name="signup_pass"
                            id="signup_pass"
                            required
                        />
                    </div>
                    <div class="form-field al-left">
                        <label>Confirmă Parola</label>
                        <input
                            class="textbox display-block width-100"
                            type="password"
                            minlength="8"
                            name="signup_pass2"
                            id="signup_pass2"
                            required
                        />
                    </div>
                    <div class="form-field al-left">
                        <label>
                            <input type="checkbox" name="signup_accept" value="yes" required />
                            Accept <a href="#">Termenii și Condițiile</a> platformei.
                        </label>
                    </div>
                    <div class="form-field al-center margin-0">
                        <button type="submit" class="btn btn-green">Înregistrare</button>
                        <a href="autentificare.php" class="btn">Ai deja cont?</a>
                    </div>
                </form>
                <script>
                    function checkPwd() {
                        if ($("#signup_pass").val() != $("#signup_pass2").val()) {
                            $("#alert_cards").html('<div class="card alertbox btn-red" style="width: 320px;"><iconify-icon icon="fluent:error-circle-16-regular" width="24"></iconify-icon><label>Parolele nu se potrivesc!</label></div>');
                            return false;
                        }
                        return true;
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<?php include_once('template_3.php'); ?>
