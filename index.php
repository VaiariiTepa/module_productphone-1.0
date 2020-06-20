<?php
/**
 * Created by PhpStorm.
 * User: vaiarii.tepa
 * Date: 25/04/2018
 * Time: 11:46
 */
// Load Dolibarr environment
require '../../main.inc.php';
require_once 'class/productphone.class.php';

global $db, $langs, $user;

// Access control
//if ($user->socid > 0) accessforbidden();

// Load translation files required by the page
$langs->load("productphone@productphone");

// Initialization of object productphone
$_productPhone = new ProductPhone($db);


// Retrieve the different fields of the filters
$t_filter = $_productPhone->get_filter();
$t_search_productphone = array();

// Get parameters from filter
$action = GETPOST('action');
$t_param = array();
foreach($t_filter as $field){
  $t_param[ $field['field'] ] = GETPOST($field['field']);
}

//tableaux valeur par défault
$t_paramKey = array(
    'Nom OS'=>'os_name',
    'Nom Version'=>'os_version_name',
    'Numéro Version'=>'os_version',
    'Nombre Coeur'=>'cpu_number',
    'Résolution écrant'=>'screen_resolution',
);

// Generate the html of the different fields of the search filter
$t_input = generateInputHTMLofFilter($t_filter);


// If action is equal to search else
if($action == 'search') {
	$p_price_average = GETPOST('price_average');
    $t_search_productphone = $_productPhone->search_productphone($t_param,$p_price_average);
}

//Si bouton reset selectionner, alors remetre a zero le filtre d'affichage
if($action == 'reset'){
    $t_param = array();
}

/*
 * VIEW
 */

llxHeader('', $langs->trans('Product-Phone'));


print '<table summary="" width="100%" border="0" class="notopnoleftnoright" style="margin-bottom: 2px;">';
    print '<tbody>';
        print '<tr>';
            print '<td class="nobordernopadding hideonsmartphone" width="40" align="left" valign="middle">';
                print '<img src="/erp-vaiarii/theme/eldy/img/title.png" border="0" alt="" title="" id="pictotitle">';
            print '</td>';
            print '<td class="nobordernopadding" valign="middle">';
                print '<div class="titre">Affichage liste PRODUCT-PHONE</div>';
            print '</td>';
        print '</tr>';
    print '</tbody>';
print '</table>';

print '<div class="fiche">';
    print '<div class="fichecenter">';
        print '<div class="fichehalfleft" width="500px;">';
            /* Assenceur catalogue */
            print '<div class="assenceur_catalogue" style="overflow: scroll; /*height: 800px;*/">';
                //*********************************DIV OUVRANTE CATALOGUE***********************************************//
                print '<div id="accordion">';
                //*******************************************************************************************************//
				if($t_search_productphone){
					foreach ($t_search_productphone as $key=>$value){
						
                            //==== TITRE ====//
                            print '<h4>';
                                print '<div>';
                                    //Affichage par default avec mis en page spéciaux
                                    print '<b class="DeviceName">'.$value['Device']['DeviceName'].' </b>';
                                    print ' date de sortie: ' . $value['Device']['announced'];
                                print '</div>';
                                print '<div id="cadre_title">';
                                    print '<div id="cadreDefault">';
                                        //Affichage par défault sans mis en page spécial
                                        foreach($t_paramKey as $fkey=>$paramKey){
                                            if ($paramKey !== 'DeviceName' && $paramKey !== 'announced'){
                                                print $fkey.': '.$value['Device'][$paramKey].' | ';
                                            }
                                        }
                                    print '</div>';

                                    print '<div id="cadre">';
                                        //FOREACH permetant d'affiché dans les caractéristique
                                        //les filtres qui on été selectionner, mais n'affiche pas
                                        //les filtres qui sont déja affiché par défaut
                                        foreach ($t_param as $nkey=>$param){
                                            if(!empty($param)){
                                                if ($param !== $value['Device']['os_name']
                                                    && $value['Device']['os_version_name']
                                                    && $param !== $value['Device']['os_version']
                                                    && $param !== $value['Device']['cpu_number']
                                                    && $param !== $value['Device']['screen_resolution']){
                                                    $label = (!empty($t_filter[$nkey]) ? $t_filter[$nkey]['label'] : $nkey );
                                                    //lire le nom des caractéristique par default
                                                    print $label.': '.$value['Device'][$nkey].' | ';
                                                }
                                            }
                                        }
                                    print '</div>';
                                print '</div>';
                            print '</h4>';
                            //============== Telephone -- Table ==============//
                            print '<div id="contenue_accordion">';
                                print '<div id="photo">';
                                print '</div>';
                                print '<div id="table_contenue_accordion">';
                                    print '<table class="noborder" width="100%">';
                                        // HEADER
                                        print '<thead>';
                                            print '<tr class="liste_titre">';
                                                print '<th width="25%">';
                                                    print '<p>Réference</p>';
                                                print '</th>';
                                                print '<th width="25%">';
                                                    print '<p>libéler</p>';
                                                print '</th>';
                                                print '<th width="25%">';
                                                    print '<p>prix</p>';
                                                print '</th>';
                                            print '</tr>';
                                        print '</thead>';
                                        // BODY
                                        print '<tbody>';
                                        $parity = TRUE;
                                            foreach($value['DeviceAssociated'] AS $ref) {
												// Si le produit est a vendre, alors affiché												
												if($ref['tosell'] === '1' ){
                                                    $parity =! $parity;
                                                    print '<tr class="'.($parity?'pair':'impair').'">';
                                                        print '<td width="25%">';
                                                            print $ref['ref_product'];
                                                        print '</td>';
                                                        print '<td width="25%">';
                                                            print $ref['label'];
                                                        print '</td>';
                                                        print '<td width="25%">';
                                                            print price($ref['Prix_TTC']).' '.$langs->trans('SellingPriceTTC');
                                                        print '</td>';
                                                    print '</tr>';
                                                }else{
													print '<tr>';
														print '<td><p>n\'est pas mis en vente</p></td>';
														print '<td><p>n\'est pas mis en vente</p></td>';
														print '<td><p>n\'est pas mis en vente</p></td>';
													print '</tr>';
												break;
												}
                                            }
                                        print '</tbody>';
                                    print '</table>';
                                print '</div>';
                            print '</div>';
                        }
                    }
                //*********************************DIV FERMANTE CATALOGUE***********************************************//
                print '</div>';
                //******************************************************************************************************//
            print '</div>';
        /* Fermeture Div FicheHalfLeft */
        print '</div>';


        /* Début fiche half right */
        print '<div class="fichehalfright">';

            // LATERAL BAR SEARCH / FILTER OF CATALOG
            print '<div class="vertical-menu">';

                //Permet de remettre a Zero le filtre
                print '<form action="index.php">';
                    print '<input type="submit" name="action" value="Reset">';
                print '</form>';

                /* div form filtre */
                print '<div class="filtre">';
                        //Formulaire filtre selectionner
						print '<form>';
						print 'prix';
						print '<select name="price_average">';
							print '<option></option>';
							print '<option value="20000-30000">20 000 - 30 000</option>';
							print '<option value="40000-50000">40 000 - 50 000</option>';
							print '<option value="60000-70000">60 000 - 70 000</option>';
							print '<option value="80000-90000">80 000 - 90 000</option>';
							print '<option value="90000-100000">90 000 - 100 000</option>';
							print '<option value="100000-600000"> > 100 000</option>';
						print '</select>';
                        if($t_input){
                            foreach($t_input as $input){
                                print $input['label'];
                                print '<br>';
                                print $input['input'];
                                print '<br>';
                            }
                        }
                        print '<button type="submit" name="action" value="search">Rechercher</button>';
                        print '</form>';

                /* Fin Div Form Filter */
                print '</div>';

                print '<div class="Test">0</div>';

            print '</div>';
        /* Fin Fichehalfright */
        print '</div>';

    /* Fin fiche center */
    print '</div>';

/* Fin Div Fiche */
print '</div>';


// End of page
llxFooter();
?>
<script>

    $(document).ready(function(){
        //ACCORDION
        $( "#accordion" ).accordion({
            collapsible: true,
            heightStyle: "fill"
        });

        $(window).resize(function(){
            $("Test").text(x += 1);

        });

    })



</script>
