<?php

use App\Http\Controllers\api\v1\ArticleController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\v1\Auth\RegisterController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\FestivalController;
use App\Http\Controllers\api\v1\LocationController;
use App\Http\Controllers\api\v1\OrderController;
use App\Http\Controllers\api\v1\PlaceController;
use App\Http\Controllers\api\v1\ProductController;
use App\Http\Controllers\api\v1\TopicController;
use App\Http\Controllers\api\v1\UploadController;
use App\Http\Controllers\api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('users', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(
    ['namespace' => 'Api/v1'],
    function () {
        Route::middleware('auth:sanctum')->post('product/add', [ProductController::class, 'add']);   // Thêm sản phẩm
        Route::get('product/show/category/{id}', [ProductController::class, 'showByCategory']);    // Lấy tất cả theo danh mục
        Route::get('product/show/{id}', [ProductController::class, 'showById']);    // Lấy theo Id
        Route::get('product/showRelatedProduct/{id}', [ProductController::class, 'showRelatedProduct']);    // Lấy sản phẩm liên quan
        Route::middleware('auth:sanctum')->post('product/addToCart', [ProductController::class, 'addToCart']);    // Thêm giỏ hàng
        Route::middleware('auth:sanctum')->get('product/showCart', [ProductController::class, 'showCart']);    // Thêm giỏ hàng
        Route::middleware('auth:sanctum')->put('product/updateCart/{id}', [ProductController::class, 'updateCart']);    // cập nhật giỏ hàng
        Route::middleware('auth:sanctum')->delete('product/deleteCart/{id}', [ProductController::class, 'deleteCart']);    // Xóa giỏ hàng
        Route::middleware('auth:sanctum')->post('product/comment/add', [ProductController::class, 'addComment']); //Thêm comment
        Route::get('product/show/comments/{id}', [ProductController::class, 'showCommentByProduct']); // Lấy comment theo sản phẩm


        Route::middleware('auth:sanctum')->post('order/add', [OrderController::class, 'add']);    // Tạo đơn hàng

        Route::get('category/show', [CategoryController::class, 'show']);       // Lấy tất cả danh mục
        Route::post('category/add', [CategoryController::class, 'add']);       // Thêm danh mục

        Route::middleware('auth:sanctum')->post('post/add', [ArticleController::class, 'add']);     // Thêm bài viết
        Route::get('post/show', [ArticleController::class, 'show']); // Lấy tất cả bài viết
        Route::get('post/show/{id}', [ArticleController::class, 'showById']); // Lấy bài viết theo id
        Route::get('post/topic/{id}', [ArticleController::class, 'showByTopic']); // Lấy bài viết chủ đề
        Route::get('post/show/article/new', [ArticleController::class, 'articleNew']); // Lấy bài viết mới
        Route::middleware('auth:sanctum')->post('post/comment/add', [ArticleController::class, 'addComment']);
        Route::middleware('auth:sanctum')->put('post/updateStatus/{id}', [ArticleController::class, 'updateStatus']);
        Route::get('post/show/comments/{id}', [ArticleController::class, 'showCommentByArticle']); // Lấy comment theo bài viết 


        Route::middleware('auth:sanctum')->post('place/add', [PlaceController::class, 'add']); // Thêm địa danh
        Route::get('place/show', [PlaceController::class, 'show']); // Lấy tất cả địa danh
        Route::get('place/show/{id}', [PlaceController::class, 'showById']); // Lấy tất cả địa danh theo Id
        Route::get('place/show/products/{id}', [PlaceController::class, 'showProductByPlace']); // Lấy tất cả sản phẩm theo địa danh
        Route::get('place/show/festivals/{id}', [PlaceController::class, 'showFestivalByPlace']); // Lấy tất cả sự kiện theo địa danh
        Route::get('place/show/articles/{id}', [PlaceController::class, 'showArticleByPlace']); // Lấy tất cả bài viết theo địa danh
        Route::get('place/show/locations/{id}', [PlaceController::class, 'showPlaceByLocation']); // Lấy tất cả địa danh theo địa điểm
        Route::get('place/show/comments/{id}', [PlaceController::class, 'showCommentByPlace']); // Lấy tất cả bài viết theo địa danh
        Route::middleware('auth:sanctum')->post('place/comment/add', [PlaceController::class, 'addComment']); // Thêm comment

        Route::get('location/show', [LocationController::class, 'show']); // Lấy tất cả địa điểm
        Route::middleware('auth:sanctum')->post('location/add', [LocationController::class, 'add']); // thêm  địa điểm
        Route::get('location/show/{id}', [LocationController::class, 'showById']); // Lấy địa điểm theo id

        Route::get('topic/show', [TopicController::class, 'show']); // Lấy tất cả địa điểm
        Route::middleware('auth:sanctum')->post('topic/add', [TopicController::class, 'add']); // thêm  địa điểm
        Route::get('topic/show/{id}', [TopicController::class, 'showById']); // Lấy địa điểm theo id

        Route::get('festival/show', [FestivalController::class, 'show']); // Lấy tất cả sự kiện
        Route::get('festival/show/month/{month}', [FestivalController::class, 'showFestivalByMonth']); // Lấy tất cả sự kiện
        Route::middleware('auth:sanctum')->post('festival/add', [FestivalController::class, 'add']); // thêm sự kiện
        Route::get('festival/show/{id}', [FestivalController::class, 'showById']); // Lấy sự kiện theo id
        Route::middleware('auth:sanctum')->put('festival/updateStatus/{id}', [FestivalController::class, 'updateStatus']); // Cập nhật trạng thái status
        Route::middleware('auth:sanctum')->delete('festival/delete/{id}', [FestivalController::class, 'delete']); // Cập nhật trạng thái status

        Route::middleware('auth:sanctum')->put('user/updateProfile/{id}', [UserController::class, 'updateProfile']);

        Route::post('login', [LoginController::class, 'login']);
        Route::post('register', [RegisterController::class, 'register']);
        Route::middleware('auth:sanctum')->get('logout', [LoginController::class, 'logout']);
        Route::middleware('auth:sanctum')->get('me', [LoginController::class, 'getMe']);

        Route::post('upload', [UploadController::class, 'upload']); //Upload ảnh trong bài viết
        Route::middleware('auth:sanctum')->post('carousel/add', [UploadController::class, 'add']); //Upload ảnh carousel
        Route::middleware('auth:sanctum')->get('carousel/show', [UploadController::class, 'show']); //Lấy carousel
        Route::middleware('auth:sanctum')->delete('carousel/delete/{id}', [UploadController::class, 'delete']); //Xóa carousel
        Route::middleware('auth:sanctum')->put('carousel/updateStatus/{id}', [UploadController::class, 'updateStatus']); //Cập nhật trạng thái
        Route::get('carousel/showAll', [UploadController::class, 'showAll']); //Lấy tất cả carousel có trạng thái 1

    },
    Route::post('upload', [UploadController::class, 'uploadPreview']) //Upload ảnh previwe trong bài viết
);
