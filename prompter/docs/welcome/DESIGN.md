# Design System Document

## 1. Overview & Creative North Star
### The Creative North Star: "Kinetic Precision"
This design system rejects the "template-first" approach of typical sports apps. Instead, it embraces **Kinetic Precision**—an editorial-inspired aesthetic that mirrors the velocity of soccer with high-performance digital craft. The experience is defined by breathable, expansive layouts, intentional asymmetry in data visualization, and a "layered-glass" depth model. By prioritizing aggressive whitespace over rigid borders, we create an interface that feels fast, light, and premium.

## 2. Colors
Our palette is anchored by a high-energy primary red and supported by a sophisticated range of neutral surfaces.

*   **Primary Action:** `#9e0000` (Primary) and `#cc0000` (Primary Container). These are the energy drivers.
*   **Neutral Foundation:** `#f8f9fa` (Surface) provides a crisp, clinical backdrop.
*   **Typography:** Use `#191c1d` (On Surface) for authoritative headers and `#5e3f3a` (On Surface Variant) for secondary metadata.

### The "No-Line" Rule
**Explicit Instruction:** Designers are prohibited from using 1px solid borders to section content. Information architecture must be established through background tonal shifts. Use `surface-container-low` to distinguish sections from the main `surface` background. The only exception is the **Ghost Border** fallback (see Elevation & Depth).

### Surface Hierarchy & Nesting
Treat the UI as a physical stack of translucent materials. 
*   **Base:** `surface` (#f8f9fa)
*   **Sectioning:** `surface-container-low` (#f3f4f5)
*   **Interactive Cards:** `surface-container-lowest` (#ffffff)
This nesting creates natural, soft-edged focus zones without the "boxiness" of traditional grids.

### Signature Textures
Main action buttons and hero score headers should utilize a subtle radial gradient transitioning from `primary` (#9e0000) to `primary_container` (#cc0000). This adds a "weighted" feel to the color that flat hex codes lack.

## 3. Typography
We utilize a dual-typeface system to balance technical clarity with editorial impact.

*   **Display & Headlines (Lexend):** A geometric sans-serif that provides maximum legibility for scorelines and major headers. It feels athletic and modern.
    *   *Scale Example:* `display-lg` (3.5rem) for live scores; `headline-md` (1.75rem) for section titles.
*   **Titles & Body (Manrope):** A functional, high-readability face for player names, match events, and inputs. 
    *   *Scale Example:* `title-md` (1.125rem) for player names; `label-md` (0.75rem) for timestamps.

**Hierarchical Intent:** Use `headline-sm` in all-caps with generous letter-spacing for section labels (e.g., "MATCH TIMELINE") to establish an authoritative editorial tone.

## 4. Elevation & Depth
Depth in this system is achieved through light and layering, not heavy shadows.

*   **The Layering Principle:** Place a `surface-container-lowest` card on top of a `surface-container-low` background. The slight shift in lightness (from #f3f4f5 to #ffffff) provides all the separation necessary.
*   **Ambient Shadows:** For floating elements (like Bottom Sheets or specialized Pop-overs), use an extra-diffused shadow: `box-shadow: 0 12px 40px rgba(25, 28, 29, 0.06);`. The shadow color is a low-opacity version of `on-surface` to mimic natural light.
*   **The "Ghost Border":** If accessibility requires a stroke, use `outline-variant` (#e8bdb6) at 20% opacity. Never use 100% opaque borders.
*   **Glassmorphism:** Apply `backdrop-blur: 12px` and 80% opacity to navigation headers to allow the "energy" of the content to bleed through as the user scrolls.

## 5. Components

### Buttons
*   **Primary:** Rounded `full` (9999px), `primary` background with `on_primary` text. Use the Signature Texture gradient for a premium feel.
*   **Secondary:** `secondary_container` background with `on_secondary_fixed_variant` text. High-padding, low-contrast.

### Cards & Lists
*   **Match Cards:** Use `surface-container-lowest` with `md` (1.5rem) rounded corners. 
*   **The "No-Divider" Rule:** Never use horizontal lines to separate match events. Instead, use a `spacing-3` (1rem) vertical gap. The absence of lines makes the timeline feel like a continuous stream of play.

### Score Input
*   **Tonal Steppers:** Score increment/decrement buttons should use a soft `secondary_container` circular background. The score itself should be `display-sm` (Lexend) for maximum impact.

### Match Timeline Chips
*   **Event Indicator:** Use a soft-tinted circular background (e.g., `primary_fixed` at 50% opacity) for timestamps to create a "pulsing" focal point.

## 6. Do's and Don'ts

### Do:
*   **Do** use generous spacing (`spacing-6` or `spacing-8`) between major UI sections to give the athletic data room to breathe.
*   **Do** use `lexend` for anything involving a number (scores, minutes, dates).
*   **Do** utilize `xl` (3rem) corner radius for large hero sections to create a friendly, contemporary PWA feel.

### Don't:
*   **Don't** use pure black (#000000). Always use `on_surface` (#191c1d) for text to maintain a sophisticated, "ink-like" quality.
*   **Don't** use standard "drop shadows" on cards. Rely on tonal layering.
*   **Don't** cram content. If the screen feels full, increase the spacing and allow the PWA to scroll naturally. Premium design lives in the gaps.