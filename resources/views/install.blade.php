@extends('layouts.app')

@php($title = 'Install and Customize Widget | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'install'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>Install and customize widget</h1>
            </section>

            <section class="domain-card">
                <h2>Install snippet</h2>
                <div class="domain-code-block">
                    <code id="install-embed-code">{{ $embedCode }}</code>
                    <button class="copy-button" type="button" data-copy-target="install-embed-code">העתק קוד הטמעה</button>
                </div>
                <p class="panel-intro">הטמע פעם אחת לפני <code>&lt;/body&gt;</code>. מכאן כל שינוי בעיצוב או בהגדרות נמשך מרחוק.</p>
            </section>

            <section class="domain-card">
                <h2>Install checklist</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>Step 1</span>
                        <strong>Copy the hosted snippet</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Step 2</span>
                        <strong>Paste into your global site footer</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Step 3</span>
                        <strong>Refresh and verify widget position</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Step 4</span>
                        <strong>Change one setting and confirm live sync</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card">
                <h2>Customize widget</h2>
                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>Position</span>
                        <strong>{{ $widget['position'] === 'bottom-left' ? 'Bottom left' : 'Bottom right' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Language</span>
                        <strong>{{ $widget['language'] === 'en' ? 'English' : 'Hebrew' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Button label</span>
                        <strong>{{ $widget['label'] }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Feature controls</span>
                        <strong>{{ $featureCount }} enabled</strong>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
