<?php
session_start(); 
?>
<!DOCTYPE html>

<html>
<head>
    <title>Formulaire</title>
    <meta name="keywords" content="HTML, CSS, XML, XHTML, JavaScript">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="css/formu.css">
    
</head>
<body>
<form action="./moteur.php"  method="GET">	
	<LABEL for="debut">Heure du début de journée (ex: 9H00 ==> 09-00-00)</LABEL>
	<input type="text" name="debut"><br/>
	<LABEL for="fin">Heure de fin de journée: (ex: 23H42 ==> 23-42-00)</LABEL>
	<input type="text" name="fin"><br/>
	<LABEL for="lien">URL calendrier(ics):</LABEL>
    <input type="text" name="lien"><br/>
    <div ="salle">
    <LABEL for="salle">Salle:</LABEL>
	<select name="salle" style="margin-top: 20px;">
    	<option value="OpenSpace">Open Space 1</option>
    	<option value="101">Salle 1</option>
    	<option value="102">Salle 2</option>
	</select><br />
    </div>
	<label for="message">Vous pouvez ajouter un message d'information:</label><br />
       <textarea name="message" id="message" maxlength = "50"></textarea><br />

    <input type="submit" value="Valider">

</form>
</body>
</html>

