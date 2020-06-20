<?php

require '../../main.inc.php';
require_once TCPDF_PATH.'tcpdf.php';
require_once "class/productphone.class.php";

// Access control
if( !$user->admin) accessforbidden();

//Initialisation de l objet
$_productPhone = new ProductPhone($db);


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


/**
 * View
 */

 $content .= '<div id="header">
 				<img height="300%" width="810" class="enhaut"src="img\productpad\bandeau_tatoo.png">';
				
				foreach ($t_name_promotion as  $v) {
					foreach ($t_capacity_promotion as $value) {
						if ($value['name'] == $v['name']) {
							$content .= '<b><h3>'.$v['name'].'</h3></b>';
						break;
						}

						
					}
				}



				$content .= '<div id="bandeau">
					<h1 class="blocktextcentre">Samsung galaxi A80</h1>
					<h5 class="blocktextdroit"> Taux de DAS : 0.242w/kg </h5>
				
					<div class="separation">
						<img src="img\productpad\separation.png">
					
						<div id="caracteristique">		
							<table>
								<tr>
									<th>
										<div id ="icone1">icone1</div>            
									</th>
									<th>
										<div id="icone2">icone2</div>
									</th>
									<th>
										<div id="icone3">icone3</div>
									</th>
								</tr>
								<tr>
									<td>
										valeur 1er colonne
									</td>
									<td>
										valeur 2em colonne
									</td>
									<td>
										valeur 3em colonne
									</td>
								</tr>
							</table>
							<table>
								<tr>
									<th>
										<div id="icone4">icone4</div>            
									</th>
									<th>
										<div id="icone5">icone5</div>
									</th>
									<th>
										<div id="icone6">icone6</div>
									</th>
								</tr>
								<tr>
									<td>
										valeur 4em colonne            
									</td>
									<td>
										valeur 5em colonne
									</td>
									<td>
										valeur 6em colonne
									</td>
								</tr>
							</table>
							<img src="img\productpad\separation_bas.png">
						</div>
						<div id="couleur">
							<table width="100%" id="table_color">
								<tr>
									<td width="50%" colspan="3">
										Couleur
										<div id="couleurtel">couleur disponible</div>
									</td>
									<td width="25%">
										Type téléphone
										<div id="typetel"> type de téléphone</div>
									</td>
									<td width="25%">
										Capacité
										<div id="capacitetel">Capicité disponible </div>
									</td>
								</tr>
							</table>
						</div>
					
					//fin séparation
					</div>

					<table>
					<tr>
						<th colspan="2">ABONNEMMENT</th>
					</tr>'; 

					foreach ($t_capacity_promotion as $device) {
						$label = explode('-',$device['label']);
						$abo = explode('(',$label[0]);

						if($device['price_ttc'] < 1){
							if($abo[2]){

								$content .='<tr><td class="price_field">'.$abo[2].'</td><td><b>1 F</b></td></tr>';
							}

							
						}else{
							if ($abo[2]) {
								$content .='<tr><td class="price_field">'.$abo[2].'</td><td class="price_value"><b>'.substr($device['price_ttc'], 0, -9).' F</b></td></tr>';
							}
							
						}
					}

					
				$content .= '</table>
				<table>
					<tr>
						<th colspan="2">PREPAYEE</th>
					</tr>
					<tr>
						<td class="price_field">abo>5000</td>
						<td class="price_value"><b>Prix</b></td>
					</tr>
					<tr>
						<td class="price_field">abo>2500</td>
						<td class="price_value"><b>Prix</b></td>
					</tr>
				</table>
				<table>
					<tr>
						<th colspan="3">SANS ENGAGEMENT</th>
					</tr>
					<tr>';
					foreach ($t_capacity_promotion as $value) {
						if ($value['fk_product_phone_type_promotion'] > 1) {
							
							$content .= '<td class="old_price"><h2>'.$value['old_price'].'</h2></td>';
							$content .= '<td class="price"><h2>'.$value['promo_price'].'</h2></td>';
							$content .= '<td><h2></h2></td>';
						}else{
							$content .= '<td><h2></h2></td>';
							$content .= '<td class="price"><h2>'.$value['old_price'].'</h2></td>';
							$content .= '<td><h2></h2></td>';

						}	

					break;

					}

					$content .= '</tr>
			
				</table>

				//fin bandeau	
				</div>
			//fin header
			</div>';


$style = '
	<style>
	@page {
		size: A4;
		margin: 0;
	}
	
	@media print {
	
		html, body {
		  width: 210mm;
		  height: 297mm;
		}
	}
	
	th{
		text-align:center;
	}
	
	h1{
		text-align: center;
		border: 1px black solid;
	}
	h5{
		border: 1px black solid;
	}
	#bandeau{
		width: 80%;
		margin-left: 100px;
		margin-right: 100px;
		height: 472.5;
	}

	.price{
		text-align: center;
	}
	
	.old_price{
		text-decoration:line-through;
	}

	#all_price.table{
		width: auto;
		border: 1px red solid;
	}
	table{
		width: 100%;
	}
	
	
	#couleur{
		margin: auto;
		width: 100%;
	}

	#header{

	}
	
	#all_price{
		margin: auto;
		width: 100%;
	}

	.separation{
		min-width: 100%
	}
	
	#caracteristique{
		width: 100%;
		text-align: center;
	}
	
	
	.price_value{
		width: 50%;
		background-color: #DCDCDC;
		text-align: right;
	}
	
	.price_field{
		width: 50%;
		background-color: #DCDCDC;
		text-align: left;
	}
	
	#price_raw{
		text-align: center;
	}

	#device_name{
		width: 20px;
	}
	
	#bandeau {
		height: 50px;
		width: 100%;
		text-align: center;
		border: solid 1px black;
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
		height: 100%;    
		/* padding-left:35px; */
		
		/* float: left; */
		background-color: red;
	}
	div#icone2{
		margin: auto;
		width: 100%;
		height: 100%;    
		/* padding-left:35px; */
		
		/* float: left; */
		background-color: grey;
	}
	div#icone3{
		margin: auto;
		width: 100%;
		height: 100%;    
		/* padding-left:35px; */
		
		/* float: left; */
		background-color: yellowgreen;
		
	}
	div#icone4{
		margin: auto;
		width: 100%;
		height: 100%;    
		/* padding-left:35px; */
		
		/* float: none; */
		background-color: purple;
	}
	div#icone5{
		margin: auto;
		width: 100%;
		height: 100%;    
		/* padding-left:35px; */
		
		/* float: left; */
		/* position: absolute; */
		left: 207px;
		background-color: blue;
	}
	div#icone6{
		margin: auto;
		width: 100%;
		height: 100%;
		/* padding-left: 35px; */
		
		/* float: left; */
		/* position: absolute; */
		left: 207px;
		background-color: maroon;
	}
	
	div#couleurtel{
		height: 50px;
		margin: 0px 0px 0px 0px;
		border: solid 1px black;
		/* position: absolute; */
		top: 172px;
		right: 362px;
		left: 47px;
	
	}
	div#typetel{
		height: 50px;
		border: solid 1px black;
		/* position: absolute; */
		top: 20px;
		left: 213px;
	
		
	}
	div#capacitetel{
		height: 50px;
		border: solid 1px black;
		/* position: absolute; */
		top: -54px;
		left: 468px;
		
	}
		
	</style>n
	';
$width = 283.5;
$height = 473;
$pageLayout = array($width, $height);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $pageLayout, true, 'UTF-8', false);

// $pdf->setHeaderData($logo,$width_logo);
$pdf->setPrintHeader(False);
$pdf->setPrintFooter(False);

// set margins
$pdf->SetMargins(0,0,0);

$pdf->SetFont('helvetica','',12);

$pdf->AddPage();

$pdf->writeHTML($style.$content,true,false,true,false,'');
$pdf->writeHTML($style.$scenario,true,false,true,false,'');
// $pdf->writeHTML($style.$old_price,true,false,true,false,'');
// $pdf->writeHTML($style.$footer,true,false,true,false,'');

	


$pdf->lastPage();

$pdf->Output($devicename.'_ProductPad','I');

// Page end
dol_fiche_end();
llxFooter();
?>
<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
