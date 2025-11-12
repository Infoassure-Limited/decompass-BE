<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetType;
use OpenAI\Laravel\Facades\OpenAI;

class AssetsController extends Controller
{
    public function __construct(Request $httpRequest)
    {
        parent::__construct($httpRequest);
        $this->middleware(function ($request, $next) {

            $this->autoGenerateAndSaveAssetTypes();
            return $next($request);
        });


    }

    private function autoGenerateAndSaveAssetTypes()
    {
        // Only seed when there are no global asset types or no types for the current client
        $client = $this->getClient();
        if (!$client) {
            return;
        }

        $globalCount = AssetType::whereNull('client_id')->count();
        $clientCount = AssetType::where('client_id', $client->id)->count();

        if ($globalCount < 1 && $clientCount < 1) {
            $asset_types = $this->generateAssetTypes();
            if (!is_array($asset_types)) {
                return;
            }

            foreach ($asset_types as $asset_type) {
                $name = trim(ucwords((string) ($asset_type->category ?? $asset_type)));
                if ($name === '')
                    continue;
                AssetType::updateOrCreate([
                    'name' => $name,
                    'client_id' => null,
                ], [
                    'name' => $name,
                ]);
            }
        }
    }

    private function generateAssetTypes()
    {
        $filename = portalPulicPath('asset_types.json');
        if (!is_readable($filename)) {
            return [];
        }
        $file_content = @file_get_contents($filename);
        if ($file_content === false) {
            return [];
        }
        $decoded = json_decode($file_content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }
        return is_array($decoded) ? $decoded : [];
    }

    //
    public function fetchAssetTypes(Request $request)
    {
        $client_id = $this->getClient()->id;
        $asset_types = AssetType::where(function ($q) use ($client_id) {
            $q->where('client_id', $client_id)
                ->orWhereNull('client_id');
        })
            ->select('id', 'name', 'client_id')
            ->orderBy('name')
            ->get();
        return response()->json(compact('asset_types'), 200);
    }

    public function fetchAssets(Request $request)
    {
        $client_id = $this->getClient()->id;
        $request->validate([
            'name' => 'nullable|string|max:255',
        ]);
        $query = Asset::query()->join('asset_types', 'asset_types.id', '=', 'assets.asset_type_id')
            ->where('assets.client_id', $client_id);

        if ($request->filled('name')) {
            $name = trim($request->name);
            $query = $query->where(function ($q) use ($name) {
                $q->where('assets.name', 'LIKE', "%{$name}%")
                    ->orWhere('asset_types.name', 'LIKE', "%{$name}%");
            });
        }
        $assets = $query->select('assets.*', 'assets.id as id', 'assets.name as name', 'asset_types.name as asset_type_name')
            ->limit(5000) // guard against accidental huge result sets
            ->get()
            ->groupBy('asset_type_name');
        return response()->json(compact('assets'), 200);
    }

    public function saveAssetTypes(Request $request)
    {
        $client_id = $this->getClient()->id;
        $data = $request->validate([
            'names' => 'required|array|min:1|max:200',
            'names.*' => 'required|string|max:100'
        ]);
        $names_array = array_unique(array_map(function ($n) {
            return trim(ucwords((string) $n));
        }, $data['names']));
        foreach ($names_array as $name) {
            if ($name === '')
                continue;
            AssetType::firstOrCreate([
                'client_id' => $client_id,
                'name' => $name
            ]);
        }
        return response()->json(['message' => 'Successful'], 200);
    }

    public function saveAssets(Request $request)
    {
        $client_id = $this->getClient()->id;
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'asset_type_id' => 'required|integer|exists:asset_types,id',
            'location' => 'nullable|string|max:255',
            'classification' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:2000'
        ]);

        // ensure asset type is either global or belongs to this client
        $assetType = AssetType::findOrFail($data['asset_type_id']);
        if (!is_null($assetType->client_id) && $assetType->client_id !== $client_id) {
            abort(403, 'Invalid asset type for client');
        }

        $payload = [
            'client_id' => $client_id,
            'asset_type_id' => $assetType->id,
            'name' => trim($data['name']),
            'description' => $data['description'] ?? null,
            'location' => $data['location'] ?? null,
            'classification' => $data['classification'] ?? null,
            'purpose' => $data['purpose'] ?? null,
        ];

        Asset::firstOrCreate([
            'client_id' => $client_id,
            'name' => $payload['name']
        ], $payload);

        return response()->json(['message' => 'Successful'], 200);
    }
    private function updateAssetTypeAssetSamples($asset_type_id, $new_asset)
    {
        $asset_type = AssetType::find($asset_type_id);
        $old_asset_samples = $asset_type->asset_samples;
        $old_asset_samples[] = $new_asset;
        $asset_type->asset_samples = array_unique($old_asset_samples);
        $asset_type->save();
        return response()->json(['message' => 'Successful'], 200);
    }
    public function updateAssetType(Request $request, AssetType $asset_type)
    {
        $client = $this->getClient();
        // Only allow editing of client's own types or refuse editing global types
        if (!is_null($asset_type->client_id) && $asset_type->client_id !== $client->id) {
            abort(403, 'Forbidden');
        }
        $data = $request->validate([
            'name' => 'required|string|max:100'
        ]);
        $asset_type->name = trim($data['name']);
        $asset_type->save();
        return response()->json(['message' => 'Successful'], 200);
    }
    public function updateAsset(Request $request, Asset $asset)
    {
        $client = $this->getClient();
        if ($asset->client_id !== $client->id) {
            abort(403, 'Forbidden');
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string|max:2000',
            'asset_type_id' => 'sometimes|required|integer|exists:asset_types,id',
            'location' => 'sometimes|nullable|string|max:255',
            'classification' => 'sometimes|nullable|string|max:255',
            'purpose' => 'sometimes|nullable|string|max:2000',
            'owner_id' => 'sometimes|nullable|integer|exists:users,id',
        ]);

        if (isset($data['asset_type_id'])) {
            $assetType = AssetType::findOrFail($data['asset_type_id']);
            if (!is_null($assetType->client_id) && $assetType->client_id !== $client->id) {
                abort(403, 'Invalid asset type for client');
            }
            $asset->asset_type_id = $assetType->id;
        }

        foreach (['name', 'description', 'location', 'classification', 'purpose', 'owner_id'] as $field) {
            if (array_key_exists($field, $data)) {
                $asset->$field = $data[$field];
            }
        }

        $asset->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    public function setAssetOwner(Request $request, Asset $asset)
    {
        $client = $this->getClient();
        if ($asset->client_id !== $client->id) {
            abort(403, 'Forbidden');
        }
        $data = $request->validate([
            'owner_id' => 'required|integer|exists:users,id'
        ]);
        $owner = User::findOrFail($data['owner_id']);
        // optional: ensure owner belongs to same client (if app enforces per-client users)
        if (method_exists($owner, 'client_id') && isset($owner->client_id) && $owner->client_id !== $client->id) {
            abort(403, 'Owner not in client scope');
        }
        $asset->owner_id = $owner->id;
        $asset->save();
        return response()->json(compact('asset'), 200);
    }

    public function deleteAssetType(AssetType $assetType)
    {
        $client = $this->getClient();
        // Prevent deleting global types via client endpoints
        if (is_null($assetType->client_id) || ($assetType->client_id !== $client->id)) {
            abort(403, 'Forbidden');
        }

        // prevent deletion if there are assets referencing this type
        $dependentCount = Asset::where('asset_type_id', $assetType->id)->count();
        if ($dependentCount > 0) {
            abort(409, 'Asset type has dependent assets; remove or reassign them first');
        }

        $actor = $this->getUser();
        $title = "Asset Type Deletion";
        $description = "$actor->name deleted $assetType->name from the list of asset types.";
        $this->auditTrailEvent($title, $description);
        $assetType->delete();
        return response()->json([], 204);
    }
    public function deleteAsset(Asset $asset)
    {
        $client = $this->getClient();
        if ($asset->client_id !== $client->id) {
            abort(403, 'Forbidden');
        }
        $actor = $this->getUser();
        $title = "Asset Deletion";
        $description = "$actor->name deleted $asset->name from the list of assets.";
        $this->auditTrailEvent($title, $description);

        $asset->delete();
        return response()->json([], 204);
    }

    public function uploadBulkAssets(Request $request)
    {
        $client_id = $this->getClient()->id;
        $data = $request->validate([
            'asset_type_id' => 'required|integer|exists:asset_types,id',
            'assets' => 'required|array|min:1|max:1000',
            'assets.*.ASSET_NAME' => 'required|string|max:255',
            'assets.*.ASSET_DESCRIPTION' => 'nullable|string|max:2000',
            'assets.*.ASSET_LOCATION' => 'nullable|string|max:255',
            'assets.*.ASSET_CLASSIFICATION' => 'nullable|string|max:255',
            'assets.*.PURPOSE_OF_ASSET' => 'nullable|string|max:2000',
            'assets.*.INFORMATION_STORED' => 'nullable|string|max:2000',
            'assets.*.ASSET_OWNER' => 'required|string|max:255',
        ]);

        $asset_type = AssetType::findOrFail($data['asset_type_id']);
        if (!is_null($asset_type->client_id) && $asset_type->client_id !== $client_id) {
            abort(403, 'Invalid asset type for client');
        }

        $unsaved_data = [];
        $maxPerBatch = 500;
        $count = 0;
        foreach ($data['assets'] as $asset_data) {
            $count++;
            if ($count > $maxPerBatch) {
                $unsaved_data[] = ['reason' => 'batch limit reached'];
                break;
            }
            try {
                // try to resolve owner within same client by name first, fallback to global user
                $owner = User::where('name', $asset_data['ASSET_OWNER'])->first();
                $owner_id = $owner ? $owner->id : null;

                $name = trim($asset_data['ASSET_NAME']);
                Asset::firstOrCreate([
                    'client_id' => $client_id,
                    'name' => $name
                ], [
                    'asset_type_id' => $asset_type->id,
                    'description' => $asset_data['ASSET_DESCRIPTION'] ?? null,
                    'location' => $asset_data['ASSET_LOCATION'] ?? null,
                    'classification' => $asset_data['ASSET_CLASSIFICATION'] ?? null,
                    'purpose' => $asset_data['PURPOSE_OF_ASSET'] ?? null,
                    'information_stored' => $asset_data['INFORMATION_STORED'] ?? null,
                    'owner_id' => $owner_id,
                ]);
            } catch (\Throwable $th) {
                $unsaved_data[] = ['row' => $asset_data, 'error' => 'save_failed'];
            }
        }
        return response()->json(compact('unsaved_data'), 200);
    }
}
