<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2015 Martin ( https://github.com/Martin-G- )
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
	'ACP_EMPTYPOSTSUBJECTS_TITLE'													=> 'Empty Post Subjects',
	'ACP_EMPTYPOSTSUBJECTS_SETTINGS'												=> 'Einstellungen',
	'ACP_EMPTYPOSTSUBJECTS_SETTING_SAVED'											=> 'Die Einstellungen wurden erfolgreich gespeichert!',
	'ACP_EMPTYPOSTSUBJECTS_REPLY'													=> 'Betreff beim Antworten leeren',
	'ACP_EMPTYPOSTSUBJECTS_REPLY_EXPLAIN'											=> 'Leert das \'Betreff\'-Feld im Beitragseditor beim Antworten auf ein Thema.',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY'												=> 'Betreff bei Schnellantworten leeren',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY_EXPLAIN'										=> 'Leert das \'Betreff\'-Feld im Schnellantwort-Editor beim Betrachten eines Themas.',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST'												=> 'Link zum Letzten Beitrag zeigt',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST_EXPLAIN'										=> 'Welchen Text soll der Link zum \'Letzten Beitrag\' auf der Indexseite anzeigen?',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_LAST_POST_SUBJECT			=> 'Betreff des letzten Beitrags',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_TOPIC_TITLE					=> 'Betreff des Themas, das den letzten Beitrag enthält',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY	=> 'Betreff des letzten Beitrags, wenn dieser nicht leer ist; ansonsten Betreff des Themas',
));
