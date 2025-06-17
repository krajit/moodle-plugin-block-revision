define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    return {
        init: function() {

            function saveData() {
                const selectedRadio = $('input[name="learningLevel"]:checked');
                const selectedLevel = selectedRadio.length ? selectedRadio.next('label').data('value') : '';
                const count = $('#revisionCount').val();
                const date = $('#nextReview').val();
                const pageurl = window.location.pathname + window.location.search;

                if (!selectedLevel && !count && !date) {
                    // Nothing to save
                    return;
                }

                Ajax.call([{
                    methodname: 'block_revision_save_entry',
                    args: {
                        learninglevel: selectedLevel,
                        revisioncount: parseInt(count),
                        nextreview: date,
                        pageurl: pageurl
                    },
                    done: function(response) {
                        window.console.log('Saved:', response.status);
                    },
                    fail: Notification.exception
                }]);
            }

            function loadExistingData() {
                const pageurl = window.location.pathname + window.location.search;

                Ajax.call([{
                    methodname: 'block_revision_get_entry',
                    args: { pageurl: pageurl },
                    done: function(data) {
                        if (data.learninglevel) {
                            $(`label[data-value="${data.learninglevel}"]`).prev('input').prop('checked', true);
                        }
                        if (data.revisioncount !== null) {
                            $('#revisionCount').val(data.revisioncount);
                        }
                        if (data.nextreview) {
                            $('#nextReview').val(data.nextreview);
                        }
                    },
                    fail: Notification.exception
                }]);
            }

            // Attach listeners for autosave
            $('#revisionCount, #nextReview').on('input change', saveData);
            $('input[name="learningLevel"]').on('change', saveData);
            

            // Optional: initial save if you want to auto-save prepopulated data
            // saveData();

             loadExistingData(); // Fetch saved data on page load
        }
    };
});
