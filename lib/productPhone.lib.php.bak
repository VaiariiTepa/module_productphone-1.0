<?php

/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    lib/mymodule.lib.php
 * \ingroup mymodule
 * \brief   Example module library.
 *
 * Put detailed description here.
 */

/**
 * Prepare admin pages header
 *
 * @return array
 */
function productPhoneAdminPrepareHead()
{
    global $langs, $conf;

    $langs->load("productphone@productphone");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/productphone/admin/manage.php", 1);
    $head[$h][1] = $langs->trans("ManagementCards");
    $head[$h][2] = 'correspondance';
    $h++;

    $head[$h][0] = dol_buildpath("/productphone/admin/import.php", 1);
    $head[$h][1] = $langs->trans("ImportingCard");
    $head[$h][2] = 'import';
    $h++;

    $head[$h][0] = dol_buildpath("/productphone/admin/config.php", 1);
    $head[$h][1] = $langs->trans("ProductPhoneSetup");
    $head[$h][2] = 'configuration du module';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$this->tabs = array(
    //    'entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
    //    'entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'mymodule');

    return $head;
}

/**
 * Prepare index pages header
 * @return array
 */
function productPhoneCardPrepareHead($t_param = array())
{
    global $langs, $conf;

    $param = '';
    foreach ($t_param as $k => $v) {
        $param .= (!empty($param) ? '&' : '') . $k . '=' . $v;
    }
    if ($param) {
        $param = '?' . $param;
    }

    $langs->load("productphone@productphone");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/productphone/fiche.php" . $param, 1);
    $head[$h][1] = $langs->trans("Productfiche");
    $head[$h][2] = 'fiche_produit';
    $h++;

    $head[$h][0] = dol_buildpath("/productphone/fichebrut.php" . $param, 1);
    $head[$h][1] = $langs->trans("Productficheraw");
    $head[$h][2] = 'produit_associer';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$this->tabs = array(
    //    'entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
    //    'entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'mymodule');

    return $head;
}

function generateInputHTMLofFilter($_filter)
{
    global $conf, $langs, $db;
    $t_return = array();
    if ($_filter) {
        foreach ($_filter as $k_filter => $v_filter) {

            //Si le champs "active" est a 1, alors continuer
            if (!$v_filter['active']) {
                continue;
            }

            $input_id = 'filter_' . $v_filter['field'];
            $input_name = $v_filter['field'];
            $label_name = $v_filter['label'];
            $value_name = $v_filter['t_value'];
            $attr_id_name = 'name="' . $input_name . '" id="' . $input_id . '"';
            $attr_checkbox_id_name = 'name="' . $input_name . '[]" id="' . $input_id . '"';

            $label = '';
            $input = '';
            $p_value = '';
            $p_value = GETPOST($input_name);

            // input
            switch ($v_filter['type']) {

                // ok
                case 'select':
                    $label .= '<label for="' . $input_id . '">' . $label_name . '</label>';
                    $input .= '<select ' . $attr_id_name . '>';
                    foreach ($v_filter['t_value'] as $value) {
                        $selected = (isset($p_value) && ($p_value == $value) ? ' selected' : "");
                        $input .= '<option value="' . $value . '"' . $selected . '>' . $value . '</option>';
                    }
                    $input .= '</select>';
                    break;

                // ok
                case 'radio':
                    $label .= '<label>' . $label_name . '</label>';
                    foreach ($v_filter['t_value'] as $value) {
                        $checked = (isset($p_value) && ($p_value == $value) ? ' checked="checked"' : "");
                        $input .= '<label><input type="' . $v_filter['type'] . '" ' . $attr_id_name . ' value="' . $value . '"' . $checked . '>' . $value . '</label><br>';
                    }
                    break;

                // ok
                case 'checkbox':
                    $label .= '<label>' . $label_name . '</label>';
                    foreach ($v_filter['t_value'] as $value) {
                        $checked = (($p_value && in_array($value, $p_value)) ? 'checked="checked"' : '');
                        $input .= '<label><input type="' . $v_filter['type'] . '" ' . $attr_checkbox_id_name . ' value="' . $value . '"' . $checked . '>' . $value . '</label><br>';
                    }
                    break;

                default:
                    $label .= '<label for="' . $input_id . '">' . $label_name . '</label>';
                    foreach ($v_filter['t_value'] as $value) {
                        $input .= '<label><textarea ' . $input_name . '>' . (isset($p_value) && $p_value == $value ? $value : '') . '</textarea></label>';
                    }
                    break;

                    // case 'button':
                    //     $label .= '<label for="' . $input_id . '">' . $label_name . '</label>';
                    //     foreach ($v_filter['t_value'] as $value) {
                    //         $input .= '<button type="' . $v_filter['type'] . '" ' . $attr_id_name . ' value="' . $value . '">' . $value . '</button>';
                    //     }
                    //     break;

            }
            $t_return[$v_filter['field']] = array('label' => $label, 'input' => $input);
        }
        return $t_return;

    }

}