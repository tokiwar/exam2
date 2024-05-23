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
		"SIMPLECOMP_EXAM2_TYPE_PROP" => "FIRMA",
		"SIMPLECOMP_EXAM2_LINK_TEMPLATE" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
		"SIMPLECOMP_EXAM2_PAGE_ITEMS" => "2"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>