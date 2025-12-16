# projet-opatrimoine

PHP : `8.1`
NODE : `18`

<h2 align="center">O'patrimoine</h2>

Projet fictif, O’Patrimoine a été développé en autonomie après une formation spécialisée sur le développement avec WordPress pour passer un titre professionnel devant un jury.

O’Patrimoine a pour objectif de centraliser des visites guidées de différents lieux sur une seule plateforme et ainsi faciliter l’accès aux sites historiques hors Journées du Patrimoine. Les organisateurs (via un compte au rôle spécifique) peuvent gérer des fiches “lieux” et d’y associer des visites avec thème, horaires et nombre de places. Les internautes peuvent consulter ces fiches “lieux” et les visites qui y sont prévues, et s’y inscrire via un compte “visiteur” (en précisant le nombre de places).

## Struture WordPress

Après avoir défini les fonctionnalités minimales du projets, les rôles utilisateurs et les user stories, j’ai élaboré le Modèle Conceptuel de Donnée autour du cœur du projet : les visites et leur réservation. Aux visites sont associées un lieu unique et deux taxonomies, les contraintes d’accessibilité et les thématiques. Les lieux sont rattachés à un type d’établissement, une taxonomie, et pourraient avoir des commentaires. Enfin les réservations, qui connectent un utilisateur “visiteur” avec une visite, sont une table personnalisée en base de donnée pour enregistrer le nombre de places réservées.

Le projet étant développé avec WordPress, la structure des fichiers et de la base de donnée est donc celle du CMS et le code s’appuie au maximum sur les fonctions et hooks de WordPress et également sur certains plugins comme ACF. La structure du thème, de ses fonctions et fichiers (modèles de pages notamment) et cependant personnalisée.

Avant le développement technique, j’ai conçu quelques wireframes afin d’anticiper la structure HTML des pages et leurs découpages en blocs (fichiers) réutilisables, mais également afin de positionner les éléments selon des règles UX pour une meilleur expérience utilisateur. Les wireframes sont importantes pour prévoir les variations de certains blocs (par exemple les visites) et les déclinaisons pour différentes tailles d’écran.

## Challenges

O’Patrimoine a été également une bonne occasion d’approfondir l’Ajax avec WordPress : ces requêtes via Javascript sans rechargement de page. C’est notamment le cas pour le bouton “voir plus” au bas des listes des lieux et des visites. Au clique sur ce dernier, on récupère en Javascript le numéro de “la page à charger” et les valeurs des filtres de recherche pour lancer une nouvelle requête en Ajax avec les mêmes filtres mais pour “la page suivante”. Plutôt qu’une pagination classique prévue par WordPress, j’utilise donc ce système de pagination pour afficher la suite des résultats sous les précédents sans rechargement de page.

Une autre difficulté a été le formatage de la date en français. J’ai dû tout d’abord séparer dans mon esprit la timezone de la langue puis ensuite j’ai dû diviser en deux champs ACF date et heure le champ “datetime” des visites. J’ai utilisé la fonction “datefmt_create()” qui est je crois un raccourci de la méthode “create()” de la classe ”IntlDateFormatter” pour créer un format de date. Mais la syntaxe du format n’est pas celle plus courante pour le PHP, elle est définie par le projet International Components for Unicode (ICU) pour une meilleure internationalisation. Dans mon cas, “d F Y” est devenu “dd LLLL y” puis j’ai obtenu le format en français en utilisant ensuite la fonction “datefmt_format()” en lui passant la date et le format.

## Import BDD

docker exec -it opatrimoine-db \
 mysql -h ${DB_HOST} -u ${DB_USER} -p ${DB_NAME}

source opatrimoine.sql
