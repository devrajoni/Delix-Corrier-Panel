<style>
    .expireModal .modal-content {
        padding: 30px;
        border-radius: 16px;
    }

    .expireModal .modal-content .modal-header {
        padding: 0;
        padding-bottom: 15px;
    }

    .expireModal .modal-content .modal-header .modal-title {
        font-size: 20px;
        font-weight: 500;
        line-height: 28px;
        color: #556068;
        margin-bottom: 0;
    }

    .expireModal .modal-content .modal-header .close {
        display: none; /* Hides the close button */
    }

    div#accessDeniedModal {
        pointer-events: none;
    }

    .expireModal .modal-content .modal-body {
        padding: 10px 0;
        line-height: 22px;
    }

    .expireModal .modal-content .modal-footer {
        border: none;
        padding: 0;
        margin: 0;
        border-radius: 0;
        padding-top: 10px;
        justify-content: center;
    }

    .expireModal .modal-content .modal-footer .btn {
        padding: 10px 28px;
        margin: 0;
        background-color: var(--sg-primary);
        border-color: var(--sg-primary);
        color: #fff;
        width: 200px;
    }


    body.modal-open {
        pointer-events: none; /* Disable pointer events for the body */
        overflow: hidden; /* Prevent scrolling */
    }

    .modal.fade.show {
        pointer-events: auto;
    }
</style>

<div class="modal fade expireModal" id="accessDeniedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('package_expired') }}</h5>
            </div>
            <div class="modal-body">
                <p>{{ __('your_subscription_package_already_expired_please_complete_the_payment_and_continue_your_business') }} <span id="daysRemaining"></span>.</p>
            </div>
            <div class="modal-footer">
                <a href="https://delix.cloud" target="_blank" class="btn btn-secondary">{{ __('pay_now') }}</a>

            </div>
            <div>
                <a href="{{  route('free.package') }}" class=" btn-link text-danger d-flex justify-content-center align-items-center pt-2">{{ __('i_want_to_use_free_plan') }}</a>
            </div>
        </div>
    </div>
</div>


