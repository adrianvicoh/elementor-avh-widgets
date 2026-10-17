# Changelog

All notable changes to this plugin are documented in this file.

## Unreleased

### Added

- Project contribution guidance in `AGENTS.md`.
- A `STATE.md` handoff document for in-progress work.
- Draggable Boxes, an Elementor widget that supports constrained mouse, touch,
  and keyboard box movement through interact.js 1.10.27. Boxes stay inside
  their canvas when it is resized, and the widget re-initialises cleanly after
  Elementor editor rerenders.
- Numeric Chart, an Elementor widget for bar, line, and doughnut charts using
  Chart.js 4.5.1. Editor rerenders replace the chart instead of leaving
  duplicate Chart.js instances.
- A *Third-party libraries* section in `README.md` and a versioned
  `THIRD-PARTY-NOTICES.md` recording the origin, author, copyright, license,
  and distribution of interact.js, Chart.js, border-beam, Swiper, and the
  DMmotionarts Coverflow tutorial.
- Local, version-pinned browser bundles and MIT license texts for interact.js
  and Chart.js under `assets/vendor/`.

### Fixed

- The Animated Border Beam port now credits the original border-beam library
  by Jakub Antalik: its MIT license ships as
  `configs/assets/border-beam.LICENSE`, and the adapted CSS and JavaScript
  carry an attribution header.
