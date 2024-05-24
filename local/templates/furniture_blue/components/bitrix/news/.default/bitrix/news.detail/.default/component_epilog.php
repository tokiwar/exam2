<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arResult
 * @var array $arParams
 * @global $APPLICATION
 */
if ($arResult['CANONICAL']) {
    $APPLICATION->SetPageProperty('canonical', $arResult['CANONICAL']);
}
if (isset($_REQUEST['COMP'])) {
    $elem = new CIBlockElement();
    global $USER;
    if ($arParams['COLLECT_BAD_WITH_AJAX'] == 'Y') {
        $res = $elem->Add(['NAME' => 'Жалоба ' . time(), 'ACTIVE_FROM' => new \Bitrix\Main\Type\DateTime(), 'IBLOCK_ID' => COMP_IBLOCK_ID, 'PROPERTY_VALUES' => ['NEWS' => $arResult['ID'], 'USER' => $USER->IsAuthorized() ? implode(', ', [$USER->GetID(), $USER->GetLogin(), $USER->GetFullName()]) : GetMessage('NOT_USER')]]);
        $APPLICATION->RestartBuffer();
        die(json_encode(['result' => $res ? GetMessage('GOOD_AJAX', ['#ID#' => $res]) : GetMessage('ERROR_AJAX')]));
    } else {
        $res = $elem->Add(['NAME' => 'Жалоба ' . time(), 'ACTIVE_FROM' => new \Bitrix\Main\Type\DateTime(), 'IBLOCK_ID' => COMP_IBLOCK_ID, 'PROPERTY_VALUES' => ['NEWS' => $arResult['ID'], 'USER' => $USER->IsAuthorized() ? implode(', ', [$USER->GetID(), $USER->GetLogin(), $USER->GetFullName()]) : GetMessage('NOT_USER')]]);
        LocalRedirect($APPLICATION->GetCurPage() . '?RESULT=' . json_encode($res ? GetMessage('GOOD_AJAX', ['#ID#' => $res]) : GetMessage('ERROR_AJAX'), JSON_UNESCAPED_UNICODE));
    }
} ?>
<?php if ($arParams['COLLECT_BAD_WITH_AJAX'] == 'Y'): ?>
    <script>
      if (typeof button === 'undefined') {
        const button = document.querySelector('#report_action');
        if (button) {
          button.addEventListener('click', async function (e) {
            e.preventDefault();
            await BX.ajax({
              url: '<?=$APPLICATION->GetCurPage() . '?COMP=Y'?>',
              dataType: 'json',
              method: 'GET',
              onsuccess: function (resp) {
                if (resp && resp.result) {
                  const textPlace = document.querySelector('#reports_status');
                  if (textPlace) {
                    textPlace.innerText = resp.result;
                  }
                }
              }
            });
          });
        }
      }
    </script>
<?php elseif ($_REQUEST['RESULT']) : ?>
    <script>
      const textPlace = document.querySelector('#reports_status');
      if (textPlace) {
        const obj = <?= CUtil::PhpToJSObject($_REQUEST['RESULT'])?>;
        textPlace.innerText = obj.replace(/"/g, '');
      }
    </script>
<?php endif; ?>