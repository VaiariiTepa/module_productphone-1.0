<?php
/**
 * Created by PhpStorm.
 * Permet de tester mon code
 * sans faire de modification a risque dans le code de Vaiarii
 *
 * User: sabrina.hauata
 * Date: 13/06/2018
 * Time: 11:31
 */

// Load Dolibarr environment
if ( false === (@include '../../main.inc.php')) require '../../main.inc.php';

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

// Translations
$langs->load("productphone@productphone");

// Parameters
$brand = GETPOST('brand');
$device = GETPOST('device');
$action = GETPOST('action');

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
        //  recupere les appareils selon dans table llx_product_phone_raw selon $brand et $device
        //  ensuite recupere les reponses sur FonoApi
        $data_getDevice = $_productPhone->get_productPhoneRaw($brand,$device);
        $t_getDevice = $_productPhone -> get_raw_data ($brand ,$device);

        // enfin insertion en base de donnees
        $_productPhone->feed_productPhone($brand,$device);
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

/**
 * - convertit l objet $data_getDevice et $t_getDevice en array
 * - fusionner avec un array_merge
 */
// pour les
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
$page_name = 'ProductPhoneSetup';
llxHeader('', $langs->trans($page_name));

// Back Modules list
$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans('BackToModuleList').'</a>';
print load_fiche_titre($langs->trans($page_name), $linkback);

// Configuration header
$head = productPhoneAdminPrepareHead();
dol_fiche_head($head,'productphone',$langs->trans('Module750504Name'),0,'productphone@productphone');

// Champs de recherche
print '<table class="noborder" width="50%">';
print '<tr class="liste_titre">';
print '<td width="20%">'.$langs->trans('productPhoneBrand').'</td>';
print '<td width="20%">'.$langs->trans('productPhoneDeviceName').'</td>';
print '<td width="50%">&nbsp;</td>';
print '</tr>';

print '<form action="'.dol_buildpath('/productphone/admin/importTest.php',1).'?action='.$langs->trans('productPhoneSearch').'&device="'.$device.'"&brand="'.$brand.'" method="GET"';
print '<tr class="impair">';
print '<td><input type="text" name="brand" value="'.($brand?$brand:'').'"></td>';
print '<td><input type="text" name="device" value="'.($device?$device:'').'"></td>';
print '<td colspan="2" align="left"><input type="submit" class="button" name="action" value="'.$langs->trans('productPhoneSearch').'">';
print '<input type="submit" class="button" name="action" value="'.$langs->trans('productPhoneImporting').'"></td></tr>';
print '</form>';
print '</table><br>';
print '<tr class="pair"><td colspan="5">&nbsp;</td></tr>';

if($action == $langs->trans('productPhoneSearch') && $t_getDevice )
{
    print '<table class="noborder" width="70%">';

    // Liste des produits
    print '<h2>'.$langs->trans('productPhoneListOfDevices').'</h2>';

    print '<tr class="liste_titre">';
    print '<th width="10%" style="text-align: center">Tms</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans('productPhoneBrand').'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans('productPhoneDeviceName').'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans('productPhoneAnnounced').'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans('productPhoneStatus').'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans('productPhoneOs').'</th>';
    print '<th width="10%" style="text-align: center">'.$langs->trans('productPhoneDimensions').'</th>';
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

// Page end
dol_fiche_end();
llxFooter();
