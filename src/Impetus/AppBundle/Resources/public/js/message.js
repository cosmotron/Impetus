var recipient_box =
    ['<span class="recipient-box">',
     '    <span class="recipient-name"></span>',
     '    <span class="recipient-remove"><a href="#">x</a></span>',
     '</span>'].join('\n');

var Message = {
    currentUser: null,
    recipient_box_html: $(recipient_box),
    recipients: {},

    init: function(currentUser) {
        this.currentUser = currentUser;

        this.autocompleteSetup();

        this.bindRecipientAdd();
        this.bindRecipientRemove();
    },

    autocompleteSetup: function() {
        $.ui.autocomplete.prototype._renderItem = function(ul, item) {
            var term = this.term.split(' ').join('|');
            var re = new RegExp("(" + term + ")", "gi") ;
            var t = item.label.replace(re, "<b>$1</b>");
            return $( "<li></li>" )
                .data( "item.autocomplete", item )
                .append( "<a>" + t + "</a>" )
                .appendTo( ul );
        };
    },

    bindRecipientAdd: function() {
        $('#recipient-add a').click(function(event) {
            event.preventDefault();

            $('#recipient-add a').toggle();
            $('#recipient-add input').toggle().focus();
        });

        $('#recipient-add input')
            .blur(function(event) {
                $('#recipient-add a').toggle();
                $('#recipient-add input').val('').toggle();
            })
            .autocomplete({
                source: Routing.generate('_message_recipient_search'),
                minLength: 2,
                select: function(event, ui) {
                    Message.addSelectedRecipient(ui.item);
                    $('#recipient-add input').val('');
                    return false;
                }
            })
        ;
    },

    bindRecipientRemove: function() {
        $('.recipient-remove a').live('click', function(event) {
            event.preventDefault();

            var box_id = $(this).parents('.recipient-box').attr('id');
            var recipient_name = $(this).parent().siblings('.recipient-name').text();

            delete Message.recipients[box_id]

            $(this).parents('.recipient-box').remove();
        });
    },

    addSelectedRecipient: function(item) {
        var box_id = item.type + '-' + item.id;

        // Make sure the recipient isn't already added
        if (!(box_id in this.recipients) && (item.id != Message.currentUser)) {
            var box = this.recipient_box_html.clone();

            box.attr('id', box_id);
            box.find('.recipient-name').html(item.value);

            Message.recipients[box_id] = { 'id': item.id, 'value': item.value, 'type': item.type };

            $('#recipient-add').before(box);

            $('#recipients').val(JSON.stringify(Message.recipients));
        }
    }
}