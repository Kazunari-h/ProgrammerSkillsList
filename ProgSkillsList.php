<?php
/*
Plugin Name: ProgrammerSkillsList
Plugin URI: kazunari.hal2016.com
Description: Programmer's Skills List
Version: 1.0
Author: Kazunari Hirosawa
Author URI: kazunari.hal2016.com
License:GPL2
*/

/*  Copyright 2015 Kazunari Hirosawa (email : kazunari@hal2016.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//ここからプラグイン

add_action('init', 'my_font_init');

function my_font_init(){
    $labels = array(
        'name' => _x('スキルリスト', 'post type general name'),
        'singular_name' => _x('スキルリスト', 'post type singular name'),
        'add_new' => _x('新しくスキルを書く', 'wpfont'),
        'add_new_item' => __('スキルを書く'),
        'edit_item' => __('スキルを編集'),
        'new_item' => __('新しいスキル'),
        'view_item' => __('スキルを見てみる'),
        'search_items' => __('スキルを探す'),
        'not_found' =>  __('スキルはありません'),
        'not_found_in_trash' => __('ゴミ箱にスキルはありません'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
        'supports' => array('title','editor','thumbnail','custom-fields','excerpt','revisions','page-attributes','comments'),
        'has_archive' => true
    );
    register_post_type('wpfont',$args);
    //カテゴリータイプ
    $args = array(
        'label' => 'スキルカテゴリー',
        'public' => true,
        'show_ui' => true,
        'hierarchical' => true
    );
    register_taxonomy('wpfont_category','wpfont',$args);

    //タグタイプ
    $args = array(
        'label' => 'スキルタグ',
        'public' => true,
        'show_ui' => true,
        'hierarchical' => false
    );
    register_taxonomy('wpfonttag','wpfont',$args);
}

/* post_id.htmlにRewrite */
function myposttype_rewrite() {
    global $wp_rewrite;
    $queryarg = 'post_type=wpskill&p=';
    $wp_rewrite->add_rewrite_tag('%wpskill_id%', '([^/]+)',$queryarg);
    $wp_rewrite->add_permastruct('wpskill', '/wpskill/entry-%wpskill_id%/', false);
}

add_action('init', 'myposttype_rewrite');

function myposttype_permalink($post_link, $id = 0, $leavename) {
    global $wp_rewrite;
    $post = &get_post($id);
    if ( is_wp_error( $post ) )
        return $post;
    $newlink = $wp_rewrite->get_extra_permastruct($post->post_type);
    $newlink = str_replace('%'.$post->post_type.'_id%', $post->ID, $newlink);
    $newlink = home_url(user_trailingslashit($newlink));
    return $newlink;
}
add_filter('post_type_link', 'myposttype_permalink', 1, 3);

?>
