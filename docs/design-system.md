# A11Y Bridge Design System

## Design Intent

The visual language should feel:

- quiet
- premium
- exact
- accessible
- calm under pressure

The goal is not to imitate Apple visually.
The goal is to reach that level of restraint and consistency.

## Core Principles

1. Fewer visual ideas per screen.
2. Strong hierarchy before decoration.
3. Motion that supports understanding, not attention-seeking.
4. Soft materials, sharp alignment.
5. Every control must feel intentional.

## Brand Personality

- Trusted
- Warm
- Minimal
- Product-grade
- Operational

## Color System

### Primary

- Deep teal
- Used for main actions, brand moments, and key states

### Neutrals

- Warm ivory backgrounds
- Sand surfaces
- Slate text

### Support

- Soft amber for highlights and warning-adjacent emphasis
- Green reserved for healthy state confirmation
- Red reserved only for true error states

## Typography

### Heading

- Serif with elegance and authority
- Used for major headlines and named sections

### UI / Body

- Clean sans serif
- Used for controls, helper text, and dense information

### Rules

- Limit the number of font sizes in view
- Use weight changes before size changes
- Favor generous line-height over large text blocks

## Spacing System

Use consistent rhythm:

- 8
- 12
- 16
- 20
- 24
- 32
- 40

Never create arbitrary spacing values when an existing step works.

## Radius System

- Small controls: 14px to 18px
- Cards: 22px to 30px
- Pills and floating buttons: 999px

## Shadow System

Use soft atmospheric shadows:

- low elevation for controls
- medium elevation for cards
- deeper elevation only for floating elements like the widget

Shadows should feel diffused, not hard-edged.

## Motion System

### Timing

- fast: 140ms
- standard: 180ms
- soft: 220ms

### Easing

- ease-out for reveal
- ease-in-out for state change

### Allowed motion

- fade
- slight lift
- soft scale
- subtle position shift

### Avoid

- bouncy motion
- elastic effects
- long transitions
- decorative looping animation

## Component Rules

## 1. Floating Widget Button

- One dominant surface
- One icon
- One text label
- One direction hint at most
- Strong contrast
- Soft depth

The button should never feel noisy or overloaded.

## 2. Widget Panel

- Branded header
- Minimal description
- Maximum clarity
- Controls grouped in clean cards
- Footer with statement link and governance note

The panel should feel like a premium utility, not a plugin popup.

## 3. Product Cards

- One key title
- One supporting paragraph
- One state or action

Do not stack too many borders, shadows, and gradients on the same object.

## 4. Navigation

- Lightweight
- Clearly active
- Not visually dominant

## 5. Forms

- Quiet fields
- Strong labels
- Clear validation
- Primary action obvious

## Accessibility Rules

- Visible keyboard focus on every interactive element
- Color is never the only state signal
- Hover states must not be required to understand controls
- Touch targets must remain comfortable on mobile
- Motion must respect reduced-motion preferences

## Tone Rules

Copy should feel:

- direct
- calm
- transparent
- professional

Avoid hype language, legal overclaiming, or “one-click miracle” messaging.

## Visual Standard

If an element does not improve:

- trust
- clarity
- usability
- hierarchy

it should be removed or simplified.
