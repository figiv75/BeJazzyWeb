Migration brief

# BeJazzy on WordPress

Today, concert edits made through **"Uredi vsebino"** live only in the visiting browser's `localStorage` — nobody else sees them. Moving the site to WordPress replaces that with a real database, real admin accounts, and a CMS a non-developer can run day to day.

Current: **static HTML/CSS/JS, no backend** Target: **self-hosted WordPress theme** 8 phases · 3 open decisions

Current → WordPress

## What each piece of app.js becomes

The visual system (CSS, images, copy) carries over almost unchanged. What disappears is every line written to work around *not* having a backend — the fake admin toggle, the escaping discipline, the localStorage fallback logic.

Today

On WordPress

`state.events` in `localStorage`per-browser, lost on clear

→

`concert` custom post typeACF fields: date, time, place, ticket_url, is_past, is_free_entry

`data-admin` button, no passwordanyone can open it

→

real `wp-admin` loginroles, capabilities, audit log

`copy.sl` / `copy.en` objecthand-maintained string dictionary

→

Polylang-linked pagessl default, en alternate — same rule, native UI

`headerHtml()` / `footerHtml()`

→

`header.php` / `footer.php`

`siteTemplate()`, `allEventsTemplate()`, `aboutPageTemplate()`, `joinInfoTemplate()`

→

`front-page.php`, concert archive template, `page-about.php`, `page-join.php`

`escapeHtml()` before every admin valuehand-rolled, easy to forget on a new field

→

WP's `esc_html()` / ACF output escapingon by default

styles.css, brand-wave-step1.css, join-page.css, logo-overrides.css

→

theme stylesheets, enqueued as-isnear-zero rewrite

`images/` folder

→

Media Library

Sequence

## Eight phases, in order

Each phase depends on the one before it — the theme can't be wired to real data until the post type exists, and nothing goes live until QA passes on real content.

0

### Environment & plugins

Hosting account with WordPress installed on a staging URL. Install Advanced Custom Fields and Polylang. Set up theme code under version control (keeps the current git history useful).

1

### Event data model

Register the `concert` post type and an ACF field group matching the current event shape exactly: `date`, `time`, `place`, `ticket_url`, `is_past`, `is_free_entry`. Configure Polylang (Slovenian default, English alternate).

2

### Theme build

Convert the current HTML structure into a classic PHP theme — one template per current view, existing CSS files enqueued largely untouched. The concert list swaps `state.events.map()` for a `WP_Query` loop filtered by `is_past`.

3

### Interactive bits

The mobile menu toggle (open/close, `aria-expanded`, Escape to close) ports over nearly verbatim as a small enqueued script. The language switcher becomes Polylang's built-in function. The entire admin template, its form-binding code, and `escapeHtml()` are deleted outright — wp-admin replaces all of it.

4

### Content migration

Enter the 6 real concerts as `concert` posts. Move the About page's long-form Slovenian and English copy into WordPress pages. Re-upload logo, posters, join illustration and the two co-funder logos to the Media Library.

5

### SEO & meta

A long-open item finally becomes easy: per-language `title`, meta description, canonical URL and Open Graph tags via Yoast SEO or Rank Math — none of this exists on the static site today.

6

### QA

Both languages, every breakpoint, real CRUD through wp-admin (create, edit, mark a concert past, mark free entry, delete), and a check that the past/upcoming split renders the same way it does today.

7

### Go-live

DNS cutover to the WordPress install, redirects for any URL shape that changed, retire the static site and its localStorage admin.

Before phase 0

## Three decisions this plan assumes

Each has a reasonable default below — worth confirming before work starts, since switching later means redoing theme or content work.

Theme approach

### How is the design rebuilt?

Custom classic theme — today's HTML/CSS ported to PHP templates Block theme (Full Site Editing) — Gutenberg-native Page builder (e.g. Elementor) — visual rebuild

**Pick**Custom classic theme — closest fidelity to the current gradient/editorial design, and the existing CSS survives almost untouched.

Multilingual plugin

### sl default, en alternate

Polylang — free, simple page-linking model WPML — paid, built for larger translation workflows

**Pick**Polylang. Two languages and no translation team is exactly its use case; WPML's extra machinery isn't needed here.

Hosting

### Needs real PHP + MySQL

Managed WordPress host (e.g. SiteGround, Kinsta) Self-managed VPS

**Open**Budget-dependent — the static site had no hosting cost beyond a CDN; this introduces a recurring one.

Net effect

## What gets easier, what gets rebuilt

### Gets simpler

- **Real persistence.** Concert edits are visible to every visitor immediately — the original problem, solved.
- **Real authentication.** No more unlocked "Uredi vsebino" button; wp-admin has actual accounts.
- **Built-in media handling.** Uploads, resizing, alt text — all native.
- **SEO tooling.** Per-page meta and Open Graph without hand-writing any of it.
- **No more hand-rolled escaping.** One less place a future field can introduce an XSS gap.

### Needs rework

- **Every template.** Template-literal JS strings become PHP files using the WordPress Loop.
- **The copy dictionary.** `copy.sl`/`copy.en` splits into Polylang-linked pages and strings.
- **Ongoing maintenance.** Core, theme and plugin updates, backups, security — none of this existed for a static site.
- **Hosting cost.** A new recurring line item instead of near-zero static hosting.

Main risk

Design fidelity depends entirely on the theme-approach decision above. A custom classic theme can match the current gradient treatment, the event-row layout and the mobile menu almost exactly. A page-builder rebuild is faster to hand off to a non-developer editor later, but some amount of visual drift from the current design should be expected.

BeJazzy — migration brief, prepared for internal review.