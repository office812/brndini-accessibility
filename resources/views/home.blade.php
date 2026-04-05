@extends('layouts.app')

@php($title = 'A11Y Bridge | כלי חינמי להטמעת וידג׳ט נגישות')
@php($metaDescription = 'A11Y Bridge היא שכבה חינמית, שקטה וברורה להטמעת וידג׳ט נגישות: פתיחת חשבון, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית ותמיכה טכנית בלבד.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="stitch-home-hero">
        <div class="stitch-home-hero-copy">
            <div class="stitch-home-badge">
                <span class="stitch-home-badge-dot"></span>
                <span>A11Y Bridge 2.0 — נגישות בקוד אחד</span>
            </div>
            <h1>
                <span>מטמיעים נגישות</span>
                <span class="is-accent">במהירות האור.</span>
            </h1>
            <p>
                פותחים חשבון, מחברים אתר, מטמיעים קטע קוד אחד ומנהלים וידג׳ט,
                הצהרה וזיהוי התקנה מתוך סביבת עבודה אחת.
            </p>
            <div class="stitch-home-actions">
                <a class="stitch-button stitch-button-primary" href="{{ route('register.show') }}">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v16m8-8H4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
                    פתיחת חשבון חינמי
                </a>
                <a class="stitch-button stitch-button-secondary" href="{{ route('how-it-works') }}">איך זה עובד</a>
            </div>
            <div class="stitch-home-trust-row">
                <span>
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="#22c55e" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="10" stroke="#22c55e" stroke-width="2"/></svg>
                    ללא כרטיס אשראי
                </span>
                <span>
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="#22c55e" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="10" stroke="#22c55e" stroke-width="2"/></svg>
                    הטמעה תוך 3 דקות
                </span>
                <span>
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4" stroke="#22c55e" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="10" stroke="#22c55e" stroke-width="2"/></svg>
                    תמיכה טכנית כלולה
                </span>
            </div>
        </div>

        <div class="stitch-home-hero-visual" aria-hidden="true">
            <div class="stitch-home-hero-float">
                <div class="stitch-home-float-title">
                    <span class="stitch-home-float-dot"></span>
                    <span>בדיקת התקנה</span>
                </div>
                <strong>הווידג׳ט זוהה</strong>
                <div class="stitch-home-float-bar"><span></span></div>
            </div>

            <div class="stitch-home-product">
                <div class="stitch-home-product-frame">
                    <div class="stitch-home-product-sidebar">
                        <div class="stitch-avocado">
                            <span class="stitch-avocado-shell"></span>
                            <span class="stitch-avocado-core"></span>
                            <span class="stitch-avocado-seed"></span>
                        </div>
                        <div class="stitch-home-site-card">
                            <small>A11Y Bridge</small>
                            <strong>סביבת האתר</strong>
                            <p>קטע קוד, הצהרה ווידג׳ט</p>
                        </div>
                        <div class="stitch-home-site-list">
                            <span>קטע קוד קבוע</span>
                            <span>preset מרחוק</span>
                            <span>הצהרה בסיסית</span>
                        </div>
                    </div>

                    <div class="stitch-home-product-main">
                        <div class="stitch-home-product-top">
                            <div class="stitch-home-pill-row">
                                <span class="stitch-home-pill is-active">הווידג׳ט זוהה</span>
                                <span class="stitch-home-pill">הדשבורד חי</span>
                            </div>
                            <div class="stitch-home-chip">האתר מחובר</div>
                        </div>

                        <div class="stitch-home-stats-grid">
                            <article>
                                <small>קטע קוד</small>
                                <strong>פעם אחת</strong>
                                <p>מטמיעים פעם אחת בלבד.</p>
                            </article>
                            <article>
                                <small>וידג׳ט</small>
                                <strong>מנוהל מרחוק</strong>
                                <p>טקסטים ופעולות מתוך הדשבורד.</p>
                            </article>
                            <article>
                                <small>הצהרה</small>
                                <strong>נקודת פתיחה בסיסית</strong>
                                <p>מחובר למסך הציות.</p>
                            </article>
                            <article>
                                <small>סטטוס</small>
                                <strong>זוהה לאחרונה</strong>
                                <p>רואים שהשכבה חיה באתר.</p>
                            </article>
                        </div>

                        <div class="stitch-home-graph-panel">
                            <div class="stitch-home-graph-copy">
                                <small>פעילות</small>
                                <strong>התקנה, וידג׳ט והצהרה</strong>
                            </div>
                            <div class="stitch-home-graph-lines">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stitch-home-proof">
        <p>מותאם לכל סוג אתר — מטמיעים בכמה דקות</p>
        <div class="stitch-home-proof-row" aria-label="פלטפורמות נתמכות">
            <span class="stitch-platform-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="#21759B"/><path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm-1 17.93V15H9l3-9 3 9h-2v4.93A8 8 0 014 12c0-.34.02-.67.05-1H7l-1.5-4.5A8 8 0 0112 4c.35 0 .69.02 1.03.06L11 9h2l1 3h-3v7.93z" fill="#fff"/></svg>
                וורדפרס
            </span>
            <span class="stitch-platform-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect width="24" height="24" rx="4" fill="#96BF48"/><path d="M8.5 6.5c.28-1.64 1.64-2.9 3.34-2.9.56 0 1.1.14 1.56.4l.9-1.56A5.5 5.5 0 006.5 7l2 .5zm7.5 1l-1.5 1c.28.36.5.76.5 1.22 0 .28-.06.54-.16.78l1.86.48C16.88 10.44 17 9.98 17 9.5c0-.72-.18-1.4-.5-2zm-4 8.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 11 12 11s2.5 1.12 2.5 2.5S13.38 16 12 16zm0-7c-2.49 0-4.5 2.01-4.5 4.5S9.51 18 12 18s4.5-2.01 4.5-4.5S14.49 9 12 9z" fill="#fff"/></svg>
                שופיפיי
            </span>
            <span class="stitch-platform-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect width="24" height="24" rx="4" fill="#0C6EFC"/><path d="M7 8h10v8H7z" fill="none" stroke="#fff" stroke-width="1.5"/><path d="M10 8v8M14 8v8M7 12h10" stroke="#fff" stroke-width="1.5"/></svg>
                וויקס
            </span>
            <span class="stitch-platform-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect width="24" height="24" rx="4" fill="#5b5fca"/><path d="M7 8l5 8 5-8" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                אתר מותאם אישית
            </span>
        </div>
    </section>

    <section class="stitch-home-features" id="features">
        <div class="stitch-home-section-heading">
            <h2>הכלים הנכונים לשיפור הנגישות</h2>
            <p>במקום עוד תוסף מבולגן, מתחילים ממוצר ברור שאפשר להטמיע, לנהל ולעקוב אחריו.</p>
        </div>

        <div class="stitch-home-feature-grid">
            <article class="stitch-home-feature-card">
                <div class="stitch-home-feature-icon">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8 6l-4 6 4 6M16 6l4 6-4 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13 4l-2 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <h3>קטע קוד אחד, אתר חי</h3>
                <p>מטמיעים פעם אחת בלבד וממשיכים לנהל את שכבת הנגישות מתוך סביבת העבודה.</p>
                <div class="stitch-home-feature-visual stitch-home-feature-visual-bars">
                    <span></span><span></span><span></span>
                </div>
            </article>

            <article class="stitch-home-feature-card">
                <div class="stitch-home-feature-icon">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <h3>וידג׳ט, הצהרה וסטטוס</h3>
                <p>אותו דשבורד מחזיק את טקסטי הווידג׳ט, ההצהרה הבסיסית וחיווי ההטמעה.</p>
                <div class="stitch-home-feature-visual stitch-home-feature-visual-lines">
                    <span></span><span></span><span></span><span></span>
                </div>
            </article>

            <article class="stitch-home-feature-card">
                <div class="stitch-home-feature-icon">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6L12 2z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3>Brndini כשצריך יותר</h3>
                <p>כשצריך צמיחה, ביצועים או שכבת שירותים נוספת, ממשיכים ל־Brndini בלי לבלבל את המוצר עצמו.</p>
                <div class="stitch-home-feature-visual stitch-home-feature-visual-orbit">
                    <span></span><span></span><span></span>
                </div>
            </article>
        </div>
    </section>

    <section class="stitch-home-cta-band">
        <div class="stitch-home-cta-surface">
            <div class="stitch-home-cta-copy">
                <h2>אל תחכו שהנגישות תהפוך לבלגן. <span>הכניסו שכבה טכנית ברורה עוד היום.</span></h2>
                <div class="stitch-home-actions">
                    <a class="stitch-button stitch-button-primary" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                    <a class="stitch-button stitch-button-secondary" href="{{ route('brndini.home', $marketingParams) }}">ל־Brndini</a>
                </div>
            </div>
            <div class="stitch-home-cta-orbs" aria-hidden="true">
                <span class="is-ring"></span>
                <span class="is-glow"></span>
                <span class="is-pill"></span>
            </div>
        </div>
    </section>

    <section class="stitch-home-faq" id="faq">
        <div class="stitch-home-section-heading stitch-home-section-heading-centered">
            <h2>שאלות נפוצות</h2>
            <p>כל מה שצריך לדעת לפני שמטמיעים את A11Y Bridge.</p>
        </div>

        <div class="stitch-home-faq-list">
            <details>
                <summary>מה מקבלים בחינם?</summary>
                <div>
                    פתיחת חשבון, חיבור אתר, קטע קוד קבוע, וידג׳ט מנוהל, הצהרה בסיסית,
                    חיווי התקנה ותמיכה טכנית במערכת.
                </div>
            </details>
            <details>
                <summary>האם זה שירות נגישות?</summary>
                <div>
                    לא. זה כלי self-service. התמיכה היא טכנית בלבד, ו־Brndini נשארת שכבת שירותים עסקיים נפרדת.
                </div>
            </details>
            <details>
                <summary>כמה זמן לוקח להטמיע?</summary>
                <div>
                    ברוב האתרים מטמיעים קטע קוד אחד בתוך כמה דקות, ואז ממשיכים לנהל את השכבה מתוך הדשבורד.
                </div>
            </details>
        </div>
    </section>
@endsection
