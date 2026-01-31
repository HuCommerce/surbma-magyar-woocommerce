# Domain–name pairing for translation lists — Shaping Notes

## Scope

- Add a **domain → name** mapping array for plugins and themes (text domain → human-readable name).
- Use the **name** as the visible label in the translation checkbox lists (Plugins and Themes) instead of the domain slug.
- Keep validation and option keys **domain-based** (no change to validation logic).
- Extend the existing dynamic translation UI (checkboxes from domains array, validation from domains array) with human-readable names and search by both name and domain.

## Decisions

- **Explicit name map with fallback:** Build an explicit `domain => name` map for as many domains as practical. For any domain not in the map, fall back to a humanized form of the domain (replace `-`/`_` with spaces, ucwords) so every domain has a display name without requiring 403 manual entries on day one. The map can be extended over time.
- **Search by name and domain:** Add `data-domain` on each translation checkbox list item so the client-side filter can match the search string against both the visible label (name) and the domain (case-insensitive). Items match if either contains the query.
- **Validation:** Continue to use the **domains** array only for validation; the **names** array is for display only.

## Context

- **Extends:** agent-os/specs/2026-01-31-1430-dynamic-translation-settings-ui-validation (dynamic checkboxes and validation from domains array — already implemented).
- **References:** pages/menu-modules.php (translations block ~501–558), modules/translations.php (getter, option-key helper), settings/settings-validate.php (translation validation loop), settings/settings-functions.php (checkbox helper).
- **Product alignment:** User-centric (readable names in lists); easy-to-use (search by name or domain).

## Standards Applied

- No project standards files in agent-os/standards/ apply; follow existing patterns in menu-modules.php and translations.php.
