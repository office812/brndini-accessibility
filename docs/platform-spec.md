# A11Y Bridge Platform Spec

## Product Goal

A11Y Bridge is a hosted accessibility management platform.

Each customer:

- opens an account
- creates a site profile
- configures a hosted widget
- receives a permanent embed snippet
- updates settings from the platform without replacing the script on the site

The product promise is:

- centralized accessibility preferences
- hosted widget management
- accessibility statement connection
- governance and audit support

The product must not promise:

- instant full legal compliance
- automatic fixing of every accessibility issue

## Core Principles

1. The setup must feel possible in under 5 minutes.
2. The platform must look premium, calm, and trustworthy.
3. The widget must feel elegant and lightweight, not loud or gimmicky.
4. The platform itself must be accessible:
   - keyboard support
   - visible focus states
   - strong contrast
   - RTL and LTR support
   - mobile-first behavior
5. Every screen should reduce anxiety and explain what happens next.

## Primary User Types

### 1. Small business owner

Needs:

- simple setup
- clear instructions
- confidence that something meaningful was installed

### 2. Agency / implementer

Needs:

- reusable workflow
- stable embed code
- ability to configure multiple sites

### 3. Compliance manager

Needs:

- statement link
- visibility into settings
- proof of governance and change tracking

## Primary Product Structure

The platform should contain these product areas:

1. Marketing / Landing
2. Registration and Login
3. Onboarding
4. Main Dashboard
5. Widget Builder
6. Install Center
7. Compliance Center
8. Account and Billing

## Information Architecture

### Public Area

- `/`
- `/login`
- `/register`

### Authenticated Area

- `/dashboard`
- `/dashboard/widget`
- `/dashboard/install`
- `/dashboard/compliance`
- `/dashboard/account`

## Main Flow

### Flow A: New customer

1. Customer lands on homepage.
2. Customer creates account.
3. Customer enters company name, site name, domain, and contact email.
4. Platform creates:
   - user
   - site record
   - public site key
   - default widget config
5. Customer is redirected to dashboard.
6. Customer customizes widget.
7. Customer copies embed code.
8. Customer installs code on site.
9. Widget pulls current config from the hosted platform.

### Flow B: Existing customer

1. Customer logs in.
2. Customer updates style or accessibility preferences.
3. Customer saves settings.
4. Hosted widget config updates immediately.
5. Existing embed code continues to work unchanged.

## Screen Specification

## 1. Landing Page

### Goal

Convert visitors into registered customers.

### Above the fold

- Strong headline
- One supporting paragraph
- Primary CTA: `Open account`
- Secondary CTA: `Sign in`

### Required messages

- "One hosted script, updated from your dashboard"
- "Personal accessibility preferences and statement access"
- "Governance support, not compliance theater"

### Supporting blocks

- How it works in 3 steps
- Feature cards
- Compliance language
- Optional agency section later

### Visual direction

- premium SaaS
- clean gradients
- not corporate boring
- not generic “AI SaaS purple”

## 2. Registration

### Goal

Remove friction and create first site quickly.

### Fields

- Company name
- Email
- Password
- Site name
- Domain

### UX rules

- only essential fields
- inline validation
- clear error copy
- after submit, go directly into dashboard

## 3. Login

### Goal

Fast access back into management area.

### Fields

- Email
- Password

### UX rules

- calm, simple, no distractions
- direct recovery link later

## 4. Main Dashboard

### Goal

Give the customer one clear control center.

### Top section

- Site name
- Site key
- Embed script snippet
- Last updated indicator
- Copy button

### KPI cards

- Widget status: active / not installed / recently updated
- Statement status: connected / missing
- Sync mode: hosted

### Main content layout

Left area:

- configuration form

Right area:

- live preview
- install summary
- compliance summary

### Success state

After save:

- success flash
- updated preview
- no change to embed code

## 5. Widget Builder

### Goal

Make the widget configurable but controlled.

### Style controls

- Position:
  - bottom right
  - bottom left
- Size:
  - compact
  - comfortable
  - large
- Primary color
- Button label
- Language:
  - Hebrew
  - English

### Feature toggles

- High contrast toggle
- Font scale controls
- Underline links
- Reduce motion
- Accessibility statement link

### Rules

- No unlimited customization
- Keep layout quality consistent
- Preview updates instantly

## 6. Install Center

### Goal

Help the customer install the widget without support.

### Sections

- Standard script embed
- WordPress connector
- Platform notes

### Required content

- the exact script snippet
- where to paste it
- how to verify installation
- what changes automatically after installation

### Validation state

Later version:

- detect if widget has been loaded successfully from production site

## 7. Compliance Center

### Goal

Frame the product responsibly and professionally.

### Sections

- Accessibility statement URL
- Service mode
- Last audit date
- Open remediation tasks

### Required compliance copy

The platform should explicitly say:

- the widget provides preferences and guidance
- full accessibility depends on site code, content, and testing
- managed service and audits are separate layers

## 8. Account Area

### Contains

- Company profile
- Contact email
- Plan / billing
- Connected sites
- API / platform details later

## Widget Specification

## Closed State

### Must include

- floating pill button
- brandable color
- icon + label
- strong but elegant shadow
- clear hover and focus states

### Feel

- premium
- compact
- confident
- not cartoonish

## Open State

### Layout

- branded header
- title
- short description
- site name chip
- preference controls
- accessibility statement link
- short explanatory note

### Width

- desktop max width: 360px
- mobile: nearly full width with side margins

### Behavior

- opens and closes smoothly
- closes on `Escape`
- maintains `aria-expanded`
- all toggles expose `aria-pressed`

## Widget Interaction Model

### Text size

- 3 actions:
  - small
  - normal
  - large

### Contrast

- toggle button

### Underline links

- toggle button

### Reduce motion

- toggle button

### Persistence

- save preferences in `localStorage`
- scope by `siteKey`

## Design System

## Brand Personality

- trustworthy
- premium
- calm
- practical
- modern

## Color Direction

Primary:

- deep teal

Support:

- warm sand
- soft amber
- dark slate text

Avoid:

- default purple SaaS palettes
- cold sterile grayscale
- neon tones

## Typography

- serif for major headings
- clean sans for interface and body text
- generous line-height

## Shapes

- rounded cards
- rounded pill actions
- soft shadows
- layered surfaces

## Motion

- soft panel reveal
- subtle hover lift
- no excessive motion

## Copy System

### Tone

- calm
- clear
- non-technical when possible
- transparent about limits

### Good examples

- "Your embed code stays the same. Changes are pulled automatically."
- "Display preferences and accessibility statement access in one hosted layer."
- "Full compliance still depends on code, content, and testing."

### Avoid

- "Instant compliance"
- "One-click full accessibility"
- "Automatic legal protection"

## Accessibility Requirements For The Platform

- Full keyboard navigation
- Focus visible on every control
- Form labels always visible
- Error messages tied to inputs
- Semantic headings
- Contrast ratio aligned with WCAG AA
- Touch targets at least comfortable on mobile
- RTL support without broken spacing

## Technical UX Rules

- The embed code must never change after initial installation unless domain changes fundamentally.
- Widget settings must update without requiring reinsertion of code.
- Preview should closely match the real widget.
- Failures should degrade safely:
  - if config fetch fails, widget should fail quietly
  - site should not break

## "Perfect" Acceptance Criteria

The platform is considered excellent when:

1. A first-time customer can register and install within 5 minutes.
2. The dashboard immediately explains what to do next.
3. The widget looks premium on both desktop and mobile.
4. The preview matches the live widget closely.
5. The compliance language is responsible and clear.
6. The product feels like a real platform, not a script generator.

## Recommended Build Order

### Phase 1

- Landing page
- Login / registration
- Main dashboard
- Widget builder
- Install center

### Phase 2

- Compliance center
- Better install verification
- Multi-site support
- Better plan structure

### Phase 3

- Billing
- Team roles
- Audit history
- Managed remediation workflows

## Immediate Design Execution Priorities

1. Finalize dashboard visual system
2. Finalize hosted widget visual system
3. Add dedicated install page
4. Add compliance center page
5. Polish homepage last
