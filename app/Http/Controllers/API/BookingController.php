<?php

namespace App\Http\Controllers\API;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\bookingRepository;
use App\Notifications\NewBookingNotification;

class BookingController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private bookingRepository $bookingRepository)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $fields = $request->validate([
            'establishment_id' => ['required',Rule::exists('establishments','id')],
            'price_package_id' =>['required',Rule::exists('price_packages','id')],
            'booking_date' =>['required','date','after_or_equal:now'],
            'coupon_id' => ['nullable',Rule::exists('discount_coupons','id')],
        ]);

        try {
            $user_id = auth('sanctum')->id();
            $fields['user_id'] = $user_id;
            $booking = $this->bookingRepository->store($fields);
            $establishment = $booking->establishment;
            $owner = $establishment->owner;

            // send notification to the owner
            $owner->notify(new NewBookingNotification($booking));
            
            return ApiResponseClass::sendResponse($booking, 'Booking saving successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error saving booking: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
