$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    $('#jobForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '{{ route("jobs.store") }}',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    location.reload(); 
                }
            }
        });
    });

    $('.toggle-completed').on('click', function() {
        const card = $(this).closest('.job-card');
        const jobId = card.data('id');

        $.ajax({
            type: 'PATCH',
            url: `/jobs/${jobId}/toggle`,
            success: function(response) {
                if (response.success) {
                    
                    if (response.status) {
                        card.remove();
                        $('#completedJobList').append(card); 
                        card.find('.badge').removeClass('badge-secondary').addClass('badge-success').text('Completed');
                    } else {
                        card.remove(); 
                        $('#jobList').append(card); 
                        card.find('.badge').removeClass('badge-success').addClass('badge-secondary').text('Pending');
                    }
                }
            }
        });
    });

    $('.delete-job').on('click', function() {
        const card = $(this).closest('.job-card');
        const jobId = card.data('id');

        if (confirm('Apakah Anda yakin ingin menghapus job ini?')) {
            $.ajax({
                type: 'DELETE',
                url: `/jobs/${jobId}`,
                success: function(response) {
                    if (response.success) {
                        card.remove(); 
                    }
                }
            });
        }
    });
});