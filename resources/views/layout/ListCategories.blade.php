
<div class="row">
    <div class="col-xl-12">
        <div class="row justify-content-center">
            @foreach ($categories as $item)
                <div class="col-lg-4 col-sm-8 mb-3">
                    <div
                        class="card border-0 position-relative b-2" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25);"
                        >
                            <img class="card-img-top b-2 image-product" width="100%" src="{{ $item->thumbnail }}" alt="Title" />
                            <div class="card-body position-absolute">
                                <h4 class="card-title">{{ $item->name }}</h4>
                                <a href="{{ route('user.category', ['slug' => $item->slug]) }}">
                                    <p class="card-text btn btn-primary bg-color border-color b-2" >Khám Phá</p>
                                </a>
                            </div>
                        </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@isset($recommendation)

<div class="new-product pt-5">

    <div class="title">
        <h3 class="text-center text-bold">Dành Cho Bạn</h3>
    </div>

    <div class="menu-product pt-3">
        <div class="row justify-content-center">
            @foreach ($recommendation as $item)
                <div class="col-lg-3 col-6 mb-3 p-1" >
                    <a href="{{ route('user.detailproduct', ['id' => $item->id]) }}" class="text-decoration-none">
                        <div class="card b-2">
                            <img
                                class="card-img-top image-product"
                                src="{{ $item->poster }}"
                                alt="Card image cap"
                            />
                            <div class="card-body d-flex flex-column justify-content-between p-sp-1">
                                <h4 class="card-title font-size-16 clamp-2-lines fs-md-4 fs-lg-3">{{ $item->title }}</h4>
                                <div class="label d-flex align-items-center gap-1 mb-1">
                                    <div class="border-1 border-success">
                                        <span class="text-success badge">Dành cho bạn</span>
                                    </div>
                                    <div class="sale badge bg-danger"> 
                                        <span class="badge text-white">- {{ ($item->option_type == 0) ? $item->variations[0]->sale ?? 0 : $item->sale ?? 0 }}%</span>
                                    </div>
                                </div> 

                                <div class="price-product d-flex">
                                    <span class="card-link text-secondary text-decoration-line-through">
                                        @if ($item->option_type == 0)
                                            @formatPrice($item->variations[0]->price ?? 0) 
                                        @else
                                            @formatPrice($item->price ?? 0)
                                        @endif
                                    </span>
                                    <span class="card-link text-danger">
                                        @if ($item->option_type == 0)
                                            @handlePrice($item->variations[0]->price ?? 0, $item->variations[0]->sale ?? 0) 
                                        @else
                                            @handlePrice($item->price ?? 0, $item->sale ?? 0)
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
            
        </div>
    </div>

</div>

@endisset