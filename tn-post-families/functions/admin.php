<?php
/** Editor navigation for hierarchical post types. */

if (!defined('ABSPATH')) {
    exit;
}

function tnpf_register_meta_box($post_type) {
    if (!is_post_type_hierarchical($post_type)) {
        return;
    }

    add_meta_box(
        'tnpf_family',
        __('Post family', 'tn-post-families'),
        'tnpf_render_meta_box',
        $post_type,
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'tnpf_register_meta_box');

function tnpf_editable_relatives($parent_id, $post_type, $exclude_id = 0) {
    $posts = get_posts(array(
        'post_parent'    => $parent_id,
        'post_type'      => $post_type,
        'post_status'    => array('publish', 'future', 'draft', 'pending', 'private'),
        'posts_per_page' => -1,
        'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
    ));

    return tnpf_filter_editable_posts($posts, $exclude_id);
}

function tnpf_filter_editable_posts($posts, $exclude_id = 0) {
    return array_values(array_filter($posts, static function ($relative) use ($exclude_id) {
        return (int) $relative->ID !== (int) $exclude_id
            && current_user_can('edit_post', $relative->ID)
            && get_edit_post_link($relative->ID, 'raw');
    }));
}

function tnpf_render_list($label, $posts) {
    if (!$posts) {
        return;
    }

    echo '<div class="tnpf-group"><h3>' . esc_html($label) . '</h3><ul>';
    foreach ($posts as $relative) {
        $title = get_the_title($relative);
        if ('' === trim($title)) {
            $title = __('(no title)', 'tn-post-families');
        }
        echo '<li><a href="' . esc_url(get_edit_post_link($relative->ID, 'raw')) . '">'
            . esc_html($title) . '</a></li>';
    }
    echo '</ul></div>';
}

function tnpf_render_meta_box($post) {
    if (!$post || !current_user_can('edit_post', $post->ID)) {
        return;
    }

    $parent = array();
    if ($post->post_parent) {
        $parent_post = get_post($post->post_parent);
        if ($parent_post && current_user_can('edit_post', $parent_post->ID) && get_edit_post_link($parent_post->ID, 'raw')) {
            $parent[] = $parent_post;
        }
    } elseif ('page' !== $post->post_type) {
        $parent = tnpf_filter_editable_posts(get_posts(array(
            'post_type'      => 'page',
            'name'           => $post->post_type,
            'post_status'    => array('publish', 'future', 'draft', 'pending', 'private'),
            'posts_per_page' => -1,
            'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
            'no_found_rows'  => true,
        )), $post->ID);
    }

    $children = $post->ID ? tnpf_editable_relatives($post->ID, $post->post_type) : array();
    if ('page' === $post->post_type && $post->post_name && post_type_exists($post->post_name)) {
        $children = array_merge($children, tnpf_editable_relatives(0, $post->post_name));
        usort($children, static function ($left, $right) {
            return ($left->menu_order <=> $right->menu_order)
                ?: strcasecmp(get_the_title($left), get_the_title($right));
        });
    }
    $siblings = ($post->post_parent || 'page' !== $post->post_type)
        ? tnpf_editable_relatives($post->post_parent, $post->post_type, $post->ID)
        : array();

    if (!$parent && !$children && !$siblings) {
        echo '<p>' . esc_html__('No parent, children or siblings to show.', 'tn-post-families') . '</p>';
        return;
    }

    tnpf_render_list(__('Parent', 'tn-post-families'), $parent);
    tnpf_render_list(__('Children', 'tn-post-families'), $children);
    tnpf_render_list(__('Siblings', 'tn-post-families'), $siblings);
}

function tnpf_enqueue_admin_styles($hook) {
    if (!in_array($hook, array('post.php', 'post-new.php'), true)) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || !is_post_type_hierarchical($screen->post_type)) {
        return;
    }

    wp_enqueue_style('tnpf-admin', TNPF_PLUGIN_URL . 'styles/admin.css', array(), TNPF_VERSION);
}
add_action('admin_enqueue_scripts', 'tnpf_enqueue_admin_styles');
