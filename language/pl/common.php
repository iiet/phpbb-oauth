<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'AUTH_PROVIDER_OAUTH_SERVICE_IIET' => 'I@IET',
	'UCP_PROFILE_OAUTH_REG_DETAILS' => 'Ustawienia konta I@IET',
));
