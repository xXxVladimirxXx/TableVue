jQuery('document').ready(function ($) {

    $(document).on('submit',function(){

        $form = $('#inquiries_create_form');
        var ajaxHandler = new AcfAjaxFormHandler($form, {'success': printSuccessMessage});


        /**
         * Отображает сообщение об успешном сохранении
         *
         * @param response
         */
        function printSuccessMessage(response) {

            if (typeof response.post_id != 'undefined') {

                ajaxHandler.printMessage($form, 'Данные request сохранены!');
            }
        }
        $(document).off('submit');
    });

});