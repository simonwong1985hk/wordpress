<?php

/**
 * Plugin Name:     Dummy
 * Plugin URI:      https://github.com/simonwong1985hk/wordpress
 * Description:     Create [user,post,category,tag,comment] programmatically
 * Author:          Simon Wong
 * Author URI:      https://simonwong1985hk.github.io
 * Text Domain:     dummy
 * Domain Path:     /languages
 * Version:         2022.5.22
 *
 * @package         Dummy
 */

/**
 * register activation hook
 */
register_activation_hook(__FILE__, 'create');

function create()
{
    // get all role names except administrator
    $roles = wp_roles()->role_names;
    $roles = array_keys($roles);
    $roles = array_diff($roles, ['administrator']);
    $roles = array_values($roles);

    foreach ($roles as $role) {
        // create user
        wp_insert_user([
            'user_login' => $role,
            'user_pass' => 'password',
            'user_email' => $role . '@example.com',
            'role' => $role,
        ]);

        // create category
        wp_insert_term(ucwords($role), 'category');

        // create tag
        wp_insert_term(ucwords($role), 'post_tag');

        // create post
        wp_insert_post([
            'post_title' => ucwords($role) . '\'s Post',
            'post_name' => $role . '-post',
            'post_content' => 'It\'s a post created by ' . ucwords($role) . '.',
            'post_status' => 'publish',
            'post_author' => get_user_by('slug', $role)->ID,
            'post_category' => [get_term_by('slug', $role, 'category')->term_id],
            'tags_input' => [get_term_by('slug', $role, 'post_tag')->term_id],
        ]);

        // create comment
        wp_insert_comment([
            'comment_post_ID' => get_page_by_path($role . '-post', OBJECT, 'post')->ID,
            'comment_content' => 'It\'s a comment created by ' . ucwords($role) . '.',
            'comment_author' => ucwords($role),
        ]);
    }
}

/**
 * register deactivation hook
 */
register_deactivation_hook(__FILE__, 'delete');

function delete()
{
    // get all role names except administrator
    $roles = wp_roles()->role_names;
    $roles = array_keys($roles);
    $roles = array_diff($roles, ['administrator']);
    $roles = array_values($roles);

    foreach ($roles as $role) {
        // delete post
        wp_delete_post(get_page_by_path($role . '-post', OBJECT, 'post')->ID, true);

        // delete category
        wp_delete_term(get_term_by('slug', $role, 'category')->term_id, 'category');

        // delete tag
        wp_delete_term(get_term_by('slug', $role, 'post_tag')->term_id, 'post_tag');

        // delete user
        wp_delete_user(get_user_by('slug', $role)->ID);
    }
}
