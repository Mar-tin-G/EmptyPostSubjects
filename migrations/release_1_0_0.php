<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2015 Martin ( https://github.com/Martin-G- )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace martin\emptypostsubjects\migrations;

class release_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['martin_emptypostsubjects_version']) && version_compare($this->config['martin_emptypostsubjects_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\alpha2');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('martin_emptypostsubjects_version', '1.0.0')),
			array('config.add', array('martin_emptypostsubjects_empty_reply', 0)),
			array('config.add', array('martin_emptypostsubjects_empty_quick_reply', 0)),
			array('config.add', array('martin_emptypostsubjects_last_post', 0)),
			array('config.add', array('martin_emptypostsubjects_search', 0)),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_EMPTYPOSTSUBJECTS_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_EMPTYPOSTSUBJECTS_TITLE',
				array(
					'module_basename'	=> '\martin\emptypostsubjects\acp\main_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
