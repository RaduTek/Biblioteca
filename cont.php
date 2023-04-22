<?php 

include_once('template_1.php');

$titlu_pagina = "Contul Meu";

if (!isset($_SESSION['user_id'])) {
    redirect('autentificare.php');
}

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
        </div>
    </div>
</div>

<?php include_once('template_3.php'); ?>
