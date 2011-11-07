var student_row =
    ['<li id="student-0" class="student-list-row">',
     '    <div class="action"><a href="#" class="remove-student">Remove</a></div>',
     '       <div class="data">',
     '           <a href="#" class="name"></a>',
     '           <form class="user-grade" style="display: inline;">',
     '               &mdash; Grade:',
     '               <select style="font-size: 14px;" class="grade-selector" name="grade">',
     '                   <option>12</option>',
     '                   <option>11</option>',
     '                   <option>10</option>',
     '                   <option>9</option>',
     '                   <option>8</option>',
     '                   <option>7</option>',
     '                   <option>6</option>',
     '                   <option>5</option>',
     '                   <option>4</option>',
     '                   <option>3</option>',
     '                   <option>2</option>',
     '                   <option>1</option>',
     '               </select>',
     '           </form>',
     '       </div>',
     '   </li>'].join('\n');

var user_row =
    ['    <li id="template" class="user-list-row">',
     '       <div>',
     '           <div class="action"><a href="#" class="remove-">Remove</a></div>',
     '           <div class="data">',
     '               <a href="#" class="name"></a>',
     '           </div>',
     '       </div>',
     '   </li>'].join('\n');

var District = {
    id: null,
    student_row_html: $(student_row),
    user_row_html: $(user_row),

    init: function(district_id) {
        this.id = district_id;

        this.bindAutoComplete('teacher', this.user_row_html);
        this.bindRemoveUsers('teacher');

        this.bindAutoComplete('assistant', this.user_row_html);
        this.bindRemoveUsers('assistant');

        this.bindAutoComplete('student', this.student_row_html);
        this.bindRemoveUsers('student');

        this.bindGradeUpdator();
    },

    bindAutoComplete: function(type, row_html) {
        var add_id = '#add-' + type;
        var list_id = '#list-' + type;

        $('input', add_id).autocomplete({
            source: Routing.generate('_user_search', { 'type': type }),
            minLength: 2,
            select: function(event, ui) {
                $.ajax({
                    url: Routing.generate('_district_roster_add', { 'districtId': District.id, 'type': type, 'userId': ui.item.id }),
                    type: 'GET',
                    error: Impetus.errorAlert,
                    success: function(data) {
                        if (data != 'success') {
                            alert('Error adding user to roster');
                        }
                        else {
                            District.createUserListRow(ui.item, row_html, list_id);
                            $('input', add_id).val('');
                            return false;
                        }
                    }
                });
            }
        });
    },

    bindGradeUpdator: function() {
        $('.grade-selector').live('change', function() {
            var user_id = District.getUserIdFromRow(this);

            $.ajax({
                url: Routing.generate('_district_update_grade', { 'userId': user_id }),
                type: 'POST',
                data: $(this).parent().serialize(),
                error: Impetus.errorAlert,
                success: function(data) {
                    if (data != 'success') {
                        alert('Error saving grade');
                    }
                }
            });
        });
    },

    bindRemoveUsers: function(type) {
        var remove_class = '.remove-' + type;

        $(remove_class).live('click', function(event) {
            var user_id = District.getUserIdFromRow(this);
            var row_element = this;
            event.preventDefault();

            $.ajax({
                url: Routing.generate('_district_roster_remove', { 'districtId': District.id, 'type': type, 'userId': user_id }),
                type: 'GET',
                error: Impetus.errorAlert,
                success: function(data) {
                    if (data != 'success') {
                        alert('Error removing user from roster');
                    }
                    else {
                        District.removeUserListRow(row_element)
                    }
                }
            });
        });
    },

    createUserListRow: function(data, row_html, append_list_selector) {
        row_html.attr('id', 'user-' + data.id);
        row_html
            .find('.name')
            .attr('href', Routing.generate('_user_show', { 'id': data.id }))
            .html(data.value);

        if ($('.empty-list', append_list_selector).length) {
            $('.empty-list', append_list_selector).remove();
        }

        row_html.appendTo(append_list_selector);
    },

    removeUserListRow: function(row_element) {
        var parent_list = $(row_element).parents('ul');

        $(row_element).parents('li').remove();

        if (!$(parent_list).children('li').length) {
            $(parent_list).html('<li class="empty-list">Empty</li>');
        }
    },

    getUserIdFromRow: function(row_child_element) {
        var id = $(row_child_element).parents('li').attr('id');
        return id.substring(id.lastIndexOf('-') + 1);
    }
}
