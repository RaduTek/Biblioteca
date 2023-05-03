<?php 

include_once('template_1.php');

$titlu_pagina = "Împrumuturi";

$page_msg = "ok";

// Stari:

$stari = [
    "asteptare" => "În așteptare",
    "predat" => "Predat",
    "anulat" => "Anulat",
    "returnat" => "Returnat",
];

function modifica_stare_imprumut($id, $stare) {
    global $bazadate;
    $stmt = $bazadate -> prepare("UPDATE biblioteca_imprumuturi SET stare = ? WHERE id = ?");
    $stmt -> bind_param("ss", $stare, $id);
    $status = $stmt -> execute();
    $page_msg = "modificare_stare_" . ($status ? "ok" : "fail");
}

if (isset($_SESSION['user_tip']) && $_SESSION['user_tip'] = 'admin') {

    if (isset($_GET['preda'])) {
        modifica_stare_imprumut($_GET['preda'], 'predat');
    } else if (isset($_GET['anuleaza'])) {
        modifica_stare_imprumut($_GET['anuleaza'], 'anulat');
    } else if (isset($_GET['returneaza'])) {
        modifica_stare_imprumut($_GET['returneaza'], 'returnat');
    }

}

include_once('template_2.php');

?>

<div id="continut">
<?php if (isset($_SESSION['user_tip']) && $_SESSION['user_tip'] = 'admin') { ?>

    <h1>Împrumuturi active</h1>
    <div class="table" style="text-align: center">
        <table>
            <thead>
                <tr>
                    <th>Carte</th>
                    <th>Utilizator</th>
                    <th>Data împrumutului</th>
                    <th>Data restanță</th>
                    <th>Stare</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $stmt = $bazadate -> prepare("SELECT biblioteca_imprumuturi.*, biblioteca_useri.nume AS nume_user, biblioteca_carti.titlu AS titlu_carte FROM biblioteca_imprumuturi 
                    INNER JOIN biblioteca_useri ON biblioteca_imprumuturi.user=biblioteca_useri.id 
                    INNER JOIN biblioteca_carti ON biblioteca_imprumuturi.carte=biblioteca_carti.id 
                    WHERE biblioteca_imprumuturi.stare != 'returnat' AND biblioteca_imprumuturi.stare != 'anulat'
                    ORDER BY biblioteca_imprumuturi.id");
                $stmt -> execute();
                $result = $stmt -> get_result();
                while ($row = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td><a href="carte.php?id=<?php echo $row['carte']; ?>"><?php echo $row['titlu_carte']; ?></a></td>
                    <td><?php echo $row['nume_user']; ?></td>
                    <td><?php echo $row['inceput']; ?></td>
                    <td><?php echo $row['sfarsit']; ?></td>
                    <td><?php echo $stari[$row['stare']]; ?></td>
                    <td>
                        <?php if($row['stare'] == 'predat') { ?>
                            <a href="imprumuturi.php?returneaza=<?php echo $row['id']; ?>"><button class="btn">Returnează</button></a>
                        <?php } else { ?> 
                            <a href="imprumuturi.php?preda=<?php echo $row['id']; ?>"><button class="btn">Predă</button></a>
                        <?php } ?>
                        <a href="imprumuturi.php?anuleaza=<?php echo $row['id']; ?>"><button class="btn">Anulează</button></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    
<?php } else { ?>
    <div class="alertbox btn-red">Trebuie să fiți autentificat cu un cont administrativ pentru a avea acces la această funcție!</div><br/>
<?php } ?>
</div>

<?php include_once('template_3.php'); ?>
