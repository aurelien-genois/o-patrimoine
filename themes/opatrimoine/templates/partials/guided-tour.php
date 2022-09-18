<?php

?>

<article>
    <div class="flex flex-wrap">
        <div class="w-1/2 sm:w-1/3 order-1">
            <!-- date -->
            <!-- heure -->
            <!-- durée -->
        </div>
    
        <div class="w-1/2 sm:w-1/3 order-2 sm:order-3">
            <!-- accessibilités  -->
            <!-- thèmatiques  -->
        </div>

        <!-- contact organisateur 
        => get_the_author_meta('user_email',$user_id)
        ou seulement $user_id (car sélection du mail sur formulaire de contact) 
        si on peut cacher l'adresse mail pour plus de confidentialité -->

        <div class="w-full sm:w-1/3 order-3 sm:order-2">
            <?= get_the_title() ?>
        </div>
    </div>

    <form class="flex flex-wrap">
        <div class="w-full sm:w-auto">
            <!-- nb_reservations/nb_places_total -->
        </div>
        <div class="w-full sm:w-auto">
            <!-- select nb_places or msg -->
        </div>
        <div class="w-full sm:w-auto">
            <!-- submit (inscrire/désinscrire) or links connexion/inscription -->
        </div>
</form>
</article>