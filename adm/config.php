<?php
require_once './_common.php';

if (!$isSuper)
    alert(_('Only super administrators can access.'));

$html->setPageTitle(_('Configuration'));

require_once NT_ADMIN_PATH.DIRECTORY_SEPARATOR.'header.php';
?>

<div class="col-md-8">
    <form name="fconfig" method="post" class="form-ajax" action="./configUpdate.php" autocomplete="off">

        <div class="form-group row">
            <label for="cf_site_name" class="col-sm-3 col-form-label"><?php echo _('Site name'); ?></label>
            <div class="col-sm-5">
                <input type="text" name="cf_site_name" id="cf_site_name" value="<?php echo $config['cf_site_name']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_locale" class="col-sm-3 col-form-label"><?php echo _('Site Locale'); ?></label>
            <div class="col-sm-3">
                <select name="cf_locale" id="cf_locale" class="custom-select mr-sm-2" required>
                    <?php
                    foreach ($_LOCALES as $k => $v) {
                    ?>
                    <option value="<?php echo $k; ?>"<?php echo getSelected($k, $config['cf_locale']); ?>><?php echo $v[1]; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_email_name" class="col-sm-3 col-form-label"><?php echo _('Email sending name'); ?></label>
            <div class="col-sm-5">
                <input type="text" name="cf_email_name" id="cf_email_name" value="<?php echo $config['cf_email_name']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_email_address" class="col-sm-3 col-form-label"><?php echo _('Email sending address'); ?></label>
            <div class="col-sm-5">
                <input type="text" name="cf_email_address" id="cf_email_address" value="<?php echo $config['cf_email_address']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_theme" class="col-sm-3 col-form-label"><?php echo _('Theme'); ?></label>
            <div class="col-sm-4">
                <select name="cf_theme" id="cf_theme" class="custom-select mr-sm-2" required>
                    <?php
                    foreach (getThemeDir() as $dir) {
                    ?>
                    <option value="<?php echo $dir; ?>"<?php echo getSelected($dir, $config['cf_theme']); ?>><?php echo $dir; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_recaptcha_site_key" class="col-sm-3 col-form-label"><?php echo _('reCAPTCHA Site key'); ?></label>
            <div class="col-sm-8">
                <input type="text" name="cf_recaptcha_site_key" id="cf_recaptcha_site_key" value="<?php echo $config['cf_recaptcha_site_key']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_recaptcha_secret_key" class="col-sm-3 col-form-label"><?php echo _('reCAPTCHA secret key'); ?></label>
            <div class="col-sm-8">
                <input type="text" name="cf_recaptcha_secret_key" id="cf_recaptcha_secret_key" value="<?php echo $config['cf_recaptcha_secret_key']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_css_version" class="col-sm-3 col-form-label"><?php echo _('CSS version'); ?></label>
            <div class="col-sm-3">
                <input type="text" name="cf_css_version" id="cf_css_version" value="<?php echo $config['cf_css_version']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="rform-group row">
            <label for="cf_js_version" class="col-sm-3 col-form-label"><?php echo _('JavaScript version'); ?></label>
            <div class="col-sm-3">
                <input type="text" name="cf_js_version" id="cf_js_version" value="<?php echo $config['cf_js_version']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <hr class="mb-4">

        <div class="form-group row">
            <label for="cf_page_rows" class="col-sm-3 col-form-label"><?php echo _('Lines per page'); ?></label>
            <div class="col-sm-2">
                <input type="text" name="cf_page_rows" id="cf_page_rows" value="<?php echo $config['cf_page_rows']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_page_limit" class="col-sm-3 col-form-label"><?php echo _('Number of pages'); ?></label>
            <div class="col-sm-2">
                <input type="text" name="cf_page_limit" id="cf_page_limit" value="<?php echo $config['cf_page_limit']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_max_level" class="col-sm-3 col-form-label"><?php echo _('Max member level'); ?></label>
            <div class="col-sm-2">
                <input type="text" name="cf_max_level" id="cf_max_level" value="<?php echo $config['cf_max_level']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_member_level" class="col-sm-3 col-form-label"><?php echo _('Sign Up member level'); ?></label>
            <div class="col-sm-2">
                <input type="text" name="cf_member_level" id="cf_member_level" value="<?php echo $config['cf_member_level']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_super_admin" class="col-sm-3 col-form-label"><?php echo _('Administrator level'); ?></label>
            <div class="col-sm-2">
                <input type="text" name="cf_super_admin" id="cf_super_admin" value="<?php echo $config['cf_super_admin']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_token_time" class="col-sm-3 col-form-label"><?php echo _('Token valid seconds'); ?></label>
            <div class="col-sm-2">
                <input type="text" name="cf_token_time" id="cf_token_time" value="<?php echo $config['cf_token_time']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_password_length" class="col-sm-3 col-form-label"><?php echo _('Minimum password length'); ?></label>
            <div class="col-sm-2">
                <input type="text" name="cf_password_length" id="cf_password_length" value="<?php echo $config['cf_password_length']; ?>" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="cf_keywords" class="col-sm-3 col-form-label"><?php echo _('Meta Keywords'); ?></label>
            <div class="col-sm-8">
                <textarea name="cf_keywords" id="cf_keywords" class="form-control" rows="5"><?php echo $config['cf_keywords']; ?></textarea>
            </div>
        </div>

        <hr class="mb-4">

        <div class="row">
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-lg"><?php echo _('Save'); ?></button>
            </div>
        </div>

    </form>
</div>

<?php
include_once(NT_ADMIN_PATH.DIRECTORY_SEPARATOR.'footer.php');
?>
