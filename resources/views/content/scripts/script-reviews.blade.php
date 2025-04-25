<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var reviews_table_json = '{{ route('reviews_table_json') }}';
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: true,
            timer: 3000
        });
    @endif
    function csv_export_review(id) {
        var date_range = document.getElementById('review-flatpickr-range').value;
        var url = "{{ route('reviews.exportcsv') }}";
        if (date_range) {
            url += '?date_range=' + encodeURIComponent(date_range);
        }
        window.location.href = url;
    }

    function delete_review(id) {
        // Ask for confirmation using SweetAlert
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to delete the category
                $.ajax({
                    url: "{{ route('reviews.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}' // CSRF token is required for DELETE requests
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        // Handle errors if the request fails
                        Swal.fire(
                            'Error!',
                            'An error occurred while trying to delete the Review.',
                            'error'
                        );
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    }

    function block_unblock_review(id) {
        // Perform AJAX request to toggle the status
        $.ajax({
            url: "{{ route('reviews.block_unblock', ':id') }}".replace(':id', id),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}' // CSRF token is required for POST requests
            },
            success: function(response) {
                if (response.success) {
                    // Show a success message
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload the page to reflect changes
                    });
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr) {
                // Handle errors if the request fails
                Swal.fire(
                    'Error!',
                    'An error occurred while trying to update the review status.',
                    'error'
                );
                console.error(xhr.responseText);
            }
        });
    }
</script>
