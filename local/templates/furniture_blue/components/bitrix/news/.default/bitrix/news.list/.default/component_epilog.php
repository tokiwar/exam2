<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 * @global $APPLICATION
 */
if ($arResult['SPECIALDATE']) {
    $APPLICATION->SetPageProperty('specialdate', $arResult['SPECIALDATE']);
}