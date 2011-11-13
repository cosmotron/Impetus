var Quiz = {
    init: function() {
        this.bindProblemControls();
    },

    bindProblemControls: function() {
        $('#add-problem').click(function(event) {
            event.preventDefault();

            Quiz.addNewProblem();
        });

        $('.add-answer').live('click', function(event) {
            event.preventDefault();

            Quiz.addNewAnswer($(this).parents('.answers'));
        });
    },

    addNewAnswer: function($context) {
        var index = $('.remove-answer', $context).length;
        var answer_prototype = $($context).attr('data-prototype');
        var $answer = $(answer_prototype.replace(/\$\$answers\$\$/g, index));

        $('.answers-marker', $context).before($answer.clone());
    },

    addNewProblem: function() {
        var index = $('.remove-problem').length;
        var problem_prototype = $('#problems').attr('data-prototype');
        var $problem = $(problem_prototype.replace(/\$\$questions\$\$/g, index));

        $('#problems-marker').before($problem.clone());
    }
}