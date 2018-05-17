<?php
require_once './_common.php';

unset($_SESSION['ss_password_check']);

$token = new TOKEN();

// 토큰체크
if (!$token->verifyToken((string)$_POST['token']))
    dieJson(_('Please use it in the correct way.'));

$email = trim($_POST['email']);
$pass  = trim($_POST['pass']);

if (!$email || !$pass)
    dieJson(_('Please enter your email and password.'));

if ($member['mb_email'] != $email)
    dieJson(_('Please check your email.'));

$sql = " select mb_uid, mb_password from `{$nt['member_table']}` where mb_email = :mb_email ";

$DB->prepare($sql);
$DB->bindValue(':mb_email', $email);
$DB->execute();

$row = $DB->fetch();

if (!$row['mb_uid'])
    dieJson(_('Member information does not exist.'));

if ($member['mb_uid'] != $row['mb_uid'] || !passwordVerify($pass, $row['mb_password']))
    dieJson(_('The member information does not match.'));

$_SESSION['ss_password_check'] = $_SESSION['ss_password_mode'];

$href = '';
switch ($_SESSION['ss_password_mode']) {
    case 'leave':
        $href = NT_LINK_LEAVE;
        break;
    default:
        $href = NT_LINK_ACCOUNT;
        break;
}

unset($_SESSION['ss_password_mode']);
die(json_encode(array('error'=>'', 'href'=>$href)));