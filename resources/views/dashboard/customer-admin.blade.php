<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                <li class="breadcrumb-item active">Customer Admin Dashboard</li>
            </ul>
        </div>
    </div>
</div>
<!-- /Page Header -->


<div id="carouselExampleAutoplaying" class="carousel carousel-fade slide " data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets-v2/banners/HC-App-Banners-OAT.jpg" class="w-100 mb-4 d-none d-lg-block" alt="">
            <img src="assets-v2/banners/HC-App-Banners-OAT (1).jpg" class="w-100 mb-4 d-lg-none" alt="">
        </div>
        <div class="carousel-item">
            <img src="assets-v2/banners/HC-App-Banners-Zelaton.jpg" class="w-100 mb-4" alt="">
        </div>
        <div class="carousel-item">
            <img src="assets-v2/banners/HC-App-Banners-Epimol.jpg" class="w-100 mb-4" alt="">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="dash-widget">
            <div class="dash-boxs comman-flex-center mx-auto">
                <img src="assets/img/icons/calendar.svg" alt="">
            </div>
            <div class="dash-content dash-count text-center">
                <h4>Total
                    Calls This Month</h4>
                <h2><span class="counter-up" >{{$total_sales_calls}}</span></h2>
                <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>40%</span> vs last month</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="dash-widget">
            <div class="dash-boxs comman-flex-center mx-auto">
                <img src="assets/img/icons/profile-add.svg" alt="">
            </div>
            <div class="dash-content dash-count text-center">
                <h4>Coverage
                </h4>
                <h2><span class="counter-up" >40</span>%</h2>
                <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>20%</span> vs last month</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="dash-widget">
            <div class="dash-boxs comman-flex-center mx-auto">
                <img src="assets/img/icons/scissor.svg" alt="">
            </div>
            <div class="dash-content dash-count text-center">
                <h4>Total Calls Today</h4>
                <h2><span class="counter-up" >{{$today_sales_calls}}</span></h2>
                <p><span class="negative-view"><i class="feather-arrow-down-right me-1"></i>15%</span> vs last month</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="dash-widget">
            <div class="dash-boxs comman-flex-center mx-auto">
                <img src="assets/img/icons/empty-wallet.svg" alt="">
            </div>
            <div class="dash-content dash-count text-center">
                <h4>Sales Performance To-Date</h4>
                <h2><span class="counter-up" > 20</span>%</h2>
                <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>30%</span> vs last month</p>
            </div>
        </div>
    </div>
</div>
