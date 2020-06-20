<?php
require '../../main.inc.php';
require_once 'class/productphone.class.php';

// Access control
if( !$user->admin) accessforbidden();

//Initialisation de l objet
$_productPhone = new ProductPhone($db);

// Translation
$langs->load("productphone@productphone");

$p_fk_product_phone_raw = GETPOST('rowid');
$action = GETPOST('action');
$p_name_promotion = GETPOST('name_promotion');
$p_value_capaciti = GETPOST('value_capaciti');
$p_rowid_type_promotion = GETPOST('rowid_type_promotion');
$p_old_price = GETPOST('old_price');
$p_new_price = GETPOST('new_price');
$p_start_time = GETPOST('start_time');
$p_end_time = GETPOST('end_time');
$p_fk_product_phone = GETPOST('fk_product_phone');

$t_param = array(
	'rowid' => $p_fk_product_phone_raw,
);

//Action
if ($action == 'create_type_promo') {
	$_productPhone->create_typePromotion($p_name_promotion);
}

if ($action == 'create_promo') {
	$_productPhone->create_promotion(
		$p_value_capaciti
		,$p_fk_product_phone
		,$p_rowid_type_promotion
		,$p_old_price
		,$p_new_price
		,$p_start_time
		,$p_end_time
	);
}

// Si presence de l id du produit alors
if($p_fk_product_phone_raw)
{
    // Liste les appareils presents
    $t_productPhone = $_productPhone->fetch_productPhone_all($p_fk_product_phone_raw);
    // Si appareil present dans tableau alors remet le pointeur interne de tableau au debut
	if($t_productPhone) $t_productPhone = reset($t_productPhone);


}

//Récupération des données
$productphone_by_capacity_promotion = $_productPhone->fetch_productPhone_ByCapacity_promotion();
$type_promotion = $_productPhone->fetch_typePromotion();
$card_capacity = $_productPhone->count_capacity_promotion($t_productPhone['rowid']);


$title = 'promotion';
llxHeader('', $title);

// Configuration header
print load_fiche_titre($langs->trans($title));

// Configuration header
$head = productPhoneCardPrepareHead($t_param);
dol_fiche_head(
    $head,
    'promotion',
    $langs->trans("Module750504Name"),
    1,
    'productphone@productphone'
);

print '<div class="fiche">';
	print '<div class="tabBar">';
		print '<div class="fichecenter">';
			// ---------------------------------------
			// ---------------------------------------
			//AFFICHAGE GAUCHE PAGE
			print '<div class="fichehalfleft">';
				print '<p>Résumés des promotions</p>';
				foreach ($productphone_by_capacity_promotion as $value) {
					print '<div class="row">';
						print '<div  style="width: 95%">';
							print '<div>';
								print 'Fiche '.$value['capaciti'].' Go';
							print '</div>';
								print '<table>';
								print '<tr>';
									print '<th>Capacité</th>';
									print '<th>promotion</th>';
									print '<th>prix</th>';
									print '<th>début-fin</th>';
									print '<th>version pdf</th>';
									print '<th>version web</th>';
								print '</tr>';
									foreach ($card_capacity as $card){
										$parity = !$parity;
										print '<tr class="'.($parity?'pair':'impair').'">';
										if ($card['capaciti'] == $value['capaciti']) {
											print '<td>'.$value['capaciti'].' Go</td>';
											print '<td>'.$card['nom_promo'].'</td>';
											print '<td>'.$card['promo_price'].'</td>';
											print '<td>'.$card['start_time'].' au '.$card['end_time'].'</td>';
											print '<td>';
												print '<form action="'.dol_buildpath('/productphone/export_pdf.php',1).'" method="POST">';
													print '<input type="hidden" value="'.$p_fk_product_phone_raw.'" name="rowid">';
													print '<input type="hidden" value="'.$t_productPhone['rowid'].'" name="fk_product_phone">';
													print '<input type="hidden" value="'.$card['capaciti'].'" name="value_capaciti">';
													print '<input type="hidden" value="'.$card['type_promotion'].'" name="rowid_type_promotion">';		
													print '<button class="btn btn-primary" type="submit">PDF</button>';
												print '</form>';
											print '</td>';
											print '<td>';
												print '<form action="'.dol_buildpath('/productphone/export_html.php',1).'" method="POST">';
													print '<input type="hidden" value="'.$p_fk_product_phone_raw.'" name="rowid">';
													print '<input type="hidden" value="'.$t_productPhone['rowid'].'" name="fk_product_phone">';
													print '<input type="hidden" value="'.$card['capaciti'].'" name="value_capaciti">';
													print '<input type="hidden" value="'.$card['type_promotion'].'" name="rowid_type_promotion">';
													
													print '<button class="btn btn-primary" type="submit">WEB</button>';
												print '</form>';
											print '</td>';	
										}
										print '</tr>';
									}		
								print '</table>';
						print '</div>';	
					print '</div>';
				}
			print '</div>';
			// ---------------------------------------
			// ---------------------------------------
			//AFFICHAGE DROITE
			print '<div class="fichealfright" style="padding-left: 50%">';
				print '<div class="form-row">';
					print '<div class="col">';
						print '<p>créer une catégorie promotion</p>';
					print '</div>';
				print '</div>';
				print '<form action="'.dol_buildpath('/productphone/promo.php',1).'?action=create_type_promo" method="POST">';
					print '<input type="hidden" value="'.$p_fk_product_phone_raw.'" name="rowid">';
					print '<div class="form-row">';
						print '<div class="col">';
							print '<input type="text" name="name_promotion" placeholder="nom promotion">';
						print '</div>';
					
						print '<div class="col">';
							print '<button class="btn btn-info">créer</button>';
						print '</div>';
					print '</div>';
				print '</form>';
				print '<br>';
				print '<br>';
				
				//Créer une promotion
				print '<p>Créer une promotion</p>';
				print '<form action="'.dol_buildpath('/productphone/promo.php',1).'?action=create_promo" method="POST">';
					print '<input type="hidden" value="'.$p_fk_product_phone_raw.'" name="rowid">';
					print '<input type="hidden" id="fk_product_phone" value="'.$t_productPhone['rowid'].'" name="fk_product_phone">';
					print '<div class="form-row">
							<div class="col">
								<select name="value_capaciti" id="capaciti" class="form-control">';
									print '<option>capacité</option>';
								//capacité
								foreach ($productphone_by_capacity_promotion as $value) {
									print '<option value="'.$value['capaciti'].'">'.$value['capaciti'].'</option>';
								}
								print '</select>		
							</div>
							<div class="col">';			
							//promotion type
							print '<select class="form-control" name="rowid_type_promotion">';
								foreach ($type_promotion as $value) {
									print '<option value="'.$value['rowid'].'">'.$value['name'].'</option>';
								}	
							print '</select>';
						print '</div>';
					print '</div>';
					print '<br>';
					//price
					print '<div class="form-row">
							<div class="col" id="show_price">
								<input name="old_price" type="text" class="form-control" placeholder="Old price">
							</div>
							<div class="col">
								<input name="new_price" type="text" class="form-control" placeholder="New price">
							</div>
						</div>';
					print '<br>';
					
					//date
					print '<div class="form-row">
						<div class="col">
							<input name="start_time" type="date" class="form-control" placeholder="début">
						</div>
						<div class="col">
							<input name="end_time" type="date" class="form-control" placeholder="fin">
						</div>
					</div>';
					print '<br>';
					print '<button type="submit" class="btn btn-primary">Validé</button>';
				print '</form>';				
			print '</div>';
		
		//fin fichecenter
		print '</div>';
	//fin tabBar
	print '</div>';
//fin fiche
print '</div>';
dol_fiche_end();
// Page end
llxFooter();
?>
<script>

$(document).ready(function(){
	var capaciti;
	var fk_productphone;
	var phones_price_capaciti = {};

	//Récupère le prix "a nu" d'un téléphone en fonction de la capacité de stockage créer
	$('#capaciti').on('change',function(){

		capaciti = $(this).val();
		fk_product_phone = $('#fk_product_phone').val();
		get_price_capaciti(fk_product_phone,capaciti)
	});

	function get_price_capaciti(fk_product_phone,capaciti){
        $.ajax({
            type:"GET",
            url: "admin/ajax.php",
            dataType: "json",
            data: {
                'action': 'get_price_byCapacity'
                , 'fk_productphone': fk_product_phone
                ,'capaciti': capaciti},
                'success': function (data) {
					show_price(data);
                },
        });
    }

    function show_price(data){
        $('#show_price').empty();
		$.each(data, function(idx,el){
			element = el;
		});

		$.each(element, function(x,e){	
			$('#show_price').append('<input name="old_price" type="text" value="'+e.price_ttc+'" class="form-control" placeholder="Old price">');
		})
    }


});

</script>

