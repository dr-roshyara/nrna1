# INV-ORG-SCOPE-1 Stage 1: classification of every `withoutGlobalScopes()` site (appendix)

**Date:** 2026-11-02 · **Status:** RESEARCH evidence appendix (static only; authority: generated). Companion of `2026-11-02-inv-org-scope-1-stage1-static-investigation.md`.

**Method:** `grep -rn "withoutGlobalScopes(" app --include=*.php` at HEAD `0c782012c` → 379 lines; each line classified by one of four read-only reviewers against a shared rubric; completeness checked mechanically (every input line exactly once). C4 rows are **hypotheses**, not confirmed defects; rows the lead reviewer re-checked in code are marked in the main report.

**Classes:** **C1** compensated (223) · **C2** platform / cross-organisation (apparent) (73) · **C3** no organisation bypass (other scopes only) (43) · **C4** potential defect (hypothesis) (20) · **C5** unresolved / no caller / comment (20). Column *soft*: Y = soft-deleted rows can now be returned and plausibly matter; N = no; ? = unsure.

**Note:** 20 lines are comments or docblocks, not calls (marked in the evidence column), so the real call count is 359.


## C1: compensated (223)

| Site | Model | soft | Evidence |
|---|---|---|---|
| `app/Application/Election/Security/TrustPolicyEvaluator.php:97` | ElectionMembership | Y | ->where('election_id',$election->id)->where('user_id',$user->id) L98-99; soft-deleted membership could be used as eligibility evidence |
| `app/Application/Election/Services/ConstitutionalTransitionGuard.php:212` | Post | Y | $election->posts() election_id parent-key (already unscoped L509); soft-deleted posts satisfy has_posts precondition |
| `app/Application/Election/Services/ConstitutionalTransitionGuard.php:213` | Voter | Y | $election->voters() hasMany election_id parent-key; soft-deleted voters satisfy has_voters precondition |
| `app/Application/Election/Services/NominationCompletionPredicates.php:26` | Candidacy | Y | $election->candidacies() HasManyThrough posts.election_id; soft-deleted approved candidacy satisfies has_approved_candidates |
| `app/Application/Election/Services/NominationCompletionPredicates.php:32` | Candidacy | Y | $election->candidacies() parent-key; soft-deleted pending candidacy blocks nomination completion |
| `app/Console/Commands/SetupDemoElection.php:51` | Election | N | ->where('organisation_id',$organisation->id) at :55 (org resolved by command); withTrashed() explicit at :52 to avoid unique-constraint clash. |
| `app/Console/Commands/SetupPublicDemoElection.php:42` | Election | N | ->where('organisation_id',$organisation->id) at :46 (platform org from command); withTrashed() explicit at :43. |
| `app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php:154` | OrganisationUser | Y | where('organisation_id',$organisationId) :156; org from ElectionVoterController (route org, election org-checked :236). No whereNull(deleted_at): removed org user may still be eligible. |
| `app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php:180` | OrganisationUser | N | where('organisation_id',$organisationId) :182 + whereNull('deleted_at') :183 re-adds soft-delete filter. |
| `app/Contexts/Elections/Infrastructure/Repositories/EloquentVoterRepository.php:29` | ElectionMembership | N | Predicate election_id (no org) but election org-checked by caller ElectionVoterController:236 before AssignVoterCommand; withTrashed() intentional for re-import. |
| `app/Contexts/Elections/Infrastructure/Repositories/EloquentVoterRepository.php:85` | ElectionMembership | N | election_id from election org-checked at ElectionVoterController:278; whereNull('deleted_at') :88 re-adds soft filter. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentApplicationRepository.php:23` | ApplicationContextModel | Y | where('organisation_id',$tenantId) :25; callers build TenantId from route org (e.g. MembershipApplicationController:352). Soft-deleted applications can be found. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentApplicationRepository.php:41` | ApplicationContextModel | ? | where('organisation_id',$orgId) :43; soft-deleted record would be updated in place (stays deleted). |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentApplicationRepository.php:64` | ApplicationContextModel | Y | where('organisation_id') :65; soft-deleted applications appear in status lists (e.g. pending). |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:35` | CommitteeModel | N | where('organisation_id',$tenantId) :37 + whereNull('deleted_at') :38; TenantId from route org in controllers (some API paths use TenantContext/X-Tenant-Id). |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:99` | CommitteeModel | ? | where('organisation_id', aggregate tenantId) :101; soft-deleted committee would be updated rather than recreated. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:159` | CommitteeModel | N | where('organisation_id',$tenantId) :160 + whereNull('deleted_at') :161. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:186` | CommitteeModel | N | where('organisation_id') :187 + whereNull('deleted_at') :189. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:213` | CommitteeModel | N | where('organisation_id') :215 + whereNull('deleted_at') :216. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:225` | CommitteeModel | N | delete() with where('organisation_id') :227; soft delete write, no read leak. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:233` | CommitteeModel | N | where('organisation_id') :234 + whereNull('deleted_at') :235. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentFeeRepository.php:137` | FeeContextModel | ? | scopedQuery(): where('organisation_id',$tenantId) :138 for every use; soft-deleted fees become visible to all fee queries built on it. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentMemberRepository.php:26` | MemberContextModel | Y | where('organisation_id',$tenantId) :28; soft-deleted member is found and reconstituted. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentMemberRepository.php:45` | MemberContextModel | ? | where('organisation_id',$orgId) :47; soft-deleted member updated in place (stays deleted). |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentMemberRepository.php:76` | MemberContextModel | Y | where('organisation_id') :77; soft-deleted members included in status lists. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentMemberRepository.php:89` | MemberContextModel | Y | where('organisation_id') :90; soft-deleted members included in expiring list. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentMemberRepository.php:102` | MemberContextModel | ? | where('organisation_id') :103; soft-deleted member makes existsByEmail true (may block re-adding). |
| `app/Contexts/Membership/Infrastructure/Repository/EloquentCommitteeSlugReadRepository.php:14` | CommitteeModel | N | where('organisation_id',$tenantId) :15; including trashed slugs is correct for uniqueness. |
| `app/Contexts/Membership/Infrastructure/Repository/EloquentCommitteeSlugReadRepository.php:22` | CommitteeModel | N | where('organisation_id',$tenantId) :23; trashed slugs counted, appropriate for slug generation. |
| `app/Http/Controllers/Api/DemoSetupController.php:121` | Election | ? | where('organisation_id',$organisation->id) l.123; route-bound org with owner/admin role check l.31-37. Soft-deleted demo election may be picked for stats. |
| `app/Http/Controllers/Api/Governance/CommitteeMemberController.php:130` | Member | Y | where('organisation_id',$tenantIdValue) l.132; TenantContext set by IdentifyTenantFromHeader after membership check (api_v1). Soft-deleted Member can be assigned to a committee. |
| `app/Http/Controllers/CandidacyApplicationController.php:35` | Election | Y | where organisation_id=$organisation->id L36 after ensureOrganisationMember L32. Soft-deleted elections offered for candidacy. |
| `app/Http/Controllers/CandidacyApplicationController.php:40` | Post | Y | Eager-load posts relation of elections org-predicated at L36 (election_id FK). Soft-deleted posts offered. |
| `app/Http/Controllers/CandidacyApplicationController.php:55` | Election | N | where organisation_id=$organisation->id L56 after ensureOrganisationMember L32; display list only. |
| `app/Http/Controllers/CandidacyApplicationController.php:85` | Election | N | Eager-load election of CandidacyApplications filtered by user_id + organisation_id=$organisation->id L83-84; name display only. |
| `app/Http/Controllers/CandidacyApplicationController.php:135` | Post | Y | where election_id + organisation_id=$organisation->id L136-137; election org check L112-116. Soft-deleted posts shown in apply form. |
| `app/Http/Controllers/CandidacyApplicationController.php:179` | Election | Y | where id=input + organisation_id=$organisation->id L180-181; ensureOrganisationMember L168. Application could target a soft-deleted election. |
| `app/Http/Controllers/CandidacyApplicationController.php:196` | Post | Y | where id=input + election_id=$election->id L197-198 (election org-checked L181). Application could target a soft-deleted post. |
| `app/Http/Controllers/CodeController.php:434` | Code | ? | Transitive: user_id=auth + election_id of slug/middleware election after ensureVoterMembership L421. Soft-deleted code may be returned. |
| `app/Http/Controllers/CodeController.php:563` | Code | ? | Transitive: user_id=auth + election_id=$election->id L565 (middleware-resolved election). Soft-deleted code may be returned. |
| `app/Http/Controllers/CodeController.php:783` | Code | Y | Transitive: user_id + election_id L784-785; getOrCreateCode would reuse a soft-deleted code instead of creating one. |
| `app/Http/Controllers/CodeController.php:922` | Election | N | find($code->election_id) where $code is the auth user's own code; used for IP-limit check only. |
| `app/Http/Controllers/CodeController.php:1052` | Code | N | Uniqueness probe: where organisation_id=$organisationId L1054, passed from $election->organisation_id (L167/L795) or $code->organisation_id (L855). |
| `app/Http/Controllers/Committee/MemberSearchController.php:26` | Member | Y | where organisation_id=$organisation->id L27; authorize('manageCommittee') L16. Soft-deleted members returned in search. |
| `app/Http/Controllers/Demo/DemoCodeController.php:640` | DemoCode | N | where user_id=auth user l.641 + election_id of resolved election l.642. No SoftDeletes on DemoCode. |
| `app/Http/Controllers/Demo/DemoCodeController.php:1122` | DemoCode | N | Uniqueness probe with where('organisation_id',$organisationId) l.1124 (server value from code/election). |
| `app/Http/Controllers/Demo/DemoResultController.php:50` | DemoPost | N | Constant predicate whereNull('organisation_id') l.51 (MODE 1 global demo, by design). DemoPost has no SoftDeletes. |
| `app/Http/Controllers/Demo/DemoResultController.php:75` | DemoVote | ? | whereNull('organisation_id') l.76 (MODE 1 global demo). DemoVote inherits SoftDeletes from BaseVote: soft-deleted demo votes counted. |
| `app/Http/Controllers/Demo/DemoResultController.php:99` | DemoCandidacy | N | whereNull('organisation_id') l.101 + post_id from MODE-1 posts. No SoftDeletes on DemoCandidacy. |
| `app/Http/Controllers/Demo/DemoResultController.php:126` | DemoVote | ? | whereNull('organisation_id') l.133 (MODE 1 global demo). Soft-deleted demo votes would be tallied. |
| `app/Http/Controllers/Demo/DemoResultController.php:283` | DemoPost | N | whereNull('organisation_id') l.284 (MODE 1 global PDF). No SoftDeletes on DemoPost. |
| `app/Http/Controllers/Demo/DemoVoteController.php:2949` | DemoCandidacy | N | Client candidacy_id + post_id l.2950-2951; post then verified to belong to $election via l.2962-2967 (election-chain, not org column). |
| `app/Http/Controllers/Demo/DemoVoteController.php:2962` | DemoPost | N | where id=post_id AND election_id=$election->id l.2963-2964 — this IS the compensating containment check. |
| `app/Http/Controllers/Election/CandidacyManagementController.php:19` | Election | Y | Slug lookup, compensated by authorize managePosts l.21 (officer of this election); route org not compared. Soft-deleted election usable. |
| `app/Http/Controllers/Election/CandidacyManagementController.php:23` | Post | Y | where('organisation_id',$organisation->id) l.25 + election_id l.24. Soft-deleted posts listed. |
| `app/Http/Controllers/Election/CandidacyManagementController.php:28` | Candidacy (rel candidacies) | Y | Eager-load on org-filtered posts (l.23-25). Soft-deleted candidacies shown in management list. |
| `app/Http/Controllers/Election/CandidacyManagementController.php:60` | Election | Y | Slug lookup, authorize l.62, post org check l.63 (post->election_id not checked against election). Soft-deleted election usable. |
| `app/Http/Controllers/Election/CandidacyManagementController.php:103` | Election | Y | Slug lookup, authorize l.105, candidacy org check l.106. |
| `app/Http/Controllers/Election/CandidacyManagementController.php:136` | Election | Y | Slug lookup, authorize l.138, candidacy org check l.139. |
| `app/Http/Controllers/Election/CandidacyReviewController.php:28` | Post (rel post) | Y | Eager-load of post for applications already filtered by org l.25/election l.24. Soft-deleted posts shown. |
| `app/Http/Controllers/Election/CandidacyReviewController.php:93` | Candidacy | N | create() (scopes irrelevant to insert); organisation_id set from route org l.95 after checks l.68-70. |
| `app/Http/Controllers/Election/ElectionManagementController.php:281` | Election | Y | where('organisation_id',$orgId) l.282 with $orgId=session('current_organisation_id') l.277; session can be written unvalidated elsewhere (startDemo l.411, updateTimeline l.1445). |
| `app/Http/Controllers/Election/ElectionManagementController.php:519` | Election | Y | where('organisation_id',$user->organisation_id) l.521 (server-side user attribute). Soft-deleted demo elections may be listed. |
| `app/Http/Controllers/Election/ElectionManagementController.php:539` | Election | Y | Constant whereNull('organisation_id') l.541 (platform demos, by design). Soft-deleted platform demos may be listed. |
| `app/Http/Controllers/Election/ElectionManagementController.php:607` | Election | Y | Client election_id, then result-org check l.620 (org must be null or = user->organisation_id, else 403). Soft-deleted demo election can be started. |
| `app/Http/Controllers/Election/ElectionManagementController.php:676` | Election | Y | Slug lookup, authorize manageSettings l.681 before session org switch l.685. Soft-deleted election usable. |
| `app/Http/Controllers/Election/ElectionManagementController.php:758` | Election | N | Mass update where id = route-bound $election->id l.759 after authorize l.751. |
| `app/Http/Controllers/Election/ElectionManagementController.php:781` | Election | N | Mass update where id = route-bound $election->id l.782 after authorize l.775. |
| `app/Http/Controllers/Election/ElectionManagementController.php:1328` | Post | N | where election_id of authorised election l.1329 + explicit whereNull('deleted_at') l.1330. |
| `app/Http/Controllers/Election/ElectionManagementController.php:1333` | Candidacy | N | Scoped via whereHas post.election_id l.1335 + whereNull('deleted_at') l.1334. |
| `app/Http/Controllers/Election/ElectionManagementController.php:1335` | Post (rel post) | Y | whereHas constraint election_id l.1335; soft-deleted posts not excluded => candidates of deleted posts counted. |
| `app/Http/Controllers/Election/ElectionManagementController.php:1339` | Candidacy | N | whereHas post.election_id l.1342 + whereNull('deleted_at') l.1340. |
| `app/Http/Controllers/Election/ElectionManagementController.php:1342` | Post (rel post) | Y | election_id constraint l.1342; soft-deleted posts not excluded from pending count. |
| `app/Http/Controllers/Election/ElectionManagementController.php:1346` | ElectionMembership (rel memberships) | Y | Relation of authorised $election (election_id FK). ElectionMembership SoftDeletes removed => deleted voter memberships counted in votersCount. |
| `app/Http/Controllers/Election/ElectionManagementController.php:1396` | Election | Y | Slug lookup, authorize manageSettings l.1404 before rendering. Soft-deleted election usable. |
| `app/Http/Controllers/Election/ElectionManagementController.php:1421` | Election | Y | Slug lookup, authorize manageSettings l.1429. |
| `app/Http/Controllers/Election/ElectionSettingsController.php:138` | Vote/DemoVote | N | where('election_id',$election->id) l.139; election route-bound + authorize manageSettings l.30. Soft-deleted votes count as 'has votes' (conservative). |
| `app/Http/Controllers/Election/PostManagementController.php:17` | Election | Y | Slug lookup w/o org predicate, compensated by authorize managePosts (officer of THIS election) l.19; election org not compared to route {organisation}. Soft-deleted election usable. |
| `app/Http/Controllers/Election/PostManagementController.php:23` | Post | Y | where('organisation_id',$organisation->id) l.25 (route org, ensure.organisation) + election_id l.24. Soft-deleted posts listed in management UI. |
| `app/Http/Controllers/Election/PostManagementController.php:47` | Election | Y | Slug lookup, authorize managePosts l.49; then Post created with route org l.63 — election org vs route org not compared (mismatch possible). Soft-deleted election usable. |
| `app/Http/Controllers/Election/PostManagementController.php:71` | Election | Y | Slug lookup, authorize l.73, post org check l.74. Soft-deleted election usable. |
| `app/Http/Controllers/Election/PostManagementController.php:92` | Election | Y | Slug lookup, authorize l.94, post org check l.95. Soft-deleted election usable. |
| `app/Http/Controllers/Election/PostManagementController.php:97` | Candidacy (rel candidacies) | Y | Relation of route-bound $post checked org l.95; soft-deleted candidacies count => blocks deleting post with only removed candidates. |
| `app/Http/Controllers/Election/VoterImportController.php:170` | Election | Y | resolveElection by slug only; every caller authorizes manageVoters right after (l.27,48,86,97,121; officer of this election). Route org not compared. Soft-deleted election usable. |
| `app/Http/Controllers/Election/VoterIpCaptureController.php:28` | Election | Y | where('organisation_id',$organisation->id) l.30 + authorize manageSettings l.33. Soft-deleted election resolvable. |
| `app/Http/Controllers/Election/VoterIpCaptureController.php:50` | ElectionMembership | Y | where('election_id',$election->id) l.51 of authorised election. Soft-deleted membership with status active would pass the voter check. |
| `app/Http/Controllers/Election/VoterVerificationController.php:252` | Election | Y | where('organisation_id',$organisation->id) l.254 (route-bound org). Soft-deleted election resolvable. |
| `app/Http/Controllers/ElectionOfficerController.php:39` | Election | Y | where organisation_id=$organisation->id L40 (route-bound org; page canManage). Soft-deleted elections listed for officer assignment. |
| `app/Http/Controllers/ElectionVoterController.php:59` | Election | Y | Slug lookup then abort_if(organisation_id!==$organisation->id,404) L60 + authorize('view') L63. Soft-deleted election manageable. |
| `app/Http/Controllers/ElectionVoterController.php:165` | Code | N | Transitive: election_id=$election->id L166 (authorised election) + whereIn user_ids. |
| `app/Http/Controllers/ElectionVoterController.php:235` | Election | Y | abort_if org mismatch L236 + authorize('manageVoters') L239. Voters could be added to soft-deleted election. |
| `app/Http/Controllers/ElectionVoterController.php:277` | Election | Y | abort_if org mismatch L278 + authorize('manageVoters') L281. Soft-deleted election manageable. |
| `app/Http/Controllers/ElectionVoterController.php:308` | Election | Y | abort_if org mismatch L309 + authorize('manageVoters') L312. Soft-deleted election manageable. |
| `app/Http/Controllers/ElectionVoterController.php:333` | Election | Y | abort_if org mismatch L334 + authorize L337 + membership->election_id check L339. Soft-deleted election manageable. |
| `app/Http/Controllers/ElectionVoterController.php:359` | Election | Y | abort_if org mismatch L360 + authorize L363 + membership election check L365. Soft-deleted election manageable. |
| `app/Http/Controllers/ElectionVoterController.php:385` | Election | Y | abort_if org mismatch L386 + authorize('view') L389. Export works for soft-deleted election. |
| `app/Http/Controllers/ElectionVoterController.php:427` | Election | Y | abort_if org mismatch L428 + authorize L431 + membership election check L433. Soft-deleted election manageable. |
| `app/Http/Controllers/ElectionVoterController.php:468` | Election | Y | abort_if org mismatch L469 + authorize L472 + membership election check L474. Soft-deleted election manageable. |
| `app/Http/Controllers/ElectionVoterController.php:517` | Election | Y | abort_if org mismatch L518 + authorize L521 + membership election check L523. Soft-deleted election manageable. |
| `app/Http/Controllers/ElectionVotingController.php:108` | Election | Y | start(): slug lookup, then standingOf(user,election) gate L117-126 (entitlement implies election org). Soft-deleted election could be started. |
| `app/Http/Controllers/Membership/MembershipApplicationController.php:69` | OrganisationUser | Y | where org = route org l.70 + user_id = auth user l.71. Soft-deleted OrganisationUser rows can trigger false 'already member'. |
| `app/Http/Controllers/Membership/MembershipApplicationController.php:74` | Member (rel member) | Y | whereHas member with where('organisation_id',$organisation->id) l.75. Soft-deleted Member counted as active. |
| `app/Http/Controllers/Membership/MembershipApplicationController.php:85` | MembershipApplication | Y | where('organisation_id',$organisation->id) L86; $organisation route-bound (self-application). Soft-deleted pending application would block re-apply. |
| `app/Http/Controllers/Membership/OrganisationRoleController.php:31` | OrganisationUser | Y | where organisation_users.organisation_id=$organisation->id L32; authorizeAdmin L23. Soft-deleted org users (raw join on members) can flag is_member=true. |
| `app/Http/Controllers/Membership/OrganisationRoleController.php:61` | Election | Y | where organisation_id=$organisation->id L62 after authorizeAdmin L23. Soft-deleted elections appear in assign-officer dropdown. |
| `app/Http/Controllers/Membership/OrganisationRoleController.php:82` | OrganisationUser | Y | where organisation_id=$organisation->id L83 after authorizeAdmin L76. A soft-deleted OrganisationUser may be returned and a Member created against it (L102). |
| `app/Http/Controllers/Membership/OrganisationRoleController.php:88` | Member | Y | Transitive: organisation_user_id=$orgUser->id L89 (orgUser org-predicated L83). Soft-deleted Member blocks re-adding ('already a formal member'). |
| `app/Http/Controllers/Membership/OrganisationRoleController.php:96` | OrganisationUser | N | firstOrCreate keyed on organisation_id=$organisation->id L97; authorizeAdmin L76. Branch only reached when L82 (incl. trashed) found nothing. |
| `app/Http/Controllers/Membership/OrganisationRoleController.php:127` | Election | Y | where id=input + organisation_id=$organisation->id L128-129; authorizeAdmin L118. Officer could be assigned to a soft-deleted election. |
| `app/Http/Controllers/Membership/PublicMembershipApplicationController.php:56` | MembershipApplication | Y | where('organisation_id',$organisation->id) l.58 (route-bound target org of public join). Soft-deleted pending apps still block re-apply. |
| `app/Http/Controllers/Organisation/OrganisationMemberInvitationController.php:185` | OrganisationUser | Y | firstOrCreate keyed on $invitation->organisation_id L187 (invitation by secret token L114). Re-joining user may get a soft-deleted row back. Note: no check auth user email == invitation email. |
| `app/Http/Controllers/Organisation/OrganisationMemberInvitationController.php:208` | Member | Y | firstOrCreate keyed on organisation_id=$invitation->organisation_id L210 + orgUser id. A soft-deleted Member would be returned and stay deleted. |
| `app/Http/Controllers/OrganisationController.php:172` | Election | Y | where organisation_id=$organisation->id L173; isMember check L96-114. Soft-deleted elections shown on org page. |
| `app/Http/Controllers/OrganisationController.php:485` | Election | Y | where organisation_id=$organisation->id L486; role check abort_if(!$role) L473-476. Soft-deleted elections in voter hub. |
| `app/Http/Controllers/OrganisationController.php:489` | Post | Y | Eager-load posts of elections org-predicated L486 (election_id FK). Soft-deleted posts shown. |
| `app/Http/Controllers/OrganisationController.php:511` | Election | Y | where organisation_id=$organisation->id L512; role check L476. Soft-deleted published elections listed. |
| `app/Http/Controllers/OrganisationController.php:583` | Election | Y | where organisation_id=$organisation->id L584; admin/officer check L574,581. Soft-deleted elections counted in stats. |
| `app/Http/Controllers/OrganisationController.php:625` | Election | Y | slug + organisation_id=$organisation->id L626-627; canAccessElection L631-635. Soft-deleted election viewable. |
| `app/Http/Controllers/OrganisationController.php:637` | Post | Y | election_id + organisation_id=$organisation->id L638-639. Soft-deleted posts listed. |
| `app/Http/Controllers/OrganisationController.php:663` | Election | Y | slug + organisation_id=$organisation->id L664-665; canAccessElection L669-673. Soft-deleted election viewable. |
| `app/Http/Controllers/OrganisationController.php:675` | Post | Y | election_id + organisation_id=$organisation->id L676-677. Soft-deleted posts listed. |
| `app/Http/Controllers/OrganisationController.php:680` | Candidacy | Y | Eager-load candidacies relation of org-predicated posts (post_id FK) L679. Soft-deleted approved candidacies shown to voters. |
| `app/Http/Controllers/OrganisationController.php:730` | Election | Y | slug + organisation_id=$organisation->id L731-732; canAccessElection L736-740. Soft-deleted election viewable. |
| `app/Http/Controllers/OrganisationController.php:750` | Code | Y | Transitive: election_id=$electionModel->id L751 (org-checked election). Soft-deleted codes with has_voted count as voted. |
| `app/Http/Controllers/OrganisationController.php:762` | ElectionMembership | Y | $electionModel->memberships() relation (election_id FK) on org-checked election L761. Soft-deleted memberships inflate voter stats. |
| `app/Http/Controllers/OrganisationController.php:775` | ElectionMembership | Y | $electionModel->memberships() relation L774 on org-checked election. Soft-deleted (removed) memberships listed as voters. |
| `app/Http/Controllers/OrganisationSettingsController.php:126` | Election | Y | where organisation_id=$organisation->id L127. A soft-deleted participation-locked election would block the membership-mode change. |
| `app/Http/Controllers/ResultController.php:27` | Vote | Y | Transitive: election_id=$election->id L28 (election from L19, itself unchecked for org). Vote count includes soft-deleted votes. |
| `app/Http/Controllers/ResultController.php:40` | Post | Y | Transitive: election_id=$election->id L41 (election from L19). Soft-deleted posts appear in results. |
| `app/Http/Controllers/ResultController.php:46` | Candidacy | Y | Transitive: post_id=$post->id L47 (posts of L19 election). Soft-deleted candidacies appear in results. |
| `app/Http/Controllers/ResultController.php:238` | Vote | Y | Transitive: election_id=$election->id L239 (election from L228). Counts soft-deleted votes. |
| `app/Http/Controllers/ResultController.php:251` | Post | Y | Transitive: election_id=$election->id L252 (election from L228). Soft-deleted posts in PDF. |
| `app/Http/Controllers/ResultController.php:257` | Candidacy | Y | Transitive: post_id=$post->id L258. Soft-deleted candidacies in PDF. |
| `app/Http/Controllers/VoteController.php:109` | Election | N | NOT A CALL: comment line for the call at L111 (scanner false positive). |
| `app/Http/Controllers/VoteController.php:111` | Election | Y | find($voterSlug->election_id) (server slug record); org mismatch check slug vs election L128-143 throws. Soft-deleted election usable. |
| `app/Http/Controllers/VoteController.php:180` | Code | ? | Transitive: user_id=auth + election_id=$election->id L182 (election from getElection). Soft-deleted verified code could grant eligibility. |
| `app/Http/Controllers/VoteController.php:455` | Post | Y | Transitive: election_id=$election->id L455 (election from getElection, slug-bound). Soft-deleted regional posts affect has_regional_posts flag. |
| `app/Http/Controllers/VoteController.php:509` | Code | ? | Transitive: user_id=auth + election_id=$election->id L510. Soft-deleted code may be returned. |
| `app/Http/Controllers/VoteController.php:739` | Code | ? | Transitive: user_id=auth + election_id=$election->id L740 (getElection L728). Soft-deleted code may be returned. |
| `app/Http/Controllers/VoteController.php:1626` | Code | ? | Transitive: user_id=auth + election_id=$election->id L1627 in vote store. Soft-deleted code could authorise a cast. |
| `app/Http/Controllers/VoteController.php:2453` | Code | ? | Transitive: user_id=auth + election_id=$election->id; ensureVoterMembership L2446. Soft-deleted code may be returned. |
| `app/Http/Controllers/VoteController.php:2805` | Code | ? | user_id=auth + election_id=session('current_election_id') (set server-side L297 after code check). Soft-deleted code may be returned. |
| `app/Http/Controllers/VoteController.php:3046` | Code | ? | user_id=auth + election_id=session('current_election_id') (server-set L297). Soft-deleted code may be returned. |
| `app/Http/Controllers/VoteController.php:3242` | Election | N | find($code->election_id) where $code is the auth user's own code; used for IP-limit check only. |
| `app/Http/Controllers/VoteController.php:3797` | Candidacy | Y | candidacy_id taken from the server-stored vote record being displayed (not client input); no org predicate. Soft-deleted candidacies still resolved (needed for history). |
| `app/Http/Controllers/VoteController.php:3895` | Election | N | find($vote->election_id) of the already-retrieved vote; display only. |
| `app/Http/Controllers/VoterSlugController.php:298` | Election | Y | Session id; real elections then gated by VoterSlugIssuer entitlement->decide(voter,election) (VoterSlugIssuer.php:123). Soft-deleted election could be issued a link. |
| `app/Http/Middleware/ElectionMiddleware.php:56` | Election | Y | find(voterSlug->election_id) then orgsMatch/platform check :68-82 abort(500) on mismatch. No trashed() check. |
| `app/Http/Middleware/VerifyVoterSlug.php:92` | Election | N | After find, explicit trashed() reject (:93) and slug.org===election.org check (:96-98); slug ownership verified at V-3 (user_id===auth id). |
| `app/Http/Middleware/VerifyVoterSlugConsistency.php:43` | Election | Y | find(voterSlug->election_id) then org check election.org===slug.org (or platform id 1) at :58-64, else OrganisationMismatchException. No trashed() check here. |
| `app/Models/BaseResult.php:107` | Vote | Y | creating hook: Vote::find(result->vote_id) then L125 throws unless vote->organisation_id === result->organisation_id; soft-deleted vote passes existence check |
| `app/Models/BaseResult.php:184` | Post | N | belongsTo Post via result.post_id FK of already-scoped result; soft-deleted post resolvable for historical results (benign) |
| `app/Models/BaseResult.php:196` | Candidacy | N | belongsTo Candidacy via result.candidacy_id FK of already-scoped result; deleted candidacy resolvable for results (benign) |
| `app/Models/BaseResult.php:218` | Election | N | belongsTo Election via result.election_id FK of already-scoped result |
| `app/Models/BaseResult.php:230` | Result | Y | scopeForOrganisation: ->where('organisation_id',$organisationId) L231; caller-supplied; no Result caller found in app/; soft-deleted results included |
| `app/Models/BaseResult.php:243` | Result | Y | scopeForElection: ->where('election_id',$election->id) L244 parent-key; Election object caller-supplied; no caller found in app/; soft-deleted results included |
| `app/Models/BaseVote.php:166` | Election | Y | creating hook: Election::find(vote->election_id) then L183 throws if real vote org !== election org; soft-deleted election passes existence check |
| `app/Models/BaseVote.php:348` | Election | N | belongsTo Election via vote.election_id FK of already-scoped vote |
| `app/Models/BaseVote.php:415` | Vote | Y | scopeForElection ->where('election_id',$election->id) L416 parent-key; no caller found in app/; soft-deleted votes included in counts |
| `app/Models/BaseVote.php:440` | Vote | Y | scopeForOrganisation ->where('organisation_id',$organisationId) L441; caller-supplied; no Vote caller found in app/; soft-deleted votes included |
| `app/Models/Calendar.php:35` | Event | N | hasMany Event constrained by events.calendar_id = this->id (tenant-scoped Calendar parent); Event has no SoftDeletes |
| `app/Models/Candidacy.php:116` | Post | ? | belongsTo Post via candidacy.post_id FK of scoped candidacy; soft-deleted post still resolvable |
| `app/Models/CandidacyApplication.php:34` | Post | ? | belongsTo Post via application.post_id FK; CandidacyApplication has no tenant scope so parent authorisation depends on caller |
| `app/Models/CandidacyApplication.php:36` | Candidacy | ? | belongsTo Candidacy via application.candidacy_id FK; CandidacyApplication itself unscoped, authorisation depends on caller |
| `app/Models/Code.php:115` | Election | ? | belongsTo Election via code.election_id FK of scoped code; soft-deleted election resolvable |
| `app/Models/Code.php:126` | Voter | ? | belongsTo Voter (tenant-scoped) via code.voter_id FK; soft-deleted voter resolvable |
| `app/Models/Code.php:138` | Code | Y | scopeForOrganisation ->where('organisation_id',$organisationId) L139; caller-supplied; no Code caller in app/; soft-deleted codes included |
| `app/Models/Code.php:152` | Code | Y | scopeForElection ->where('election_id',$electionId) L153; electionId may be a raw string from caller; no caller in app/; soft-deleted codes included |
| `app/Models/Code.php:166` | Election | N | whereHas('election') subquery correlated on codes.election_id; outer Code tenant scope stays; only election scope lifted for type='demo' |
| `app/Models/DemoPost.php:57` | DemoCandidacy | N | hasMany DemoCandidacy on post_id = this->id (scoped DemoPost parent); DemoCandidacy has no SoftDeletes |
| `app/Models/Election.php:400` | Candidacy | Y | whereHas post election_id = $this->id L401 (parent-key); soft-deleted candidacies counted in candidates_count |
| `app/Models/Election.php:401` | Post | Y | subquery ->where('election_id',$this->id) L401; soft-deleted posts match |
| `app/Models/Election.php:417` | Candidacy | Y | whereHas post election_id = $this->id L418; soft-deleted pending candidacies counted |
| `app/Models/Election.php:418` | Post | Y | subquery ->where('election_id',$this->id) L418; soft-deleted posts match |
| `app/Models/Election.php:436` | ElectionMembership | Y | memberships() hasMany election_id = this->id; voter_stats (ElectionVoterController L108) counts soft-deleted memberships |
| `app/Models/Election.php:440` | ElectionMembership | Y | membershipVoters() election_id = this->id + role/status; soft-deleted memberships counted as active_voters |
| `app/Models/Election.php:441` | ElectionMembership | Y | eligibleVoters() election_id = this->id; soft-deleted memberships counted as eligible_voters |
| `app/Models/Election.php:509` | Post | Y | posts() hasMany election_id = this->id; soft-deleted posts returned to every posts() consumer (ballot, preconditions) |
| `app/Models/Election.php:526` | Candidacy | Y | hasManyThrough via posts.election_id = this->id; Candidacy soft-delete filter removed (Post deleted_at still applied by HasManyThrough) |
| `app/Models/Election.php:566` | VoterSlug | Y | voterSlugs() election_id = this->id; soft-deleted slugs returned |
| `app/Models/Election.php:586` | DemoPost | N | demoPosts() election_id = this->id; DemoPost has no SoftDeletes |
| `app/Models/ElectionAuditLog.php:34` | Election | N | belongsTo Election via log.election_id FK; audit log model unscoped; resolving deleted election is desirable for audit |
| `app/Models/ElectionMembership.php:89` | Election | N | belongsTo Election via membership.election_id FK of scoped membership |
| `app/Models/ElectionOfficer.php:52` | Election | ? | belongsTo Election via officer.election_id FK; ElectionOfficer itself has no tenant scope (controllers check officer.organisation_id e.g. ElectionOfficerController L167) |
| `app/Models/ElectionStateTransition.php:50` | Election | N | belongsTo Election via transition.election_id FK; unscoped model; deleted election resolvable for history (benign) |
| `app/Models/Event.php:33` | Calendar | N | belongsTo Calendar via event.calendar_id FK of tenant-scoped Event; Calendar has no SoftDeletes |
| `app/Models/Organisation.php:121` | Post | Y | hasMany Post on organisation_id = this->id (explicit org FK); soft-deleted posts included |
| `app/Models/Post.php:42` | Post | Y | scopeForOrganisation ->where('organisation_id',$organisationId) L43; caller-supplied; no Post caller in app/ |
| `app/Models/Post.php:51` | Post | Y | scopeForElection ->where('election_id',$electionId) L52; raw string from caller; no caller in app/; soft-deleted posts included |
| `app/Models/Post.php:70` | Election | N | belongsTo Election via post.election_id FK of scoped post |
| `app/Models/Post.php:81` | Candidacy | Y | hasMany Candidacy on post_id = this->id; soft-deleted candidacies included in ballot/candidate lists |
| `app/Models/Post.php:92` | Candidacy | Y | approvedCandidacies on post_id = this->id + status=approved; soft-deleted approved candidacies included (ballot) |
| `app/Models/User.php:1305` | Election | Y | ->whereIn('organisation_id',$orgIds) L1306; $orgIds from $this->organisations() type=tenant L1288; soft-deleted (ongoing-window) elections can be returned to voter |
| `app/Models/User.php:1357` | Election | Y | ->whereIn('organisation_id',$orgIds) L1358; $orgIds from $this->organisations() L1345; soft-deleted elections counted |
| `app/Models/Vote.php:243` | Result | Y | verifyResultsIntegrity: ->where('vote_id',$this->id) L243 parent-key; soft-deleted results counted in integrity check |
| `app/Models/VoterSlug.php:66` | Election | N | belongsTo Election via slug.election_id FK of scoped slug |
| `app/Models/VoterSlug.php:79` | Voter | ? | belongsTo Voter via slug.voter_id FK; soft-deleted voter resolvable |
| `app/Models/VoterSlug.php:89` | VoterSlug | Y | scopeForOrganisation ->where('organisation_id',$organisationId) L89; caller-supplied; soft-deleted slugs included |
| `app/Models/VoterSlug.php:95` | VoterSlug | Y | scopeForElection ->where('election_id',$electionId) L95; caller-supplied id; soft-deleted slugs (e.g. voted status) included |
| `app/Services/BallotAssemblyService.php:23` | Post | Y | election_id=$election->id (:28); callers org-check election (BallotPreviewController canAccessElection; vote flow via VerifyVoterSlug). Post has SoftDeletes: deleted posts appear on ballot. |
| `app/Services/BallotAssemblyService.php:26` | Candidacy (eager candidacies) | Y | Constrained by parent post ids; status approved. Candidacy has SoftDeletes: deleted approved candidacies appear on ballot/authoritative ballot. |
| `app/Services/BallotAssemblyService.php:68` | Post | Y | election_id + state_name (:73-75) of org-checked election; soft-deleted regional posts appear on ballot. |
| `app/Services/BallotAssemblyService.php:71` | Candidacy (eager candidacies) | Y | Constrained by parent posts; deleted approved candidacies appear on regional ballot. |
| `app/Services/BallotAssemblyService.php:119` | Post | Y | election_id predicate (:120); deleted regional posts make has_regional_posts true. |
| `app/Services/ContributionPointsService.php:60` | PointsLedger | N | where('organisation_id',$organisationId) :62; callers pass route-bound $organisation under ensure.organisation (routes/organisations.php:92) or contribution->organisation_id. No SoftDeletes. |
| `app/Services/DemoElectionResolver.php:39` | Election | Y | Base query; org predicate users.organisation_id of auth user (:44) or whereNull(organisation_id) platform demo (:96). Trashed demo elections may be chosen. |
| `app/Services/DemoElectionResolver.php:134` | Election | Y | where('organisation_id',$platformOrg->id) :136 (server-derived platform org); trashed named demo may be returned. |
| `app/Services/DemoElectionResolver.php:145` | Election | Y | where('organisation_id',$platformOrg->id) :147; trashed demo may be returned. |
| `app/Services/DemoElectionResolver.php:230` | DemoPost | N | create() with explicit organisation_id = $election->organisation_id (:232); no read. |
| `app/Services/DemoElectionResolver.php:239` | DemoCandidacy | N | create() with explicit organisation_id = $election->organisation_id (:242); no read. |
| `app/Services/NewsletterService.php:139` | Member | Y | members.organisation_id=$organisation->id :142 (route org under ensure.organisation). Soft-deleted active members become newsletter recipients. |
| `app/Services/NewsletterService.php:151` | Member | Y | members.organisation_id :154; soft-deleted members included as recipients. |
| `app/Services/NewsletterService.php:164` | Member | Y | members.organisation_id :167; soft-deleted members included. |
| `app/Services/NewsletterService.php:177` | Member | Y | members.organisation_id :180; soft-deleted members included. |
| `app/Services/NewsletterService.php:192` | ElectionMembership | Y | users.organisation_id=$organisation->id :202 (election_id from request meta not tied to org, but recipients limited to org users); soft-deleted memberships included. |
| `app/Services/NewsletterService.php:212` | ElectionMembership | Y | users.organisation_id :224; soft-deleted memberships included. |
| `app/Services/NewsletterService.php:234` | ElectionMembership | Y | users.organisation_id :245; soft-deleted memberships included. |
| `app/Services/NewsletterService.php:268` | ElectionMembership | Y | users.organisation_id :277; soft-deleted candidate memberships included. |
| `app/Services/NewsletterService.php:287` | ElectionMembership | Y | users.organisation_id :296; soft-deleted observer memberships included. |
| `app/Services/NewsletterService.php:325` | ElectionMembership | Y | users.organisation_id :335; soft-deleted memberships included. |
| `app/Traits/BelongsToTenant.php:106` | any tenant model | Y | scopeForOrganisation ->where('organisation_id',$organisationId) L106; only caller CommitteeHierarchyRepository L21/L73 with TenantId arg; soft-deleted rows included |
| `app/Traits/BelongsToTenant.php:116` | any tenant model | Y | scopeForDefaultPlatform ->where('organisation_id',0) constant; UUID ids make it match nothing; no caller in app/ |
| `app/Traits/ChecksElectionAccess.php:24` | ElectionMembership | Y | ->where('election_id',$electionId)->where('user_id',$userId=auth id) L25-26; note fn never checks election belongs to $organisation; soft-deleted active membership grants access |

## C2: platform / cross-organisation (apparent) (73)

| Site | Model | soft | Evidence |
|---|---|---|---|
| `app/Console/Commands/ActivateElectionCommand.php:17` | Election | ? | Operator CLI lookup by slug across orgs; no authority check needed (CLI). Soft-deleted election can be found and activated; slug uniqueness across orgs not visible. |
| `app/Console/Commands/AuditCleanup.php:98` | Election | Y | System-wide retention job (:91-96). Comment says trashed election "not found", but withoutGlobalScopes() drops SoftDeletes so trashed elections are judged by isOpenFor (:104). |
| `app/Console/Commands/BackfillElectionState.php:36` | Election | N | One-off backfill over all elections (console); includes soft-deleted rows, acceptable for a state backfill. |
| `app/Console/Commands/BackfillMemberDirectories.php:28` | MemberContextModel | ? | Backfill over all orgs' members (console, whereNotNull organisation_user_id). Includes soft-deleted members in backfill. |
| `app/Console/Commands/BackfillVoterSourceStrategy.php:29` | Election | N | Console backfill chunkById over all orgs whereNull(voter_source_strategy); includes trashed rows, harmless for backfill. |
| `app/Console/Commands/CleanupExpiredVoterSlugs.php:88` | VoterSlug | N | Console count of onlyTrashed() (:86) expired real slugs across orgs for verbose output; trashed rows intended. |
| `app/Console/Commands/CleanupExpiredVoterSlugs.php:107` | DemoVoterSlug | N | Console cleanup across all orgs (expires_at < cutoff); DemoVoterSlug has no SoftDeletes. |
| `app/Console/Commands/CleanupExpiredVoterSlugs.php:116` | DemoVoterSlug | N | Console hard delete of expired demo slugs across all orgs; no SoftDeletes on model. |
| `app/Console/Commands/ExpireMemberships.php:15` | Member | N | Scheduled bulk update over all orgs (status/expiry predicates only); console, no request path. Also updates soft-deleted rows (harmless status flip). |
| `app/Console/Commands/ExportVotingCodesCommand.php:38` | Election | ? | Operator CLI lookup by slug across orgs; soft-deleted election can be resolved; slug uniqueness across orgs not visible. |
| `app/Console/Commands/ExportVotingCodesCommand.php:51` | Code | ? | CLI export scoped by election_id (:53); org filter not needed for operator command; soft-deleted codes included in export. |
| `app/Console/Commands/ExportVotingCodesCommand.php:116` | Code | ? | CLI single-code lookup by election_id + user email (:118-119); soft-deleted code may be returned. |
| `app/Console/Commands/GenerateRecoverySlug.php:55` | Election | ? | Operator CLI find($electionId); no trashed check visible before generating recovery slug. |
| `app/Console/Commands/ProcessElectionAutoTransitions.php:17` | Election | Y | Scheduled job over all orgs' elections; soft-deleted elections are also processed and may be auto-transitioned. |
| `app/Console/Commands/ProcessElectionAutoTransitions.php:65` | Candidacy (via $election->candidacies()) | Y | Relation keeps election constraint; scope removal drops SoftDeletes, so approved count includes deleted candidacies (console job). |
| `app/Console/Commands/ProcessElectionAutoTransitions.php:66` | Candidacy (via $election->candidacies()) | Y | Same as :65 for pending count; deleted candidacies counted. |
| `app/Console/Commands/ProcessElectionAutoTransitions.php:144` | Post (via $election->posts()) | Y | Relation keeps election_id; soft-deleted posts count toward hasPost gate for admin->nomination transition. |
| `app/Console/Commands/ProcessElectionAutoTransitions.php:146` | ElectionMembership (via $election->memberships()) | Y | Relation keeps election_id; soft-deleted voter memberships satisfy hasVoter gate (role/status predicates only). |
| `app/Console/Commands/ProcessElectionAutoTransitions.php:157` | Post (via $election->posts()) join candidacies | Y | Relation keeps election_id; deleted posts included and joined candidacies have no deleted_at filter -> deleted pending candidacies counted. |
| `app/Contexts/Elections/Infrastructure/Queries/AssembledAuthoritativeBallotQuery.php:26` | Election | N | findOrFail of $election->id already resolved/validated by VoteController (:1692 passes $election->id); re-hydration, no new org exposure. |
| `app/Contexts/Elections/Infrastructure/Security/TrustLegitimacySecurityVerdict.php:57` | Election | N | findOrFail(context->electionId) where id comes from controller-resolved $election (CodeController:720, VoteController:1492); returns verdict only. |
| `app/Contexts/Governance/Infrastructure/Projections/CommitteeGovernanceProjector.php:39` | CommitteeModel | ? | Projection rebuild by committee id (rebuilder/event); writes projection, no user-facing return. Soft-deleted committees get projected. |
| `app/Contexts/Governance/Infrastructure/Projections/CommitteeGovernanceProjector.php:79` | CommitteeModel | ? | rebuildAll over all orgs' committees (platform projection rebuild); includes soft-deleted committees. |
| `app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeProjectionRebuildRepository.php:15` | CommitteeModel | ? | Optional tenant predicate when($tenantId) :16; tenantId from console GovernanceRebuildProjections option, null = all orgs by design. Trashed included. |
| `app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeProjectionRebuildRepository.php:24` | CommitteeModel | ? | countCommittees, same optional tenant predicate (:25); console caller (GovernanceRebuildProjections:39). |
| `app/Http/Controllers/Admin/AdminElectionController.php:34` | Election | Y | Platform approval by client id; guard requires platform_admin (ElectionConstitution:34, Guard:65-73). /admin mount (electionRoutes:684) lacks platform_admin mw; deleted election approvable. |
| `app/Http/Controllers/Admin/AdminElectionController.php:57` | Election | Y | Platform rejection by client id; guard requires platform_admin (ElectionConstitution:39-41, Guard:65). /admin mount (electionRoutes:687) lacks platform_admin mw; soft-deleted election actionable. |
| `app/Http/Controllers/Admin/AdminElectionController.php:77` | Election | Y | all() platform listing; only route platform.php:18 behind platform_admin. Soft-deleted elections appear in the list. |
| `app/Http/Controllers/Admin/PlatformDashboardController.php:17` | Election | N | Platform-wide pending count; route only in routes/platform.php under auth+verified+platform_admin (platform.php:7). Count includes soft-deleted elections (cosmetic). |
| `app/Http/Controllers/Admin/PlatformDashboardController.php:24` | Election | N | Platform-wide total count; platform_admin middleware (platform.php:7). Soft-deleted elections counted (cosmetic only). |
| `app/Http/Controllers/Demo/DemoVoteController.php:211` | Election | Y | Election by voter_slug->election_id (slug resolved by middleware into request attributes); no org check visible here. Soft-deleted election returned. |
| `app/Http/Controllers/Demo/DemoVoteController.php:377` | DemoPost | N | Demo ballot; where('election_id',$election->id) l.378 of election resolved upstream (isDemo() l.374). |
| `app/Http/Controllers/Demo/DemoVoteController.php:381` | DemoCandidacy (rel candidacies) | N | Eager-load on candidacies of election-scoped demo posts (l.377-379). |
| `app/Http/Controllers/Demo/DemoVoteController.php:431` | DemoPost | N | Demo regional posts; election_id l.432 + user region l.434. |
| `app/Http/Controllers/Demo/DemoVoteController.php:436` | DemoCandidacy (rel candidacies) | N | Eager-load on candidacies of election-scoped demo posts (l.431-434). |
| `app/Http/Controllers/Demo/DemoVoteController.php:3029` | DemoVote | N | Re-read of the vote just saved in this request by server id ($vote->id) for log only; no client input. |
| `app/Http/Controllers/Demo/DemoVoteController.php:4842` | DemoVote | Y | Lookup by client vote id, then HMAC check hash_equals(sha256(privateKey.id.app.key)) l.4913; no org check. Soft-deleted vote verifiable. Note: l.4879-4881 log app.key. |
| `app/Http/Controllers/Demo/DemoVoteController.php:4848` | DemoVote | N | Global count across all orgs written to log only (l.4848); not returned to client. |
| `app/Http/Controllers/Demo/PublicDemoController.php:179` | Election | Y | Anonymous public demo; election_id from server-stored PublicDemoSession (route-bound by session_token). Soft-deleted election still found, defeating 'Election deleted' branch l.181-185. |
| `app/Http/Controllers/Demo/PublicDemoController.php:462` | DemoPost | N | Public demo results; where('election_id',$election->id) l.463, election from server resolver getPublicDemoElection() l.447. No SoftDeletes on DemoPost. |
| `app/Http/Controllers/Demo/PublicDemoController.php:495` | DemoVote | ? | Public demo; election_id of server-resolved public demo election l.496. Soft-deleted demo votes counted. |
| `app/Http/Controllers/Demo/PublicDemoController.php:505` | DemoVote | ? | Public demo; election_id of server-resolved election l.506. Soft-deleted demo votes tallied. |
| `app/Http/Controllers/Demo/PublicDemoController.php:520` | DemoCandidacy | N | Public demo; post_id + election_id of server-resolved election l.521-522. |
| `app/Http/Controllers/Demo/PublicDemoController.php:594` | DemoPost | N | Existence check by election_id l.594 of session/resolver election (public demo). |
| `app/Http/Controllers/Demo/PublicDemoController.php:595` | DemoCandidacy | N | Existence check by election_id l.595 (public demo). |
| `app/Http/Controllers/Demo/PublicDemoController.php:620` | DemoPost | N | Public demo; where('election_id',$election->id) l.621, election from PublicDemoSession/resolver. |
| `app/Http/Controllers/Demo/PublicDemoController.php:624` | DemoCandidacy (rel candidacies) | N | Eager-load constraint on candidacies of posts already restricted to the public demo election (l.620-622). |
| `app/Http/Controllers/Demo/PublicDemoController.php:639` | DemoCandidacy | N | Fallback by post_id+election_id l.640-641; post from election-scoped query. |
| `app/Http/Controllers/Demo/PublicDemoController.php:646` | DemoCandidacy | N | Fallback by post_id only l.647; post_id comes from election-scoped post (server), not client. |
| `app/Http/Controllers/ElectionController.php:213` | Election | N | NOT A CALL: comment line describing the call at L216 (scanner false positive). |
| `app/Http/Controllers/ElectionController.php:216` | Election | Y | startDemo: first votable demo election across ALL orgs, no org predicate (per-org demo elections exist). Method appears unrouted (only ElectionManagementController::startDemo is). |
| `app/Http/Controllers/NewsletterUnsubscribeController.php:12` | Member | N | Public route /unsubscribe/{token}; lookup by newsletter_unsubscribe_token L13 (secret token); only sets newsletter_unsubscribed_at; no org context exists or needed. |
| `app/Http/Controllers/VoteController.php:2290` | Vote | ? | Client vote_id, but returned only if receipt_hash present and hash_equals(sha256(private_key.id.app_key)) L2292-2294: proof-of-receipt token. Soft-deleted votes verifiable. |
| `app/Http/Controllers/VoterInvitationController.php:22` | Election | N | findOrFail($invitation->election_id); invitation looked up by secret token, unused, unexpired L15-19. Only election name shown. |
| `app/Http/Controllers/VoterInvitationController.php:65` | Election | N | findOrFail($invitation->election_id) after token-validated invitation L36-40; used for redirect slug only. |
| `app/Http/Controllers/VoterSlugController.php:121` | Election | N | Demo/non-real path only (real goes to issuer L42). Id from server session set after tenant-scoped findOrFail (ElectionController:99) or org check (ElectionManagementController:619). |
| `app/Http/Controllers/VoterSlugController.php:237` | Election | N | Demo/non-real restart path (real handled L219). Session id set by tenant-scoped/org-checked selection; no org re-check here. |
| `app/Http/Middleware/CheckVotingWindow.php:52` | Election | N | find(voter_slug->election_id) from VerifyVoterSlug-validated slug; only proceeds for demo; trashed already rejected upstream. |
| `app/Http/Middleware/CheckVotingWindow.php:58` | DemoCode | N | Predicates user_id=auth()->id() + election_id (:59-60): ownership-scoped, demo cross-org by design. DemoCode has no SoftDeletes. |
| `app/Http/Middleware/EnsureElectionVoter.php:90` | Election | Y | Resolves route election before scoping; real: isVoterInElection() authority check (:37); demo bypasses (:31) by design. No trashed() check, so a soft-deleted election resolves. |
| `app/Http/Middleware/EnsureVoterStepOrder.php:42` | Election [comment line] | N | Comment for the bypass at :44; see :44. |
| `app/Http/Middleware/EnsureVoterStepOrder.php:44` | Election | N | find($vslug->election_id) where vslug is the VerifyVoterSlug-validated slug (owner, trashed and org-match checks done there); no own org predicate. |
| `app/Http/Middleware/VerifyVoterSlug.php:145` | VoterSlug/DemoVoterSlug | N | Resolves slug before org context is known; ownership check user_id===auth()->id() (V-3) and trashed reject (V-5a) follow in handle(). |
| `app/Models/User.php:256` | Candidacy | ? | user's own candidacies across all orgs (user_id FK); by-design cross-org per user; no caller found in app/; deleted candidacies included |
| `app/Models/User.php:301` | Election | Y | belongsToMany via election_memberships pivot user_id = this->id; cross-org by design; no caller found in app/; soft-deleted elections included |
| `app/Services/DemoElectionResolver.php:177` | Election | Y | Public demo fallback whereNull('organisation_id') :179 - platform-wide demo by design; may return trashed. |
| `app/Services/DemoElectionResolver.php:192` | DemoPost | N | Count by election_id of already-resolved demo election (:193); no SoftDeletes on DemoPost. |
| `app/Services/DemoElectionResolver.php:195` | DemoCandidacy | N | Count by election_id of resolved demo election (:196); no SoftDeletes. |
| `app/Services/DemoElectionResolver.php:214` | DemoPost | N | Count by election_id of resolved election (:215); no SoftDeletes. |
| `app/Services/VoterSlugService.php:302` | DemoVoterSlug/VoterSlug ($model) | N | Demo-only path (real refused :258); predicates user_id=$user->id + election_id (:303-304) - own slugs; forceDelete intentionally includes trashed. |
| `app/Services/VoterSlugService.php:333` | DemoVoterSlug/VoterSlug ($model) | N | Demo-only path; user_id + election_id + active predicates (:334-338) - user's own slug. |
| `app/Services/Voting/IpVoteLimit.php:72` | Code | Y | Deliberate cross-tenant-context count by election_id of caller-resolved $election (rationale TrustLegitimacySecurityVerdict:66-68); counts only. Soft-deleted voted codes counted. |
| `app/Traits/BelongsToTenant.php:96` | any tenant model | Y | scopeIgnoreTenant: documented admin bypass, no predicate, no authority check; latent: no caller found in app/ or routes/ |

## C3: no organisation bypass (other scopes only) (43)

| Site | Model | soft | Evidence |
|---|---|---|---|
| `app/Application/Election/Services/NominationCompletionPredicates.php:17` | n/a | N | COMMENT LINE (docblock); no executable bypass |
| `app/Console/Commands/BackfillMemberDirectories.php:66` | MembershipType | N | MembershipType has no BelongsToTenant; find($typeId) only drops SoftDeletes to resolve a name for backfill (console). |
| `app/Console/Commands/ExportVotingCodesCommand.php:18` | Code::user() relation (User) [docblock] | N | Docblock comment, not an executable call; refers to Code::user() (Code.php:103) which bypasses scopes on User (no org filter). |
| `app/Contexts/Elections/Infrastructure/Queries/ElectionOfficerVerificationAuthoritySource.php:25` | ElectionOfficer | Y | ElectionOfficer has no org filter; user_id+election_id+status=active. SoftDeletes dropped: a soft-deleted officer still 'active' would retain chief/deputy verification authority. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:121` | CommitteeAssignmentModel | N | CommitteeAssignmentModel has neither BelongsToTenant nor SoftDeletes; by committee_id of org-scoped aggregate. |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:134` | CommitteeAssignmentModel | N | No org filter/SoftDeletes on model; update by assignment id within aggregate's existing ids (:133). |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:143` | CommitteeAssignmentModel | N | No org filter/SoftDeletes on model; update by committee_id of the aggregate. |
| `app/Http/Controllers/Election/CandidacyManagementController.php:29` | User (rel user) | ? | User has no org filter; only SoftDeletes removed => deleted users' names shown. |
| `app/Http/Controllers/Election/CandidacyReviewController.php:23` | CandidacyApplication | N | Model has no BelongsToTenant/SoftDeletes; also explicit org predicate l.25 (route org) + authorize managePosts l.21. |
| `app/Http/Controllers/Election/CandidacyReviewController.php:27` | User (rel user) | ? | User has no org filter; removes SoftDeletes => applications of soft-deleted users show their name/email. |
| `app/Http/Controllers/Election/ElectionManagementController.php:372` | Organisation | Y | Organisation has no org filter; bypass only removes SoftDeletes => soft-deleted org resolvable by slug. No membership check follows (session org set l.411). |
| `app/Http/Controllers/Election/ElectionManagementController.php:1352` | ElectionOfficer (rel officers) | Y | ElectionOfficer has no org filter (HasUuids,SoftDeletes only); relation of $election. Soft-deleted officers counted in committeeCount. |
| `app/Http/Controllers/Membership/MembershipApplicationController.php:266` | UserOrganisationRole | N | UserOrganisationRole has no tenant scope and no SoftDeletes; call is a no-op. firstOrCreate keyed on organisation_id=$organisation->id L268. |
| `app/Http/Controllers/Organisation/OrganisationMemberInvitationController.php:199` | UserOrganisationRole | N | No tenant scope/SoftDeletes on UserOrganisationRole; no-op. Keyed on $invitation->organisation_id L201. |
| `app/Http/Controllers/OrganisationController.php:682` | User | N | User has no tenant scope (SoftDeletes only); names of soft-deleted candidate users shown, minor. |
| `app/Http/Controllers/OrganisationController.php:776` | User | N | User has no tenant scope (SoftDeletes only); eager-load of voter users; deleted users' names shown, minor. |
| `app/Http/Controllers/ResultController.php:79` | Organisation | N | Organisation has no tenant scope (only SoftDeletes); find($election->organisation_id) for logo/name. |
| `app/Models/BaseResult.php:165` | Organisation | N | belongsTo Organisation; Organisation has no tenant scope (only SoftDeletes); parent lookup of already-loaded result |
| `app/Models/BaseVote.php:337` | Organisation | N | belongsTo Organisation; Organisation has no tenant scope (SoftDeletes only) |
| `app/Models/Calendar.php:30` | GoogleAccount | N | GoogleAccount has no BelongsToTenant/SoftDeletes; call is a no-op on scopes |
| `app/Models/Candidacy.php:106` | User | ? | belongsTo User; User has no tenant scope; soft-deleted candidate user still resolvable (ballot display) |
| `app/Models/Candidacy.php:326` | Organisation | N | belongsTo Organisation; Organisation has no tenant scope |
| `app/Models/Code.php:93` | Organisation | N | belongsTo Organisation; Organisation has no tenant scope |
| `app/Models/Code.php:104` | User | ? | belongsTo User; User has no tenant scope; soft-deleted user resolvable |
| `app/Models/Code.php:164` | n/a | N | COMMENT LINE (mentions withoutGlobalScopes); no executable bypass |
| `app/Models/Election.php:435` | n/a | N | COMMENT LINE (mentions withoutGlobalScopes); no executable bypass |
| `app/Models/Election.php:1840` | ElectionOfficer | Y | ElectionOfficer has no tenant scope; election_id=this->id; soft-deleted officer (destroy() soft-deletes, status stays active) still grants role via getAllowedActionsForUser L1878 |
| `app/Models/Image.php:24` | User | ? | hasOne User; User has no tenant scope (SoftDeletes only) -> soft-deleted user can be returned |
| `app/Models/Post.php:61` | Organisation | N | belongsTo Organisation; no tenant scope on Organisation |
| `app/Models/Upload.php:25` | User | ? | belongsTo User; User has no tenant scope (SoftDeletes only) -> soft-deleted uploader can be returned |
| `app/Models/User.php:1298` | n/a | N | COMMENT LINE (mentions withoutGlobalScopes); no executable bypass |
| `app/Models/User.php:1355` | n/a | N | COMMENT LINE (mentions withoutGlobalScopes); no executable bypass |
| `app/Models/VoterSlug.php:61` | Organisation | N | belongsTo Organisation; no tenant scope on Organisation |
| `app/Models/VoterSlug.php:71` | User | ? | belongsTo User; no tenant scope on User; soft-deleted user resolvable |
| `app/Services/NewsletterService.php:306` | ElectionOfficer | Y | No BelongsToTenant on ElectionOfficer; users.organisation_id :315 anyway. Soft-deleted officers with status active become recipients. |
| `app/Services/NewsletterService.php:340` | ElectionOfficer | Y | No org filter on model; users.organisation_id :349; soft-deleted officers included. |
| `app/Services/NewsletterService.php:359` | OrganisationParticipant | Y | No org filter on model; organisation_participants.organisation_id :360; soft-deleted participants included. |
| `app/Services/NewsletterService.php:375` | OrganisationParticipant | Y | No org filter on model; organisation_id predicate :376; soft-deleted participants included. |
| `app/Services/NewsletterService.php:391` | UserOrganisationRole | N | No BelongsToTenant and no SoftDeletes; organisation_id predicate :392 anyway. |
| `app/Services/ParticipantImportService.php:109` | OrganisationParticipant | Y | No BelongsToTenant on model; org predicate anyway (:110, $this->organisation from admin-checked controller). Soft-deleted participant is found and updated, so re-import does not restore it. |
| `app/Traits/BelongsToTenant.php:53` | Organisation | N | platform org lookup ->where('slug','platform'); Organisation has no tenant scope; only SoftDeletes lifted (deleted platform org still used) |
| `app/Traits/BelongsToTenant.php:76` | Organisation | N | creating hook platform org lookup ->where('slug','platform'); Organisation unscoped; only SoftDeletes lifted |
| `app/Traits/BelongsToTenant.php:92` | n/a | N | COMMENT LINE (docblock usage example); no executable bypass |

## C4: potential defect (hypothesis) (20)

| Site | Model | soft | Evidence |
|---|---|---|---|
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeAggregateRepository.php:67` | CommitteeModel | Y | findById by id only, no org predicate; GetCommitteeDashboard:26 ignores its $tenantId. Mitigated: id comes from route-bound CommitteeModel (session-tenant scoped); route lacks ensure.organisation. |
| `app/Http/Controllers/Admin/AdminElectionController.php:18` | Election | Y | pending() lists all orgs' elections; also mounted at electionRoutes.php:679-681 (/admin/elections/pending) with only auth:sanctum+verified, no platform_admin => any verified user sees cross-org list. |
| `app/Http/Controllers/Admin/VotingSecurityController.php:154` | Election | Y | Election by route id, no org predicate; only gate is global users.is_committee_member (EnsureCommitteeMember:24) => committee member of any org can issue recovery links in another org. |
| `app/Http/Controllers/Demo/DemoVoteController.php:225` | Election | Y | Fallback picks first is_active election of ANY org/type ordered by id (l.225-228), no org predicate => request could bind to another org's (incl. real) election. |
| `app/Http/Controllers/Election/ElectionManagementController.php:385` | Election | N | Predicate org_id=$organisation->id l.387 but org comes from client slug with no membership check (route auth only, web.php:210) => any user enters another org's demo and session org set l.411. |
| `app/Http/Controllers/Election/ElectionManagementController.php:1439` | Election | Y | Slug lookup then session('current_organisation_id') + TenantContext set to election org l.1445-1446 BEFORE authorize l.1448 => unauthorised caller can switch session org. |
| `app/Http/Controllers/ElectionVotingController.php:29` | Election | Y | NOT A CALL: docblock comment for show(); see L33 for the real call and finding. |
| `app/Http/Controllers/ElectionVotingController.php:33` | Election | Y | show(): slug from URL, no org/entitlement gate; full $election model + organisation rendered (L76,L92) to any authenticated user of any org; entitlement only computed, not enforced. |
| `app/Http/Controllers/ResultController.php:19` | Election | Y | Slug from URL L20; only gate results_published L23; no org/membership check (route auth+verified only). Any user can view another org's published results; may be intended. |
| `app/Http/Controllers/ResultController.php:228` | Election | Y | downloadPDF: slug from URL L229; only gate results_published L233; no org/membership check. Same cross-org exposure as L19; may be intended. |
| `app/Http/Controllers/VoteController.php:121` | Election | N | Fallback: first demo election across ALL orgs (orderBy id); org check L128 only runs when a voter slug exists. Low risk (demo data) but can bind user to another org's demo. |
| `app/Http/Controllers/VoteController.php:2806` | Code | ? | Fallback with no election/org predicate: auth user's latest() code across all orgs; own data only, but may validate against another election's code (correctness, low). |
| `app/Http/Controllers/VoteController.php:3047` | Code | ? | Fallback with no election/org predicate: auth user's latest() code across all orgs/elections (verify_final_vote); own data only, wrong-election risk (low). |
| `app/Http/Controllers/VoteController.php:3605` | Vote | Y | find(client vote_id); receipt_hash check only if $vote->receipt_hash non-null L3610 (fail-open); a vote without receipt_hash from any org is returned with no org check. |
| `app/Http/Middleware/ElectionMiddleware.php:131` | Election [comment line] | Y | Comment documenting the fallback bypass executed at :142; see :142. |
| `app/Http/Middleware/ElectionMiddleware.php:142` | Election | Y | Fallback takes first is_active election of ANY org/type (no org/type predicate), sets request attr + session selected_election_id (:172-175); may bind user to another org's election. |
| `app/Models/Code.php:220` | Code | Y | scopeUnused removes Code tenant scope with no org/election predicate (L220-222); latent: no caller found in app/; would return codes of all orgs incl. deleted |
| `app/Models/Election.php:90` | Election | Y | resolveRouteBinding: slug lookup across all orgs + deleted (L90-92); relies on controllers; e.g. BallotPreviewController L31 never checks election->organisation_id === $organisation->id |
| `app/Models/Organisation.php:110` | Election | Y | resolveChildRouteBinding: Election by slug only, ignores parent $this->id (L110-112); latent: no route uses scopeBindings with {election} (routes/organisations.php L246) |
| `app/Services/VoterSlugService.php:72` | Election | Y | find($electionId); id may come from session selected_election_id (:37), settable cross-org by ElectionMiddleware:142; slug created with election org (:86), no user-org check. |

## C5: unresolved / no caller / comment (20)

| Site | Model | soft | Evidence |
|---|---|---|---|
| `app/Http/Controllers/Demo/DemoCodeController.php:638` | NONE(comment) | N | Comment line; not a call. |
| `app/Http/Controllers/Demo/DemoVoteController.php:209` | NONE(comment) | N | Comment line ('CRITICAL: Use withoutGlobalScopes()...'); not a call. |
| `app/Http/Controllers/Demo/DemoVoteController.php:376` | NONE(comment) | N | Comment line; not a call. |
| `app/Http/Controllers/Demo/DemoVoteController.php:2948` | NONE(comment) | N | Comment line; not a call. |
| `app/Http/Controllers/Demo/PublicDemoController.php:205` | DemoPost | N | Inside commented-out dd() diagnostic block (l.203-212); not executable, no runtime effect. |
| `app/Http/Controllers/Demo/PublicDemoController.php:206` | DemoCandidacy | N | Commented-out diagnostic (l.203-212); not executable. |
| `app/Http/Controllers/Demo/PublicDemoController.php:207` | DemoCandidacy | N | Commented-out diagnostic (l.203-212); not executable. |
| `app/Http/Controllers/Demo/PublicDemoController.php:208` | DemoCandidacy | N | Commented-out diagnostic (l.203-212); not executable. |
| `app/Http/Controllers/Demo/PublicDemoController.php:210` | DemoPost | N | Commented-out diagnostic (l.203-212); not executable. |
| `app/Http/Controllers/Demo/PublicDemoController.php:615` | NONE(docblock) | N | Text inside docblock of buildPostsData (l.611-617); not a call. |
| `app/Services/CacheService.php:43` | Election | Y | find($electionId) with no org predicate; no caller of App\Services\CacheService found (appears unused) - would be C4 if wired into a request path. |
| `app/Services/CacheService.php:62` | VoterSlug | Y | Slug lookup across orgs, includes soft-deleted (revoked) slugs; no callers found (unused class). |
| `app/Services/CacheService.php:101` | Election | Y | where('organisation_id',$organisationId) at :102 but no caller exists to show the source of $organisationId; includes trashed elections. |
| `app/Services/CacheService.php:121` | Election | Y | where('organisation_id',$organisationId) at :122; no caller found to validate source; trashed included. |
| `app/Services/CacheService.php:142` | Election | Y | where('organisation_id',$organisationId) at :144; no caller found; may return trashed demo election. |
| `app/Services/CacheService.php:169` | Election | N | find($electionId) only to read type for cache invalidation; no data returned; class has no callers. |
| `app/Services/CacheService.php:218` | Code | Y | user_id + election_id predicates (:219-220), no org predicate; no callers found; soft-deleted codes would count as can_vote. |
| `app/Services/DeviceFingerprint.php:51` | Code | Y | Count by device hash + election_id; only registered as singleton (AppServiceProvider:102), no consumer found; deleted codes would count toward device limit. |
| `app/Services/DeviceFingerprint.php:77` | Code | Y | Count by device hash + election_id + window; no consumer found. |
| `app/Services/DeviceFingerprint.php:150` | Code | Y | get() by device hash + election_id; no consumer found. |
