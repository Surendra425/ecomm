@if (isset($errors) && count($errors) > 0)
    <div class="alert alert-danger alert-messages">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session()->has('success'))
  <div class="alert alert-success alert-messages">
    {{ session()->get('success') }}
  </div>
@endif
                    
@if(session()->has('error'))
					
  <div class="alert alert-danger alert-messages">
    {{ session()->get('error') }}
  </div>
@endif

<div id="error-message" style="display:none" class="alert alert-danger alert-messages">
</div>
<div id="success-message" style="display:none" class="alert alert-success alert-messages">
</div>