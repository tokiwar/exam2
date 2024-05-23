<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 * @global $APPLICATION
 */
$url = $APPLICATION->GetCurPage() . '?F=Y';
?>
<?php
if ($arResult['NEWS']):?>
    <?= GetMessage('FILTER_PAGE') ?> <a href='<?= $url ?>'><?= $url ?></a>
    <ul>
        <?php foreach ($arResult['NEWS'] as $newsItem): ?>
            <li>
                <b><?= $newsItem['NAME'] ?></b> - <?= $newsItem['ACTIVE_FROM'] ?>
                (<?= implode(', ', $newsItem['SECTION_NAMES']) ?>)
                <ul>
                    <?php foreach ($newsItem['ITEMS'] as $item): ?>
                        <?php
                        $this->AddEditAction($newsItem['ID'] . '_' . $item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item['IBLOCK_ID'], "ELEMENT_EDIT"));
                        $this->AddEditAction($newsItem['ID'] . '_' . $item['ID'], $item['ADD_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_ADD"));
                        $this->AddDeleteAction($newsItem['ID'] . '_' . $item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), ['CONFIRM' => GetMessage('PAGE_DELETE_ITEM')]);
                        ?>
                        <li id="<?= $this->GetEditAreaId($newsItem['ID'] . '_' . $item['ID']) ?>">
                            <?= $item['NAME'] ?> - <?= $item['PRICE'] ?> - <?= $item['MATERIAL'] ?>
                            - <?= $item['ARTNUMBER'] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>