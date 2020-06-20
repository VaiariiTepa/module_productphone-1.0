<?php
// Load Dolibarr environment
// if ( false === (@include '../../main.inc.php')) require '../../main.inc.php';
require '../../../main.inc.php';
global $langs, $user;

// Librairies
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once '../lib/productPhone.lib.php';
require_once '../lib/fonoapi.lib.php';
require_once '../class/productphone.class.php';

// Access control
if( !$user->admin) accessforbiden();

// Initialisation de l objet
$_productPhone = new ProductPhone($db);
$t_dataDevices = $_productPhone->count_devices();

// Translations
$langs->load("productphone@productphone");

// Parameters
$brand = GETPOST('brand');
$device = GETPOST('device');
$action = GETPOST('action');

$brand = GETPOST('brand');
$devicename = GETPOST('devicename');
$os_name = GETPOST('os_name');
$os_version = GETPOST('os_version');
$os_version_name = GETPOST('os_version_name');
$screen_resolution_width = GETPOST('screen_resolution_width');
$screen_resolution_height = GETPOST('screen_resolution_height');
$screen_resolution = GETPOST('screen_resolution');
$phone_size = GETPOST('phone_size');
$phone_weight = GETPOST('phone_weight');
$primary_camera_resolution = GETPOST('primary_camera_resolution');
$secondary_camera_resolution = GETPOST('secondary_camera_resolution');
$cpu_number = GETPOST('cpu_number');
$cpu_speed = GETPOST('cpu_speed');
$ram = GETPOST('ram');
$interne_memory = GETPOST('interne_memory');
$connexion_type = GETPOST('connexion_type');
$battery_capacity = GETPOST('battery_capacity');
$phone_color = GETPOST('phone_color');
$sim1_format = GETPOST('sim1_format');
$sim2_format = GETPOST('sim2_format');
$dual_sim = GETPOST('dual_sim');

// noms des champs
$t_field = array(
    "brand" => "Marque",
    "device" => "Modèle"
);

// controle des champs
$t_error = array();
foreach($t_field as $key => $value){
    if( empty( GETPOST($key))) $t_error[] = 'Le champ \''.$value.'\' est obligatoire';
}

/******************************************************************************/
/*                     Actions                                                */
/******************************************************************************/
/**
 * @action importer
 *  - recupere les donnees dans la db
 *  - recupere les reponses sur FonoApi
 *  - insert dans la db
 */
if ( $action == $langs->trans('productPhoneImporting'))
{
    // controle champs : si pas d erreurs
    if(count($t_error) == 0){
        //  recupere les appareils dans table llx_product_phone_raw selon $brand et $device
        //  ensuite recupere les reponses sur FonoApi
        $data_getDevice = $_productPhone->get_productPhoneRaw($brand,$device);
        $t_getDevice = $_productPhone -> get_raw_data ($brand ,$device);

        // enfin insertion en base de donnees
        $_productPhone->feed_productPhone($brand,$device);

        setEventMessage('Produit(s) correctement importé(s)', 'mesgs');
        header("Location: ".$_SERVER['PHP_SELF'].'?brand='.$brand.'&device='.$device.'&action='.$langs->trans('productPhoneSearch')); // a revoir
    }
    // si erreur
    else
    {
        foreach ($t_error as $value)
        {
            setEventMessage($value, 'errors');
        }
    }
}

/**
 * @action rechercher
 * Recherche sur FonoAPi ensuite dans la database
 * database = llx_product_phone_raw
 */
if ( $action == $langs->trans('productPhoneSearch') )
{
    // controle champs : si pas d erreurs
    if(count($t_error) == 0)
    {
        $t_getDevice = $_productPhone->get_raw_data($brand,$device);
        $data_getDevice = $_productPhone->get_productPhoneRaw($brand,$device);
    }
    else
    {
        foreach ($t_error as $value)
        {
            setEventMessage($value, 'errors');
        }
    }
}

if ($action == 'creer_fiche') {
	
	$_productPhone->create_fiche(
		$brand
		,$devicename
		,$os_name
		,$os_version
		,$os_version_name
		,$screen_resolution_width
		,$screen_resolution_height
		,$screen_resolution
		,$phone_size
		,$phone_weight
		,$primary_camera_resolution
		,$secondary_camera_resolution
		,$cpu_number
		,$cpu_speed
		,$ram
		,$interne_memory
		,$connexion_type
		,$battery_capacity
		,$phone_color
		,$sim1_format
		,$sim2_format
		,$dual_sim
	);

}

/**
 * - convertit l objet $data_getDevice et $t_getDevice en array
 * - fusionner avec un array_merge
 */
// et les transforme en object
if($data_getDevice || $t_getDevice)
{
    // parcourir reponse API et verifier si present dans database
    foreach ($t_getDevice as $key => $response)
    {
        if( isset ($data_getDevice[$response->Brand][$response->DeviceName]))
        {
            $data_getDevice[$response->Brand][$response->DeviceName] = (object)array_merge((array)$data_getDevice[$response->Brand][$response->DeviceName],(array)$response);
        } else
        {
            $data_getDevice[$response->Brand][$response->DeviceName] = (object) $response;
        }
    }
}
/******************************************************************************/
/*                               Affichage fiche                              */
/******************************************************************************/
// Page header
$page_name = 'ImportingCard';
llxHeader('', $langs->trans($page_name));

// Back Modules list
$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print load_fiche_titre($langs->trans($page_name), $linkback);

// Configuration header
$head = productPhoneAdminPrepareHead();
dol_fiche_head($head,'import',$langs->trans('Module750504Name'),0,'productphone@productphone');
//var_dump($t_dataDevices);

// Statistiques des importations
print_titre($langs->trans('ProductPhoneStatistical'));
print '<table class="noborder" width="100%">';
print '<tr class="liste_titre"><td colspan="2">'.$langs->trans("productphone_statistics").'</td></tr>';
print '<tr><td>'.$langs->trans("productphone_devicesImporting").'</td>';
print '<td>'.$t_dataDevices['nb_devices'].'</td></tr>';
print '<tr><td>'.$langs->trans("productphone_brandsImporting").'</td>';
print '<td>'.$t_dataDevices['nb_brands'].'</td></tr>';

print '</table><br>';

// Recherche et importation des produits
print_titre($langs->trans("SearchImportingProductPhone"));
print '<table class="noborder" width="50%">';
print '<tr class="liste_titre">';
print '<td width="13.333%">'.$langs->trans('productPhoneBrand').'</td>';
print '<td width="13.333%">'.$langs->trans('productPhoneDeviceName').'</td>';
print '<td width="13.333%"></td>';
print '<td width="50%">&nbsp;</td>';
print '</tr>';
// Champs de recherche
print '<form action="'.dol_buildpath('/productphone/admin/import.php',1).'?action='.$langs->trans('productPhoneSearch').'&device="'.$device.'"&brand="'.$brand.'" method="GET"';
print '<tr class="impair">';
print '<td><input type="text" name="brand" value="'.($brand?$brand:'').'"></td>';
print '<td><input type="text" name="device" value="'.($device?$device:'').'"></td>';
print '<td colspan="2" align="left"><input type="submit" class="btn btn-primary" name="action" value="'.$langs->trans('productPhoneSearch').'">';
print '<input type="submit" class="btn btn-success" name="action" value="'.$langs->trans('productPhoneImporting').'"></td></tr>';
print '</form>';
print '<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Créer une fiche</button></td>';
print '</table><br>';
print '<tr class="pair"><td colspan="5">&nbsp;</td></tr>';

if($action == $langs->trans('productPhoneSearch') && $t_getDevice )
{
    print '<table class="noborder" width="70%">';

    // Liste des produits
    print_titre($langs->trans('productPhoneListOfDevices'));

    print '<tr class="liste_titre">';
    print '<th width="10%" style="text-align: center">'.$langs->trans("productPhoneTms").'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans("productPhoneBrand").'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans("productPhoneDeviceName").'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans("productPhoneAnnounced").'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans("productPhoneStatus").'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans("productPhoneOs").'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans("productPhoneDimensions").'</th>';
    print '</tr>';

    foreach ($data_getDevice as $brand => $t_phoneBrand )
    {
        if($t_phoneBrand)
        {
            foreach($t_phoneBrand  as $device => $t_phoneDevice)
            {
                $parity = !$parity;

                print '<tr class="'.($parity?'pair':'impair').'">';

                // listing des appareils
                print '<td width="10%" style="text-align: center">'.($t_phoneDevice->tms?$t_phoneDevice->tms:'-').'</td>';
                print '<td width="10%" style="text-align: center">'.$t_phoneDevice->Brand.'</td>';
                print '<td width="10%" style="text-align: center">'.$t_phoneDevice->DeviceName.'</td>';
                print '<td width="10%" style="text-align: center">'.($t_phoneDevice->status?$t_phoneDevice->status:'-').'</td>';
                print '<td width="10%" style="text-align: center">'.($t_phoneDevice->announced?$t_phoneDevice->announced:'-').'</td>';
                print '<td width="10%" style="text-align: center">'.($t_phoneDevice->os?$t_phoneDevice->os:'-').'</td>';
                print '<td width="10%" style="text-align: center">'.($t_phoneDevice->dimensions?$t_phoneDevice->dimensions:'-').'</td>';

                print '</tr>';
            }
        }
        elseif (empty($data_getDevice))
        {
            setEventMessage($langs->trans('ErrorSearchProductPhone'), 'errors');
        }

    }
    print '</table>';
}
?>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Créer une fiche Téléphone</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
		<?php
			print '<form action="'.dol_buildpath('/productphone/admin/import.php',1).'?action=creer_fiche" method="POST">';
				
				print '<input type="text" name="brand" placeholder="Marque">';
				print '<input type="text" name="devicename" placeholder="Modèle">';
				print '<input type="text" name="os_name" placeholder="nom OS">';
				print '<input type="text" name="os_version" placeholder="version OS">';
				print '<input type="text" name="os_version_name" placeholder="nom version OS">';
				print '<input type="text" name="screen_resolution_width" placeholder="largeur ecrant">';
				print '<input type="text" name="screen_resolution_height" placeholder="hauteur ecrant">';
				print '<input type="text" name="screen_resolution" placeholder="résolution ecrant">';
				print '<input type="text" name="phone_size" placeholder="taille ecrant">';
				print '<input type="text" name="phone_weight" placeholder="poids">';
				print '<input type="text" name="primary_camera_resolution" placeholder="camera arrière">';
				print '<input type="text" name="secondary_camera_resolution" placeholder="camera avant">';
				print '<input type="text" name="cpu_number" placeholder="nbr cpu">';
				print '<input type="text" name="cpu_speed" placeholder="vitesse cpu">';
				print '<input type="text" name="ram" placeholder="ram">';
				print '<input type="text" name="interne_memory" placeholder="memoire interne">';
				print '<input type="text" name="connexion_type" placeholder="connexion">';
				print '<input type="text" name="battery_capacity" placeholder="battery">';
				print '<input type="text" name="phone_color" placeholder="couleur">';
				print '<input type="text" name="sim1_format" placeholder="sim1">';
				print '<input type="text" name="sim2_format" placeholder="sim2">';
				print '<input type="text" name="dual_sim" placeholder="dual sim O/N">';
				
				print '</div>';
				print '<div class="modal-footer">';
				print '<button type="submit" class="btn btn-primary">Créer</button>';
				print '</div>';


			print '</form>';

		?>
    </div>
  </div>
</div>

<?php


// Page end
dol_fiche_end();
llxFooter();
?>
<!-- 'JS', Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
