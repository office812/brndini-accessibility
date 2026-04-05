@extends('layouts.app')

@php($title = 'המודל של A11Y Bridge | כלי חינמי ושירותי Brndini')
@php($metaDescription = 'המודל של A11Y Bridge פשוט: הכלי החינמי נשאר self-service, ושירותי Brndini נשארים שכבה עסקית נפרדת ואופציונלית.')

@section('content')
    <section class="public-stage">
        <div class="public-stage-copy">
            <p class="eyebrow">המודל</p>
            <h1>הכלי נשאר חינמי. Brndini נשארת שכבת שירותים עסקיים נפרדת.</h1>
            <p class="hero-text hero-text-lead">
                אין כאן “חינם מול פרימיום”. יש שכבת מוצר חינמית, ברורה ושימושית, ולצידה
                שכבת שירותים אופציונלית לעסקים שרוצים תשתית, צמיחה או שדרוג רחב יותר.
            </p>
            <div class="public-cta-row">
                <a class="primary-button button-link" href="{{ route('register.show') }}">פתיחת חשבון חינמי</a>
                <a class="ghost-button button-link" href="{{ route('brndini.services') }}">שירותי Brndini</a>
            </div>
        </div>
    </section>

    <section class="public-shell-section" data-reveal>
        <div class="section-heading section-heading-center">
            <p class="eyebrow">שתי שכבות. לא יותר.</p>
            <h2>מוצר חינמי להתחלה, ושירותים עסקיים למי שבאמת צריך אותם.</h2>
        </div>

        @include('partials.pricing-cards')
    </section>
@endsection
