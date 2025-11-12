<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;
use App\Models\Website\Resource;
use App\Models\Website\ResourceMedia;
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    //
    public function index(Request $request, $type)
    {
        $resource_query = Resource::query();
        if (isset($request->search) && $request->search != '') {
            $search = $request->search;
            $resource_query->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('content', 'LIKE', '%' . $search . '%');
        }
        // $type = $request->type;
        $resources = $resource_query->with('media')->where('content_type', $type)->orderBy('id', 'DESC')->paginate(10);
        $resource = $resources[0];
        $formated_type = str_replace('_', ' ', $type);
        // return response()->json(compact('cyberSecurityArticle'), 200);
        return view('resources.public.index', compact('resources', 'resource', 'type', 'formated_type'));
    }
    public function private(Request $request, $type)
    {
        $resource_query = Resource::query();
        if (isset($request->search) && $request->search != '') {
            $search = $request->search;
            $resource_query->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('content', 'LIKE', '%' . $search . '%');
        }
        // $type = $request->type;
        $resources = $resource_query->where('content_type', $type)->paginate(10);

        $formated_type = str_replace('_', ' ', $type);
        // return response()->json(compact('cyberSecurityArticle'), 200);
        return view('resources.index', compact('resources', 'type', 'formated_type'));
    }
    public function show(Request $request, Resource $resource)
    {
        $type = $resource->content_type;
        $formated_type = str_replace('_', ' ', $type);
        $resource = $resource->with('media')->find($resource->id);
        $resources = Resource::with('media')->where('content_type', $type)->where('id', '!=', $resource->id)->paginate(10);
        return view('resources.public.detail', compact('resources', 'resource', 'type', 'formated_type'));
    }
    private function uploadNewFile($request, $resource_id)
    {
        if ($request->hasFile('media')) {
            $allowedfileExtension = ['jpg', 'png', 'jpeg'];
            $files = $request->file('media');
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $folder = "media";
                    $avatar = $file->storeAs($folder, $name, 'public');

                    $filename = 'storage/' . $avatar;

                    ResourceMedia::create([
                        'resource_id' => $resource_id,
                        'image_link' => $filename
                    ]);
                }
            }
        }
    }
    public function store(Request $request)
    {
        // return $this->uploadNewFile($request, 3);
        $type = $request->type;
        $resource = new Resource();
        $resource->title = $request->title;
        $resource->content = $request->content;
        $resource->content_type = $type;
        $resource->video_link = $request->video_link;
        $resource->save();
        $this->uploadNewFile($request, $resource->id);

        return redirect()->route('resource_index', ['type' => $type])->with('status', 'Action Successful');
        ;
    }
    public function create(Request $request, $type)
    {
        return view('resources.create', compact('type'));
    }
    public function edit(Request $request, Resource $resource)
    {
        $resource = $resource->with('media')->find($resource->id);
        return view('resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $type = $request->type;
        $resource->title = $request->title;
        $resource->content = $request->content;
        $resource->content_type = $type;
        $resource->video_link = $request->video_link;
        $resource->save();
        $this->uploadNewFile($request, $resource->id);
        return redirect()->route('resource_index', ['type' => $type]);
    }

    public function destroy(Request $request, Resource $resource)
    {
        $type = $resource->content_type;
        $resource->delete();
        return redirect()->route('resource_index', ['type' => $type]);
    }

    public function services(Request $request, $serviceType)
    {
        $view_file = str_replace('-', '_', $serviceType);

        return view('pages.services.' . $view_file);
    }

    public function destroyMedia(ResourceMedia $media)
    {
        // $file = str_replace($media->image_link);
        unlink(portalPulicPath($media->image_link));

        $media->delete();

        return 'deleted';
    }
}
