@extends('layouts.app')
@section('title', 'Features')
@section('feature_active', 'active')
@section('content')
<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="display-3 mb-2 wow fadeInDown">FAQ</h1>
    </div>
</div>
@include('pages.partials.faq')
<!-- About End -->
@endsection