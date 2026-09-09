# BeJazzy Project Instructions

## Project

BeJazzy is a lightweight bilingual website prototype for a Slovenian vocal group. It currently uses dependency-free HTML, CSS, and JavaScript with browser `localStorage` for the prototype admin flow.

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
- `index.html`: document shell, metadata, fonts, and stylesheet loading.
- `styles.css`: global visual system, responsive layout, navigation, events, contact, and footer.
- `join-page.css`: audition information page layout.
- `logo-overrides.css`: logo sizing overrides.
- `Avdicija-slo.png` and `Avdicija-eng.png`: Slovenian and English audition artwork.
- `logo-bejazzy.png`: current logo asset.
- `todo.txt` and `todo-slo.txt`: implementation plans, not runtime configuration.

## Verification

- Check both languages after changes; Slovenian must remain the default.
- Test public navigation, language switching, join information, event states, mobile layout, and footer alignment.
- Test zero events, missing ticket URLs, malformed `localStorage`, long translations, and missing images where relevant.
- Run editor diagnostics or an equivalent syntax check after edits.
- Do not commit or push changes unless explicitly requested.
- Before production, replace the local prototype admin with authenticated server persistence and verify all public facts and links.
