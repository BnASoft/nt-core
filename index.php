<?php
require_once './_common.php';

// Routing
$route = new Klein\Klein();

$route->respond('GET', '/', function() {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    require_once NT_THEME_PATH.DIRECTORY_SEPARATOR.'index.php';
});

$route->respond('GET', '/member/login', function() {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    require_once NT_MEMBER_PATH.DIRECTORY_SEPARATOR.'login.php';
});

$route->respond('GET', '/member/signup', function() {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    require_once NT_MEMBER_PATH.DIRECTORY_SEPARATOR.'signup.php';
});

$route->respond('GET', '/member/logout', function() {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    require_once NT_MEMBER_PATH.DIRECTORY_SEPARATOR.'logout.php';
});

$route->respond('GET', '/member/account', function() {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_GET['w'] = 'u';
    require_once NT_MEMBER_PATH.DIRECTORY_SEPARATOR.'signup.php';
});

$route->respond('GET', '/member/find', function() {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    require_once NT_MEMBER_PATH.DIRECTORY_SEPARATOR.'findPassword.php';
});

$route->respond('GET', '/member/password', function() {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    require_once NT_MEMBER_PATH.DIRECTORY_SEPARATOR.'password.php';
});

$route->respond('GET', '/member/leave', function() {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    require_once NT_MEMBER_PATH.DIRECTORY_SEPARATOR.'memberLeave.php';
});

$route->respond('GET', '/board/[:id]', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['id'] = $request->id;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'list.php';
});

$route->respond('GET', '/board/[:id]/p/[i:page]', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['id'] = $request->id;
    $_REQUEST['p']  = $request->page;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'list.php';
});

$route->respond('GET', '/board/[:id]/write', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['w']  = '';
    $_REQUEST['id'] = $request->id;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'write.php';
});

$route->respond('GET', '/board/[:id]/edit/[i:no]', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['w']  = 'u';
    $_REQUEST['id'] = $request->id;
    $_REQUEST['no'] = $request->no;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'write.php';
});

$route->respond('GET', '/board/[:id]/reply/[i:no]', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['w']  = 'r';
    $_REQUEST['id'] = $request->id;
    $_REQUEST['no'] = $request->no;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'write.php';
});

$route->respond('GET', '/board/[:id]/[i:no]', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['id'] = $request->id;
    $_REQUEST['no'] = $request->no;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'view.php';
});

$route->respond('GET', '/download/[:id]/[i:no]/[i:fn]', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['id'] = $request->id;
    $_REQUEST['no'] = $request->no;
    $_REQUEST['fn'] = $request->fn;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'download.php';
});

$route->respond('GET', '/board/[:id]/delete/[i:no]', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['id'] = $request->id;
    $_REQUEST['no'] = $request->no;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'delete.php';
});

$route->respond('GET', '/board/[:id]/[edit|read|delete:action]/[i:no]/password', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['id'] = $request->id;
    $_REQUEST['no'] = $request->no;
    $_REQUEST['action'] = $request->action;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'password.php';
});

$route->respond('GET', '/board/[:id]/[i:no]/comment', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['id'] = $request->id;
    $_REQUEST['no'] = $request->no;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'comment.php';
});

$route->respond('GET', '/board/[:id]/[i:no]/comment/[i:cn]/[edit|reply|delete:action]', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $_REQUEST['id'] = $request->id;
    $_REQUEST['no'] = $request->no;
    $_REQUEST['cn'] = $request->cn;
    $_REQUEST['action'] = $request->action;
    require_once NT_BOARD_PATH.DIRECTORY_SEPARATOR.'commentAction.php';
});

$route->respond('GET', '/[:id]', function($request) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    $sql = " select * from `{$nt['pages_table']}` where pg_id = :pg_id ";
    $DB->prepare($sql);
    $DB->execute([':pg_id' => $request->id]);
    $pages = $DB->fetch();

    if (!$pages['pg_no']) {
        require_once NT_THEME_PATH.DIRECTORY_SEPARATOR.'404.php';
        return;
    }

    if (!$pages['pg_use']) {
        require_once NT_THEME_PATH.DIRECTORY_SEPARATOR.'404.php';
        return;
    }

    $html->setPageTitle(getHtmlChar($pages['pg_subject']));

    $header = $pages['pg_header'];
    $footer = $pages['pg_footer'];

    if (is_file(NT_THEME_PATH.DIRECTORY_SEPARATOR.$header))
        $html->loadPage($header);
    else
        $html->loadPage('header.sub.php');

    echo PHP_EOL.$pages['pg_content'].PHP_EOL;

    if (is_file(NT_THEME_PATH.DIRECTORY_SEPARATOR.$footer))
        $html->loadPage($footer);
    else
        $html->loadPage('footer.sub.php');

    if (!$_SESSION['ss_page_'.$pages['pg_no'].'_view']) {
        $sql = " update `{$nt['pages_table']}` set pg_views = pg_views + 1 where pg_no = :pg_no ";
        $DB->prepare($sql);

        $_SESSION['ss_page_'.$pages['pg_no'].'_view'] = $DB->execute([':pg_no' => $pages['pg_no']]);
    }
});

$route->onHttpError(function ($code, $router) {
    global $isMember, $isAdmin, $isGuest, $member, $html, $nt, $DB;

    switch ($code) {
        case 404:
            require_once NT_THEME_PATH.DIRECTORY_SEPARATOR.'404.php';
            break;
        default:
            break;
    }
});

$route->dispatch();