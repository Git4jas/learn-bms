<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\ConsultationSlot;
use Auth;
use DB;

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
                'customer_user',
                'consultation_slots' => function($query){
                    $query->orderBy('consultation_slots.start_time', 'ASC');
                },
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

        $bookings = $bookings->simplePaginate(5);

        $booking_cards_html = view('bookings.card', ['bookings' => $bookings->items()])->render();
        $has_more_pages = $bookings->hasMorePages();
        $next_page_no = $bookings->currentPage() + 1;

        return response()->json([
            'cards' => $booking_cards_html, 
            'has_more_pages' => $has_more_pages, 
            'next_page_no' => $next_page_no
        ]);
    }

    public function show($assistance_id, $booking_id){
        $booking = Booking::where('consultant_user_id', Auth::user()->user_id)
            ->where('assistance_id', $assistance_id)
            ->where('booking_id', $booking_id)
            ->select('bookings.booking_id','bookings.assistance_id')
            ->first();

        $response = [
            'status' => 'failed',
            'error' => 'Cannot find Booking details. Please try after sometime'
        ];

        if(!empty($booking)){
            $slots = $booking->consultation_slots()
                ->select(DB::raw("consultation_slots.consultation_slot_id, 
                    CONCAT(DATE_FORMAT(consultation_slots.start_time,  '%m-%d-%Y %H:%i %p'), ' - ', 
                    DATE_FORMAT(consultation_slots.end_time, '%m-%d-%Y %H:%i %p')) as slot_time"))
                ->orderBy('consultation_slots.start_time', 'ASC')->get();

            $response['status'] = 'success';
            $response['booking'] = $booking;
            $response['slots_dd_snippet'] = view('bookings.slots_dropdown', ['slots' => $slots])->render();
            $response['error'] = '';
        }

        return response()->json($response);
    }

    public function update(Request $request, $assistance_id, $booking_id){
        $response = [
            'status' => 'failed',
            'error' => 'Cannot find Booking details. Please try after sometime'
        ];

        $booking = Booking::where('consultant_user_id', Auth::user()->user_id)
        ->where('assistance_id', $assistance_id)
        ->where('booking_id', $booking_id)
        ->first();

        if(empty($booking)){
            return response()->json($response);
        }

        $validator = Validator::make($request->all(), [
            'status' => [
                'required',
                Rule::in(['pending', 'active', 'payment']),
            ],
        ]);

        if($validator->fails()){
            $response['error'] = $validator->errors()->first();
            return response()->json($response);
        }

        $new_status_id = BookingStatus::PENDING;
        if($request->get('status') == 'active'){
            $booking->booking_status_id = BookingStatus::ACTIVE;
        }
        elseif($request->get('status') == 'payment'){
            $booking->booking_status_id = BookingStatus::PAYMENT;
        }

        if(!empty($request->get('slot_id'))){
            $slot = ConsultationSlot::find($request->get('slot_id'));
            if(!empty($slot)){
                $booking->session_start_time = $slot->start_time;
                $booking->session_end_time = $slot->end_time;
            }
        }

        $booking->save();

        $response['status'] = 'success';
        $response['booking'] = $booking;
        $response['error'] = '';

        return response()->json($response);
    }
}
