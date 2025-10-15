<?php

namespace App\Http\Controllers\Api\Job;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Job\EmployeeJob\StoreEmployeeJobRequest;
use App\Models\EmployeeJob;
use App\Models\Job;
use App\Traits\ApiTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Http\Request;

class EmployeeJobController extends Controller
{
    use ApiTrait, UploadFileTrait;

    public function store(StoreEmployeeJobRequest $request, Job $job)
    {
        $cv = $this->generalUploadFile(EmployeeJob::UPLOAD_PATH, $request->cv);
        $job->employees()->create(['cv' => $cv] + $request->validated());
        return $this->apiResponse(message: "You have applied successfully, we will connect with you soon.");
    }
}
