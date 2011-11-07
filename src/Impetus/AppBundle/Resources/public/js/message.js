var recipient_box =
    ['<span class="recipient-box">',
     '    <span class="recipient-name"></span>',
     '    <span class="recipient-remove"><a href="#">x</a></span>',
     '</span>'].join('\n');

var Message = {
    recipient_box_html: $(recipient_box),
    role_recipients: {},
    user_recipients: {},

    init: function() {
        this.bindRecipientAdd();
        this.bindRecipientBox();
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
                    Message.addSelectedToRecipientGroup(ui.item);
                    $('#recipient-add input').val('');
                    return false;
                }
            })
        ;
    },

    bindRecipientBox: function() {
        $('.recipient-remove a').live('click', function(event) {
            event.preventDefault();

            var recipient_name = $(this).parent().siblings('.recipient-name').text();
            alert(recipient_name);

            if ($(this).parents('.recipient-box').hasClass('role-box')) {
                delete Message.role_recipients[recipient_name]
            }
            else if ($(this).parents('.recipient-box').hasClass('user-box')) {
                delete Message.user_recipients[recipient_name]
            }

            $(this).parents('.recipient-box').remove();
        });
    },

    addSelectedToRecipientGroup: function(item) {
        var box = this.recipient_box_html.clone();

        if (item.type == 'role') {
            box.addClass('role-box');
            box.find('.recipient-name').html(item.value);

            // Make sure the recipient isn't already added
            if (!(item.value in this.role_recipients)) {
                this.role_recipients[item.value] = item.id;
                $('#recipient-add').before(box);

                var role_json = JSON.stringify(Message.role_recipients);
                $('#role-recipients').val(role_json);
            }
        }
        else if (item.type == 'user') {
            box.addClass('user-box');
            box.find('.recipient-name').html(item.value);

            if (!(item.value in this.user_recipients)) {
                this.user_recipients[item.value] = item.id;
                $('#recipient-add').before(box);

                var user_json = JSON.stringify(Message.user_recipients);
                $('#user-recipients').val(user_json);
            }
        }
        else {
            Impetus.errorAlert('Could not add selected to group - bad group type');
        }
    }
}