<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Policies\MembershipPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MembershipTypeController extends Controller
{
    public function index(Request $request, Organisation $organisation): Response
    {
        $this->authorizeManageTypes($request->user(), $organisation);

        $types = MembershipType::where('organisation_id', $organisation->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return Inertia::render('Organisations/Membership/Types/Index', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'types'        => $types,
        ]);
    }

    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $this->authorizeManageTypes($request->user(), $organisation);

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:100'],
            'slug'              => [
                'required', 'string', 'max:100',
                Rule::unique('membership_types')->where('organisation_id', $organisation->id),
            ],
            'description'       => ['nullable', 'string'],
            'fee_amount'        => ['required', 'numeric', 'min:0'],
            'fee_currency'      => ['required', 'string', 'size:3'],
            'duration_months'   => ['nullable', 'integer', 'min:1'],
            'requires_approval' => ['boolean'],
            'is_active'         => ['boolean'],
            'sort_order'        => ['integer', 'min:0'],
        ]);

        MembershipType::create([
            'id'                => (string) Str::uuid(),
            'organisation_id'   => $organisation->id,
            'name'              => $validated['name'],
            'slug'              => $validated['slug'],
            'description'       => $validated['description'] ?? null,
            'fee_amount'        => $validated['fee_amount'],
            'fee_currency'      => $validated['fee_currency'],
            'duration_months'   => $validated['duration_months'] ?? null,
            'requires_approval' => $validated['requires_approval'] ?? true,
            'is_active'         => $validated['is_active'] ?? true,
            'sort_order'        => $validated['sort_order'] ?? 0,
            'created_by'        => $request->user()->id,
        ]);

        return redirect()->route('organisations.membership-types.index', $organisation->slug)
            ->with('success', 'Membership type created successfully.');
    }

    public function update(Request $request, Organisation $organisation, MembershipType $membershipType): RedirectResponse
    {
        $this->authorizeManageTypes($request->user(), $organisation);
        abort_if($membershipType->organisation_id !== $organisation->id, 404);

        $validated = $request->validate([
            'name'              => ['sometimes', 'string', 'max:100'],
            'slug'              => [
                'sometimes', 'string', 'max:100',
                Rule::unique('membership_types')->where('organisation_id', $organisation->id)->ignore($membershipType->id),
            ],
            'description'       => ['nullable', 'string'],
            'fee_amount'        => ['sometimes', 'numeric', 'min:0'],
            'fee_currency'      => ['sometimes', 'string', 'size:3'],
            'duration_months'   => ['nullable', 'integer', 'min:1'],
            'requires_approval' => ['boolean'],
            'is_active'         => ['boolean'],
            'sort_order'        => ['integer', 'min:0'],
        ]);

        $membershipType->update($validated);

        return back()->with('success', 'Membership type updated successfully.');
    }

    public function destroy(Request $request, Organisation $organisation, MembershipType $membershipType): RedirectResponse
    {
        $this->authorizeManageTypes($request->user(), $organisation);
        abort_if($membershipType->organisation_id !== $organisation->id, 404);

        if ($membershipType->applications()->exists() || $membershipType->fees()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete a type with existing applications or fees.']);
        }

        $membershipType->delete();

        return back()->with('success', 'Membership type deleted successfully.');
    }

    private function authorizeManageTypes($user, Organisation $organisation): void
    {
        abort_if(!(new MembershipPolicy())->manageMembershipTypes($user, $organisation), 403);
    }
}
