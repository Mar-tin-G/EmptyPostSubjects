<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2018 Martin ( https://github.com/Mar-tin-G )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
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
	'ACP_EMPTYPOSTSUBJECTS_TITLE'				=> 'Empty Post Subjects',
	'ACP_EMPTYPOSTSUBJECTS_SETTINGS'			=> 'Einstellungen',
	'ACP_EMPTYPOSTSUBJECTS_SETTING_SAVED'		=> 'Die Einstellungen wurden erfolgreich gespeichert!',
	'ACP_EMPTYPOSTSUBJECTS_REPLY'				=> 'Betreff beim Antworten leeren',
	'ACP_EMPTYPOSTSUBJECTS_REPLY_EXPLAIN'		=> 'Leert das ’Betreff’-Feld im Beitragseditor beim Antworten auf ein Thema.',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY'			=> 'Betreff bei Schnellantworten leeren',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY_EXPLAIN'	=> 'Leert das ’Betreff’-Feld im Schnellantwort-Editor beim Betrachten eines Themas.',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST'			=> 'Link zum Letzten Beitrag zeigt',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST_EXPLAIN'	=> 'Welchen Text soll der Link zum ’Letzten Beitrag’ auf der Indexseite anzeigen?',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_0'			=> 'Betreff des Beitrags',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_1'			=> 'Betreff des Themas, das den Beitrag enthält',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_2'			=> 'Betreff des Beitrags, wenn dieser nicht leer ist; ansonsten Betreff des Themas',
	'ACP_EMPTYPOSTSUBJECTS_SEARCH'				=> 'Titel in den Suchergebnissen zeigen',
	'ACP_EMPTYPOSTSUBJECTS_SEARCH_EXPLAIN'		=> 'Welchen Text sollen die Titel der Suchergebnisse zeigen?',
));
