<?php

namespace App\Http\Controllers\Api\Job;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class JobController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $jobs =  JobResource::collection(Job::where('status', 1)->get(['id', 'title', 'description', 'salary']));
        return $this->apiResponse(data: $jobs);
    }
}
