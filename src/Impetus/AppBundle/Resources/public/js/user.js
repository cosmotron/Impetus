var User = {
    init: function() {
        this.bindActivityControls();
    },

    bindActivityControls: function() {
        $('.add-activity').live('click', function(event) {
            event.preventDefault();

            User.addActivityRow();
        });

        $('.remove-activity').live('click', function(event) {
            event.preventDefault();

            $(this).parents('.activities-row').remove();
        });
    },

    addActivityRow: function() {
        var index = $('.remove-activity').length;
        var raw_row = $('script[type="text/html"]').text().replace(/\$\$name\$\$/g, index)
        var formatted_row = $('<div class="activities-row" />').html($('select', raw_row)).append(' &mdash; <a href="#" class="remove-activity">Remove</a>');

        $('.activities-row:last').before(formatted_row.clone());
    }
}