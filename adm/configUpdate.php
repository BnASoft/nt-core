<?php
require_once './_common.php';

if (!$isSuper)
    dieJson(_('Only super administrators can access.'));

$token = new TOKEN();

// 토큰체크
if (!$token->verifyToken($_POST['token'], 'ss_adm_token'))
    dieJson(_('Please use it in the correct way.'));

$flds = array(
    'cf_theme',
    'cf_locale',
    'cf_site_name',
    'cf_email_name',
    'cf_email_address',
    'cf_page_rows',
    'cf_page_limit',
    'cf_max_level',
    'cf_member_level',
    'cf_super_admin',
    'cf_keywords',
    'cf_recaptcha_site_key',
    'cf_recaptcha_secret_key',
    'cf_css_version',
    'cf_js_version',
    'cf_token_time',
    'cf_password_length'
);

$sql = " update `{$nt['config_table']}` set ";

$values = array();
$querys = array();

foreach ($flds as $k) {
    $querys[] = "{$k} = :{$k}";
    $values[':'.$k] = trim($_POST[$k]);
}

$sql .= implode(', ', $querys);
$DB->prepare($sql);
$DB->bindValueArray($values);
$result = $DB->execute();

if (!$result)
    dieJson(_('An error occurred while editing the information. Please try again.'));
else
    dieJson('');