<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент - 71");
?><?$APPLICATION->IncludeComponent(
	"exam2:simplecomp71", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"PRODUCTS_IBLOCK_ID" => "2",
		"SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID" => "7",
		"SIMPLECOMP_EXAM2_TYPE_PROP" => "FIRMA"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>