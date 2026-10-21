# No.9 Theme

WordPress block theme for No.9 — Taiwanese bubble tea.

## Brand Guidelines

Follow these for all design and styling work (theme.json, templates, parts, CSS).

### Colours

| Role      | Name          | Hex       | Usage                                                        |
|-----------|---------------|-----------|--------------------------------------------------------------|
| Primary   | Midnight Navy | `#232B4E` | Main background, logo badge, section-title outlines, footer |
| Secondary | Warm Cream    | `#FBF6EC` | Menu panels, arched cards, soft-serve & coffee blocks       |
| Accent    | Tomato Red    | `#E0492B` | Prices, sub-headlines, short highlights                      |
| Text      | Charcoal      | `#1E1E24` | Body copy and item lists on cream                            |
| Reverse   | White         | `#FFFFFF` | Text on navy: toppings list, logo lettering, footer          |

**Pairing rules**
- Charcoal and red text sit on cream; white text sits on navy.
- Red is reserved for prices and short highlights, never long passages.
- Navy frames every section; cream holds the content.

Hex values are close estimates sampled from the menu artwork — verify against original design files where available.

### Typography

| Element                     | Style                                                                                          | Font                                   |
|-----------------------------|------------------------------------------------------------------------------------------------|----------------------------------------|
| Headings & section titles   | Bold, rounded-geometric sans serif, title case, set inside navy pill outlines (e.g. "Brown Sugar Series") | Poppins SemiBold/Bold (alt. Nunito Bold) |
| Prices                      | Same sans serif, Bold, Tomato Red, sized larger than body copy (e.g. "M£5.8  L£6.8")           | Poppins Bold                           |
| Body copy & item lists      | Clean neutral sans serif, Regular, small size, generous line spacing (e.g. "Strawberry · Lychee · Grape") | Noto Sans or Source Sans 3             |
| Feature labels              | Bold italic uppercase, white on navy (e.g. "TOPPINGS")                                          | Poppins Bold Italic                    |
| Logo lettering              | Uppercase, widely tracked, set on a circular path around the badge ("TAIWANESE · BUBBLE TEA")  | Uppercase sans serif                   |

Fonts are nearest visual matches rather than confirmed typefaces.

### Visual motifs
- Section titles in rounded pill outlines (navy border).
- Content in cream panels / arched cards with rounded corners on a navy background.
- Feature labels in solid navy rounded bars.

## Development

Styles are written in Sass (`src/scss/`) and compiled to `assets/css/main.css`, which `functions.php` enqueues on the front end and in the editor. Don't edit `assets/css/main.css` directly.

- `npm run build` — compile compressed CSS
- `npm run watch` — recompile on change (with source maps)
