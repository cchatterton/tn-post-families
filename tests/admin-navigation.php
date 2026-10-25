<?php
/* Lightweight WordPress API fixture for editor navigation behavior. */
define('ABSPATH', __DIR__);
$GLOBALS['posts'] = array();
$GLOBALS['editable'] = array();
$GLOBALS['hooks'] = array();

function add_action($hook, $callback) { $GLOBALS['hooks'][$hook] = $callback; }
function is_post_type_hierarchical($type) { return 'page' === $type; }
function add_meta_box(...$args) { $GLOBALS['box'] = $args; }
function __($text, $domain) { return $text; }
function esc_html__($text, $domain) { return htmlspecialchars($text, ENT_QUOTES); }
function esc_html($text) { return htmlspecialchars($text, ENT_QUOTES); }
function esc_url($url) { return htmlspecialchars($url, ENT_QUOTES); }
function current_user_can($cap, $id) { return !empty($GLOBALS['editable'][$id]); }
function get_edit_post_link($id, $context) { return '/wp-admin/post.php?post=' . $id . '&action=edit'; }
function get_the_title($post) { return $post->post_title; }
function get_post($id) { return $GLOBALS['posts'][$id] ?? null; }
function get_posts($args) {
    $posts = array_values(array_filter($GLOBALS['posts'], static function ($post) use ($args) {
        return $post->post_parent === $args['post_parent']
            && $post->post_type === $args['post_type']
            && in_array($post->post_status, $args['post_status'], true);
    }));
    check($args['orderby'] === array('menu_order' => 'ASC', 'title' => 'ASC'), 'Relationship query must request menu order');
    usort($posts, static function ($left, $right) {
        return ($left->menu_order <=> $right->menu_order)
            ?: strcmp($left->post_title, $right->post_title);
    });
    return $posts;
}
function get_current_screen() { return (object) array('post_type' => 'page'); }
function wp_enqueue_style(...$args) { $GLOBALS['style'] = $args; }

require dirname(__DIR__) . '/tn-post-families/functions/admin.php';

function record($id, $parent, $title, $status = 'publish', $menu_order = 0) {
    return (object) array('ID' => $id, 'post_parent' => $parent, 'post_type' => 'page', 'post_title' => $title, 'post_status' => $status, 'menu_order' => $menu_order);
}
function check($condition, $message) {
    if (!$condition) { throw new RuntimeException($message); }
}

$GLOBALS['posts'] = array(
    1 => record(1, 0, 'Parent'),
    2 => record(2, 1, 'Current'),
    3 => record(3, 1, 'Sibling <private>', 'private', 5),
    4 => record(4, 2, 'Child <draft>', 'draft', 10),
    5 => record(5, 2, 'Hidden child'),
    6 => record(6, 1, 'Earlier sibling', 'publish', 1),
    7 => record(7, 2, 'Earlier child', 'publish', 2),
);
$GLOBALS['editable'] = array(1 => true, 2 => true, 3 => true, 4 => true, 6 => true, 7 => true);

tnpf_register_meta_box('post');
check(!isset($GLOBALS['box']), 'Non-hierarchical post type received a box');
tnpf_register_meta_box('page');
check($GLOBALS['box'][0] === 'tnpf_family', 'Hierarchical box missing');

ob_start();
tnpf_render_meta_box($GLOBALS['posts'][2]);
$html = ob_get_clean();
check(strpos($html, 'Parent') !== false && strpos($html, 'Siblings') !== false, 'Relationships missing');
check(strpos($html, 'Child &lt;draft&gt;') !== false, 'Draft title not escaped');
check(strpos($html, 'Sibling &lt;private&gt;') !== false, 'Private title not escaped');
check(strpos($html, 'Hidden child') === false, 'Uneditable child exposed');
check(substr_count($html, 'post=2') === 0, 'Current post shown as sibling');
check(strpos($html, 'post=6') < strpos($html, 'post=3'), 'Siblings ignore menu order');
check(strpos($html, 'post=7') < strpos($html, 'post=4'), 'Children ignore menu order');

unset($GLOBALS['editable'][1], $GLOBALS['editable'][3], $GLOBALS['editable'][4], $GLOBALS['editable'][6], $GLOBALS['editable'][7]);
ob_start();
tnpf_render_meta_box($GLOBALS['posts'][2]);
$html = ob_get_clean();
check(strpos($html, 'No parent, children or siblings to show.') !== false, 'Empty state missing');

echo "Editor navigation checks passed.\n";
