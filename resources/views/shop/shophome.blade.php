<x-shop_layout>
    <section class="banner banner-secondary" id="top" style="background-image: url(img/banner-image-1-1920x300.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="banner-caption">
                        <div class="line-dec"></div>
                        <h2>Products</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main>
        <section class="featured-places">
            <div class="container">
                <div class="row">

                    @foreach ($products as $product)

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="featured-item">
                            <div class="thumb">
                                <img src="{{ asset('images/' . $product->image) }}" alt="">
                            </div>
                            <div class="down-content">
                                <h4>{{ $product->name }}</h4>

                                <span><strong><sup>Rs. {{ $product->price }}</strong></span>

                                <p>{{ $product->description }}</p>
                                <div class="text-button">
                                    <a href="{{ url('more/' . $product->id) }}">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach


                </div>
            </div>
        </section>
    </main>
    </x-shop_layout>
