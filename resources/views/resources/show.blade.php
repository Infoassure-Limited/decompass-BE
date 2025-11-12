@extends('layouts.private.main')
@section('title', 'Edit Resource')
@section('styles')
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <style>
    #editor-container {
      height: 375px;
    }
  </style>
@endsection
@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3">Update Resource</h6>
          </div>
        </div>
        <div class="col-12">
          <div class="card-body">
            <form class="text-start" method="POST"
              action="{{ route('resource_update', ['resource' => $resource->id]) }}">
              <input type="hidden" name="_method" value="PUT">
              @csrf
              <div class="input-group input-group-outline mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="title" value="{{ $resource->title }}">
                <input type="hidden" class="form-control" name="type" value="{{ $resource->content_type }}">
              </div>
              <div id="editor-container">
                {!! $resource->content !!}
              </div>
              <textarea name="content" style="display: none" id="hiddenArea">{{ $resource->content }}</textarea>
              <div class="text-center">
                <button type="submit" class="btn btn-lg bg-gradient-success btn-lg w-100 mt-4 mb-0">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <!-- Include the Quill library -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

  <!-- Initialize Quill editor -->
  <script>
    var quill = new Quill('#editor-container', {
      //   modules: {
      //     toolbar: '#toolbar'
      //   },
      placeholder: 'Type your content here...',
      theme: 'snow',
    });
    quill.on('text-change', function(delta, oldDelta, source) {

      // console.log(quill.container.firstChild.innerHTML)

      document.getElementById('hiddenArea').value = quill.container.firstChild.innerHTML
      // $('#detail').val(quill.container.firstChild.innerHTML);
    });
    // $("#formSubmit").on("submit", function() {
    //   $("#hiddenArea").val(JSON.stringify(editor.getContents()));
    // })
  </script>
@endsection
