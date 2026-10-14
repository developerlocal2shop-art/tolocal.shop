<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('includes.client.head')

<style>

    .paypal-guide-content p{
        color: #0c263f;
    }
    .paypal-guide-content ol{
        color: #0c263f;
    }
    .paypalguidecontent {
        margin: 18px -4px;
    }

    /* MODAL CONTAINER */
    .paypal-modal {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    /* HEADER */
    .paypal-modal .modal-header {
        border-bottom: none;
        padding: 20px 24px;
        background: #ffffff;
    }

    .paypal-modal .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #0d3b66;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .paypal-modal .modal-title i {
        color: #0070ba;
        font-size: 20px;
    }

    /* CLOSE BUTTON */
    .paypal-modal .btn-close {
        background-size: 12px;
        opacity: 0.6;
    }
    .paypal-modal .btn-close:hover {
        opacity: 1;
    }

    /* BODY */
    .paypal-modal .modal-body {
        padding: 20px 24px 25px;
        background: #f8fafc;
    }

    /* LABEL */
    .paypal-modal .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .paypal-modal .form-label a {
        font-size: 12px;
        color: #0070ba;
        text-decoration: none;
    }

    .paypal-modal .form-label a:hover {
        text-decoration: underline;
    }

    /* INPUT FIELDS */
    .paypal-modal .form-control {
        height: 46px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 14px;
        padding: 10px 14px;
        background: #ffffff;
        transition: all 0.25s ease;
    }

    .paypal-modal .form-control:focus {
        border-color: #0070ba;
        box-shadow: 0 0 0 3px rgba(0, 112, 186, 0.15);
    }

    /* ERROR TEXT */
    .paypal-modal small.text-danger {
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    /* BUTTON */
    .btn-paypal {
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0070ba, #003087);
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-paypal:hover {
        background: linear-gradient(135deg, #005ea6, #002970);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0, 112, 186, 0.25);
    }

    /* INPUT SPACING */
    .paypal-modal .mb-3,
    .paypal-modal .mb-4 {
        margin-bottom: 18px !important;
    }

    /* RESPONSIVE */
    @media (max-width: 576px) {
        .paypal-modal .modal-body {
            padding: 18px;
        }

        .paypal-modal .modal-header {
            padding: 16px 18px;
        }
    }

    .btn-primary {
        padding: 5px 25px !important;
    }
    .sub_header_in {
        background-color: #2f4a7c !important;
        padding: 30px 0;
    }
    ul.comp-header li.list-group-item {
        padding-left: 3px;
        padding-right: 3px;
        padding-top: 2px;
        padding-bottom: 2px;
    }


    .btn-paypal {
        background: #0070ba;
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        padding: 12px;
        transition: all 0.3s ease;
    }

    .btn-paypal:hover {
        background: #003087;
        color: #fff;
    }

</style>

    <body>

        <x-client.commonheader/>

        <!-- Modal -->
            <div class="modal fade paypal_model" id="myModal33" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content paypal-modal">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fab fa-paypal me-2"></i> PayPal Payout Details
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form id="paypalForm">
                                <div class="mb-3">
                                    <label class="form-label">
                                        PayPal Email <b class="text-danger">*</b>
                                        <a href="" target="_blank" class="ms-1" data-bs-toggle="modal" data-bs-target="#myModal_paypal_guide">
                                            <i class="fas fa-info-circle"></i> Paypal payout guide
                                        </a>
                                    </label>
                                    <input type="email" name="paypal_email" id="paypal_email"
                                        class="form-control"
                                        value="{{ auth()->user()->paypal_email }}">
                                    <small class="text-danger error-paypal_email"></small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">PayPal Merchant ID</label>
                                    <input type="text" name="paypal_merchant_id" id="paypal_merchant_id"
                                        class="form-control"
                                        value="{{ auth()->user()->paypal_merchant_id }}">
                                    <small class="text-danger error-paypal_merchant_id"></small>
                                </div>

                                <button type="button" id="paypalBtn" class="btn btn-paypal w-100">
                                    Save PayPal Details
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>



            <div class="modal fade myModal_paypal_guide" id="myModal_paypal_guide" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content paypal-modal paypalguidecontent">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fab fa-paypal me-2"></i> PayPal Payout Guide
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="paypal-guide-content">
                                <p>To receive payouts via PayPal, please ensure you have a PayPal Business account. Follow these steps to set up your PayPal payout details:</p>
                                <ol>
                                    <li>Log in to your PayPal Business account.</li>
                                    <li>Navigate to the "Settings" or "Profile" section.</li>
                                    <li>Find the "Payouts" or "Mass Payments" settings.</li>
                                    <li>Copy your PayPal email address and Merchant ID (if required).</li>
                                    <li>Return to this page and click on "Save PayPal Details".</li>
                                    <li>Enter your PayPal email and Merchant ID, then save the information.</li>
                                </ol>
                                <p>If you encounter any issues, please contact our support team for assistance.</p>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


          @yield('content')
        <x-client.footervendor/>


    </body>

</html>
