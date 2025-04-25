<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var main_category_table_json = '{{ route('mainCategory.tablejson') }}';
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: true,
            timer: 3000
        });
    @endif

    function edit_main_category(id) {
        // Perform an AJAX call to get the category data by ID
        $.ajax({
            url: "{{ route('mainCategory.edit', ':id') }}".replace(':id', id),
            method: 'GET',
            success: function(response) {
                // Assuming `response` contains the main category data
                $('#cat_id').val(response.id);
                $('#sort_order').val(response.sort_order); // Fill sort order field
                $('#name').val(response.name); // Fill category name field

                // Update form action for editing
                var updateUrl = "{{ route('mainCategory.update', ':id') }}";
                $('#main_category_form').attr('action', updateUrl.replace(':id', response.id));
                $('#method_field').html('<input type="hidden" name="_method" value="PUT">');
                $('#main_category_form button[type=submit]').text('Update');

                // Set focus on the first field and scroll to it
                var $firstField = $('#sort_order');
                $('html, body').animate({
                    scrollTop: $firstField.offset().top - 250
                }, 500);
                $firstField.focus();
            },
            error: function(xhr) {
                console.log(xhr.responseText); // Log error if needed
            }
        });
    }



    function delete_main_category(id) {
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
                    url: "{{ route('mainCategory.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}' // CSRF token is required for DELETE requests
                    },
                    success: function(response) {
                        console.log(response);
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
                            'An error occurred while trying to delete the category.',
                            'error'
                        );
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    }
</script>
