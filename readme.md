![Logo](/files/logo.png)

# Despre proiect

Biblioteca Mea este o aplicație web pentru administrarea și organizarea unei biblioteci. Ea permite utilizatorilor să exploreze catalogul de cărți, să salveze cărțile preferate și să realizeze un împrumut online.

# Configurare

- Fișierul `model_db.sql` trebuie importat în baza de date (fie MySQL, fie MariaDB).
- Datele de conectare la baza de date trebuie amplasate într-un fișier numit `env.php` după acest model:

```
<?php
$db_user = "User_Baza_De_Date";
$db_passwd = "Parola_Contului";
$db_name = "Nume_Baza_De_Date";
?>
```

# Design-ul aplicației

Tot stilul CSS pentru site a fost realizat de la zero pentru a crea un stil vizual unic. Este utilizat fontul Segoe UI Variable și iconițele Fluent Design create de Microsoft pentru a oferi aplicației un design modern, simplu și aerisit.

_Pagina principală când utilizatorul nu este autentificat_

![Pagina principala](/files/screenshot/pagina_principala.png)

Pagina este formată din mai multe secțiuni:

- Header-ul conține o casetă de căutare a cărților în bibliotecă.
- Sidebar-ul conține o listă cu categoriile de cărți
- Conținutul paginii

## Header-ul

Header-ul are un design responsive, intuitiv și pentru ecrane mari și pentru ecrane mici. Pe ecrane de lățime mică, caseta de căutare se ascunde automat, iar în schimbul ei apare un buton cu o iconiță de căutare descriptivă.

![Caseta ascunsă](/files/screenshot/navbar1.png)

La apăsarea butonului de căutare, caseta de căutare înlocuiește conținutul navbar-ului temporar.

![Caseta vizibilă](/files/screenshot/navbar2.png)

Butonul X poate fi apăsat pentru a ascunde modul de căutare și a reveni la navbar-ul normal.

## Sidebar-ul

Sidebar-ul este vizibil permanent pe ecrane de lățime suficient de mare pentru a afișa conținutul paginii în lățimea completă și cu destul spațiu rămas pentru sidebar.

Pe ecrane de lățime mică acesta se ascunde automat, iar un buton pentru deschiderea acestuia apare în stânga siglei "Biblioteca Mea".

![Sidebar vizibil](/files/screenshot/sidebar1.png)

Apariția și dispariția acestui sidebar este animată folosind CSS, iar conținutul paginii este acoperit de un container estompat. La apăsarea spațiului estompat sidebar-ul este închis automat.

## Conținutul

Conținutul se adaptează lățimii pentru a preveni apariția scroll-ului orizontal și pentru a facilita utilizarea aplicației pentru dispozitive mobile.

_Pagina principală pe un dispozitiv mobil_

![Pagina principală pe un dispozitiv mobil](/files/screenshot/mobil1.png)

_Lista de cărți pe un dispozitiv desktop_

![Lista de cărți pe un dispozitiv desktop](/files/screenshot/complet2.png)

_Lista de cărți pe un dispozitiv mobil_

![Lista de cărți pe un dispozitiv mobil](/files/screenshot/mobil2.png)

# Explorarea bibliotecii

_Pagina unei cărți_

![Pagina unei cărți](/files/screenshot/carte1.png)

# Contul utilizatorului

Când utilizatorul nu este autentificat, butonul din dreapta de pe navbar afișează mesajul "Autentifică-te". La apăsarea acestuia utilizatorul este redirecționat către pagina de autentificare.

![Autentificare](/files/screenshot/autentificare.png)

Dacă persoana care accesează această pagină nu deține un cont, ea poate să își creeze un cont nou folosind pagina de înregistrare accesibilă prin butonul "Nu ai cont?".

![Înregistrare](/files/screenshot/inregistrare.png)

Când utilizatorul este autentificat, el are acces la pagina de cont:

![Cont](/files/screenshot/cont1.png)

Utilizatorul poate modifica diferite setări ale contului:

![Setările contului](/files/screenshot/cont_setari.png)

Opțiuni relevante doar pentru utilizatorii autentificați apar în sidebar:

![Sidebar pentru utilizator autentificat](/files/screenshot/sidebar2.png)

# Interacțiuni cu biblioteca

Utilizatorul poate salva cărți pe care ar dori să le împrumute în viitor într-o listă de cărți salvate printr-un buton ce apare lângă carte fie în listă, fie în pagina cărții.

![Cărți salvate](/files/screenshot/salvate.png)

Utilizatorul poate iniția un împrumut prin apăsarea butonului "Împrumută", atașat fiecărei cărți în listă sau în pagina cărții.

![Împrumută](/files/screenshot/imprumut1.png)

După ce utilizatorul specifică data în care dorește ca împrumutul să înceapă și în care să se termine el este direcționat către pagina contului, unde este vizibilă o listă cu împrumuturile active.

![De înapoiat](/files/screenshot/imprumut2.png)

După ce un împrumut este marcat de administrator ca finalizat sau anulat, acesta este mutat în pagina de cărți citite.

![Citite](/files/screenshot/imprumut3.png)

# Contul administrativ

Un cont cu drepturi de administrator are acces la funcții pentru administrarea cărților din baza de date și a împrumuturilor.

_Opțiuni extra în sidebar_

![Sidebar administrativ](/files/screenshot/admin1.png)

_Administrare împrumuturilor active_

![Împrumuturi active](/files/screenshot/admin2.png)

_Adăugarea unei cărți_

![Adaugă o carte](/files/screenshot/admin3.png)

_Opțiuni administrative pe pagina cărții_

![Admin Carte](/files/screenshot/admin4.png)

_Modificarea unei cărți_

![Modifică o carte](/files/screenshot/admin5.png)

_Ștergerea unei cărți_

![Șterge o carte](/files/screenshot/admin6.png)
