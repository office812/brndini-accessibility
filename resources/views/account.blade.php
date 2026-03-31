@extends('layouts.app')

@php($title = 'Plan and Payments | A11Y Bridge')

@section('content')
    <section class="domain-shell">
        @include('partials.domain-sidebar', ['activeSection' => 'account'])

        <div class="domain-shell-main">
            <section class="domain-shell-header">
                <h1>Plan and payments</h1>
            </section>

            <section class="domain-card">
                <h2>{{ parse_url($site->domain, PHP_URL_HOST) ?: $site->domain }}</h2>

                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>Plan</span>
                        <strong>{{ $currentPlan['name'] }} · Yearly</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Status</span>
                        <strong><span class="status-pill is-good">Active</span></strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Service mode</span>
                        <strong>{{ $serviceModeLabel }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Public site key</span>
                        <strong><code>{{ $site->public_key }}</code></strong>
                    </div>
                </div>
            </section>

            <section class="domain-card">
                <h2>Advanced features</h2>

                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>Google Analytics</span>
                        <strong>Not available</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Managed statement</span>
                        <strong>{{ $statementStatus === 'connected' ? 'Enabled' : 'Needs setup' }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Hosted widget sync</span>
                        <strong>Enabled</strong>
                    </div>
                </div>
            </section>

            <section class="domain-card" id="license-owner">
                <h2>License owner info</h2>

                <div class="domain-info-list">
                    <div class="domain-info-row">
                        <span>Company</span>
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Contact email</span>
                        <strong>{{ $user->contact_email }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Workspace</span>
                        <strong>{{ $site->site_name }}</strong>
                    </div>
                    <div class="domain-info-row">
                        <span>Primary domain</span>
                        <strong>{{ $site->domain }}</strong>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
