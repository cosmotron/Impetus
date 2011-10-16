var UserSearch = {
    district_id: null,
}

$(function() {
    $("#add-student input").autocomplete({
        source: Routing.generate('_user_search', {'type': 'student'}),
        minLength: 2,
        select: function(event, ui) {
            alert(ui.item.value);
        }
    });

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
    }).blur().parents('form').submit(function() {
        $(this).find('[placeholder]').each(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
            }
        })
            });
});