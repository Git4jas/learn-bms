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

function showAcceptBooking(service_id, booking_id){
  $('#booking_tab_loading').show();

  $.ajax({
    url: BMS_CNS_BASE + 'assistances/' + service_id + '/bookings/' + booking_id,
    type: 'GET',
    data: {}
  }).done(function(data, textStatus, jqXHR){
    $('#booking_tab_loading').hide();

    if(textStatus == 'success'){
      if(data.status == 'success'){
        $('#acpt_bk_assistance_id').val(service_id);
        $('#acpt_bk_booking_id').val(booking_id);
        $('#acpt_bk_slot_container').html(data.slots_dd_snippet);
        $('#accept_booking_modal').modal('show');
      }
      else{
        errorMessageToastr(data.error);
      }
    }
    else{
      errorMessageToastr('Error loading booking details. Please try again later.');
    }
  })
  .fail(function(jqXHR, textStatus, errorThrown){
    $('#booking_tab_loading').hide();
    errorMessageToastr('Request failed. Unexpected error!!!');
  });
}

function generateInvoice(service_id, booking_id){
  var form_data = {
    'status': 'payment',
    'slot_id': $('#booking_selected_slot').val(),
    '_method': 'PUT',
    '_token': XCSRF_TOKEN
  };

  $('#booking_tab_loading').show();
  $.ajax({
    url: BMS_CNS_BASE + 'assistances/' + service_id + '/bookings/' + booking_id,
    type: 'POST',
    data: form_data
  }).done(function(data, textStatus, jqXHR){
    $('#booking_tab_loading').hide();

    if(textStatus == 'success'){
      if(data.status == 'success'){
        successMessageToastr('Status updated successfully!');
        $('#service_card_' + booking_id).remove();
      }
      else{
        errorMessageToastr(data.error);
      }
    }
    else{
      errorMessageToastr('Error loading booking details. Please try again later.');
    }
  })
  .fail(function(jqXHR, textStatus, errorThrown){
    $('#booking_tab_loading').hide();
    errorMessageToastr('Request failed. Unexpected error!!!');
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

  $('.load_more_btn').click(function(){
    var page_no = $(this).data('page-no');
    var list_type = $(this).data('list-type');
    var selected_assistance_id = $('#carouselServiceItems').find('div.carousel-item.active').data('assistance-id');
    getServiceBookings(selected_assistance_id, list_type, 'append', page_no);
  });

  $('#submit_accept_booking').click(function(){
    var service_id = $('#acpt_bk_assistance_id').val();
    var booking_id = $('#acpt_bk_booking_id').val();
    var form_data = {
      'status': 'active',
      'slot_id': $('#booking_selected_slot').val(),
      '_method': 'PUT',
      '_token': XCSRF_TOKEN
    };
    $('#accept_booking_modal').modal('hide');

    $('#booking_tab_loading').show();
    $.ajax({
      url: BMS_CNS_BASE + 'assistances/' + service_id + '/bookings/' + booking_id,
      type: 'POST',
      data: form_data
    }).done(function(data, textStatus, jqXHR){
      $('#booking_tab_loading').hide();

      if(textStatus == 'success'){
        if(data.status == 'success'){
          successMessageToastr('Status updated successfully!');
          $('#service_card_' + booking_id).remove();
        }
        else{
          errorMessageToastr(data.error);
        }
      }
      else{
        errorMessageToastr('Error loading booking details. Please try again later.');
      }
    })
    .fail(function(jqXHR, textStatus, errorThrown){
      $('#booking_tab_loading').hide();
      errorMessageToastr('Request failed. Unexpected error!!!');
    });

  });
});