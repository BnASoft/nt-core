<?php
require_once './_common.php';
?>
jQuery(function() {
    jQuery(document).on("click", "form.form-ajax button:submit, form.form-ajax input:submit, form.form-ajax input:image", function(e) {
        e.preventDefault();
        e.stopPropagation();

        var f  = this.form;
        var $f = jQuery(f);
        var $t = jQuery(this);

        setTokenValue(this.form, "adm");

        $t.parent().append("<span class=\"pl-3 save_spinner save-spinner\"><img src=\"" + nt_img_url + "/spinner-2x.gif\"></span>");

        jQuery.ajax({
            url: f.action,
            method: f.method,
            data: $f.serialize(),
            success: function(data) {
                jQuery(".save_spinner").remove();

                if (data.error != "") {
                    $f.after("<div class=\"mt-3 alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" title=\"close\">&times;</a>" + data.error + "</div>");
                    return;
                }

                $t.parent().append("<div class=\"save_result save-done\"></div>");

                setTimeout(function() {
                    jQuery(".save_result").fadeOut(750, function() { jQuery(this).remove(); });
                }, 2000);
            },
            error: function(request, status, error) {
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            },
            dataType: "JSON"
        });

        $t.trigger("blur");
    });

    jQuery(document).on("click", ".member-edit", function(e) {
        PopupCenterDual(this.href, 'win_member', '720', '685');
        return false;
    });

    jQuery(document).on("click", ".board-edit", function(e) {
        PopupCenterDual(this.href, 'win_board', '720', '770');
        return false;
    });

    jQuery(document).on("click", ".delete-confirm", function(e) {
        if(!confirm("<?php echo _('Are you sure you want to delete?'); ?>"))
            return false;
    });

    jQuery(document).on("click", ".page-delete", function(e) {
        e.preventDefault();
        e.stopPropagation();

        if(!confirm("<?php echo _('Are you sure you want to delete?'); ?>"))
            return false;

        var token = setTokenValue("", "adm");

        jQuery.ajax({
            url: this.href + "&token=" + token,
            async: false,
            cache: false,
            success: function(data) {
                if (data.error != "") {
                    alert(data.error);
                    return;
                }

                document.location.reload();
            },
            error: function(request, status, error) {
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            },
            dataType: "JSON"
        });
    });
});