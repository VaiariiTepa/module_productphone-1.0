<?php

/**
 *  \file       htdocs/product/fiche.php
 *  \ingroup    product
 *  \brief      Page to show product
 */

require '../../main.inc.php';
require_once "class/productphone.class.php";

// Load translation files required by the page
$langs->load("productphone@productphone");



$action = GETPOST('action');
$p_fk_product_phone_raw = GETPOST('rowid');
$p_device = GETPOST('device');
$p_action = GETPOST('action');
$p_product = GETPOST('product');


$t_param = array(
  'rowid' => $p_fk_product_phone_raw,
);

$t_fields = array(
  array( // Bloc 1 : Marque Modele
    'Brand', 'DeviceName',
  ),
  array( // Bloc 2 : Données brut retravailler
    'os_name','os_version','os_version_name','screen_resolution'
  ,'phone_size','phone_weight','primary_camera_resolution'
  ,'secondary_camera_resolution','cpu_number','spu_speed'
  ,'ram','interne_memory','connexion_type','battery_capacity'
  ,'phone_color','sim1_format','sim2_format','dual_sim',
  ),
  array( // Bloc 3 : Donnees brut
    'rowid','alert_types','announced','audio_quality','battery_c','bluetooth','body_c'
  ,'Brand','browser','build','call_records','card_slot'
  ,'camera','camera_c','card_slot','chipset','clock','colors','cpu','DeviceName'
  ,'dimensions','display','display_c','edge','features','features_c','fk_user'
  ,'games','gprs','gps','gpu','infrared_port','internal','java','keyboard','languages'
  ,'loudspeaker','loudspeaker_','memory_c','messaging','multitouch','music_play','network_c'
  ,'nfc','os','performance','phonebook','price','primary_','protection','radio','resolution'
  ,'alarm','sar','sar_eu','sar_us','secondary','sensors','sim','size','sound_c','speed'
  ,'stand_by','status','talk_time','technology','tms','type','usb','video','weight','wlan'
  ,'_2g_bands','_3g_bands','_3_5mm_jack_','_4g_bands',
  ),
);

/*
 * Actions
 */
//$t_productPhone = array();
if($p_fk_product_phone_raw){
//    echo 'lecture du controler RECHERCHE UNIQUE RAW';
    $_productPhone = new ProductPhone($db);
    $t_productPhone = $_productPhone->fetch_productPhone_all($p_fk_product_phone_raw);
    
    if($t_productPhone) $t_productPhone = reset($t_productPhone);

}


/*
 * View
 */
$title = 'Fiches détails';
llxHeader('', $title);


// Subheader
if($p_product){
    $product = trim($p_product);
$linkback = '<a href="' . DOL_URL_ROOT . '/custom/productphone/admin/manage.php?productphone='.$p_device.'&product='.$product.'&action='.$p_action.'">'
    . $langs->trans("BackToModuleList") . '</a>';
    print load_fiche_titre($langs->trans($title), $linkback);
}else{
    $linkback = '<a href="' . DOL_URL_ROOT . '/custom/productphone/admin/manage.php?productphone='.$p_device.'"">'
        . $langs->trans("BackToModuleList") . '</a>';
    print load_fiche_titre($langs->trans($title), $linkback);
}

// Configuration header
$head = productPhoneCardPrepareHead($t_param);
dol_fiche_head(
    $head,
    'fiche_produit',
    $langs->trans("Module750504Name"),
    1,
    'productphone@productphone'
);

//AFFICHAGE MARQUE ET MODELE
print '<div class="tabBar">';
	print '<table class="border" width="100%">';
		print '<tbody>';
		foreach($t_fields[0] as $key){
			print '<tr>';
				print '<td width="30%">'.$langs->trans('productphone_'.$key).'</td>';
				print '<td>';
					print nl2br($t_productPhone[$key]);
				print '</td>';
			print '</tr>';
		}
		print '</tbody>';
	print '</table>';
print '</div>';

//AFFICHAGE FORMATER
print '<div class="tabBar">';
	print '<table class="border" width="100%">';
		print '<tbody>';
		foreach($t_fields[1] as $key){
			print '<tr>';
				print '<td width="30%">'.$langs->trans('productphone_'.$key).'</td>';
				print '<td>';
					print nl2br($t_productPhone[$key]);
				print '</td>';
			print '</tr>';
		}
		print '</tbody>';
	print '</table>';
print '</div>';

//AFFICHAGE BRUT
print '<div class="tabBar">';
	print '<table class="border" width="100%">';
		print '<tbody>';
		foreach($t_fields[2] as $key){
			print '<tr>';
				print '<td width="30%">'.$langs->trans('productphone_'.$key).'</td>';
				print '<td>';
					print nl2br($t_productPhone[$key]);
				print '</td>';
			print '</tr>';
		}
		print '</tbody>';
	print '</table>';
print '</div>';

dol_fiche_end();

llxFooter();
$db->close();
