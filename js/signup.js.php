<?php
require_once './_common.php';
?>
jQuery(function() {
    // Email check
    jQuery(document).on("change", "form#fsignup #mb_email", function() {
        var $this = jQuery(this);
        var mb_email = $this.val();
        var w = jQuery("input[name=w]").val();

        if (mb_email.lenght < 1)
            return;

        jQuery.ajax({
            url: nt_ajax_url + "/memberEmail.php",
            type: "POST",
            async: true,
            cache: false,
            data: {email: mb_email},
            success: function(data) {
                if (data != "") {
                    $this.data("content", data).popover("show");
                    return false;
                }
            },
            error: function(request, status, error) {
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }
        });
    });

    jQuery(document).on("submit", "form#fsignup", function(e) {
        e.preventDefault();
        e.stopPropagation();

        var f  = this;
        var $f = jQuery(this);
        var w  = f.w.value;
        var $b = $f.find("button.btn");

        var mb_name, mb_email, mb_password, mb_password_re;

        mb_name        = jQuery.trim(f.mb_name.value);
        mb_email       = jQuery.trim(f.mb_email.value);
        mb_password    = jQuery.trim(f.mb_password.value);
        if (w != "u")
            mb_password_re = jQuery.trim(f.mb_password_re.value);

        if (mb_name.length < 1) {
            jQuery("#mb_name").data("content", "<?php echo _d('Please enter Name.', THEME_LOCALE_DOMAIN); ?>").popover("show");
            return false;
        }

        if (mb_email.length < 1) {
            jQuery("#mb_email").data("content", "<?php echo _d('Please enter Email.', THEME_LOCALE_DOMAIN); ?>").popover("show");
            return false;
        }

        if (w == "") {
            <?php if ((int)__c('cf_password_length') > 0) { ?>
            if (mb_password.length < <?php echo (int)__c('cf_password_length'); ?>) {
                jQuery("#mb_password").data("content", "<?php echo sprintf(_dn('Please enter your password at least %d character.', 'Please enter your password at least %d characters.', (int)__c('cf_password_length'), THEME_LOCALE_DOMAIN), (int)__c('cf_password_length')); ?>").popover("show");
                return false;
            }

            <?php } ?>
            if (mb_password != mb_password_re) {
                jQuery("#mb_password_re").data("content", "<?php echo _d('The password you entered does not match.', THEME_LOCALE_DOMAIN); ?>").popover("show");
                return false;
            }
        }

        <?php if ((int)__c('cf_password_length') > 0) { ?>
        if (w == "u" && mb_password && mb_password.length < <?php echo (int)__c('cf_password_length'); ?>) {
            jQuery("#mb_password").data("content", "<?php echo sprintf(_dn('Please enter your password at least %d character.', 'Please enter your password at least %d characters.', (int)__c('cf_password_length'), THEME_LOCALE_DOMAIN), (int)__c('cf_password_length')); ?>").popover("show");
            return false;
        }

        <?php } ?>
        if (typeof(grecaptcha) != "undefined") {
            if (grecaptcha.getResponse() == "") {
                jQuery("#recaptcha_area").data("content", "<?php echo _d('Please check the anti-spam code.', THEME_LOCALE_DOMAIN); ?>").popover("show");
                return false;
            }
        }

        var data = $f.serializeArray();

        $b.after("<span class=\"mt-3 save_spinner save-spinner d-block w-100\"><img src=\"" + nt_img_url + "/spinner-2x.gif\"></span>");

        jQuery.ajax({
            url: f.action,
            method: "POST",
            async: true,
            cache: false,
            data: data,
            success: function(data) {
                jQuery(".save_spinner").remove();

                if(data.error != "") {
                    jQuery("#"+data.element).data("content", data.error).popover("show");
                    return;
                }

                if (w != "") {
                    $b.after("<div class=\"mt-3 save_result save-done d-block w-100 text-center\"></div>");
                } else {
                    jQuery("#signupSuccess").modal("show");

                    jQuery(document).on("hidden.bs.modal", "#signupSuccess", function(e) {
                        document.location.href = "<?php echo NT_URL; ?>";
                    });

                    setTimeout(function() {
                        jQuery("#signupSuccess").modal("hide");
                    }, 7000)
                }

                setTimeout(function() {
                    jQuery(".save_result").fadeOut(750, function() { jQuery(this).remove(); document.location.href = "<?php echo NT_URL; ?>"; });
                }, 2000);
            },
            error: function(request, status, error) {
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            },
            dataType: "JSON"
        });
    });
});