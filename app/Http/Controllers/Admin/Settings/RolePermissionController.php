<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\Role;
use Silber\Bouncer\Database\Ability;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::query()->orderBy('name')->get(['id','name','title']);
        $abilities = Ability::query()->orderBy('name')->get(['id','name','title']);
        
        // matrix: [role_id][ability_id] => bool
        $allowed = [];
        foreach ($roles as $role) {
            $roleAbilityIds = $role->abilities()->pluck('abilities.id')->all();
            $allowed[$role->id] = array_fill_keys($roleAbilityIds, true);
        }
        
        $user = $request->user();
        $canManagePermissions = $user && ($user->isAn('master') || $user->isAn('admin'));
        
        return Inertia::render('admin/settings/roles/Index', [
            'roles' => $roles,
            'abilities' => $abilities,
            'allowed' => $allowed,
            'canManagePermissions' => $canManagePermissions,
        ]);
    }
    
    public function storeMatrix(Request $request)
    {
        $data = $request->validate([
            'matrix' => ['required', 'array'],
            'matrix.*' => ['array'], // role_id => [ability_id => bool]
        ]);
        
        $roles = Role::query()->get(['id']);
        $abilities = Ability::query()->get(['id'])->keyBy('id');
        
        foreach ($roles as $role) {
            $roleMatrix = $data['matrix'][$role->id] ?? [];
            
            foreach ($abilities as $abilityId => $ability) {
                $checked = (bool)($roleMatrix[$abilityId] ?? false);
                
                if ($checked) {
                    Bouncer::allow($role)->to($ability);
                } else {
                    Bouncer::disallow($role)->to($ability);
                }
            }
        }
        
        return back()->with('success', 'Uloge i odobrenja spremljeni.');
    }
}