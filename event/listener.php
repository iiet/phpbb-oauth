<?php

namespace iiet\oauth\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup' => 'language',
		);
	}

	public function language($event)
	{
		$event['lang_set_ext'] = array_merge($event['lang_set_ext'], array(
			array(
				'ext_name' => 'iiet/oauth',
				'lang_set' => 'common',
			)
		));
	}
}
