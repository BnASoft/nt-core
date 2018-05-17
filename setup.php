<?php
define('_SETUP_', true);
require_once './_common.php';

if (!isset($nt['config_table']) || !isset($nt['member_table']) || !empty($config))
    die(_('Setup can not be executed.'));

$html->setPageTitle('NT-CORE Setup');
$html->addStyleSheet(NT_CSS_URL.DIRECTORY_SEPARATOR.'setup.css', 'header', 0);
$html->addStyleSheet('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css','header', 0, '',  'integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous"');

$html->addJavaScript('https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', 'header', 0);
$html->addJavaScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js', 'footer', 0, '', 'integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"');
$html->addJavaScript('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js', 'footer', 0, '', 'integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"');
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

<form class="form-signin" method="post" action="./setupRun.php">
    <div class="row">
        <h1 class="h3 mb-3 font-weight-normal"><?php echo $html->title; ?></h1>
    </div>

    <?php if (!is_dir(NT_DATA_PATH) || !is_writable(NT_DATA_PATH)) { ?>
    <div class="row">
        <div class="alert alert-danger" role="alert">
            <?php echo sprintf(_('Do not have permission to write to the %s folder.'), basename(NT_DATA_PATH)); ?>
        </div>
    </div>
    <?php } else { ?>
    <div class="row mb-3">
        <label for="mb_name" class="sr-only"><?php echo _('Name'); ?></label>
        <input type="text" name="mb_name" id="mb_name" class="form-control" placeholder="<?php echo _('Name'); ?>" required autofocus>
    </div>
    <div class="row mb-3">
        <label for="mb_email" class="sr-only"><?php echo _('Email'); ?></label>
        <input type="email" name="mb_email" id="mb_email" class="form-control" placeholder="<?php echo _('Email'); ?>" required>
    </div>
    <div class="row mb-3">
        <label for="mb_password" class="sr-only"><?php echo _('Password'); ?></label>
        <input type="text" name="mb_password" id="mb_password" class="form-control" placeholder="<?php echo _('Password'); ?>" required>
    </div>
        <div class="row">
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo _('Setup'); ?></button>
    </div>
    <?php } ?>
</form>

<?php
echo $html->getPageStyle('footer');
echo $html->getStyleString('footer');
echo $html->getPageScript('footer');
echo $html->getScriptString('footer');
?>
</body>
</html>