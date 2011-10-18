var District = {
    id: null,
    student_row: null,
    user_row: null,

    init: function(district_id) {
        this.id = district_id;
        this.student_row = $('.student-list-row:first').clone();
        this.user_row = $('.user-list-row:first').clone();

        this.bindAutoCompletes();
        this.bindGradeUpdators();
        this.bindPlaceholderReplacement();
    },

    bindAutoCompletes: function() {
        $('add-student input').autocomplete({
            source: Routing.generate('_user_search', {'type': 'student'}),
            minLength: 2,
            select: function(event, ui) {
                District.createUserListRow(ui.item, student_row, '#student-list');
            }
        });
    },

    bindGradeUpdators: function() {
        $('.grade-selector').live('change', function() {
            var id = $(this).parents('li').attr('id');
            var user_id = id.substring(id.lastIndexOf('-') + 1);

            $.ajax({
                url: Routing.generate('_district_update_grade', { 'userId': user_id }),
                type: 'POST',
                data: $(this).parent().serialize(),
                success: function(data) {
                    ;
                },
                error: function() {
                    alert("Error saving grade.");
                }
            });
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

    createUserListRow: function(data, row_html, append_list_selector) {
        row_html.attr('id', 'user-' + data.id);
        row_html.find('.name').html(data.value);
        row_html.appendTo(append_list_selector);
    }
}
