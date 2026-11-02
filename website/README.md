# OSUS Interiors — Website

Static website built from the Figma design (https://dandy-nebula-41711332.figma.site/#home).
No build step or dependencies — open `index.html` in a browser, or upload the whole folder to any host (Vercel, Netlify, cPanel).

```
website/
├── index.html          page markup (all sections)
├── css/style.css       design tokens, layout, animations, responsive rules
├── js/main.js          menu, scroll reveal, parallax, progress bar
└── assets/
    ├── logo/           osus-logo.png (header/footer), osus-logo-alt.png
    └── images/         optimized .webp photos (watermark cropped out)
```

## Swapping images
Replace a file in `assets/images/` with the same name, or change the `src` in `index.html`.
Images source files (cropped to remove the bottom OSUS watermark):

| File | Source |
|---|---|
| hero-dining.webp | 30-9-2026/4.png |
| living-lounge.webp | 5-10-2026/7.jpg |
| project-aurelle.webp | 5-10-2026/3.jpg |
| project-sage-bedroom.webp | 10-10-2026/1.jpg |
| project-dining.webp | 20-10-2026/2.jpg |
| room-kitchen.webp | 28-8-20263/osus 4.png |
| room-bedroom.webp | 7-10-2026/2.jpg |
| room-living.webp | 20-10-2026/4.jpg |

## Video carousel ("In motion" section)
Clips live in `assets/videos/` (540×960, muted, ~10s, each with a `.webp` poster).
To add or replace one, copy a `<article class="reel-card">` block in `index.html` and change `data-title`, `data-meta`, `poster` and `src`.

| File | Source |
|---|---|
| site-to-sanctuary.mp4 | 0821/video.mp4 |
| gilded-lounge.mp4 | 30-9-2026/0930 (3)/1.mp4 |
| through-the-house.mp4 | Camera_gliding_through_modern_house_202608210102.mp4 |
| night-day-suite.mp4 | 16-9-2026/0916 (1)/0916 (1).mp4 |
| soft-edges.mp4 | Osus.mp4 |
| glass-house.mp4 | 0829/osus 1.mp4 |

## Colours
Taken from the OSUS logo: `--ink #34151e` (maroon — text, dark sections, logo), `--acid #e6d3cc` (blush accent), `--paper #f1f0eb`, `--soft #d8d7d0`, `--line #b8b7b0`, `--olive #9a6f78` (in `css/style.css`). `assets/logo/osus-logo-black.png` is the original black logo.
