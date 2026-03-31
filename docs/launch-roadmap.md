# A11Y Bridge Launch Roadmap

## Goal

Turn the current platform into a sellable hosted accessibility product.

This roadmap is split into:

1. Must-have before selling
2. Critical in the first month
3. Strong upgrades after first customers

## Phase 1: Must-Have Before Selling

These are not optional.

If they are missing, the product may still look good, but it is not ready for real customers.

### 1. Production Stability

#### Required

- Finish and apply all database migrations on the server
- Remove dependency on temporary runtime fallbacks for critical flows
- Make all save/update actions stable across:
  - onboarding
  - widget settings
  - billing state
  - support tickets
  - statement builder
  - install tracking
- Make sure production never shows Laravel exception screens to users

#### Definition of done

- New account creation works
- New site creation works
- Widget settings save without crashes
- Super admin opens without crashes
- Support center opens without crashes
- No red Symfony/Laravel exception screen in production

### 2. Real Install Detection

#### Required

- Track whether the widget was actually loaded on the customer site
- Save:
  - installed / not installed
  - last seen timestamp
  - last seen page URL
- Show clear dashboard states:
  - waiting for install
  - installed and detected
  - installed before but not seen recently

#### Definition of done

- A new customer cannot see a fake “healthy” site before the script is actually installed
- The dashboard clearly tells the customer what still needs to be done

### 3. Package and License Logic

#### Required

- Keep only two packages:
  - Free
  - Premium
- Make feature gating fully consistent in:
  - widget
  - dashboard
  - pricing page
  - account page
- Make inactive licenses show the inactive widget state clearly
- Make upgrade path obvious

#### Free package target

Open around 70% of the basic adjustments:

- contrast
- text size
- readable font
- link highlight
- heading highlight
- reduce motion
- larger cursor
- line spacing
- basic profiles

#### Premium package target

Reserve the most advanced 30%:

- ADHD / dyslexia / cognitive profiles
- reading guide
- hide images
- advanced alignment controls
- letter spacing controls
- premium statement / governance tools later

#### Definition of done

- Free users never see broken premium interactions
- Premium users get full controls
- Upgrade feels intentional, not accidental

### 4. Legal and Trust Layer

#### Required

- Terms of service page
- Privacy policy page
- Clear onboarding consent checkboxes
- Accessibility statement builder with public page
- Clear product wording:
  - helps improve accessibility
  - supports governance
  - does not guarantee full compliance by widget alone

#### Definition of done

- Every new user accepts legal terms
- Every site can publish a statement
- No misleading “instant compliance” claims remain

### 5. Support and Operations Basics

#### Required

- Working support center for customers
- Working super admin ticket view
- Statuses:
  - open
  - pending
  - resolved
- Priority levels
- Internal admin response

#### Definition of done

- A customer can open a ticket
- Super admin can see it, reply, and change status

## Phase 2: Critical In The First Month

These are what make the product feel serious after first sales.

### 1. Billing

#### Required

- Connect real billing provider
- Create free and premium plans
- Handle:
  - upgrade
  - renewal
  - failed payment
  - cancellation
- Show current package and billing cycle clearly

#### Definition of done

- A user can move from free to premium without manual admin work

### 2. Super Admin Maturity

#### Required

- Users tab
- Sites tab
- Support tab
- Tracking scripts tab
- Better analytics summary
- Quick actions

#### Strong next step

- impersonate user
- jump directly to customer site record
- jump directly to open ticket

### 3. Widget QA Pass

#### Required

Check every feature manually:

- toggle on
- toggle off
- reset
- free state
- premium state
- desktop
- mobile
- RTL
- Safari / Chrome

#### Definition of done

- No feature closes the panel unexpectedly
- No feature gets “stuck on”
- Reset truly resets everything

### 4. Mobile Hardening

#### Required

- Dashboard
- install center
- compliance center
- account
- support
- super admin
- public site
- widget

#### Definition of done

- No horizontal overflow
- No clipped text
- No hidden actions

### 5. Install UX

#### Required

- Better onboarding after account creation
- Clear next action:
  - copy code
  - install on site
  - verify detection
  - activate premium if needed

#### Definition of done

- A non-technical user understands exactly what to do next

## Phase 3: Strong Upgrades After First Customers

These are what make the product feel like a larger company platform.

### 1. Public Site Expansion

- Better pricing page with comparison table
- FAQ page
- Industry landing pages
- More case-study style content
- SEO articles with cover upload

### 2. Better Widget Positioning

- More presets
- Better icon system
- stronger premium upsell
- small onboarding inside widget

### 3. Statement Builder Expansion

- Industry templates
- Version history
- downloadable PDF
- proof-of-review log

### 4. Customer Success Layer

- onboarding checklist
- success status
- “what still needs attention”
- recommended next action

### 5. White-Label / Agency Layer

- agency dashboard
- client ownership view
- branded statement templates
- reseller workflows

## Recommended Execution Order

### Sprint 1

- Stabilize production and remove crash paths
- Finish migrations
- Lock package logic
- Fix install detection

### Sprint 2

- Finish support center and super admin
- Finish onboarding and install flow
- QA full widget behavior

### Sprint 3

- Connect billing
- Improve pricing and upgrade flow
- polish widget premium upsell

### Sprint 4

- Expand marketing site
- expand statement builder
- add analytics and admin operations

## What “Ready To Sell” Means

The product is ready to sell when all of the following are true:

- New customer can create account successfully
- New customer can create site successfully
- Customer sees correct “not installed yet” state
- Customer can copy embed code and install it
- Platform detects installation
- Free and premium packages behave correctly
- Support tickets work
- Super admin works
- No production exception screens appear
- Terms, privacy, and statement pages exist

## Current Best Next Step

The highest value next block of work is:

1. Finish server/database consistency
2. Finish install detection and package gating
3. Finish support and super admin stability
4. Run full widget QA

Only after that should the focus move back to heavy visual polish.
