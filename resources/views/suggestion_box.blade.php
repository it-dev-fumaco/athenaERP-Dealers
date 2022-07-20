<div id="item-list" class="container-fluid p-0 ul_list" style="border: 1px solid #ccc;">
  @forelse ($q as $item)
    @php
      $img = isset($image[$item->name]) ? "/img/" . $image[$item->name][0]->image_path : "/icon/no_img.png";
      $img_webp = isset($image[$item->name]) ? "/img/" . explode('.',$image[$item->name][0]->image_path)[0].'.webp' : "/icon/no_img.webp";
    @endphp
    <div class="search-row row w-100 p-2" style="border-bottom: 1px solid #ccc;">
      <div class="text-center pl-2" style="width: 60px !important;">
        @if(!Storage::disk('public')->exists('/img/'.explode('.', $item->item_image_path)[0].'.webp'))
          <img src="{{ asset('storage/').$img }}" class="img w-100">
        @elseif(!Storage::disk('public')->exists('/img/'.$item->item_image_path))
          <img src="{{ asset('storage/').$img_webp }}" class="img w-100">
        @else
          <picture>
            <source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" style=" height: 60px;" class="w-100">
            <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" style="height: 60px;" class="w-100">
            <img src="{{ asset('storage'.$img) }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}" style="height: 60px;" class="w-100">
          </picture>
        @endif
      </div>
      <div class="col-8 col-md-10 col-lg-9 col-xl-10 text-truncate">
        <span style="font-size: 10pt;"><b>{{ $item->name }}</b></span>
        <br><span style="font-size: 10pt;">{{ $item->description }}</span>
      </div>
      <div class="col">
        <a class="btn btn-default" href="/get_item_details/{{ $item->name }}">
          <i class="fa fa-arrow-right"></i>
        </a>
      </div>
    </div>
  @empty
    <div class="row w-100 p-2 text-center">
      <p class="mx-auto">No results found.</p>
    </div>
  @endforelse
</div>
    <style type="text/css">
      .search-row:hover{
        background-color: #DCDCDC;
        color: #373D3F;
      }
      .truncate:hover{
        background-color: #DCDCDC;
        color: #373D3F;
      }
      
      .no-hover:hover{
        background-color: #fff;
      }

      .ul_list{
        padding: 0;
        margin: 0;
        font-size: 12pt;
        list-style-type: none;
        text-align: left;
      }
      </style>