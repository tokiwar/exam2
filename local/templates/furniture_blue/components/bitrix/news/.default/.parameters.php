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
);
