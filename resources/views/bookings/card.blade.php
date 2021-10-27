@foreach ($bookings as $booking)
<div class="card card-outline card-default">
  <div class="card-header">
      <div class="user-block">
        <img class="img-circle" src="{{ asset('images/default-150x150.png') }}" alt="User Image">
        <span class="username"><a href="#" class="text-info">{{ $booking->customer_user->name }}</a></span>
        <span class="description">{{ $booking->created_at->diffForHumans() }}</span>
      </div>
      <div class="card-tools">
        <span class="badge bg-warning">{{ $booking->booking_status->label }}</span>
      </div>
  </div>
  <div class="card-body">
    <div>
      @if($booking->booking_status_id == App\Models\BookingStatus::PENDING)
      <p>The customer is available at:</p>
      <ul>
        @foreach ($booking->consultation_slots as $slot)
        <li>
          @if($slot->start_time->format('Y-m-d') == $slot->end_time->format('Y-m-d'))
            {{ $slot->start_time->format('D, M d, Y g:i A') }} - {{ $slot->end_time->format('g:i A') }}
          @else
            {{ $slot->start_time->format('m-d-Y g:i A') }} - {{ $slot->end_time->format('m-d-Y g:i A') }}
          @endif
        </li>    
        @endforeach
      </ul>
      @endif
    </div>
    <div>
      <p class="text-info"><i class="fas fa-map-marker-alt"></i> To Do</p>
    </div>
    <div>
      @if($booking->booking_status_id == App\Models\BookingStatus::PENDING)
        <button type="button" class="btn btn-primary" 
          onclick="acceptBooking({{$booking->assistance_id}},{{$booking->booking_id}})">Accept Request</button>
      @elseif($booking->booking_status_id == App\Models\BookingStatus::ACTIVE)

      @elseif($booking->booking_status_id == App\Models\BookingStatus::PAYMENT)

      @endif
    </div>
  </div>
</div>
@endforeach