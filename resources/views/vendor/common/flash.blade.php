{{-- @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}
@if(session()->has('success'))			
  <div class="alert alert-success">
    {{ session()->get('success') }}
  </div>
@endif
                    
@if(session()->has('error'))
					
  <div class="alert alert-danger">
    {{ session()->get('error') }}
  </div>
@endif
{{--<div id="error-message" style="display:none" class="alert alert-danger">
</div>--}}
<div id="success-message" style="display:none" class="alert alert-success">
</div>