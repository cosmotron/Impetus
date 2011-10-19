var District = {
    id: null,
    student_row: null,
    user_row: null,

    init: function(district_id) {
        this.id = district_id;
        this.student_row_html = $('.student-list-row:first').clone();
        this.user_row_html = $('.user-list-row:first').clone();

        this.bindAutoCompletes();
        this.bindGradeUpdators();
    },

    bindAutoCompletes: function() {
        $('#add-teacher input').autocomplete({
            source: Routing.generate('_user_search', {'type': 'teacher'}),
            minLength: 2,
            select: function(event, ui) {
                // TODO: $.ajax call to add student to the roster
                District.createUserListRow(ui.item, District.user_row_html, '#teacher-list');
                $('#add-teacher input').val('');
                return false;
            }
        });

        $('#add-assistant input').autocomplete({
            source: Routing.generate('_user_search', {'type': 'assistant'}),
            minLength: 2,
            select: function(event, ui) {
                // TODO: $.ajax call to add student to the roster
                District.createUserListRow(ui.item, District.user_row_html, '#assistant-list');
                $('#add-assistant input').val('');
                return false;
            }
        });

        $('#add-student input').autocomplete({
            source: Routing.generate('_user_search', {'type': 'student'}),
            minLength: 2,
            select: function(event, ui) {
                // TODO: $.ajax call to add student to the roster
                District.createUserListRow(ui.item, District.student_row_html, '#student-list');
                $('#add-student input').val('');
                return false;
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

    createUserListRow: function(data, row_html, append_list_selector) {
        row_html.attr('id', 'user-' + data.id);
        row_html.find('.name').html(data.value);
        row_html.appendTo(append_list_selector);
    }
}
