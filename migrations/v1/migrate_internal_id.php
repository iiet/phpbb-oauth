<?php

namespace iiet\oauth\migrations\v1;

class migrate_internal_id extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !$this->db_tools->sql_column_exists(PROFILE_FIELDS_DATA_TABLE, 'pf_internal_id');
	}


	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'migrate_oauth_internal_id'))),
		);
	}

	public function migrate_oauth_internal_id()
	{
		$internal_id_ary = array();

		$sql = 'DELETE FROM ' . $this->table_prefix . "oauth_accounts
			WHERE provider = 'iiet'";
		$result = $this->sql_query($sql);

		$sql = 'SELECT user_id, pf_internal_id
			FROM ' . PROFILE_FIELDS_DATA_TABLE . '
                        WHERE pf_internal_id IS NOT NULL';
		$result = $this->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$internal_id_ary[] = array(
				'user_id' => $row['user_id'],
				'provider' => 'iiet',
				'oauth_provider_id' => $row['pf_internal_id'],
			);
		}
		$this->db->sql_freeresult($result);

		$this->db->sql_multi_insert($this->table_prefix . 'oauth_accounts', $internal_id_ary);
	}
}
