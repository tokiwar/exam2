<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\Loader::includeModule('iblock');

class SimpleComp71 extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['PRODUCTS_IBLOCK_ID'] = intval($arParams['PRODUCTS_IBLOCK_ID']);
        $arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID'] = intval($arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID']);
        $arParams['SIMPLECOMP_EXAM2_TYPE_PROP'] = trim($arParams['SIMPLECOMP_EXAM2_TYPE_PROP']);
        if (!$arParams["CACHE_TIME"]) {
            $arParams["CACHE_TIME"] = 36000000;
        }
        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;
        $this->arResult['COUNT'] = 0;
        if ($this->arParams['PRODUCTS_IBLOCK_ID'] && $this->arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID'] && $this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP']) {
            if ($this->StartResultCache()) {
                $this->getProducts();
                if ($this->arResult['PRODUCTS']) {
                    $this->getFirms();
                }
                $this->setResultCacheKeys(['COUNT']);
            }
        }
        $APPLICATION->SetTitle(GetMessage('ITEMS_COUNT_SIMPLE') . $this->arResult['COUNT']);
        $this->includeComponentTemplate();
    }

    private function getFirms()
    {
        $result = [];
        $firmIds = [];
        foreach ($this->arResult['PRODUCTS'] as $product) {
            $firmIds = array_merge($firmIds, $product['FIRMA']);
        }
        $firmIds = array_unique($firmIds);
        $req = \CIBlockElement::GetList([], ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID'], 'ID' => $firmIds], false, false, ['ID', 'IBLOCK_ID', 'NAME']);
        while ($res = $req->Fetch()) {
            $tempRes = [
                'NAME' => $res['NAME'],
            ];
            foreach ($this->arResult['PRODUCTS'] as $product) {
                if (in_array($res['ID'], $product['FIRMA'])) {
                    $tempRes['PRODUCTS'][] = $product;
                }
            }
            $result[$res['ID']] = $tempRes;
        }
        unset($this->arResult['PRODUCTS']);
        $this->arResult['FIRMS'] = $result;
    }

    private function getProducts()
    {
        $result = [];
        $count = 0;
        $req = \CIBlockElement::GetList(['NAME' => 'ASC', 'SORT' => 'ASC'], ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['PRODUCTS_IBLOCK_ID'], '!PROPERTY_' . $this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP'] => false], false, false, ['ID', 'CODE', 'DETAIL_PAGE_URL', 'IBLOCK_SECTION_ID', 'IBLOCK_ID', 'NAME',]);
        $urlTemplate = $this->arParams['SIMPLECOMP_EXAM2_LINK_TEMPLATE'];
        while ($res = $req->GetNextElement()) {
            $count++;
            $resData = $res->GetFields();
            $properties = $res->GetProperties();
            $result[$resData['ID']] = [
                'NAME' => $resData['NAME'],
                'URL' => $urlTemplate ? (str_replace(['#SECTION_ID#', '#ELEMENT_CODE#'], [$resData['IBLOCK_SECTION_ID'], $resData['CODE']], $urlTemplate) . '.php') : $resData['DETAIL_PAGE_URL'],
                'PRICE' => $properties['PRICE']['VALUE'],
                'MATERIAL' => $properties['MATERIAL']['VALUE'],
                'ARTNUMBER' => $properties['ARTNUMBER']['VALUE'],
                'FIRMA' => $properties['FIRMA']['VALUE']
            ];

        }
        $this->arResult['COUNT'] = $count;
        $this->arResult['PRODUCTS'] = $result;
    }
}