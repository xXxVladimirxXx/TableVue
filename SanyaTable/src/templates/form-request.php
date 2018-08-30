<?php wp_enqueue_script('acf-request-ajax-handler'); ?>

<form method="post" id="<?php echo $acf_form->args['id']; ?>">
	
	<?php $acf_form->render_form(); ?>

    <div class="btn-wrap request__btn animation animated animation__has-fadeInTop_loaded full-visible" data-vp-add-class="animated animation__has-fadeInTop_loaded">
        <button type="submit" class="btn btn-primary" name="request" value="request">
            <?php $name_but = 'Добавить заявку'; ?>
            <span class="btn__over btn__over_gold"></span>
            <span class="btn__text"><?php echo $name_but;?></span>
        </button>
        <span class="acf-spinner"></span>
    </div>
</form>
