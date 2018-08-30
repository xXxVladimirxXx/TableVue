<?php
namespace Ignet\Kabinet\Plugin;

class RegisterAjaxController {

    public $ajax_manager;

    public function __construct(RequestMeneger $requestMeneger){

        $this->ajax_manager = $requestMeneger;

        add_action('wp_ajax_get_all_posts', [$this, 'get_all_posts']);
        add_action('wp_ajax_get_the_post', [$this, 'get_the_post']);
        add_action('wp_ajax_create_post', [$this, 'create_post']);
        add_action('wp_ajax_edit_request', [$this, 'edit_request']);
        add_action('wp_ajax_delete_request', [$this, 'delete_request']);
    }

    public function get_all_posts() {
        // http://kabinet.ignet.org/wp-admin/admin-ajax.php?action=get_all_posts

        $current_user = get_current_user_id();
        $result = $this->ajax_manager->get_all_posts($current_user);

        wp_send_json($result);
    }

    public function get_the_post() {
        // http://kabinet.ignet.org/wp-admin/admin-ajax.php?action=get_the_post

        $post_id = json_decode(file_get_contents('php://input'));
        $result = $this->ajax_manager->the_post($post_id);

        wp_send_json($result);
    }

    public function create_post() {
        // http://kabinet.ignet.org/wp-admin/admin-ajax.php?action=create_post

        $request = json_decode(file_get_contents('php://input'));

        $args = [
            'post_title'   => $request->form->title,
            'post_content' => $request->form->content,
            'post_type'    => $request->form->postType,
            'post_status'  => 'publish'
        ];

        $post_id = wp_insert_post($args);

        $name_company      = $request->form->name_company;
        $type_rquest       = $request->form->type_request;
        $count_participant = $request->form->count_participant;
        $field_of_activity = $request->form->field_of_activity;
        $site_company      = $request->form->site_company;
        $price             = $request->form->price;
        $selectedKlient    = get_user_by('id', (int)$request->form->selectedKlient);

        wp_set_post_terms($post_id, $type_rquest, 'type_inquiries');
        update_post_meta($post_id, 'name_company', $name_company);
        update_post_meta($post_id, 'count_participant', $count_participant);
        update_post_meta($post_id, 'activity_of_field', $field_of_activity);
        update_post_meta($post_id, 'site_for_company', $site_company);
        update_post_meta($post_id, 'site_for_company', $site_company);
        update_post_meta($post_id, 'price', $price);
        update_field('client', $selectedKlient->data, $post_id);
        update_field('status', 'В обработке', $post_id);

        wp_die();
    }

    public function edit_request() {
        // http://kabinet.ignet.org/wp-admin/admin-ajax.php?action=edit_request
        $dataForUpdate = json_decode(file_get_contents('php://input'));

        $post = $dataForUpdate->formData->Post;
        $post_id = $post->id;

        $args = [
            'ID'           => $post->id,
            'post_title'   => $post->title,
            'post_content' => $post->content,
            'post_status'  => 'publish',
            'post_type'    => $dataForUpdate->formData->post_type
        ];

        wp_insert_post($args);

        $name_company      = $post->name_company;
        $type_rquest       = $post->type->term_id;
        $count_participant = $post->count_participant;
        $field_of_activity = $post->activity_of_field;
        $site_company      = $post->site_for_company;
        $price             = $post->price;
        $selectedKlient    = get_user_by('id', (int)$post->client->ID);

        wp_set_post_terms($post_id, $type_rquest, 'type_inquiries');
        update_post_meta($post_id, 'name_company', $name_company);
        update_post_meta($post_id, 'count_participant', $count_participant);
        update_post_meta($post_id, 'activity_of_field', $field_of_activity);
        update_post_meta($post_id, 'site_for_company', $site_company);
        update_post_meta($post_id, 'site_for_company', $site_company);
        update_post_meta($post_id, 'price', $price);
        update_field('client', $selectedKlient, $post_id);
        update_field('status', 'В обработке', $post_id);

        wp_die();
    }

    public function delete_request() {
        // http://kabinet.ignet.org/wp-admin/admin-ajax.php?action=delete_request

        $request = json_decode(file_get_contents('php://input'));

        $result = wp_delete_post($request->postId);

        wp_send_json($result);
    }
}