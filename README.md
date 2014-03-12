# Introduction: Zabbix Extras

This project is intended as extend the native options of [Zabbix](http://www.zabbix.com).

It changes the frontend of your Zabbix to allow access to new functions, including: 
- Capacity and Trend Report 
- Report of items not supported 
- Estimated costs of storage with the items listed 
- Interrelationships between events (temporal analysis of possible causes and effects)
- Customization of the frontend logo based on image from Zabbix network
map images (facilitates customization by companies). 
- Menus derived from the database (useful for adding your own menus to Zabbix) 
- Support for literal values ​​in graphs (without using scales)

It connects to your Zabbix API, using your Zabbix credentials. Afterwards, all settings are pulled from your Zabbix.
This means any change you make in your Zabbix, will be transported to the Mobile ZBX application.

If you're worried about security implications, please read more on the FAQ at http://www.mozbx.net.

For more install guides (in Brazilian Portuguese), please see http://spinola.net.br/blog or... use Google Translator to get english version: http://translate.google.com.br/translate?sl=pt&tl=en&js=n&prev=_t&hl=pt-BR&ie=UTF-8&u=zabbix.spinola.net.br&act=url.

It also adds two other plugins with very interesting features (but kept by other authors): 
- Hierarchical Tree Service - SERPRO / Rodrigo Dias 
- ZGeo - Plugin Zabbix integration with Google Maps - Aristotenes Araújo.


##
### Installation instructions

Download file "instalaExtras.sh" run with SH command:
sh ./instalaExtras.sh
Follow the instructions (the first screen is the language selection).
After the install... refresh zabbix interface (F5) and use the new Menu item called "EXTRAS".

### About the author

- Zabbix-Extras Built by [Adail Horst](http://spinola.net.br)
- Submit your issues using [the Github issue tracker](hhttps://github.com/SpawW/zabbix-extras/issues)
