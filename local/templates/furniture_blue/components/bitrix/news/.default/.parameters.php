<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arCurrentValues */

$arTemplateParameters = array(
    "SET_SPECIAL_DATE" => array(
        "NAME" => GetMessage("SET_SPECIAL_DATE"),
        "TYPE" => "CHECKBOX",
        'PARENT' => 'BASE',
        "DEFAULT" => "Y",
    ),
    "IBLOCK_ID_CANONICAL" => array(
        "NAME" => GetMessage("IBLOCK_ID_CANONICAL"),
        "TYPE" => "STRING",
        'PARENT' => 'BASE',
    ),
    'COLLECT_BAD_WITH_AJAX' => array(
        "NAME" => GetMessage("COLLECT_BAD_WITH_AJAX"),
        "TYPE" => "CHECKBOX",
        'PARENT' => 'BASE',
        "DEFAULT" => "Y",
    )
);
