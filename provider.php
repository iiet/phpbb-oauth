<?php

namespace iiet\oauth;

if (!defined('IN_PHPBB'))
{
    exit;
}

class provider extends \phpbb\auth\provider\oauth\service\base
{
	protected $config;
	protected $request;

	public function __construct(\phpbb\config\config $config, \phpbb\request\request_interface $request)
	{
		$this->config = $config;
		$this->request = $request;
	}

	public function get_service_credentials()
	{
		return array(
			'key'		=> $this->config['auth_oauth_iiet_key'],
			'secret'	=> $this->config['auth_oauth_iiet_secret'],
		);
	}

	public function perform_auth_login()
	{
		if (!($this->service_provider instanceof \OAuth\OAuth2\Service\Iiet))
		{
			throw new \phpbb\auth\provider\oauth\service\exception('AUTH_PROVIDER_OAUTH_ERROR_INVALID_SERVICE_TYPE');
		}

		$this->service_provider->requestAccessToken($this->request->variable('code', ''));

		$result = json_decode($this->service_provider->request('v1/public'), true);

		return $result['user_id'];
	}

	public function perform_token_auth()
	{
		if (!($this->service_provider instanceof \OAuth\OAuth2\Service\Iiet))
		{
			throw new \phpbb\auth\provider\oauth\service\exception('AUTH_PROVIDER_OAUTH_ERROR_INVALID_SERVICE_TYPE');
		}

		$result = json_decode($this->service_provider->request('v1/public'), true);

		return $result['user_id'];
	}
}
