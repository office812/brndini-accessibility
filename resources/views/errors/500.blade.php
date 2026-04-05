@extends('layouts.app')
@php($title = '500 | A11Y Bridge')
@section('content')
<section class="error-page-shell">
    <div class="error-page-card">
        <span class="error-page-code">500</span>
        <h1>משהו השתבש</h1>
        <p>אנחנו על זה ומטפלים בבעיה. אפשר לנסות שוב בעוד כמה רגעים.</p>
        <a class="primary-button" href="{{ route('home') }}">חזרה לדף הבית</a>
    </div>
</section>
@endsection
