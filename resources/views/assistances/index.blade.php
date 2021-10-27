@extends('layouts.consultant')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="overlay-wrapper" id="booking_tab_loading" style="display:none;">
      <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>
    </div>

    <div class="row">
      <div class="col-md-12">

        <div id="carouselServiceItems" class="carousel slide" data-ride="false" data-wrap="false" data-interval="false">
          <div class="carousel-inner">
            @foreach($assistances as $key => $assistance)
              <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" data-assistance-id="{{ $assistance->assistance_id }}">
                <div class="card">
                  <div class="card-header">
                    <h2 class="card-title text-info">
                      <strong>{{ $assistance->label }}</strong>
                    </h2>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <img class="img-fluid" src="{{ asset('images/default-service.jpg') }}" alt="Service Image">
                      </div>
                      <div class="col-md-6">
                        <p class="text-default"><strong>{{ $assistance->assistanceCategory->label }}</strong></p>
                        <p>{{ Str::limit($assistance->description, 200) }}</p>
                        <span>Cost per session: ${{ $assistance->cost_per_session }}</span>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
              </div>
            @endforeach
          </div>
        </div> 

      </div>
      <div class="col-md-12">
        <div class="d-flex justify-content-center">
          <div class="btn-group">
            <a href="#carouselServiceItems" role="button" data-slide="prev" class="btn btn-default">Prev</a>
            <a href="#carouselServiceItems" role="button" data-slide="next" class="btn btn-default">Next</a>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary card-tabs mt-3">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="booking_services_tabs" role="tablist">
              <li class="nav-item">
                <a class="bst_link nav-link active" data-toggle="pill" href="#" role="tab" data-list-type="pending">Pending</a>
              </li>
              <li class="nav-item">
                <a class="bst_link nav-link" data-toggle="pill" href="#" role="tab" data-list-type="active">Services</a>
              </li>
              <li class="nav-item">
                <a class="bst_link nav-link" data-toggle="pill" href="#" role="tab" data-list-type="payment">Payment</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane fade show active" role="tabpanel">
                <div class="row">
                  <div class="col-md-12" id="booking_card_container">
                    
                  </div>
                  <div class="col-md-12" id="booking_load_more_container">
                    <button type="button" class="load_more_btn btn btn-block btn-primary btn-sm" 
                      data-page-no="1" data-list-type="pending" 
                      style="display:none;">Load More</button>
                    <p class="no_record_text text-center text-default" style="display:none;">No More Records..</p>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>

  </div>
</div>

<div class="modal fade" id="accept_booking_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirm Booking?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="acpt_bk_assistance_id" value="">
        <input type="hidden" id="acpt_bk_booking_id" value="">
        <div id="acpt_bk_slot_container"></div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="submit_accept_booking" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page_based_js')
<script>

/******** Config for Toastr **************/
toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "show",
    "hideMethod": "fadeOut"
}
/*****************************************/

/******** Toastr messages **************/
function successMessageToastr(message){
    var text_message = message ? message : 'Success!';
    toastr["success"](text_message);
}

function errorMessageToastr(message){
    var text_message = message ? message : 'An error occurred. Please try again later!';
    toastr["error"](text_message);
}
/*****************************************/

function getServiceBookings(service_id, type, render_mode, page_no){
  $('#booking_tab_loading').show();

  $.ajax({
    url: BMS_CNS_BASE + 'assistances/' + service_id + '/bookings',
    type: 'GET',
    data: {
      list_type: type,
      page: page_no ? page_no : 1
    }
  }).done(function(data, textStatus, jqXHR){
    $('#booking_tab_loading').hide();

    if(textStatus == 'success'){
      if(render_mode == 'append'){
        $('#booking_card_container').append(data.cards);
      }
      else{
        $('#booking_card_container').html(data.cards);
      }
      
      if(data.has_more_pages){
        $('#booking_load_more_container').find('button')
          .data('page-no', data.next_page_no)
          .data('list-type', type).show();
        $('#booking_load_more_container').find('p').hide();
      }
      else{
        $('#booking_load_more_container').find('button').hide();
        $('#booking_load_more_container').find('p').show();
      }
    }
    else{
      errorMessageToastr('Error loading bookings. Please try again later.');
    }
  })
  .fail(function(jqXHR, textStatus, errorThrown){
    $('#booking_tab_loading').hide();
    errorMessageToastr('Request failed. Unexpected error!!!');
  });
}

function acceptBooking(assistance_id, booking_id){
  
}

$(document).ready(function(){
  var selected_assistance_id = $('#carouselServiceItems').find('div.carousel-item.active').data('assistance-id');
  getServiceBookings(selected_assistance_id, 'pending', 'append', 1);

  $('#carouselServiceItems').on('slide.bs.carousel', function (e) {
    console.log(e.relatedTarget);
    var carousel_item = $(e.relatedTarget);
    var selected_assistance_id = carousel_item.data('assistance-id');
    getServiceBookings(selected_assistance_id, 'pending', 'reload', 1);
    $('.bst_link').removeClass('active');
    $('.bst_link').first().addClass('active');
  });

  $('.bst_link').click(function(e){
    var selected_assistance_id = $('#carouselServiceItems').find('div.carousel-item.active').data('assistance-id');
    var list_type = $(this).data('list-type');
    getServiceBookings(selected_assistance_id, list_type, 'reload', 1);
  });

  $('.load_more_btn').click(function(){
    var page_no = $(this).data('page-no');
    var list_type = $(this).data('list-type');
    var selected_assistance_id = $('#carouselServiceItems').find('div.carousel-item.active').data('assistance-id');
    getServiceBookings(selected_assistance_id, list_type, 'append', page_no);
  });
});
</script>

@endsection
