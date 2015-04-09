<?php

namespace iiet\oauth\migrations\v1;

class change_schema extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array(
			'iiet\oauth\migrations\v1\migrate_internal_id'
		);
	}

	public function update_schema()
	{
		return array(
			'drop_columns' => array(
				PROFILE_FIELDS_DATA_TABLE => array(
					'pf_internal_id',
				),
			),
		);
	}
}
