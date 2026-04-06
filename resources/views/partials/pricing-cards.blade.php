<div class="pricing-section-shell">
    <div class="pricing-grid">
        <article class="plan-card">
            <span class="status-pill is-neutral">חינמי לצמיתות</span>
            <strong>כלי self-service חינמי לאתר שלך</strong>
            <p>פותחים חשבון, מוסיפים אתר, מטמיעים קוד קבוע, ומנהלים את הווידג׳ט, ההצהרה והבקרה הטכנית ממקום אחד.</p>
            <div class="plan-price-line">
                <span class="plan-price-amount">₪0</span>
                <span class="plan-price-period">ללא עלות, לתמיד</span>
            </div>
            <ul class="plan-card-list">
                <li>
                    <svg class="plan-check-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    וידג׳ט נגישות, קוד הטמעה קבוע וניהול מרחוק מתוך הדשבורד
                </li>
                <li>
                    <svg class="plan-check-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    הצהרת נגישות בסיסית, זיהוי התקנה, סטטוס אתר ובקרה טכנית
                </li>
                <li>
                    <svg class="plan-check-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    מתאים לבעלי אתרים, סוכנויות וצוותים שרוצים להתחיל מהר בלי חיכוך
                </li>
            </ul>
            <div class="hero-action-row">
                <a class="ghost-button button-link" href="{{ route('register.show') }}">להתחיל בחינם</a>
                <a class="ghost-button button-link" href="{{ route('free-tool') }}">מה כלול בחינם</a>
            </div>
        </article>

        <article class="plan-card plan-card-current">
            <div class="plan-card-recommended-badge">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6L12 2z" fill="currentColor"/></svg>
                מומלץ לצמיחה
            </div>
            <span class="status-pill is-good">שירותים אופציונליים</span>
            <strong>Brndini נכנסת כשצריך צמיחה, תשתית ותפעול</strong>
            <p>הכלי נשאר חינמי. כשצריך תוצאה עסקית רחבה יותר, אפשר להשאיר פנייה מסודרת לשירותים של Brndini.</p>
            <div class="plan-price-line">
                <span class="plan-price-amount">לפי צורך</span>
                <span class="plan-price-period">הצעת התאמה אישית</span>
            </div>
            <ul class="plan-card-list">
                <li>
                    <svg class="plan-check-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    אחסון, תחזוקת אתר, שדרוג אתר קיים ודפי נחיתה
                </li>
                <li>
                    <svg class="plan-check-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    SEO, קמפיינים, אוטומציות ומנועי צמיחה נוספים של Brndini
                </li>
                <li>
                    <svg class="plan-check-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    נכנסים רק אם אתה רוצה, בלי לבלבל את זה עם הכלי החינמי
                </li>
            </ul>
            <a class="primary-button button-link" href="{{ route('brndini.services', request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])) }}">לשירותי Brndini</a>
        </article>
    </div>
</div>
