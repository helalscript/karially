<?php

namespace App\Services\API\V1\User;

use App\Mail\CoachMatchSubmitted;
use App\Models\CoachMatch;
use App\Models\SystemSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CoachMatchService
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
            $coachMatches = CoachMatch::where('user_id', $this->user->id)->latest()->paginate($per_page);
            return $coachMatches;

        } catch (Exception $e) {
            Log::error("CoachMatchService::index" . $e->getMessage());
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
            DB::beginTransaction();
            $validatedData['user_id'] = $this->user->id;
            $coachMatch = CoachMatch::create($validatedData);
            $systemSetting = SystemSetting::first();
            $email = $systemSetting->email ?? 'hello@karially.com';
            Mail::to($systemSetting->email)->send(new CoachMatchSubmitted($coachMatch));
            DB::commit();
            return $coachMatch;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("CoachMatchService::store" . $e->getMessage());
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
            $coachMatch = CoachMatch::find($id);
            if (!$coachMatch) {
                throw new Exception("Coach match not found");
            }
            return $coachMatch;
        } catch (Exception $e) {
            Log::error("CoachMatchService::show" . $e->getMessage());
            throw $e;
        }
    }
}