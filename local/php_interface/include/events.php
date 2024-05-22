<?php
IncludeModuleLangFile(__FILE__);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("Exam2", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("main", "OnEpilog", array("Exam2", "OnEpilogHandler"));
AddEventHandler("main", "OnBeforeEventAdd", array("Exam2", "OnBeforeEventAddHandler"));

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
}