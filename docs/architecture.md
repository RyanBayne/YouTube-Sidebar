# YouTube Sidebar Modernization Notes

## Legacy Inventory

- `youtubesidebar.php` defines constants, loads the legacy controller, starts textdomain loading, and now starts the modern namespaced layer.
- `classes/class-youtubesidebar.php` owns legacy admin menus, request processing, dashboard widgets, old sidebar widgets, public/admin assets, cron-style event checks, and the old `video_object()` render helper.
- Legacy sidebar widgets read one or more `youtubesidebar` post meta values and previously rendered Flash/object embeds.
- The old bulk assignment UI in `postlist_addvideotosidebar()` writes video IDs against posts with field names like `yts_videoid_{post_id}`.
- Optional post-type files under `posttypes/` are disabled by default in `arrays/settings_array.php`.

## Modern Service Boundaries

- `WPSeed\YouTubeSidebar\Plugin`: bootstrap and WordPress hook registration.
- `Settings`: API key, privacy embed default, default layout, and metadata cache TTL.
- `Assignments`: structured post-level source storage in `_youtubesidebar_source` with compatibility reads/writes for legacy `youtubesidebar` meta.
- `VideoSource`: source model and URL/ID parsing for videos, playlists, channels, live streams, and comma-separated video collections.
- `YouTubeApi`: YouTube Data API metadata fetching with cached, graceful fallback when no key or request failure exists.
- `Cache`: transient-backed cache abstraction.
- `Renderer`: shared responsive renderer for widgets, shortcodes, blocks, and template usage.
- `LegacyWidget`: modern WP_Widget implementation while old registered widget callbacks remain available.

## Render Surfaces

- Shortcodes: `[youtube_sidebar]` and `[youtubesidebar]`.
- Block: `youtubesidebar/display`, server-rendered for frontend consistency.
- Template tag: `youtubesidebar_render( $args = array(), $echo = true )`.
- Widgets: existing legacy widgets remain registered; the new `YouTube Display` widget renders assigned content through the shared renderer.

## Data Model

Structured assignments use:

```php
array(
    'type'    => 'video|playlist|channel|live',
    'id'      => 'primary-source-id',
    'ids'     => array( 'video-id', 'video-id-2' ),
    'input'   => 'original-url-or-id',
    'layout'  => 'sidebar|featured|gallery|carousel|accordion|playlist-strip|live-now',
    'title'   => '',
    'options' => array(
        'privacy'  => true,
        'autoplay' => false,
        'mute'     => false,
        'captions' => false,
        'ratio'    => '16:9',
    ),
)
```

Legacy `youtubesidebar` meta remains readable. Saving through the new assignment box also rewrites the legacy meta values so old widget workflows continue to see the assigned videos.
