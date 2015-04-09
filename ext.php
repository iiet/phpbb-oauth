<?php

namespace iiet\oauth;

class ext extends \phpbb\extension\base
{
	function enable_step($old_state)
	{
		switch ($old_state)
		{
			case '':
				$db = $this->container->get('dbal.conn');
				$modules = array('UCP_PROFILE_REG_DETAILS', 'UCP_PROFILE_AUTOLOGIN_KEYS', 'UCP_AUTH_LINK_MANAGE');
				$sql = 'UPDATE ' . MODULES_TABLE . '
					SET module_enabled = 0
					WHERE ' . $db->sql_in_set('module_langname', $modules);
				$db->sql_query($sql);

				return 'ucp_modules_disabled';
			break;

			default:
				return parent::enable_step($old_state);
			break;
		}
	}

	function disable_step($old_state)
	{
		switch ($old_state)
		{
			case '':
				$db = $this->container->get('dbal.conn');
				$modules = array('UCP_PROFILE_REG_DETAILS', 'UCP_PROFILE_AUTOLOGIN_KEYS', 'UCP_AUTH_LINK_MANAGE');
				$sql = 'UPDATE ' . MODULES_TABLE . '
					SET module_enabled = 1
					WHERE ' . $db->sql_in_set('module_langname', $modules);
				$db->sql_query($sql);

				return 'ucp_modules_disabled';
			break;

			default:
				return parent::disable_step($old_state);
			break;
		}
	}
}
