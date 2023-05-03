<?php 

include_once('template_1.php');

$titlu_pagina = "Împrumuturi";

include_once('template_2.php');

?>

<div id="continut">
    <h1>Împrumuturi</h1>
    <div class="table" style="text-align: center">
        <table>
            <thead>
                <tr>
                    <th>Cod Împrumut</th>
                    <th>Carte</th>
                    <th>Utilizator</th>
                    <th>Data împrumutului</th>
                    <th>Data restanță</th>
                    <th>Stare</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>012345abc</td>
                    <td><a href="carte.php?id="></a></td>
                    <td>Nume Utilizator</td>
                    <td>01.01.2023</td>
                    <td>03.01.2023</td>
                    <td>In Asteptare</td>
                    <td><a href="imprumut.php?finalizare="><button class="btn">Finalizează</button></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include_once('template_3.php'); ?>
