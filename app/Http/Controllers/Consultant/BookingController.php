<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatus;
use Auth;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    public function index(Request $request, $assistance_id)
    {
        $booking_type = $request->get('list_type');

        $bookings = Booking::where('consultant_user_id', Auth::user()->user_id)
            ->where('assistance_id', $assistance_id)
            ->with([
                'customer_user:user_id,name',
                'consultation_slots',
                'booking_status:booking_status_id,label'
            ]);
        
        if($booking_type == 'active'){
            $bookings->where('booking_status_id', BookingStatus::ACTIVE);
        }
        elseif($booking_type == 'payment'){
            $bookings->where('booking_status_id', BookingStatus::PAYMENT);
        }
        else{
            $bookings->where('booking_status_id', BookingStatus::PENDING);
        }

        $bookings = $bookings->simplePaginate(5); //dd($bookings);

        $booking_cards_html = view('bookings.card', ['bookings' => $bookings->items()])->render();
        $has_more_pages = $bookings->hasMorePages();
        $next_page_no = $bookings->currentPage() + 1;

        return response()->json([
            'cards' => $booking_cards_html, 
            'has_more_pages' => $has_more_pages, 
            'next_page_no' => $next_page_no
        ]);
    }
}
