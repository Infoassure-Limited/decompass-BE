@extends('layouts.private.main')
@section('title', 'Cyber Security Article')
@section('content')
  <div class="row">
    <div class="col-12">
      @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="row bg-gradient-primary">
            <div class="col-9 d-flex align-items-center">
              <h6 class="text-white text-capitalize ps-3">Resources - {{ ucwords($formated_type) }} </h6>
            </div>
            <div class="col-3 text-end">
              <x-responsive-nav-link class="btn bg-gradient-info" type="button" :href="route('resource_create', ['type' => $type])">
                Create
              </x-responsive-nav-link>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <form method="GET" action="{{ route('resource_index', ['type' => $type]) }}">
              <div class="row">
                <div class="col-md-9">
                  <div class="input-group input-group-outline mb-3">
                    <input class="form-control" type="text" name="search" placeholder="Search Here">
                  </div>
                </div>
                <div class="col-md-3">
                  <button class="btn bg-gradient-success" type="submit">Search</button>
                </div>
              </div>
            </form>

            @foreach ($resources as $resource)
              <div class="col-md-4">
                <div class="blog-item bg-light rounded overflow-hidden">
                  {{-- <div class="blog-img position-relative overflow-hidden">
                    <img class="img-fluid" src="img/blog-1.jpg" alt="">
                  </div> --}}
                  <div class="p-4">
                    <div class="d-flex mb-3">
                      <small><i
                          class="far fa-calendar-alt text-primary me-2"></i>{{ date('d-M-Y', strtotime($resource->created_at)) }}</small>
                    </div>
                    <h4 class="mb-3">{{ $resource->title }}</h4>
                    <p>{!! substr($resource->content, 0, 70) !!} ...</p>
                    <x-responsive-nav-link class="btn bg-gradient-primary" type="button" :href="route('resource_edit', ['resource' => $resource->id])">
                      Edit
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('resource_delete', ['resource' => $resource->id]) }}">
                      <input type="hidden" name="_method" value="DELETE">
                      @csrf
                      <x-responsive-nav-link class="btn bg-gradient-danger"
                        onclick="event.preventDefault(); deleteResource(this)"
                        type="button">Delete</x-responsive-nav-link>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
            {{ $resources->links() }}
          </div>
          {{-- <div class="table-responsive p-0">

            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($resources as $resource)
                  <tr>
                    <td>
                      {{ $resource->title }}
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-success">{{ $resource->published }}</span>
                    </td>
                    <td class="align-middle">
                      <x-responsive-nav-link class="btn bg-gradient-primary" type="button" :href="route('resource_edit', ['resource' => $resource->id])">
                        Edit
                      </x-responsive-nav-link>

                      <form method="POST" action="{{ route('resource_delete', ['resource' => $resource->id]) }}">
                        <input type="hidden" name="_method" value="DELETE">
                        @csrf
                        <x-responsive-nav-link class="btn bg-gradient-danger"
                          onclick="event.preventDefault(); deleteResource(this)"
                          type="button">Delete</x-responsive-nav-link>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

          </div> --}}
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    function deleteResource(form) {
      if (confirm('Are you sure you want to delete this resource?')) {

        form.closest('form').submit();
      }
    }
  </script>
@endsection
