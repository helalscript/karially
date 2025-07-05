<?php

namespace App\Services\API\V1\User;

use App\Models\Package;
use App\Models\Pakage;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SubcriptionService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    /**
     * Fetch all resources.
     *
     * @return mixed
     */
    public function getPackages($request)
    {
        try {
            $per_page = $request->get('per_page', 10);
            $data = Package::with([
                'packageFeatures'
            ])
                ->latest()
                ->paginate($per_page);
            return $data;

        } catch (Exception $e) {
            Log::error("SubcriptionService::index" . $e->getMessage());
            throw $e;

        }
    }



}