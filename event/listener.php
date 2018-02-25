<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2018 Martin ( https://github.com/Mar-tin-G )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace martin\emptypostsubjects\event;

/**
* @ignore
*/
use \phpbb\template\template;
use \phpbb\config\config;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'							=> 'define_constants',
			'core.posting_modify_template_vars'			=> 'remove_subject_reply',
			'core.viewtopic_modify_page_title'			=> 'remove_subject_quick_reply',
			'core.display_forums_modify_sql'			=> 'query_topic_title',
			'core.display_forums_modify_forum_rows'		=> 'set_parent_topic_title',
			'core.display_forums_before'				=> 'set_custom_last_post',
			'core.search_modify_tpl_ary'				=> 'modify_search_results',
		);
	}

	/** @var template */
	protected $template;

	/** @var config */
	protected $config;

	/**
	* Constructor
	*
	* @param template	$template
	* @param config		$config
	*/
	public function __construct(template $template, config $config)
	{
		$this->template = $template;
		$this->config = $config;
	}

	/**
	* Define some constants that are needed for this extension. This function is called
	* early on every page by using the event 'core.user_setup'.
	*
	* @param	object		$event	The event object
	* @return	null
	* @access	public
	*/
	public function define_constants($event)
	{
		define('EMPTYPOSTSUBJECTS_POST_SUBJECT', 0);
		define('EMPTYPOSTSUBJECTS_TOPIC_TITLE', 1);
		define('EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY', 2);
	}

	/**
	* Empties the subject when replying to a topic with the "Post Reply" button.
	*
	* @param	object		$event	The event object
	* @return	null
	* @access	public
	*/
	public function remove_subject_reply($event)
	{
		if ($this->config['martin_emptypostsubjects_empty_reply'])
		{
			$page_data = $event['page_data'];
			$mode = $event['mode'];
			$submit = $event['submit'];
			$preview = $event['preview'];
			$refresh = $event['refresh'];

			// only empty subject on initial reply or quote
			if (($mode == 'reply' || $mode == 'quote') && !$submit && !$preview && !$refresh)
			{
				$page_data['SUBJECT'] = '';
				$event['page_data'] = $page_data;
			}
		}
	}

	/**
	* Empties the subject when the "Quick Reply" feature is active.
	*
	* @param	object		$event	The event object
	* @return	null
	* @access	public
	*/
	public function remove_subject_quick_reply($event)
	{
		if ($this->config['martin_emptypostsubjects_empty_quick_reply'])
		{
			// since the SUBJECT template variable is used only for the Quick Reply subject
			// in the template quickreply_editor.html, we can safely overwrite the value of
			// this template variable without checking if Quick Reply is active.
			$this->template->assign_vars(array(
				'SUBJECT'	=> '',
			));
		}
	}

	/**
	* Function to join the topics table to the forum query and fetch the topic title
	* of the topic that contains the last post in this forum.
	*
	* @param	object		$event	The event object
	* @return	null
	* @access	public
	*/
	public function query_topic_title($event)
	{
		$sql_ary = $event['sql_ary'];

		$sql_ary['LEFT_JOIN'][] = array(
			'FROM'	=> array(TOPICS_TABLE => 't'),
			'ON'	=> "f.forum_last_post_id = t.topic_last_post_id AND t.topic_moved_id = 0"
		);
		$sql_ary['SELECT'] .= ', t.topic_title AS last_post_topic_title';

		$event['sql_ary'] = $sql_ary;
	}

	/**
	* Function to check if the last post information of this forum has been
	* copied to the parent forum, and copies the topic title if necessary.
	*
	* @param	object		$event	The event object
	* @return	null
	* @access	public
	*/
	public function set_parent_topic_title($event)
	{
		$forum_rows = $event['forum_rows'];
		$parent_id = $event['parent_id'];
		$row = $event['row'];

		if ($forum_rows[$parent_id]['forum_id_last_post'] == $row['forum_id'])
		{
			$forum_rows[$parent_id]['last_post_topic_title'] = $row['last_post_topic_title'];
		}

		$event['forum_rows'] = $forum_rows;
	}

	/**
	* Function to replace the link text of the Last Post link.
	*
	* @param	object		$event	The event object
	* @return	null
	* @access	public
	*/
	public function set_custom_last_post($event)
	{
		$forum_rows = $event['forum_rows'];

		foreach ($forum_rows as $row)
		{
			switch ($this->config['martin_emptypostsubjects_last_post'])
			{
				// always display topic title
				case EMPTYPOSTSUBJECTS_TOPIC_TITLE:
					$forum_rows[$row['forum_id']]['forum_last_post_subject'] = $row['last_post_topic_title'];
				break;

				// display topic title if last post subject is empty
				case EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY:
					$forum_rows[$row['forum_id']]['forum_last_post_subject'] = (!$row['forum_last_post_subject'] || $row['forum_last_post_subject'] == '') ? $row['last_post_topic_title'] : $row['forum_last_post_subject'];
				break;

				// always display last post subject
				case EMPTYPOSTSUBJECTS_POST_SUBJECT:
				default:
					$forum_rows[$row['forum_id']]['forum_last_post_subject'] = $row['forum_last_post_subject'];
				break;
			}
		}

		$event['forum_rows'] = $forum_rows;
	}

	/**
	* Function to modify the title of search results when searching for posts.
	*
	* @param	object		$event	The event object
	* @return	null
	* @access	public
	*/
	public function modify_search_results($event)
	{
		// only modify post subject when viewing search results as posts
		if ($event['show_results'] == 'posts')
		{
			$tpl_ary = $event['tpl_ary'];
			$topic_title = $event['tpl_ary']['TOPIC_TITLE'];
			$post_subject = $event['tpl_ary']['POST_SUBJECT'];

			switch ($this->config['martin_emptypostsubjects_search'])
			{
				// always display topic title
				case EMPTYPOSTSUBJECTS_TOPIC_TITLE:
					$search_result_subject = $topic_title;
				break;

				// display topic title if post subject is empty
				case EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY:
					$search_result_subject = (!$post_subject || $post_subject == '') ? $topic_title : $post_subject;
				break;

				// always display post subject
				case EMPTYPOSTSUBJECTS_POST_SUBJECT:
				default:
					$search_result_subject = $post_subject;
				break;
			}

			$tpl_ary['POST_SUBJECT'] = $search_result_subject;
			$event['tpl_ary'] = $tpl_ary;
		}
	}
}
