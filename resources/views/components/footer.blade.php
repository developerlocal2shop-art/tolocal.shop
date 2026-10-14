    <script>

        $(document).ready(function() {
            document.querySelectorAll('.ckeditor').forEach((el) => {
                ClassicEditor.create(el)
                    .catch(error => console.error(error));
            });
        });

        function charCount(textvalue,id,maxlenght){
            var len = 0;
            len = textvalue.length
            if(len > maxlenght){
                return false;
            }
            else if (len > 0) {
                if($(".errorshortcontent").length >= 1){
                    $('.errorshortcontent').remove();
                }
                $( "#"+id ).after("<span class='text-danger errorshortcontent'>"+ ( maxlenght - len )+"/"+( maxlenght )+" characters left! </span>");
            }
            else {
                if($(".errorshortcontent").length >= 1){
                    $('.errorshortcontent').remove();
                }
                $( "#"+id ).after("<span class='text-danger errorshortcontent'>"+ ( maxlenght - len )+"/"+( maxlenght )+" characters left! </span>");
            }

        }

        $("document").ready(function(){
            setTimeout(function(){
            $("div.alert-success").remove();
            }, 5000 ); // 5 secs

        });
    </script>
    
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->

    <script src="{{ asset('assets/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('assets/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <!--This page JavaScript -->
    <!--chartis chart-->


    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" crossorigin="anonymous"></script>

    <script type="text/javascript">
        var baseUrl = "{{URL::to('/')}}";
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    

    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.2/jquery.ui.touch-punch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" ></script>
    <script src="{{ asset('assets/js/fontawesome-iconpicker.min.js') }}"></script>
    <script src="{{ asset('assets/client/js/toastr.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.js') }}"></script>

     


    