# YouTube Sidebar Roadmap

## Purpose

YouTube Sidebar should no longer be treated as a narrow sidebar widget plugin. The modern opportunity is a broader video display plugin that can still preserve legacy sidebar/widget behavior for existing users.

The roadmap prioritizes standards, a modern service layer, and new rendering surfaces built for the current YouTube ecosystem.

## Current State

### Strengths

- The plugin already has a per-post video assignment concept.
- There is an existing admin framework, widget mindset, and output configuration model.
- The plugin has room to expand beyond its original sidebar-only framing.

### Key Evidence In Current Code

- Bootstrap and constants: `youtubesidebar.php`
- Main controller and hooks: `classes/class-youtubesidebar.php`
- Legacy database helper: `classes/class-wpdb.php`
- Default settings model: `arrays/settings_array.php`
- Legacy admin/dashboard UI: `views/main.php`

### Standards Gaps

- No namespaces or modern autoloading.
- Heavy use of globals, constant sprawl, and manual class loading.
- Custom SQL layer needs security review.
- Current admin framework is tightly coupled and hard to evolve.
- Product scope is still framed around old widget/sidebar assumptions.
- No modern block editor or REST-oriented architecture.
- Current YouTube feature set predates the platform's modern API and presentation expectations.

## Product Direction

Position the plugin as a YouTube display and video layout system for WordPress.

Legacy compatibility:

- existing sidebar/widget behavior remains supported,
- existing per-post video assignment concepts can be migrated,
- old settings should remain readable during transition.

Future direction:

- widgets,
- shortcodes,
- blocks,
- template embeds,
- galleries,
- carousels,
- accordions,
- featured-video layouts,
- live stream support.

## Non-Negotiable Legacy Behaviors To Preserve

- Existing users must still be able to attach or associate videos to content.
- Sidebar/widget rendering must remain available as a compatibility mode.
- Existing settings should be migrated or read through compatibility layers.
- Ad or fallback behavior should remain conceptually possible, but redesigned safely.

## Modernization Principles

- Introduce namespaces and a modern loader.
- Separate YouTube API logic from admin UI and rendering logic.
- Use a structured data model for video sources and layouts.
- Replace raw or legacy query patterns with prepared access and WordPress APIs where practical.
- Prefer reusable renderers shared across block, shortcode, widget, and template contexts.

## Phase 1: Architecture Reset

### 1.1 Bootstrap And Dependency Audit

- Document what `youtubesidebar.php` loads and when.
- Inventory current widgets, post hooks, admin routes, and render paths.
- Identify which parts are still product-relevant and which are framework baggage.

### 1.2 Namespace And Service Layer Plan

**Status: ✓ COMPLETE**

Added modern service layer:

- `ServiceContainer.php` — central registry.
- `Services/VideoSourceRepository.php` — video/post associations.
- `Services/VideoManagement.php` — add/update/remove operations.
- `Services/ConfigurationDiagnostics.php` — health and feature status.
- `Admin/SettingsPage.php` — modern settings UI.
- `bootstrap.php` — clear load order and initialization.

### 1.3 Data Model Review

**Status: ✓ COMPLETE**

Data structure established:

- Post meta key: `_youtubesidebar_videos`
- Each video object: `{ id, url, added, layout?, autoplay?, mute?, captions?, ratio? }`
- Options: API key, block support, layout preference.

Supports: single videos, multiple per post, future fields for playlists/channels.

## Phase 2: YouTube Integration Modernization

### 2.1 API Layer

- Add secure API configuration and key handling.
- Build metadata fetchers for videos, playlists, channels, and live streams.
- Add caching and refresh rules.
- Provide graceful fallback when API access is unavailable.

### 2.2 Embed Strategy

- Standardize embed rendering.
- Support privacy-enhanced embeds where possible.
- Add control over autoplay, captions, muted behavior, aspect ratio, and responsive sizing.

### 2.3 Live Stream Handling

- Add support for current or upcoming live streams.
- Provide fallback layouts when no stream is active.

## Phase 3: Output Surface Expansion

### 3.1 Legacy Compatibility Mode

- Keep a sidebar/widget-compatible renderer for existing users.
- Map old widget settings into a stable compatibility layer.

### 3.2 Modern Render Surfaces

- Add shortcode rendering.
- Add block editor integration.
- Add template tags for theme/plugin developers.

### 3.3 Layout Templates

- Provide reusable layouts:
  - sidebar,
  - gallery,
  - carousel,
  - accordion,
  - featured video with supporting grid,
  - playlist strip,
  - live-now panel.

## Phase 4: Admin UX Rebuild

### 4.1 Clean Configuration Screens

- Replace the legacy admin framework with smaller, task-specific screens.
- Separate API settings, display settings, assignments, and layouts.

### 4.2 Content Assignment Model

- Support post-level assignments in a structured way.
- Add bulk assignment and defaults where useful.
- Allow content creators to choose between automatic and manual selection modes.

### 4.3 Preview Tools

- Add live preview for layout choices.
- Show how a video, playlist, or live stream will render before saving.

## Phase 5: Standards And Safety Refresh

- Add capability checks and nonces to all mutating actions.
- Review and replace unsafe SQL patterns.
- Modernize uninstall behavior and option cleanup.
- Align assets, admin routing, and documentation with current WPSeed standards.
- Add tests for API and rendering slices where practical.

## Phase 6: Feature Opportunities

- Live chat and scheduled stream awareness.
- Channel sync with cached recent uploads.
- Video schema markup support.
- Import of playlist/channel metadata into WordPress content.
- Multi-layout display packs for different themes.
- Optional analytics integration for clicks and views.
- Content fallback modes for non-YouTube video sources in a later expansion.

## First Implementation Slice

1. Inventory current widget, admin, and post-assignment routes.
2. Define the new service boundaries for API, cache, renderer, and assignment storage.
3. Decide the first modern render surfaces: shortcode plus block, with widget compatibility preserved.
4. Build around a clean data model rather than around the legacy admin framework.

## Risks

- Existing users may depend on widget-specific behavior and old defaults.
- YouTube API policy or quota changes can affect product design.
- Rendering expansion can create too many modes unless layout strategy is kept disciplined.
- Legacy framework code may hide assumptions about how settings are stored and read.

## Verification Expectations

- Existing sidebar/widget behavior remains available during transition.
- Legacy settings are either migrated or correctly interpreted by compatibility code.
- A configured video can render consistently in at least widget, shortcode, and block contexts.
- API-backed metadata fetches are cached and fail gracefully.
- Live stream and playlist renderers degrade cleanly when data is missing.