$(document).ready(function() {
    // Handle query links
    $('.query-link').click(function(event) {
        event.preventDefault();
        let query = $(this).data('query');
        $.get('nba.php', { query: query }, function(data) {
            $('#modal-body').html(data);
            $('#queryModal').modal('show');
        });
    });

    // Handle ad-hoc query form submission
    $('#adhoc-query-form').submit(function(event) {
        event.preventDefault();
        let adhocQuery = $('#adhoc_query').val();
        $.post('nba.php', { adhoc_query: adhocQuery, submit: true }, function(data) {
            $('#modal-body').html(data);
            $('#queryModal').modal('show');
        });
    });
});