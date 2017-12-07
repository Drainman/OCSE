<?php

function genere_select_options()
{
    $arrayOptions = scandir('files_import/');
    //print_r($arrayOptions);
    foreach ($arrayOptions as $a_file)
    {
      //echo "<script>console.log('$_afile')</script>";
      //'\'^universe_s[0-9]{3}-[a-z]{2}.xml$\'' controleur pour s-XXX-yy
      if(preg_match('#^universe_[a-zA-Z]*\.xml$#',$a_file))
      {
        $without_xml =  preg_split('\'.xml$\'',$a_file)[0];
        $withou_univese =   preg_split('\'^universe_\'',$without_xml)[1];
        if($withou_univese!='Xanthus')
          echo "<option value='$withou_univese'>$withou_univese</option>";
      }
    }
}

/*FONCTION PHP AJOUTEE AU DESSUS */


function recup_xml_file($string)
{
  //Recupération du fichier xml associé.
  $files = file_get_contents("http://$string.ogame.gameforge.com/api/universe.xml");
  echo $file;
}

function add_universe($string)
{
    $infoServ = file_get_contents("https://$string.ogame.gameforge.com/api/serverData.xml");
    if($infoServ == false)
      echo "Le serveur n'existe pas ou la récupétation de ses informations est impossible.";
    else
    {
        $infoServFile = fopen("../temp/serverData_$string.xml",'a+');
        fputs($infoServFile, $infoServ);
        fclose($infoServFile);
    }

}

/**
* Test l'existence d'un fichier xml passé en paramètre.
* @param string $xml_file Le Fichier XML.
* @return Bool - true = existe / false = n'existe pas.
*/
function test_xml_file_exist($xml_file)
{
  if (file_exists($xml_file))
    return true;

  else
    return false;
}

/**
* Parse le fichier xml et retourne la liste contenant les planètes existantes.
* @return array Listes des planètes existantes dans l'univers.
*/
function parse_xml_ogame($path)
{
  //$XML_FILES = 'files_import/universe.xml';
  $xml = simplexml_load_file($path);

  $array_all = array();

  $compteur = 0;
  //Pour tout les éléments 'planet' du fichier xml
  foreach ($xml->planet as $planet) {
    //On décompose les coordonées
    $new_coords =  $planet['coords'];
    $array_coords = parse_coords($new_coords);
    $galaxie = $array_coords[0];$ss=$array_coords[1];$exist=$array_coords[2];

    //Puis on alimente la liste les contenant
    if(!isset($array_all[$galaxie]))
      $array_all[$galaxie] = array($ss => array($exist));

    else if(!isset($array_all[$galaxie][$ss]))
      $array_all[$galaxie][$ss] = array($exist);

    else
      array_push($array_all[$galaxie][$ss],$exist);

    $compteur++;
  }

  return $array_all;
}

/**
* Parse les coordonées d'une planète et les retourne sous la forme d'une liste.
* @param string $string Coordonées en string.
* @return array Coordonées en liste.
*/
function parse_coords($string)
{
  $array_coords = preg_split('/(:| |;)/',$string);
  return $array_coords;
}

/**
* Liste toutes les planètes existantes par Galaxie et par système solaire.
* @param array $array_planet La liste des planètes.
*/
function list_planets($array_planet)
{
  foreach ($array_planet as $galaxie => $planets) {
    echo "<h2> -- GALAXIE : $galaxie -- </h2>";
    foreach ($planets as $ss => $l_planet )
    {
      echo "<h3> Système Solaire : $ss * </h3>";
      foreach ($l_planet as $a_planet) {
          echo " * G$galaxie - ss : $ss - Planet in $a_planet <br>";
      }
    }
  }
}


/**
* Liste toutes les emplacements de planètes libre par Galaxie et par système solaire.
* @param array $array_planet La liste des planètes.
*/
function list_planets_dispo($array_planet)
{
  foreach ($array_planet as $galaxie => $planets) {
    echo "<h2> -- GALAXIE : $galaxie -- </h2>";
    foreach ($planets as $ss => $l_planet )
    {
      echo "<h3> Système Solaire : $ss * </h3>";
      for($i=1;$i<=15;$i++)
      {
        if(array_search($i,$l_planet)==false && $i != $l_planet[0])
          echo " * G$galaxie - ss : $ss - No planet in $i <br>";
      }
    }
  }
}

/**
* Liste la disponibilité des planètes selon une galaxie et un ss précis.
* @param int $g Galaxie dans laquelle chercher.
* @param int $ss ss dans lequelle chercher.
* @param array $array_planet La liste des planètes.
*/
function search_dispo_gs($g,$ss,$array)
{
  $array_dispo = array();

  if(!isset($array[$g]))
    echo "Galaxie Inexistante ou pleine.";
  else if(!isset($array[$g][$ss]))
    echo "Système solaire inexsitant ou pleine.";
  else
  {
    for($i=1;$i<=15;$i++)
    {
      if(array_search($i,$array[$g][$ss])==false && $i != $array[$g][$ss][0])
      {
          echo " * G$g - ss : $ss -  Slot free for a planet in $i <br>";
          array_push($array_dispo,$i);
      }
    }
  }
  return $array_dispo;
}

/**
* Liste la disponibilité des planètes selon une galaxie précise.
* @param int $g Galaxie dans laquelle chercher.
* @param array $array_planet La liste des planètes.
*/
function search_dispo_g($g,$array)
{
  $array_dispo = array();
  ksort($array[$g]);
  foreach ($array[$g] as $ss => $l_planet )
  {
    echo "<div class='divs_result'>";
    echo "<h2> Système Solaire : $ss </h2>";
    for($i=1;$i<=15;$i++)
    {
      if(array_search($i,$l_planet)==false && $i != $l_planet[0])
      {
        echo " * G$g - ss : $ss - Slot free for a planet in $i <br>";
        if(!isset($array_dispo[$ss]))
          $array_dispo[$ss] = array($i);
        else
          array_push($array_dispo[$ss],$i);
      }
    }
    echo "</div>";
  }
  return $array_dispo;
}

/**
* Liste la disponibilité des planètes selon une position données (entre 8 et 15) et une galaxie précise.
* @param int $emplacement à trouver.
* @param int $galaxie Galaxie dans laquelle chercher.
* @param array $array_planet La liste des planètes.
*/
function search_particular_planet_in_particular_galaxie($emplacement,$galaxie,$array)
{
  $array_position = array();
  foreach ($array[$galaxie] as $ss => $l_planet )
  {
    if(array_search($emplacement,$l_planet)==false && $emplacement != $l_planet[0])
        array_push($array_position,$ss);
  }
  asort($array_position);
  foreach ($array_position as $position) {
    echo " * Planet num $emplacement free in $galaxie:$position  <br>";
  }
}

/**
* Indique les planètes existante dans une galaxie avec un système solaire précisé.
*/
function search_planet_gs($g,$ss,$array)
{
  if(!isset($array[$g]))
    echo "Galaxie Inexistante ou vide.";
  else if(!isset($array[$g][$ss]))
    echo "Système solaire inexsitant ou vide.";
  else
  {
    foreach ($array[$g][$ss] as $a_planet) {
        echo " * G$galaxie - ss : $ss - Planet in $a_planet <br>";
    }
  }
}


/**
* Cherche les planètes dispo selon un interval de sustème solaire dans une galaxie.
*/
function plageSS_search_particular($borneInf,$borneSup,$g,$array)
{
  if($borneInf>$borneSup)
  {
    $temp = $borneSup;
    $borneSup = $borneInf;
    $borneInf = $temp;
  }
  for($i=$borneInf;$i<=$borneSup;$i++)
  {
      echo "<div class='divs_result'>";
      echo "<h2> - SS-$i -</h2>";
      if(isset($array[$g][$i]))
        search_dispo_gs($g,$i,$array);
      else
        echo "Toute les planètes sont disponibles dans le ss - $i";

      echo "</div>";
  }
}

function plageSS_search_particular_planet($emplacement,$borneInf,$borneSup,$g,$array)
{
  if($borneInf>$borneSup)
  {
    $temp = $borneSup;
    $borneSup = $borneInf;
    $borneInf = $temp;
  }
  echo "<div class='divs_result'>";
  for($i=$borneInf;$i<=$borneSup;$i++)
  {
      if(isset($array[$g][$i]))
      {
        if(array_search($emplacement,$array[$g][$i])==false && $emplacement != $array[$g][$i][0])
          echo "Planetète en position $emplacement disponible en $g:$i <br>";
      }
      else
        echo "Planetète en position $emplacement disponible en $g:$i <br>";
  }
  echo "</div>";
}

function plageE_plageSS_search($borneInfE,$borneSupE,$borneInf,$borneSup,$g,$array)
{

  if($borneInfE>$borneSupE)
  {
    $temp = $borneSupE;
    $borneSupE = $borneInfE;
    $borneInfE = $temp;
  }

  for($i=$borneInfE;$i<=$borneSupE;$i++)
  {
    echo "<div class='divs_result'>";
    echo "<h2> - Slot $i - </h2>";
    plageSS_search_particular_planet($i,$borneInf,$borneSup,$g,$array);
    echo "</div>";
  }

}

//RAJOUTER LISTE POUR CONTROLER QUAND C EST VIDE
function decoup_position($string,$galaxie,$bornInf,$bornSup,$array)
{
  //DEFINITION DU PROFIL
  /*
  -> profil = "GPB" -> Galaxie, Positions et Bornes précisées
  -> profil = "GP" -> Galaxie et Positions précisées.
  -> profil = "P" -> Positions seules précisées.
  */
  if($bornInf=="NONE")
    $profil = "GP";
  else if($galaxie=="NONE" && $bornInf=="NONE")
    $profil = "P";
  else
    $profil = "GPB";

  $arrayPosition = preg_split('\';\'', $string);
  foreach ($arrayPosition as $new_string)
  {
    //Si on ne repète pas de - (interval) alors éxécution individuel
    if(preg_match('\'-\'', $new_string)==0)
    {
      echo "<div class='divs_result'>";
      echo "<h2> - Slot $new_string - </h2>";
      if($profil=="GPB")
        plageSS_search_particular_planet($new_string,$bornInf,$bornSup,$galaxie,$array);
      if($profil=="GP")
        plageSS_search_particular_planet($new_string,1,450,$galaxie,$array);
      if($profil=="P")
        echo "Cette opération n'est pas encore implémenté";

      echo "</div>";
    }

    //Sinon éxécution bornée
    else
    {
      $arrayFinal = preg_split('\'-\'', $new_string);
      $borneInfE = $arrayFinal[0];$borneSupE = $arrayFinal[1];
      if($profil=="GPB")
        plageE_plageSS_search($borneInfE,$borneSupE,$bornInf,$bornSup,$galaxie,$array);
      if($profil=="GP")
        plageE_plageSS_search($borneInfE,$borneSupE,1,450,$galaxie,$array);
      if($profil=="P")
        echo "Cette opération n'est pas encore implémenté";
    }
  }
}

 ?>
