<div class="pricing-section-shell">
    <div class="pricing-grid">
        <article class="plan-card">
            <span class="status-pill is-neutral">חינם</span>
            <strong>כלי self-service חינמי לאתר שלך</strong>
            <p>פותחים חשבון, מוסיפים אתר, מטמיעים קוד קבוע, ומנהלים את הווידג׳ט, ההצהרה והבקרה הטכנית ממקום אחד.</p>
            <div class="plan-price-line">ללא עלות</div>
            <ul class="plan-card-list">
                <li>ווידג׳ט נגישות, קוד הטמעה קבוע וניהול מרחוק מתוך הדשבורד</li>
                <li>הצהרת נגישות בסיסית, זיהוי התקנה, סטטוס אתר ובקרה טכנית</li>
                <li>מתאים לבעלי אתרים, סוכנויות וצוותים שרוצים להתחיל מהר בלי חיכוך</li>
            </ul>
            <a class="ghost-button button-link" href="{{ route('register.show') }}">להתחיל בחינם</a>
        </article>

        <article class="plan-card plan-card-current">
            <span class="status-pill is-good">שירותים אופציונליים</span>
            <strong>Brndini נכנסת כשצריך צמיחה, תשתית ותפעול</strong>
            <p>הכלי נשאר חינמי. כשצריך תוצאה עסקית רחבה יותר, אפשר להשאיר פנייה מסודרת לשירותים של Brndini.</p>
            <div class="plan-price-line">לפי צורך והצעת התאמה</div>
            <ul class="plan-card-list">
                <li>אחסון, תחזוקת אתר, שדרוג אתר קיים ודפי נחיתה</li>
                <li>SEO, קמפיינים, אוטומציות ומנועי צמיחה נוספים של Brndini</li>
                <li>נכנסים רק אם אתה רוצה, בלי לבלבל את זה עם הכלי החינמי או עם תמיכה טכנית</li>
            </ul>
            <a class="primary-button button-link" href="{{ route('brndini.services', request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])) }}">לשירותי Brndini</a>
        </article>
    </div>
</div>
