<?php

namespace iiet\oauth\ucp;

class oauth_reg_details_info
{
	function module()
	{
		return array(
			'filename'	=> '\iiet\oauth\ucp\oauth_reg_details_module',
			'title'		=> 'UCP_PROFILE',
			'version'	=> '0.0.1',
			'modes'		=> array(
				'reg_details' => array('title' => 'UCP_PROFILE_OAUTH_REG_DETAILS', 'auth' => 'ext_iiet/oauth', 'cat' => array('UCP_PROFILE'))
			)
		);
	}
}
