<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 */
if ($arResult['FIRMS']):?>
    <ul>
        <?php foreach ($arResult['FIRMS'] as $firm): ?>
            <li>
                <b><?= $firm['NAME'] ?></b>
                <ul>
                    <?php foreach ($firm['PRODUCTS'] as $product): ?>
                        <li>
                            <?= $product['NAME'] ?> – <?= $product['PRICE'] ?> – <?= $product['MATERIAL'] ?>
                            – <?= $product['ARTNUMBER'] ?> - <?= $product['URL'] ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
    <?= $arResult['NAV_STRING'] ?>
<?php endif; ?>