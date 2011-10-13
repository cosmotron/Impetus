var addUserDiv = '<div class="perm-row"> \
    <input class="perm-name" type="text" /> \
    <div class="perm-delete"><a href="#">Delete</a></div> \
</div>';


$(function() {
    $('#add-teacher').click(function(event) {
        event.preventDefault();

        $(this).toggle();
        $(this).parent().append(addUserDiv);
    });
});