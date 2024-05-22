<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentParameters = array(
    "PARAMETERS" => array(
        "PRODUCTS_IBLOCK_ID" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
            "TYPE" => "STRING",
        ),
        "SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID"),
            "TYPE" => "STRING",
        ),
        "SIMPLECOMP_EXAM2_TYPE_PROP" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_TYPE_PROP"),
            "TYPE" => "STRING",
        ),
        "CACHE_TIME" => ["DEFAULT" => 36000000],
    ),
);