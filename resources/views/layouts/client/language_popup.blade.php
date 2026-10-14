    <style>
        p#staticBackdropLabel {
            color: #040303;
            font-weight: 500;
            font-size: 18px;
        }
        .modal-header {
            padding: 2rem;
            border-bottom: none;
        }
        div#staticBackdrop {
            margin: 175px auto;
            max-width: 260px;
            background-color: transparent;
            border-radius: 26px;
            backdrop-filter: blur(55px);
            justify-content: center;
            align-items: center;
            height: 98px;
            padding: 2px 17px;
            border: 1px solid #ccc;
        }
        #staticBackdrop .goog-te-gadget-simple {
            background-color: #fff;
            border-left: 1px solid #d5d5d5;
            border-top: 1px solid #9b9b9b;
            border-bottom: 1px solid #e8e8e8;
            border-right: 1px solid #d5d5d5;
            font-size: 10pt;
            display: inline-block;
            padding-top: 1px;
            padding-bottom: 2px;
            cursor: pointer;
            text-align: center;
        }
        #staticBackdrop .modal-content {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-clip: padding-box;
            border: none !important;
            border-radius: .3rem;
            outline: 0;
            background-color: transparent;
            color: #fff;
            padding: 0px 4px;
        }
        .google_translate_element_home .goog-te-gadget-simple {
            width: 100%;
        }
        .pop_home_btn {
            cursor: pointer;
            border: 1px solid #a29b81;
        }
    </style>

        @if(session()->has("is_lang"))@else
            <div class="">
                {{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <p class="modal-title fs-5" id="staticBackdropLabel">Access to page</p>
                        <button type="button" class="btn-close pop_home_btn" data-bs-dismiss="modal" aria-label="Close">OK</button>
                        </div>

                    </div>
                    </div>
                </div> --}}
                {{-- <div class="modal-body" style="width: 160px; text-align: center; margin: 0 auto;">
                            <b id="google_translate_element_home" class="google_translate_element_home" ></b>
                        </div> --}}
            </div>
        @endif
