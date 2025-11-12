@extends('layouts.app')
@section('title', ucwords($formated_type))
@section('page', 'resources')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h1 class="display-4 text-white animated zoomIn">{{ ucwords($formated_type) }}</h1>
        {{-- <a href="/" class="h5 text-white">Home</a> --}}
      </div>
    </div>
  </div>

  <!-- Blog Start -->
  <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-8">
          <!-- Blog Detail Start -->
          <div class="mb-5">
            {{-- <img class="img-fluid w-100 rounded mb-5" src="img/blog-1.jpg" alt=""> --}}
            @if (count($resource->media) > 0)
              <img class="img-fluid w-100 rounded mb-5" src="/{{ $resource->media[0]->image_link }}">
            @endif
            <h1 class="mb-4">{{ $resource->title }}</h1>
            {!! $resource->content !!}
          </div>
          <!-- Blog Detail End -->
          {!! $resource->video_link !!}
        </div>

        <!-- Sidebar Start -->
        <div class="col-lg-4">
          <!-- Search Form Start -->
          {{-- <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
            <div class="input-group">
              <input type="text" class="form-control p-3" placeholder="Keyword">
              <button class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
            </div>
          </div> --}}
          <!-- Search Form End -->

          <!-- Recent Post Start -->
          @if (count($resources) > 0)
            <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
              <div class="section-title section-title-sm position-relative pb-3 mb-4">
                <h3 class="mb-0">Related Articles</h3>
              </div>
              @foreach ($resources as $res)
                <div class="d-flex rounded overflow-hidden mb-3">
                  @if (count($res->media) > 0)
                    <img class="img-fluid" src="/{{ $res->media[0]->image_link }}" width="100">
                  @else
                    <img class="img-fluid" src="/img/logo.png" width="100">
                  @endif

                  <x-responsive-nav-link class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0"
                    :href="route('public_resource_details', ['resource' => $res->id])">
                    {{ $res->title }}
                  </x-responsive-nav-link>
                </div>
              @endforeach
            </div>
          @endif
          <!-- Recent Post End -->
        </div>
        <!-- Sidebar End -->
      </div>
    </div>
  </div>
  @include('pages.partials.subscribe')
  <!-- Blog End -->
@endsection
