<?php
require_once './_common.php';

$html->setPageTitle(_('Member'));

require_once __DIR__.DIRECTORY_SEPARATOR.'header.inc.php';
require_once __DIR__.DIRECTORY_SEPARATOR.'header.sub.php';

$mb_uid = preg_replace('#[^0-9]#', '', $_GET['uid']);

$mb = array();

$mbLeave = 0;
$mbBlock = 0;

if ($mb_uid) {
    $sql = " select * from `{$nt['member_table']}` where mb_uid = :mb_uid ";

    $DB->prepare($sql);
    $DB->bindValue(':mb_uid', $mb_uid);
    $DB->execute();

    $mb = $DB->fetch();

    if (!$mb['mb_uid'])
        alertClose(_('Member information does not exist.'));

    if($member['mb_level'] < $mb['mb_level'])
        alertClose(_('Members of a higher level than you can not modify.'));

    if(!isNullTime((string)$mb['mb_leave']))
        $mbLeave = 1;
    if(!isNullTime((string)$mb['mb_block']))
        $mbBlock = 1;
} else {
    $mb['mb_level'] = __c('cf_member_level');
}
?>

<div class="col-md-6 my-4">
    <h1 class="h2"><?php echo $html->title; ?></h1>

    <form name="fconfig" method="post" class="form-ajax" action="./memberFormUpdate.php" autocomplete="off">
        <input type="hidden" name="uid" value="<?php echo $mb_uid; ?>">

        <div class="form-group row">
            <label for="mb_name" class="col-sm-3 col-form-label"><?php echo _('Name'); ?></label>
            <div class="col-sm-4">
                <input type="text" name="mb_name" id="mb_name" value="<?php echo getHtmlChar($mb['mb_name']); ?>" class="form-control form-control-sm" required autofocus>
            </div>
        </div>

        <div class="form-group row">
            <label for="mb_email" class="col-sm-3 col-form-label"><?php echo _('Email'); ?></label>
            <div class="col-sm-6">
                <input type="text" name="mb_email" id="mb_email" value="<?php echo getHtmlChar($mb['mb_email']); ?>" class="form-control form-control-sm" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="mb_password" class="col-sm-3 col-form-label"><?php echo _('Password'); ?></label>
            <div class="col-sm-4">
                <input type="password" name="mb_password" id="mb_password" value="" class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group row">
            <label for="mb_level" class="col-sm-3 col-form-label"><?php echo _('Level'); ?></label>
            <div class="col-sm-2">
                <select name="mb_level" id="mb_level" class="custom-select mr-sm-2" required>
                    <?php
                    for ($i = 1; $i <= __c('cf_max_level'); $i++) {
                        $optLevel = 'Lv. ' . $i;
                    ?>
                    <option value="<?php echo $i; ?>"<?php echo getSelected($i, $mb['mb_level']); ?>><?php echo $optLevel; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="mb_admin" class="col-sm-3 col-form-label"><?php echo _('Member Tyep'); ?></label>
            <div class="col-sm-3">
                <select name="mb_admin" id="mb_admin" class="custom-select mr-sm-2" required>
                    <option value="0"<?php echo getSelected(0, $mb['mb_admin']); ?>><?php echo _('Member'); ?></option>
                    <option value="1"<?php echo getSelected(1, $mb['mb_admin']); ?>><?php echo _('Manager'); ?></option>
                    <?php if ($isSuper) { ?>
                    <option value="<?php echo __c('cf_super_admin'); ?>"<?php echo getSelected(__c('cf_super_admin'), $mb['mb_admin']); ?>><?php echo _('Administrator'); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="mb_memo" class="col-sm-3 col-form-label"><?php echo _('Memo'); ?></label>
            <div class="col-sm-8">
                <textarea name="mb_memo" id="mb_memo" class="form-control" rows="5"><?php echo $mb['mb_memo']; ?></textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3"><?php echo _('Member withdrawal date'); ?></div>
            <div class="col-sm-3 form-check ml-3">
                <input type="checkbox" name="mb_leave" id="mb_leave" class="form-check-input" value="1"<?php echo getChecked(1, $mbLeave); ?>>
                <label for="mb_leave" class="form-check-label"><?php echo _('Leave'); ?></label>
                <?php if($mbLeave) { ?> <small class="text-secondary"><?php echo $mb['mb_leave']; ?></small><?php } ?>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3"><?php echo _('Member blocking date'); ?></div>
            <div class="col-sm-3 form-check ml-3">
                <input type="checkbox" name="mb_block" id="mb_block" class="form-check-input" value="1"<?php echo getChecked(1, $mbBlock); ?>>
                <label for="mb_block" class="form-check-label"><?php echo _('Block'); ?></label>
                <?php if($mbBlock) { ?> <small class="text-secondary"><?php echo $mb['mb_block']; ?></small><?php } ?>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3"><?php echo _('Sign Up'); ?></div>
            <div class="col-sm-3"><?php echo $mb['mb_date']; ?></div>
        </div>

        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-primary btn-lg"><?php echo _('Save'); ?></button>
            </div>
        </div>

    </form>
</div>

<?php
include_once(__DIR__.DIRECTORY_SEPARATOR.'footer.sub.php');
?>
