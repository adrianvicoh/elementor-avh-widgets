# Elementor Custom Widgets

WordPress plugin with extended widgets and global features for Elementor.

## Requirements

- WordPress 6.8+
- Elementor (tested with 4.x)
- Elementor Pro is listed as a requirement for some widget features
- PHP 8.0+

## Features

The plugin is split in two kinds of features, both manageable from the plugin dashboard:

### Widgets

New standalone widgets, registered in the **Custom Elementor Widgets** category:

- **Coverflow Slider** — image carousel with a 3D coverflow effect, built on Swiper 11
  (loaded from CDN with SRI + `crossorigin` attributes).
- **Toggle Content** — button that shows/hides a WYSIWYG content block, with styling
  controls for the button and the content alignment.

### Global configurations

Modifications that extend **existing** Elementor elements instead of adding new widgets.
Each configuration is opt-in from the dashboard and only registers its controls and
loads its assets when enabled:

- **Animated Border Beam** — adds an animated, light-beam style border to any
  Elementor element that has a **Border** section:

  - Widgets: **Advanced → Border** (`elementor/element/common/_section_border`)
  - Containers, Sections and Columns: **Style → Border**
    (`elementor/element/{container,section,column}/section_border`)

  Available options:

  - **Effect type**: Compact rotation (`sm`), Full border rotation (`md`),
    Bottom traveling line (`line`), Inner pulse (`pulse-inner`), Outside pulse (`pulse-outside`)
  - **Color palette**: Colorful, Monochrome, Ocean, Sunset
  - **Background theme**: Dark, Light, System preference (auto)
  - **Duration** (seconds), **Strength**, **Brightness**, **Saturation**, **Hue range** (degrees)
  - **Static colors**: disables hue shifting while keeping the border motion
  - Respects `prefers-reduced-motion` and pauses when the element is out of view
    (IntersectionObserver), so the animation loop only runs while visible.

## Dashboard

The plugin adds an **AVH Widgets** admin page (Settings via the WordPress Settings API)
where every widget and configuration can be enabled or disabled independently:

- `widgets/` are listed in the **Widgets** section (opt-in, default off).
- `configs/` are listed in the **Elementor configurations** section (opt-in, default off).

Disabled features do not register controls, widgets, or frontend assets.

## Project structure

```
elementor-avh-widgets/
├── elementor-avh-widgets.php      # Plugin entry point: loads dashboard, configs, widgets
├── widgets/                       # Standalone Elementor widgets (Coverflow Slider, Toggle Content)
├── assets/                        # Frontend assets for the widgets (css/js)
├── configs/                       # Global Elementor modifications, not widgets
│   ├── border-beam.php            # Border Beam: registers controls + assets
│   └── assets/                    # Config assets (css/js)
├── dashboard/                     # Admin dashboard
│   ├── dashboard-configs.php      # Dashboard page + settings registration
│   ├── dash-configs/              # Per-feature settings (widgets, configs)
│   └── assets/                    # Dashboard-only admin styles
└── libs/                          # External read-only libraries (reference only)
```

Notes:

- `assets/` is reserved for widget assets; configuration assets live under `configs/assets/`.
- `libs/` contains third-party reference code (e.g. `border-beam-main`) and is **read-only**,
  it is not part of the built plugin.
- Config assets are enqueued on the frontend and the Elementor preview iframe only
  (`wp_enqueue_scripts`, `elementor/preview/*`) — never on the editor panel.

## Usage

1. Install and activate the plugin (requires Elementor).
2. Go to **AVH Widgets** in the admin menu and enable the widgets/configurations you need.
3. In the Elementor editor:
   - **Coverflow Slider** and **Toggle Content** appear under *Custom Elementor Widgets*.
   - **Animated Border Beam** appears inside the **Border** section of any supported element
     (Advanced → Border on widgets, Style → Border on containers/sections/columns).
4. Toggle *Enable animated border* on and configure the effect.
