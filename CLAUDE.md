# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

BeJazzy is a lightweight bilingual website prototype for a Slovenian vocal group. It is dependency-free HTML, CSS, and JavaScript with browser `localStorage` used for the prototype admin flow. There is no build step, package manager, bundler, linter, or test suite.

## Commands

- **Run**: open `index.html` directly in a browser, or serve the folder with any static file server (e.g. `npx serve .`). No install or build step is required.
- **Lint/test**: none configured. After edits, verify via the manual checks in "Verification" below and check the browser console / editor diagnostics for syntax errors.

## Architecture

The whole app is a client-rendered single-page app driven by `app.js`, with no router library and no virtual DOM:

- `state` (top of `app.js`) holds `lang` (`sl`/`en`, persisted to `localStorage` as `bejazz-lang`), `view` (drives which template renders), `admin` (bool, toggles the CMS view), and `events` (persisted to `localStorage` as `bejazz-events`, falling back to seeded demo data).
- `copy` is a flat `{ sl: {...}, en: {...} }` dictionary of every UI string; `t(key)` looks up the active language. All new user-facing text must be added to both locales here — there is no separate localization file or framework.
- `render()` is the single entry point that re-renders everything: it picks one of three template functions based on `state.admin`/`state.view`, sets `document.getElementById('app').innerHTML`, does a small DOM patch to merge the contact/join footer into a shared-background wrapper (see `.contact-footer`/`.join-footer` in `brand-wave-step1.css`), then calls `bind()`. There is no diffing — every state change re-renders the full subtree.
- Three template functions build the markup as template-literal strings: `siteTemplate()` (public one-pager: hero, about, events, media, join, contact), `joinInfoTemplate()` (audition info page, swaps `images/Avdicija-slo.png`/`images/Avdicija-eng.png` by language), `adminTemplate()` (local CMS table for editing events).
- `bind()` re-attaches all event listeners after every render using `data-*` attribute selectors (`data-view`, `data-lang`, `data-admin`, event row forms, delete buttons). Navigation is hash-based and simulated in JS (`state.view = el.dataset.view`) rather than using real routing; `#hash` links are used for anchor scrolling and are re-bound each render.
- `persist()` writes `state.events` back to `localStorage`; there is no server, API, or authentication — the admin view is a local-only prototype CRUD over events.
- CSS is layered via `<link>` order in `index.html`: `styles.css` (base visual system + all responsive breakpoints, single `@media(max-width:800px)` block) → `logo-overrides.css` (logo sizing) → `join-page.css` (audition page layout) → `brand-wave-step1.css` (redefines `:root` color variables and adds the shared gradient background used by hero/contact/join footer — this is a newer, in-progress rebrand layer; its `:root` values currently override `styles.css`'s `:root` values globally, not just for the intended sections).

## Content Rules

- Slovenian (`sl`) is the default language; English (`en`) is the alternate language.
- Keep Slovenian and English public content equivalent in meaning.
- Do not invent choir facts, dates, venues, member biographies, contact details, social accounts, event information, or history claims.
- Keep unverified content clearly marked as placeholder or pending approval.
- Preserve the term `vokalna skupina`; do not reintroduce `mešani pevski zbor` unless explicitly requested.
- Keep audition information available as semantic HTML; posters are visual supplements, not the only source of essential information.

## Implementation Rules

- Preserve the existing dependency-free architecture unless a framework or backend is explicitly requested.
- Keep public content and admin editing flows usable on mobile.
- Preserve the warm editorial musical identity and existing logo assets unless a redesign is explicitly requested.
- Keep responsive behavior intact at mobile, tablet, and desktop widths.
- Use safe rendering for editable content; do not interpolate unescaped admin values into `innerHTML`.
- Validate external URLs and use `rel="noopener noreferrer"` with links opened in a new tab.
- Keep `localStorage` admin behavior clearly prototype-only; never describe it as production authentication or shared persistence.
- Keep accessibility states complete: visible keyboard focus, semantic headings and landmarks, descriptive labels, and functional mobile navigation.

## Files

- `app.js`: localized copy, state, rendering, navigation, join view, events, and prototype admin behavior.
- `index.html`: document shell, metadata, fonts, and stylesheet loading order.
- `styles.css`: global visual system, responsive layout, navigation, events, contact, footer, and admin CMS styles.
- `join-page.css`: audition information page layout.
- `logo-overrides.css`: logo sizing overrides.
- `brand-wave-step1.css`: in-progress rebrand layer — shared gradient background and overridden color variables for hero/contact/join.
- `images/Avdicija-slo.png` and `images/Avdicija-eng.png`: Slovenian and English audition artwork.
- `images/bejazzy-logo-new.png`: current logo asset.
- `todo.txt` and `todo-slo.txt`: implementation plans (Ukrainian), not runtime configuration.

## Verification

- Check both languages after changes; Slovenian must remain the default.
- Test public navigation, language switching, join information, event states, mobile layout, and footer alignment.
- Test zero events, missing ticket URLs, malformed `localStorage`, long translations, and missing images where relevant.
- Run editor diagnostics or an equivalent syntax check after edits.
- Do not commit or push changes unless explicitly requested.
- Before production, replace the local prototype admin with authenticated server persistence and verify all public facts and links.
