<?php 

include_once('template_1.php'); 

include_once('template_2.php'); 

?>

<div id="continut">
    <div class="sub al-center" style="margin-bottom: 50px;">
        <h1 style="font-size: 36pt;">
            <?php $titlu_aplicatie_x = explode(' ', $titlu_aplicatie); ?>
            <b><?php echo array_shift($titlu_aplicatie_x);; ?></b>
            <span> <?php echo implode(' ', $titlu_aplicatie_x);?></span>
        </h1>
        <h2 style="border: none;">Împrumută o carte aici, rapid și eficient.</h2>
        <p>
            <a href="carti.php"><button style="font-size: 14pt;" class="btn btn-green">
                <label>Explorează Biblioteca Acum</label>
                <iconify-icon icon="fluent:arrow-right-16-filled" width="24" style="margin-left: 0;"></iconify-icon>
            </button></a>
        </p>
    </div>
    <div class="sub">
        <h1 class="al-center" style="border: none;">Statistici</h1>
        <div class="cards hoverable fixed-width">
            <div class="card">
                <h1>
                    <?php
                    $result = $bazadate->query("SELECT COUNT(id) FROM biblioteca_useri");
                    if ($row = $result->fetch_assoc())
                        echo $row['COUNT(id)'];
                    ?>
                </h1>
                <h2>Utilizatori</h2>
            </div>
            <div class="card">
                <h1>
                    <?php
                    $result = $bazadate->query("SELECT COUNT(id) FROM biblioteca_carti");
                    if ($row = $result->fetch_assoc())
                        echo $row['COUNT(id)'];
                    ?>
                </h1>
                <h2>Cărți</h2>
            </div>
            <div class="card">
                <h1>
                    <?php
                    $result = $bazadate->query("SELECT COUNT(id) FROM biblioteca_imprumuturi");
                    if ($row = $result->fetch_assoc())
                        echo $row['COUNT(id)'];
                    ?>
                </h1>
                <h2>Împrumuturi</h2>
            </div>
        </div>
    </div>
</div>

<?php include_once('template_3.php'); ?>
