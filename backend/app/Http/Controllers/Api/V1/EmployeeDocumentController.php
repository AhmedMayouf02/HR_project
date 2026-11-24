<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeDocument\StoreEmployeeDocumentRequest;
use App\Http\Requests\EmployeeDocument\UpdateEmployeeDocumentRequest;
use App\Http\Resources\EmployeeDocumentResource;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeDocumentController extends Controller
{
    public function index(Employee $employee): AnonymousResourceCollection
    {
        return EmployeeDocumentResource::collection($employee->documents()->latest()->get());
    }

    public function store(StoreEmployeeDocumentRequest $request, Employee $employee): EmployeeDocumentResource
    {
        $path = $request->file('file')->store("employees/{$employee->id}", config('hr.documents_disk'));

        $document = $employee->documents()->create([
            ...$request->safe()->except('file'),
            'file_path' => $path,
        ]);

        return new EmployeeDocumentResource($document);
    }

    public function show(EmployeeDocument $document): EmployeeDocumentResource
    {
        return new EmployeeDocumentResource($document);
    }

    public function download(EmployeeDocument $document): StreamedResponse
    {
        return Storage::disk(config('hr.documents_disk'))->download($document->file_path, $document->title);
    }

    public function update(UpdateEmployeeDocumentRequest $request, EmployeeDocument $document): EmployeeDocumentResource
    {
        $document->update($request->validated());

        return new EmployeeDocumentResource($document);
    }

    public function destroy(EmployeeDocument $document): Response
    {
        Storage::disk(config('hr.documents_disk'))->delete($document->file_path);
        $document->delete();

        return response()->noContent();
    }
}
