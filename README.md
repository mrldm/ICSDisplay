L'objectif est de se rendre capable d'afficher la reservation d'une salle afin que chacun soit en mesure de savoir par qui, pour quoi, et pour combien de temps, la salle est occupée.

Pour cela, nous souhaitons donc que les groupes qui relèveront le défi fasse un serveur qui lise régulièrement des ICS de Google Calendar, et proposent une visualisation afin de savoir :

qu'est-ce qu'il y a dans une salle (il peut y avoir 0, 1 ou n évènements en même temps)
jusqu'à quelle heure dure le/les évènement(s) actuel(s)
qui (étudiants et / ou profs) sont supposés être dans la salle
quels sont les prochains (nombre à définir) évènements à venir aujourd'hui
Nous souhaitons par exemple :

afficher la liste des étudiants devant passer un exam
indiquer le nom de la soutenance en cours (afin de savoir si vous attendez devant la bonne salle)
Nous pensons qu'il faut faire un serveur (au sens logiciel du terme) qui :

télécharge & parse l'ICS afin de le mettre en cache local (sqlite ? BDD plus classique ? fichier plat ? fichier ics ?)
génère le rendu HTML lorsque l'on appelle une URL bien précise qui fait référence à un seul calendrier

PREREQUI
- PHP5
- URL vers un .ics
- le calendrier devra avoir un timezone sur londre. (ou GMT + 0)
