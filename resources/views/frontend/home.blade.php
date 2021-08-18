@extends('layouts.frontend.app')
@section('content')
<!-- Carousel Start -->
<div class="carousel">
    <div class="container-fluid">
        <div class="owl-carousel">
            <div class="carousel-item">
                <div class="carousel-img">
                    <img src="{{asset('assets/frontend/img/carousel-1.jpg')}}" alt="Image">
                </div>
                <div class="carousel-text">
                    <h3>Washing & Detailing</h3>
                    <h1>Keep your Car Newer</h1>
                    <p>
                        Lorem ipsum dolor sit amet elit. Phasellus ut mollis mauris. Vivamus egestas eleifend dui ac
                    </p>
                    <a class="btn btn-custom" href="">Explore More</a>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-img">
                    <img src="{{asset('assets/frontend/img/carousel-2.jpg')}}" alt="Image">
                </div>
                <div class="carousel-text">
                    <h3>Washing & Detailing</h3>
                    <h1>Quality service for you</h1>
                    <p>
                        Morbi sagittis turpis id suscipit feugiat. Suspendisse eu augue urna. Morbi sagittis orci sodales
                    </p>
                    <a class="btn btn-custom" href="">Explore More</a>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-img">
                    <img src="{{asset('assets/frontend/img/carousel-3.jpg')}}" alt="Image">
                </div>
                <div class="carousel-text">
                    <h3>Washing & Detailing</h3>
                    <h1>Exterior & Interior Washing</h1>
                    <p>
                        Sed ultrices, est eget feugiat accumsan, dui nibh egestas tortor, ut rhoncus nibh ligula euismod quam
                    </p>
                    <a class="btn btn-custom" href="">Explore More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Carousel End -->
@endsection