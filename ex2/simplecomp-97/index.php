<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент - 97");
?><?$APPLICATION->IncludeComponent(
	"exam2:simplecomp97", 
	".default", 
	array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"PRODUCTS_IBLOCK_ID" => "",
		"SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID" => "1",
		"SIMPLECOMP_EXAM2_TYPE_PROP" => "UF_AUTHOR_TYPE",
		"SIMPLECOMP_EXAM2_TYPE_PROP_AUTHOR" => "AUTHOR",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>