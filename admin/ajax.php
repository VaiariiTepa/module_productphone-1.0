<?php
/* Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *   	\file       dev/skeletons/skeleton_page.php
 *		\ingroup    mymodule othermodule1 othermodule2
 *		\brief      This file is an example of a php page
 *					Put here some comments
 */

// Load Dolibarr environment
if (false === (@include '../../main.inc.php')) {  // From htdocs directory
    require '../../../main.inc.php'; // From "custom" directory
}

global $langs, $user;
// Change this following line to use the correct relative path from htdocs
//dol_include_once('/module/class/skeleton_class.class.php');

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
dol_include_once("productphone/class/productphone.class.php");
// Translations
$langs->load("productphone@productphone");
$langs->load("errors");



// Get parameters
$p_action	= GETPOST('action');
$t_response = array('status'=>'', 'message'=>'', 'data'=>array());


// Access control SI UTILISATEUR N'EST PAS ADMIN, ALORS NE PAS AUTORISER L'ACCES
if (! $user->admin){
    $t_response['status'] = 'error';
    $t_response['message'] = $langs->trans('ErrorForbidden');
    echo json_encode($t_response);
    exit;
}

switch($p_action){
    //récupere product qui sont rataché a productphone
    case 'get_product_from_productphone':
        $_ProductPhone = new ProductPhone($db);
        //recuperation des parametres
        $t_fk_productphone_raw = GETPOST('fk_productphone_raw');
        $t_getProductPhone = $_ProductPhone->get_product_from_productphone($t_fk_productphone_raw);
        $t_response['status'] = 'success';
        $t_ProductPhone = array();
        if($t_getProductPhone){
            foreach($t_getProductPhone as $id => $productphone){
                $t_ProductPhone[] = array_intersect_key(
                    $productphone,
                    array_flip(array('fk_product','ref','label'))
                );
            }
            $t_response['data'] = $t_ProductPhone;
        }else{
            $t_response['status'] = 'error';
        }
//        $_ProductPhone->gen_product_phone($t_fk_product_phone);
    break;

    //Ratache product a product_phone dans llx_product_phone_product
    case 'set_or_unset_select_product':
        $_ProductPhone = new ProductPhone($db);
        //recuperation des parametres
        $t_fk_productphone_raw = GETPOST('fk_productphone_raw');
        $fk_product = GETPOST('fk_product');
        $_ProductPhone->set_or_unset_productphone_product($t_fk_productphone_raw,$fk_product);
        $_ProductPhone->gen_product_phone($t_fk_productphone_raw);
        break;

    case 'unset_all_product':
        $_ProductPhone = new ProductPhone($db);
        //recuperation des parametres
        $t_fk_productphone_raw = GETPOST('fk_productphone_raw');
        $_ProductPhone->unset_all_productphone_product($t_fk_productphone_raw);
        $_ProductPhone->gen_product_phone($t_fk_productphone_raw);
        break;

    case 'set_all_product':
        $_ProductPhone = new ProductPhone($db);
        $t_fk_productphone_raw = GETPOST('fk_productphone_raw');

        $t_fk_product = json_decode(GETPOST('fk_products'));
        $_ProductPhone->set_all_productphone_product($t_fk_productphone_raw,$t_fk_product);
        $_ProductPhone->gen_product_phone($t_fk_product_phone);
        break;

    case 'unset_productphone_product':
        $_ProductPhone = new ProductPhone($db);
        $t_fk_productphone_raw = GETPOST('fk_productphone_raw');
        $fk_product = GETPOST('fk_product');
        $_ProductPhone->unset_productphone_product($t_fk_productphone_raw,$fk_product);
        $_ProductPhone->gen_product_phone($t_fk_productphone_raw);
        break;

    /**
     * Permet de récupérer les valeurs en fonction du "Field" selectionner config.php
     */
    case 'get_filter':
        $_ProductPhone = new ProductPhone($db);
		$filter_value = GETPOST('get_value_filter');
		
        $t_ProductPhone = $_ProductPhone->gen_value_filter($filter_value);
		
		if ($t_ProductPhone){
            $t_response['data']['FieldValue'] = json_encode($t_ProductPhone);
            $t_response['status'] = 'succes';
        }else {
            $t_response['status'] = 'error';
        }
		break;
		
    case 'get_filter_price':
        $_ProductPhone = new ProductPhone($db);
        $filter_value = GETPOST('get_value_filter_price');
        $t_ProductPhone = $_ProductPhone->gen_value_filter_price();
        if ($t_ProductPhone){
            $t_response['data']['FieldValue'] = json_encode($t_ProductPhone);
            $t_response['status'] = 'succes';
        }else {
            $t_response['status'] = 'error';
        }
		break;
		
    case 'get_price_byCapacity':
        $_ProductPhone = new ProductPhone($db);
        $fk_productphone = GETPOST('fk_productphone');
        $capaciti = GETPOST('capaciti');
        $t_ProductPhone = $_ProductPhone->fetch_productPhone_price_ByCapacity($fk_productphone,$capaciti);
        if ($t_ProductPhone){
            $t_response['data'] = $t_ProductPhone;
            // $t_response['status'] = 'succes';
        }else {
            $t_response['status'] = 'error';
        }
        break;
}

echo json_encode($t_response);
