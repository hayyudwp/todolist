<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Job List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Job List</h1>
        
        <form id="jobForm" class="mb-4">
            <div class="input-group">
                <input type="text" name="title" class="form-control" placeholder="Job Title" required>
                <input type="date" name="date" class="form-control"> 
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Add Job</button>
                </div>
            </div>
        </form>

        <h2>Pending Jobs</h2>
        <div id="jobList">
            @foreach ($jobs as $job)
                @if (!$job->status)
                    <div class="job-card" data-id="{{ $job->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="job-title">{{ $job->title }} - {{ $job->date }}</span>
                            <span>
                                <span class="badge badge-secondary status-badge">Pending</span>
                                <button class="btn btn-info btn-sm toggle-completed">Complete</button>
                                <button class="btn btn-danger btn-sm delete-job">Hapus</button> 
                            </span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <h2>Completed Jobs</h2>
        <div id="completedJobList">
            @foreach ($jobs as $job)
                @if ($job->status)
                    <div class="job-card" data-id="{{ $job->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="job-title">{{ $job->title }} - {{ $job->date }}</span>
                            <span>
                                <span class="badge badge-success status-badge">Completed</span>
                                
                            </span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <script>    
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
                                card.remove(); // Hapus card dari tampilan
                            } else {
                                alert('Gagal menghapus job: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan saat menghapus job.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
