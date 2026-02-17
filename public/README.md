php -S localhost:8000 -t public



Sujet v3 

on deja un ordre 
- le fifo , first in first out 
    c'est celui qui demande en premier qui recoit 

    et maintenant on va definir 2 autres ordre 

    -l'ordre decroissant 
    -l'ordre proportionel 

    explication :

    -ordre decroissant = celui qui a la plus petite demande recoit en premier 
         par exemple tulear demande 2kg de riz et fianarantsoa demande 8kg de riz donc c'est tulear qui recoit en premier 
    -ordre proportionel = chacun recoit un don proportionel a sa demande , exemple: au total on a 6kg de riz , y a une qui demande 1kg , un autre qui en demande 2kg , et un dernier qui en demande 5kg , donc le premier recevra 1/6 , le second recevra 2/6 et le dernier 5/6 
        
          nb:arrondir vers le bas si il y a des virgules , exmple 1.8 = 1 ou 2.4= 2 

 tout cela inclut un petit changement dans saisir don 
     - l'ajout d'un formulaire pour choisir l'ordre que je veux utiliser sur le don que je fais 
          les ordres sont : - ordre prioritaire 
                            - ordre decroissant 
                            - ordre proportionel 

il est aussi exiger un bouton reinitialiser pour tout recommencer (histoire de bien tester)
car des donnees de test seront donnes plus tard (qui seront insere dans la bases)        

NB: dans chaque regle , si il y a des restes on les garde en stock (mais on ne fait rien avec , ca ne doit rien affecter)