
@extends('layouts.admin')


@section('content')
    @php
        $pageTitle ="eventAdd";
    $contentTitle =empty($event) ? 'Create Event' : 'Edit Event';
    @endphp
    <!--begin::Portlet-->
    <form id="event_create" class="m-form m-form--fit m-form--label-align-right form"
              enctype="multipart/form-data" method="post"
              action="<?php echo (!empty($event)) ? (url(route('eventUpdate', ['event' => $event['id']]))) : (url(route('events.store'))); ?>"
              novalidate>
    <div class="m-portlet">
        @include('admin.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        <input type="hidden" name="isEditEvent" id="isEditEvent" value="{{ (empty($event)) ? 0 : 1}}"> 
                        <?php echo (empty($event)) ? 'Create Event' : 'Edit Event'; ?>
                    </h3>
                </div>
            </div>
        </div>
        
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$event->id or ''}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="title"
                                   name="title" value="{{ $event->title or ''}}" placeholder="Title" @if(isset($event) &&$event->event_name != '' && $event->event_name == 'KUWAIT') readonly @endif>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="event_type">Event type <span class="text-danger">*</span></label>
                            <select class="custom-select col-md-6" name="event_type" id="event_type">
                                <option value="" selected="">Select Event type</option>
                                <option value="contact" <?php echo (!empty($event) && ($event->event_type == 'contact')) ? 'selected' : ''; ?>>Contact</option>
                                <option value="location" <?php echo (!empty($event) && ($event->event_type == 'location')) ? 'selected' : ''; ?>>Location</option>                          
                            </select>
                        </div>
                        
                        <div class="form-group m-form__group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control m-input m-input--square" id="address"
                               name="address" value="{{ $event->address or ''}}">
                        </div>
                        
                        <div class="form-group m-form__group">
                            <label for="start_date">Select Date</label>
                            <div class='input-group' id='m_daterangepicker_2'>
                                <input type='text' class="form-control m-input" id="start_date_time" name="start_date_time" readonly  placeholder="Select start date" value="{{ isset($event->start_date_time) ? date('d/m/Y H:i',strtotime($event->start_date_time)) : ''}}" />
                                <span onclick="document.getElementById('start_date_time').focus();" class="input-group-addon"><i class="la la-calendar-check-o"></i></span>
                            </div>
                            <label id="start_date_time-error" class="error" for="start_date_time"></label>
                    	</div>
                    </div>
                    <div class="col-md-6">
                    
                        <div class="form-group m-form__group">
                            <label for="address">Latitude</label>
                            <input type="text" class="form-control phonNumberOnly m-input m-input--square" id="latitude"
                               name="latitude" value="{{ $event->latitude or ''}}">
                        </div>
                        
                        <div class="form-group m-form__group">
                            <label for="address">Longitude</label>
                            <input type="text" class="form-control phonNumberOnly m-input m-input--square" id="longitude"
                               name="longitude" value="{{ $event->longitude or ''}}">
                        </div>
                        
                        <div class="form-group m-form__group">
                            <label for="contact_number">Contact number</label>
                            <input type="text" class="form-control phonNumberOnly m-input m-input--square" id="contact_number"
                               name="contact_number" value="{{ $event->contact_number or ''}}">
                        </div>
                        
                        <div class="form-group m-form__group">
                            <label for="status">Status : <span class="text-danger">*</span></label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" id="status_1" name="status" value="1" {{ empty($event) ? 'checked' : '' }} {{ (!empty($event) && $event->status == 1) ? 'checked' : '' }}> Active
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" id="status_2" name="status" value="0" {{ (!empty($event) && $event->status == 0) ? 'checked' : '' }}> Inactive
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-12">
                        <div class="form-group m-form__group" id="long_description_div">
                            <label>Description</label>
                            <textarea name="description" id="description" class="summernote">{!! !empty($event) ? $event->description : '' !!}</textarea>
                            <label id="long_description-error" class="error" for="long_description"></label>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    
    <div class="m-portlet">
                <div class="m-portlet__head">
                    <h6 class="m-portlet__head-caption">Images and Video</h6>
                </div>

                <fieldset class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group" id="eventImage_div">
                                <label>Event Image</label>
                                <div class="col-lg-12 col-md-9 col-sm-12">
                                    <div class="m-dropzone dropzone m-dropzone--success" action="#" id="eventImage">
                                        <div class="m-dropzone__msg dz-message needsclick">
                                            <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                                            <span class="m-dropzone__msg-desc">Only image are allowed for upload</span>
                                        </div>
                                    </div>
                                </div>
                                <p id="eventImage_message" class="text-danger"></p>
                                 <label id="eventImages-error" class="error" for="eventImages"></label>
                            </div>
                           
                        </div>
                        
                        <div class="m--hide">
                            <textarea name="eventImages" id="eventImages"></textarea>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Event Video</label>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="m-dropzone dropzone m-dropzone--success" action="#" id="video">
                                        <div class="m-dropzone__msg dz-message needsclick">
                                            <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                                            <span class="m-dropzone__msg-desc">Only video are allowed for upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php $imagesList = []; @endphp
                        @if(!empty($eventImages) && count($eventImages) > 0)
<input type="hidden" value="{{count($eventImages)}}" name="totalImages" id="totalImages" >
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    <label>Event Images</label>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        @foreach($eventImages as $image)
                                            @php $imagesList[] = $image->file; @endphp
                                            <div class="img-wraps" id="media_div_{{$image->id}}">
                                                <span class="close" data-type="image" data-id="{{$image->id}}">&times;</span>
                                                <img src=" {{url('doc/events/images').'/'.$image->file }}"
                                                     width="100" data-id="{{$image->id}}"
                                                     data-name="{{$image->file}}" id="imageEvent">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php $imagesName = implode(',',$imagesList); @endphp
                        <div class="m--hide">
                            <textarea name="eventImages1" id="eventImages1">{{$imagesName}}</textarea>
                        </div>

                    @if(!empty($eventVideos) && count($eventVideos) > 0)
							<input type="hidden" value="{{count($eventVideos)}}" name="totalVideo" id="totalVideo" >
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    <label>Event Video</label>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        @foreach($eventVideos as $videos)
                                            @php
                                                $video = explode('.',$videos->video_url);
                                            @endphp
                                            <div class="img-wraps video" id="media_div_{{$videos->id}}">
                                                <span class="close" data-type="video" data-id="{{$videos->id}}">&times;
													
                                                <video width="320" controls
                                                            type="video/mp4">
                                                    <source src="{{url('doc/events/videos').'/'.$videos->file }}"
                                                            type="video/mp4">
                                                  
                                                </video>
												 </span>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="m--hide">
                        <textarea id="eventVideo" name="eventVideo"></textarea>
                    </div>
                    <!-- <video class="hidden" width="320" height="240" controls>
                         <source src="" type="video/mp4">
                    </video> -->
                </fieldset>
                
                 <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
           
            </form>
    <!--end::Portlet-->
@endsection
@section('js')
<script src="{{ url('assets/demo/default/custom/components/forms/widgets/dropzone.js')}}" type="text/javascript"></script>
{{--<script src="{{url('assets/demo/default/custom/components/forms/widgets/summernote.js')}}" type="text/javascript"></script>--}}
<script src="{{ url('assets/demo/default/custom/components/forms/widgets/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
<script src="{{url('assets/ckeditor/ckeditor.js')}}"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIszvWipx3KBHnS2iyg9gxjf-FPHXQ-LQ&libraries=places"></script>
 -->

    <script type="text/javascript">

        $(document).ready(function () {

            CKEDITOR.replace('description');
            
            var date = new Date();

            date.setYear({{$currentYear}});
            date.setMonth({{$currentMonth}});
            date.setDate({{$currentDay}});
            date.setHours({{$currentHour}});
            date.setMinutes({{$currentMinute}});
            
        	 $('#start_date_time').datetimepicker({
        		 startDate:date,
                 format: "dd/mm/yyyy hh:ii",
                 pickerPosition: 'top-right',
             });
        	
        	/*var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);

             google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();

                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                var placeId = place.place_id;
                // to set city name, using the locality param
                var componentForm = {
                  locality: 'short_name',
                };
                
                for (var i = 0; i < place.address_components.length; i++) {
                  var addressType = place.address_components[i].types[0];
                  if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];

                    document.getElementById("city").value = val;
                  }
                }
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;
            });
             */

             
            $("#event_create").validate({
            	 ignore: [],
                rules: {
                    title: {
                        required: true,
                        minlength: 2,
                    },
                    event_type: {
                        required: true,
                    },
                    address: {
                    	required: function(element){
                            return $("#event_type").val() == "location";
                        }
                    },
                    start_date_time: {
                        required: true,
                    },
                    
                    latitude: {
                    	required: function(element){
                            return $("#event_type").val() == "location";
                        }
                    },
                    longitude: {
                    	required: function(element){
                            return $("#event_type").val() == "location";
                        }
                    },
                    
                    contact_number: {
                    	required: function(element){
                            return $("#event_type").val() == "contact";
                        }
                    },
                    status: {
                        required: true,
                    },
                    eventImages: {
                    	required: function(element){
                            return $("#isEditEvent").val() == 0;
                        }
                    }
                    
                },
                messages: {
                	title: {
                        required: "event title is required",
                    },
                    event_type: {
                        required: "event type is required",
                    },
                    address: {
                        required: "address is required",
                    },
                    start_date_time: {
                        required: "start date time is required",
                    },
                    longitude: {
                        required: "longitude is required",
                    },
                    latitude: {
                        required: "latitude is required",
                    },
                    contact_number: {
                        required: "contact number is required",
                    },
                    status: {
                        required: "status is required",
                    },
                    eventImages: {
                    	required: "event image is required",
                    }
                    
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });

            
        });

        $("#eventImage").dropzone(
                {
                    url: "{{url(route('uploadEventImage'))}}" ,
                    acceptedFiles: "image/*",
                    maxFilesize: 2, // MB
                    addRemoveLinks: true,

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    accept: function(file, done) {
                    	done();
                    },
                    init: function() {
                        this.on("maxfilesexceeded", function(file){
                            alert("You are only allow to upload 5 event Image");
                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                        });
                        this.on("thumbnail", function(file) {
                            if(file.size > 4194304){
                                alert("Your image size is grater then 2MB.");
                                this.removeFile(file);
                            }
                        });
                    },
                    error:function(file, response){
                        alert(response);
                        this.removeFile(file);
                    },
                    success: function(file, response){
                        $("#eventImages").append(response);
                        console.log(response);
                        // alert(response);
                    },


                    removedfile: function(file) {

                            var str1 = $("#eventImages").val();
                            var str =  str1.split('""');
                            var img = file.name;

                            var str2 = img.replace(/ /g, '-');
                            var str2 = str2.split(".");
                            $.each(str, function( index, value ) {
                                var rgxp = new RegExp(str2[0], "g");
                                if(value.match(rgxp)){
                                    var url = $("#eventImages").val();
                                    var remove = value;
                                    url = url.replace (remove, "");
                                    $("#eventImages").empty();
                                    $("#eventImages").append(url);
                                }
                            });
                       // this.removeFile(file);
                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                    }
                }
        );
        $("#video").dropzone(
                {
                    url: "{{url(route('uploadEventVideo'))}}",
                    maxFilesize: 10, // MB
                     timeout: 180000,
                    addRemoveLinks: true,
                    acceptedFiles: "video/*",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                     accept: function(file,done) {
                    	 var totalVideo = $("#totalVideo").val();
                         done();
                    }, 
                    init: function() {
                        this.on("maxfilesexceeded", function(file){
                            alert("You are only allow to upload 2 event Video");
                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                        });
                    },
                    success: function (file, response) {

                        $("#eventVideo").append(response);
                        // alert(response);
                    },
                    error:function(file, response){
                         this.removeFile(file);
                    },
                    removedfile: function(file) {

                            var str1 = $("#eventVideo").val();
                            var img = file.name;

                            var str2 = img.replace(/ /g, '-');
                            var str2 = str2.split(".");

                            var rgxp = new RegExp(str2[0], "g");
                            if(str1.match(rgxp)){
                                var url = $("#eventVideo").val();
                                var remove = str1;
                                url = url.replace (remove, "");
                                $("#eventVideo").empty();
                                $("#eventVideo").append(url);
                            }

                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                    }
                }
        );


        $('.img-wraps .close').on('click', function () {

        	
            var id = $(this).data('id');
            var type = $(this).data('type');

            if(!confirm('Are you sure want to delete this '+type+' ?'))
        	{
            	return false;
        	}
        	
            $.ajax({
                url: baseUrl + '/event-media/delete',
                method: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'id': id,
                },
                success: function(result){

                    $('#media_div_'+id).remove();
                    if(type == "image")
                    {
                    	var total = $("#totalImages").val();
                    	var totalimage = total-1;
                    	$("#totalImages").val(totalimage);
                            
                    }

                },
                error:function(response){
                    alert(response.responseJSON.message);
                },
            });
        });
    </script>
@endsection
