# YouTube Sidebar Implementation Baseline

## Purpose

This document captures the YouTube Sidebar architecture and modernization approach. Unlike CSV2POST, YouTube Sidebar already has modern PHP namespacing and a service-oriented Plugin class. The baseline outlines how to extend this existing modern infrastructure.

## Current Modern Structure

### Plugin Entry Point

Entry file: `youtubesidebar.php`

Current responsibilities:

- direct-access guard,
- early request bail for login, XML-RPC, and cron,
- modern bootstrap loader (`bootstrap.php`),
- legacy class loading for backward compatibility,
- textdomain registration.

### Modern Bootstrap Layer

New file: `bootstrap.php`

Responsibilities:

- define plugin constants,
- load modern service layer,
- load legacy runtime for compatibility,
- register plugin hooks.

### Core Modern Services

Existing in `src/`:

- `Plugin.php` — main plugin class with block, shortcode, widget, and settings page registration.
- `VideoSource.php` — video source utilities.
- `Settings.php` — plugin settings management.
- `YouTubeApi.php` — YouTube API integration.
- `Cache.php` — caching layer for API responses.
- `Renderer.php` — video rendering and output.
- `Assignments.php` — video-to-post assignment logic.
- `LegacyWidget.php` — backward compatibility widget.

### New Modern Services

Added:

- `ServiceContainer.php` — central service registry.
- `Services/VideoSourceRepository.php` — structured video source access.
- `Services/VideoManagement.php` — video operations (add, remove, update).
- `Services/ConfigurationDiagnostics.php` — plugin health and configuration inspection.
- `Admin/SettingsPage.php` — modern admin settings interface.

## Data Ownership & Structures

### Post Metadata

- `_youtubesidebar_videos` — array of video assignments per post.

Each video object contains:

- `id` — YouTube video ID,
- `url` — YouTube video URL,
- `added` — timestamp,
- `layout` — optional display layout,
- `autoplay` — optional autoplay setting,
- `mute` — optional mute setting.

### Plugin Options

- `youtubesidebar_api_key` — YouTube Data API key.
- `youtubesidebar_use_blocks` — enable/disable block editor support.
- `youtubesidebar_layout` — default layout option.

## Current Rendering Surfaces

The modern Plugin class already supports:

1. **Blocks** — WordPress block editor block `youtubesidebar/display`.
2. **Shortcodes** — `[youtube_sidebar]`, `[youtubesidebar]`.
3. **Widgets** — legacy widget with modern `LegacyWidget` wrapper.
4. **Meta boxes** — per-post assignment interface.
5. **Settings page** — admin configuration screen.

## Modernization Constraints

- Preserve backward compatibility with existing shortcode and widget usage.
- Keep per-post video assignment concept as primary model.
- Allow multiple videos per post (current Plugin class supports arrays).
- Maintain existing settings and options structure.
- Support both legacy and modern rendering simultaneously.

## Architecture Decisions

### Why ServiceContainer?

YouTube Sidebar's Plugin class already works like a service container, but it's not explicitly structured that way. The new `ServiceContainer` class makes this pattern explicit and allows:

- lazy-loading of heavy services (YouTube API client),
- dependency injection for testing,
- separation of concerns for admin vs. public rendering.

### Why Diagnostics?

ConfigurationDiagnostics provides visibility into:

- whether API is configured,
- how many posts have videos,
- feature enablement status,
- configuration validation.

This is valuable for troubleshooting and for modern admin UI decisions.

### Why VideoSourceRepository?

The existing `Assignments` class handles some of this, but a repository layer makes it easier to:

- query posts with videos,
- extract video IDs from various URL formats,
- bulk operations on multiple posts,
- future database schema changes (e.g., custom video table).

## Next Modernization Steps

1. **REST API Layer** — expose diagnostics and video management through WordPress REST for JavaScript interfaces.
2. **Video Gallery/Carousel** — create a shortcode/block for displaying multiple videos from one post.
3. **Video Feed** — new shortcode for querying and displaying videos across multiple posts.
4. **Advanced Layouts** — new rendering patterns (accordion, grid, masonry).
5. **Live Stream Support** — extend to handle YouTube live streams and YouTube channel embeds.
6. **Caching Strategy** — implement better cache invalidation for bulk operations.
7. **Admin Dashboard** — use diagnostics service to create a dashboard widget showing video usage statistics.

## Current Load Order

1. `youtubesidebar.php` entry file,
2. Bootstrap checks and early exit logic,
3. Load `bootstrap.php`,
4. PluginBootstrap::boot() executes:
   - Define constants,
   - Load modern services (ServiceContainer, Services, Admin),
   - Load legacy class,
   - Register hooks,
   - Initialize legacy plugin.

## Validation Status

- Bootstrap: ✓ syntax clean, ✓ PHP 8.4 compatible
- ServiceContainer: ✓ syntax clean
- VideoSourceRepository: ✓ syntax clean
- VideoManagement: ✓ syntax clean
- ConfigurationDiagnostics: ✓ syntax clean
- SettingsPage: ✓ syntax clean

All new services pass PHP linting and editor diagnostics.
