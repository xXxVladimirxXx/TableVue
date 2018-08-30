<div class="row">
    <?php

        $acf_ext_form->render_field($fields, '_post_title');
        $acf_ext_form->render_field($fields, '_post_content');
        $acf_ext_form->render_field($fields, 'name_company');
        $acf_ext_form->render_field($fields, 'count_participant');
        $acf_ext_form->render_field($fields, 'activity_of_field');
        $acf_ext_form->render_field($fields, 'site_for_company');
        $acf_ext_form->render_field($fields, 'client');
        $acf_ext_form->render_field($fields, 'price');

    ?>
</div>