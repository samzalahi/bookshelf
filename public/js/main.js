// $(document).ready(function() {
// // Live search code
// //   $("#result_table").hide();
// //   // Search
// //   function search() {
// //     var search_value = $('input#search').val();
    
// //     if (search_value !== '') {
// //       console.log(search_value);
// //       $.ajax({
// //         type: "POST",
// //         url: "http://localhost/bookshelf/books/index",
// //         data: {
// //           search: search_value
// //         },
// //         cache: false,
// //         success: function (html) {
// //           $("table#result_table tbody").html(html);
// //         }
// //       });
// //     }
// //     return false;
// //   }

// //   $("input#search").on("keyup", function (e) {
// //     // Set Timeout
// //     clearTimeout($.data(this, 'timer'));

// //     // Set Search String
// //     var search_string = $(this).val();

// //     // Do Search
// //     if (search_string == '') {
// //       $("#result_table").fadeOut(300);
// //     } else {
// //       $("#result_table").fadeIn(300);
// //       $(this).data('timer', setTimeout(search, 100));
// //     };
// //   });

// // yes or no model code
// // Bind click to OK button within popup
// // $('#confirm-delete').on('click', '.btn-ok', function(e) {
// //   var $modalDiv = $(e.delegateTarget);
// //   var id = $(this).data('recordId');
// //   var path = $(this).data('recordUrl');
// //   console.log(id);
// //   console.log(path + id);
  
// //   $.ajax({url: path + id, type: 'POST', })
// //   $.post(path + id).then()
// //   $modalDiv.addClass('loading');
// //   setTimeout(function() {
// //       $modalDiv.modal('hide').removeClass('loading');
// //   }, 1000)
// // });
// // $('#confirm-delete').on('show.bs.modal', function(e) {
// //   var data = $(e.relatedTarget).data();
// //   $('#title', this).text(data.recordTitle);
// //   $('.btn-ok', this).data('recordId', data.recordId);
// //   $('.btn-ok', this).data('recordUrl', data.recordUrl);
// // });

// });

$(function() {
  
  $('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    popout: true,
    btnOkClass: 'btn-success',
    singleton:true
  });

  $('[data-toggle="tooltip"]').tooltip();

  $('#tokenfield').tokenfield();

  $('#tokenfield').on('tokenfield:createtoken', function (event) {
    var existingTokens = $(this).tokenfield('getTokens');
    $.each(existingTokens, function(index, token) {
        if (token.value === event.attrs.value)
            event.preventDefault();
    });
  });

  $(".tokenfield.form-control").addClass("tokenfield form-control col-sm-12");

  // Sidebar nav active class
  $(".nav .nav-link").on('click', function(){
    // $(this).siblings().removeClass('active')
    // $(this).addClass('active');Default();
  })

  // Date picker
  $('[data-provide="datepicker"]').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true
  });

})

function goBack() {
  window.history.back();
}