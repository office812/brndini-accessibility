@extends('layouts.app')

@php($title = 'שאלות נפוצות | A11Y Bridge')
@php($metaDescription = 'תשובות קצרות וברורות על A11Y Bridge: מה הכלי החינמי נותן, מה נשאר טכני בלבד, ואיך Brndini נכנסת רק כשצריך שכבת שירות נפרדת.')
@php($marketingParams = array_filter(request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_url'])))

@section('content')
    <section class="public-stage">
        <div class="public-stage-copy">
            <p class="eyebrow">FAQ</p>
            <h1>התשובות הקצרות לפני שמתחילים.</h1>
            <p class="hero-text hero-text-lead">
                מה הכלי נותן, מה לא, איך עובדת ההטמעה, ואיפה עובר הגבול בין A11Y Bridge
                לבין שירותי Brndini. בלי משפטי שיווק ארוכים ובלי בלבול.
            </p>
            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('brndini.services', $marketingParams) }}">שירותי Brndini</a>
            </div>
        </div>
    </section>

    <section class="public-shell-section">
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שאלות ותשובות</p>
            <h2>מה שצריך להבין לפני שמטמיעים.</h2>
        </div>

        @include('partials.faq-items')
    </section>
@endsection
