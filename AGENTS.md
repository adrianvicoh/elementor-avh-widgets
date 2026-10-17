# AGENTS.md

## Project overview

This repository is a WordPress plugin that adds opt-in Elementor widgets and
opt-in extensions to existing Elementor elements.

## Conventions

- Never modify `.gitignore`, including to allow vendored files or generated
  artifacts. Ask for explicit user direction if its rules block the work.
- Keep standalone widget PHP classes in `widgets/`.
- Register every widget in
  `dashboard/dash-configs/widget-visibility.php`; the dashboard controls
  whether Elementor registers it.
- Register widget assets in `elementor-avh-widgets.php`. Load frontend
  behavior through Elementor's `frontend/element_ready/<widget>.default` hook
  and provide a standalone frontend fallback.
- Reuse the `custom-elementor-widgets` text domain and escape all rendered
  Elementor control values.
- Keep global element extensions in `configs/` and their assets in
  `configs/assets/`.
- Make widget JavaScript instance-safe. It must support several instances on
  one page and Elementor preview rerenders without duplicate listeners or
  leaked library instances.
- Elementor keeps the `.elementor-widget-<name>` wrapper across editor
  rerenders and replaces only its content. Never use a flag on the wrapper to
  skip initialisation. Store the library instance on the wrapper, skip only
  when it is bound to the current inner node, and otherwise destroy it
  (`destroy()`, `unset()`, observer `disconnect()`) before binding again.
- Prefer the library's own features over custom code. In `README.md`, state
  what each widget gets from its library and what it implements itself.

## Third-party libraries

- Vendor only the pinned upstream package source needed for a feature under
  `libs/<package>-<version>/`.
- Copy a runtime bundle and its matching license to `assets/vendor/` when a
  widget must work from a normal plugin archive. Keep the full source only in
  `libs/`.
- Preserve the upstream `LICENSE` file. Record package name, version,
  author and exact copyright line, source URL, pinned commit, license, the
  feature that uses it, and how it is distributed (unmodified bundle, adapted
  port, or CDN) in the root `THIRD-PARTY-NOTICES.md`, which is versioned.
  `libs/THIRD-PARTY-NOTICES.md` is only a local index because `libs/` is
  ignored. Also add a row to the *Third-party libraries* table in `README.md`.
- When code is adapted or ported from a third-party work rather than shipped
  unchanged, ship its license text beside the adapted assets and start each
  adapted file with a comment naming the original work, author, copyright,
  and license.
- Credit tutorials, templates, and other non-library sources in
  `THIRD-PARTY-NOTICES.md` too, and state when no license is given.
- Do not update a vendored dependency incidentally. Update its source,
  runtime integration, notice, and changelog together.
- Do not add build caches, `node_modules`, or unrelated reference trees under
  `libs/`.

## Documentation and validation

- Add user-visible changes to `CHANGELOG.md` under `Unreleased`.
- Update `README.md` when adding or changing a widget, setting, dependency,
  or activation step. The project uses `README.md`; do not create a duplicate
  `README.txt`.
- Keep `STATE.md` current while an implementation is in progress so another
  agent can continue safely. Remove only stale completed-task details.
- Validate PHP syntax with `php -l` for each changed PHP file and JavaScript
  syntax with `node --check` for each changed JavaScript file.
