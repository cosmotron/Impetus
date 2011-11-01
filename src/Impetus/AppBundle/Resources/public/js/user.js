var User = {
    init: function() {
        this.bindActivityControls();
        this.bindCourseControls();
        this.bindExamControls();
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
        var raw_row = $('#activities').attr('data-prototype').replace(/\$\$name\$\$/g, index)
        var formatted_row = $('<div class="activities-row" />').html($('select', raw_row)).append(' &mdash; <a href="#" class="remove-activity">Remove</a>');

        $('#activities-marker').before(formatted_row.clone());
    },

    bindCourseControls: function() {
        $('.add-course').live('click', function(event) {
            event.preventDefault();

            User.addCourseRow();
        });

        $('.remove-course').live('click', function(event) {
            event.preventDefault();

            $(this).parents('.courses-row').remove();
        });
    },

    addCourseRow: function() {
        var index = $('.remove-course').length;
        var raw_row = $('#courses').attr('data-prototype').replace(/\$\$name\$\$/g, index)
        var formatted_row = $('<div class="courses-row" />').html($('select', raw_row)).append(' &mdash; <a href="#" class="remove-course">Remove</a>');

        $('#courses-marker').before(formatted_row.clone());
    },

    bindExamControls: function() {
        $('.add-exam').live('click', function(event) {
            event.preventDefault();

            User.addExamRow();
        });

        $('.remove-exam').live('click', function(event) {
            event.preventDefault();

            $(this).parents('.exams-row').remove();
        });
    },

    addExamRow: function() {
        var index = $('.remove-exam').length;
        var raw_row = $('#exams').attr('data-prototype').replace(/\$\$name\$\$/g, index)
        var formatted_row = $('<div class="exams-row" />')
            .html($('select', raw_row))
            .append(' &mdash; Score: ')
            .append($('input', raw_row))
            .append(' &mdash; <a href="#" class="remove-exam">Remove</a>');

        $('#exams-marker').before(formatted_row.clone());
    }
}