<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\ArticleFavourite;
use App\Models\Location;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class ArticleController extends Controller
{
    //Lấy bài viết cá nhân 
    public function showPostMe(Request $request)
    {
        $post = Article::where('user_id', $request->user()->users_id)->with(['topic', 'user', 'location'])->get();
        if ($post->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Không có bài viết !'], 200);
        } else {
            return response()->json(['success' => true, 'message' => 'Có bài viết yêu thích!', 'data' => $post], 200);
        }
    }
    //Lấy bài viết đã lưu
    public function showFavourite(Request $request)
    {
        $favourites = ArticleFavourite::where('user_id', $request->user()->users_id)->with(['article.topic', 'article.user', 'article.location'])->get();
        if ($favourites->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Không có bài viết yêu thích!'], 200);
        } else {
            return response()->json(['success' => true, 'message' => 'Có bài viết yêu thích!', 'data' => $favourites], 200);
        }
    }
    //Thêm yêu thích
    public function addFavourite(Request $request)
    {
        $favourites = new ArticleFavourite();
        $favourites->user_id = $request->user()->users_id;
        $favourites->article_id = $request['article_id'];
        $favourites->save();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Lưu vào mục yêu thích thành công!']);
    }
    //Xóa yêu thích
    public function removeFavourite($id)
    {
        ArticleFavourite::where('article_id', $id)->delete();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Hủy yêu thích thành công!']);
    }
    //Thêm comment
    public function addComment(Request $request)
    {
        $comment = new ArticleComment();
        $comment->content = $request['content'];
        $comment->user_id = $request->user()->users_id;
        $comment->article_id = $request['article_id'];
        $comment->save();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!']);
    }

    //Lấy bài viết theo chủ đề
    public function showByTopic($id)
    {
        if (intval($id) == 0) {
            $data = Article::where('status', 1)->with('topic', 'user', 'location')->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $data = Article::whereHas('topic', function ($query) use ($id) {
                $query->where('topic_id', $id);
            })
                ->where('status', 1)
                ->with('topic', 'user', 'location',)->orderBy('created_at', 'desc')->paginate(10);
        }
        if (!$data->isEmpty()) {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!', 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'Không có dữ liệu!'], 201);
        }
    }

    //Lấy chi tiết bài viết theo Id
    public function showById($id)
    {
        $article = Article::with('user')->where('articles_id', $id)->first();
        if ($article) {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!', 'data' => $article]);
        }
        return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tìm thấy dữ liệu !'], 404);
    }
    public function showCommentByArticle($id)
    {
        $countTotalComment = ArticleComment::where('article_id', $id)->count();
        $comments = ArticleComment::with('user')->where('article_id', $id)->paginate(10);
        if ($comments) {
            $comments->appends(['countTotalComment' => $countTotalComment]);
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!', 'data' => $comments]);
        }
        $comments->appends(['countTotalComment' => $countTotalComment])->links();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Không có bình luận!', 'data' => $comments]);
    }

    //Duyệt bài hoặc từ chối
    public function updateStatus(Request $request, $id)
    {
        $article = Article::where('articles_id', $id)->first();
        if ($article) {
            if ($request->has('status')) {
                $article->status = intval($request['status']);
            }
            $article->save();
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Cập nhật thành công!']);
        }
        return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tồn tại bài viết!'], 404);
    }
    //Lấy 10 bài viết mới nhất
    public function articleNew()
    {
        $data = Article::where('status', 1)->with([
            'topic', 'user', 'location',
            'place' => function ($query) {
                $query->select('name');
            },
            'festival' => function ($query) {
                $query->select('name');
            }
        ])->latest()->take(10)->get();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công !', 'data' => $data]);
    }

    // Lất tả cả bài viết
    public function show(Request $request)
    {
        $status = intval($request['status']);
        $data = Article::where('status', $status)->with([
            'topic', 'user', 'location',
            'place' => function ($query) {
                $query->select('name');
            },
            'festival' => function ($query) {
                $query->select('name');
            }
        ])->orderBy('created_at', 'desc')->paginate(10);

        $countPostPending = Article::where('status', 0)->count();

        $data->appends(['status' => $status]);
        $data->appends(['countPostPending' => $countPostPending])->links();

        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công !', 'data' => $data]);
    }

    //Thêm bài viết
    public function add(Request $request)
    {
        $post = new Article();

        $post->title = $request['title'];
        if ($request->user()->role === 1) {
            $post->status = 1;
        } else {
            $post->status = $request['status'] ?? 0;
        }
        $post->content = $request['content'];
        $post->place_id = ($request['place_id'] === "undefined") ? null : $request['place_id'];
        $post->festival_id = ($request['festival_id'] === "undefined") ? null : $request['festival_id'];
        $post->user_id = $request->user()->users_id;
        if (intval($request['topic']) == 0) {
            $topic = Topic::firstOrCreate(['name' => $request['topic']]);
            $post->topic_id = $topic->topics_id;
        } else {
            $post->topic_id = intval($request['topic']);
        }
        if (intval($request['location']) == 0) {
            $location = Location::firstOrCreate(['name' => $request['location']]);
            $post->location_id = $location->locations_id;
        } else {
            $post->location_id = intval($request['location']);
        }
        $avatar = null;
        $publicId = null;
        if ($request->hasFile('avatar')) {
            $image = cloudinary()->upload($request->file('avatar')->getRealPath(), ['folder' => 'travel/article']);
            $avatar = $image->getSecurePath();
            $publicId = $image->getPublicId();
        }
        if ($avatar !== null && $publicId !== null) {
            $post->images = json_encode(['avatar' => $avatar, 'publicId' => $publicId]);
            $post->save();
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công']);
        }
        return response()->json(['success' => false, 'code' => 422, 'message' => 'Đăng tải thất bại'], 422);
    }
}
