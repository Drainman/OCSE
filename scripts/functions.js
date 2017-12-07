
function send_search()
{
  var galaxie = $("#galaxie").val();
  var bornInf = $("#bornInf").val();
  var bornSup = $("#bornSup").val();
  var positions = $("#positions").val();

  var universe = $( "#unvierse_choice option:selected" ).text();

  if(galaxie=="")
  {
    swal({
      title: '<h2 style="margin:0;"> Erreur ! </h2>',
      html: '<p style="color:black;font-weight: bold;font-family:Nasalization;"> Vous devez préciser la galaxie. </p>',
      type: 'error',
      confirmButtonText: 'Ok',
      background : '#787B7A'
    })
  }

  else
  {
    if(bornInf=="")
      bornInf = "NONE";

    if(bornSup=="")
      bornSup = "NONE";

    if(positions=="")
      positions = "NONE";

    console.log(galaxie+" "+bornInf+" "+bornSup+" "+positions);

    $.ajax({
     url : "files_application/search_control.php",
     type : 'POST',
     data : 'galaxie=' + galaxie + '&bornInf='+bornInf + '&bornSup='+bornSup + '&positions='+positions+'&universe='+universe,
     success : function(code_html, statut){affiche_res(code_html);},
     error : function(resultat, statut, erreur){'Error : Data not sends'}
   });
  }
}

function affiche_res(affichage)
{
  if($('#res_div'))
    $('#res_div').remove();

  $('body').append("<div id='res_div'></div>");
  $('#res_div').append(affichage);
}

// MODIFICATION DE SEND_SEARCH en fonction du choix fait
//$( "#unvierse_choice option:selected" ).text();
//
/*********************************/
async function add_universe()
{
  const {value: name} = await swal({
  title: '<h2 style="font-size:30px">Id of the new Universe</h2>',
  input: 'text',
  inputPlaceholder: 'Ex : s150-fr',
  showCancelButton: true,
  confirmButtonColor:'#162756',
  background : '#787B7A',
  preConfirm: (name) => test_universe(name),
  inputValidator: (value) => {return !value && '<warn style="color:black;font-weight: bold;font-family:Nasalization;">You need to write something!</warn>'}
});

}


async function test_universe(name)
{
  if (name)
  {
    /*
    2) Soumettre requête AJAX.
      -> Serveur réponds OK : swall ok
      -> Serveur sinon swall réponse

    Coté serveur ->
      * nouvelle fonction de récupération des info serveur xml selon le champs "name"
      * test si fichier trouvable
          - si non retourne -> "Cette univers n'esxiste pas ou la récupération de ses informations n'est pas possible"
      * Si oui -> récupération du fichier, trouver (parse) le nom du serveur, création du fichier, server_info_NAMESEVER.xml - dossier temp ? Si nom du serveur connu swall(server know)
      * Retourne OK, propose à l'utilisateur (client) d'importer pour la première fois les infos concernant l'univers

      + Controle de l'existence d'un fichier ou de son obsolecence, si DATEMODIFxml > 2j proposition d'actualisation au moment de la requête de recherche,
        sinon checkbox pour dire [Prendre en compte les nouvelles infos].
    */

    //Contrôle de l'expression
    if(name.match('^s[0-9]{3}\-[a-z]{2}$'))
    {
      $.ajax({
       url : "files_application/addUniverse_control.php",
       type : 'POST',
       data : 'universe='+name,
       success : function(code_html, statut){swal({type: 'info',background : '#787B7A', title: 'Res',html:code_html});},
       error : function(resultat, statut, erreur){'Error : Data not sends'}
     });
      swal({type: 'success',background : '#787B7A', title: '<h2 style="font-size:30px">Universe Add : ' + name+"</h2>"});
    }

    else
      swal({type: 'error', background : '#787B7A',title: '<h2 style="font-size:30px">Invalid Name </h2>',html:"<p style='color:black;font-weight: bold;font-family:Nasalization;'> Text : "+name+"</p>"})
  }
}
