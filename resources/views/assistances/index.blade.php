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
                    <button type="button" class="load_more_btn btn btn-block btn-primary btn-sm" data-page-no="1" style="display:none;">Load More</button>
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
@endsection

@section('page_based_js')
<script>

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
    //console.log(data);
    //console.log(textStatus);
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
          .data('page-no', data.next_page_no).show();
        $('#booking_load_more_container').find('p').hide();
      }
      else{
        $('#booking_load_more_container').find('button').hide();
        $('#booking_load_more_container').find('p').show();
      }
    }
    else{
      // to do error
    }
  })
  .fail(function(jqXHR, textStatus, errorThrown){
    $('#booking_tab_loading').hide();
    //console.log(errorThrown);  
  });
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
});
</script>

@endsection
