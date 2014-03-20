<?php header("Content-Type: text/html; charset=utf-8", true); 

/* Used for inicial development: 
** Objective: Export database configuration of Zabbix-Extras
** Copyright 2014 - Adail Horst - http://spinola.net.br/blog
**
** This file is part of Zabbix-Extras.
** It is not authorized any change that would mask the existence of the plugin. 
** The menu names, logos, authorship and other items identificatory plugin 
** should always be maintained.
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
** If not, see http://www.gnu.org/licenses/.
**/

require_once('include/config.inc.php');
require_once('include/zbxe_visual_imp.php');
$campos = "lang, tx_original, tx_new";
$res = DBselect("SELECT $campos FROM zbxe_translation");
$i=0;
while ($row = DBfetch($res)) {
    echo "preparaQuery(\"INSERT INTO zbxe_translation ($campos) VALUES("
       .quotestr($row['lang']).", "
       .quotestr($row['tx_original']).", "
       .quotestr($row['tx_new']).")\");<br>";
}

$campos = "userid, tx_option, tx_value, st_ativo";
$res = DBselect("SELECT $campos FROM zbxe_preferences");
$i=0;
while ($row = DBfetch($res)) {
    echo "preparaQuery(\"INSERT INTO zbxe_preferences ($campos) VALUES("
       .quotestr($row['userid']).", "
       .quotestr($row['tx_option']).", "
       .quotestr($row['tx_value']).", "
       .quotestr($row['st_ativo']).")\");<br>";
}

