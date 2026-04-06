# Brndini Performance Optimization Guide

## Current State
- TTFB: 603ms (target: <200ms)
- HTML Size: 356KB (target: <100KB)
- HTTP Requests: 96 (48 CSS + 48 JS) - target: <30
- FlyingPress: Active but not fully optimized
- Cloudflare: Active (CDN working)

## Step 1: FlyingPress Settings (wp-admin → FlyingPress)

### CSS Optimization
- [x] **Minify CSS** → ON
- [ ] **Remove Unused CSS** → ON (critical! will cut CSS by ~60%)
- [ ] **Load CSS Asynchronously** → ON (prevents render blocking)

### JS Optimization  
- [x] **Minify JS** → ON
- [ ] **Defer JavaScript** → ON
- [ ] **Delay JavaScript** → ON, add these scripts to delay:
  ```
  jet-tricks
  tsparticles
  jet-search
  chosen.jquery
  jet-tabs
  pixelyoursite
  vimeo.min.js
  jet-blog
  link-whisper
  googlesitekit
  ```

### Caching
- [ ] **Page Caching** → ON
- [ ] **Cache Lifespan** → 30 days (for a site that doesn't change often)
- [ ] **Separate Mobile Cache** → ON
- [ ] **Preload Cache** → ON (automatically warms cache)

### Image Optimization
- [ ] **Lazy Load Images** → ON (already working)
- [ ] **Add Missing Width/Height** → ON
- [ ] **Serve WebP Images** → ON (if not already)
- [ ] **Lazy Load iframes** → ON

### Fonts
- [ ] **Self-host Google Fonts** → ON (eliminates external requests to fonts.googleapis.com)
- [ ] **Preload Fonts** → add critical fonts

### CDN
- [ ] **CDN URL** → verify Cloudflare integration

## Step 2: Install Brndini Performance Plugin

Upload `brndini-performance/brndini-performance.php` to:
`wp-content/plugins/brndini-performance/brndini-performance.php`

This plugin handles:
- WordPress bloat removal (emojis, RSD, wlwmanifest, generator)
- Gutenberg block CSS removal (using Elementor instead)
- jQuery Migrate removal
- Heartbeat API disabled on frontend
- Dashicons removed for non-logged-in users
- Conditional Jet plugin loading (heavy assets removed on blog posts)
- Script defer/async
- Query string removal from static assets
- Preconnect hints
- Weekly database cleanup (revisions, transients, spam)

## Step 3: Cloudflare Settings

### Page Rules (brndini.co.il)
1. **Cache Level**: Cache Everything
2. **Browser Cache TTL**: 1 month
3. **Edge Cache TTL**: 1 month

### Speed Settings
- [x] Auto Minify (HTML, CSS, JS)
- [ ] Brotli Compression → ON
- [ ] Early Hints → ON
- [ ] Rocket Loader → OFF (conflicts with FlyingPress)
- [ ] HTTP/2 → ON
- [ ] HTTP/3 → ON

## Step 4: Cloudways Server Settings

### Varnish Cache
- **Enable Varnish** → ON
- **Varnish Exclusions** → /wp-admin/, /wp-login.php

### Redis Object Cache
- **Enable Redis** → ON (dramatically improves TTFB)
- Install "Redis Object Cache" plugin or use Cloudways built-in

### PHP Settings
- **PHP Version** → 8.2+ (latest stable)
- **PHP Memory Limit** → 512MB
- **OPcache** → ON
- **Max Execution Time** → 300s

### MySQL
- **MySQL Slow Query Log** → check for slow queries
- **Query Cache** → ON

## Step 5: Consider Deactivating Unused Plugins

Currently 35 active plugins. Consider deactivating:
1. **Complianz Privacy Suite** → already inactive, consider removing
2. **Brndini Hub Connector** → inactive, remove if not needed
3. **Brndini Smart Case Studies** → inactive, remove if not needed

Plugins to evaluate if truly needed:
- **JetSearch** → if the site doesn't have a search feature, disable
- **JetTricks** → only needed for specific animation effects
- **JetTabs** → only needed if using tab/accordion widgets
- **JetBlog** → only needed for blog listing widgets

## Step 6: Image Optimization

1. Install **ShortPixel** or use FlyingPress image optimization
2. Convert all images to WebP format
3. Set max image width to 1920px (no need for larger)
4. Enable lazy loading on all images below the fold

## Expected Results After Optimization

| Metric | Before | After (Expected) |
|--------|--------|-------------------|
| TTFB | 603ms | <200ms |
| HTML Size | 356KB | <120KB |
| CSS Files | 48 | <15 |
| JS Files | 48 | <15 |
| PageSpeed Mobile | ~50 | 80+ |
| PageSpeed Desktop | ~70 | 90+ |
| LCP | >3s | <2.5s |
| CLS | Unknown | <0.1 |

## Priority Order
1. **FlyingPress settings** (biggest impact, no risk)
2. **Cloudways Redis + Varnish** (TTFB improvement)
3. **Install Brndini Performance plugin** (bloat removal)
4. **Cloudflare settings** (edge optimization)
5. **Deactivate unused plugins** (reduce PHP execution time)
6. **Image optimization** (bandwidth savings)
