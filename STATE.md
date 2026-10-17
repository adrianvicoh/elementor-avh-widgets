# Implementation state

## Current objective

Add Elementor widgets for freely draggable boxes and numerical charts using
locally vendored JavaScript libraries, and record the origin, authorship, and
license of every third-party work the plugin uses.

## Progress

- The existing plugin architecture has been reviewed.
- `README.txt` does not exist; `README.md` is the maintained user
  documentation.
- The requested box interaction is confirmed as direct click/touch dragging,
  not a swipe carousel.
- Chosen drag library: interact.js, because it supports free pointer/touch
  dragging, containment constraints, and inertia. Muuri and SortableJS were
  considered but target sortable grids/lists rather than free positioning.
- Chosen chart library: Chart.js.
- `AGENTS.md` and `CHANGELOG.md` were created before functional changes.
- Vendored full upstream sources with their MIT licenses under `libs/`
  (ignored by Git):
  - interact.js `1.10.27` at commit
    `3ace1cad145fb728a925e01a3fea2e86d761f6b4`.
  - Chart.js `4.5.1` at commit
    `9c5cf9fac7ec04a71b516e2aff3f7d76876be369`.
- Copied the frontend bundles and matching license texts to `assets/vendor/`
  so the plugin archive does not require ignored files at runtime.
- Implemented and registered `Draggable Boxes` and `Numeric Chart`, including
  local scripts, styles, Elementor controls, and dashboard visibility entries.
- Review pass after the first agent session (GitHub Copilot in Zed, session
  `205af757`) stopped on a quota error:
  - Draggable Boxes no longer marks the persistent Elementor wrapper as bound,
    which blocked re-initialisation after editor rerenders. Each instance now
    unsets its interact.js interactables and ResizeObserver before rebinding.
  - Boxes are clamped inside the canvas on init and when the canvas resizes,
    so percentage positions cannot leave a box outside a narrow canvas.
  - Numeric Chart keeps its Chart.js instance on the widget wrapper and
    destroys it when the canvas is replaced, so rerenders no longer leak
    instances. A duplicate init for the same canvas is skipped.
  - An empty chart title no longer enables an empty Chart.js title block.
- Documentation pass: `README.md` says what each new widget gets from its
  library and what it implements itself, lists all third-party works, and
  shows the new root documents in the project structure. `AGENTS.md` records
  the Elementor rerender pattern and the attribution rules.
- Attribution pass: added the versioned root `THIRD-PARTY-NOTICES.md` (author,
  copyright, source, commit, license, and distribution for interact.js,
  Chart.js, border-beam, Swiper, and the DMmotionarts tutorial). The Border
  Beam port previously shipped without its MIT notice; it now has
  `configs/assets/border-beam.LICENSE` and attribution headers in its CSS/JS.
  `libs/THIRD-PARTY-NOTICES.md` is only a local index.
- Known scope limits (possible follow-ups, not started): Numeric Chart exposes
  one data series and only bar/line/doughnut out of Chart.js's chart types.
  Draggable Boxes uses a generic drag library; card visuals are custom.

## Next steps

1. Optionally record the border-beam upstream commit in
   `THIRD-PARTY-NOTICES.md`; the local copy is a `main` download at 1.4.0
   and its commit was never recorded.

## Validation

- `php -l` passed for the plugin entry point, widget registry, and both new
  widget classes.
- `node --check` passed for `assets/js/draggable-boxes.js`,
  `assets/js/numeric-chart.js`, and `configs/assets/js/border-beam.js`.
  `node` is not on this machine's `PATH`; Zed's bundled runtime works:
  `C:\Users\adria\AppData\Local\Zed\node\node-v24.21.0-win-x64\node.exe`.
- Standalone browser smoke test (vendored bundles, no WordPress): both widgets
  initialise, simulated rerenders leave one Chart instance and one
  interactable, out-of-bounds boxes are clamped, and arrow keys move a box.
  A real mouse drag was not tested.
- `git diff --check` passed.
- The user tested the widgets manually in a WordPress site with Elementor after
  commit `f7cbc35`; everything worked.

## Constraints

- Do not modify `.gitignore`; this is an explicit user instruction recorded
  in `AGENTS.md`. Its existing local change (removed `elementor-source/` and
  `external-source/` rules) predates this work and must be preserved.
- `libs/` is ignored by Git and holds local reference trees. Anything that
  must be versioned (bundles, license texts, notices) lives outside `libs/`.
- Do not replace the existing Coverflow Slider's CDN dependency in this task;
  the new widgets must not introduce CDN runtime requests.
