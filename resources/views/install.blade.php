@extends('layouts.app')

@php($title = 'התקנה והתאמת הווידג׳ט | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'install'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>התקנה והתאמת הווידג׳ט</h1>
            </section>

            <section class="domain-card">
                <h2>קוד הטמעה</h2>
                <div class="domain-code-block">
                    <code id="install-embed-code">{{ $embedCode }}</code>
                    <button class="copy-button" type="button" data-copy-target="install-embed-code">העתק קוד הטמעה</button>
                </div>
                <p class="panel-intro">הטמע פעם אחת לפני <code>&lt;/body&gt;</code>. מכאן כל שינוי בעיצוב או בהגדרות נמשך מרחוק.</p>
            </section>

            <section class="domain-card">
                <h2>רשימת התקנה</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>שלב 1</span>
                        <strong>העתק את קוד ההטמעה המנוהל</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>שלב 2</span>
                        <strong>הדבק באזור הסקריפטים או לפני תגית הסגירה של body</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>שלב 3</span>
                        <strong>רענן את האתר וודא שמיקום הווידג׳ט תקין</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>שלב 4</span>
                        <strong>שנה הגדרה אחת וודא שהסנכרון חי</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card">
                <h2>התאמת הווידג׳ט</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>מיקום</span>
                        <strong>{{ $widget['position'] === 'bottom-left' ? 'שמאל למטה' : 'ימין למטה' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>שפה</span>
                        <strong>עברית</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>טקסט כפתור</span>
                        <strong>{{ $widget['label'] }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>פקדים פעילים</span>
                        <strong>{{ $featureCount }} פעילים</strong>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
