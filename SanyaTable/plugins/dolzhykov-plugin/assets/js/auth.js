(function($) {

    $(document).ready( function() {
        var form_auth = $('#lichess_auth');

        form_auth.click( function() {
            var data = {
                action: 'start_auth',
            };

            jQuery.post(myajax.url, data, function(response) {

                console.log(response);
                window.location.href = response.url_for_auth;
            });
        });
    })

})(jQuery);