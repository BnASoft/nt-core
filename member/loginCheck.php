<?php
require_once './_common.php';

$token   = new TOKEN();
$captcha = new reCAPTCHA();

// 토큰체크
if (!$token->verifyToken($_POST['token']))
    die(_('Please use it in the correct way.'));

// reCAPTCHA 체크
if (!$captcha->checkResponse((string)$_POST['g-recaptcha-response']))
    die(_('Please check the anti-spam code.'));

$email = trim($_POST['email']);
$pass  = trim($_POST['pass']);

$sql = " select mb_uid, mb_password, mb_leave, mb_block from `{$nt['member_table']}` where mb_email = :mb_email ";

$DB->prepare($sql);
$DB->bindValue(':mb_email', $email);
$DB->execute();

$row = $DB->fetch();

if (!$row['mb_uid'])
    die(_('Member information does not exist.'));

if (!passwordVerify($pass, $row['mb_password']))
    die(_('The member information does not match.'));

if (!isNullTime((string)$row['mb_leave']))
    die(_('I am a member who has left.'));

if (!isNullTime((string)$row['mb_block']))
    die(_('Member access is blocked.'));

// 세션에 uid 기록
$_SESSION['ss_uid'] = $row['mb_uid'];

die('');