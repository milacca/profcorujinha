@extends('layouts.admin')
@section('content')

<div class="content">
     <h1>E-books de <b>{{ $filteredProducts->first()->categories->first()->name ?? '' }}</b></h1><br>
     <div class="row">
        <div class="card col-md-8">
            <div class="card-ebook">
                @foreach($filteredProducts as $product)

                        <a href="{{ route('admin.products.show', $product->id) }}" style="float: left;padding:50px 30px 50px;">
                            <img src="{{ $product->photo->getUrl() }}" width="130px">
                        </a><br><br>
                        @foreach($product->categories as $key => $item)
                            <a href="{{ route('admin.products.filter', ['category' => $item->id]) }}" class="badge badge-info category-filter">
                                {{ $item->name }}
                            </a>
                        @endforeach
                        <br>
                        @php
                        $totalRating = 0;
                        $reviewCount = count($product->reviews);
                    @endphp
                    @if ($reviewCount > 0)
                        @foreach($product->reviews as $review)
                        @php
                                $totalRating += $review->rating;
                            @endphp
                        @endforeach

                        @php
                            $averageRating = ($totalRating / $reviewCount);
                            $averageStars = '';
                            for ($i = 5; $i >= 1; $i--) {
                                if ($i <= $averageRating) {
                                    $averageStars .= '<i class="fas fa-star"></i>';
                                } else {
                                    $averageStars .= '<i class="far fa-star"></i>';
                                }
                            }
                        @endphp
                        <div class="rating-all">{!! $averageStars !!}</div>
                    @else

                    @endif<br>
                        <div class="tit-ebook-box">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="tit-ebook">
                                <b>{{ $product->name ?? '' }}</b>
                            </a><br>
                            {{ (strlen($product->description ?? '') > 100) ? substr($product->description ?? '', 0, 100) . '...' : ($product->description ?? '') }}

                            <br>
                            <a class="btn btn-dow" href="{{ route('admin.products.show', $product->id) }}">
                                Saiba mais
                            </a><br>
                            @can('product_edit')
                                    <a class="btn" href="{{ route('admin.products.edit', $product->id) }}" style="padding:0;">
                                        <i class="fas fa-edit" style="font-size:16px;color:#47bee6;"></i>
                                    </a>
                                @endcan

                                @can('product_delete')
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="btn" type="submit" style="padding:0;color:#47bee6;">
                                            <i class="fas fa-trash" style="font-size:16px;"></i>
                                        </button>
                                    </form>
                                @endcan
                        </div>

                        <div style="clear: both;"></div>
                        <hr>

                @endforeach
            </div>
        </div>


        <div class="col-md-4" style="padding-left: 50px;">
            <h3>Categorias</h3><br>
            @foreach($productCategories as $productCategory)
            <a href="{{ route('admin.products.filter', ['category' => $productCategory->id]) }}">
                {{ $productCategory->name }}
            </a><hr>
            @endforeach
        </div>

</div>
</div>
@endsection
