<?php

namespace App\Http\Controllers;

use App\Models\RealState;
use Illuminate\Http\Request;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        return response()->json(auth('api')->user()->real_state()->paginate('10'), 200);
    }

    public function show($id) {
        try {
            return response()->json(auth('api')->user()->real_state()->findOrFail($id), 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['user_id'] = auth('api')->user()->id;
            $realState = $this->realState->create($data);
            if(isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }
            return response()->json($realState, 201);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->all();
            $realState = auth('api')->user()->real_state()->findOrFail($id);
            $realState->update($data);
            if(isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }
            return response()->json($realState, 204);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            return response()->json(auth('api')->user()->real_state()->findOrFail($id)->findOrFail($id)->delete(), 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
