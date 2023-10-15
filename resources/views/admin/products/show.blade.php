@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn-blue" href="{{ route('admin.products.index') }}">
        << {{ trans('global.back_to_list') }}
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
        <div class="col-md-4"><center><img src="{{ $product->photo->getUrl() }}" width="65%"></center></div>

        <div class="col-md-8">
            <b>ID:</b> {{ $product->id }}<br>

            @foreach($product->categories as $category)
            <span class="badge badge-info category-filter">{{ $category->name }}</span>
            @endforeach
            <br><br>
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

                    @endif

            <div class="tit-show">{{ $product->name }}</div><br>
            <b>Descrição:</b><br>
            {{ $product->description }}<br>
            <br>

            <a href="/" class="btn btn-dow">Download</a>

                            <br><br>
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
</div>
</div>
</div>

<div class="card">
    <div class="card-ebook">
        @if ($product->reviews->isEmpty())
            <p>Nenhuma avaliação encontrada para este produto.</p>
        @else
            @foreach($product->reviews as $review)
                <div class="rating">
                    @for ($i = 5; $i >= 1; $i--)
                        @if ($i <= $review->rating)
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>

                @if($review->user)
                    <div class="tit-review">{{ $review->user->name }}</div>
                @else
                    <p>Usuário não encontrado</p>
                @endif

                <p>{{ $review->comment }}</p>
                <hr>
            @endforeach
        @endif

        <br>

        <h3>Deixe sua avaliação</h3>
        <br>
        <form action="{{ route('admin.products.storeReview', ['product' => $product->id]) }}" method="POST">
            <div class="rating-form">
                <input type="radio" id="star5" name="rating" value="5" /><label for="star5"></label>
                <input type="radio" id="star4" name="rating" value="4" /><label for="star4"></label>
                <input type="radio" id="star3" name="rating" value="3" /><label for="star3"></label>
                <input type="radio" id="star2" name="rating" value="2" /><label for="star2"></label>
                <input type="radio" id="star1" name="rating" value="1" /><label for="star1"></label>
            </div>
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <textarea class="form-control" id="comment" name="comment"></textarea><br>
            <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
        </form>
    </div>
</div>
@endsection
