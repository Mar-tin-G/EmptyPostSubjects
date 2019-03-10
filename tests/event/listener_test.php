<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2018 Martin ( https://github.com/Mar-tin-G )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace martin\emptypostsubjects\tests\event;

require_once dirname(__FILE__) . '/../../../../../includes/functions.php';

class listener_test extends \phpbb_test_case
{
	/** @var \martin\emptypostsubjects\event\listener */
	protected $listener;

	protected $template, $config;

	/**
	* Define the extensions to be tested
	*
	* @return array vendor/name of extension(s) to test
	*/
	static protected function setup_extensions()
	{
		return array('martin/emptypostsubjects');
	}

	/**
	* Setup test environment
	*/
	public function setUp()
	{
		parent::setUp();

		$this->config = new \phpbb\config\config(array(
			'martin_emptypostsubjects_empty_reply'			=> 1,
			'martin_emptypostsubjects_empty_quick_reply'	=> 1,
		));

		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	* Get an instance of the event listener to test
	*/
	protected function set_listener()
	{
		$this->listener = new \martin\emptypostsubjects\event\listener(
			$this->template,
			$this->config
		);
	}

	/**
	* Test the event listener is constructed correctly
	*/
	public function test_construct()
	{
		$this->set_listener();
		$this->assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
	}

	/**
	* Test the event listener is subscribing events
	*/
	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.posting_modify_template_vars',
			'core.viewtopic_modify_page_title',
			'core.display_forums_modify_sql',
			'core.display_forums_modify_forum_rows',
			'core.display_forums_before',
			'core.search_modify_rowset',
			'core.search_modify_tpl_ary',
		), array_keys(\martin\emptypostsubjects\event\listener::getSubscribedEvents()));
	}

	/**
	* Data set for test_remove_subject_reply
	*
	* @return array Array of test data
	*/
	public function remove_subject_reply_data()
	{
		return array(
			'initial reply' => array(
				'reply',
				false,
				false,
				false,
				array('SUBJECT' => 'Re: Topic title'),
				array('SUBJECT' => ''),
			),
			'initial quote' => array(
				'quote',
				false,
				false,
				false,
				array('SUBJECT' => 'Re: Topic title'),
				array('SUBJECT' => ''),
			),
			'submitting' => array(
				'reply',
				true,
				false,
				false,
				array('SUBJECT' => 'Re: Topic title'),
				array('SUBJECT' => 'Re: Topic title'),
			),
			'previewing' => array(
				'reply',
				false,
				true,
				false,
				array('SUBJECT' => 'Re: Topic title'),
				array('SUBJECT' => 'Re: Topic title'),
			),
			'refreshing' => array(
				'reply',
				false,
				false,
				true,
				array('SUBJECT' => 'Re: Topic title'),
				array('SUBJECT' => 'Re: Topic title'),
			),
			'neither replying nor quoting' => array(
				'something',
				false,
				false,
				false,
				array('SUBJECT' => 'Re: Topic title'),
				array('SUBJECT' => 'Re: Topic title'),
			),
		);
	}

	/**
	* Test the core.posting_modify_template_vars event
	*
	* @dataProvider remove_subject_reply_data
	*/
	public function test_remove_subject_reply($mode, $submit, $preview, $refresh, $page_data, $expected)
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.posting_modify_template_vars', array($this->listener, 'remove_subject_reply'));

		$event_data = array('page_data', 'mode', 'submit', 'preview', 'refresh');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.posting_modify_template_vars', $event);

		$event_data_after = $event->get_data_filtered($event_data);
		$this->assertArrayHasKey('page_data', $event_data_after);
		$this->assertEquals($expected, $event_data_after['page_data']);
	}

	/**
	* Test the core.viewtopic_modify_page_title event
	*/
	public function test_remove_subject_quick_reply()
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.viewtopic_modify_page_title', array($this->listener, 'remove_subject_quick_reply'));

		$this->template->expects($this->once())
			->method('assign_vars')
			->with($this->equalTo(array('SUBJECT' => '')));

		$dispatcher->dispatch('core.viewtopic_modify_page_title');
	}

	/**
	* Data set for test_query_topic_title
	*
	* @return array Array of test data
	*/
	public function query_topic_title_data()
	{
		return array(
			array(
				array(
					'LEFT_JOIN'	=> array(
						'some joins we do not care about',
					),
					'SELECT'	=> 'and some selects',
				),
				array(
					'LEFT_JOIN'	=> array(
						'some joins we do not care about',
						array(
							'FROM'	=> array(TOPICS_TABLE => 't'),
							'ON'	=> "f.forum_last_post_id = t.topic_last_post_id AND t.topic_moved_id = 0"
						),
					),
					'SELECT'	=> 'and some selects, t.topic_title AS last_post_topic_title',
				),
			),
		);
	}

	/**
	* Test the core.display_forums_modify_sql event
	*
	* @dataProvider query_topic_title_data
	*/
	public function test_query_topic_title($sql_ary, $expected)
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.display_forums_modify_sql', array($this->listener, 'query_topic_title'));

		$event_data = array('sql_ary');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.display_forums_modify_sql', $event);

		$event_data_after = $event->get_data_filtered($event_data);
		$this->assertArrayHasKey('sql_ary', $event_data_after);
		$this->assertEquals($expected, $event_data_after['sql_ary']);
	}

	/**
	* Data set for test_set_parent_topic_title
	*
	* @return array Array of test data
	*/
	public function set_parent_topic_title_data()
	{
		return array(
			'last post of this forum is last post of parent forum' => array(
				2,
				array(
					1 => array(
						'forum_id'				=> 1,
						'parent_id'				=> 0,
						'forum_id_last_post'	=> 2,
						'last_post_topic_title'	=> 'some topic title that has to be replaced',
					),
					2 => array(
						'forum_id'				=> 2,
						'parent_id'				=> 1,
						'last_post_topic_title'	=> 'new topic title',
					),
					3 => array(
						'forum_id'				=> 3,
						'parent_id'				=> 1,
						'last_post_topic_title'	=> 'topic title of some unrelated forum',
					),
				),
				array(
					1 => array(
						'forum_id'				=> 1,
						'parent_id'				=> 0,
						'forum_id_last_post'	=> 2,
						'last_post_topic_title' => 'new topic title',
					),
					2 => array(
						'forum_id'				=> 2,
						'parent_id'				=> 1,
						'last_post_topic_title'	=> 'new topic title',
					),
					3 => array(
						'forum_id'				=> 3,
						'parent_id'				=> 1,
						'last_post_topic_title'	=> 'topic title of some unrelated forum',
					),
				),
			),
			'last post of this forum is NOT last post of parent forum' => array(
				2,
				array(
					1 => array(
						'forum_id'				=> 1,
						'parent_id'				=> 0,
						'forum_id_last_post'	=> 3,
						'topic_title'			=> 'some topic title that has NOT to be replaced',
					),
					2 => array(
						'forum_id'				=> 2,
						'parent_id'				=> 1,
						'topic_title'			=> 'topic title',
					),
					3 => array(
						'forum_id'				=> 3,
						'parent_id'				=> 1,
						'topic_title'			=> 'topic title of some unrelated forum',
					),
				),
				array(
					1 => array(
						'forum_id'				=> 1,
						'parent_id'				=> 0,
						'forum_id_last_post'	=> 3,
						'topic_title'			=> 'some topic title that has NOT to be replaced',
					),
					2 => array(
						'forum_id'				=> 2,
						'parent_id'				=> 1,
						'topic_title'			=> 'topic title',
					),
					3 => array(
						'forum_id'				=> 3,
						'parent_id'				=> 1,
						'topic_title'			=> 'topic title of some unrelated forum',
					),
				),
			),
		);
	}

	/**
	* Test the core.display_forums_modify_forum_rows event
	*
	* @dataProvider set_parent_topic_title_data
	*/
	public function test_set_parent_topic_title($forum_id, $forum_rows, $expected)
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.display_forums_modify_forum_rows', array($this->listener, 'set_parent_topic_title'));

		$row = $forum_rows[$forum_id];
		$parent_id = $row['parent_id'];
		$event_data = array('forum_rows', 'parent_id', 'row');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.display_forums_modify_forum_rows', $event);

		$event_data_after = $event->get_data_filtered($event_data);
		$this->assertArrayHasKey('forum_rows', $event_data_after);
		$this->assertEquals($expected, $event_data_after['forum_rows']);
	}

	/**
	* Data set for test_set_custom_last_post
	*
	* @return array Array of test data
	*/
	public function set_custom_last_post_data()
	{
		return array(
			'always display last post subject' => array(
				0,
				array(
					1 => array(
						'forum_id'					=> 1,
						'forum_last_post_subject'	=> 'post subject',
						'last_post_topic_title'		=> 'topic title',
					),
				),
				array(
					1 => array(
						'forum_id'					=> 1,
						'forum_last_post_subject'	=> 'post subject',
						'last_post_topic_title'		=> 'topic title',
					),
				),
			),
			'always display topic title' => array(
				1,
				array(
					2 => array(
						'forum_id'					=> 2,
						'forum_last_post_subject'	=> 'post subject',
						'last_post_topic_title'		=> 'topic title',
					),
				),
				array(
					2 => array(
						'forum_id'					=> 2,
						'forum_last_post_subject'	=> 'topic title',
						'last_post_topic_title'		=> 'topic title',
					),
				),
			),
			'display topic title if last post subject is empty, last post subject is not empty' => array(
				2,
				array(
					3 => array(
						'forum_id'					=> 3,
						'forum_last_post_subject'	=> 'post subject',
						'last_post_topic_title'		=> 'topic title',
					),
				),
				array(
					3 => array(
						'forum_id'					=> 3,
						'forum_last_post_subject'	=> 'post subject',
						'last_post_topic_title'		=> 'topic title',
					),
				),
			),
			'display topic title if last post subject is empty, last post subject is empty' => array(
				2,
				array(
					4 => array(
						'forum_id'					=> 4,
						'forum_last_post_subject'	=> '',
						'last_post_topic_title'		=> 'topic title',
					),
				),
				array(
					4 => array(
						'forum_id'					=> 4,
						'forum_last_post_subject'	=> 'topic title',
						'last_post_topic_title'		=> 'topic title',
					),
				),
			),
		);
	}

	/**
	* Test the core.display_forums_before event
	*
	* @dataProvider set_custom_last_post_data
	*/
	public function test_set_custom_last_post($option, $forum_rows, $expected)
	{
		$this->set_listener();

		$this->config['martin_emptypostsubjects_last_post'] = $option;

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.display_forums_before', array($this->listener, 'set_custom_last_post'));

		$event_data = array('forum_rows');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.display_forums_before', $event);

		$event_data_after = $event->get_data_filtered($event_data);
		$this->assertArrayHasKey('forum_rows', $event_data_after);
		$this->assertEquals($expected, $event_data_after['forum_rows']);
	}

	/**
	* Data set for test_get_search_hilit
	*
	* @return array Array of test data
	*/
	public function get_search_hilit_data()
	{
		return array(
			'get hilit' => array(
				'needle',
			),
		);
	}

	/**
	* Test the core.search_modify_rowset event
	*
	* @dataProvider get_search_hilit_data
	*/
	public function test_get_search_hilit($hilit)
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.search_modify_rowset', array($this->listener, 'get_search_hilit'));

		$event_data = array('hilit');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.search_modify_rowset', $event);

		$this->assertEquals(\PHPUnit\Framework\Assert::readAttribute($this->listener, "hilit"), $hilit);
	}

	/**
	* Data set for test_modify_search_results
	*
	* @return array Array of test data
	*/
	public function modify_search_results_data()
	{
		return array(
			'do not modify search results when viewing results as topics' => array(
				'topics',
				0,
				array(
					'idem'
				),
				array(
					'idem'
				),
			),
			'always display post subject' => array(
				'posts',
				0,
				array(
					'POST_SUBJECT'	=> 'post subject',
					'TOPIC_TITLE'	=> 'topic title',
				),
				array(
					'POST_SUBJECT'	=> 'post subject',
					'TOPIC_TITLE'	=> 'topic title',
				),
			),
			'always display topic title' => array(
				'posts',
				1,
				array(
					'POST_SUBJECT'	=> 'post subject',
					'TOPIC_TITLE'	=> 'topic title',
				),
				array(
					'POST_SUBJECT'	=> 'topic title',
					'TOPIC_TITLE'	=> 'topic title',
				),
			),
			'display topic title if post subject is empty, post subject is not empty' => array(
				'posts',
				2,
				array(
					'POST_SUBJECT'	=> 'post subject',
					'TOPIC_TITLE'	=> 'topic title',
				),
				array(
					'POST_SUBJECT'	=> 'post subject',
					'TOPIC_TITLE'	=> 'topic title',
				),
			),
			'display topic title if post subject is empty, post subject is empty' => array(
				'posts',
				2,
				array(
					'POST_SUBJECT'	=> '',
					'TOPIC_TITLE'	=> 'topic title',
				),
				array(
					'POST_SUBJECT'	=> 'topic title',
					'TOPIC_TITLE'	=> 'topic title',
				),
			),
			'topic title supports highlighting' => array(
				'posts',
				1,
				array(
					'POST_SUBJECT'	=> 'post subject',
					'TOPIC_TITLE'	=> 'topic needle title',
				),
				array(
					'POST_SUBJECT'	=> 'topic <span class="posthilit">needle</span> title',
					'TOPIC_TITLE'	=> 'topic needle title',
				),
				'needle',
			),
		);
	}

	/**
	* Test the core.search_modify_tpl_ary event
	*
	* @dataProvider modify_search_results_data
	*/
	public function test_modify_search_results($show_results, $option, $tpl_ary, $expected, $hilit = null)
	{
		$this->set_listener();

		$this->config['martin_emptypostsubjects_search'] = $option;

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.search_modify_tpl_ary', array($this->listener, 'modify_search_results'));

		if ($hilit !== null)
		{
			$dispatcher->addListener('core.search_modify_rowset', array($this->listener, 'get_search_hilit'));
			$event_data = array('hilit');
			$event = new \phpbb\event\data(compact($event_data));
			$dispatcher->dispatch('core.search_modify_rowset', $event);
		}

		$event_data = array('show_results', 'tpl_ary');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.search_modify_tpl_ary', $event);

		$event_data_after = $event->get_data_filtered($event_data);
		$this->assertArrayHasKey('tpl_ary', $event_data_after);
		$this->assertEquals($expected, $event_data_after['tpl_ary']);
	}
}
