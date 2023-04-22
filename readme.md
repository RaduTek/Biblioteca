# Biblioteca Mea

## Despre proiect

Biblioteca Mea este o aplicație web pentru administrarea și organizarea unei biblioteci. Ea permite utilizatorilor să exploreze catalogul de cărți, să salveze cărțile preferate și să realizeze un împrumut online.

## Configurare

- Fișierul `model_db.sql` trebuie importat în baza de date (fie MySQL, fie MariaDB).
- Datele de conectare la baza de date trebuie amplasate într-un fișier numit `env.php` după acest model:

```
<?php
$db_user = "User_Baza_De_Date";
$db_passwd = "Parola_Contului";
$db_name = "Nume_Baza_De_Date";
?>
```
