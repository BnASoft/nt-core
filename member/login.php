<?php
require_once './_common.php';

if ($isMember)
    gotoUrl(NT_URL);

$html->setPageTitle(_('Log In'));
$html->addJavaScript(NT_JS_URL.DIRECTORY_SEPARATOR.'login.js.php', 'footer', 10);

if (__c('cf_recaptcha_site_key'))
    $html->addJavaScript('https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=', 'footer', 10, '', 'async defer');

$html->getPageHeader();

$captcha = new reCAPTCHA();

if (__c('cf_recaptcha_site_key'))
    $captcha->getScript();
?>

<form name="flogin" id="flogin" class="form-login form-token" method="post" action="<?php echo NT_MEMBER_URL; ?>/loginCheck.php">

    <h1 class="h3 mb-3 font-weight-normal"><?php echo $html->title; ?></h1>

    <label for="inputEmail" class="sr-only"><?php echo _('Email'); ?></label>
    <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" data-toggle="popover" data-trigger="focus" required autofocus>
    <label for="inputPassword" class="sr-only"><?php echo _('Password'); ?></label>
    <input type="password" name="pass" id="inputPassword" class="form-control" placeholder="Password" data-toggle="popover" data-trigger="focus" required>

    <?php if (__c('cf_recaptcha_site_key')) { ?>
    <div class="mt-3">
        <?php $captcha->getElement(); ?>
    </div>
    <?php } ?>

    <div class="mt-3">
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo _('Log In'); ?></button>
    </div>

    <div class="mt-3">
        <a href="<?php echo NT_LINK_SIGNUP; ?>" class="a"><?php echo _('Sign Up'); ?></a>
        <span class="sep">|</span>
        <a href="<?php echo NT_LINK_FIND; ?>" id="find_password" class="a"><?php echo _('Find Password'); ?></a>
    </div>

</form>

<?php
$html->getPageFooter();
?>