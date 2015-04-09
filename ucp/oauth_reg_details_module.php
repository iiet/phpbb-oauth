<?php

namespace iiet\oauth\ucp;

class oauth_reg_details_module
{
	function main($id, $mode)
	{
		redirect('https://accounts.iiet.pl/student/account/edit', false, true);
	}
}
