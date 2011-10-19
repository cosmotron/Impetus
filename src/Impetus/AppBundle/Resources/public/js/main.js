var Impetus = {
    init: function() {
        this.bindFlashNoticeButtons();
        this.bindPlaceholderReplacement();
    },

    bindFlashNoticeButtons: function() {
        $("#flash-notice a.dismiss").click(function(event) {
            event.preventDefault();
            $("#flash-notice-wrapper").remove();
        });
    },

    bindPlaceholderReplacement: function() {
        $('[placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur().parents('form').submit(function(event) {
            event.preventDefault();

            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            });
        });
    }
}