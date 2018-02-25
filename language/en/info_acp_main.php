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
	'ACP_EMPTYPOSTSUBJECTS_TITLE'													=> 'Empty Post Subjects',
	'ACP_EMPTYPOSTSUBJECTS_SETTINGS'												=> 'Settings',
	'ACP_EMPTYPOSTSUBJECTS_SETTING_SAVED'											=> 'Settings have been saved successfully!',
	'ACP_EMPTYPOSTSUBJECTS_REPLY'													=> 'Empty post subject on reply',
	'ACP_EMPTYPOSTSUBJECTS_REPLY_EXPLAIN'											=> 'Empties the \'Subject\' field in post editor when replying to a topic.',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY'												=> 'Empty post subject on quick reply',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY_EXPLAIN'										=> 'Empties the \'Subject\' field in the quick reply editor when viewing a topic.',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST'												=> 'Last Post link displays',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST_EXPLAIN'										=> 'Customize which text the \'Last Post\' link on the board index displays.',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT				=> 'subject of the post',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_TOPIC_TITLE					=> 'title of the topic that contains the post',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY	=> 'subject of the post if it is not empty, topic title otherwise',
	'ACP_EMPTYPOSTSUBJECTS_SEARCH'													=> 'Search result titles display',
	'ACP_EMPTYPOSTSUBJECTS_SEARCH_EXPLAIN'											=> 'Customize which text the titles in the search result display.',
));
