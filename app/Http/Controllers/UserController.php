<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return response()->json($this->user->paginate('10'), 200);
    }

    public function store(Request $request)
    {

        try {
            $data = $request->all();
            if (!$request->has('password') || !$request->get('password')) {
                throw new Exception('PASSWORD_REQUIRED');
            }
            Validator::make($data, [
                'phone' => 'required',
                'mobile_phone' => 'required'
            ])->validate();
            $data['password'] = app('hash')->make($data['password']);
            $user = $this->user->create($data);
            $user->profile()->create([
                'phone' => $data['phone'],
                'mobile_phone' => $data['mobile_phone'],
            ]);
            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            if ($request->has('password') && $request->get('password')) {
                $data['password'] = app('hash')->make($data['password']);
            } else {
                unset($data['password']);
            }
            Validator::make($data, [
                'profile.phone' => 'required',
                'profile.mobile_phone' => 'required'
            ])->validate();
            $profile = $data['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);
            $user = $this->user->findOrFail($id);
            $user->update($data);
            return response()->json($user->profile()->update($profile), 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            return response()->json($this->user->findOrFail($id)->delete(), 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
