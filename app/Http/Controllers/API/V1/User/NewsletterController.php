<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{

    /**
     * Subscribe to newsletter
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $subscriber = Subscriber::firstOrCreate(
                ['email' => $validatedData['email']],
                $validatedData
            );

            $message = $subscriber->wasRecentlyCreated ? 'Successfully Subscribed' : 'Already Subscribed';
            $statusCode = $subscriber->wasRecentlyCreated ? 201 : 200;
            return Helper::jsonResponse(true, $message, $statusCode, $subscriber->only('id', 'email'));
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, $e->getMessage(), 500);
        }
    }

}
