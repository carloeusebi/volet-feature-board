<?php

namespace Mydnic\VoletFeatureBoard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Mydnic\Volet\Features\FeatureManager;
use Mydnic\VoletFeatureBoard\Enums\FeatureStatus;
use Mydnic\VoletFeatureBoard\Models\Comment;
use Mydnic\VoletFeatureBoard\Models\Feature;
use Mydnic\VoletFeatureBoard\Models\Vote;

class FeatureController extends Controller
{
    public function __construct(
        protected FeatureManager $featureManager
    ) {}

    public function index(Request $request): JsonResponse
    {
        $authorId = auth()->check() ? auth()->id() : $request->header('X-Guest-ID');

        /** @var Feature $model */
        $model = config('volet-feature-board.models.feature');

        $features = $model::with(['comments.author', 'votes.author', 'author'])
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->get();

        $featureBoard = $this->featureManager->getFeature('volet-feature-board');
        /** @var Collection<int, array<string, string>>  $categories */
        $categories = collect($featureBoard?->getCategories());

        $features = $features->map(function (Feature $feature) use ($categories, $authorId): array {
            $category = $categories->where('slug', $feature->category)->first();
            $has_voted = $feature->votes->where('author_id', $authorId)->isNotEmpty();

            return [
                'id' => $feature->id,
                'title' => $feature->title,
                'description' => $feature->description,
                'status' => [
                    'value' => $feature->status->value,
                    'label' => trans('volet-feature-board::volet-feature-board.feature-status.'.$feature->status->value),
                ],
                'created_at' => $feature->created_at,
                'updated_at' => $feature->updated_at,
                'votes_count' => $feature->votes_count,
                'author_name' => $feature->author_name,
                'category' => $category,
                'has_voted' => $has_voted,

                'comments' => $feature->comments->map(fn (Comment $comment): array => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                    'author_name' => $comment->author_name,
                ]),
            ];
        });

        return response()->json($features);
    }

    public function store(Request $request): JsonResponse
    {
        $featureBoard = $this->featureManager->getFeature('volet-feature-board');

        $categories = collect($featureBoard->getCategories())
            ->pluck('slug')
            ->toArray();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:'.implode(',', $categories),
        ]);

        $feature = Feature::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'status' => FeatureStatus::PENDING,
            'author_id' => auth()->check() ? auth()->id() : $request->header('X-Guest-ID'),
        ]);

        return response()->json($feature);
    }

    public function show(Request $request, Feature $feature): JsonResponse
    {
        $authorId = auth()->check() ? auth()->id() : $request->header('X-Guest-ID');

        $feature->load(['votes', 'comments']);
        $feature->has_voted = Vote::where('feature_id', $feature->id)
            ->where('author_id', $authorId)
            ->exists();

        return response()->json($feature);
    }
}
