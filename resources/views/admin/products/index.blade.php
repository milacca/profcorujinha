@extends('layouts.admin')
@section('content')


<div style="margin-bottom: 10px;" class="row">
    <div class="col-md-auto">
        <h1>{{ trans('cruds.product.title') }}</h1>
      </div>
      @can('product_create')
      <div class="col-md-auto">
        <a class="btn btn-success" href="{{ route('admin.products.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.product.title_singular') }}
        </a>
      </div>
      @endcan
</div>

    <div class="row">
        <div class="card col-md-8">
            <div class="card-ebook">
                @forelse($products as $key => $product)

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

                        @empty

                        <div style="text-align: center;font-size:28px;padding:60px;">
                            Não encontramos e-books<br> que correspondam à sua pesquisa.
                        </div>
                    @endforelse
            </div>
        </div>

        <div class="col-md-4" style="padding-left: 50px;">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Pesquisar" value="{{ request()->get('search', '') }}">
                    <div class="input-group-append">
                        <button class="btn btn-ser" type="submit">
                            <img src="https://professoracorujinha.com.br/wp-content/uploads/lupa.png" style="width: 20px;">
                        </button>
                    </div>
                </div>
            </form>

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
