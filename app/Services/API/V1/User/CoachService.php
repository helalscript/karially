<?php

namespace App\Services\API\V1\User;

use App\Models\Coach;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CoachService
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
            $per_page = $request->get('per_page', 10);
            $category_id = $request->get('category_id', null);
            $searchByName = $request->get('search_name', null);
            $data = Coach::with(['category' => fn($query) => $query->select('id', 'name')])
                ->where('status', 'active')
                ->when($category_id, function ($query) use ($category_id) {
                    return $query->where('category_id', $category_id);
                })
                ->when($searchByName, function ($query) use ($searchByName) {
                    return $query->where('name', 'like', '%' . $searchByName . '%');
                })
                ->select('id', 'name', 'avatar', 'category_id', 'calendly_user_id', 'calendly_link', 'bio', 'status')
                ->latest()
                ->paginate($per_page);
            return $data;
        } catch (Exception $e) {
            Log::error("CoachService::index" . $e->getMessage());
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
            $data = Coach::with(['category' => fn($query) => $query->select('id', 'name')])
                ->select('id', 'name', 'avatar', 'category_id', 'calendly_user_id', 'calendly_link', 'bio', 'status')
                ->where('status', 'active')
                ->find($id);
            if (!$data) {
                throw new Exception("Coach not found");
            }
            return $data;
        } catch (Exception $e) {
            Log::error("CoachService::show" . $e->getMessage());
            throw $e;
        }
    }

}