------------------------------------------------------------------------------------------------------------------------------------------------
SOMMAIRE
------------------------------------------------------------------------------------------------------------------------------------------------
ctrl+f_1	INTRODUCTION
ctrl+f_2	PRE-REQUIS
ctrl+f_3	INSTALLATION
ctrl+f_4	FONCTIONNALITE

------------------------------------------------------------------------------------------------------------------------------------------------
INTRODUCTION																															ctrl+f_1
------------------------------------------------------------------------------------------------------------------------------------------------
Le Positivometre est un logiciel d'analyse d'emotions de Tweets selon un modele à 7 dimensions (polarité, joie, colère, dégout, surprise, 
peur et tristesse) et base sur la mise en correspondance des Tweets avec un dictionnaire comptant 14127 mots lemmatisés

------------------------------------------------------------------------------------------------------------------------------------------------
PRE-REQUIS																																ctrl+f_2
------------------------------------------------------------------------------------------------------------------------------------------------
Si vous voulez recherher des tweets, deux choses sont nécessaires : 
_ vous devez créer des clefs d'accès auprès de twitter via cette adresse, https://apps.twitter.com/ . Vous devez posséder un compte twitter. 
_ Vous avez aussi besoin de l'outil "TreeTagger", http://www.cis.uni-muenchen.de/~schmid/tools/TreeTagger/ .
  Vous devez utiliser imperativement "French parameter file (UTF-8)"
  
------------------------------------------------------------------------------------------------------------------------------------------------
INSTALLATION																															ctrl+f_3
------------------------------------------------------------------------------------------------------------------------------------------------
Le fichier "librairie.rar" doit être décompressé dans le même dossier que les fichiers .php.
La base de donnée fournie concerne généralement ces mots-clefs : Bataclan, LoiTravail, NuitDebout.

------------------------------------------------------------------------------------------------------------------------------------------------
FONCTIONNALITE																															ctrl+f_4
------------------------------------------------------------------------------------------------------------------------------------------------
La recherche ne fonctionne que pour un seul mot-clef. Si aucune dicrétisation n'est donnée, elle est mise à 10.
Le graphique radar donne la représentation des 6 émotions et de la polarité moyenne.
Il y a aussi 8 graphiques en 2 dimensions:
_ 1 graphique permettant de comparer les 6 émotions et la polatité
_ 7 graphiques pour chaque éléments donnant des détails:
	* Le permier et troisième quartils
	* La valididé. Plus la couleur se rapproche de la couleur du titre, plus le validité se rapproche de 100%
_ Un historique permet de lire les tweets et de voir les émotions associés
