<?php

namespace iiet\oauth\migrations\v1;

class add_external_link extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			array('module.add', array(
				'ucp', 'UCP_PROFILE', array(
					'module_basename' => '\iiet\oauth\ucp\oauth_reg_details_module',
					'modes' => array('reg_details')
				)
			)),
		);
	}
}
