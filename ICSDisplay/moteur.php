<?php
session_start(); 
if (!isset($_GET["debut"])){
	$debut = '-09-00-00';
} else {
	$debut = $_GET["debut"];	
}
if (!isset($_GET["fin"])){
	$fin = '-23-42-00';
} else {
	$fin = $_GET["fin"];	
}
$url = $_GET["lien"];
$salle = $_GET["salle"];
$message = $_GET["message"];
$fin = $_GET["fin"];
$debut = $_GET["debut"];
date_default_timezone_set('Europe/Paris');
$date_instant_t = date("Y-m-d-H-i-s");
$date_jour =  date('Y-m-d');
unlink('./base.db');
create_db();
recuperer_evenements_ics2bdd($url);
$tableau_instant_T = recuperer_evenements_bdd2tab($date_instant_t, $salle);
$tableau_instant_jour = recuperer_evenements_jour($date_jour, $salle);
var_dump($_SESSION);
function create_db()
{
    $dbname='./base.db';

    if(!class_exists('SQLite3'))
        die("SQLite 3 n'est pas supporté .\n");

    $base = new SQLite3($dbname);
    $query = "CREATE TABLE evenement (
                ID INTEGER PRIMARY KEY AUTOINCREMENT,
                DTSTART datetime,
                DTEND datetime,
                DTSTAMP datetime,
                ATTENDEE VARCHAR(255),
                UID VARCHAR(255),
                CREATED VARCHAR(255),
                DESCRIPTION VARCHAR(255),
                LASTMODIFIED VARCHAR(255),
                LOCATION VARCHAR(255),
                SEQUENCE VARCHAR(255),
                TRANSP VARCHAR(255),
                STATUS VARCHAR(255),
                SUMMARY VARCHAR(255)
                )";
    $base->exec($query);
}
function ecrire_db (
    $DTSTART,
    $DTEND,
    $DTSTAMP,
    $ATTENDEE,
    $UID,
    $CREATED,
    $DESCRIPTION,
    $LASTMODIFIED,
    $LOCATION,
    $SEQUENCE,
    $TRANSP,
    $STATUS,
    $SUMMARY
)
{
    $base = new SQLite3('./base.db');

    if (!$base) die ($error);

    $query = "INSERT INTO evenement
    (
        DTSTART,
        DTEND,
        DTSTAMP,
        ATTENDEE,
        UID,
        CREATED,
        DESCRIPTION,
        LASTMODIFIED,
        LOCATION,
        SEQUENCE,
        TRANSP,
        SUMMARY
    )
    VALUES
    (
        '$DTSTART',
        '$DTEND',
        '$DTSTAMP',
        '$ATTENDEE',
        '$UID',
        '$CREATED',
        '$DESCRIPTION',
        '$LASTMODIFIED',
        '$LOCATION',
        '$SEQUENCE',
        '$TRANSP',
        '$SUMMARY'
    )";
    $base->exec($query);

}
function formater_date($date)
{

    $annee = substr($date, 0, 4);
    $mois = substr($date, 4, 2);
    $jour = substr($date, 6, 2);
    $heure = substr($date, 9, 2);
    $minute = substr($date, 11, 2);
    $seconde = substr($date, 13, 2);
    $date_formater = $annee. "-" . $mois . "-" . $jour . "-" . $heure . "-" . $minute . "-" . $seconde;
    return ($date_formater);
}
function recuperer_evenements_ics2bdd($url)
{


    if ($url == "")
	    echo "ATTENTION PAS DE CALENDRIER D INSERER";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION,3);
	$data = curl_exec ($ch);
	$error = curl_error($ch); 
	curl_close ($ch);
	
	$destination = "./basic.ics";
	$file = fopen($destination, "w+");
	fputs($file, $data);
	fclose($file);

	$ics = file_get_contents('./basic.ics');
    $regex_evenement =  '/(BEGIN:VEVENT[\s\S]*?END:VEVENT)/';
    $regex_DTSTART =  '/DTSTART:(.+)\b/';
    $regex_DTEND =   '/DTEND:(.+)\b/';
    $regex_DTSTAMP =  '/DTSTAMP:(.+)\b/';
    $regex_ATTENDEE =  '/ACCEPTED;CN=([\w\s\n\-]+)/';
    $regex_UID =   '/UID:(.+)\b/';
    $regex_CREATED =  '/CREATED:(.+)\b/';
    $regex_DESCRIPTION = '/DESCRIPTION:(.+)\b/';
    $regex_LASTMODIFIED = '/LAST-MODIFIED:(.+)\b/';
    $regex_LOCATION =   '/LOCATION:(.+)\b/';
    $regex_SEQUENCE =   '/SEQUENCE:(.+)\b/';
    $regex_STATUS =   '/STATUS:(.+)\b/';
    $regex_SUMMARY =   '/SUMMARY:(.+)\b/';
    $regex_TRANSP =   '/TRANSP:(.+)\b/';

    preg_match_all($regex_evenement, $ics, $tab_evenement);
    foreach ($tab_evenement[0] as $id => $evenement)
    {
        preg_match($regex_DTSTART, $evenement, $DTSTART);
        preg_match($regex_DTEND, $evenement, $DTEND);
        preg_match($regex_DTSTAMP, $evenement, $DTSTAMP);
        preg_match($regex_ATTENDEE, $evenement, $ATTENDEE);
        preg_match($regex_UID, $evenement, $UID);
        preg_match($regex_CREATED, $evenement, $CREATED);
        preg_match($regex_DESCRIPTION, $evenement, $DESCRIPTION);
        preg_match($regex_LASTMODIFIED, $evenement, $LASTMODIFIED);
        preg_match($regex_LOCATION, $evenement, $LOCATION);
        preg_match($regex_SEQUENCE, $evenement, $SEQUENCE);
        preg_match($regex_STATUS, $evenement, $STATUS);
        preg_match($regex_SUMMARY, $evenement, $SUMMARY);
        preg_match($regex_TRANSP, $evenement, $TRANSP);
        
        $ATTENDEE_clean = str_replace("\n", "", $ATTENDEE[1]);
        ecrire_db
        (
            formater_date($DTSTART[1]),
            formater_date($DTEND[1]),
            formater_date($DTSTAMP[1]),
            $ATTENDEE_clean,
            $UID[1],
            formater_date($CREATED[1]),
            $DESCRIPTION[1],
            formater_date($LASTMODIFIED[1]),
            $LOCATION[1],
            $SEQUENCE[1],
            $TRANSP[1],
            $STATUS[1],
            $SUMMARY[1]
        );
    }
    
}
function recuperer_evenements_full()
{
    $base = new SQLite3('./base.db');
    //return($base->querySingle("SELECT * FROM evenement where " . $date_du_jour . " between DTSTART AND DTEND"));
    $base->exec("SELECT * FROM evenement");
    $result     = $base->query("SELECT * FROM evenement");
    $indice = 0;
    while($row = $result->fetchArray(SQLITE3_ASSOC) ){
        $tab_final[$indice] = $row;
        $indice ++;
    }
    return($tab_final);

}
function recuperer_evenements_bdd2tab($date_du_jour, $salle)
{
    $base = new SQLite3('./base.db');
    //return($base->querySingle("SELECT * FROM evenement where " . $date_du_jour . " between DTSTART AND DTEND"));
    $base->exec("SELECT * FROM evenement where '" . $date_du_jour . "' between DTSTART AND DTEND AND LOCATION LIKE '%". $salle . "%'");
    $result     = $base->query("SELECT * FROM evenement where '" . $date_du_jour . "' between DTSTART AND DTEND AND LOCATION LIKE '%". $salle . "%'");
    $indice = 0;
    while($row = $result->fetchArray(SQLITE3_ASSOC) ){
        $tab_final[$indice] = $row;
        $indice ++;
    }
    return($tab_final);
}
function recuperer_evenements_jour($date_jour, $salle)
{
    $base = new SQLite3('./base.db');
    $base->exec("SELECT * FROM evenement where DTEND BETWEEN '" . $date_jour . "' AND '". $date_jour ."-23-59-59' AND LOCATION LIKE '%". $salle . "%' ORDER BY DTEND");
    $result = $base->query("SELECT * FROM evenement where DTEND BETWEEN '" . $date_jour . "' AND '". $date_jour ."-23-59-59' AND LOCATION LIKE '%". $salle . "%' ORDER BY DTEND");
    $indice = 0;
    while($row = $result->fetchArray(SQLITE3_ASSOC) ){
        $tab_final[$indice] = $row;
        $indice ++;
    }
    return($tab_final);
}

?>
<!DOCTYPE html>

<html>
<head>
    <title>Agenda Salle</title>
    <meta name="keywords" content="HTML, CSS, XML, XHTML, JavaScript">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/full.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
	<script type="text/javascript" src="js/date.js"></script>
	<META HTTP-EQUIV="Refresh" CONTENT="30;URL=moteur.php?debut=<?php echo $_GET['debut']; ?>&fin=<?php echo $_GET['fin']; ?>&lien=<?php echo $_GET['lien']; ?>&salle=<?php echo $_GET['salle']; ?>&message=<?php echo $_GET['message']; ?>"> 
</head>

<body onload="date_heure('date_heure');">

    <div id="bloc1">

        <div id="date">
            <div id="jour"></div>

            <div id="mois"></div>

            <div id="annee"></div>
        </div>

        <div id="salle">
            <div class="mito">
                <?php
           
                    echo $salle;
                    ?>
                
            </div>

            <div id="heure">
                <span id="date_heure"></span>
            </div>
        </div>


				<div id="prof">
					<div id="cont_OTQwNDF8MXwyfDJ8NXwyODdhZWR8MXxGRkZGRkZ8Y3wx"><div id="spa_OTQwNDF8MXwyfDJ8NXwyODdhZWR8MXxGRkZGRkZ8Y3wx"><a id="a_OTQwNDF8MXwyfDJ8NXwyODdhZWR8MXxGRkZGRkZ8Y3wx" href="http://www.meteocity.com/france/ivry-sur-seine_v94041/" rel="nofollow" target="_blank" style="text-decoration:none; color:#5b5d6e;padding-top:10%;">Météo </a></div><script type="text/javascript" src="http://widget.meteocity.com/js/OTQwNDF8MXwyfDJ8NXwyODdhZWR8MXxGRkZGRkZ8Y3wx"></script></div>
				</div>

         <div id="message">
            <MARQUEE  WIDTH="50%" Height="80%" DIRECTION="up" LOOP="infinite">
                 <p><div id="attention"><img src="images/exclam.png"></div><br\><?php echo $message; ?></p>
            </MARQUEE>   
        </div>

        <div id="liste">
                    <h4>Login étudiants concernés</h4>

        <MARQUEE bgcolor=""WIDTH="100%" DIRECTION="DOWN" LOOP="infinite">
                <?php
                echo '<p>';
	            foreach ($tableau_instant_T as $val)
	            {
	                if ($val["ATTENDEE"] != "\n") {
	                    echo $val["ATTENDEE"] . '<br />';
	                }
	            }
	            echo '</p>';
	            ?>
		</MARQUEE>
        </div>

        <div id="cours">

            <div class="text">

                <?php
                				echo '<p>';

	            foreach ($tableau_instant_T as $val)
	            {
	                if ($val["SUMMARY"] != "\n") {
						$debut = explode("-", $val["DTSTART"]);
			            $fin = explode("-", $val["DTEND"]);
	                    echo $val["SUMMARY"] . ': ' . $debut[3] . 'h' . $debut[4] . '-' . $fin[3] . 'h' . $fin[4] . '<br />';
	                }
	            }
	            echo '</p>';
	            ?>
            </div>
        </div>

        <div id="slidershow">

						<?php
			                foreach ($tableau_instant_jour as $id => $val)
			                {
			                    $debut = explode("-", $val["DTSTART"]);
			                    $fin = explode("-", $val["DTEND"]);                             
			                    echo '<h2 class="frame-'. $id .'">'. $val["SUMMARY"] . ': ' . $debut[3] . 'h' . $debut[4] . '-' . $fin[3] . 'h' . $fin[4] .'</h2>' ;
			                }
			            ?>
        </div>
        <div id="logo"><img src="images/logo.png">

        </div>
    </div>
</body>
</html>