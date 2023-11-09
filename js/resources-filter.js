jQuery(document).ready(function ($) {
    $('.resources-filter li').on('click', function () {
        var filter = $(this).data('filter');
        var data = {
            action: 'filter_resources',
            filter: filter,
        };

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'post',
            data: data,
            success: function (response) {
                $('.filtered-results').html(response);
            }
        });
    });
});
