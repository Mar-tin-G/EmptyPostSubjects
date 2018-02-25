<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2018 Martin ( https://github.com/Mar-tin-G )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace martin\emptypostsubjects\acp;

class main_module
{
	public $u_action;
	public $tpl_name;
	public $page_title;

	public function main($id, $mode)
	{
		global $phpbb_container, $template, $request, $config;

		/* @var \phpbb\language\language $lang */
		$lang = $phpbb_container->get('language');

		$this->tpl_name = 'emptypostsubjects_body';
		$this->page_title = $lang->lang('ACP_EMPTYPOSTSUBJECTS_TITLE');
		add_form_key('martin/emptypostsubjects');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('martin/emptypostsubjects'))
			{
				$lang->add_lang('acp/common');
				trigger_error('FORM_INVALID', E_USER_WARNING);
			}

			$config->set('martin_emptypostsubjects_empty_reply',		$request->variable('martin_emptypostsubjects_empty_reply', 0));
			$config->set('martin_emptypostsubjects_empty_quick_reply',	$request->variable('martin_emptypostsubjects_empty_quick_reply', 0));
			$config->set('martin_emptypostsubjects_last_post',			$request->variable('martin_emptypostsubjects_last_post', 0));
			$config->set('martin_emptypostsubjects_search',				$request->variable('martin_emptypostsubjects_search', 0));

			trigger_error($lang->lang('ACP_EMPTYPOSTSUBJECTS_SETTING_SAVED'). adm_back_link($this->u_action));
		}

		$template->assign_vars(array(
			'U_ACTION'								=> $this->u_action,
			'MARTIN_EMPTYPOSTSUBJECTS_REPLY'		=> $config['martin_emptypostsubjects_empty_reply'],
			'MARTIN_EMPTYPOSTSUBJECTS_QUICK_REPLY'	=> $config['martin_emptypostsubjects_empty_quick_reply'],
		));

		// options for last post
		foreach (array(EMPTYPOSTSUBJECTS_POST_SUBJECT, EMPTYPOSTSUBJECTS_TOPIC_TITLE, EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY) as $value)
		{
			$template->assign_block_vars('martin_emptypostsubjects_last_post_options', array(
				'L_TITLE'	=> $lang->lang('ACP_EMPTYPOSTSUBJECTS_OPTION_' . $value),
				'OPTION'	=> $value,
				'SELECTED'	=> $config['martin_emptypostsubjects_last_post'] == $value,
			));
		}

		// options for search
		foreach (array(EMPTYPOSTSUBJECTS_POST_SUBJECT, EMPTYPOSTSUBJECTS_TOPIC_TITLE, EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY) as $value)
		{
			$template->assign_block_vars('martin_emptypostsubjects_search_options', array(
				'L_TITLE'	=> $lang->lang('ACP_EMPTYPOSTSUBJECTS_OPTION_' . $value),
				'OPTION'	=> $value,
				'SELECTED'	=> $config['martin_emptypostsubjects_search'] == $value,
			));
		}
	}
}
