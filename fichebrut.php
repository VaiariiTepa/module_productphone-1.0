<?php
/**
 * Created by PhpStorm.
 * User: vaiarii.tepa
 * Date: 09/08/2018
 * Time: 11:58
 *
 *  \file       htdocs/product/fichebrut.php
 *  \ingroup    productphone
 *  \brief      Page to Card Raw
 */
// Librairies
require '../../main.inc.php';
require_once "class/productphone.class.php";

// Access control
if( !$user->admin) accessforbidden();

//Initialisation de l objet
$_productPhone = new ProductPhone($db);

// Translation
$langs->load("productphone@productphone");

//Parameter
$p_fk_product_phone_raw = GETPOST('rowid');

// $_productPhone->get_productPhone_ById($p_fk_product_phone_raw);
$p_id_productphone = GETPOST('id_productphone');
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

$p_memoire_interne = GETPOST('memoire_interne');
$p_fk_product_phone = GETPOST('fk_product_phone');
$p_scenario_1 = GETPOST('scenario_1');
$p_scenario_2 = GETPOST('scenario_2');
$p_scenario_3 = GETPOST('scenario_3');
$p_scenario_4 = GETPOST('scenario_4');
$p_scenario_5 = GETPOST('scenario_5');
$p_scenario_6 = GETPOST('scenario_6');
$p_scenario_7 = GETPOST('scenario_7');

//ACTIONS
//Créer une catégorie de productpad en fonction de la capacité de stockage
if($action == "create_fiche_capacity"){
	$_productPhone->create_fiche_capacity(
		$p_fk_product_phone
		,$p_memoire_interne
		,$p_scenario_1
		,$p_scenario_2
		,$p_scenario_3
		,$p_scenario_4
		,$p_scenario_5
		,$p_scenario_6
		,$p_scenario_7
	);
}


//Met a jours une fiche llx_product_phone
if($action == "update_fiche"){
	$_productPhone->update_productphone(
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
		,$p_id_productphone
	);

	// Liste les appareils presents
	$t_productPhone = $_productPhone->fetch_productPhone_all($p_fk_product_phone_raw);
	// Si appareil present dans tableau alors remet le pointeur interne de tableau au debut
	if($t_productPhone) $t_productPhone = reset($t_productPhone);

	// Recupere l appareil dans product phone
	$t_productPhone_product = $_productPhone->get_product_from_productphone($p_fk_product_phone_raw);
}

// Si presence de l id du produit alors
if($p_fk_product_phone_raw)
{
    // Liste les appareils presents
    $t_productPhone = $_productPhone->fetch_productPhone_all($p_fk_product_phone_raw);
    // Si appareil present dans tableau alors remet le pointeur interne de tableau au debut
    if($t_productPhone) $t_productPhone = reset($t_productPhone);

    // Recupere l appareil dans product phone
    $t_productPhone_product = $_productPhone->get_product_from_productphone($p_fk_product_phone_raw);

}

$type_promotion = $_productPhone->fetch_typePromotion();
$productphone_by_capacity = $_productPhone->fetch_productPhone_ByCapacity($t_productPhone['rowid']);

$t_param = array('rowid' => $p_fk_product_phone_raw);

$t_fields = array(

    // Bloc 1 : Marque Modele
    array(
        'Brand'
    ,'DeviceName'),

    // Bloc 2 : Donnees brut retravailler
    array(
        'os_name'
    ,'os_version'
    ,'os_version_name'
    ,'screen_resolution'
    ,'phone_size'
    ,'phone_weight'
    ,'primary_camera_resolution'
    ,'secondary_camera_resolution'
    ,'cpu_number'
    ,'spu_speed'
    ,'ram'
    ,'interne_memory'
    ,'connexion_type'
    ,'battery_capacity'
    ,'phone_color'
    ,'sim1_format'
    ,'sim2_format'
    ,'dual_sim'),

    // Bloc 3 : ProductPhone_product
    array(
        'fk_product'
		,'ref'
		,'label')
	);

/******************************************************************************/
/*                               Affichage fiche                              */
/******************************************************************************/
$title = 'productphone_detailsSheet';
llxHeader('', $title, '', '', '', 0, 0, '', array('/productphone/css/mycss.css.php'));

// Configuration header
print load_fiche_titre($langs->trans($title));
$head = productPhoneCardPrepareHead($t_param);
dol_fiche_head($head, 'produit_associer', $langs->trans("Module750504Name"), 1, 'productphone@productphone');

// Affichage de la marque et modele de l appareil
print '<div class="tabBar">';
print '<table class="border" width="100%">';
print '<tbody>';
print '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">Modifier</button>';
			
    foreach ($t_fields[0] as $key)
    {
        print '<tr>';
        print '<td style="text-align: left" width="30%">'.$langs->trans('productphone_'.$key).'</td>';
        print '<td style="text-align: left">'.$t_productPhone[$key].'</td>';
        print '</tr>';
    }
print '</tbody>';
print '</table>';
print '</div>';

// Affichage des donnees retravailler : caracteristiques
print '<div class="tabBar">';
	print '<table class="border" width="100%">';
		//print_titre($langs->trans("productphone_specificationsDevice"));
		print '<tbody>';
			foreach ($t_fields[1] as $key)
			{
				print '<tr>';
					print '<td style="text-align: left" width="30%">'.$langs->trans('productphone_'.$key).'</td>';
					print '<td style="text-align: left">'.$t_productPhone[$key].'</td>';
				print '</tr>';
			}
		print '</tbody>';
	print '</table>';
print '</div>';

?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modifier fiche Téléphone</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
					print '<form action="'.dol_buildpath('/productphone/fichebrut.php',1).'?action=update_fiche" method="POST">';
					// var_dump($t_productPhone['os_name']);
						print '<input type="hidden" name="id_productphone" value="'.$t_productPhone['rowid'].'">';
						print '<input type="hidden" name="rowid" value="'.$p_fk_product_phone_raw.'">';
						print '<input type="text" value="'.$t_productPhone['Brand'].'" placeholder="Marque">';
						print '<input type="text" value="'.$t_productPhone['DeviceName'].'" name="devicename" placeholder="Modèle">';
						print '<input type="text" value="'.$t_productPhone['os_name'].'" name="os_name" placeholder="nom OS">';
						print '<input type="text" value="'.$t_productPhone['os_version'].'" name="os_version" placeholder="version OS">';
						print '<input type="text" value="'.$t_productPhone['os_version_name'].'" name="os_version_name" placeholder="nom version OS">';
						print '<input type="text" value="'.$t_productPhone['screen_resolution_width'].'" name="screen_resolution_width" placeholder="largeur ecrant">';
						print '<input type="text" value="'.$t_productPhone['screen_resolution_height'].'" name="screen_resolution_height" placeholder="hauteur ecrant">';
						print '<input type="text" value="'.$t_productPhone['screen_resolution'].'" name="screen_resolution" placeholder="résolution ecrant">';
						if (empty($t_productPhone['phone_size'])) {
							# code...
							print '<input type="text" name="phone_size" placeholder="taille ecrant">';
						}else{

							print '<input type="text" value="'.$t_productPhone['phone_size'].'" name="phone_size">';
						}
						print '<input type="text" value="'.$t_productPhone['phone_weight'].'" name="phone_weight" placeholder="poids">';
						print '<input type="text" value="'.$t_productPhone['primary_camera_resolution'].'" name="primary_camera_resolution" placeholder="camera arrière">';
						print '<input type="text" value="'.$t_productPhone['secondary_camera_resolution'].'" name="secondary_camera_resolution" placeholder="camera avant">';
						print '<input type="text" value="'.$t_productPhone['cpu_number'].'" name="cpu_number" placeholder="nbr cpu">';
						print '<input type="text" value="'.$t_productPhone['cpu_speed'].'" name="cpu_speed" placeholder="vitesse cpu">';
						print '<input type="text" value="'.$t_productPhone['ram'].'" name="ram" placeholder="ram">';
						print '<input type="text" value="'.$t_productPhone['interne_memory'].'" name="interne_memory" placeholder="memoire interne">';
						print '<input type="text" value="'.$t_productPhone['connexion_type'].'" name="connexion_type" placeholder="connexion">';
						print '<input type="text" value="'.$t_productPhone['battery_capacity'].'" name="battery_capacity" placeholder="battery">';
						print '<input type="text" value="'.$t_productPhone['phone_color'].'" name="phone_color" placeholder="couleur">';
						print '<input type="text" value="'.$t_productPhone['sim1_format'].'" name="sim1_format" placeholder="sim1">';
						print '<input type="text" value="'.$t_productPhone['sim2_format'].'" name="sim2_format" placeholder="sim2">';
						print '<input type="text" value="'.$t_productPhone['dual_sim'].'" name="dual_sim" placeholder="dual sim O/N">';
						
						// print '</div>';
					
						print '<div class="modal-footer">';
							print '<button type="submit" class="btn btn-primary">Modifier</button>';
						print '</div>';


					print '</form>';
				?>
			</div>
		</div>
	</div>
</div>


<?php

// Affichage des caractéristique
if($t_productPhone_product)
{
	print '<div class="tabBar">';
		print '<div class="fichecenter">';
			print '<div class="fichehalfleft">';
				print_titre($langs->trans("productphone_listOfRelatedProducts"));
				print '<table class="noborder" id="table_list_product_associat">';
				print '<tbody>';
				print '<tr class="liste_titre">';
				foreach ($t_fields[2] as $key)
				{
					print '<th width="10%" style="text-align: left">'.$key.'</th>';
				}
				print '</tr>';
				foreach ($t_productPhone_product as $device)
				{
					
					$parity = !$parity;
					// listing des appareils associes
					print '<tr data-label="'.$device["label"].'" data-rowid="'.$device["fk_product"].'" class="'.($parity?"pair":"impair").'">';
						print '<td style="text-align: left">'.$device["fk_product"].'</td>';
						print '<td style="text-align: left">'.$device["ref"].'</td>';
						print '<td style="text-align: left">'.$device["label"].'</td>';
					print '</tr>';
				}				
				print '</tbody>';
				print '</table>';
			print '</div>';
			print '<div class="fichehalfright">';
				print '<h5>création + liste capacité mémoire</h5><br>';
				print '<form action="'.dol_buildpath('/productphone/fichebrut.php',1).'?action=create_fiche_capacity" method="POST">';	
					print '<input type="hidden" value="'.$p_fk_product_phone_raw.'" name="rowid">'; 
					print '<input type="hidden" value="'.$t_productPhone['rowid'].'" name="fk_product_phone">'; 
					print '<div class="input-group">';
						print '<input type="text" class="form-control" placeholder="capacité GO" name="memoire_interne">';
						print '<button class="btn btn-info">créer</button>';
					print '</div>';
					print '<br>';
					print '<div>';
						print '<select name="scenario_1" style="width: 20%;">';
							print '<option>scenario 1</option>';
						foreach ($t_productPhone_product as $device)
						{
							$label = explode('-',$device['label']);
							$abo = explode('(',$label[0]);
							// listing des appareils scenario 1
							print '<option value="'.$device["fk_product"].'">';
								print $device["label"];
							print '</option>';
						}
						print '</select>';
						print '<select name="scenario_2" style="width: 20%;">';
							print '<option></option>';
						foreach ($t_productPhone_product as $device)
						{
							$label = explode('-',$device['label']);
							$abo = explode('(',$label[0]);
							// listing des appareils scenario 2
							print '<option value="'.$device["fk_product"].'">';
								print $device["label"];
							print '</option>';
						}
						print '</select>';
						print '<select name="scenario_3" style="width: 20%;">';
							print '<option></option>';
						foreach ($t_productPhone_product as $device)
						{
							$label = explode('-',$device['label']);
							$abo = explode('(',$label[0]);
							// listing des appareils scenario 3
							print '<option value="'.$device["fk_product"].'">';
								print $device["label"];
							print '</option>';
						}
						print '</select>';
						print '<select name="scenario_4" style="width: 20%;">';
							print '<option></option>';
						foreach ($t_productPhone_product as $device)
						{
							$label = explode('-',$device['label']);
							$abo = explode('(',$label[0]);
							// listing des appareils scenario 4
							print '<option value="'.$device["fk_product"].'">';
								print $device["label"];
							print '</option>';
						}
						print '</select>';
						print '<select name="scenario_5" style="width: 20%;">';
							print '<option></option>';
						foreach ($t_productPhone_product as $device)
						{
							$label = explode('-',$device['label']);
							$abo = explode('(',$label[0]);
							// listing des appareils scenario 5
							print '<option value="'.$device["fk_product"].'">';
								print $device["label"];
							print '</option>';
						}
						print '</select>';
						print '<select name="scenario_6" style="width: 20%;">';
							print '<option></option>';
						foreach ($t_productPhone_product as $device)
						{
							$label = explode('-',$device['label']);
							$abo = explode('(',$label[0]);
							// listing des appareils scenario 6
							print '<option value="'.$device["fk_product"].'">';
								print $device["label"];
							print '</option>';
						}
						print '</select>';
						print '<select name="scenario_7" style="width: 20%;">';
							print '<option></option>';
						foreach ($t_productPhone_product as $device)
						{
							$label = explode('-',$device['label']);
							$abo = explode('(',$label[0]);
							// listing des appareils scenario 7
							print '<option value="'.$device["fk_product"].'">';
								print $device["label"];
							print '</option>';
						}
						print '</select>';
					print '</div>';
				print '</form>';	
			print '</div>';
		print '</div>';
	print '</div>';

}

// Page end
dol_fiche_end();
llxFooter();
?>


