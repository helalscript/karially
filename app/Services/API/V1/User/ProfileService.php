<?php

namespace App\Services\API\V1\User;

use App\Models\UserProfile;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProfileService
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
    public function index($request)
    {
        try {

        } catch (Exception $e) {
            Log::error("ProfileService::index" . $e->getMessage());
            throw $e;

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        try {

        } catch (Exception $e) {
            Log::error("ProfileService::index" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Store a new resource.
     *
     * @param array $validatedData
     * @return mixed
     */
    public function store(array $validatedData)
    {
        try {
            $profile = UserProfile::updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'full_name' => $validatedData['full_name'],
                    'email' => $validatedData['email'] ?? null,
                    'phone' => $validatedData['phone'] ?? null,
                    'job_title' => $validatedData['job_title'] ?? null,
                    // Add other fields like education, experience etc.
                ]
            );
            return $profile;
        } catch (Exception $e) {
            Log::error("ProfileService::store" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Display a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        try {

        } catch (Exception $e) {
            Log::error("ProfileService::show" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Show the form for editing a resource.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id)
    {
        try {

        } catch (Exception $e) {
            Log::error("ProfileService::edit" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a specific resource.
     *
     * @param int $id
     * @param array $validatedData
     * @return mixed
     */
    public function update(int $id, array $validatedData)
    {
        try {

        } catch (Exception $e) {
            Log::error("ProfileService::update" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        try {
            // Logic to delete a specific resource
        } catch (Exception $e) {
            Log::error("ProfileService::destroy" . $e->getMessage());
            throw $e;
        }
    }

}