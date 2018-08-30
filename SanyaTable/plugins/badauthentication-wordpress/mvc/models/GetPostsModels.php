<?php

namespace wp\authentication;

class GetPostsModels {

    public function get_posts_models() {

        $request_array = [];

        $argc = array( 'post_type' => 'post',
                        'posts_per_page' => -1,
                        'post_status' => 'publish'
                     );

        $query = new \WP_Query( $argc );


        foreach ($query->posts as $post) {

            $request_array[] = [
                'id'     => $post->ID,
                'date'  => $post->post_date,
                'title'  => $post->post_title,
                'content'  => $post->post_content,
                'uriUpdate'  => 'http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=get_update' . $post->ID,
                'uriDelete'  => 'http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=get_delete' . $post->ID
                //'<a href="fatum/delete/' . $post->ID . '"><i class="fa fa-times"></i></a>'
            ];
        }

        return $request_array;
    }
}