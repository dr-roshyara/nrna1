<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Candidacy;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CandidacyManagementController extends Controller
{
    public function store(Request $request, Organisation $organisation, string $election, Post $post)
    {
        $electionModel = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($electionModel->type === 'demo', 404);
        $this->authorize('managePosts', $electionModel);
        abort_if($post->organisation_id !== $organisation->id, 403);

        $data = $request->validate([
            'user_id'        => [
                'nullable', 'uuid',
                Rule::exists('user_organisation_roles', 'user_id')
                    ->where('organisation_id', $organisation->id),
            ],
            'name'           => ['required_without:user_id', 'nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'position_order' => ['nullable', 'integer', 'min:0'],
            'image_1'        => ['nullable', 'image', 'max:2048'],
            'image_2'        => ['nullable', 'image', 'max:2048'],
            'image_3'        => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePaths = [];
        foreach (['image_1', 'image_2', 'image_3'] as $i => $field) {
            if ($request->hasFile($field)) {
                $imagePaths['image_path_' . ($i + 1)] = $request->file($field)
                    ->store("candidacies/{$organisation->id}", 'public');
            }
        }

        Candidacy::create([
            'post_id'         => $post->id,
            'organisation_id' => $organisation->id,
            'user_id'         => $data['user_id'] ?? null,
            'name'            => $data['name'] ?? null,
            'description'     => $data['description'] ?? null,
            'position_order'  => $data['position_order'] ?? 0,
            'status'          => Candidacy::STATUS_APPROVED,
            ...$imagePaths,
        ]);

        return back()->with('success', __('Candidate added successfully.'));
    }

    public function update(Request $request, Organisation $organisation, string $election, Post $post, Candidacy $candidacy)
    {
        $electionModel = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($electionModel->type === 'demo', 404);
        $this->authorize('managePosts', $electionModel);
        abort_if($candidacy->organisation_id !== $organisation->id, 403);

        $data = $request->validate([
            'name'           => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'status'         => ['required', 'in:pending,approved,rejected,withdrawn'],
            'position_order' => ['nullable', 'integer', 'min:0'],
            'image_1'        => ['nullable', 'image', 'max:2048'],
            'image_2'        => ['nullable', 'image', 'max:2048'],
            'image_3'        => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePaths = [];
        foreach (['image_1', 'image_2', 'image_3'] as $i => $field) {
            if ($request->hasFile($field)) {
                $imagePaths['image_path_' . ($i + 1)] = $request->file($field)
                    ->store("candidacies/{$organisation->id}", 'public');
            }
        }

        $candidacy->update(array_merge(
            collect($data)->only(['name', 'description', 'status', 'position_order'])->toArray(),
            $imagePaths
        ));

        return back()->with('success', __('Candidate updated successfully.'));
    }

    public function destroy(Organisation $organisation, string $election, Post $post, Candidacy $candidacy)
    {
        $electionModel = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($electionModel->type === 'demo', 404);
        $this->authorize('managePosts', $electionModel);
        abort_if($candidacy->organisation_id !== $organisation->id, 403);

        $candidacy->delete();

        return back()->with('success', __('Candidate removed successfully.'));
    }
}
