<!DOCTYPE html>
<?php
  include 'files_application/functions.php';
 ?>
<html>
<head>
  <!-- PPL -->
  <meta charset="UTF-8">
  <meta name="description" content="Ogame Colonization Search Engine">
  <meta name="keywords" content="Ogame,Colonisation,Free,API">
  <meta name="author" content="Kurai">
  <title>OCSE (Ogame Colonization Search Engine)</title>
  <!-- PERSONALISATION -->
  <link rel="icon" type="image/png" href="styles/images/icon_ship.png" />
  <link rel="stylesheet" type="text/css" href="styles/default.css">
  <link rel="stylesheet" type="text/css" href="styles/sweetalert2.css">
  <link rel="stylesheet" type="text/css" href="styles/pretty-checkbox.min.css">
  <!-- SCRIPTS -->
  <script src="scripts/jquery.js"></script>
  <script src="scripts/functions.js"></script>
  <script src="scripts/sweetalert2.js"></script>
</head>

<body>

  <div id="main_div">
  <h1> Ogame Colonization Search Engine </h1>
  <h2> Find your next planet ! </h2>
  <p> Tips : Laissez un champs vide pour généraliser. </p>

  <!-- FORMULAIRE DE RECHERCHE -->
  <div id="formulaire">
    <form>
      <table id="tab_form">
        <!-- 1ère LIGNE -->
        <tr id="top_tr">
          <td style="border-right:1px solid white;border-bottom:1px solid white;"> - Galaxie - <br> <input type="number" id="galaxie" placeholder="Ex : 2" max="12" min="1" /> </td>
          <td style="border-bottom:1px solid white;"> - Plage Système Solaire - <br> <input type="number" id="bornInf" placeholder="Ex : 100" max="450"  min="1" />
          - <input type="number" id="bornSup" placeholder="Ex : 150" max="450"  min="1" /> </td>
      </tr>
      <!-- 2ème LIGNE -->
      <tr>
        <td colspan="2">
          - Positions - <br>
          <div id="div_position">
            <input type="text" id="positions" placeholder="Ex : 1-8 ou 1,8,6"/>
            <img class="help_icon" src="styles/images/help.png" alt="help" onmouseover="javascript: afficher_aide(document.getElementById('aide_position'));" onmouseout="javascript: afficher_aide(document.getElementById('aide_position'));"/>
          </div>
          <!-- INFOBULLE   -->
          <div class="infobulle">
            <div class="infobulle-texte" id="aide_position" style="display: none;">
              <p> Syntaxe pour Positions </p>
              <ul>
                <li> X-Y - Des positions X à Y. </li>
                <li> X;Y;Z - Position X et Y et Z. </li>
                <li> X-Y;Z - Position X à Y et Z. </il>
              </ul>
            </div>
          </div>
          <!-- FIN TABLEAU -->
        </td> </tr> <br>
      </table>
      <input id="search_button" type="button" name="search" value="Find It !" onclick="send_search()"/>
      </form>
      <!-- FOOTER DU MAIN_DIV -->
      <div id="foot_div">
        <!-- Version -->
        <p style="text-align:left;color:white;margin:10px;font-size:15px;"> By Kurai ~ Version 1.0 </P>
        <!-- Actualisation forcée -->
        <div id="force_actu_div">
          <div class="pretty p-switch">
            <input type="checkbox" name="actu_force" id="actu_force" />
            <div  class="state p-success"> <label>Forcer l'actualisation</label> </div>
          </div>
        </div>
        <!-- Ajout d'un univers -->
        <div id="option_and_add_div">
          <select name="unvierse_choice" id="unvierse_choice">
                     <?php genere_select_options(); ?>
          </select>
          <img id="add_universe_image" src="styles/images/plus.png" alt="Ajouter un univers" onclick="add_universe()"/>
        </div>
    </div>
    <!-- FIN FOOTER -->
    </div>
  </div>


<!-- SCRIPTS DE LA PAGE-->

<script>
// Afficher l'infobulle
function afficher_aide(aide) {
if (aide.style.display == "none") aide.style.display = "block";
else aide.style.display = "none";
}
</script>

<script type="text/javascript">
//Affiche le bouton de retour vers le haut de la page.
   $(document).ready(function(){
      $('body').append('<a href="#top" class="top_link" title="Revenir en haut de page">Haut</a>');s
   });
   $(window).scroll(function(){
	posScroll = $(document).scrollTop();
	if(posScroll >=550)
		$('.top_link').fadeIn(600);
	else
		$('.top_link').fadeOut(600);
});
</script>

<script>
  //Permet d'appuyer sur entrée dans les champs du formulaire pour valider la saisie
  var galaxie = document.getElementById("galaxie");
  var bornInf = document.getElementById("bornInf");
  var bornSup = document.getElementById("bornSup");
  var positions = document.getElementById("positions");

  galaxie.addEventListener("keydown", function (e) {if (e.keyCode === 13) {send_search();}});
  bornInf.addEventListener("keydown", function (e) {if (e.keyCode === 13) {send_search();}});
  bornSup.addEventListener("keydown", function (e) {if (e.keyCode === 13) {send_search();}});
  positions.addEventListener("keydown", function (e) {if (e.keyCode === 13) {send_search();}});
</script>


</body>
</html>
