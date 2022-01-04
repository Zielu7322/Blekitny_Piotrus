<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Czarter jachtów Błękitny Piotruś - rezerwacja</title>
        <link rel="stylesheet" href="main.css">
        <style></style>
    </head>
    
    <body>
        <heder>
            <div>
            <a href="index.html">
            <img id="logo" src="grafika/logo-blekitny-piotrus.jpg" alt="logo firmy Błękitny Piotruś"></a>
            <h1 id="haslo">  PASJA • SPORT • EMOCJE | Jakość w żeglarstwie!</h1>
            <section>
                <nav>
                    <ul class=ulm>
                        <li class=lim><a href="o_nas.html">O firmie</a></li>
                        <li class=lim><a href="srodladowe.html">Jachty śródlądowe</a></li>
                        <li class=lim><a href="morskie.html">Jachty morskie</a></li>
                        <li class=lim><a href="kontakt.html">Kontakt</a></li>
                </nav>
            </section>
            </div>
            <br><br>     
        </heder>

<?php
$mysqli = new mysqli('127.0.0.1','struser','password','czarter') or die('Błąd połączenia do bazy danych');                     
if ($mysqli->connect_errno){
	   die ('Błąd połączenia: (' . $mysqli->connect_errno . ') ');	
	}



$imie=$_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$email = $_POST['email'];
$telefon = $_POST['telefon'];
$jachty = $_POST['jachty'];
$dataod = $_POST['dataod'];
$datado = $_POST['datado'];

$wynikdatar = $mysqli->query("SELECT CURDATE()");
$datarr = $wynikdatar->fetch_row();
$datar = $datarr[0];

switch($jachty){
    case "Twister 780 komfort - Mazury" : 
        $j="J1"; $idj=1;
        break;
    case "Altra 27 - Mazury" :
        $j="J2"; $idj=2;
        break;
    case "Raptor 26 komfort - Mazury" :
        $j="J3"; $idj=3;
        break;
    case "Bonawentura - Morze" :
        $j="J4"; $idj=4;
        break;
    case "Bavaria 51 - Morze" :
        $j="J5"; $idj=5;
        break;
    case "Hanse 348 - Morze" :
        $j="J6"; $idj=6;
        break;
    default :
    echo'błędne dane';
    }

/* Czy wolny jacht*/
$sql2="SELECT COUNT(*) as licznik FROM wypo WHERE IDJACHTY='$idj' AND (('$dataod'<=DATAW AND '$datado'>=DATAW) OR ('$dataod'>DATAW AND '$dataod'<=DATAZ))";
$wynik2 = $mysqli->query($sql2);
$wiersz2 = $wynik2->fetch_assoc();
/*            echo $sql2;
echo '<p> Liczba rezerwacji: '.$wiersz2['licznik'].'</p>';*/

if ($dataod>$datado) {$wiersz2['licznik']=1;}
            
if ($wiersz2['licznik'] ==0) {
/*Rezerwacja*/
$idk= ("select max(idklienta) as idk from klienci");
$wynikid = $mysqli->query($idk);
$idn = $wynikid->fetch_row();
$idnowe=$idn[0]+1;

$sql3 = "INSERT INTO klienci (idklienta, imie, nazwisko, email, telefon) values ($idnowe,'".$imie."', '".$nazwisko."', '".$email."', '".$telefon."')";
$wynik3 = $mysqli->query($sql3);
/*if ($wynik3) echo 'Liczba rekordów dodanych do bazy:'.$mysqli->affected_rows;*/
$sql4 = "INSERT INTO wypo (idklienta, idjachty, dataw, dataz, datar) values ($idnowe,$idj, '".$dataod."', '".$datado."', '".$datar."')";
$wynik4 = $mysqli->query($sql4);
/*if ($wynik4) echo 'Liczba rekordów dodanych do bazy:'.$mysqli->affected_rows;*/

/* Kwota do zapłaty*/    
$sql1= "SELECT SUM($j) as cena FROM cennik WHERE DATA BETWEEN '$dataod' AND '$datado'";   
$wynik1 = $mysqli->query($sql1);
$wiersz1 = $wynik1->fetch_assoc();
$zaliczka = $wiersz1['cena']/5;    
echo '<p class=jachtytytul><br><br>Dokonano rezerwacji '.$jachty.' na okres od '.$dataod.' do '.$datado.'.<br>
Kwota do zapłaty za czarter: '.$wiersz1['cena'].' złotych.<br><br>
Kwota zaliczki (20%): '.$zaliczka.' złotych.<br>
Wpłaty zaliczki proszę dokonać na konto: 02 1240 0000 1111 0000 1234 5678 w terminie 7 dni.</p>';

} else echo '<p class=jachtytytul><br><br>Rezerwacja niemożliwa - proszę zmienić daty czarteru.</p>';

/*
$imie->free();
$nazwisko->free();
$email->free();
$telefon->free();
$jachty->free();
$dataod->free();
$datado->free();
$wynikdatar->free();
$datarr->free();
$datar->free();
$sql2->free();
$wynik2->free();
$wiersz2->free();
$idk->free();
$wynikid->free();
$idn->free();
$idnowe->free();
$sql3->free();
$wynik3->free();
$sql4->free();
$wynik4->free();
$sql1->free();
$wynik1->free();
$wiersz1->free();*/
$mysqli -> close();

?>
            
            
                </body>
</html>