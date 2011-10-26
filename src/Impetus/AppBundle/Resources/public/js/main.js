var Impetus = {
    init: function() {
        this.createYearChanger();

        this.bindFlashNoticeButtons();
        this.bindPlaceholderReplacement();
    },

    bindFlashNoticeButtons: function() {
        // TODO: Perhaps implement an Undo

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
    },

    populateYearOptions: function(responseJson) {
        var years = responseJson.years;
        var current_year = responseJson.current;
        var output = '';

        for (var i in years) {
            var selected = (years[i].year == current_year) ? 'selected="selected"' : '';
            output += '<option ' + selected + '>' + years[i].year + '</option>';
        }

        return output;
    },

    createYearChanger: function() {
        $.ajax({
            url: Routing.generate('_year_list'),
            type: 'GET',
            dataType: 'json',
            error: Impetus.errorAlert,
            success: function(data) {
                $('#academic-year').html(Impetus.populateYearOptions(data));
            }
        });

        $('#academic-year').change(function(event) {
            var year = $(this).val();

            $.ajax({
                url: Routing.generate('_year_change', { 'year': year }),
                type: 'POST',
                error: Impetus.errorAlert,
                success: function(data) {
                    if (data == 'success') {
                        window.location.reload();
                    }
                }
            });
        });
    },

    errorAlert: function(msg) {
        msg = (typeof a == 'undefined') ? 'no details provided' : msg;

        alert('Error processing request: ' + msg);
    }
}