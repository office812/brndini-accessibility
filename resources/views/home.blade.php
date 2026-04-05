@extends('layouts.app')

@php($title = 'A11Y Bridge | כלי חינמי להטמעת וידג׳ט נגישות')
@php($metaDescription = 'A11Y Bridge היא שכבה חינמית, שקטה וברורה להטמעת וידג׳ט נגישות: פתיחת חשבון, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית ותמיכה טכנית בלבד.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="stitch-home-hero">
        <div class="stitch-home-hero-copy">
            <div class="stitch-home-badge">
                <span class="stitch-home-badge-dot"></span>
                <span>חדש: A11Y Bridge 2.0</span>
            </div>
            <h1>
                <span>מטמיעים שכבת נגישות</span>
                <span class="is-accent">במהירות האור.</span>
            </h1>
            <p>
                פותחים חשבון, מחברים אתר, מטמיעים snippet אחד ומנהלים widget, statement
                וזיהוי התקנה מתוך סביבת עבודה שקטה אחת.
            </p>
            <div class="stitch-home-actions">
                <a class="stitch-button stitch-button-primary" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="stitch-button stitch-button-secondary" href="{{ route('how-it-works') }}">צפו בדמו מהיר</a>
            </div>
        </div>

        <div class="stitch-home-hero-visual" aria-hidden="true">
            <div class="stitch-home-hero-float">
                <div class="stitch-home-float-title">
                    <span class="stitch-home-float-dot"></span>
                    <span>Install check</span>
                </div>
                <strong>Widget detected</strong>
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
                            <strong>Site workspace</strong>
                            <p>snippet, statement ו־widget</p>
                        </div>
                        <div class="stitch-home-site-list">
                            <span>snippet קבוע</span>
                            <span>preset מרחוק</span>
                            <span>statement בסיסי</span>
                        </div>
                    </div>

                    <div class="stitch-home-product-main">
                        <div class="stitch-home-product-top">
                            <div class="stitch-home-pill-row">
                                <span class="stitch-home-pill is-active">widget זוהה</span>
                                <span class="stitch-home-pill">dashboard חי</span>
                            </div>
                            <div class="stitch-home-chip">Site connected</div>
                        </div>

                        <div class="stitch-home-stats-grid">
                            <article>
                                <small>Snippet</small>
                                <strong>פעם אחת</strong>
                                <p>מטמיעים פעם אחת בלבד.</p>
                            </article>
                            <article>
                                <small>Widget</small>
                                <strong>מנוהל מרחוק</strong>
                                <p>טקסטים ופעולות מתוך dashboard.</p>
                            </article>
                            <article>
                                <small>Statement</small>
                                <strong>נקודת פתיחה בסיסית</strong>
                                <p>מחובר למסך הציות.</p>
                            </article>
                            <article>
                                <small>Status</small>
                                <strong>זוהה לאחרונה</strong>
                                <p>רואים שהשכבה חיה באתר.</p>
                            </article>
                        </div>

                        <div class="stitch-home-graph-panel">
                            <div class="stitch-home-graph-copy">
                                <small>Activity</small>
                                <strong>Install, widget, statement</strong>
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
        <p>מתאים לאתרים שרוצים להתחיל מהר</p>
        <div class="stitch-home-proof-row" aria-label="קהלי יעד">
            <span>WordPress</span>
            <span>Shopify</span>
            <span>Wix</span>
            <span>Custom stack</span>
        </div>
    </section>

    <section class="stitch-home-features">
        <div class="stitch-home-section-heading">
            <h2>הכלים הנכונים לשיפור הנגישות</h2>
            <p>במקום עוד plugin מבולגן, מתחילים ממוצר ברור שאפשר באמת להטמיע ולעבוד איתו.</p>
        </div>

        <div class="stitch-home-feature-grid">
            <article class="stitch-home-feature-card">
                <div class="stitch-home-feature-icon">01</div>
                <h3>snippet אחד, אתר חי</h3>
                <p>מטמיעים פעם אחת בלבד וממשיכים לנהל את שכבת הנגישות מתוך סביבת העבודה.</p>
                <div class="stitch-home-feature-visual stitch-home-feature-visual-bars">
                    <span></span><span></span><span></span>
                </div>
            </article>

            <article class="stitch-home-feature-card">
                <div class="stitch-home-feature-icon">02</div>
                <h3>widget, statement ו־status</h3>
                <p>אותו dashboard מחזיק את טקסטי הווידג׳ט, ההצהרה הבסיסית וחיווי ההטמעה.</p>
                <div class="stitch-home-feature-visual stitch-home-feature-visual-lines">
                    <span></span><span></span><span></span><span></span>
                </div>
            </article>

            <article class="stitch-home-feature-card">
                <div class="stitch-home-feature-icon">03</div>
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
                <h2>אל תחכו שהנגישות תהפוך לעוד בלגן. <span>הכניסו שכבה טכנית ברורה עוד היום.</span></h2>
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

    <section class="stitch-home-faq">
        <div class="stitch-home-section-heading stitch-home-section-heading-centered">
            <h2>שאלות נפוצות</h2>
            <p>כל מה שצריך לדעת לפני שמטמיעים את A11Y Bridge.</p>
        </div>

        <div class="stitch-home-faq-list">
            <details>
                <summary>מה מקבלים בחינם?</summary>
                <div>
                    פתיחת חשבון, חיבור אתר, snippet קבוע, widget מנוהל, statement בסיסי,
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
                    ברוב האתרים מטמיעים snippet אחד בתוך כמה דקות, ואז ממשיכים לנהל את השכבה מתוך ה־dashboard.
                </div>
            </details>
        </div>
    </section>
@endsection
