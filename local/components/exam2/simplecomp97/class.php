<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\Loader::includeModule('iblock');

class SimpleComp71 extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID'] = intval($arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID']);
        $arParams['SIMPLECOMP_EXAM2_TYPE_PROP'] = trim($arParams['SIMPLECOMP_EXAM2_TYPE_PROP']);
        $arParams['SIMPLECOMP_EXAM2_TYPE_PROP_AUTHOR'] = trim($arParams['SIMPLECOMP_EXAM2_TYPE_PROP_AUTHOR']);
        if (!$arParams["CACHE_TIME"]) {
            $arParams["CACHE_TIME"] = 36000000;
        }
        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->arResult['COUNT'] = 0;
            if ($this->arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID'] && $this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP'] && $this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP_AUTHOR']) {
                $this->getCurrentUserType();
                if ($this->arResult['USER_TYPE']) {
                    if ($this->StartResultCache(false, [$USER->GetID(), $this->arResult['USER_TYPE']])) {
                        $this->getSameTypesUsers();
                        if ($this->arResult['USERS']) {
                            $this->getNews();
                        }
                    }
                    $this->setResultCacheKeys(['COUNT']);
                }
            }
            $APPLICATION->SetTitle(GetMessage('ITEMS_COUNT_SIMPLE') . $this->arResult['COUNT']);
        }
        $this->includeComponentTemplate();
    }

    private function getNews()
    {
        global $USER;
        $result = [];
        $count = 0;
        $req = \CIBlockElement::GetList([], ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID'], 'PROPERTY_' . $this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP_AUTHOR'] => array_keys($this->arResult['USERS']), false, false, ['ID', 'NAME', 'ACTIVE_FROM',]]);
        while ($res = $req->GetNextElement()) {
            $resData = $res->GetFields();
            $props = $res->GetProperties();
            if (!in_array($USER->GetID(), $props[$this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP_AUTHOR']]['VALUE'])) {
                $count++;
                foreach ($this->arResult['USERS'] as $userId => $userValues) {
                    if (in_array($userId, $props[$this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP_AUTHOR']]['VALUE'])) {
                        $result[$userId]['ITEMS'][] = [
                            'ID' => $resData['ID'],
                            'NAME' => $resData['NAME'],
                            'ACTIVE_FROM' => $resData['ACTIVE_FROM'],
                        ];
                    }
                }
            }
        }
        $this->arResult['COUNT'] = $count;
        $this->arResult['NEWS'] = $result;
    }

    private function getSameTypesUsers()
    {
        $result = [];
        global $USER;
        $req = \CUser::GetList($by = 'ID', $order = 'ASC', ['ACTIVE' => 'Y', '!ID' => $USER->GetID(), $this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP'] => $this->arResult['USER_TYPE']], ['SELECT' => [$this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP']]]);
        while ($res = $req->Fetch()) {
            $result[$res['ID']] = [
                'ID' => $res['ID'],
                'LOGIN' => $res['LOGIN']
            ];
        }
        $this->arResult['USERS'] = $result;
    }

    private function getCurrentUserType()
    {
        global $USER;
        $id = $USER->GetID();
        $req = \CUser::GetList($by = 'ID', $order = 'ASC', ['ACTIVE' => 'Y', 'ID' => $id, '!' . $this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP'] => false], ['SELECT' => [$this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP']]]);
        if ($res = $req->Fetch()) {
            $this->arResult['USER_TYPE'] = $res[$this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP']];
        }
    }
}