var Quiz = {
    counter: null,

    init: function() {
        this.counter = 0;
        this.bindProblemControls();

        $('#add-problem').click();
        $('.add-answer').click();
    },

    listInit: function() {
        this.bindQuizDelete();
    },

    bindProblemControls: function() {
        $('#add-problem').click(function(event) {
            event.preventDefault();

            Quiz.addNewProblem();
        });

        $('.remove-problem').live('click', function(event) {
            event.preventDefault();

            $(this).parents('.problem-wrapper').remove();
        });

        $('.add-answer').live('click', function(event) {
            event.preventDefault();

            Quiz.addNewAnswer($(this).parents('.answers'));
        });

        $('.remove-answer').live('click', function(event) {
            event.preventDefault();

            $(this).parents('.answer').remove();
        });
    },

    bindQuizDelete: function() {
        $('.delete-quiz').click(function(event) {
            event.preventDefault();

            var $quizRow = $(this).parents('tr');

            if (confirm("Delete this quiz and all of its attempts? This is permanent.")) {
                $.ajax({
                    url: Routing.generate('_quiz_delete'),
                    type: 'POST',
                    data: {'id': $(this).attr('quiz-id')},
                    error: Impetus.errorAlert,
                    success: function(data) {
                        if (data == 'success') {
                            $quizRow.remove();
                        }
                    }
                });
            }
        });
    },

    addNewAnswer: function($context) {
        //var index = $('.remove-answer', $context).length;
        var answer_prototype = $($context).attr('data-prototype');
        var $answer = $(answer_prototype.replace(/\$\$answers\$\$/g, Quiz.counter));
        Quiz.counter++;

        $('.answers-marker', $context).before($answer.clone());
    },

    addNewProblem: function() {
        //var index = $('.remove-problem').length;
        var problem_prototype = $('#problems').attr('data-prototype');
        var $problem = $(problem_prototype.replace(/\$\$questions\$\$/g, Quiz.counter));
        Quiz.counter++;

        $('#problems-marker').before($problem.clone());
    }
}