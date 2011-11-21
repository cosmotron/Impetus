var Survey = {
    counter: null,

    init: function() {
        this.counter = 0;
        this.bindQuestionControls();
    },

    listInit: function() {
        this.bindSurveyDelete();
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

        $('.remove-question').live('click', function(event) {
            event.preventDefault();

            $(this).parents('.question-wrapper').remove();
        });

        $('.add-answer').live('click', function(event) {
            event.preventDefault();

            Survey.addNewAnswer($(this).parents('.answers'));
        });
    },

    bindSurveyDelete: function() {
        $('.delete-survey').click(function(event) {
            event.preventDefault();

            var $surveyRow = $(this).parents('tr');

            if (confirm("Delete this survey and all of its responses? This is permanent.")) {
                $.ajax({
                    url: Routing.generate('_survey_delete'),
                    type: 'POST',
                    data: {'id': $(this).attr('survey-id')},
                    error: Impetus.errorAlert,
                    success: function(data) {
                        if (data == 'success') {
                            $surveyRow.remove();
                        }
                    }
                });
            }
        });
    },

    addNewAnswer: function($context) {
        //var index = $('.remove-answer', $context).length;
        var answer_prototype = $($context).attr('data-prototype');
        var $answer = $(answer_prototype.replace(/\$\$answers\$\$/g, Survey.counter));
        Survey.counter++;

        $('.answers-marker', $context).before($answer.clone());
    },

    addNewMultipleChoiceQuestion: function() {
        //var index = $('.remove-question').length;
        var question_prototype = $('#questions').attr('multiple-choice-prototype');
        var $question = $(question_prototype.replace(/\$\$questions\$\$/g, Survey.counter));
        Survey.counter++;

        $question.find('input[type=hidden]').val('multipleChoice')

        $('#questions-marker').before($question.clone());
    },

    addNewShortAnswerQuestion: function() {
        //var index = $('.remove-question').length;
        var question_prototype = $('#questions').attr('short-answer-prototype');
        var $question = $(question_prototype.replace(/\$\$questions\$\$/g, Survey.counter));
        Survey.counter++;

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