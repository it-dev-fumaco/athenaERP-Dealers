<table class="table" style='font-size: 10pt;'>
    <thead>
      <th class="text-center p-1 align-middle">Store</th>
    </thead>
    <tbody>
      @forelse ($list as $ste)
      <tr>
        <td class="p-2">
          <a href="#" data-toggle="modal" data-target="#{{ $ste['name'] }}-Modal">{{ $ste['to_consignment'] }}</a>
          <small class="d-block"><b>{{ $ste['name'] }}</b> | <b>Delivery Date:</b> {{ Carbon\Carbon::parse($ste['delivery_date'])->format('M d, Y').' - '.Carbon\Carbon::parse($ste['posting_time'])->format('h:i A') }}</small>
          <span class="badge badge-{{ $ste['status'] == 'Pending' ? 'warning' : 'success' }}">{{ $ste['status'] }}</span>
          @if ($ste['status'] == 'Delivered')
            <span class="badge badge-{{ $ste['delivery_status'] == 0 ? 'warning' : 'success' }}">{{ $ste['delivery_status'] == 0 ? 'To Receive' : 'Received' }}</span>
          @endif

      
          <div class="modal fade" id="{{ $ste['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form action="/promodiser/receive/{{ $ste['name'] }}" method="get">
                <div class="modal-header bg-navy">
                  <h6 class="modal-title">Incoming Item(s)</h6>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form></form>
                  <h5 class="text-center font-responsive font-weight-bold m-0">{{ $ste['to_consignment'] }}</h5>
                  <small class="d-block text-center mb-2">{{ $ste['name'] }} | Delivery Date: {{ Carbon\Carbon::parse($ste['delivery_date'])->format('M. d, Y').' - '.Carbon\Carbon::parse($ste['posting_time'])->format('h:i A') }}</small>
                  <div class="callout callout-info text-center">
                    <small><i class="fas fa-info-circle"></i> Once items are received, stocks will be automatically added to your current inventory.</small>
                  </div>
                  <table class="table" style="font-size: 9pt;">
                    <thead>
                      <th class="text-center p-1 align-middle" style="width: 40%">Item Code</th>
                      <th class="text-center p-1 align-middle" style="width: 30%">Delivered Qty</th>
                      <th class="text-center p-1 align-middle" style="width: 30%">Rate</th>
                    </thead>
                    <tbody>
                      @foreach ($ste['items'] as $item)
                      @php
                        $id = $ste['name'].'-'.$item['item_code'];
                        $img = $item['image'] ? "/img/" . $item['image'] : "/icon/no_img.png";
                        $img_webp = $item['image'] ? "/img/" . explode('.', $item['image'])[0].'.webp' : "/icon/no_img.webp";
                      @endphp
                      <tr>
                        <td class="text-left p-1 align-middle" style="border-bottom: 0 !important;">
                          <div class="d-flex flex-row justify-content-start align-items-center">
                            <div class="p-1 text-left">
                              <a href="{{ asset('storage/') }}{{ $img }}" data-toggle="mobile-lightbox" data-gallery="{{ $item['item_code'] }}" data-title="{{ $item['item_code'] }}">
                                <picture>
                                  <source srcset="{{ asset('storage'.$img_webp) }}" type="image/webp" alt="{{ str_slug(explode('.', $img)[0], '-') }}" width="40" height="40">
                                  <source srcset="{{ asset('storage'.$img) }}" type="image/jpeg" alt="{{ str_slug(explode('.', $img)[0], '-') }}" width="40" height="40">
                                  <img src="{{ asset('storage'.$img) }}" alt="{{ str_slug(explode('.', $img)[0], '-') }}" width="40" height="40">
                                </picture>
                              </a>
                            </div>
                            <div class="p-1 m-0">
                              <span class="font-weight-bold">{{ $item['item_code'] }}</span>
                            </div>
                          </div>

                          <div class="modal fade" id="mobile-{{ $item['item_code'] }}-images-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                <form action="/promodiser/receive/{{ $ste['name'] }}" method="get">
                                <div class="modal-header">
                                  <h5 class="modal-title">{{ $item['item_code'] }}</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div id="image-container" class="container-fluid">
                                            <div id="carouselExampleControls" class="carousel slide" data-interval="false">
                                                <div class="carousel-inner">
                                                    <div class="carousel-item active">
                                                        <picture>
                                                            <source id="mobile-{{ $item['item_code'] }}-webp-image-src" srcset="{{ asset('storage/').$img_webp }}" type="image/webp" class="d-block w-100" style="width: 100% !important;">
                                                            <source id="mobile-{{ $item['item_code'] }}-orig-image-src" srcset="{{ asset('storage/').$img }}" type="image/jpeg" class="d-block w-100" style="width: 100% !important;">
                                                            <img class="d-block w-100" id="mobile-{{ $item['item_code'] }}-image" src="{{ asset('storage/').$img }}" alt="{{ Illuminate\Support\Str::slug(explode('.', $img)[0], '-') }}">
                                                        </picture>
                                                    </div>
                                                    <span class='d-none' id="mobile-{{ $item['item_code'] }}-image-data">0</span>
                                                </div>
                                                @if ($item['img_count'] > 1)
                                                <a class="carousel-control-prev" href="#carouselExampleControls" onclick="prevImg('{{ $item['item_code'] }}')" role="button" data-slide="prev" style="color: #000 !important">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#carouselExampleControls" onclick="nextImg('{{ $item['item_code'] }}')" role="button" data-slide="next" style="color: #000 !important">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </td>
                        <td class="text-center p-1 align-middle">
                          <span class="d-block font-weight-bold">{{ number_format($item['delivered_qty'] * 1) }}</span>
                          <span class="d-none font-weight-bold" id="{{ $item['item_code'] }}-qty">{{ $item['delivered_qty'] * 1 }}</span>
                          <small>{{ $item['stock_uom'] }}</small>
                        </td>
                        <td class="text-center p-1 align-middle">
                            <input type="text" name="item_codes[]" class="d-none" value="{{ $item['item_code'] }}"/>
                            <input type="text" value='{{ $item['price'] > 0 ? number_format($item['price'], 2) : null }}' class='form-control text-center price' name='price[{{ $item['item_code'] }}]' data-item-code='{{ $item['item_code'] }}' placeholder='0' required>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3" class="text-justify pt-0 pb-1 pl-1 pr-1" style="border-top: 0 !important;">
                          <span class="item-description">{!! strip_tags($item['description']) !!}</span> <br>
                          Amount: ₱ <span id="{{ $item['item_code'] }}-amount" class='font-weight-bold amount'>{{ number_format($item['delivered_qty'] * $item['price'], 2) }}</span>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  @if ($ste['status'] == 'Delivered' && $ste['delivery_status'] == 0)
                    <button type="submit" class="btn btn-primary w-100">Receive</button>
                  @else
                    <button type="submit" class="btn btn-info w-100">Update Prices</button>
                    <button type="button" class="btn btn-secondary w-100" data-toggle="modal" data-target="#cancel-{{ $ste['name'] }}-Modal">
                      Cancel
                    </button>
                      
                    <div class="modal fade" id="cancel-{{ $ste['name'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-navy">
                                    <h5 class="modal-title" id="exampleModalLabel">Cancel</h5>
                                    <button type="button" class="close" onclick="close_modal('#cancel-{{ $ste['name'] }}-Modal')">
                                    <span aria-hidden="true" style="color: #fff;">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Cancel {{ $ste['name'] }}?
                                </div>
                                <div class="modal-footer">
                                    <a href="/promodiser/cancel/received/{{ $ste['name'] }}" class="btn btn-primary w-100 submit-once">Confirm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                  @endif
                </div>
                </form>
              </div>
            </div>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td class="text-center text-uppercase text-muted align-middle">No incoming deliveries</td>
      </tr>
      @endforelse
    </tbody>
  </table>