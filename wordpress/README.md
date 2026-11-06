# OSUS Interiors — WordPress theme

`osus/` is a classic WordPress theme built from the static site in `../website`.
`osus-theme.zip` is the same folder packaged for upload.

## Install
1. WordPress admin → **Appearance → Themes → Add New → Upload Theme** → choose `osus-theme.zip` → Install → **Activate**.
2. **Settings → Reading** → "Your homepage displays": leave on *Your latest posts*, or pick any static page — `front-page.php` always renders the homepage.
3. **Settings → General** → set the Site Title and Tagline (the tagline is used as the meta description).

The zip is ~10 MB (photos and videos included). If the host's upload limit is lower, upload the `osus` folder to `wp-content/themes/` over FTP / File Manager instead.

## Editing content
**Appearance → Customize → OSUS homepage**

| Section | What you can change |
|---|---|
| Hero | label, headline (one line per line of text), intro text, 4 × slideshow photo + caption |
| Studio | scroll-fill heading, text |
| Video carousel | 6 × MP4 / cover image / title / subtitle (empty video = slide hidden) |
| Selected projects | heading, 6 × name / type · city / photo URLs, one per line (empty name = hidden) |
| What we do | 5 × service name / two-line detail / photo |
| Client reviews | 6 × review / client name / project · city (empty text = hidden). Defaults are labelled sample reviews — replace them with genuine ones. |
| Contact & forms | Web3Forms key (enquiry + review forms), email, phone, WhatsApp number, Instagram, location line |

Project photos: upload them in **Media → Add New**, open each one, copy its **File URL** and paste one URL per line. Photos uploaded this way get responsive sizes automatically.

Forms: create a free access key at [web3forms.com](https://web3forms.com) with the Gmail address that should receive enquiries and reviews, and paste it into **Contact & forms**. Until then both forms say "not connected yet" and send nothing. Submitted reviews only arrive by email; add the ones you approve under **Client reviews**.

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
