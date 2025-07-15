<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Exception;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Subscriber::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '<div class="action-wrapper">
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
             
                </div>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layouts.newsletter.index");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Subscriber::findOrFail($id);
        // check here BookingRequest hotel_id === identifyHotel
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }

        if (!empty($data->image)) {
            Helper::fileDelete(public_path($data->image));
        }

        $data->delete();

        return response()->json([
            "success" => true,
            "message" => "Item deleted successfully."
        ]);
    }
}
