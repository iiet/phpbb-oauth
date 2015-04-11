<?php
namespace iiet\oauth\auth\provider;

class oauth_iiet extends \phpbb\auth\provider\oauth\oauth
{
	public function logout($data, $new_session)
	{
		parent::logout($data, $new_session);

		redirect('https://accounts.iiet.pl/students/sign_out', false, true);

		return;
	}

	public function login($username, $password)
	{
		// Temporary workaround for only having one authentication provider available
		if (!$this->request->is_set('oauth_service'))
		{
			$provider = new \phpbb\auth\provider\db($this->db, $this->config, $this->passwords_manager, $this->request, $this->user, $this->phpbb_container, $this->phpbb_root_path, $this->php_ext);
			return $provider->login($username, $password);
		}

		// Requst the name of the OAuth service
		$service_name_original = $this->request->variable('oauth_service', '', false);
		$service_name = 'auth.provider.oauth.service.' . strtolower($service_name_original);
		if ($service_name_original === '' || !array_key_exists($service_name, $this->service_providers))
		{
			return array(
				'status'		=> LOGIN_ERROR_EXTERNAL_AUTH,
				'error_msg'		=> 'LOGIN_ERROR_OAUTH_SERVICE_DOES_NOT_EXIST',
				'user_row'		=> array('user_id' => ANONYMOUS),
			);
		}

		// Get the service credentials for the given service
		$service_credentials = $this->service_providers[$service_name]->get_service_credentials();

		$storage = new \phpbb\auth\provider\oauth\token_storage($this->db, $this->user, $this->auth_provider_oauth_token_storage_table);
		$query = 'mode=login&login=external&oauth_service=' . $service_name_original . '&redirect=' . rawurlencode(htmlspecialchars_decode($this->request->variable('redirect', '')));
		$service = $this->get_service($service_name_original, $storage, $service_credentials, $query, $this->service_providers[$service_name]->get_auth_scope());

		if ($this->request->is_set('code', \phpbb\request\request_interface::GET))
		{
			$this->service_providers[$service_name]->set_external_service_provider($service);
			$unique_id = $this->service_providers[$service_name]->perform_auth_login();

			// Check to see if this provider is already assosciated with an account
			$data = array(
				'provider'	=> $service_name_original,
				'oauth_provider_id'	=> $unique_id
			);
			$sql = 'SELECT user_id FROM ' . $this->auth_provider_oauth_token_account_assoc . '
				WHERE ' . $this->db->sql_build_array('SELECT', $data);
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if (!$row)
			{
				// The user does not yet exist, ask to link or create profile
				return array(
					'status'		=> LOGIN_SUCCESS_LINK_PROFILE,
					'error_msg'		=> 'LOGIN_OAUTH_ACCOUNT_NOT_LINKED',
					'user_row'		=> array(),
					'redirect_data'	=> array(
						'auth_provider'				=> 'oauth',
						'login_link_oauth_service'	=> $service_name_original,
					),
				);
			}

			// Retrieve the user's account
			$sql = 'SELECT user_id, username, user_password, user_passchg, user_email, user_type, user_login_attempts
				FROM ' . $this->users_table . '
					WHERE user_id = ' . (int) $row['user_id'];
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if (!$row)
			{
				throw new \Exception('AUTH_PROVIDER_OAUTH_ERROR_INVALID_ENTRY');
			}

			// Update token storage to store the user_id
			$storage->set_user_id($row['user_id']);

			// The user is now authenticated and can be logged in
			return array(
				'status'		=> LOGIN_SUCCESS,
				'error_msg'		=> false,
				'user_row'		=> $row,
			);
		}
		else
		{
			$url = $service->getAuthorizationUri();
			header('Location: ' . $url);
		}
	}
}
