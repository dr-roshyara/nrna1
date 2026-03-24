<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostManagementController extends Controller
{
    public function index(Organisation $organisation, string $election): Response
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->type === 'demo', 404, 'Post management is not available for demo elections.');
        $this->authorize('managePosts', $election);

        $election->load(['organisation']);

        $posts = Post::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('organisation_id', $organisation->id)
            ->with(['candidacies' => function ($q) {
                $q->withoutGlobalScopes()
                  ->with('user')
                  ->orderBy('position_order');
            }])
            ->orderBy('position_order')
            ->get()
            ->map(fn($post) => [
                'id'               => $post->id,
                'name'             => $post->name,
                'nepali_name'      => $post->nepali_name,
                'is_national_wide' => $post->is_national_wide,
                'state_name'       => $post->state_name,
                'required_number'  => $post->required_number,
                'position_order'   => $post->position_order,
                'candidacies'      => $post->candidacies->map(fn($c) => [
                    'id'             => $c->id,
                    'name'           => $c->candidate_name,
                    'description'    => $c->description,
                    'status'         => $c->status,
                    'position_order' => $c->position_order,
                    'user_id'        => $c->user_id,
                    'image_path_1'   => $c->image_path_1,
                    'image_path_2'   => $c->image_path_2,
                    'image_path_3'   => $c->image_path_3,
                ]),
            ]);

        return Inertia::render('Election/Posts/Index', [
            'election'     => $election->only('id', 'name', 'slug', 'status', 'type'),
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'posts'        => $posts,
        ]);
    }

    public function store(Request $request, Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->type === 'demo', 404);
        $this->authorize('managePosts', $election);

        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'nepali_name'      => ['nullable', 'string', 'max:255'],
            'is_national_wide' => ['required', 'boolean'],
            'state_name'       => ['nullable', 'string', 'max:255', 'required_if:is_national_wide,false'],
            'required_number'  => ['required', 'integer', 'min:1'],
            'position_order'   => ['nullable', 'integer', 'min:0'],
        ]);

        Post::create([
            ...$data,
            'election_id'     => $election->id,
            'organisation_id' => $organisation->id,
        ]);

        return back()->with('success', __('Post created successfully.'));
    }

    public function update(Request $request, Organisation $organisation, string $election, Post $post)
    {
        $electionModel = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($electionModel->type === 'demo', 404);
        $this->authorize('managePosts', $electionModel);
        abort_if($post->organisation_id !== $organisation->id, 403);

        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'nepali_name'      => ['nullable', 'string', 'max:255'],
            'is_national_wide' => ['required', 'boolean'],
            'state_name'       => ['nullable', 'string', 'max:255', 'required_if:is_national_wide,false'],
            'required_number'  => ['required', 'integer', 'min:1'],
            'position_order'   => ['nullable', 'integer', 'min:0'],
        ]);

        $post->update($data);

        return back()->with('success', __('Post updated successfully.'));
    }

    public function destroy(Organisation $organisation, string $election, Post $post)
    {
        $electionModel = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($electionModel->type === 'demo', 404);
        $this->authorize('managePosts', $electionModel);
        abort_if($post->organisation_id !== $organisation->id, 403);
        abort_if(
            $post->candidacies()->withoutGlobalScopes()->exists(),
            422,
            'Cannot delete a post that has candidates. Remove all candidates first.'
        );

        $post->delete();

        return back()->with('success', __('Post deleted successfully.'));
    }
}
