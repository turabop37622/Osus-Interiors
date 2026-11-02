# OSUS Interiors — WordPress theme

`osus/` is a classic WordPress theme built from the static site in `../website`.
`osus-theme.zip` is the same folder packaged for upload.

## Install
1. WordPress admin → **Appearance → Themes → Add New → Upload Theme** → choose `osus-theme.zip` → Install → **Activate**.
2. **Settings → Reading** → "Your homepage displays": leave on *Your latest posts*, or pick any static page — `front-page.php` always renders the homepage.
3. **Settings → General** → set the Site Title and Tagline (the tagline is used as the meta description).

The zip is ~6.7 MB. If the host's upload limit is lower, upload the `osus` folder to `wp-content/themes/` over FTP / File Manager instead.

## Editing content
**Appearance → Customize → OSUS homepage**

| Section | What you can change |
|---|---|
| Hero | label, headline, intro text, hero image, caption |
| Studio | scroll-fill heading, text |
| Selected projects | heading, 3 × image / name / type |
| Video carousel | 6 × MP4 / cover image / title / subtitle (empty video = slide hidden) |
| Contact details | email, phone, Instagram, location line |

Logo: **Customize → Site Identity → Logo** (falls back to the bundled OSUS logo). Favicon: **Site Icon**.
Menu: optional — create one under **Appearance → Menus** and assign it to *Main navigation*; without it the header links to the homepage sections.

Videos: keep them short (~10 s), muted, vertical 9:16 and compressed (under ~1 MB each) so the page stays fast.

## Files
```
osus/
├── style.css        theme header
├── functions.php    setup, asset loading, Customizer options and defaults
├── header.php / footer.php
├── front-page.php   the one-page homepage
├── index.php        blog posts, pages, archives, 404
├── screenshot.png
└── assets/          css/site.css, js/main.js, images, logo, videos (defaults)
```

## Local preview
`.claude/launch.json` → `osus-wordpress` runs WordPress Playground on http://127.0.0.1:8881 with this theme mounted and activated (`blueprint.json`). Nothing is saved between runs.

Without Claude: double-click `start-wordpress.bat` (needs Node.js). It opens a console; when it says the server is ready, open http://127.0.0.1:8881 — admin at `/wp-admin`, user `admin`, password `password`. Close the window to stop. Changes made there are lost on restart; theme file edits in `osus/` show up on refresh.
