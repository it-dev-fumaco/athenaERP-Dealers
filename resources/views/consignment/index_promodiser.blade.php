@extends('layout', [
    'namePage' => 'Dashboard',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
    <div class="container p-0">
      <div class="row p-0 m-0">
        @if ($branches_with_pending_beginning_inventory)
          <div class="modal fade" id="pendingBeginningInventoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header pt-2 pb-2 bg-navy">
                  <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-info-circle"></i> Reminder</h5>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body p-2" style="font-size: 10pt;">
                  <span>Please enter your beginning inventory for:</span>
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th class="text-center p-2" style="width: 70%;">Branch / Store</th>
                        <th class="text-center p-2">-</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach (array_keys($branches_with_pending_beginning_inventory) as $branch)
                        <tr>
                          <td class="p-2 align-middle">{{ $branch }}</td>
                          <td class="text-center p-2 align-middle"><a href="/beginning_inventory?branch={{ $branch }}" class="btn btn-primary btn-xs" style="font-size: 9pt"><i class="fa fa-plus"></i> Create</a></td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <script>
            $(document).ready(function(){
              $('#pendingBeginningInventoryModal').modal('show');
            });
          </script>
        @endif
        <div class="col-6 p-1">
          @if (count($assigned_consignment_store) > 1)
          <a href="#" data-toggle="modal" data-target="#select-branch-modal">
          @else
          <a href="/view_calendar_menu/{{ $assigned_consignment_store[0] }}">
          @endif
            <div class="info-box bg-gradient-primary m-0">
              <div class="info-box-content p-1">
                <span class="info-box-text" style="font-size: 9pt;">Product Sold</span>
                <span class="info-box-number">{{ number_format($total_item_sold) }}</span>
                <span class="progress-description" style="font-size: 7pt;">{{ $duration }}</span>
              </div>
            </div>
          </a>
        </div>
        <div class="col-6 p-1">
          <a href="/inventory_audit">
            <div class="info-box bg-gradient-info m-0">
              <div class="info-box-content p-1">
                <span class="info-box-text" style="font-size: 9pt;">Inventory Audit</span>
                <span class="info-box-number">{{ number_format($total_pending_inventory_audit) }}</span>
                <span class="progress-description" style="font-size: 7pt;">{{ $duration }}</span>
              </div>
            </div>
          </a>
        </div>
        <div class="col-6 p-1">
          <a href="/stock_transfer/list/Material Transfer">
            <div class="info-box bg-gradient-warning m-0">
              <div class="info-box-content p-1">
                <span class="info-box-text" style="font-size: 9pt;">Stock Transfer</span>
                <span class="info-box-number">{{ number_format($total_stock_transfer) }}</span>
                <div class="progress">
                  <div class="progress-bar"></div>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-6 p-1">
          <a href="/beginning_inv_list">
            <div class="info-box bg-gradient-secondary m-0">
              <div class="info-box-content p-1">
                <span class="info-box-text" style="font-size: 9pt;">Stock Adjustment</span>
                <span class="info-box-number">{{ number_format($total_stock_adjustments) }}</span>
                <div class="progress">
                  <div class="progress-bar"></div>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      <div class="row p-0 m-0">
        <div class="col-md-12 p-1">
          <div class="card card-secondary card-outline mt-2 mb-2">
            <div class="card-header text-center font-weight-bold p-1 font-responsive">Inventory Summary</div>
            <div class="card-body p-0">
              <table class="table table-bordered" style="font-size: 8pt;">
                <thead class="text-uppercase">
                  <th class="text-center p-1 align-middle" style="width: 64%;">Store</th>
                  <th class="text-center p-1 align-middle" style="width: 18%;">Items on Hand</th>
                  <th class="text-center p-1 align-middle" style="width: 18%;">Total Qty</th>
                </thead>
                <tbody>
                  @forelse ($assigned_consignment_store as $branch)
                  @php
                    $items_on_hand = array_key_exists($branch, $inventory_summary) ? $inventory_summary[$branch]['items_on_hand'] : 0;
                    $total_qty = array_key_exists($branch, $inventory_summary) ? $inventory_summary[$branch]['total_qty'] : 0;
                  @endphp
                  <tr>
                    <td class="text-justify pt-2 pb-2 pr-1 pl-1 align-middle">
                      <a href="/inventory_items/{{ $branch }}">{{ $branch }}</a>
                    </td>
                    <td class="text-center pt-2 pb-2 pr-1 pl-1 align-middle font-weight-bold">{{ number_format($items_on_hand) }}</td>
                    <td class="text-center pt-2 pb-2 pr-1 pl-1 align-middle font-weight-bold">{{ number_format($total_qty) }}</td>
                  </tr> 
                  @empty
                  <tr>
                    <td class="text-center font-weight-bold p-2 text-uppercase" colspan="3">No assigned consignment branch</td>
                  </tr> 
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


      <div class="row p-0 m-0">
        <div class="col-md-12 p-1">
          <div class="card card-warning card-outline mt-2 mb-2">
            <div class="card-header text-center font-weight-bold p-1 font-responsive">To Receive Item(s)</div>
            <div class="card-body p-0">
              <table class="table" style='font-size: 10pt;'>
                <thead>
                  <th class="text-center p-1 align-middle">Store</th>
                </thead>
                <tbody>
                  @forelse ($ste_arr as $ste)
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
            </div>
          </div>
        </div>
      </div>
      <div class="row p-0 m-0">
        <div class="col-md-12 p-1 mb-5">
          <div class="card m-0 p-1">
            <div class="card-header text-center font-weight-bold p-1">
              <span class="d-block">Sales Report</span>
              <div class="form-group pl-2 pr-2 m-1">
                <select id="sr-branch-warehouse-select" class="form-control form-control-sm {{ count($assigned_consignment_store) > 1 ? '' : 'd-none' }}">
                  @foreach ($assigned_consignment_store as $branch)
                  <option value="{{ $branch }}">{{ $branch }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="card-body p-0 mt-2">
              <div class="position-relative mb-4">
                <canvas id="sales-chart" height="200"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="select-branch-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header pt-2 pb-2 bg-navy">
        <h5 class="modal-title">Select Store</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="color: #fff">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <table class="table" style="font-size: 10pt;">
          <tbody>
            @forelse ($assigned_consignment_store as $branch)
            <tr>
              <td class="text-justify p-2 align-middle">
                <a href="/view_calendar_menu/{{ $branch }}">{{ $branch }}</a>
              </td>
              <td class="text-center p-2 align-middle">
                <a href="/view_calendar_menu/{{ $branch }}" class="btn btn-primary btn-xs"><i class="fas fa-search"></i></a>
              </td>
            </tr> 
            @empty
            <tr>
              <td class="text-center font-weight-bold" colspan="2">No assigned consignment branch</td>
            </tr> 
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<style>
  .morectnt span {
      display: none;
  }
</style>
@endsection

@section('script')
<script>
  $(function () {
    $('.price').keyup(function(){
        var item_code = $(this).data('item-code');
        var price = $(this).val().replace(/,/g, '');
        if($.isNumeric($(this).val()) && price > 0 || $(this).val().indexOf(',') > -1 && price > 0){
            var qty = parseInt($('#'+item_code+'-qty').text());
            var total_amount = price * qty;

            const amount = total_amount.toLocaleString('en-US', {maximumFractionDigits: 2});
            $('#'+item_code+'-amount').text(amount);
        }else{
            $('#'+item_code+'-amount').text('0');
            $(this).val('');
        }
    });

    var showTotalChar = 150, showChar = "Show more", hideChar = "Show less";
            $('.item-description').each(function() {
                var content = $(this).text();
                if (content.length > showTotalChar) {
                    var con = content.substr(0, showTotalChar);
                    var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                    var txt = con + '<span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="#" class="show-more">' + showChar + '</a></span>';
                    $(this).html(txt);
                }
            });

            $(".show-more").click(function(e) {
                e.preventDefault();
                if ($(this).hasClass("sample")) {
                    $(this).removeClass("sample");
                    $(this).text(showChar);
                } else {
                    $(this).addClass("sample");
                    $(this).text(hideChar);
                }

                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });


    'use strict'
    var ticksStyle = {
      fontColor: '#495057',
      fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true

    $(document).on('change', '#sr-branch-warehouse-select', function(){
      loadChart();
    });

    loadChart();
    function loadChart() {
      $.ajax({
        type: "GET",
        url: "/consignment_sales/" + $('#sr-branch-warehouse-select').val(),
        success: function (data) {
          new Chart($('#sales-chart'), {
            type: 'bar',
            data: {
              labels: data.labels,
              datasets: [{
                backgroundColor: '#0774C0',
                borderColor: '#0774C0',
                data: data.data
              }]
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                mode: mode,
                intersect: intersect
              },
              hover: {
                mode: mode,
                intersect: intersect
              },
              legend: {
                display: false
              },
              scales: {
                yAxes: [{
                  ticks: $.extend({
                    beginAtZero: true,
                    // Include a dollar sign in the ticks
                    callback: function (value) {
                      if (value >= 1000) {
                        value /= 1000
                        value += 'k'
                      }

                      return '₱' + value;
                    }
                  }, ticksStyle)
                }],
                xAxes: [{
                  display: true,
                  ticks: ticksStyle
                }]
              },
              tooltips: {
                callbacks: {
                  label: function(tooltipItem) {
                    return "₱ " + tooltipItem.yLabel.toLocaleString();
                  }
                }
              }
            }
          });
        }
      });
    }
  });
</script>
@endsection