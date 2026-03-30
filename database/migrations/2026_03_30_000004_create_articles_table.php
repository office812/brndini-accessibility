<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 280);
            $table->text('meta_description')->nullable();
            $table->longText('body');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        $authorId = DB::table('users')->orderBy('id')->value('id');

        if ($authorId) {
            $now = now();

            DB::table('articles')->insert([
                [
                    'user_id' => $authorId,
                    'title' => 'תוסף נגישות לאתר: מה הוא באמת עושה ומה הוא לא עושה',
                    'slug' => 'website-accessibility-widget-what-it-really-does',
                    'excerpt' => 'מדריך שמסביר מה תוסף נגישות או widget hosted יכולים לתת, ואיפה עדיין צריך תיקוני קוד, תוכן ובדיקות ידניות.',
                    'meta_description' => 'מה תוסף נגישות לאתר באמת עושה, איך widget hosted משתלב בתהליך, ומה עדיין חייבים לבדוק ידנית כדי להתקדם ל-WCAG.',
                    'body' => "אם אתם מחפשים תוסף נגישות לאתר, חשוב להבין את התמונה המלאה. שכבת widget יכולה לתת העדפות תצוגה, גישה נוחה להצהרת נגישות, ושפה ניהולית מסודרת מול הלקוח או הארגון.\n\nהיא לא מחליפה תיקוני קוד, היררכיית כותרות תקינה, טפסים נגישים, נגישות מקלדת, איכות תוכן, או בדיקות עם טכנולוגיות מסייעות. לכן הפתרון הנכון הוא לא הבטחה של קסם, אלא שילוב בין שכבת widget hosted, dashboard ניהול, audit מקצועי ותהליך remediation.\n\nהגישה הזאת נותנת גם חוויה טובה יותר ללקוח. במקום להבטיח הבטחה מסוכנת, אפשר להגיד בדיוק מה המערכת מספקת: ניהול מרכזי, קוד הטמעה קבוע, עדכונים אוטומטיים, statement access, והעדפות תצוגה שנמשכות מתוך השרת.\n\nכדי להתקדם בצורה רצינית, כדאי לבחור פלטפורמה שמאפשרת גם governance וגם workflow מסודר לצעדי ההמשך.",
                    'published_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'user_id' => $authorId,
                    'title' => 'איך לשפר נגישות אתר בלי להחליף קוד הטמעה בכל שינוי',
                    'slug' => 'how-to-improve-website-accessibility-with-hosted-config',
                    'excerpt' => 'למה קוד הטמעה קבוע ו-hosted config הם מודל הרבה יותר חזק עבור סוכנויות, בעלי אתרים ומנהלי מוצר.',
                    'meta_description' => 'למה כדאי לעבוד עם קוד הטמעה קבוע ל-widget נגישות, ואיך hosted configuration חוסך עבודה ומאפשר שליטה טובה יותר.',
                    'body' => "אחת הבעיות בפתרונות רבים היא שכל שינוי קטן דורש שוב התעסקות עם קוד. בפלטפורמה hosted המודל שונה: האתר מדביק snippet אחד בלבד, ומאותו רגע ההגדרות נמשכות מהשרת לפי site key.\n\nזה אומר שאפשר לשנות צבע, מיקום, שפה, label, statement URL ואפילו חלק מהפיצ'רים, בלי לגעת שוב בהטמעה עצמה. עבור בעלי אתרים זה פשוט יותר. עבור סוכנויות זה scalable יותר. עבור המוצר עצמו זה מאפשר dashboard אמיתי ולא רק סקריפט.\n\nהיתרון הזה גם חשוב מבחינת governance. אפשר להוכיח מי עדכן, מה עודכן, ואיך החוויה באתר נשארת עקבית בין פרויקטים שונים.\n\nאם המטרה היא מוצר ברמה גבוהה, hosted configuration עם site key הוא הבסיס הנכון.",
                    'published_at' => $now->copy()->subDay(),
                    'created_at' => $now->copy()->subDay(),
                    'updated_at' => $now->copy()->subDay(),
                ],
                [
                    'user_id' => $authorId,
                    'title' => 'הצהרת נגישות באתר: למה חשוב לחבר אותה ל-widget ול-dashboard',
                    'slug' => 'accessibility-statement-best-practices-for-websites',
                    'excerpt' => 'הצהרת נגישות טובה לא עומדת לבד. היא צריכה להיות חלק מחוויית מוצר מסודרת עם גישה ברורה ועדכון פשוט.',
                    'meta_description' => 'למה חשוב לחבר הצהרת נגישות ל-widget ול-dashboard, ואיך זה עוזר ללקוח, לארגון ולתהליך הניהול השוטף.',
                    'body' => "הצהרת נגישות היא לא רק מסמך שמעלים לאתר. היא חלק מחוויית המוצר. המשתמש צריך להגיע אליה בקלות, והארגון צריך לדעת לעדכן אותה בלי לחפש קבצים או להחליף קוד באתר.\n\nכאשר הצהרת הנגישות מחוברת ל-dashboard ול-widget, אפשר לוודא שהיא זמינה בלחיצה אחת מתוך הפאנל. זה תומך בשקיפות, בבהירות ובחוויה נוחה יותר למשתמש.\n\nבנוסף, כשההצהרה יושבת בתוך מערכת hosted, אפשר לשלב אותה עם מסלול השירות, audit status, next steps ו-governance messaging מדויק. כך המוצר כולו מרגיש כמו פלטפורמה מקצועית, לא אוסף של חלקים.\n\nבסביבה שבה רוצים גם אמינות וגם פשטות, זה פרט קטן שעושה הבדל גדול.",
                    'published_at' => $now->copy()->subDays(2),
                    'created_at' => $now->copy()->subDays(2),
                    'updated_at' => $now->copy()->subDays(2),
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
