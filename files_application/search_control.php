<?php

include 'functions.php';

$galaxie = $_POST['galaxie'];
$positions = $_POST['positions'];
$bornInf = $_POST['bornInf'];
$bornSup = $_POST['bornSup'];
$universe = $_POST['universe'];

$arrayAll = parse_xml_ogame('../files_import/universe_'.$universe.'.xml');

//REDEFINITION DES BORNES
if($bornInf=="NONE" && $bornSup!="NONE")
  $bornInf = $bornSup;

if($bornInf!="NONE" && $bornSup=="NONE")
    $bornSup = $bornInf;

if($positions!="NONE")
  decoup_position($positions,$galaxie,$bornInf,$bornSup,$arrayAll);

else
{
  if($bornInf!="NONE")
    plageSS_search_particular($bornInf,$bornSup,$galaxie,$arrayAll);
  else
    search_dispo_g($galaxie,$arrayAll);
}


 ?>
