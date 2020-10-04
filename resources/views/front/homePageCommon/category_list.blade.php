<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        {{$item->category_name}}
        <i class="fa fa-chevron-down" aria-hidden="true"></i>
    </a>
    <ul class="dropdown-menu">
        <li>
            <a href="{{url(route('showCategory',['categorySlug'=>$item->category_slug]))}}">
                All {{ $item->category_name }}
            </a>
        </li>
        @if(count($item->subCategories) > 0)
            @foreach($item->subCategories as $list)
                <li>
                    <a href="{{url(route('showCategory',['categorySlug'=>$list->category_slug]))}}">
                        {{$list->category_name}}
                    </a>
                </li>
            @endforeach
        @endif

    </ul>
</li>