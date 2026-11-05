# TN Post Families

TN Post Families adds a **Post family** navigation box to the WordPress editor for hierarchical post types. It shows editable parents, children and siblings without changing post data.

- Version: 1.1.1
- Author: Techn
- Branding mode: Extension Branded (native WordPress editor UI)
- Requirements: WordPress 7.0+, PHP 7.4+

## Install

Download `tn-post-families.zip` from the latest GitHub release and upload it through **Plugins → Add New → Upload Plugin**. Activate the plugin and open a hierarchical post in the editor.

This is a new plugin identity. To replace the supplied WordPost Family 1.11 ZIP, activate TN Post Families, then deactivate and delete WordPost Family. No data migration is needed because the plugin stores no data.

Techn Update Controller is optional for feature operation. Its [official releases](https://github.com/cchatterton/tn-update-controller/releases) provide the manual installation route when this plugin is inactive. The controller's explicit check discovers GitHub releases; ordinary editor views make no updater requests.

## Development

`tn-post-families/` contains the distributable plugin. `scripts/build-plugin-zip.sh` creates `dist/tn-post-families.zip` and the matching root ZIP. The controller helper is copied from `tn-update-controller/integration/controller-client.php` at the published v1 contract.

The editor links use WordPress capabilities and edit URLs, and include published, scheduled, draft, pending and private posts. Trashed and automatic drafts are excluded. There are no stored settings or uninstall tasks.

When a WordPress Page slug matches a registered post type name, the box adds that type's editable top-level items to the existing Children list. The merged list follows `menu_order`, then title. Descendants of those post type items are not listed there.

For a top-level item of that post type, the matching Page appears in its Parent list. Nested items continue to show their actual post parent.

The project follows the [Codex standards](https://github.com/cchatterton/codex-standards): general development, WordPress plugin, GitHub update, and branding/UX standards.
