<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\Loader::includeModule('iblock');

class SimpleComp70 extends CBitrixComponent
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
        $request = isset($_REQUEST['F']);
        if ($this->arParams['PRODUCTS_IBLOCK_ID'] && $this->arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID'] && $this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP']) {
            $arButtons = CIblock::GetPanelButtons($this->arParams['PRODUCTS_IBLOCK_ID']);
            $this->AddIncludeAreaIcon(array(

                'URL' => $arButtons["submenu"]["element_list"]["ACTION_URL"],
                'TITLE' => GetMessage('IB_V_ADMINKE'),
                "IN_PARAMS_MENU" => true,

            ));
            if ($this->StartResultCache(false, [$request])) {
                $this->getSections();
                if ($this->arResult['SECTIONS']) {
                    $this->getItems($request);
                    if ($this->arResult['ITEMS']) {
                        $this->getNews();
                        $this->setItemsToSections();
                        $this->setSectionsToNews();
                    }
                }
                if ($request) {
                    $this->abortResultCache();
                }
                $this->setResultCacheKeys(['COUNT', 'MAX_PRICE', 'MIN_PRICE']);
            }
        }
        $APPLICATION->SetTitle(GetMessage('ITEMS_COUNT_SIMPLE') . $this->arResult['COUNT']);
        if ($this->arResult['MAX_PRICE'] && $this->arResult['MIN_PRICE']) {
            $APPLICATION->AddViewContent('prices', '<div style="color:red; margin: 34px 15px 35px 15px">' . GetMessage('MAX_MIN_PRICE', ['#MAX_PRICE#' => $this->arResult['MAX_PRICE'], '#MIN_PRICE#' => $this->arResult['MIN_PRICE']]) . '</div>');
        }
        $this->includeComponentTemplate();
    }

    private function getNews()
    {
        $newsIds = [];
        $result = [];
        foreach ($this->arResult['SECTIONS'] as $section) {
            $newsIds = array_merge($newsIds, $section['NEWS_IDS']);
        }
        $newsIds = array_unique($newsIds);
        $req = \CIBlockElement::GetList([], ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID'], 'ID' => $newsIds], false, false,
            ['ID', 'IBLOCK_ID', 'NAME', 'ACTIVE_FROM']);
        while ($res = $req->Fetch()) {
            $result[$res['ID']] = [
                'ID' => $res['ID'],
                'ACTIVE_FROM' => $res['ACTIVE_FROM'],
                'NAME' => $res['NAME'],
                'ITEMS' => [],
            ];
        }
        $this->arResult['NEWS'] = $result;
    }

    private function setItemsToSections()
    {
        foreach ($this->arResult['ITEMS'] as $sectionId => $items) {
            if ($this->arResult['SECTIONS'][$sectionId]) {
                $this->arResult['SECTIONS'][$sectionId]['ITEMS'] = array_merge($this->arResult['SECTIONS'][$sectionId]['ITEMS'], $items);
            }
        }
    }

    private function setSectionsToNews()
    {
        foreach ($this->arResult['NEWS'] as $newsId => $newsData) {
            foreach ($this->arResult['SECTIONS'] as $sectionId => $sectionData) {
                if (in_array($newsId, $sectionData['NEWS_IDS'])) {
                    $this->arResult['NEWS'][$newsId]['SECTION_NAMES'][] = $sectionData['NAME'];
                    $this->arResult['NEWS'][$newsId]['ITEMS'] = array_merge($this->arResult['NEWS'][$newsId]['ITEMS'], $sectionData['ITEMS']);
                }
            }
        }
        unset($this->arResult['ITEMS'], $this->arResult['SECTIONS']);
    }

    private function getSections()
    {
        $result = [];
        $req = \CIBlockSection::GetList([], ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['PRODUCTS_IBLOCK_ID'], '!' . $this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP'] => false], false, [$this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP']]);
        while ($res = $req->Fetch()) {
            $result[$res['ID']] = [
                'NAME' => $res['NAME'],
                'ITEMS' => [],
                'NEWS_IDS' => $res[$this->arParams['SIMPLECOMP_EXAM2_TYPE_PROP']]
            ];
        }
        $this->arResult['SECTIONS'] = $result;
    }

    private function getItems(bool $request)
    {
        $result = [];
        $sectionsIds = array_keys($this->arResult['SECTIONS']);
        $filter = ['ACTIVE' => 'Y', 'IBLOCK_ID' => $this->arParams['PRODUCTS_IBLOCK_ID'], 'IBLOCK_SECTION_ID' => $sectionsIds];
        if ($request) {
            $filter[] = [
                'LOGIC' => 'OR',
                ['<=PROPERTY_PRICE' => 1700, 'PROPERTY_MATERIAL' => 'Дерево, ткань'],
                ['<PROPERTY_PRICE' => 1500, 'PROPERTY_MATERIAL' => 'Металл, пластик'],
            ];
        }
        $req = \CIBlockElement::GetList([],
            $filter
            , false, false, ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME', 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER']);
        $itemsCount = 0;
        $prices = [];
        while ($res = $req->Fetch()) {
            $arButtons = CIBlock::GetPanelButtons($res['IBLOCK_ID'], $res['ID'], 0, ['SECTION_BUTTONS' => false, 'SESSID' => false]);
            $itemsCount++;
            if ($res['PROPERTY_PRICE_VALUE']) {
                $prices[] = $res['PROPERTY_PRICE_VALUE'];
            }
            $result[$res['IBLOCK_SECTION_ID']][] = [
                'ID' => $res['ID'],
                'IBLOCK_ID' => $res['IBLOCK_ID'],
                'NAME' => $res['NAME'],
                'PRICE' => $res['PROPERTY_PRICE_VALUE'],
                'ARTNUMBER' => $res['PROPERTY_ARTNUMBER_VALUE'],
                'MATERIAL' => $res['PROPERTY_MATERIAL_VALUE'],
                'ADD_LINK' => $arButtons['edit']['add_element']['ACTION_URL'],
                'EDIT_LINK' => $arButtons['edit']['edit_element']['ACTION_URL'],
                'DELETE_LINK' => $arButtons['edit']['delete_element']['ACTION_URL'],
            ];
        }
        $this->arResult['MAX_PRICE'] = max($prices);
        $this->arResult['MIN_PRICE'] = min($prices);
        $this->arResult['COUNT'] = $itemsCount;
        $this->arResult['ITEMS'] = $result;
    }
}