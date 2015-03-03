<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2015 Martin ( https://github.com/Martin-G- )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* French translation by Galixte (http://www.galixte.com)
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
	'ACP_EMPTYPOSTSUBJECTS_TITLE'													=> 'Sujets des messages vides',
	'ACP_EMPTYPOSTSUBJECTS_SETTINGS'												=> 'Paramètres',
	'ACP_EMPTYPOSTSUBJECTS_SETTING_SAVED'											=> 'Les paramètres ont été sauvegardés avec succès !',
	'ACP_EMPTYPOSTSUBJECTS_REPLY'													=> 'Sujet du message vide en répondant',
	'ACP_EMPTYPOSTSUBJECTS_REPLY_EXPLAIN'											=> 'Champ « Sujet » vide sur la page l’éditeur de texte lors de la réponse à un sujet.',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY'												=> 'Sujet du message vide dans la réponse rapide',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY_EXPLAIN'										=> 'Champ « Sujet » vide dans l’éditeur de texte de la réponse rapide lors de la lecture du sujet.',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST'												=> 'Affichage du lien du dernier message',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST_EXPLAIN'										=> 'Personnaliser l’affichage du texte du lien du dernier message sur la page de l’index du forum.',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT				=> 'sujet du message',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_TOPIC_TITLE					=> 'titre du sujet contenant le message',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY	=> 'sujet du message si il n’est pas vide, autrement ce sera le titre du sujet',
	'ACP_EMPTYPOSTSUBJECTS_SEARCH'													=> 'Affichage des titres dans les résultats de la recherche',
	'ACP_EMPTYPOSTSUBJECTS_SEARCH_EXPLAIN'											=> 'Personnaliser l’affichage du texte des titres dans les résultats de la recherche.',
));
