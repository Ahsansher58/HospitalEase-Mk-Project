<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var users_table_json = '{{ route('users.table_json') }}';
    var get_countries = '{{ route('sadmin.getCountries') }}';
    var get_states = '{{ route('sadmin.getStates') }}';
    var get_cities = '{{ route('sadmin.getCities') }}';

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: true,
            timer: 3000
        });
    @endif
    function csv_export_users() {
        var country = document.getElementById('country').value;
        var state = document.getElementById('state').value;
        var city = document.getElementById('city').value;
        var url = "{{ route('users.exportcsv') }}";
        if (country) {
            url += '?country=' + encodeURIComponent(country);
        }
        if (state) {
            url += (url.includes('?') ? '&' : '?') + 'state=' + encodeURIComponent(state);
        }
        if (city) {
            url += (url.includes('?') ? '&' : '?') + 'city=' + encodeURIComponent(city);
        }
        window.location.href = url;
    }

    function delete_user(id) {
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
                $.ajax({
                    url: "{{ route('users.destroy', ':id') }}".replace(':id', id),
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
                        // Handle errors if the request fails
                        Swal.fire(
                            'Error!',
                            'An error occurred while trying to delete the User.',
                            'error'
                        );
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    }

    function block_unblock_user(id) {
        $.ajax({
            url: "{{ route('users.block_unblock', ':id') }}".replace(':id', id),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
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
                    'An error occurred while trying to update the user status.',
                    'error'
                );
                console.error(xhr.responseText);
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        // Fetch all countries
        $.ajax({
            url: get_countries,
            method: 'GET',
            success: function(countries) {
                let countrySelect = $('#country');
                $.each(countries, function(index, country) {
                    countrySelect.append('<option value="' + country + '">' + country +
                        '</option>');
                });
            },
        });
    });
</script>
