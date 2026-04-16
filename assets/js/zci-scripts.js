jQuery(document).ready(function($) {
    var upload_button;
    
    $(document).on('click', ".z_upload_image_button", function(event) {
        upload_button = $(this);
        var frame;
        if (zci_config.wordpress_ver >= "3.5") {
            event.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media();
            frame.on( "select", function() {
                // Grab the selected attachment.
                var attachment = frame.state().get("selection").first();
                var attachmentUrl = attachment.attributes.url;
                var attachmentId = attachment.attributes.id;
                attachmentUrl = attachmentUrl.replace('-scaled.', '.');

                frame.close();
                $(".zci-taxonomy-image").attr("src", attachmentUrl);
                if (upload_button.parent().prev().children().hasClass("tax_list")) {
                    upload_button.parent().prev().children().val(attachmentUrl);
                    upload_button.parent().prev().prev().children().attr("src", attachmentUrl);
                    upload_button.parent().next().children().val(attachmentId);
                }
                else {
                    $("#zci_taxonomy_image").val(attachmentUrl);
                    $("#zci_taxonomy_image_id").val(attachmentId);
                }
            });
            frame.open();
        }
        else {
            tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
            return false;
        }
    });
    
    $(document).on('click', ".z_remove_image_button", function() {
        $(".zci-taxonomy-image").attr("src", zci_config.placeholder);
        $("#zci_taxonomy_image").val("");
        $("#zci_taxonomy_image_id").val("");
        $(this).parent().siblings(".title").children("img").attr("src", zci_config.placeholder);
        $(".inline-edit-col :input[name='zci_taxonomy_image']").val("");
        $(".inline-edit-col :input[name='zci_taxonomy_image_id']").val("");
        return false;
    });
    
    if (zci_config.wordpress_ver < "3.5") {
        window.send_to_editor = function(html) {
            imgurl = $("img",html).attr("src");
            if (upload_button.parent().prev().children().hasClass("tax_list")) {
                upload_button.parent().prev().children().val(imgurl);
                upload_button.parent().prev().prev().children().attr("src", imgurl);
            }
            else
                $("#zci_taxonomy_image").val(imgurl);
            tb_remove();
        }
    }
    
    $(document).on('click', ".editinline", function() {
        var tax_id = $(this).parents("tr").attr("id").substr(4);
        var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");
        
        // Populate inputs from hidden data attributes
        var zci_data = $("#tag-"+tax_id+" .thumb .zci-data");
        var full_url = zci_data.data("url");
        var image_id = zci_data.data("id");

        if (full_url) {
            $(".inline-edit-col :input[name='zci_taxonomy_image']").val(full_url);
            $(".inline-edit-col :input[name='zci_taxonomy_image_id']").val(image_id);
        } else {
            $(".inline-edit-col :input[name='zci_taxonomy_image']").val("");
            $(".inline-edit-col :input[name='zci_taxonomy_image_id']").val("");
        }
        
        $(".inline-edit-col .title img").attr("src", thumb);
    });
});