<?php

namespace App\Http\Controllers;

use App\Models\ActivatedModule;
use App\Models\AvailableModule;
use App\Models\Client;
use App\Models\ModulePackage;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    public function fetchPackages()
    {
        $packages = ModulePackage::with('availableModule')->orderBy('available_module_id')->get()->groupBy('available_module_id');
        return response()->json(compact('packages'), 200);
    }

    public function storePackage(Request $request)
    {
        $name = $request->name;
        $available_module_id = $request->available_module_id;
        $package = ModulePackage::where(['name' => $name, 'available_module_id' => $available_module_id])->first();
        if (!$package) {
            $package = new ModulePackage();
        }
        $package->name = $name;
        $package->available_module_id = $available_module_id;
        $package->features = $request->features;
        $package->price = $request->price;
        $package->save();
        return 'success';
    }
    public function updatePackage(Request $request, ModulePackage $package)
    {
        $package->name = $request->name;
        $package->features = $request->features;
        $package->price = $request->price;
        $package->save();
        return 'success';
    }
    public function deletePackage(Request $request, ModulePackage $package)
    {
        $package->delete();
        return 'success';
    }
    public function fetchActivatedModules(Request $request)
    {
        $partner_id = $this->getPartner()->id;
        $activated_modules = ActivatedModule::with('availableModule')->where('partner_id', $partner_id)->get();
        foreach ($activated_modules as $activated_module) {
            $client_ids = $activated_module->client_ids;
            $client_ids_array = explode('~', $client_ids);
            $activated_module->clients = Client::whereIn('id', $client_ids_array)->get();
        }
        return response()->json(compact('activated_modules'), 200);
    }
    public function fetchModules()
    {
        $modules = AvailableModule::with([
            'activatedModules.partner',
            'features',
            'packages' => function ($q) {
                // $q->where('price', 0);
            }
        ])->where('status', 'Ready')->get();
        return response()->json(compact('modules'), 200);
    }
    public function activatePartnersModule(Request $request)
    {
        $module_id = $request->module_id;
        $partner_ids = json_decode(json_encode($request->partner_ids));
        foreach ($partner_ids as $partner_id) {
            ActivatedModule::firstOrCreate([
                'available_module_id' => $module_id,
                'partner_id' => $partner_id
            ]);
        }
        return response([], 204);
    }
    public function deactivatePartnersModule(Request $request, ActivatedModule $activated_module)
    {
        $activated_module->delete();
        return response([], 204);
    }
    public function activateClientsModule(Request $request, ActivatedModule $activated_module)
    {
        $client_ids = $request->client_ids;
        foreach ($client_ids as $client_id) {

            $parent_string = $activated_module->client_ids;
            $activated_module->client_ids = addSingleElementToString($parent_string, $client_id);
            $activated_module->save();
        }
        return response([], 204);
    }
    public function deactivateClientModule(Request $request, ActivatedModule $activated_module)
    {
        $parent_string = $activated_module->client_ids;
        $child_string = $request->client_id;
        $activated_module->client_ids = deleteSingleElementFromString($parent_string, $child_string);
        $activated_module->save();
        return response([], 204);
    }
}
