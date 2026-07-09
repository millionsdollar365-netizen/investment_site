---
name: ui-ux-pro-max
description: UI/UX design intelligence for web and mobile. 50+ styles, 161 color palettes, 57 font pairings, 161 product types, 99 UX guidelines, 25 chart types across 10 stacks.
---

# UI-UX Pro Max — Design Intelligence

## When to Apply

**Use when:**
- Designing new pages (Landing, Dashboard, Admin, SaaS, Mobile)
- Creating or refactoring UI components
- Choosing color schemes, typography, spacing, or layout
- Reviewing UI for UX, accessibility, or visual consistency
- Implementing navigation, animations, or responsive behavior
- UI "not professional enough" with unclear cause
- Pre-launch quality optimization

**Skip when:**
- Pure backend logic, API, database, DevOps, non-visual scripts

## Priority Rules

### Priority 1: Accessibility (CRITICAL)
- 4.5:1 contrast for normal text, 3:1 for large text
- Visible focus rings (2-4px) on all interactive elements
- aria-label on icon-only buttons
- Tab order matches visual order
- Form labels with `for` attribute
- Sequential heading hierarchy (h1→h6)
- Don't convey info by color alone
- Respect `prefers-reduced-motion`
- Provide escape routes in modals and multi-step flows

### Priority 2: Touch & Interaction (CRITICAL)
- Min 44x44pt touch targets (Apple), 48x48dp (Material)
- Min 8px gap between touch targets
- Don't rely on hover alone for primary actions
- Disable buttons during async with spinner/progress
- Error messages near problem field
- `cursor: pointer` on clickable elements (web)
- `touch-action: manipulation` to reduce 300ms tap delay
- Visual feedback on press (ripple/highlight)
- Safe area awareness — keep targets away from notch/edges

### Priority 3: Performance (HIGH)
- WebP/AVIF images, lazy load, responsive images
- `font-display: swap` with space reservation
- Lazy load non-hero components
- Batch DOM reads then writes
- Reserve space for async content to prevent CLS
- Virtualize lists with 50+ items
- Skeleton screens for >1s operations
- Debounce/throttle scroll, resize, input

### Priority 4: Style Selection (HIGH)
- Match style to product type consistently
- SVG icons only (no emojis as icons)
- Color palette from product/industry
- Distinct hover/pressed/disabled states
- Consistent elevation/shadow scale
- Design light/dark together
- One icon set across the product
- One primary CTA per screen

### Priority 5: Layout & Responsive (HIGH)
- `viewport` meta with `width=device-width initial-scale=1`
- Mobile-first (375px baseline), scale up
- Systematic breakpoints: 375 / 768 / 1024 / 1440
- Min 16px body text on mobile
- 35-60 chars per line on mobile, 60-75 on desktop
- NO horizontal scroll on mobile
- 4pt/8dp spacing scale
- Layered z-index: 0/10/20/40/100/1000
- `min-h-dvh` over `100vh` on mobile
- Show core content first on mobile

### Priority 6: Typography & Color (MEDIUM)
- 1.5-1.75 line-height for body
- 65-75 chars per line max for body
- Consistent type scale: 12/14/16/18/24/32
- Bold headings (600-700), Regular body (400), Medium labels (500)
- Semantic color tokens, not raw hex
- Dark mode: desaturated/lighter tonal variants
- 4.5:1 minimum contrast (AA)

### Priority 7: Animation (MEDIUM)
- 150-300ms micro-interactions, ≤400ms complex, avoid >500ms
- Use transform/opacity only (GPU), NOT width/height/top/left
- Skeleton if loading exceeds 300ms
- 1-2 animated elements per view max
- ease-out for entering, ease-in for exiting
- Exit ~60-70% of enter duration
- Stagger items by 30-50ms
- Interruptible by user input
- Never block input during animation

### Priority 8: Forms & Feedback (MEDIUM)
- Visible label per input (not placeholder-only)
- Error below related field with clear recovery path
- Submit: loading → success/error state
- Required fields marked with asterisk
- Helpful empty states with action
- Toast auto-dismiss 3-5s, use aria-live="polite"
- Confirm before destructive actions
- Validate on blur (not keystroke)
- Password show/hide toggle
- Auto-focus first invalid field on error
- Mobile input height ≥44px

### Priority 9: Navigation (HIGH)
- Bottom nav: max 5 items, labels + icons
- Predictable back behavior preserving scroll/state
- Active location visually highlighted
- Primary vs secondary nav clearly separated
- Modal has clear close/dismiss affordance
- Same nav placement across all pages
- Never mix Tab + Sidebar + Bottom Nav at same level

### Priority 10: Charts (LOW)
- Match type to data (trend→line, comparison→bar)
- Always show legend near chart
- Tooltip on hover/tap showing values
- Responsive — reflow on small screens
- Screen reader summary for accessibility

## Pre-Delivery Checklist

### Visual Quality
- [ ] No emojis as icons (SVG only)
- [ ] Consistent icon family and style
- [ ] Semantic theme tokens (no ad-hoc colors)
- [ ] Press states don't shift layout

### Interaction
- [ ] All tappable elements have press feedback
- [ ] Touch targets ≥44x44pt
- [ ] Micro-interactions 150-300ms
- [ ] Disabled states visually clear
- [ ] Screen reader focus order correct

### Light/Dark Mode
- [ ] Text contrast ≥4.5:1 in both modes
- [ ] Dividers/interaction states visible in both
- [ ] Modal scrim 40-60% black
- [ ] Both themes tested

### Layout
- [ ] Safe areas respected
- [ ] No content hidden behind fixed bars
- [ ] Tested on small phone, large phone, tablet
- [ ] 4/8dp spacing rhythm consistent

### Accessibility
- [ ] All images/icons have accessibility labels
- [ ] Form fields have labels and errors
- [ ] Color not the only indicator
- [ ] Reduced motion supported
- [ ] Dynamic text size doesn't break layout

## Design Dials
- **Variance (1-10):** Low=centered/minimal, Mid=balanced, High=bold/asymmetric
- **Motion (1-10):** Low=subtle, Mid=standard stagger, High=complex choreography
- **Density (1-10):** Low=spacious (24-96px), Mid=standard (16-64px), High=dense (8-32px)
