<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2015 Martin ( https://github.com/Martin-G- )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* Translated By : Basil Taha Alhitary - www.alhitary.net
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
	'ACP_EMPTYPOSTSUBJECTS_TITLE'													=> 'TODO Empty Post Subjects',
	'ACP_EMPTYPOSTSUBJECTS_SETTINGS'												=> 'الإعدادات',
	'ACP_EMPTYPOSTSUBJECTS_SETTING_SAVED'											=> 'تم حفظ الإعدادات بنجاح !',
	'ACP_EMPTYPOSTSUBJECTS_REPLY'													=> 'TODO Empty post subject on reply',
	'ACP_EMPTYPOSTSUBJECTS_REPLY_EXPLAIN'											=> 'TODO Empties the \'Subject\' field in post editor when replying to a topic.',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY'												=> 'TODO Empty post subject on quick reply',
	'ACP_EMPTYPOSTSUBJECTS_QUICK_REPLY_EXPLAIN'										=> 'TODO Empties the \'Subject\' field in the quick reply editor when viewing a topic.',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST'												=> 'إظهار رابط آخر مُشاركة ',
	'ACP_EMPTYPOSTSUBJECTS_LAST_POST_EXPLAIN'										=> 'تحديد طريقة إظهار رابط آخر مُشاركة في الصفحة الرئيسية للمنتدى.',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_LAST_POST_SUBJECT			=> 'عنوان آخر مُشاركة',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_TOPIC_TITLE					=> 'عنوان الموضوع (الذي يحتوي على آخر مُشاركة)',
	'ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY	=> 'عنوان آخر مشاركة, وإذا كان فارغاً يُستبدل بعنوان الموضوع',
));
