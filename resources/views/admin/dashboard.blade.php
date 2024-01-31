@extends('layouts.admin')
@section('admin-content')

<div class="srcBtnWrap">
    <div class="white-box-sadow">
        <div class="row">
            <div class="col-12">
                <div class="wrap-heading">Today</div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
            <div class="small-box bg-info-g">
                <div class="inner">
                <h3>{{number_format($data['totalSalesToday'])}}</h3>

                <p>Total sales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">Go to <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
            <div class="small-box bg-success-g">
                <div class="inner">
                <h3>{{$data['totalBilltoday']}}</h3>

                <p>Total Bill</p>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">Go to <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
            <div class="small-box bg-warning-g">
                <div class="inner">
                <h3>{{number_format($data['totalProfitToday'])}}</h3>

                <p>Total Profit</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">Go to <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
            <div class="small-box bg-danger-g">
                <div class="inner">
                <h3>{{$data['totalBilltoday']}}</h3>
                <p>Total Customer</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">Go to <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
            </div>
        </div>
    </div>
    <div class="white-box-sadow">
        <div class="row">
            <div class="col-12">
                <div class="wrap-heading">This Month</div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <div class="small-box bg-blue-g">
                <div class="inner">
                    <h3>{{number_format($data['totalSalesthismonth'])}}</h3>
                    <p>Total Sale</p>
                </div>
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">Go to <i class="fas fa-arrow-circle-right"></i></a> --}}
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
            <div class="small-box bg-yellow-g">
                <div class="inner">
                <h3>{{$data['totalBillthismonth']}}</h3>
                <p>Total Bill</p>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">Go to <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
            <div class="small-box bg-syblue-g">
                <div class="inner">
                <h3>{{number_format($data['totalProfitthismonth'])}}</h3>
                <p>Total Profit</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">Go to <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
            <div class="small-box bg-yellowgreen-g">
                <div class="inner">
                <h3>{{$data['totalBillthismonth']}}</h3>
                <p>Total Customer</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">Go to <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
            </div>
        </div>
    </div>
    <div class="white-box-sadow">
        <div class="row">
            <div class="col-12">
                <div class="wrap-heading">Overall</div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="box-card-new">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Latest purchase history</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($data['latest_purchase_history'] as $itempurchase_history)
                                        <tr>
                                            <td>{{$itempurchase_history->purchase_date}}</td>
                                            <td>{{$itempurchase_history->invoice_no}}</td>
                                            <td>{{number_format($itempurchase_history->gross_amount)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-view-all">
                        <a href="{{ route('admin.purchase.inward_list') }}" class="table-view-all-btn commonBtn-btnTag">view all</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="box-card-new">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sale per employee</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['emp_query'] as $emp_queryitem)
                                        <tr>
                                            <td>{{$emp_queryitem->name}}</td>
                                            <td>{{number_format($emp_queryitem->SellInwardStock->sum('sub_total'))}}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-view-all">
                        {{-- <a href="#" class="table-view-all-btn commonBtn-btnTag">view all</a> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="box-card-new">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Low stock Alert</h3>
                        {{-- <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                            </a>
                        </div> --}}
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-valign-middle">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col">Product Name</th> --}}
                                        <th scope="col">Brand</th>
                                        <th scope="col">Barcode</th>
                                        <th scope="col">In Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['low_stock'] as $low_stockitem)
                                        @if($low_stockitem->t_qty <= @$low_stockitem->product->alert_product_qty)
                                            <tr>
                                                {{-- <td>{{$low_stockitem->product->product_name}}</td> --}}
                                                <td>{{$low_stockitem->product->brand}}</td>
                                                <td>{{$low_stockitem->product->product_barcode}}</td>
                                                <td>{{$low_stockitem->t_qty}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-view-all">
                        <a href="{{ route('admin.report.low_stock_product') }}" class="table-view-all-btn commonBtn-btnTag">view all</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="box-card-new">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Near Expiry Products</h3>
                        {{-- <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                            </a>
                        </div> --}}
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-valign-middle">
                                <thead>
                                    <th scope="col">Brand</th>
                                    <th scope="col">Product Barcode</th>
                                    <th scope="col">Expiry Date</th>
                                </thead>
                                <tbody>
                                    @forelse ($data['nearExpiryStock'] as $key=>$purchase)
                                        @if($purchase->t_qty <= $purchase->product->alert_product_qty)
                                            <tr>
                                                <td>{{$purchase->product->brand}}</td>
                                                <td>{{$purchase->product->product_barcode}}</td>
                                                <td>{{date('Y-m', strtotime(str_replace('.', '/', $purchase->product_expiry_date)))}}</td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr><td colspan="3"> No data found </td></tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-view-all">
                        <a href="{{ route('admin.report.near_expiry_stock') }}" class="table-view-all-btn commonBtn-btnTag">view all</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="box-card-new">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top Selling Product</h3>
                        {{-- <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                            </a>
                        </div> --}}
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-valign-middle">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col">Product</th> --}}
                                        <th scope="col">Brand</th>
                                        <th scope="col">Barcode</th>
                                        <th scope="col">In Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['top_products'] as $top_productsitem)
                                        <tr>
                                            {{-- <td>{{$top_productsitem['product_name']}}</td> --}}
                                            <td>{{$top_productsitem['brand']}}</td>
                                            <td>{{$top_productsitem['product_barcode']}}</td>
                                            <td>{{$top_productsitem['t_qty']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-view-all">
                        <a href="{{ route('admin.report.top_selling_products') }}" class="table-view-all-btn commonBtn-btnTag">view all</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="box-card-new">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Slow Moving Item</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-valign-middle">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col">Product</th> --}}
                                        <th scope="col">Brand</th>
                                        <th scope="col">Barcode</th>
                                        <th scope="col">In Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['low_products'] as $low_productsitem)
                                        <tr>
                                            {{-- <td>{{$low_productsitem['product_name']}}</td> --}}
                                            <td>{{$low_productsitem['brand']}}</td>
                                            <td>{{$low_productsitem['product_barcode']}}</td>
                                            <td>{{$low_productsitem['t_qty']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-view-all">
                        {{-- <a href="#" class="table-view-all-btn commonBtn-btnTag">view all</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="chartArea">
{{-- <div class="row g-3">
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <!-- DONUT CHART -->
      <div class="card">
        <div class="card-header border-0">
          <h3 class="card-title">Donut Chart</h3>
        </div>
        <div class="card-body">
          <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <!-- AREA CHART -->
      <div class="card">
        <div class="card-header border-0">
          <h3 class="card-title">Area Chart</h3>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <!-- PIE CHART -->
      <div class="card">
        <div class="card-header border-0">
          <h3 class="card-title">Pie Chart</h3>
        </div>
        <div class="card-body">
          <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <!-- BAR CHART -->
      <div class="card">
        <div class="card-header border-0">
          <h3 class="card-title">Bar Chart</h3>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header border-0">
          <h3 class="card-title">Sales Chart</h3>
        </div>
        <div class="card-body">
          <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
        <!-- /.card-body -->
      </div>

    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <div class="card">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <h3 class="card-title">Sales</h3>
            <a href="javascript:void(0);">View Report</a>
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex">
            <p class="d-flex flex-column">
              <span class="text-bold text-lg">$18,230.00</span>
              <span>Sales Over Time</span>
            </p>
            <p class="ml-auto d-flex flex-column text-right">
              <span class="text-success">
                <i class="fas fa-arrow-up"></i> 33.1%
              </span>
              <span class="text-muted">Since last month</span>
            </p>
          </div>
          <!-- /.d-flex -->

          <div class="position-relative mb-4">
            <canvas id="sales-chart" height="200"></canvas>
          </div>

          <div class="d-flex flex-row justify-content-end">
            <span class="mr-2">
              <i class="fas fa-square text-primary"></i> This year
            </span>

            <span>
              <i class="fas fa-square text-gray"></i> Last year
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card">
                    <div class="card-header">
                    <h3 class="card-title">Products</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-bars"></i>
                        </a>
                    </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                    <table class="table table-valign-middle">
                        <thead>
                        <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Sales</th>
                        <th>More</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td>

                            Some Product
                        </td>
                        <td>$13 USD</td>
                        <td>
                            <small class="text-success mr-1">
                            <i class="fas fa-arrow-up"></i>
                            12%
                            </small>
                            12,000 Sold
                        </td>
                        <td>
                            <a href="#" class="text-muted">
                            <i class="fas fa-search"></i>
                            </a>
                        </td>
                        </tr>
                        <tr>
                        <td>

                            Another Product
                        </td>
                        <td>$29 USD</td>
                        <td>
                            <small class="text-warning mr-1">
                            <i class="fas fa-arrow-down"></i>
                            0.5%
                            </small>
                            123,234 Sold
                        </td>
                        <td>
                            <a href="#" class="text-muted">
                            <i class="fas fa-search"></i>
                            </a>
                        </td>
                        </tr>
                        <tr>
                        <td>

                            Amazing Product
                        </td>
                        <td>$1,230 USD</td>
                        <td>
                            <small class="text-danger mr-1">
                            <i class="fas fa-arrow-down"></i>
                            3%
                            </small>
                            198 Sold
                        </td>
                        <td>
                            <a href="#" class="text-muted">
                            <i class="fas fa-search"></i>
                            </a>
                        </td>
                        </tr>
                        <tr>
                        <td>

                            Perfect Item
                            <span class="badge bg-danger">NEW</span>
                        </td>
                        <td>$199 USD</td>
                        <td>
                            <small class="text-success mr-1">
                            <i class="fas fa-arrow-up"></i>
                            63%
                            </small>
                            87 Sold
                        </td>
                        <td>
                            <a href="#" class="text-muted">
                            <i class="fas fa-search"></i>
                            </a>
                        </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Online Store Overview</h3>
                  <div class="card-tools">
                    <a href="#" class="btn btn-sm btn-tool">
                      <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-tool">
                      <i class="fas fa-bars"></i>
                    </a>
                  </div>
                </div>
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                    <p class="text-success text-xl">
                      <i class="ion ion-ios-refresh-empty"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                      <span class="font-weight-bold">
                        <i class="ion ion-android-arrow-up text-success"></i> 12%
                      </span>
                      <span class="text-muted">CONVERSION RATE</span>
                    </p>
                  </div>
                  <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                    <p class="text-warning text-xl">
                      <i class="ion ion-ios-cart-outline"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                      <span class="font-weight-bold">
                        <i class="ion ion-android-arrow-up text-warning"></i> 0.8%
                      </span>
                      <span class="text-muted">SALES RATE</span>
                    </p>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-0">
                    <p class="text-danger text-xl">
                      <i class="ion ion-ios-people-outline"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                      <span class="font-weight-bold">
                        <i class="ion ion-android-arrow-down text-danger"></i> 1%
                      </span>
                      <span class="text-muted">REGISTRATION RATE</span>
                    </p>
                  </div>
                </div>
        </div>
    </div>
</div> --}}


@endsection

@section('scripts')
<script src="{{ url('assets/admin/js/plugins/chart/Chart.min.js') }}"></script>

<script type="text/javascript">
    $('#store').select2( {
        theme: 'bootstrap-5'
    });
  $(function () {
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d');
    var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'Digital Goods',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90]
        },
        {
          label               : 'Electronics',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    new Chart(areaChartCanvas, {
      type: 'line',
      data: areaChartData,
      options: areaChartOptions
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Chrome',
          'IE',
          'FireFox',
          'Safari',
          'Opera',
          'Navigator',
      ],
      datasets: [
        {
          data: [700,500,400,600,300,100],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

     //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })


    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })


     // Sales graph chart
  var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels: ['2011 Q1', '2011 Q2', '2011 Q3', '2011 Q4', '2012 Q1', '2012 Q2', '2012 Q3', '2012 Q4', '2013 Q1', '2013 Q2'],
    datasets: [
      {
        label: 'Digital Goods',
        fill: false,
        borderWidth: 1,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#EB008A',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#EB008A',
        data: [2666, 2778, 4912, 3767, 6810, 5670, 4820, 15073, 10687, 8432]
      }
    ]
  }

  var salesGraphChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#666'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#666'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(salesGraphChartCanvas, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesGraphChartData,
    options: salesGraphChartOptions
  })


  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true
  var $salesChart = $('#sales-chart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          data: [1000, 2000, 3000, 2500, 2700, 2500, 3000]
        },
        {
          backgroundColor: '#ced4da',
          borderColor: '#ced4da',
          data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
        }
      ]
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
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '$' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })






  });



  function selectStore(store_id){
    if(store_id!=''){
        $("#selectStoreForm").submit();
    }else{
        location.href = "{{ route('admin.dashboard') }}";
    }
  }


</script>
@endsection
