<?php
define('_SETUP_', true);
require_once './_common.php';

$html->setPageTitle('NT-CORE Setup');
$html->addStyleSheet(NT_CSS_URL.DIRECTORY_SEPARATOR.'setup.css', 'header', 0);
$html->addStyleSheet('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css','header', 0, '',  'integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous"');

$html->addJavaScript('https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', 'header', 0);
$html->addJavaScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js', 'footer', 0, '', 'integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"');
$html->addJavaScript('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js', 'footer', 0, '', 'integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"');

if (!isset($nt['config_table']) || !isset($nt['member_table']) || !empty($config))
    die(_('Setup can not be executed.'));

if (!$DB->pdo)
    die($DB->error);

$mb_name     = trim(strip_tags($_POST['mb_name']));
$mb_email    = trim(strip_tags($_POST['mb_email']));
$mb_password = trim($_POST['mb_password']);

if (strlen($mb_name) < 1)
    alert(_('Please enter Name.'));

if (strlen($mb_email) <1)
    alert(_('Please enter Email.'));

if (!preg_match(NT_EMAIL_PATTERN, $mb_email))
    die(_('Please enter the Email format to fit.'));

if (strlen($mb_password) < 1)
    alert(_('Please enter Password'));

// Config Table Create
$sql = " CREATE TABLE `{$nt['config_table']}` (
    `cf_site_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cf_locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cf_email_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cf_email_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cf_theme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cf_page_rows` int(11) NOT NULL,
    `cf_page_limit` int(11) NOT NULL,
    `cf_enc_salt` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cf_max_level` int(11) NOT NULL,
    `cf_member_level` int(11) NOT NULL,
    `cf_super_admin` int(11) NOT NULL,
    `cf_keywords` text COLLATE utf8mb4_unicode_ci NULL,
    `cf_recaptcha_site_key` varchar(255) COLLATE utf8mb4_unicode_ci NULL,
    `cf_recaptcha_secret_key` varchar(255) COLLATE utf8mb4_unicode_ci NULL,
    `cf_css_version` varchar(10) COLLATE utf8mb4_unicode_ci NULL,
    `cf_js_version` varchar(10) COLLATE utf8mb4_unicode_ci NULL,
    `cf_token_time` int(11) NOT NULL DEFAULT '0',
    `cf_password_length` int(11) NOT NULL DEFAULT '0'
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; ";

if ($DB->exec($sql) === false)
    die($DB->error);

$salt = randomChar(16);

// Config Table Value
$sql = " insert into `{$nt['config_table']}` ( cf_site_name, cf_locale, cf_email_name, cf_email_address, cf_theme, cf_page_rows, cf_page_limit, cf_enc_salt, cf_max_level, cf_member_level, cf_super_admin, cf_keywords, cf_css_version, cf_js_version, cf_token_time, cf_password_length ) values ( 'NT-Core', 'ko', 'NT-Core', '{$mb_email}', 'simple', '15', '10', '{$salt}', '10', '2', '100', '', '', '', '10', '8' ) ";

if ($DB->exec($sql) === false)
    die($DB->error);

$config = getConfig();

// Member Table Create
$sql = " CREATE TABLE `{$nt['member_table']}` (
    `mb_uid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `mb_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `mb_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `mb_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `mb_admin` int(11) NOT NULL,
    `mb_level` int(11) NOT NULL,
    `mb_memo` text COLLATE utf8mb4_unicode_ci NULL,
    `mb_leave` date NULL,
    `mb_block` date NULL,
    `mb_date` datetime NOT NULL,
    PRIMARY KEY (`mb_uid`),
    UNIQUE KEY `mb_email` (`mb_email`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; ";

if ($DB->exec($sql) === false)
    die($DB->error);

// Super Admin Member
$mb_uid      = getMemberUID();
$mb_password = passwordCreate($mb_password);

$sql = " insert into `{$nt['member_table']}` ( mb_uid, mb_name, mb_email, mb_password, mb_admin, mb_level, mb_date ) values ( :mb_uid, :mb_name, :mb_email, :mb_password, :mb_admin, :mb_level, :mb_date ) ";

$DB->prepare($sql);
$DB->bindValueArray(
    [
        ':mb_uid'      => $mb_uid,
        ':mb_name'     => $mb_name,
        ':mb_email'    => $mb_email,
        ':mb_password' => $mb_password,
        ':mb_admin'    => $config['cf_super_admin'],
        ':mb_level'    => $config['cf_max_level'],
        ':mb_date'     => NT_TIME_YMDHIS
    ]
);

if (!$DB->execute()) {
    die($DB->error);
}

// Board Table Create
$sql = " CREATE TABLE `{$nt['board_table']}` (
    `bo_no` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `bo_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `bo_parent` int(11) UNSIGNED DEFAULT NULL,
    `mb_uid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `bo_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `bo_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `bo_subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `bo_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `bo_notice` tinyint(4) DEFAULT NULL,
    `bo_secret` tinyint(4) DEFAULT NULL,
    `bo_reply` tinyint(4) UNSIGNED DEFAULT NULL,
    `bo_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `bo_comment` int(11) DEFAULT NULL,
    `bo_link` tinyint(4) UNSIGNED DEFAULT NULL,
    `bo_file` tinyint(4) UNSIGNED DEFAULT NULL,
    `bo_view` int(11) UNSIGNED DEFAULT NULL,
    `bo_date` datetime DEFAULT NULL,
    `bo_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`bo_no`),
    KEY `bo_id` (`bo_id`),
    KEY `bo_parent` (`bo_parent`),
    KEY `mb_uid` (`mb_uid`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; ";

if ($DB->exec($sql) === false)
    die($DB->error);

// Board Comment Table Create
$sql = " CREATE TABLE `{$nt['board_comment_table']}` (
    `cm_no` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `bo_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `bo_no` int(11) UNSIGNED NOT NULL,
    `cm_parent` int(11) UNSIGNED DEFAULT NULL,
    `mb_uid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `cm_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cm_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cm_reply` tinyint(4) DEFAULT NULL,
    `cm_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `cm_date` datetime DEFAULT NULL,
    `cm_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`cm_no`),
    KEY `bo_id` (`bo_id`),
    KEY `bo_no` (`bo_no`),
    KEY `cm_parent` (`cm_parent`),
    KEY `mb_uid` (`mb_uid`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; ";

if ($DB->exec($sql) === false)
    die($DB->error);

// Board Config Table Create
$sql = " CREATE TABLE `{$nt['board_config_table']}` (
    `bo_id` varchar(20) NOT NULL,
    `bo_title` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `bo_use` tinyint(4) DEFAULT NULL,
    `bo_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `bo_subject_len` int(11) DEFAULT NULL,
    `bo_page_rows` int(11) DEFAULT NULL,
    `bo_page_limit` int(11) DEFAULT NULL,
    `bo_list_level` int(11) DEFAULT NULL,
    `bo_view_level` int(11) DEFAULT NULL,
    `bo_write_level` int(11) DEFAULT NULL,
    `bo_comment_level` int(11) DEFAULT NULL,
    `bo_reply_level` int(11) DEFAULT NULL,
    `bo_file_limit` int(11) DEFAULT NULL,
    `bo_link_limit` int(11) DEFAULT NULL,
    `bo_captcha_use` tinyint(4) DEFAULT NULL,
    `bo_captcha_comment` tinyint(4) DEFAULT NULL,
    PRIMARY KEY (`bo_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; ";

if ($DB->exec($sql) === false)
    die($DB->error);

// Board File Table Create
$sql = " CREATE TABLE `{$nt['board_file_table']}` (
    `bo_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `bo_no` int(11) UNSIGNED DEFAULT NULL,
    `fl_no` int(11) UNSIGNED NOT NULL,
    `fl_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `fl_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `fl_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `fl_down` int(11) DEFAULT NULL,
    KEY `bo_id` (`bo_id`),
    KEY `bo_no` (`bo_no`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; ";

if ($DB->exec($sql) === false)
    die($DB->error);

// Baord Link Table Create
$sql = " CREATE TABLE `{$nt['board_link_table']}` (
    `bo_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `bo_no` int(11) UNSIGNED DEFAULT NULL,
    `ln_no` int(11) UNSIGNED NOT NULL,
    `ln_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    KEY `bo_id` (`bo_id`),
    KEY `bo_no` (`bo_no`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; ";

if ($DB->exec($sql) === false)
    die($DB->error);

// free Board Create
$sql = " insert into `{$nt['board_config_table']}` ( bo_id, bo_title, bo_use, bo_subject_len, bo_page_rows, bo_page_limit, bo_list_level, bo_view_level, bo_write_level, bo_comment_level, bo_reply_level, bo_file_limit, bo_link_limit, bo_captcha_use, bo_captcha_comment ) values ( 'free', 'Free Board', '1', '50', '{$config['cf_page_rows']}', '{$config['cf_page_limit']}', '1', '1', '1', '1', '1', '2', '1', '0', '0' ) ";

if ($DB->exec($sql) === false)
    die($DB->error);

$sql = " CREATE TABLE `{$nt['visit_table']}` (
    `vi_date` date NOT NULL,
    `vi_time` time NOT NULL,
    `vi_referer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `vi_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `vi_ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
    KEY `vi_date` (`vi_date`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; ";

if ($DB->exec($sql) === false)
    die($DB->error);

// Pages table
$sql = " CREATE TABLE `{$nt['pages_table']}` (
    `pg_no` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `pg_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `pg_subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `pg_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `pg_header` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `pg_footer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `pg_use` tinyint(4) DEFAULT NULL,
    `pg_views` int(11) UNSIGNED NOT NULL,
    `pg_date` datetime NOT NULL,
    `pg_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`pg_no`),
    UNIQUE KEY `pg_id` (`pg_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; ";


if ($DB->exec($sql) === false)
    die($DB->error);

// Directory Create
@mkdir(NT_FILE_PATH, 0755, true);
@mkdir(NT_CACHE_PATH, 0755, true);
@mkdir(NT_SESSION_PATH, 0755, true);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo $html->title; ?></title>
<?php
echo $html->getPageStyle('header');
echo $html->getStyleString('header');
echo $html->getPageScript('header');
echo $html->getScriptString('header');
?>
</head>
<body>

<div class="highlight-clean">
    <div class="container">
        <div class="intro">
            <h2 class="text-center"><?php echo _('Complete!'); ?></h2>
            <p class="text-center"><?php echo _('NT-Core Setup is done well'); ?></p>
            <a class="btn btn-primary col-md-6" role="button" href="<?php echo NT_URL; ?>"><?php echo _('Home'); ?></a>
        </div>
    </div>
</div>

<?php
echo $html->getPageStyle('footer');
echo $html->getStyleString('footer');
echo $html->getPageScript('footer');
echo $html->getScriptString('footer');
?>
</body>
</html>