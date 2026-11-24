<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Position\StorePositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PositionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $positions = Position::with('department')
            ->when($request->integer('department_id'), fn ($q, $id) => $q->where('department_id', $id))
            ->orderBy('title')
            ->paginate(config('hr.per_page'));

        return PositionResource::collection($positions);
    }

    public function store(StorePositionRequest $request): PositionResource
    {
        return new PositionResource(Position::create($request->validated())->load('department'));
    }

    public function show(Position $position): PositionResource
    {
        return new PositionResource($position->load('department'));
    }

    public function update(UpdatePositionRequest $request, Position $position): PositionResource
    {
        $position->update($request->validated());

        return new PositionResource($position->load('department'));
    }

    public function destroy(Position $position): Response
    {
        $position->delete();

        return response()->noContent();
    }
}
