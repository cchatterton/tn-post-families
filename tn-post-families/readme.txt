=== TN Post Families ===
Contributors:
Tags: hierarchical, navigation, editor, pages
Requires at least: 7.0
Requires PHP: 7.4
Stable tag: 1.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Navigate parent, child and sibling posts from the editor sidebar for hierarchical post types.

== Description ==

TN Post Families adds a Post family box to the WordPress editor for hierarchical post types. It links to editable parent, child and sibling posts. Posts without an available relationship show a clear empty state.

When editing a Page whose slug matches a registered post type, the box adds that type's editable top-level items to the existing Children list. Items without edit access are omitted.

When editing one of those top-level post type items, the matching Page appears in its Parent list. Nested items keep their actual post parent.

Other editable top-level items of the same post type appear in Siblings, ordered by menu order.

The plugin does not change post content or relationships. Feature operation does not require Techn Update Controller.

== Installation ==

1. Upload `tn-post-families.zip` using Plugins > Add New > Upload Plugin.
2. Activate TN Post Families.
3. Open a page or another hierarchical post type in the editor and use the Post family box.

If replacing WordPost Family, deactivate and remove the old plugin after activating TN Post Families. The new folder and main file have different names, so WordPress treats them as separate plugins.

For GitHub updates, use the plugin row action to install or activate Techn Update Controller. If TN Post Families is inactive, install the controller directly from its official GitHub release ZIP. Updates are checked only after an explicit controller action. Controller installation downloads the official release from GitHub; no post content or site inventory is sent. GitHub receives normal request metadata.

== External services ==

Techn Update Controller contacts GitHub only after an authorised install or manual update check. It downloads the controller ZIP or the public Techn plugin catalogue and release assets. The feature itself makes no external requests.

GitHub terms: https://docs.github.com/en/site-policy/github-terms/github-terms-of-service
GitHub privacy: https://docs.github.com/en/site-policy/privacy-policies/github-general-privacy-statement

== Changelog ==

= 1.1.2 =
* Show other top-level items of the same custom post type as siblings.

= 1.1.1 =
* Add only matching post type's top-level items to the existing Children list; remove the separate nested section.
* Show a Page whose slug matches the post type in top-level items' Parent list.

= 1.1.0 =
* On Pages whose slug matches a registered post type, show its top-level items and nested children in menu order.

= 1.0.1 =
* Show children and siblings in WordPress menu order, then title for matching positions.

= 1.0.0 =
* First TN Post Families release, based on WordPost Family 1.11.
* Show only editable relatives and use native editor links and accessible headings.
* Add Techn Update Controller client integration.
