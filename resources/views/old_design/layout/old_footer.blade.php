       {{-- <footer class="text-white text-center">
    <p>&copy; 2024 Beautiful Dashboard. All rights reserved.</p>
</footer> --}}

       <div class="modal fade w-100" id="extraLargeModal" tabindex="-1" role="dialog"
           aria-labelledby="extraLargeModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-xl" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="extraLargeModalLabel">Extra Large Modal</h5>
                       <button type="button" class="btn btn-sm btn-warning" id="close-large-modal" data-dismiss="modal"
                           aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <div class="modal-body" id="modal-body-view">

                   </div>

               </div>
           </div>
       </div>





       <div class="modal fade w-100" id="mediumModal" tabindex="-1" role="dialog"
           aria-labelledby="extraLargeModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-xl" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="mediumModalLabel">Extra Large Modal</h5>
                       <button type="button" class="close btn btn-sm btn-warning" data-dismiss="modal"
                           aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <div class="modal-body" id="mediumModalview">

                   </div>

               </div>
           </div>
       </div>








       {{-- fullscreen modal --}}



       <!-- Fullscreen Modal -->
       <div class="modal fade" id="fullscreenModal" tabindex="-1" role="dialog" aria-labelledby="fullscreenModalLabel"
           aria-hidden="true">
           <div class="modal-dialog modal-fullscreen">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="fullscreenModalLabel">Fullscreen Modal</h5>
                       <button type="button" class="btn btn-sm btn-warning" id="close-fullscreen-modal"
                           data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <div class="modal-body" id="fullscreen-modal-view">
                       <!-- Modal body content goes here -->
                       Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam non hendrerit enim. Nullam varius
                       lacinia purus, non maximus elit molestie in. Morbi suscipit auctor urna, non malesuada elit
                       efficitur eu.
                   </div>

               </div>
           </div>
       </div>




       </div>

       </div>

       <script src="{{ url('new_design/plugins/jquery/jquery.min.js') }}"></script>
       <!-- jQuery UI 1.11.4 -->
       <script src="{{ url('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
       <!-- Bootstrap JS (optional) -->
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
       <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

       <script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



       <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

       <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
       <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
       <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.bootstrap5.min.js"></script>
       <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>

       <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.js"></script>
  
       


       </body>

       </html>





       <script>
           var csrfToken = $('meta[name="csrf-token"]').attr('content');

           //    $(document).ready(function() {
           //        // Attach a keyup event handler to all input fields of type text
           //        $('input[type="text"]').on('input', function() {
           //            // Convert the input value to uppercase
           //            var uppercaseText = $(this).val().toUpperCase();
           //            // Update the input value with the uppercase text
           //            $(this).val(uppercaseText);
           //        });
           //    });


           var csrfToken = "{{ csrf_token() }}";



           function viewModal(url) {
               if (url) {
                   $.ajax({
                       url: url,
                       type: "GET",
                       success: function(data) {
                           $('#extraLargeModal').modal('show');
                           $('#extraLargeModalLabel').html(data["title"]);
                           $('#modal-body-view').html(data["view"]);
                       }
                   })
               }
           }





           function fullScreenModal(url) {

               if (url) {
                   $.ajax({
                       url: url,
                       type: "GET",
                       success: function(data) {
                           $('#fullscreenModal').modal('show');
                           $('#fullscreenModalLabel').html(data["title"]);
                           $('#fullscreen-modal-view').html(data["view"]);
                       }
                   })
               }

           }





           $(document).on("click", "#close-fullscreen-modal", function() {

               $('#fullscreenModal .modal-body').html("");
               $('#fullscreenModal').modal('hide');

           })



           $(document).on("click", ".close", function() {

               $('#mediumModal .modal-body').html("");
               $('#mediumModal').modal('hide');

           })


           $(document).on("click", "#close-large-modal", function() {

               $('#extraLargeModal .modal-body').html();
               $('#extraLargeModal').modal('hide');

           })



           function mediumModal(url) {
               if (url) {
                   $.ajax({
                       url: url,
                       type: "GET",
                       success: function(data) {
                           $('#mediumModal').modal('show');
                           $('#mediumModalLabel').html(data["title"]);
                           $('#mediumModalview').html(data["view"]);
                       }
                   })
               }
           }




           function successAlert(message) {

               toastr.options = {
                   closeButton: true,
                   newestOnTop: false,
                   progressBar: true,
                   positionClass: 'toast-top-right',
                   // preventDuplicates: true,
                   showDuration: 300,
                   hideDuration: 1000,
                   timeOut: 1000,
                   extendedTimeOut: 1000,
                   showEasing: 'swing',
                   hideEasing: 'linear',
                   showMethod: 'fadeIn',
                   hideMethod: 'fadeOut',
                   // Customize font size and color
                   "progressBar": true,
                   "positionClass": "toast-top-right",
                   "fontSize": "30px",
                   "fontColor": "#FFFFFF",
               };
               // Example notification
               toastr.info(message);
           }


           function errorAlert(message) {

               toastr.options = {
                   closeButton: true,
                   newestOnTop: false,
                   progressBar: true,
                   positionClass: 'toast-top-right',
                   // preventDuplicates: true,
                   showDuration: 300,
                   hideDuration: 1000,
                   timeOut: 1000,
                   extendedTimeOut: 1000,
                   showEasing: 'swing',
                   hideEasing: 'linear',
                   showMethod: 'fadeIn',
                   hideMethod: 'fadeOut',
                   // Customize font size and color
                   "progressBar": true,
                   "positionClass": "toast-top-right",
                   "fontSize": "30px",
                   "fontColor": "#FFFFFF",
               };
               // Example notification
               toastr.warning(message);
           }



           $(document).ready(function() {
               // Start NProgress on page load
               // NProgress.start();
               // console.log("N progress started");

               // Listen for the completion of the initial page load
               $(window).on('load', function() {
                   NProgress.done();
                   console.log("N progress ended on page load");
               });

               // Start NProgress when an AJAX request begins
               $(document).ajaxStart(function() {
                   NProgress.start();
                   console.log("N progress started for AJAX request");
               });

               // End NProgress when an AJAX request completes
               $(document).ajaxComplete(function() {
                   NProgress.done();
                   console.log("N progress ended for AJAX request");
               });
           });
       </script>


       @yield('script')
