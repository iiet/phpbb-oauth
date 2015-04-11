<?php

namespace iiet\oauth\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

//$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';

class listener implements EventSubscriberInterface
{
	protected $request;
	protected $user;

	public function __construct(\phpbb\request\request_interface $request, \phpbb\user $user)
	{
		$this->request = $request;
		$this->user = $user;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup' => 'language',
		);
	}

	public function language($event)
	{
		global $phpbb_root_path, $phpEx;
		$event['lang_set_ext'] = array_merge($event['lang_set_ext'], array(
			array(
				'ext_name' => 'iiet/oauth',
				'lang_set' => 'common',
			)
		));
		if (!$this->user->data['is_registered'])
		{
			if (!$this->request->is_set_post('login') &&
				!($this->request->is_set('login') && $this->request->variable('login', '') == 'external') &&
				!($this->request->variable('mode', '') == 'login')) {
				$target = rawurlencode($this->user->page['page']);
				$redirect_url = append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=login&amp;login=external&amp;oauth_service=iiet&amp;redirect=' . $target);
				redirect($redirect_url);
			}
		}
	}
}
