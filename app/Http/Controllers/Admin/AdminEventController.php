<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 12/1/18
 * Time: 6:26 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Event;
use App\Helpers\ImageHelper;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use App\EventMedia;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class AdminEventController extends Controller
{
    private $validationRules = [
        'title' => 'required',
        'description' => 'required',
        'address' => 'required_if:event_type,location',
        'contact_number' => 'required_if:event_type,location',
        'latitude' => 'required_if:event_type,location',
        'longitude' => 'required_if:event_type,location',
        'eventImages' => 'required',
        'event_type' => 'required',
        'start_date_time' => 'required',
        
    ];
    
    /**
     * Display Event details.
     *
     * @return json
     */
    public function index()
    {
        return view('admin.events.events_list');
    }

    /**
     * Search Event.
     *
     * @return json
     */
    public function search(Request $request)
    {
        if($request->ajax()) {
            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);
            
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
                
                $query = Event::select('*');
                
                $orderDir = $request->order[0]['dir'];
                $orderColumnId = $request->order[0]['column'];
                $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
                $this->filterEvent($request->search['value'], $query);
                
                $events = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);
                
                $data = json_decode(json_encode($events));
                
                $data->recordsFiltered = $data->recordsTotal = $data->total;

                foreach ($data->data as $event) {
                    
                    $event->start_date_time = Carbon::parse($event->start_date_time)->format('d/m/Y H:i');
                    
                    $event->action = '<a href="'.url(route('events.edit', ['event' => $event->id ])).'" title="Edit"><i class="la la-edit"></i></a>';
                    $event->status = ($event->status === 1) ? '<a href="'.url(route('changeEventStatus', ['event' => $event->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                        : '<a href="'.url(route('changeEventStatus', ['event' => $event->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
                }
                
                return response()->json($data);
        }
        
    }
    /**
     * Filter Events listing.
     *
     * @param $search
     * @return $query
     */
    private function filterEvent($search, $query)
    {
        $query->where('title', 'like', '%'.$search.'%')
              ->orWhere('event_type', 'like', '%'.$search.'%')
              ->orWhere('contact_number', 'like', '%'.$search.'%');
    }
    
    /**
     * Display create country page.
     *
     * @return json
     */
    public function create()
    {
        $currentYear = Carbon::now()->year;
        $currentDay = Carbon::now()->day;
        $currentMinute = Carbon::now()->minute;
        $currentHour = Carbon::now()->hour;
        $currentMonth = Carbon::now()->month - 1;
        
        
        return view('admin.events.event_create', [
            'currentYear' => $currentYear,
            'currentDay' => $currentDay,
            'currentMinute' => $currentMinute,
            'currentHour' => $currentHour,
            'currentMonth' => $currentMonth,
        ]);
    }
    /**
     * Save the Event.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        
        $this->validate($request, $this->validationRules);
        $event = new Event();
        $event->fill($request->all());
        
        $event->start_date_time = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->start_date_time)));
        
        if ($event->save()) {

            $event->slug = str_slug($event->title.'-'.$event->id);
            $event->save();

            $this->addFiles($event, $request->eventImages);
            $this->addFiles($event, $request->eventVideo, 'video');
            
            return redirect(route('events.index'))->with('success',trans('messages.event.added'));
        }
        
        return redirect(route('events.index'))->with('error', trans('messages.error'));
    }
    
    /**
     * Change status of the event.
     *
     * @param Event $event
     * @return json
     */
    public function changeStatus(Event $event)
    {
        $event->status = !$event->status;
        
        if($event->save()) {
            
            return redirect(route('events.index'))->with('success', trans('messages.event.change_status'));
        }
        
        return redirect(route('events.index'))->with('error', trans('messages.error'));
    }
    
    /**
     * Show Event edit page.
     *
     * @param Event $event
     * @return json
     */
    public function edit(Event $event)
    {
        $eventMedias = $event->media;
        $eventImages = $eventMedias->where('type', 'image');
        $eventVideos = $eventMedias->where('type', 'video');
        
        $currentYear = Carbon::now()->year;
        $currentDay = Carbon::now()->day;
        $currentMinute = Carbon::now()->minute;
        $currentHour = Carbon::now()->hour;
        $currentMonth = Carbon::now()->month - 1;
        
        return view('admin.events.event_create', [
            'event' => $event,
            'eventImages' => $eventImages,
            'eventVideos' => $eventVideos,
            'currentYear' => $currentYear,
            'currentDay' => $currentDay,
            'currentMinute' => $currentMinute,
            'currentHour' => $currentHour,
            'currentMonth' => $currentMonth,
        ]);
    }
    
    /**
     * Update the events.
     *
     * @param Request $request
     * @param int $event
     * @return json
     */
    public function update(Request $request, Event $event)
    {
        unset($this->validationRules['eventImages']);
        $this->validate($request, $this->validationRules);

        $event->fill($request->all());

        $event->start_date_time = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->start_date_time)));
        
        
        /* if($request->hasFile('image'))
        {
            $imageName = $this->uploadEventImage($request->image);
            $event->image = $imageName;
        } */
        
        if ($event->save()) {
            $event->slug = str_slug($event->title.'-'.$event->id);
            $event->save();
            $this->addFiles($event, $request->eventImages);
            $this->addFiles($event, $request->eventVideo, 'video');
            
            return redirect(route('events.index'))->with('success', trans('messages.event.updated'));
        }
        
        return redirect(route('events.index'))->with('error', trans('messages.error'));
    }    
    
    
    /**
     * Add images in database.
     *
     * @param Request $request
     * @param int $event
     * @return json
     */
    public function addFiles($event, $files, $type="image")
    {
        $files = explode('"', $files);
        $files = array_filter($files);
        $eventMediaData = [];
        
        if(!empty($files))
        {
            foreach ($files as $file) {
                $date =  Carbon::now()->toDateTimeString();
                
                $eventMediaData[] = [
                    'event_id' => $event->id,
                    'type' => $type,
                    'file' => $file,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }
        }
        
        if(!empty($eventMediaData))
        {
            EventMedia::insert($eventMediaData);
        }
    
    }   
    
    
    
    public function uploadEventImage(Request $request){
        set_time_limit(0);
        $image = Input::file('file');
        //echo "<pre>";print_r($image);die;
        $destinationPath = public_path('doc/events/images_temp');
        $img = getimagesize($image);
        /*echo "$img[0]";echo "$img[1]";print_r($img);
         die;*/
        $user_img = '';
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img);
        if($data){
            
            $imgUrl = public_path('doc/events/images_temp/').'/'.$data;
            $Path = public_path('doc/events/images');
            if($img[0] < 200 || $img[1] < 200 ){
                $thumb_img = Image::make($imgUrl)->resizeCanvas(255, 255, 'center', false, 'ffffff');
                $main_img = Image::make($imgUrl)->resizeCanvas(540, 540, 'center', false, 'ffffff');
            }else{
                $thumb_img = Image::make($imgUrl)->resize(255, 255, function ($constraint) {
                    $constraint->aspectRatio();} );
                    $main_img = Image::make($imgUrl)->resize(540, 540, function ($constraint) {
                        $constraint->aspectRatio();} );
                        
                        // Fill up the blank spaces with transparent color
                        $thumb_img->resizeCanvas(255, 255, 'center', false, array(255, 255, 255, 0));
                        $main_img->resizeCanvas(540, 540, 'center', false, array(255, 255, 255, 0));
            }
            /* if($img[0] >= 540 && $img[1] >= 540 ){
             $thumb_img = Image::make($imgUrl)->fit(255, 255, function ($constraint) {
             $constraint->aspectRatio();
             $constraint->upsize();} );
             $main_img = Image::make($imgUrl)->fit(540, 540, function ($constraint) {
             $constraint->aspectRatio();
             $constraint->upsize();} );
             
             }*/
            
            $thumb_img->save($Path.'/'.$data,80);
            if (file_exists($imgUrl)) {
                unlink($imgUrl);
            }
            $main_img->save($destinationPath.'/'.$data,80);
        }//image save
        return json_encode($data);
        //return $data;
        // return Redirect::back()->with(array('Image'=>$data));
    }
    public function uploadEventVideo(Request $request){
        $image = Input::file('file');
        $sourcePath = public_path('doc/events/videos_temp');
        
        $destinationPath = public_path('doc/events/videos');
        $user_img = '';
        
        $img = str_replace(" ", '-', $image->getClientOriginalName());
        $img = explode('.', $img);
        
        //$videoName = ImageHelper::videoSave($image,$destinationPath,$user_img); //image save
        $videoName = ImageHelper::videoSave($image,$sourcePath,$user_img); //image save
        $videoData = explode('.', $videoName);
        
        
        if($videoData[1] != 'mov' && $videoData[1] != 'mp4'){
            exec('ffmpeg -i '.$sourcePath.'/'.$videoName.' -strict -2 '.$destinationPath.'/'.$videoData[0].'.mp4');
            
            $videosName = $videoData[0].'.mp4';
        }else{
            $mainVideo = $sourcePath.'/'.$videoName;

            File::copy($mainVideo, $destinationPath.'/'.$videoName);
            $videosName = $videoName;
        }
        
        $imageData = explode('.', $videoName);
        $newVideoName = $videoName;
        $thumbhnailImage = $imageData[0].'.jpeg';
        $second             = 5;
        $thumbSize       = '512x512';
        $videpath = $sourcePath.'/'.$newVideoName;
        $thumbhnailImagePath = $destinationPath.'/'.$thumbhnailImage;
        $cmd = "ffmpeg -i {$videpath} -deinterlace -an -ss {$second} -t 00:00:01  -s {$thumbSize} -r 1 -y -vcodec mjpeg -f mjpeg {$thumbhnailImagePath} 2>&1";
        exec($cmd);
        
        return json_encode($videosName);
        
        //return json_encode($videoData[0].'.mp4');
    }

    /**
     * Event media delete.
     *
     * @param Request $request
     * @return json
     */
    public function eventMediaDelete(Request $request)
    {
        set_time_limit(0);
        $eventMedia = EventMedia::find($request->id);
        if(!empty($eventMedia))
        {
            if($eventMedia->type == 'image')
            {
                $eventImageCount = EventMedia::where([
                    'type' => 'image',
                    'event_id' => $eventMedia->event_id,
                ])->count();
                
                if($eventImageCount == 1)
                {
                    return response()->json([
                        'status' => 0,
                        'message' => trans('messages.event.media.image_atleast_one'),
                    ], 400);
                }
            }
            
            if ($eventMedia->delete()) {
                
                return response()->json([
                    'status' => 1,
                ]);
            }
        }
        
        return response()->json([
            'status' => 0,
            'message' => trans('messages.error'),
           ], 400);
        
    }

}