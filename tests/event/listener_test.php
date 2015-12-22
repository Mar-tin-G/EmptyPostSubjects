<?php
/**
*
* @package phpBB Extension - martin emptypostsubjects
* @copyright (c) 2015 Martin ( https://github.com/Mar-tin-G )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace martin\emptypostsubjects\tests\event;

require_once dirname(__FILE__) . '/../../../../../includes/functions.php';
// TODO: remove, probably not needed
// require_once dirname(__FILE__) . '/../../../../../includes/functions_content.php';

class listener_test extends \phpbb_test_case
{
	/** @var \martin\emptypostsubjects\event\listener */
	protected $listener;

	protected $template, $config, $user, $auth;
	protected $auth_acl_map_admin, $auth_acl_map_user;

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
			'display_last_subject'							=> 1,
			'martin_emptypostsubjects_empty_reply'			=> 1,
			'martin_emptypostsubjects_empty_quick_reply'	=> 1,
			'martin_emptypostsubjects_last_post'			=> 2,
			'martin_emptypostsubjects_search'				=> 2,
		));

		// TODO: wohl unnötig, genau wie die set_auth() methode, wenn
		// foren mit unterschiedlichen rechten in den test-daten eingerichtet werden
		$this->auth_acl_map_user = array(
			array('f_read', 1, true),
			array('f_read', '1', true),
			array('f_read', 42, false),
			array('f_read', '42', false),
			array('a_', null, false),
		);
		$this->auth_acl_map_admin = array(
			array('f_read', 1, true),
			array('f_read', '1', true),
			array('f_read', 42, true),
			array('f_read', '42', true),
			array('a_', null, true),
		);

		$this->create_auth();

		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->disableOriginalConstructor()
			->getMock();

		$this->user = $this->getMockBuilder('\phpbb\user')
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
			$this->config,
			$this->user,
			$this->auth
		);
	}

	/**
	* Create auth object for tests. Needed to test different auth maps within one test.
	*/
	protected function create_auth()
	{
		$this->auth = $this->getMockBuilder('\phpbb\auth\auth')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	* Setup auth object with auth map
	*/
	protected function set_auth($map)
	{
		$this->auth->expects($this->any())
			->method('acl_get')
			->with($this->stringContains('_'),
				$this->anything())
			->will($this->returnValueMap($map));
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
			'core.user_setup',
			'core.posting_modify_template_vars',
			'core.viewtopic_modify_page_title',
			'core.display_forums_modify_sql',
			'core.display_forums_modify_forum_rows',
			'core.display_forums_modify_template_vars',
			'core.search_modify_tpl_ary',
		), array_keys(\martin\emptypostsubjects\event\listener::getSubscribedEvents()));
	}

	/**
	* Test the core.user_setup event
	*/
	public function test_define_constants()
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.user_setup', array($this->listener, 'define_constants'));

		$dispatcher->dispatch('core.user_setup');

		$this->assertEquals(0, EMPTYPOSTSUBJECTS_POST_SUBJECT);
		$this->assertEquals(1, EMPTYPOSTSUBJECTS_TOPIC_TITLE);
		$this->assertEquals(2, EMPTYPOSTSUBJECTS_POST_SUBJECT_IF_NOT_EMPTY);
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
					'SELECT' => 'and some selects',
				),
				array(
					'LEFT_JOIN'	=> array(
						'some joins we do not care about',
						array(
							'FROM'	=> array(TOPICS_TABLE => 't'),
							'ON'	=> "f.forum_last_post_id = t.topic_last_post_id AND t.topic_moved_id = 0"
						),
					),
					'SELECT' => 'and some selects, t.topic_title',
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
	* Data set for test_modify_forum_rows
	*
	* @return array Array of test data
	*/
	public function modify_forum_rows_data()
	{
		return array(
			'last post of this forum is last post of parent forum' => array(
				2,
				array(
					1 => array(
						'forum_id' => 1,
						'parent_id' => 0,
						'forum_id_last_post' => 2,
						'topic_title' => 'some topic title that has to be replaced',
					),
					2 => array(
						'forum_id' => 2,
						'parent_id' => 1,
						'topic_title' => 'new topic title',
					),
					3 => array(
						'forum_id' => 3,
						'parent_id'	=> 1,
						'topic_title' => 'topic title of some unrelated forum',
					),
				),
				array(
					1 => array(
						'forum_id' => 1,
						'parent_id' => 0,
						'forum_id_last_post' => 2,
						'topic_title' => 'new topic title',
					),
					2 => array(
						'forum_id' => 2,
						'parent_id' => 1,
						'topic_title' => 'new topic title',
					),
					3 => array(
						'forum_id' => 3,
						'parent_id'	=> 1,
						'topic_title' => 'topic title of some unrelated forum',
					),
				),
			),
			'last post of this forum is NOT last post of parent forum' => array(
				2,
				array(
					1 => array(
						'forum_id' => 1,
						'parent_id' => 0,
						'forum_id_last_post' => 3,
						'topic_title' => 'some topic title that has NOT to be replaced',
					),
					2 => array(
						'forum_id' => 2,
						'parent_id' => 1,
						'topic_title' => 'topic title',
					),
					3 => array(
						'forum_id' => 3,
						'parent_id'	=> 1,
						'topic_title' => 'topic title of some unrelated forum',
					),
				),
				array(
					1 => array(
						'forum_id' => 1,
						'parent_id' => 0,
						'forum_id_last_post' => 3,
						'topic_title' => 'some topic title that has NOT to be replaced',
					),
					2 => array(
						'forum_id' => 2,
						'parent_id' => 1,
						'topic_title' => 'topic title',
					),
					3 => array(
						'forum_id' => 3,
						'parent_id'	=> 1,
						'topic_title' => 'topic title of some unrelated forum',
					),
				),
			),
		);
	}

	/**
	* Test the core.display_forums_modify_forum_rows event
	*
	* @dataProvider modify_forum_rows_data
	*/
	public function test_modify_forum_rows($forum_id, $forum_rows, $expected)
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.display_forums_modify_forum_rows', array($this->listener, 'modify_forum_rows'));

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
			array(
				'text',
				'expected - user',
				'expected - admin',
			),
		);
	}

	/**
	* Test the core.display_forums_modify_template_vars event
	*
	* @dataProvider set_custom_last_post_data
	*/
	public function test_set_custom_last_post($text, $expected_user = false, $expected_admin = false)
	{
		$this->set_listener();

		$this->set_auth($this->auth_acl_map_user);

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.display_forums_modify_template_vars', array($this->listener, 'set_custom_last_post'));

		$event_data = array('text');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.display_forums_modify_template_vars', $event);

		$event_data_after = $event->get_data_filtered($event_data);
		$this->assertArrayHasKey('text', $event_data_after);
		$this->assertEquals(($expected_user !== false ? $expected_user : $text), $event_data_after['text']);

		// TODO last post ist in unterforum, dass user nicht lesen kann

		/* TODO
		if ($expected_admin !== false) {
			$this->create_auth();
			$this->set_listener();
			$this->set_auth($this->auth_acl_map_admin);

			$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
			$dispatcher->addListener('core.modify_text_for_display_after', array($this->listener, 'modify_post_data'));

			$event_data = array('text');
			$event = new \phpbb\event\data(compact($event_data));
			$dispatcher->dispatch('core.modify_text_for_display_after', $event);

			$event_data_after = $event->get_data_filtered($event_data);
			$this->assertArrayHasKey('text', $event_data_after);

			$this->assertEquals($expected_admin, $event_data_after['text']);
		}
		*/
	}

	// TODO: 'core.search_modify_tpl_ary'				=> 'modify_search_results',

}
