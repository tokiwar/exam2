<?php
IncludeModuleLangFile(__FILE__);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("Exam2", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("main", "OnEpilog", array("Exam2", "OnEpilogHandler"));
AddEventHandler("main", "OnBeforeEventAdd", array("Exam2", "OnBeforeEventAddHandler"));
AddEventHandler("main", "OnBuildGlobalMenu", array("Exam2", "OnBuildGlobalMenuHandler"));
AddEventHandler("main", "OnBeforeProlog", array("Exam2", "OnBeforePrologHandler"));

class Exam2
{
    public static function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == NEWS_IBLOCK_ID) {
            global $APPLICATION;
            $req = \CIBlockElement::GetList([], ['ACTIVE' => 'Y', 'IBLOCK_ID' => NEWS_IBLOCK_ID, 'ID' => $arFields['ID']], false, false, ['ID', 'IBLOCK_ID', 'SHOW_COUNTER', 'ACTIVE']);
            if ($res = $req->fetch()) {
                if ($arFields['ACTIVE'] == 'N' && $res['SHOW_COUNTER'] > BLOCK_NONACTIVE_COUNTER) {
                    $APPLICATION->throwException(GetMessage('CANT_DEACTIVE', ['#COUNT#' => $res['SHOW_COUNTER']]));
                    return false;
                }
            }
        }
        return true;
    }

    public static function OnEpilogHandler()
    {
        if (defined('ERROR_404') && ERROR_404 == 'Y') {
            global $APPLICATION;
            CEventLog::Add(array(
                "SEVERITY" => "SECURITY",
                "AUDIT_TYPE_ID" => "ERROR_404",
                "MODULE_ID" => "main",
                "DESCRIPTION" => $APPLICATION->GetCurUri()
            ));
        }
    }

    public static function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        if ($event == 'FEEDBACK_FORM') {
            global $USER;
            if ($USER->IsAuthorized()) {
                $newAuthorName = GetMessage('AUTHOR_AUTH', ['#ID#' => $USER->GetID(), '#NAME#' => $USER->GetFullName(), '#LOGIN#' => $USER->GetLogin(), '#FORM_NAME#' => $arFields['AUTHOR']]);
            } else {
                $newAuthorName = GetMessage('AUTHOR_NON_AUTH', ['#FORM_NAME#' => $arFields['AUTHOR']]);
            }
            $arFields['AUTHOR'] = $newAuthorName;
            CEventLog::Add(array(
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => "AUTHOR_REPLACE",
                "MODULE_ID" => "main",
                "DESCRIPTION" => GetMessage('AUTHOR_REPLACE', ['#AUTHOR#' => $arFields['AUTHOR']])
            ));
        }
    }

    public static function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
    {
        global $USER;
        if (!$USER->isAdmin()) {
            $req = \CGroup::GetList($by = 'c_sort', $order = 'asc', ['STRING_ID' => 'content_editor']);
            if ($res = $req->fetch()) {
                $contentId = $res['ID'];
                $groups = CUser::GetUserGroup($USER->GetId());
                if (in_array($contentId, $groups)) {
                    $aGlobalMenu = ['global_menu_content' => $aGlobalMenu['global_menu_content']];
                    $menuContent = current(array_filter($aModuleMenu, function ($item) {
                        return $item['items_id'] == 'menu_iblock_/news';
                    }));
                    if ($menuContent) {
                        $menuContentNews = current(array_filter($menuContent['items'], function ($item) {
                            return $item['items_id'] == 'menu_iblock_/news/' . NEWS_IBLOCK_ID;
                        }));
                        if ($menuContentNews) {
                            $menuContent['items'] = [$menuContentNews];
                            $aModuleMenu = [$menuContent];
                        }
                    }
                }
            }
        }
    }

    public static function OnBeforePrologHandler()
    {
        global $APPLICATION;
        $curPage = $APPLICATION->GetCurPage();
        if (\Bitrix\Main\Loader::includeModule('iblock')) {
            $req = \CIBlockElement::GetList([], ['IBLOCK_ID' => SEO_IBLOCK_ID, 'ACTIVE' => 'Y', '=NAME' => $curPage], false, false, ['ID', 'IBLOCK_ID', 'NAME', 'ACTIVE', 'PROPERTY_TITLE', 'PROPERTY_DESCRIPTION']);
            if ($res = $req->fetch()) {
                if ($res['PROPERTY_TITLE_VALUE']) {
                    $APPLICATION->SetPageProperty('title', $res['PROPERTY_TITLE_VALUE']);
                }
                if ($res['PROPERTY_DESCRIPTION_VALUE']) {
                    $APPLICATION->SetPageProperty('description', $res['PROPERTY_DESCRIPTION_VALUE']);
                }
            }
        }
    }
}