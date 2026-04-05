@extends('layouts.app')

@php($title = 'A11Y Bridge | כלי חינמי להטמעת וידג׳ט נגישות')
@php($metaDescription = 'A11Y Bridge היא שכבה חינמית, שקטה וברורה להטמעת וידג׳ט נגישות: פתיחת חשבון, קוד הטמעה קבוע, דשבורד, הצהרה בסיסית ותמיכה טכנית בלבד.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="stitch-home-hero">
        <div class="stitch-home-hero-copy">
            <div class="stitch-home-badge">
                <span class="stitch-home-badge-dot"></span>
                <span>A11Y Bridge 2.0</span>
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
                <a class="stitch-button stitch-button-primary" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="stitch-button stitch-button-secondary" href="{{ route('how-it-works') }}">איך זה עובד</a>
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
        <p>מותאם לאתרים שרוצים לעלות מהר לאוויר</p>
        <div class="stitch-home-proof-row" aria-label="קהלי יעד">
            <span>וורדפרס</span>
            <span>שופיפיי</span>
            <span>וויקס</span>
            <span>אתר מותאם אישית</span>
        </div>
    </section>

    <section class="stitch-home-features" id="features">
        <div class="stitch-home-section-heading">
            <h2>הכלים הנכונים לשיפור הנגישות</h2>
            <p>במקום עוד תוסף מבולגן, מתחילים ממוצר ברור שאפשר להטמיע, לנהל ולעקוב אחריו.</p>
        </div>

        <div class="stitch-home-feature-grid">
            <article class="stitch-home-feature-card">
                <div class="stitch-home-feature-icon">01</div>
                <h3>קטע קוד אחד, אתר חי</h3>
                <p>מטמיעים פעם אחת בלבד וממשיכים לנהל את שכבת הנגישות מתוך סביבת העבודה.</p>
                <div class="stitch-home-feature-visual stitch-home-feature-visual-bars">
                    <span></span><span></span><span></span>
                </div>
            </article>

            <article class="stitch-home-feature-card">
                <div class="stitch-home-feature-icon">02</div>
                <h3>וידג׳ט, הצהרה וסטטוס</h3>
                <p>אותו דשבורד מחזיק את טקסטי הווידג׳ט, ההצהרה הבסיסית וחיווי ההטמעה.</p>
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
