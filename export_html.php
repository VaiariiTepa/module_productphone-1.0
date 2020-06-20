<?php

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
$p_value_capaciti = GETPOST('value_capaciti');
$p_rowid_type_promotion = GETPOST('rowid_type_promotion');
$p_fk_product_phone = GETPOST('fk_product_phone');

// Si presence de l id du produit alors
if($p_fk_product_phone_raw)
{
	// Récupère 
	// Caractéristiques
	// Nom appareil
    $t_productPhone = $_productPhone->get_productPhone_ById($p_fk_product_phone_raw);
	
	// Récupère
	// Capacité stokage
	// Scénario
	// Prix + promo
	$t_capacity_promotion = $_productPhone->get_product_capacity_promotion(
		$p_value_capaciti
		,$p_rowid_type_promotion
		,$p_fk_product_phone
	);

	// Récupère
	// nom des promotion
	$t_name_promotion = $_productPhone->get_name_promotion();

}

?>

<!DOCTYPE html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			/* th{
				border: 1px black solid;
			}

			td{
				border: 1px black solid;
			} */
			/* tr{
				border: 1px black solid;
			} */

			#box_color{
				display: flex;
				align-items: flex-start;
				/* justify-content: space-between; */
			}

			#circle_silver{
				background-color: grey;
				border: 2px solid black;
				width: 40px;
				height: 40px;
				border-radius: 50%;
				margin-right: 16px;
			}

			#circle_rose_gold{
				background-color: #fe94a3;
				border: 2px solid black;
				width: 40px;
				height: 40px;
				border-radius: 50%;
			}

			h1{
				/* border: 1px black solid; */
			}
			#bandeau{
				/* border: 1px black solid; */
			}

			#all_price.table{
				width: auto;
				border: 1px red solid;
			}
			table{
				width: 100%;
				/* border: 1px black solid; */
			}

			#body_caracteristique{
				margin: auto;
				width: 80%;
			}

			#couleur{
				margin: auto;
				width: 80%;
			}

			#all_price{
				margin: auto;
				width: 80%;
			}

			#caracteristique{
				margin: auto;
				width: 100%;
				text-align: center;
				/* border: 1px green solid; */
			}

			#header{
				margin: auto;
				width: 80%;
				text-align: center;
				/* border: greenyellow 2px solid; */
			}

			.price_value{
				width: 50%;
				background-color: #EBEBEB;
				text-align: right;
				font-size: xx-large;
			}

			.price_field{
				width: 50%;
				background-color: #EBEBEB;
				text-align: left;
				font-size: x-large;
			}

			.titre_abonnement{
				font-size: larger;
			}

			#titre_sans_engagement{
				font-size: larger;
			}

			#value_sans_engagement{
				font-size: larger;
				background-color: #EBEBEB;
			}

			#price_raw{
				text-align: center;
			}

			#logo_footer{
				/* border: 1px solid black; */
				position: fixed;
				height: 400px;
				bottom: 0;
				text-align: right;
				width: 100%;
				z-index: 2;
			}

			h5.blocktextdroit {
				margin-top: 8px;
				margin-left: 0px;
				margin-bottom: 5px;
				margin-right: 0px;
				text-align: right;
			}

			div#icone1{
				margin: auto;
				width: 100%;
				height: 150px;    
				border: solid 1px black;
				background-color: red;
			}

			div#icone2{
				margin: auto;
				width: 100%;
				height: 150px;    
				border: solid 1px black;
				background-color: #EBEBEB;
			}

			div#icone3{
				margin: auto;
				width: 100%;
				height: 150px;    
				border: solid 1px black;
				background-color: yellowgreen;
				
			}

			div#icone4{
				margin: auto;
				width: 100%;
				height: 150px;    
				border: solid 1px black;
				background-color: purple;
			}

			div#icone5{
				margin: auto;
				width: 100%;
				height: 150px;    
				border: solid 1px black;
				left: 207px;
				background-color: blue;
			}

			div#icone6{
				margin: auto;
				width: 100%;
				height: 150px;
				border: solid 1px black;
				left: 207px;
				background-color: maroon;
			}

			div#couleurtel{
				height: 50px;
				margin: 0px 0px 0px 0px;
				border: solid 1px black;
				top: 172px;
				right: 362px;
				left: 47px;
			}

			div#typetel{
				height: 50px;
				border: solid 1px black;
				top: 20px;
				left: 213px;
			}

			.price{
				color: red;
			}

			.blocktextcentre{
				text-transform: uppercase;
			}

			.old_price{
				text-decoration:line-through;
			}

			div#capacitetel{
				height: 50px;
				border: solid 1px black;
				top: -54px;
				left: 468px;
			}
			
			@page {
				size: A5;
				margin: 0;
			}

			@media print {

				html, body {
				width: 210mm;
				height: 297mm;
				}
			}
			
			body {
				display: block;
				margin: 0px 0px 0px 0px;
			}

		</style>
    </head>
<body>
<?php
print '<div class="container">';
	print '<div id="bandeau">';
		print '<img width="100%" class="enhaut"src="img\productpad\bandeau_tatoo.png">';
	print '</div>';
print '</div>';

print '<div class="container-fluid">';
	//header + marque + modèle
	print '<div id="header">';
		print '<div>';
			print '<p>';
				foreach ($t_capacity_promotion as $value) {
					// var_dump($value);
					$das = explode('(',$value['sar_eu']);
					$device = explode(' ',$value['DeviceName']);
					
					print '<h1 class="blocktextcentre">'.$device[0].'<br>';
					
					array_shift($device);
					$devicename = implode(' ',$device);
					
					print $devicename.'</h1>';

					print '<h5 class="blocktextdroit"> Taux de DAS : '.$das[0].'</h5>';
					break;
				}
			print '</p>';
		print '</div>';
	print '</div>';

	//caracteristique
	print '<div id="body_caracteristique">';		
		print '<div class="separation">';
			print '<center>';
				print '<img width="100%" src="img\productpad\separation.png">';
			print '</center>';
		print '</div>';
		print '<br>';
		print '<div id="caracteristique">';
			foreach ($t_capacity_promotion as $value) {
				print '<table>';
					print '<tr>';
						print '<th width="33.33%">';
							print '<img src="img\productpad\phone_size.png">';
						print '</th>';
						print '<th width="33.33%">';
							if($value['connexion_type'] == '4G') {
								print '<img src="./img/4G.png" >';
							}else{
								print '<img src="./img/3G.png">';
							}
						print '</th>';
						print '<th width="33.33%">';
							print '<img src="img\productpad\camera_resolution.png">';
						print '</th>';
					print '</tr>';
					print '<tr>';
						print '<td width="33.33%">';
							print '<b>';
								print $value['phone_size'].'"';
							print '</b>';
						print '</td>';
						print '<td width="33.33%">';
							print '<b>';
								print 'Connexion';
							print '</b>';
						print '</td>';
						print '<td width="33.33%">';
							print '<b>';
								print $value['primary_camera_resolution'];
							print '</b>';
						print '</td>';
					print '</tr>';
				print '</table>';
				print '<br>';
				print '<table>';
					print '<tr>';
						print '<th width="33.33%">';
							print '<img src="img\productpad\Processeur.png">';
						print '</th>';
						print '<th width="33.33%">';
							print '<img src="img\productpad\Dual-sim.png">';
						print '</th>';
						print '<th width="33.33%">';
							print '<img width="100" src="img\productpad\batterie.png">';
						print '</th>';
					print '</tr>';
					print '<tr>';
						print '<td width="33.33%">';
							print '<b>';
								print $value['cpu_number'].' core';
									print '<br>';
								print $value['cpu_speed'];     
							print '</b>';
						print '</td>';
						print '<td width="33.33%">';
							if ($value['dual_sim'] == "dual-sim" || $value['dual_sim'] == "Dual SIM") {
								print '<b>';
									print 'Dual';
								print '</b>';
							}else{
								print '<b>';
									print 'Single';
								print '</b>';
							}
						print '</td>';
						print '<td width="33.33%">';
							$battery = explode(' ',$value['battery_capacity']);
							print '<b>';
								print $battery[1].' mAh';
							print '</b>';
						print '</td>';
					print '</tr>';
				print '</table>';
			print '</div>';
			print '<br>';
			print '<div class="separation">';
				print '<center>';
					print '<img width="100%" src="img\productpad\separation_bas.png">';
				print '</center>';
			print '</div>';
		print '</div>';
		print '<br>';
		print '<div id="couleur">';
			print '<table width="100%" id="table_color">';
				print '<tr>';
					print '<td width="50%" colspan="3" id="box_color">';

						// Affichage des couleurs
						$t_color = array();
						$t_couleur = explode(',',$value['phone_color']);
					
						foreach ($t_couleur as $v) {
							$v = trim($v);
							$t_color[] = $v;
						}
						
						foreach($t_color as $color){
						
							if ($color == 'Silver') {
								print '<div id="circle_silver"></div>';
								
							}elseif($color == 'Rose Gold'){
								
								print '<div id="circle_rose_gold"></div>';
						
							}elseif($color == 'Black'){
								print 'ce nest pas gris';
						
							}elseif($color == 'Black'){
								print 'ce nest pas gris';
						
							}elseif($color == 'Black'){
								print 'ce nest pas gris';
						
							}elseif($color == 'Black'){
								print 'ce nest pas gris';
						
							}elseif($color == 'Black'){
								print 'ce nest pas gris';
						
							}elseif($color == 'Black'){
								print 'ce nest pas gris';
						
							}elseif($color == 'Black'){
								print 'ce nest pas gris';
						
							}elseif($color == 'Black'){
								print 'ce nest pas gris';
						
							}elseif($color == 'Black'){
								print 'ce nest pas gris';
						
							}
						}
						print '</div>';
					print '</td>';
					print '<td width="25%">';
					if ($value['os_name'] == 'Android') {
						print '<img width="35" src="img\productpad\android.png">';
						print '<b>';
						print '</b>';
						print $value['os_version'];
					}elseif($value['os_name'] == 'iOS'){
						print '<img width="35" src="img\productpad\apple.png">';
						print '<b>';
							print $value['os_version'];
						print '</b>';
					}else{
						print 'Memoire interne :';
					}
					print '</td>';
					print '<td width="25%">';
						print '<div>';
							print '<img width="35" src="img\productpad\memoire.jpg">';
							print '<b>';
								print $value['capaciti'];
							print '</b>';
						print '</div>';
					print '</td>';
				print '</tr>';
			print '</table>';
		print '</div>';
		print '<br>';
		print '<br>';

		break;
		}
		print '<div id="all_price">';
			print '<table>';
				print '<th colspan="2" id="titre_abonnement">ABONNEMMENT';
				print '</th>';
				array_shift($t_capacity_promotion);
				// var_dump($t_capacity_promotion);
				foreach ($t_capacity_promotion as $value) {
					
					$label = explode('-',$value['label']);
					$abo = explode('(',$label[0]);
					$price = substr($value['price_ttc'], 0, -9);
					// var_dump($price);
					if($price < 1){
						if($abo[2]){
							print '<tr>';
								print '<td class="price_field">';
									print $abo[2];
								print '</td>';
								print '<td class="price_value">';
								print '<b>';
									print '1 F';
								print '</b>';
								print '</td>';
							print '</tr>';
						}
					}else{
						if ($abo[2]) {
							// $scenario .='<li>'.$abo[2].'<b>'.substr($device['price_ttc'], 0, -9).' F</b></li>';
							print '<tr>';
								print '<td class="price_field">';
									print $abo[2];
								print '</td>';
								print '<td class="price_value">';
									print '<b>';
										print substr($value['price_ttc'], 0, -9).' F';
									print '</b>';
								print '</td>';
							print '</tr>';
						}
					}
				}
			print '</table>';
			foreach ($t_capacity_promotion as $date) {
				if ($date['name'] !== 'nu') {
					
					$start_date = explode('-',$date['start_time']);
					$end_date = explode('-',$date['end_time']);
					print '* Promotion valide du '.$start_date[2].'/'.$start_date[1].' au '.$end_date[2].'/'.$end_date[1].'/'.$end_date[0];
				}
			break;
			}
			print '<br>';
			print '<br>';
			print '<table>';
				print '<th colspan="2" id="titre_sans_engagement">SANS ENGAGEMENT';
				print '</th>';
			print '</table>';
			print '<table id="value_sans_engagement">';
				print '<tr id="price_color">';
				foreach ($t_capacity_promotion as $value) {
					if ($value['fk_product_phone_type_promotion'] > 1) {
						print '<td class="old_price"><h2>'.$value['old_price'].'</h2></td>';
						print '<td class="price"><h1>'.$value['promo_price'].'</h1></td>';
					}else{
						print '<td><h2></h2></td>';
						print '<td class="price"><center><h1>'.$value['old_price'].'</h1></center></td>';
					}
				break;
				}
				print '</tr>';
				print '<tr></tr>';
			print '</table>';
			print '<div>';
				foreach ($t_capacity_promotion as $value) {
					print 'ref '.$value['ref'];
				break;
				}
			print '</div>';
		print '</div>';
	print '</div>';
	print '<div class="container-fluid">';
		print '<div id="logo_footer">';
			print '<img width="400" src="img\productpad\logo_footer_right.png">';
		print '</div>';
	print '</diV>';

?>

</div>

</body>
</html>



