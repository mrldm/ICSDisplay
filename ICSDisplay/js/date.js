function date_heure(id)
{
        date = new Date;
	  	var annee   = date.getFullYear();
		var mois    = date.getMonth() - 1;
		var jour    = date.getDate();
		
		nom_mois = new Array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Novembre", "Decembre") ;
	    document.getElementById('jour').innerHTML = jour;
	    document.getElementById('mois').innerHTML = nom_mois[mois];
	    document.getElementById('annee').innerHTML = annee;
        h = date.getHours();
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
        resultat = +h+':'+m+':'+s;
        document.getElementById(id).innerHTML = resultat;
        setTimeout('date_heure("'+id+'");','1000');
        return true;
}