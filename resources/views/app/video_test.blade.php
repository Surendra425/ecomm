<html>
<head>
 <link href="http://vjs.zencdn.net/6.6.3/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
</head>

<body>
 
    @if(!empty($product->video))
                    @foreach($product->video as $video)
                        @php
                            $videos = explode('.',$video->video_url);
                        @endphp
     <video id="my-video" class="video-js" controls preload="auto" width="640" height="264"
  data-setup="{}">
 
  <source src="{{ url('doc/video').'/'.$video->video_url }}" type='video/mp4'>
    <source data-id="{{$video->id}}" data-name="{{$video->video_url}}" src="{{url('doc/video/ios').'/'.$video->video_url }}"
    type='video/mp4'>
    <source data-id="{{$video->id}}" data-name="{{$video->video_url}}" src="{{url('doc/video/web').'/'.$video->video_url }}"
    type='video/mp4'>
 
    
  </video> <br><br>
  @endforeach
     @endif              
<script src="http://vjs.zencdn.net/6.6.3/video.js"></script>

</body>
 
</html>