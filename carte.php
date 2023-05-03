<?php 

include_once('template_1.php');

$titlu_pagina = "Carte";

$row = 0;
if (isset($_GET['id'])) {
    $c_id = $_GET['id'];
    $stmt = $bazadate -> prepare("SELECT biblioteca_carti.*, biblioteca_categorii.nume, biblioteca_salvate.id_user FROM biblioteca_carti 
        INNER JOIN biblioteca_categorii ON biblioteca_carti.categorie=biblioteca_categorii.id 
        LEFT JOIN biblioteca_salvate ON biblioteca_carti.id = biblioteca_salvate.id_carte
        WHERE biblioteca_carti.id = ? and biblioteca_carti.categorie = biblioteca_categorii.id");
    $stmt -> bind_param("s", $c_id);
    $stmt -> execute();
    $c_data;
    $result = $stmt -> get_result();
    if ($row = $result -> fetch_assoc()) {
        $c_data = $row;
        $titlu_pagina = $c_data['titlu'] . " - " . $c_data['autor'];
    } else {
        $page_msg = 'notfound';
    }
}

include_once('template_2.php');

?>

<div id="continut">
    <?php if (isset($_SESSION['result_msg'])) {
        if ($_SESSION['result_msg'] == "edit_ok") { ?>
            <div class="alertbox btn-green">Cartea a fost modificată cu succes!</div><br/>

        <?php } else if ($_SESSION['result_msg'] == "edit_fail") { ?>
            <div class="alertbox btn-red">Eroare la modificarea cărții!</div><br/>
        <?php }
        unset($_SESSION['result_msg']);
    } ?>
    <?php if(isset($page_msg) && $page_msg == 'notfound') { ?> 
        <h1>Carte</h1>
        <div style="text-align: center"> 
            <h2 style="border: none">Această carte nu există.</h2>
            <p>Cartea cu ID-ul specificat nu există în baza de date.</p>
        </div>
    <?php } else { ?>
    <h1><?php echo $c_data['titlu']; ?></h1>
    <div class="split-page" style="padding-top: 20px">
        <div class="split-small cards cards-vertical">
            <div class="card" style="padding: 10px">
                <img id="bookImg" src="<?php if (strlen($c_data['imagine']) > 1) echo $c_data['imagine']; else echo 'imagini/book-cover-placeholder.png'; ?>" />
                <table class="statTable">
                    <tbody>
                        <tr>
                            <td><iconify-icon icon="fluent:person-20-regular" width="20"></iconify-icon></td>
                            <td><?php echo $c_data['autor']; ?></td>
                        </tr>
                        <tr>
                            <td><iconify-icon icon="fluent:book-20-regular" width="20"></iconify-icon></td>
                            <td><a href="carti.php?categorie=<?php echo $c_data['categorie']; ?>"><?php echo $c_data['nume']; ?></a></td>
                        </tr>
                        <tr>
                            <td><iconify-icon icon="fluent:style-guide-20-regular" width="20"></iconify-icon></td>
                            <td><?php echo $c_data['isbn']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card" style="padding: 10px">
                <table class="statTable">
                    <tbody>
                        <tr>
                            <?php if (isset($c_data['id_user']) && isset($_SESSION['user_id']) && $c_data['id_user'] == $_SESSION['user_id']) { ?>
                            <td><iconify-icon icon="fluent:bookmark-off-20-regular" width="20"></iconify-icon></td>
                            <td><a href="cont.php?elimina=<?php echo $c_id; ?>">Elimină</a></td>
                            <?php } else { ?>
                            <td><iconify-icon icon="fluent:bookmark-20-regular" width="20"></iconify-icon></td>
                            <td><a href="cont.php?salveaza=<?php echo $c_id; ?>">Salvează</a></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td><iconify-icon icon="fluent:arrow-square-down-20-regular" width="20"></iconify-icon></td>
                            <td><a href="imprumut.php?carte=<?php echo $c_id; ?>">Împrumută</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php if (isset($_SESSION['user_tip']) && $_SESSION['user_tip'] = 'admin') { ?>
            <div class="card" style="padding: 10px">
                <table class="statTable">
                    <tbody>
                        <tr>
                            <td><iconify-icon icon="fluent:pen-20-regular" width="20"></iconify-icon></td>
                            <td><a href="adaugare.php?editeaza=<?php echo $c_id; ?>">Editează</a></td>
                        </tr>
                        <tr style="color: red">
                            <td><iconify-icon icon="fluent:delete-20-regular" width="20"></iconify-icon></td>
                            <td><a style="color: red" href="adaugare.php?sterge=<?php echo $c_id; ?>">Șterge</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
        <div class="split-large padding">
            <h2>Descriere</h2>
            <?php echo $c_data['descriere']; ?>
        </div>
    </div>
    <?php } ?>
</div>

<?php include_once('template_3.php'); ?>
