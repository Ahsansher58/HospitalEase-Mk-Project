<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var manage_business_listing_table_json = '{{ route('manageBusinessListing.tablejson') }}';
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: true,
            timer: 3000
        });
    @endif
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'error!',
            text: '{{ session('error') }}',
            showConfirmButton: true,
            timer: 3000
        });
    @endif

    document.getElementById('downloadCsvButton').addEventListener('click', function() {
        var searchTerm = document.getElementById('customSearchInput').value;
        var url = "{{ route('business.export-csv') }}";
        if (searchTerm) {
            url += '?search=' + encodeURIComponent(searchTerm);
        }
        window.location.href = url;
    });

    function delete_business_listing(id) {
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
                    url: "{{ route('business.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
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
                        Swal.fire(
                            'Error!',
                            'An error occurred while trying to delete the Business Listing.',
                            'error'
                        );
                        console.error('Status:', xhr.status);
                        console.error('Response:', xhr.responseText);
                    }
                });
            }
        });
    }

    function block_business_listing(id, status) {
        // Determine the new status based on the current status
        const newStatus = status === 0 ? 1 : 0; // If status is 0, set new status to 1 (blocked), and vice versa

        // Ask for confirmation using SweetAlert
        Swal.fire({
            title: 'Are you sure?',
            text: newStatus === 0 ? "This ad will be blocked!" : "This ad will be activated!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to block/unblock the advertisement
                $.ajax({
                    url: "{{ route('business.block', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF token is required for POST requests
                        status: newStatus // Send the new status to the server
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: newStatus === 0 ? 'Blocked' : 'Activated',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location
                                    .reload(); // Refresh the page to see the updated status
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        // Handle errors if the request fails
                        Swal.fire(
                            'Error!',
                            'An error occurred while trying to block/unblock the advertisement.',
                            'error'
                        );
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    }
</script>
