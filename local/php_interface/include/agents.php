<?php
function CheckUserCount()
{
    $lastDate = COption::GetOptionString("main", "CheckUserCountLastDate");
    if ($lastDate) {
        $arFilter = [
            'DATE_REGISTER_1' => $lastDate
        ];
    } else {
        $arFilter = [];

    }
    $users = [];
    $req = \CUSer::GetList($by = 'DATE_REGISTER', $order = 'ASC', $arFilter);
    while ($res = $req->Fetch()) {
        $users[] = $res;
    }
    $usersCount = count($users);
    if (!$lastDate) {
        $lastDate = current($users)['DATE_REGISTER'];
    }
    $timeDiff = time() - strtotime($lastDate);
    $days = round($timeDiff / (24 * 3600));
    $reqAdmin = \CUser::GetList($by = 'ID', $order, ['ACTIVE' => 'Y', 'GROUPS_ID' => 1]);
    while ($resAdmin = $reqAdmin->Fetch()) {
        \CEvent::Send('ADMIN_USERS_COUNT', ['s1'], ['COUNT' => $usersCount, 'DAYS' => $days, 'EMAIL_TO' => $resAdmin['EMAIL']]);
    }
    COption::SetOptionString('main', 'CheckUserCountLastDate', date('d.m.Y'));
    return 'CheckUserCount();';
}