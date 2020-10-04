toastr.options = {
"closeButton": false,
"debug": false,
"newestOnTop": false,
"progressBar": false,
"positionClass": "toast-top-right",
"preventDuplicates": false,
"onclick": null,
"showDuration": "3000",
"hideDuration": "5000",
"timeOut": "5000",
"extendedTimeOut": "1000",
"showEasing": "swing",
"hideEasing": "linear",
"showMethod": "fadeIn",
"hideMethod": "fadeOut"
};
@if(session()->has('success'))

    toastr.success(" {{ session()->get('success') }}"  , 'Success');
@endif

@if(session()->has('error'))

    toastr.error("{{ session()->get('error') }}", 'Error!');

@endif

@if (count($errors) > 0)

    @foreach ($errors->all() as $error)

        toastr.error("  {{ $error }}", 'Error!');
    @endforeach

@endif