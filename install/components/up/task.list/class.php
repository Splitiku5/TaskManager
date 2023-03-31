<?php

class TasksListComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        \Bitrix\Main\Loader::includeModule('up.tasks');
        $this->prepareTemplateParams();
        $this->fetchTasksList();
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

    protected function fetchTasksList()
    {
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        if ($request->getRequestMethod() === 'POST') {
            $result = \Up\Tasks\Tasks::createTask($request->getPostList());
            header('Location: /');
        } elseif ($request->getRequestMethod() === 'GET') {
            if ($request->get('query')) {
                $query = $request->get('query');
                $tasks = \Up\Tasks\Tasks::getTasks($query);
                $this->arResult['TASKS'] = $tasks;
            } else {
                $tasks = \Up\Tasks\Tasks::getTasks();
                $this->arResult['TASKS'] = $tasks;
            }
        }

    }
}