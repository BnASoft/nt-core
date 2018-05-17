<?php
require_once './_common.php';
?>
jQuery(function() {
    jQuery(document).on("click", "form button:submit, form input:submit, form input:image", function(e) {
        e.preventDefault();
        e.stopPropagation();

        var f  = this.form;
        var $f = jQuery(f);
        var $t = jQuery(this);

        var email, pass, mode;

        email = jQuery.trim(f.email.value);
        pass  = jQuery.trim(f.pass.value);
        mode  = jQuery.trim(f.mode.value);

        if(email.length < 1) {
            jQuery("#inputEmail").data("content", "<?php echo _d('Please enter Email.', THEME_LOCALE_DOMAIN); ?>").popover("show");
            return false;
        }

        if(pass.length < 1) {
            jQuery("#inputPassword").data("content", "<?php echo _d('Please enter Password.', THEME_LOCALE_DOMAIN); ?>").popover("show");
            return false;
        }

        $t.after("<span class=\"mt-3 save_spinner save-spinner d-block w-100\"><img src=\"" + nt_img_url + "/spinner-2x.gif\"></span>");

        jQuery.ajax({
            url: f.action,
            method: "POST",
            data: $f.serialize(),
            success: function(data) {
                jQuery(".save_spinner").remove();

                if(data.error != "") {
                    $t.after("<div class=\"mt-3 alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" title=\"close\">&times;</a>" + data.error + "</div>");
                    return;
                }

                if(mode == "leave") {
                    jQuery("#withdrawalConfirm").modal("show");
                    return;
                } else {
                    document.location.href = data.href;
                }
            },
            error: function(request, status, error) {
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            },
            dataType: "JSON"
        });
    });
});
