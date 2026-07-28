<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\CommentRequest;
use App\Models\BlogPosts;
use App\Models\BlogTags;
use App\Models\Comments;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPosts::published()->with(['author', 'tags']);

        if ($tagName = $request->string('tag')->trim()->value()) {
            $query->whereHas(
                'tags',
                fn ($builder) => $builder->where('blog_tags.name', $tagName),
            );
        }

        $posts = $query->latest('published_at')->paginate(9)->withQueryString();
        $popularTags = $this->getPopularTags();

        return view('blog.index', compact('posts', 'popularTags'));
    }

    public function show(string $slug)
    {
        $post = BlogPosts::published()
            ->with(['author', 'tags', 'comments.replies'])
            ->withCount(['allComments as approved_comments_count' => fn ($query) => $query->where('is_approved', true)])
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('view_count');

        $relatedPosts = BlogPosts::published()
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->limit(3)
            ->get();
        $popularTags = $this->getPopularTags();

        return view('blog.show', compact('post', 'relatedPosts', 'popularTags'));
    }

    public function comment(CommentRequest $request, string $slug)
    {
        $post = BlogPosts::published()->where('slug', $slug)->firstOrFail();
        $data = $request->validated();

        if (! empty($data['website'])) {
            return redirect()
                ->route('blog.show', $post->slug)
                ->withFragment('comments')
                ->with('comment_success', 'Komentar berhasil dikirim.');
        }

        if (! empty($data['parent_id'])) {
            $isValidParent = Comments::query()
                ->whereKey($data['parent_id'])
                ->where('post_id', $post->getKey())
                ->whereNull('parent_id')
                ->where('is_approved', true)
                ->exists();

            abort_unless($isValidParent, 422, 'Komentar induk tidak valid.');
        }

        $isApproved = ! $this->looksSuspicious($data['content']);
        unset($data['website']);

        Comments::create([
            ...$data,
            'post_id' => $post->getKey(),
            'user_id' => $request->user()?->getKey(),
            'is_approved' => $isApproved,
        ]);

        return redirect()
            ->route('blog.show', $post->slug)
            ->withFragment('comments')
            ->with('comment_success', $isApproved
                ? 'Komentar berhasil diterbitkan.'
                : 'Komentar berhasil dikirim dan sedang diperiksa.');
    }

    private function getPopularTags()
    {
        return BlogTags::query()
            ->whereHas('posts', fn ($query) => $query->published())
            ->withCount(['posts as total' => fn ($query) => $query->published()])
            ->orderByDesc('total')
            ->limit(15)
            ->get();
    }

    private function looksSuspicious(string $content): bool
    {
        $normalized = mb_strtolower(strip_tags($content));
        $linkCount = preg_match_all('/(?:https?:\/\/|www\.)/i', $normalized);
        $spamTerms = [
            'casino', 'judi online', 'slot gacor', 'pinjaman online',
            'buy followers', 'crypto giveaway', 'free money',
        ];

        return $linkCount > 1
            || collect($spamTerms)->contains(fn ($term) => str_contains($normalized, $term))
            || preg_match('/(.)\1{9,}/u', $normalized) === 1;
    }
}
