jQuery(document).ready(function ($) {
    const $checkbox = $('#wpcf-migration-consent-checkbox-input');
    const $confirmBtn = $('#wpcf-migration-confirm-btn');
    const $cancelBtn = $('#wpcf-migration-cancel-btn');
    const $notice = $('#wpcf-migration-notice');
    const $cancelInput = $('#wpcf-migration-cancel-input');

    $checkbox.prop('disabled', false);

    $checkbox.on('change', function () {
        const isChecked = $(this).is(':checked');

        $confirmBtn.prop('disabled', !isChecked);

        if (isChecked) {
            $confirmBtn.addClass('active');
            $checkbox.val(1);
        } else {
            $confirmBtn.removeClass('active');
            $checkbox.val(0);
        }
    });

    $confirmBtn.on('click', function (e) {
        if (!$checkbox.prop('checked')) {
            e.preventDefault();
            return;
        }

        // Optional loading state
        $(this)
            .prop('disabled', true)
            .addClass('loading');

        // Submit the form
        $(this).closest('form').trigger('submit');
    });

    $cancelBtn.on('click', function (e) {
        e.preventDefault();
        $cancelInput.val(1);
        $(this).closest('form').trigger('submit');
        $notice.fadeOut(200);
    });


    const $modal = $('#wpcf-migration-modal-overlay');
    const $openModalBtn = $('#wpcf-open-migration-modal');
    const $closeModalBtn = $('#wpcf-close-migration-modal');
    const $acknowledgeBtn = $('#wpcf-migration-modal-confirm');

    $openModalBtn.on('click', function () {
        $modal.addClass('active');
        $('body').addClass('wpcf-modal-open');
    });

    $closeModalBtn.on('click', function () {
        $modal.removeClass('active');
        $('body').removeClass('wpcf-modal-open');
    });

    $acknowledgeBtn.on('click', function () {
        $modal.removeClass('active');
        $('body').removeClass('wpcf-modal-open');
    });

    $modal.on('click', function (e) {
        if ($(e.target).is('#wpcf-migration-modal-overlay')) {
            $modal.removeClass('active');
            $('body').removeClass('wpcf-modal-open');
        }
    });
});