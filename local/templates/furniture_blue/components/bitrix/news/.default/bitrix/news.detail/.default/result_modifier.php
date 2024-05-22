<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arParams
 * @var array $arResult
 */
\Bitrix\Main\Loader::includeModule('iblock');
if ($iblockid = intval($arParams['IBLOCK_ID_CANONICAL'])) {
    $req = \CIBlockElement::getList([], ['ACTIVE' => 'Y', 'IBLOCK_ID' => $iblockid, 'PROPERTY_NEWS' => $arResult['ID']], false, false, ['ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_NEWS']);
    if ($res = $req->fetch()) {
        $arResult['CANONICAL'] = $res['NAME'];
        if ($arResult['CANONICAL']) {
            $this->__component->setResultCacheKeys(['CANONICAL']);
        }
    }
}