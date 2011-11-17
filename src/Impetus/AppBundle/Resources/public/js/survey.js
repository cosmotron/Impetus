var Survey = {
    init: function() {
        this.bindQuestionControls();
    },

    bindQuestionControls: function() {
        $('#add-multiple-choice-question').click(function(event) {
            event.preventDefault();

            Survey.addNewMultipleChoiceQuestion();
        });

        $('#add-short-answer-question').click(function(event) {
            event.preventDefault();

            Survey.addNewShortAnswerQuestion();
        });

        $('#add-scale-question').click(function(event) {
            event.preventDefault();

            Survey.addNewScaleQuestion();
        });

        $('.add-answer').live('click', function(event) {
            event.preventDefault();

            Survey.addNewAnswer($(this).parents('.answers'));
        });
    },

    addNewAnswer: function($context) {
        var index = $('.remove-answer', $context).length;
        var answer_prototype = $($context).attr('data-prototype');
        var $answer = $(answer_prototype.replace(/\$\$answers\$\$/g, index));

        $('.answers-marker', $context).before($answer.clone());
    },

    addNewMultipleChoiceQuestion: function() {
        var index = $('.remove-question').length;
        var question_prototype = $('#questions').attr('multiple-choice-prototype');
        var $question = $(question_prototype.replace(/\$\$questions\$\$/g, index));

        $question.find('input[type=hidden]').val('multipleChoice')

        $('#questions-marker').before($question.clone());
    },

    addNewShortAnswerQuestion: function() {
        var index = $('.remove-question').length;
        var question_prototype = $('#questions').attr('short-answer-prototype');
        var $question = $(question_prototype.replace(/\$\$questions\$\$/g, index));

        $question.find('input[type=hidden]').val('shortAnswer')

        $('#questions-marker').before($question.clone());
    },

    addNewScaleQuestion: function() {
        var index = $('.remove-question').length;
        var question_prototype = $('#questions').attr('scale-prototype');
        var $question = $(question_prototype.replace(/\$\$questions\$\$/g, index));

        $question.find('input[type=hidden]').val('scale')

        $('#questions-marker').before($question.clone());
    }
}