<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Services\StoreConnector;

class AdminController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        return view('admin.dashboard', compact('stores'));
    }

    public function connect(Request $request)
    {
        $store = Store::findOrFail($request->store_id);

        $db = StoreConnector::connect($store->host);

        $users = $db->table('pos_users')->get();

        return view('admin.dashboard', [
            'stores' => Store::all(),
            'selectedStore' => $store,
            'users' => $users
        ]);
    }

    public function updateUser(Request $request)
    {
        $store = Store::findOrFail($request->store_id);

        $db = StoreConnector::connect($store->host);

        $db->table('pos_users')
            ->where('id', $request->user_id)
            ->update([
                'name' => $request->name
            ]);

        return back()->with('success', 'User updated!');
    }

    // Stores management
    public function storesIndex()
    {
        $stores = Store::all();
        return view('admin.stores.index', compact('stores'));
    }

    public function createStore()
    {
        $supervisors = \App\Models\User::where(function($q) {
            $q->where('position', 'Supervisor')
              ->orWhere('position', 'supervisor');
        })->get();
        return view('admin.stores.create', compact('supervisors'));
    }

    public function storeStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
            'authorized' => 'nullable|boolean',
            'supervisor_id' => 'nullable|exists:pos_users,person_id',
        ]);

        $store = Store::create([
            'name' => $validated['name'],
            'host' => $validated['host'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'authorized' => $request->has('authorized') ? $request->boolean('authorized') : true,
            'supervisor_id' => $validated['supervisor_id'] ?? null,
        ]);

        return redirect()->route('admin.stores.index')->with('success', 'Store created.');
    }

    public function editStore(Store $store)
    {
        $supervisors = \App\Models\User::where(function($q) {
            $q->where('position', 'Supervisor')
              ->orWhere('position', 'supervisor');
        })->get();
        return view('admin.stores.edit', compact('store', 'supervisors'));
    }

    public function updateStore(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
            'authorized' => 'nullable|boolean',
            'supervisor_id' => 'nullable|exists:pos_users,person_id',
        ]);

        $store->update([
            'name' => $validated['name'],
            'host' => $validated['host'] ?? $store->host,
            'status' => $validated['status'] ?? $store->status,
            'authorized' => $request->has('authorized') ? $request->boolean('authorized') : ($store->authorized ?? true),
            'supervisor_id' => $validated['supervisor_id'] ?? null,
        ]);

        return redirect()->route('admin.stores.index')->with('success', 'Store updated.');
    }

    public function destroyStore(Store $store)
    {
        $store->delete();
        return redirect()->route('admin.stores.index')->with('success', 'Store deleted.');
    }
}
