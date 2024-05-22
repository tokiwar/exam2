<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arParams
 * @var array $arResult
 */
\Bitrix\Main\Loader::includeModule('iblock');
if ($arParams['SET_SPECIAL_DATE'] == 'Y') {
    $arResult['SPECIALDATE'] = current($arResult['ITEMS'])['ACTIVE_FROM'];
    if ($arResult['SPECIALDATE']) {
        $this->__component->setResultCacheKeys(['SPECIALDATE']);
    }
}