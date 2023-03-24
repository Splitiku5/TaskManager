<?php

class TasksListComponent extends CBitrixComponent
{
	public function executeComponent()
	{
        \Bitrix\Main\Loader::includeModule('up.tasks');
		$this->prepareTemplateParams();
		$this->fetchTasksList($this->arParams['query']);
		$this->includeComponentTemplate();

	}

	public function onPrepareComponentParams($arParams)
	{
		$arParams['DATE_FORMAT'] = $arParams['DATE_FORMAT'] ?? 'd.m.Y';

		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$this->arResult['DATE_FORMAT'] = $this->arParams['DATE_FORMAT'];
	}

	protected function fetchTasksList($query = '')
	{

        $tasks = \Up\Tasks\Tasks::getTasks($query);
		$this->arResult['TASKS'] = $tasks;
	}
}