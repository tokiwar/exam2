<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 */
if ($arResult['NEWS']):?>
    <ul>
        <?php foreach ($arResult['NEWS'] as $userId => $news): ?>
            <li>
                [<?= $userId ?>] - <?= $arResult['USERS'][$userId]['LOGIN'] ?>
                <ul>
                    <?php foreach ($news['ITEMS'] as $newsItem): ?>
                        <li>
                            <?= $newsItem['NAME'] ?> – <?= $newsItem['ACTIVE_FROM'] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>