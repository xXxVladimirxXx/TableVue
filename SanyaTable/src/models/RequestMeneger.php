<?php
namespace Ignet\Kabinet\Plugin;

/**
 * Класс, достает посты из БД, создает их, обновляет и удаляет
 *
 * Class RequestMeneger
 */
class RequestMeneger {


    public function get_all_posts($current_user) {

        $request_list = [];

        $query_args = [
            'post_type'      => 'inquiries',
            'author'         => $current_user,
            'posts_per_page' => -1,
        ];

        $query = new \WP_Query($query_args);

        foreach ($query->posts as $post) {

            $value = get_post_meta($post->ID);

            //Мета поля заказа
            $client   = get_field( 'client', $post->ID );
            $status   = get_field( 'status', $post->ID );
            $link     = get_permalink($post->ID);
            $price    = get_field('price', $post->ID);
            $category = get_the_terms($post->ID, 'type_inquiries');

            $request_list[] = [
                'id'                => (int)$post->ID,
                'date'              => get_the_date('', $post->ID),
                'client'            => $client,
                'type'              => $category[0]->name,
                'title'             => $post->post_title,
                'price'             => $price,
                'status'            => $status,
                'name_company'      => $value['name_company'][0],
                'count_participant' => $value['count_participant'][0],
                'activity_of_field' => $value['activity_of_field'][0],
                'site_for_company'  => $value['site_for_company'][0],
                'link'              => $link,
            ];

        }

        return $request_list;
    }

	public function the_post($post_id) {

        $id = $post_id;

        $request_list = [];

        $query_args = [
            'post_type'      => 'inquiries',
            'p'              => $id->postId
        ];

        $query = new \WP_Query($query_args);

        wplog($query->posts, 0,'kabinet');
        foreach($query->posts as $post) {

            $value = get_post_meta($post->ID);

            //Мета поля заказа
            $client_id = get_post_meta($post->ID, 'client');
            $client    = get_user_by('id', $client_id[0]);
            $status    = get_field( 'status', $post->ID );
            $link      = get_permalink($post->ID);
            $price     = get_field('price', $post->ID);
            $category  = get_the_terms($post->ID, 'type_inquiries');
            $content   =  $post->post_content;

            $request_list[] = [
                'id'                => (int)$post->ID,
                'date'              => get_the_date('', $post->ID),
                'client'            => $client->data,
                'type'              => $category[0],
                'title'             => $post->post_title,
                'price'             => $price,
                'status'            => $status,
                'name_company'      => $value['name_company'][0],
                'count_participant' => $value['count_participant'][0],
                'activity_of_field' => $value['activity_of_field'][0],
                'site_for_company'  => $value['site_for_company'][0],
                'link'              => $link,
                'author_id'         => $post->post_author,
                'content'           => $content,
            ];
        }

        return $request_list;
    }

    public function create_post() {

    }
}