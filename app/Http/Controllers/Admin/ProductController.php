<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\Review;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request) // Injete o objeto Request para acessar os parâmetros de entrada.
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Product::with(['categories', 'tags', 'media']);

        if ($request->has('search')) {
            $search = $request->get('search');

            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->get();

        $productCategories = ProductCategory::all();

        return view('admin.products.index', compact('products', 'productCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ProductCategory::pluck('name', 'id');

        $tags = ProductTag::pluck('name', 'id');

        return view('admin.products.create', compact('categories', 'tags'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->categories()->sync($request->input('categories', []));
        $product->tags()->sync($request->input('tags', []));
        if ($request->input('photo', false)) {
            $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $product->id]);
        }

        return redirect()->route('admin.products.index');
    }

        public function edit(Product $product)
        {
            abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $categories = ProductCategory::pluck('name', 'id');

            $tags = ProductTag::pluck('name', 'id');

            $product->load('categories', 'tags');

            return view('admin.products.edit', compact('categories', 'product', 'tags'));
        }

        public function update(UpdateProductRequest $request, Product $product)
        {
            $product->update($request->all());
            $product->categories()->sync($request->input('categories', []));
            $product->tags()->sync($request->input('tags', []));
            if ($request->input('photo', false)) {
                if (! $product->photo || $request->input('photo') !== $product->photo->file_name) {
                    if ($product->photo) {
                        $product->photo->delete();
                    }
                    $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
                }
            } elseif ($product->photo) {
                $product->photo->delete();
            }

            return redirect()->route('admin.products.index');
        }

        public function show(Product $product)
        {
            abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $product->load('categories', 'tags', 'reviews');

            return view('admin.products.show', compact('product'));
        }

        public function destroy(Product $product)
        {
            abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $product->delete();

            return back();
        }

        public function massDestroy(MassDestroyProductRequest $request)
        {
            $products = Product::find(request('ids'));

            foreach ($products as $product) {
                $product->delete();
            }

            return response(null, Response::HTTP_NO_CONTENT);
        }

        public function storeCKEditorImages(Request $request)
        {
            abort_if(Gate::denies('product_create') && Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $model         = new Product();
            $model->id     = $request->input('crud_id', 0);
            $model->exists = true;
            $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

            return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
        }

            public function filterByCategory($categoryId)
        {
            $filteredProducts = Product::with('categories')->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            })->get();

            $productCategories = ProductCategory::all();

            return view('admin.products.filtered-products', compact('filteredProducts', 'productCategories'));
        }

        public function storeReview(Request $request,)
        {
            $product_id = $request->input('product_id');

            $request->validate([
                'comment' => 'required|string',
                'rating' => 'required|integer|between:1,5',
            ]);

            $review = new Review();
            $review->comment = $request->input('comment');
            $review->rating = $request->input('rating');
            $review->product_id = $product_id;
            $review->user_id = auth()->user()->id;
            $review->save();

            return redirect()->route('admin.products.show', ['product' => $product_id]);
        }
}
