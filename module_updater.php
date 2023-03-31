<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;

function __projectorMigrate(int $nextVersion, callable $callback)
{
	global $DB;
	$moduleId = 'up.task';

	if (!ModuleManager::isModuleInstalled($moduleId))
	{
		return;
	}

	$currentVersion = intval(Option::get($moduleId, '~database_schema_version', 0));

	if ($currentVersion < $nextVersion)
	{
		include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_class.php');
		$updater = new \CUpdater();
		$updater->Init('', 'mysql', '', '', $moduleId, 'DB');

		$callback($updater, $DB, 'mysql');
		Option::set($moduleId, '~database_schema_version', $nextVersion);
	}
}

__projectorMigrate(2, function($updater, $DB)
{
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_tasks_task'))
	{
		$DB->query("CREATE TABLE IF NOT EXISTS up_tasks_task
            (
                ID INT AUTO_INCREMENT NOT NULL,
                TITLE VARCHAR(255) NOT NULL,
                DESCRIPTION VARCHAR(255),
                DATE_CREATION datetime not null,
                DATE_DEADLINE datetime,
                DATE_UPDATE datetime,
                STATUS VARCHAR(255) not null default 'new',
                PRIORITY VARCHAR(255) not null default 'normal',
                PRIMARY KEY (ID)
            );
	");
	}
});
