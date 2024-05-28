<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Lấy comment sản phẩm chi tiết
    public function showCommentByProduct($id)
    {
        $product = Product::where('products_id', $id)->first();
        if ($product) {
            $comments = $product->comments()->with('user')->paginate(3);
            if ($comments->total() > 0) {
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $comments]);
            }
            return response()->json(['success' => false, 'message' => 'Không tìm thấy comments'], 201);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], 404);
    }
    //Thêm comment
    public function addComment(Request $request)
    {
        $comment = new ProductComment();
        $comment->content = $request['content'];
        $comment->user_id = $request->user()->users_id;
        $comment->product_id = $request['product_id'];
        $comment->save();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!']);
    }

    //Xóa sản phẩm trong giỏ hàng
    public function deleteCart($id)
    {
        Cart::where('carts_id', $id)->delete();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Xóa thành công!']);
    }
    //Cập nhật giỏ hàng
    public function updateCart(Request $request, $id)
    {
        $cart = Cart::where('carts_id', $id)->first();
        $action = $request->input('action');
        if ($cart) {

            if ($action === 'increase') {
                $cart->quantity += 1;
            } elseif ($action === 'decrease') {
                if ($cart->quantity > 1) {
                    $cart->quantity -= 1;
                }
            } else {
                return response()->json(['success' => false, 'error' => 'Hành động không hợp lệ.'], 400);
            }
            $cart->save();
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Cập nhật thành công!']);
        }
        return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tồn tại !'], 404);
    }
    // Hiển thị sản phẩm trong giỏ hàng
    public function showCart(Request $request)
    {
        $cart = Cart::with('products')->where('user_id', $request->user()->users_id)->get();
        // Kiểm tra xem giỏ hàng có tồn tại không
        if ($cart) {
            // Giỏ hàng được tìm thấy, bạn có thể truy cập thông tin về sản phẩm và người dùng
            return response()->json(['success' => true, 'code' => 200, 'data' => $cart]);
        } else {
            // Không tìm thấy giỏ hàng cho người dùng hiện tại
            return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tìm thấy giỏ hàng cho người dùng hiện tại']);
        }
    }
    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $cart = new Cart();
        $cart->user_id = $request->user()->users_id;
        $cart->quantity = intval($request['quantity']);
        if (intval($request['product_id']) !== 0) {
            $cart->product_id = $request['product_id'];
            $product = Cart::where('product_id', $request['product_id'])->first();
            if (!$product) {
                $cart->save();
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công']);
            } else {
                $product->quantity += intval($request['quantity']);
                $product->save();
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công']);
            }
        }
    }

    // Lấy sản phẩm liên quan 
    public function showRelatedProduct($id)
    {
        $product = Product::where('products_id', $id)->first();

        if ($product) {
            $categoryId = $product->category_id;

            $relatedProducts = Product::where('category_id', $categoryId)
                ->where('products_id', '!=', $id) // Loại bỏ sản phẩm chi tiết
                ->paginate(10);

            if (($relatedProducts->total() > 0)) {
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $relatedProducts]);
            } else {
                return response()->json(['success' => false, 'code' => 404, 'message' => 'Không có dữ liệu !', 'data' => []]);
            }
        } else {
            return response()->json(['success' => false, 'code' => 404, 'message' => 'Lỗi', 404]);
        }
    }
    // Lấy sản phẩm theo Id
    public function showById($id)
    {
        $data = Product::where('products_id', $id)->get();
        if (!$data->isEmpty()) {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
        }
        return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tìm thấy dữ liệu', 404]);
    }
    // Lấy tất cả sản phẩm theo danh mục
    public function showByCategory($id, Request $request)
    {
        if (intval($id) === 0 &&  $request->input('searchParam') === null) {
            $data = Product::paginate(20);
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
        } else {
            $data = Product::where('category_id', $id)->where('name', 'like', '%' . $request->input('searchParam') . '%')->paginate(10);
            if (!$data->isEmpty()) {
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
            } else {
                return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tìm thấy dữ liệu', 'data' => []]);
            }
        }
    }

    // Thêm sản phẩm
    public function add(Request $request)
    {
        $product = new Product();

        $product->name = $request['name'];
        $product->description = $request['description'];
        $product->price = intval($request['price']);
        $product->quantity = ($request['quantity'] === "undefined") ? 999 : $request['quantity'];
        $product->price_sale = 0;
        if (intval($request['location']) == 0) {
            $location = Location::firstOrCreate(['name' => $request['location']]);
            $product->location_id = $location->locations_id;
        } else {
            $product->location_id = intval($request['location']);
        }
        if (intval($request['category']) == 0) {
            $category = Category::firstOrCreate(['name' => $request['category']]);
            $product->category_id = $category->categories_id;
        } else {
            $product->category_id = intval($request['category']);
        }

        $uploadedAvatar = null;
        if ($request->hasFile('avatar')) {
            $uploadedAvatar = cloudinary()->upload($request->file('avatar')->getRealPath(), ['folder' => 'travel/product/avatar'])->getSecurePath();
        }
        $imageDetails = [];
        $publicId = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $image = cloudinary()->upload($image->getRealPath(), ['folder' => 'travel/product/imageDetails']);
                $images = $image->getSecurePath();
                $id = $image->getPublicId();
                $imageDetails[] = $images;
                $publicId[] = $id;
            };
        }
        if ($uploadedAvatar !== null && !empty($imageDetails)) {
            $product->images = (['avatar' => $uploadedAvatar, 'imageDetails' => $imageDetails, 'publicId' => $publicId]);
            $product->save();
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công']);
        }

        return response()->json(['success' => false, 'code' => 422, 'message' => 'Thất bại!'], 422);
    }
}
