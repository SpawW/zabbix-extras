<?php
/* Used for inicial development: zbxe-geolocation.php
** Objective: Integration with Hierarquical Service Tree - SERPRO plugin
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

?>
<?php
require_once('include/config.inc.php');
require_once('include/page_header.php');
echo'<iframe width="98%" height="860" style="margin: 0 auto; display: block;" src="extras/service-tree/index.php"></iframe> ';
require_once('include/page_footer.php');
?>
