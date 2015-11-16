<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2015 Martin ( https://github.com/Martin-G- )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace martin\emptypostsubjects\acp;

class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang('acp/common');
		$this->tpl_name = 'emptypostsubjects_body';
		$this->page_title = $user->lang('ACP_EMPTYPOSTSUBJECTS_TITLE');
		add_form_key('martin/emptypostsubjects');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('martin/emptypostsubjects'))
			{
				trigger_error('FORM_INVALID');
			}

			$config->set('martin_emptypostsubjects_empty_reply', $request->variable('martin_emptypostsubjects_empty_reply', 0));
			$config->set('martin_emptypostsubjects_empty_quick_reply', $request->variable('martin_emptypostsubjects_empty_quick_reply', 0));
			$config->set('martin_emptypostsubjects_last_post', $request->variable('martin_emptypostsubjects_last_post', 0));
			$config->set('martin_emptypostsubjects_search', $request->variable('martin_emptypostsubjects_search', 0));

			trigger_error($user->lang('ACP_EMPTYPOSTSUBJECTS_SETTING_SAVED') . adm_back_link($this->u_action));
		}

		$template->assign_vars(array(
			'U_ACTION'								=> $this->u_action,
			'MARTIN_EMPTYPOSTSUBJECTS_REPLY'		=> $config['martin_emptypostsubjects_empty_reply'],
			'MARTIN_EMPTYPOSTSUBJECTS_QUICK_REPLY'	=> $config['martin_emptypostsubjects_empty_quick_reply'],
		));

		// options for last post
		$template->assign_block_vars('martin_emptypostsubjects_last_post_options', array(
			'L_TITLE'	=> $user->lang('ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT),
			'OPTION'	=> EMPTYPOSTSUBJECTS_POST_SUBJECT,
			'SELECTED'	=> $config['martin_emptypostsubjects_last_post'] == EMPTYPOSTSUBJECTS_POST_SUBJECT,
		));
		$template->assign_block_vars('martin_emptypostsubjects_last_post_options', array(
			'L_TITLE'	=> $user->lang('ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_TOPIC_TITLE),
			'OPTION'	=> EMPTYPOSTSUBJECTS_TOPIC_TITLE,
			'SELECTED'	=> $config['martin_emptypostsubjects_last_post'] == EMPTYPOSTSUBJECTS_TOPIC_TITLE,
		));
		$template->assign_block_vars('martin_emptypostsubjects_last_post_options', array(
			'L_TITLE'	=> $user->lang('ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY),
			'OPTION'	=> EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY,
			'SELECTED'	=> $config['martin_emptypostsubjects_last_post'] == EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY,
		));

		// options for search
		$template->assign_block_vars('martin_emptypostsubjects_search_options', array(
			'L_TITLE'	=> $user->lang('ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT),
			'OPTION'	=> EMPTYPOSTSUBJECTS_POST_SUBJECT,
			'SELECTED'	=> $config['martin_emptypostsubjects_search'] == EMPTYPOSTSUBJECTS_POST_SUBJECT,
		));
		$template->assign_block_vars('martin_emptypostsubjects_search_options', array(
			'L_TITLE'	=> $user->lang('ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_TOPIC_TITLE),
			'OPTION'	=> EMPTYPOSTSUBJECTS_TOPIC_TITLE,
			'SELECTED'	=> $config['martin_emptypostsubjects_search'] == EMPTYPOSTSUBJECTS_TOPIC_TITLE,
		));
		$template->assign_block_vars('martin_emptypostsubjects_search_options', array(
			'L_TITLE'	=> $user->lang('ACP_EMPTYPOSTSUBJECTS_OPTION_' . EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY),
			'OPTION'	=> EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY,
			'SELECTED'	=> $config['martin_emptypostsubjects_search'] == EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY,
		));
	}
}
