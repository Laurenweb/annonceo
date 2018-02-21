<?php
// Inclusion du fichier d'initialisation
require 'include/init.php';

// Insertion de la section haut de page
require 'layout/top.php';
?>

<div class="container">

    <fieldset>
    <h1 class="text-center my_titles my_font">A propos</h1>
        <br>
        <div class="col-md-6 col-md-offset-3 thumbnail pad">
            <img src="images/team.jpg" class="pt-20" alt="notre équipe" title="Notre équipe de gagnants">
            <hr>
        <h3 class="text-center">Notre vision</h3>
            <p class="text-justify ml-10 mr-10 pad">Le e-commerce est aujourd'hui dominé par de grands acteurs qui inondent le marché de leurs produits standardisés. Nous sommes au contraire convaincus que le web doit permettre aux « petits » de se faire une place en proposant aux consommateurs des produits originaux mais aussi et surtout une expérience d’achat humaine et conviviale.</p> 

            <blockquote class="text-center ml-20 mr-20" style="border-right: solid 5px #eee" "padding: 20px">
                <p>Les "petites annonces", nous, on aime.</p>
                <p><strong>❝</strong>Nous avons créé annonceo.com pour que chaque jour, nos utilisateurs puissent trouver à travers nos annonces les services, tarifs et conseils pratiques qui répondent à leurs objectifs.<strong>❞</strong></p>
                <footer>Laurence D., dirigeante d’annonceo.com</footer>
            </blockquote>
        </div>
    </fieldset>

</div> <!-- Fin container -->
<hr />

<?php
// Insertion de la section bas de page
require 'layout/bottom.php';
?>

