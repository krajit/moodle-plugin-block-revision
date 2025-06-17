define(['jquery', 'core/ajax', 'core/notification', 'core/templates'], function($, Ajax, Notification, Templates) {
    return {
        init: function() {
            const courseid = new URLSearchParams(window.location.search).get('courseid');

            Ajax.call([{
                methodname: 'block_revision_get_all_entries',
                args: { courseid: parseInt(courseid) },
                done: function(data) {
                    Templates.render('block_revision/edittable', data)
                        .then(html => $('#revision-table-wrapper').html(html));
                },
                fail: Notification.exception
            }]);

            // Handle cell edits (delegate in case table is loaded after DOM ready)
            $(document).on('blur', 'td.editable', function() {
                const cell = $(this);
                const value = cell.text().trim();
                const field = cell.data('field');
                const id = cell.closest('tr').data('id');

                Ajax.call([{
                    methodname: 'block_revision_update_entry',
                    args: {
                        id: parseInt(id),
                        field: field,
                        value: value
                    },
                    done: function(response) {
                        window.console.log('Updated successfully:', response);
                    },
                    fail: Notification.exception
                }]);
            });
        }
    };
});
