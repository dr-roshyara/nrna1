
● 🗳️ Complete Slug-Based Voting Workflow Steps

  Phase 1: Slug Generation & Access

  Step 0: Initiate Voting

  🔗 User Access Methods:
  1. Dashboard → "Vote Here" button → /voter/start
  2. Direct URL → /vote (auto-generates slug)
  3. Recovery command → php artisan election:recover-voter {user_id}

  🎯 Result: Generates 30-minute secure slug
  📍 Redirect: /v/{slug}/code/create

  ---
  Phase 2: Slug-Based Voting Steps

  Step 1: Code Verification

  📧 Route: GET /v/{slug}/code/create
  🎮 Controller: CodeController@create
  📱 Frontend: Code/CreateCode.vue
  🛡️ Middleware: voter.slug.window, voter.step.order, vote.eligibility, prevent.multiple.voting

  Process:
  1. 📨 System sends 6-digit code via email
  2. 👤 User enters verification code
  3. 📮 POST /v/{slug}/code → CodeController@store
  4. ✅ Validates code, sets can_vote_now = 1
  5. 📈 Updates slug current_step = 2
  6. ➡️ Redirects to /v/{slug}/vote/agreement

  Step 2: Voting Agreement

  📧 Route: GET /v/{slug}/vote/agreement
  🎮 Controller: CodeController@showAgreement
  📱 Frontend: Code/Agreement.vue
  🛡️ Middleware: voter.slug.window, voter.step.order, vote.eligibility, prevent.multiple.voting

  Process:
  1. 📄 Shows voting terms and conditions (Nepali & English)
  2. ☑️ User accepts agreement checkbox
  3. 📮 POST /v/{slug}/code/agreement → CodeController@submitAgreement
  4. ✅ Records agreement timestamp
  5. 📈 Updates slug current_step = 3
  6. ➡️ Redirects to /v/{slug}/vote/create

  Step 3: Vote Creation (Candidate Selection)

  📧 Route: GET /v/{slug}/vote/create
  🎮 Controller: VoteController@create
  📱 Frontend: Vote/CreateVotingPage.vue
  🛡️ Middleware: voter.slug.window, voter.step.order, vote.eligibility, prevent.multiple.voting

  Process:
  1. 🗳️ Displays ballot with all posts and candidates
  2. 👥 User selects candidates or "No Vote" for each post
  3. ✅ Client-side validation (required selections)
  4. 📮 POST /v/{slug}/vote/submit → VoteController@first_submission
  5. 💾 Stores vote data in session with session_name
  6. 📈 Updates slug current_step = 4
  7. ➡️ Redirects to /v/{slug}/vote/verify

  Step 4: Vote Verification

  📧 Route: GET /v/{slug}/vote/verify
  🎮 Controller: VoteController@verify
  📱 Frontend: Vote/Verify.vue
  🛡️ Middleware: voter.slug.window, voter.step.order, vote.eligibility, prevent.multiple.voting

  Process:
  1. 📋 Shows user's selections for review
  2. 📨 System sends second verification code
  3. 👤 User enters second verification code
  4. 📮 POST /v/{slug}/vote/verify → VoteController@store
  5. ✅ Final validation and vote storage in database
  6. 📈 Updates slug current_step = 5
  7. ➡️ Redirects to /v/{slug}/vote/complete

  Step 5: Completion

  📧 Route: GET /v/{slug}/vote/complete
  🎮 Controller: Closure function
  📱 Frontend: Custom completion page
  🛡️ Middleware: voter.slug.window, voter.step.order, vote.eligibility, prevent.multiple.voting

  Process:
  1. 🎉 Displays "Thank you" message
  2. 📄 Shows vote confirmation details
  3. 🔒 Marks user as has_voted = 1
  4. ⏰ Slug expires (no longer usable)
  5. 🏁 Voting process complete

  ---
  Security & Validation Layers

  🛡️ Middleware Stack

  1. voter.slug.window     → Validates slug exists & not expired
  2. voter.step.order      → Ensures steps completed in sequence
  3. vote.eligibility      → Checks user voting permissions
  4. prevent.multiple.voting → Prevents duplicate votes

  🔒 Security Features

  ⏰ Time-based: 30-minute slug expiry
  🔐 Unique: One active slug per user maximum
  📝 Tracked: Each step completion logged
  🚫 Sequential: Cannot skip steps
  💾 Session: Vote data stored until final submission
  🔍 Validated: Multiple validation layers

  📊 Progress Tracking

  Slug Model Fields:
  - current_step: 1→5 (tracks progress)
  - step_meta: JSON metadata for each step
  - expires_at: 30-minute window
  - is_active: true/false status

  ---
  🔄 Error Handling & Recovery

  Common Issues & Solutions

  ❌ Expired slug → Generate new slug via /voter/start
  ❌ Invalid step order → Redirect to correct step
  ❌ Session expired → Restart from Step 1
  ❌ Code verification failed → Show error, allow retry
  ❌ Vote validation failed → Return to selection page

  📞 Admin Recovery

  # Generate recovery slug for user
  php artisan election:recover-voter {user_id} --reason="Session expired"

  # Check user voting status
  php artisan tinker
  >>> User::find(1)->code
  >>> VoterSlug::where('user_id', 1)->get()

● This complete documentation shows the entire slug-based voting workflow from start to finish, including all security measures, error handling, and recovery options. The workflow ensures a secure,
  time-limited, and sequential voting process with multiple validation layers.