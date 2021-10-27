<div class="form-group">
  <label for="booking_selected_slot">Select Time Slot</label>
  <select class="form-control" id="booking_selected_slot">
    @foreach($slots as $slot)
        <option value="{{ $slot->consultation_slot_id }}">{{ $slot->slot_time }}</option>
    @endforeach
  </select>
</div>