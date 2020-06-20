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
 * \file    css/mycss.css.php
 * \ingroup mymodule
 * \brief   Example CSS.
 *
 * Put detailed description here.
 */

header('Content-Type: text/css');
?>


#table_productphone_raw>tbody>tr.selected>td{ background-color:#FFFFCC }
#table_product>tbody>tr.selected>td{ background-color: #FFFFCC }

#table_productphone_product>tbody>tr.selected>td{ background-color: #FFFFCC }

#header_accordion>div>b.selected { background-color: #00adee }

div#cadre { background-color: #dcdee2 }


.vertical-menu {
    margin-left: 70%;
    background-color: lightgrey;
    text-align: center;
    padding-top: 3%;
    padding-bottom: 3%;
}

.vertical-menu select {
    background-color: #eee;
    color: Black;
    width: 90%;
    max-width: 90%;
}

.vertical-menu select:hover {
    background-color: #ccc; /* Dark grey background on mouse-over */
}

.vertical-menu select.active {
    background-color: #4CAF50; /* Add a green color to the "active/current" link */
    color: white;
}

#label_forfait
{
    display: block;
    align-content: center;
    background-color: #C0C0C0;
    border: 1px solid grey;
    border-radius: 1px 1px 10px 10px;
    min-width: 300px;
    max-width: 300px;
}

#forfait
{
    text-align: center;
    display: block;
}

#price_header
{
    border-radius: 1px 10px 0px 0px;
    font-family: Impact, fantasy;
    width: 100px;
    text-align: center;
    display: block;
}

#color_header_value
{
    font-family: Luminari, fantasy;
    min-width: 120px;
    max-width: 120px;
}

#label_color
{
    background-color: #C0C0C0;
    border: 1px solid grey;
    border-radius: 1px 1px 10px 10px;
    width: 120px;
    display: block;
    align-content: center;
}

#color_header
{
    border-radius: 10px 0px 1px 1px;
    width: 120px;
    display: flex;
    flex-wrap: wrap;
    text-align: center;
}

#couleur
{
    text-align: center;
    display: block;
}

#label_price
{
    background-color: #C0C0C0;
    border: 1px solid grey;
    border-radius: 1px 1px 10px 10px;
    min-width: 100px;
    max-width: 100px;
    display: block;
    align-content: center;
}

#prix
{
    text-align: center;
    display: block;
}

/**/
#accordion
{
/*    border: 1px solid black;*/
}

.assenceur_catalogue
{
    max-height: 800px;
    width: 165%;
}

/*@media screen and (min-width: 200px) and (max-width: 640px){*/
/*    .assenceur_catalogue*/
/*    {*/
/*        max-height: 730px;*/
/*        width: 165%;*/
/*    }*/
/*}*/
/**/
/*@media screen and (min-width: 1000px) and (min-height: 600px){*/
/*    .assenceur_catalogue*/
/*    {*/
/*        max-height: 600px;*/
/*        width: 165%;*/
/*    }*/
/*}*/
/**/
/*@media screen and (min-width: 1800px){*/
/*    .assenceur_catalogue*/
/*    {*/
/*        max-height: 800px;*/
/*        width: 165%;*/
/*    }*/
/*}*/




#contenue_accordion
{
    min-height: 100px;
}

#table_contenue_accordion
{
    width: 100%;
}


/***/
/*{*/
/*    border: solid black 1px;*/
/*}*/

/*#photo*/
/*{*/
/*/*    border: 1px solid black;*/*/
/*/*    width: 200px;*/*/
/*/*    height: 300px;*/*/
/*/*    display: block;*/*/
/*/*    float: left;*/*/
/*}*/
