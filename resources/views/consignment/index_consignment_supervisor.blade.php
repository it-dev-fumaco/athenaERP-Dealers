@extends('layout', [
    'namePage' => 'Dashboard',
    'activePage' => 'dashboard',
])

@section('content')
<div class="content">
	<div class="content-header p-0">
        @if (Auth::user()->user_group == 'Director')
        <ul class="nav nav-pills mb-2 mt-2">
            <li class="nav-item p-0">
                <a class="nav-link font-responsive text-center" href="/">In House Warehouse Transaction</a>
            </li>
            <li class="nav-item p-0">
                <a class="nav-link active font-responsive text-center" href="/consignment_dashboard">Consignment Dashboard</a>
            </li>
        </ul>
        @endif
        <div class="container-fluid">
            <div class="row p-0 mr-0 ml-0 mb-0 mt-2">
                <div class="col-12 m-0 p-0">
                    <div class="row p-0 m-0">
                        <div class="col-6 col-md-3 p-1">
                            <a href="/view_sales_report">
                                <div class="info-box bg-gradient-primary m-0">
                                    <div class="info-box-content p-1">
                                        <span class="info-box-text font-responsive m-0">Sales Report</span>
                                        <span class="info-box-number font-responsive m-0">{{ number_format($total_item_sold) }}</span>
                                        <span class="progress-description font-responsive" style="font-size: 7pt;">{{ $duration }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3 p-1">
                            <a href="/inventory_audit">
                                <div class="info-box bg-gradient-info m-0">
                                    <div class="info-box-content p-1">
                                        <span class="info-box-text font-responsive m-0">Inventory Audit</span>
                                        <span class="info-box-number font-responsive m-0">{{ number_format($total_pending_inventory_audit) }}</span>
                                        <span class="progress-description font-responsive" style="font-size: 7pt;">{{ $duration }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3 p-1">
                            <a href="/stocks_report/list">
                                <div class="info-box bg-gradient-warning m-0">
                                    <div class="info-box-content p-1">
                                        <span class="info-box-text font-responsive">Stock Transfers</span>
                                        <span class="info-box-number font-responsive">{{ number_format($total_stock_transfers) }}</span>
                                        <div class="progress">
                                            <div class="progress-bar"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3 p-1">
                            <a href="/beginning_inv_list" style="color: inherit">
                                <div class="info-box bg-gradient-secondary m-0">
                                    <div class="info-box-content p-1">
                                        <span class="info-box-text font-responsive">Stock Adjustments</span>
                                        <span class="info-box-number font-responsive">{{ number_format($total_stock_adjustments) }}</span>
                                        <div class="progress">
                                            <div class="progress-bar"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="card card-secondary card-outline">
                                <div class="card-header p-1">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex flex-row align-items-center">
                                                <div class="p-0 col-5">
                                                    <ul class="nav nav-pills custom-navpill">
                                                        <li class="nav-item col-6 p-0">
                                                            <a class="nav-link active font-responsive text-center rounded-0" style="height: 60px; padding-top: 15px;" data-toggle="pill" href="#pending-content" role="tab" href="#">Sales Report</a>
                                                        </li>
                                                        <li class="nav-item col-6 p-0">
                                                            <a class="nav-link font-responsive text-center rounded-0" style="height: 60px; padding-top: 15px;" data-toggle="pill" href="#audit-report-content" role="tab" href="#">Inventory Audit Report</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="p-0 col-2">
                                                    <div class="text-center">
                                                        <p class="text-center m-0 font-responsive">
                                                            <span class="d-inline-block font-weight-bolder" style="font-size: 1.2rem;">{{ count($active_consignment_branches) }}</span>
                                                            <span class="d-inline-block text-muted" style="font-size: .8rem;">/ {{ count($consignment_branches) }}</span>
                                                        </p>
                                                        <span class="d-block" style="font-size: 9pt;">Active Store</span>
                                                    </div>
                                                </div>
                                                <div class="p-0 col-2">
                                                    <a href="/view_promodisers" style="color: inherit;">
                                                        <div class="text-center">
                                                            <p class="text-center font-weight-bolder m-0 font-responsive" style="font-size: 1.2rem;">{{ ($promodisers) }}</p>
                                                            <span class="d-block" style="font-size: 9pt;">Promodiser(s)</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="p-0 col-3 m-0">
                                                    <a href="/beginning_inv_list" style="color: inherit;">
                                                        <div class="d-flex flex-row align-items-center m-0 p-0">
                                                            <div class="p-0 m-0">
                                                                <div class="skills_section text-right m-0 p-0">
                                                                    <div class="skills-area m-0 p-0">
                                                                        <div class="single-skill w-100 mb-1">
                                                                            <div class="circlechart" data-percentage="{{ $beginning_inv_percentage }}">
                                                                                <svg class="circle-chart" viewBox="0 0 33.83098862 33.83098862"><circle class="circle-chart__background" cx="16.9" cy="16.9" r="15.9"></circle><circle class="circle-chart__circle success-stroke" stroke-dasharray="92,100" cx="16.9" cy="16.9" r="15.9"></circle></svg>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="p-0 m-0">
                                                                <div class="text-center">
                                                                    <span class="d-block text-muted" style="font-size: 1.2rem;">{{ $consignment_branches_with_beginning_inventory }} / {{ count($consignment_branches) }}</span>
                                                                    <span class="d-block" style="font-size: 8pt;">Beginning Inventory Completion</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
        
                                            <div class="tab-content custom-tabcontent">
                                                <div class="tab-pane fade show active" id="pending-content" role="tabpanel" aria-labelledby="pending-tab">
                                                    <div class="d-flex flex-row text-center align-items-center m-0">
                                                        <div class="p-2">
                                                            <select class="form-control w-100" id="year-filter">
                                                                <option value="" disabled>Select Year</option>
                                                                @foreach ($sales_report_included_years as $year)
                                                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="form-check text-center text-white">
                                                                <input class="form-check-input" type="checkbox" id="hide-zero-check" checked>
                                                                <label class="form-check-label" for="hide-zero-check"> Hide zero values
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="beginning-inventory-list-el" class="pl-2 pr-2 pb-2"></div>
                                                </div>
                                                <div class="tab-pane fade" id="audit-report-content" role="tabpanel" aria-labelledby="audit-report-tab">
                                                    <form method="GET" id="search-audit-form">
                                                        <div class="row p-2">
                                                            <div class="col-3">
                                                                <select name="store" class="form-control" id="consignment-audit-select" required>
                                                                    <option value="">Select Store</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-3">
                                                                <select name="cutoff" class="form-control" required>
                                                                    <option value="">Select Cutoff Date</option>
                                                                    @foreach ($cutoff_filters as $cf)
                                                                    <option value="{{ $cf['id'] }}">{{ $cf['cutoff_start'] . ' - ' . $cf['cutoff_end'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-3">
                                                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="row m-0 pr-2 pl-2 pb-2">
                                                        <div class="col-md-6 bg-white m-0 p-0" id="deliveries-content">
                
                                                        </div>
                                                        <div class="col-md-6 bg-white m-0 p-0" id="returns-content">
                                                           
                                                        </div>
                                                        <div class="col-md-12 bg-white m-0 p-0">
                                                            <div id="sales-content">
                                                                <h5 class="text-center text-uppercase mt-2 p-2 border-bottom font-weight-bolder">Sales</h5>
                                                                <h6 class="text-center text-uppercase text-muted">Please select Store and Cutoff Period</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-navpill .nav-item .active{
        background-color:rgba(58, 112, 170, 0.905);
    }
    .custom-tabcontent .tab-pane.active{
        background-color:rgba(58, 112, 170, 0.905);
    }
    .circle-chart {
        width: 80px;
        height: 50px;
    }
    .circle-chart__circle {
        stroke: #00acc1;
        stroke-width: 2;
        stroke-linecap: square;
        fill: none;
        animation: circle-chart-fill 2s reverse; /* 1 */ 
        transform: rotate(-90deg); /* 2, 3 */
        transform-origin: center; /* 4 */
    }
    .circle-chart__circle--negative {
        transform: rotate(-90deg) scale(1,-1); /* 1, 2, 3 */
    }
    .circle-chart__background {
        stroke: #efefef;
        stroke-width: 2;
        fill: none; 
    }
    .circle-chart__info {
        animation: circle-chart-appear 2s forwards;
        opacity: 0;
        transform: translateY(0.3em);
    } 
    .circle-chart__percent {
        alignment-baseline: central;
        text-anchor: middle;
        font-size: 7px;
    }
    .circle-chart__subline {
        alignment-baseline: central;
        text-anchor: middle;
        font-size: 3px;
    }
    .success-stroke {
        stroke: #00C851;
    }
    .warning-stroke {
        stroke: #ffbb33;
    }
    .danger-stroke {
        stroke: #ff4444;
    }
    @keyframes circle-chart-fill {
        to { stroke-dasharray: 0 100; }
    }
    @keyframes circle-chart-appear {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .skills_section{
        width: 100%;
        margin: 0 auto;
        margin-bottom: 80px;
    }
    .skills-area {
        margin-top: 5%;
        display: flex;
        flex-wrap: wrap;
    }
    .single-skill {
        width: 25%;
        margin-bottom: 80px;
    }
    .success-stroke {
        stroke: rgb(129, 86, 252);
    }
    .circle-chart__background {
        stroke: #ede4e4;
        stroke-width: 2;
    }
    /* Extra small devices (portrait phones, less than 576px) */
    @media (max-width: 575.98px) {
        .skill-icon {
            width: 50%;
        }
        .skill-icon i {
            font-size: 70px;
        }
        .single-skill {
            width: 50%;
        }
    }
</style>
@endsection

@section('script')
<script>
    function makesvg(percentage, inner_text=""){
        var abs_percentage = Math.abs(percentage).toString();
        var percentage_str = percentage.toString();
        var classes = "";
        if(percentage < 0){
            classes = "danger-stroke circle-chart__circle--negative";
        } else if(percentage > 0 && percentage <= 30){
            classes = "warning-stroke";
        } else{
            classes = "success-stroke";
        }

        var svg = '<svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" xmlns="http://www.w3.org/2000/svg">'
            + '<circle class="circle-chart__background" cx="16.9" cy="16.9" r="15.9" />'
            + '<circle class="circle-chart__circle '+classes+'"'
            + 'stroke-dasharray="'+ abs_percentage+',100"    cx="16.9" cy="16.9" r="15.9" />'
            + '<g class="circle-chart__info">'
            + '   <text class="circle-chart__percent" x="17.9" y="19.5">'+percentage_str+'%</text>';

        if(inner_text){
            svg += '<text class="circle-chart__subline" x="16.91549431" y="22">'+inner_text+'</text>'
        }

        svg += ' </g></svg>';

        return svg
    }

    (function( $ ) {
        $.fn.circlechart = function() {
            this.each(function() {
                var percentage = $(this).data("percentage");
                var inner_text = $(this).text();
                $(this).html(makesvg(percentage, inner_text));
            });
            return this;
        };
    }( jQuery ));

    $(function () {
        $('.circlechart').circlechart();

        $(document).on('change', '#hide-zero-check', function() {
            loadSalesReport();
        });

        $('#year-filter').change(function(){
            loadSalesReport();
        });

        loadSalesReport();
        function loadSalesReport() {
            var hidezero = $('#hide-zero-check').is(":checked");
            var year = $('#year-filter').val();

            $.ajax({
                type: "GET",
                url: "/sales_report",
                data: {hidezero, year},
                success: function (data) {
                    $('#beginning-inventory-list-el').html(data);
                }
            });
        }

        $('#consignment-audit-select').select2({
            placeholder: "Select Store",
            ajax: {
                url: '/consignment_stores',
                method: 'GET',
                dataType: 'json',
                data: function (data) {
                    return {
                        q: data.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $(document).on('submit', '#search-audit-form', function(e) {
            e.preventDefault();

            loadData();
        });

        function loadData() {
            loadDeliveries();
            loadReturns();
            loadSales();
        }

        function loadDeliveries() {
			$.ajax({
				type: "GET",
				url: "/get_audit_deliveries",
				data: $('#search-audit-form').serialize(),
				success: function (response) {
					$('#deliveries-content').html(response);
				}
			});
		}

        function loadReturns() {
			$.ajax({
				type: "GET",
				url: "/get_audit_returns",
				data: $('#search-audit-form').serialize(),
				success: function (response) {
					$('#returns-content').html(response);
				}
			});
		}

        function loadSales() {
			$.ajax({
				type: "GET",
				url: "/get_audit_sales",
				data: $('#search-audit-form').serialize(),
				success: function (response) {
					$('#sales-content').html(response);
				}
			});
		}
    });
</script>
@endsection