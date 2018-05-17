<?php
require_once './_common.php';

$w = substr($_GET['w'], 0, 1);

if ($w == '' && $isMember)
    gotoUrl(NT_URL);

$mb_uid = '';

if ($w == 'u') {
    if (!$isMember)
        gotoUrl(NT_LINK_LOGIN);

    if (!$_SESSION['ss_password_check']) {
        $_SESSION['ss_password_mode'] = 'modify';
        gotoUrl(NT_LINK_PASSWORD);
    }

    $enc = new STRENCRYPT();
    $mb_uid = $enc->encrypt($member['mb_uid']);
}

$captcha = new reCAPTCHA();

if ($w == 'u')
    $html->setPageTitle(_('My Account'));
else
    $html->setPageTitle(_('Sign Up'));

$html->addJavaScript(NT_JS_URL.DIRECTORY_SEPARATOR.'signup.js.php', 'footer', 10);

if (__c('cf_recaptcha_site_key'))
    $html->addJavaScript('https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=', 'footer', 10, '', 'async defer');

$html->getPageHeader();

if (__c('cf_recaptcha_site_key'))
    $captcha->getScript();

$submitButton = _('Sign Up');
if ($w == 'u')
    $submitButton = _('Edit');
?>

<form name="fsignup" id="fsignup" class="form-signup form-token" method="post" action="<?php echo NT_MEMBER_URL; ?>/signupRun.php" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w; ?>">
    <input type="hidden" name="uid" value="<?php echo $mb_uid; ?>">

    <h1 class="h3 mb-3 font-weight-normal"><?php echo $html->title; ?></h1>

    <div class="mb-3">
        <label for="mb_name"><?php echo _('Name'); ?></label>
        <input type="text" name="mb_name" id="mb_name" class="form-control" value="<?php echo getHtmlChar($member['mb_name']); ?>" data-toggle="popover" data-trigger="focus" required autofocus>
    </div>

    <div class="mb-3">
        <label for=""><?php echo _('Email'); ?></label>
        <input type="email" name="mb_email" id="mb_email" class="form-control" value="<?php echo getHtmlChar($member['mb_email']); ?>" data-toggle="popover" data-trigger="focus" required>
    </div>

    <div class="mb-3">
        <label for="mb_password"><?php echo _('Password'); ?></label>
        <input type="password" name="mb_password" id="mb_password" class="form-control" data-toggle="popover" data-trigger="focus"<?php echo ($w != 'u' ? ' required' : ''); ?>>
    </div>

    <?php if ($w != 'u') { ?>
    <div class="mb-3">
        <label for="mb_password_re"><?php echo _('Re-enter password'); ?></label>
        <input type="password" name="mb_password_re" id="mb_password_re" class="form-control" data-toggle="popover" data-trigger="focus" required>
    </div>
    <?php } ?>

    <?php if (__c('cf_recaptcha_site_key')) { ?>
    <div class="mb-3">
        <?php $captcha->getElement(); ?>
    </div>
    <?php } ?>

    <div class="mt-3">
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $submitButton; ?></button>
    </div>
</form>

<!-- Modal -->
<div class="modal fade" id="signupSuccess" tabindex="-1" role="dialog" aria-labelledby="signupSuccess" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo _('Sign Up'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center signup-success text-success">
                    <i class="icon" data-feather="check-circle"></i>
                </div>
                <div class="pt-4 text-center">
                    <?php echo _('Your account has been created.'); ?>
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?php echo NT_URL; ?>" class="btn btn-primary"><?php echo _('Go Home'); ?></a>
            </div>
        </div>
    </div>
</div>

<?php
$html->getPageFooter();
?>