<?php
IncludeModuleLangFile(__FILE__);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("Exam2", "OnBeforeIBlockElementUpdateHandler"));

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
}