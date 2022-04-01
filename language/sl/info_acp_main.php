<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2018 Martin ( https://github.com/Mar-tin-G )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* Slovenian Translation - Marko K.(max, max-ima,...)
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_EMPTYPOSTSUBJECTS_TITLE'				=> 'Prazne teme objave/<br>Empty Post Subjects',
	'ACP_EMPTYPOSTSUBJECTS_SETTINGS'			=> 'Nastavitve',
	'ACP_EMPTYPOSTSUBJECTS_SETTING_SAVED'		=> 'Nastavitve so bile uspešno shranjene!',
	'ACP_EMPTYPOSTSUBJECTS_REPLY'				=> 'Izpraznite zadevo objave ob odgovoru',
	'ACP_EMPTYPOSTSUBJECTS_REPLY_EXPLAIN'		=> 'Izprazni polje ’Zadeva’ v urejevalniku objav, ko odgovarjate na temo.',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY'			=> 'Izpraznite zadevo objave ob hitrem odgovoru',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY_EXPLAIN'	=> 'Med ogledom teme izprazni polje ’Zadeva’ v urejevalniku hitrih odgovorov.',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST'			=> 'Zadnja objava povezava prikaže',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST_EXPLAIN'	=> 'Prilagodite, katero besedilo bo prikazala povezava ’Zadnja objava’ na indeksu plošče.',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_0'			=> 'tema prispevka',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_1'			=> 'naslov teme, ki vsebuje objavo',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_2'			=> 'tema objave, če ni prazna, naslov teme drugače',
	'ACP_EMPTYPOSTSUBJECTS_SEARCH'				=> 'Prikaz naslovov rezultatov iskanja',
	'ACP_EMPTYPOSTSUBJECTS_SEARCH_EXPLAIN'		=> 'Prilagodite, katero besedilo se prikažejo naslovi v rezultatih iskanja.',
));
