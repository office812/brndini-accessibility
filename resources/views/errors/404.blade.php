@extends('layouts.app')
@php($title = '404 | A11Y Bridge')
@section('content')
<section class="error-page-shell">
    <div class="error-page-card">
        <span class="error-page-code">404</span>
        <h1>העמוד לא נמצא</h1>
        <p>הדף שחיפשת לא קיים או הועבר לכתובת אחרת.</p>
        <a class="primary-button" href="{{ route('home') }}">חזרה לדף הבית</a>
    </div>
</section>
@endsection
