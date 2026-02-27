# Election System Testing TODO List

## Overview
This document outlines comprehensive test scenarios that should be implemented to ensure complete coverage of the election voting system. Tests are organized by category with priority levels.

---

## 1. Integration Tests - Vote Submission Flow

### Priority: HIGH

#### 1.1 Complete Vote Submission Journey (Real Election)
- [ ] Test full vote submission workflow:
  1. User lands on /code/create
  2. User agrees the terms and condition
  3. User lands on /vote/create  
  2. Selects candidates for all posts
  3. Reviews selections
  4. Submits vote
  5. Receives verification code
  6. Enters verification code
  7. Vote is recorded in correct table 
  8. Cannot vote again (redirected to dashboard)

**Test File:** `tests/Feature/VoteSubmissionFlowTest.php`

#### 1.2 Complete Vote Submission Journey (Demo Election)
- [ ] Test demo voting workflow:
  1. User lands on /demo/vote
  2. Selects candidates for all posts
  3. Submits vote
  4. First vote recorded in demo_votes
  5. User returns to voting form
  6. Selects different candidates
  7. Submits second vote
  8. Second vote recorded in demo_votes
  9. Total of 2 votes visible for user

**Test File:** `tests/Feature/DemoVoteSubmissionFlowTest.php`

#### 1.3 Partial Vote Submission (Incomplete Form)
- [ ] Test submitting with missing required fields:
  1. User selects candidates for only some posts
  2. Tries to submit vote
  3. Form validation error appears
  4. Vote is NOT recorded
  5. User can correct and resubmit

#### 1.4 Vote Code Verification
- [ ] Test verification code functionality:
  1. User submits vote with correct code2
  2. Vote is recorded
  3. User submits vote with incorrect code2
  4. Vote is rejected with error message
  5. Code2 is not marked as used

#### 1.5 Session Timeout During Voting
- [ ] Test session expiration:
  1. User starts vote (code1 used)
  2. Session expires
  3. User returns and tries to submit
  4. Receives error about session expiration
  5. Vote is NOT recorded

---

## 2. Authorization & Access Control Tests

### Priority: HIGH

#### 2.1 User Eligibility Checks
- [ ] Test users cannot vote without:
  - [ ] is_voter flag set to true
  - [ ] can_vote flag set to true
  - [ ] Active voter registration
  - [ ] Valid verification code

**Test File:** `tests/Feature/VoterEligibilityTest.php`

#### 2.2 User Role-Based Access
- [ ] Test different user types:
  - [ ] Non-voter cannot access /vote/create
  - [ ] Committee member can access voting form
  - [ ] Admin can access voting form
  - [ ] Logged-out user redirected to login

#### 2.3 IP Address Validation
- [ ] Test IP-based voting restrictions:
  1. User votes from IP A
  2. Stores IP address in votes table
  3. User tries to vote from IP B
  4. Detects IP change (if implemented)
  5. Appropriate action taken (warn, block, or allow)

**Test File:** `tests/Feature/IpAddressVotingTest.php`

#### 2.4 Voter Slug Isolation
- [ ] Test voter slug isolation:
  1. Voter A can only access their own slug
  2. Cannot access Voter B's slug URL
  3. Correct error/redirect on unauthorized access

---

## 3. Candidate & Post Tests

### Priority: MEDIUM

#### 3.1 Candidate Ordering by position_order
- [ ] Test candidate display order:
  1. Create 5 candidates with position_order 1-5
  2. Query candidates
  3. Verify returned in position_order order (not insertion order)
  4. Create 2 more candidates, reorder
  5. Verify reordering works

**Test File:** `tests/Feature/CandidateOrderingTest.php`

#### 3.2 Candidate Images
- [ ] Test candidate image handling:
  1. Create candidate with valid image path
  2. Create candidate with SVG image
  3. Create candidate without image (fallback)
  4. Verify correct image paths in response
  5. Test image 404 handling

#### 3.3 Candidate with Multiple Image Options
- [ ] Test image cycling:
  1. Candidate has image_path_1, image_path_2, image_path_3
  2. Verify system selects correct image for display
  3. Test fallback logic if primary image missing

#### 3.4 Post with No Candidates
- [ ] Test edge case:
  1. Create post with no associated candidates
  2. Request post candidates
  3. Should return empty array (not error)
  4. Frontend should handle gracefully

#### 3.5 Post with Multiple Candidacies
- [ ] Test post relationship:
  1. Create post with 10 candidates
  2. Query all candidates for post
  3. All 10 returned in correct order
  4. Only candidates for this post returned (not other posts)

---

## 4. Database & Data Integrity Tests

### Priority: HIGH

#### 4.1 Cascade Delete - Elections to Posts
- [ ] Test deletion cascade:
  1. Create election with 5 posts
  2. Each post has 3 candidates
  3. Delete election
  4. Verify all posts deleted
  5. Verify all candidates deleted
  6. Verify all votes deleted (if election had voting)

**Test File:** `tests/Feature/CascadeDeleteTest.php`

#### 4.2 Cascade Delete - Elections to Votes
- [ ] Test deletion cascade:
  1. Create real election
  2. User votes (vote record created)
  3. Delete election
  4. Verify vote record deleted
  5. Verify user has_voted flag reverted (or handle appropriately)

#### 4.3 Foreign Key Integrity
- [ ] Test foreign key constraints:
  1. Try to create post with non-existent election_id
  2. Should fail with foreign key error
  3. Try to create candidacy with non-existent post_id
  4. Should fail appropriately

#### 4.4 Data Consistency
- [ ] Test data consistency:
  1. After vote submission, verify:
     - Code.has_voted = true
     - User.has_voted = true
     - Vote record exists
     - Audit trail logged
  2. All related records are consistent

#### 4.5 Concurrent Voting (Race Condition)
- [ ] Test race condition handling:
  1. Two requests try to vote simultaneously
  2. First request records vote successfully
  3. Second request detects has_voted=true
  4. Second request is rejected
  5. Verify only one vote recorded

---

## 5. Language & Localization Tests

### Priority: MEDIUM

#### 5.1 Language Persistence in Voting Form
- [ ] Test language preference in /vote/create:
  1. User sets language to English on login
  2. Navigates to /vote/create
  3. Language remains English (not reverted to DE)
  4. Changes to German
  5. Refreshes page
  6. Language is German
  7. Preference persists in localStorage

**Test File:** `tests/Feature/LanguagePersistenceTest.php`

#### 5.2 Multi-Language Candidate Names
- [ ] Test candidate name display:
  1. Candidate has user.name in DB
  2. Displays in English interface
  3. Switch to German
  4. Still displays user.name (or translated if available)
  5. Switch to Nepali
  6. Displays correctly

#### 5.3 Multi-Language Voting Form
- [ ] Test form translations:
  1. All form labels translated to:
     - [ ] English
     - [ ] German (Deutsch)
     - [ ] Nepali (नेपाली)
  2. Error messages translated
  3. Success messages translated
  4. Placeholder text translated

#### 5.4 Language Fallback
- [ ] Test language fallback:
  1. User selects unsupported language (ES)
  2. System falls back to default (DE)
  3. No errors or broken text

---

## 6. Error Handling & Edge Cases

### Priority: MEDIUM

#### 6.1 Invalid Election States
- [ ] Test invalid election scenarios:
  1. Election is_active = false → Cannot vote
  2. Election start_date is future → Cannot vote
  3. Election end_date is past → Cannot vote
  4. Election type unknown → Handled gracefully

**Test File:** `tests/Feature/InvalidElectionStateTest.php`

#### 6.2 Invalid Code States
- [ ] Test invalid code scenarios:
  1. Code with can_vote_now = false → Cannot vote
  2. Code with has_voted = true (real election) → Cannot vote
  3. Code1 already used → Cannot use again
  4. Code2 already used → Cannot use again
  5. Code expired → Cannot use
  6. Code not found → Appropriate error

#### 6.3 Malformed Input
- [ ] Test input validation:
  1. Voting code not 6 characters → Error
  2. Candidate ID doesn't exist → Error
  3. Negative position_order → Error
  4. Invalid election_id format → Error

#### 6.4 Missing Related Data
- [ ] Test missing data scenarios:
  1. Election exists but no posts → Voting form shows empty
  2. Post exists but no candidates → Shows appropriate message
  3. Vote exists but candidate deleted → Handle gracefully
  4. User.code doesn't exist → Appropriate error

---

## 7. Performance & Load Tests

### Priority: LOW

#### 7.1 Large Candidate Sets
- [ ] Test performance with large datasets:
  1. Create 100 candidates for single post
  2. Load /vote/create page
  3. Verify page loads within acceptable time (< 2s)
  4. Candidates ordered correctly
  5. Images load

**Test File:** `tests/Feature/PerformanceTest.php`

#### 7.2 Large Vote Volume
- [ ] Test system with many votes:
  1. Record 1000 votes for single election
  2. Query election results
  3. Performance acceptable
  4. Counting logic correct

#### 7.3 Query Optimization
- [ ] Test query count:
  1. Loading /vote/create should use ≤ 5 queries
  2. Submitting vote should use ≤ 10 queries
  3. No N+1 query problems
  4. Eager loading used correctly

---

## 8. Audit & Logging Tests

### Priority: MEDIUM

#### 8.1 Vote Audit Trail
- [ ] Test voting audit logs:
  1. Each vote submission logged
  2. Log includes:
     - [ ] User ID
     - [ ] Election ID
     - [ ] Timestamp
     - [ ] IP address
     - [ ] Candidates selected
  3. Logs cannot be modified (append-only)

**Test File:** `tests/Feature/VotingAuditTest.php`

#### 8.2 Failed Vote Attempts
- [ ] Test logging of failed votes:
  1. Failed code verification logged
  2. Revoting attempts logged
  3. Ineligible voters logged
  4. Session timeouts logged

#### 8.3 Audit Report Generation
- [ ] Test generating audit reports:
  1. Generate voting audit for election
  2. Report includes all votes
  3. Report includes all failed attempts
  4. Report can be exported to CSV

---

## 9. API/Mobile Endpoint Tests

### Priority: MEDIUM

#### 9.1 Mobile API Voting Endpoints
- [ ] Test /mapi endpoints:
  1. GET /mapi/v1/elections/{id}/candidates
  2. POST /mapi/v1/votes (submit vote)
  3. GET /mapi/v1/votes (retrieve vote)
  4. Proper authentication required
  5. Tenant scoping correct

**Test File:** `tests/Feature/MobileVotingApiTest.php`

#### 9.2 Desktop API Voting Endpoints
- [ ] Test /api endpoints:
  1. GET /api/v1/elections/{id}/candidates
  2. POST /api/v1/votes
  3. GET /api/v1/votes/{id}
  4. Session auth works
  5. CSRF protection

#### 9.3 API Response Format
- [ ] Test API responses:
  1. JSON format correct
  2. Status codes appropriate (200, 400, 403, 404)
  3. Error messages descriptive
  4. Pagination works (if candidates > 50)

---

## 10. Security Tests

### Priority: HIGH

#### 10.1 Vote Tampering Prevention
- [ ] Test anti-tampering:
  1. User cannot modify candidate IDs in form
  2. User cannot modify election ID
  3. User cannot modify code2 before submission
  4. Server validates all submitted data

**Test File:** `tests/Feature/SecurityTest.php`

#### 10.2 XSS Prevention
- [ ] Test XSS vulnerability:
  1. Candidate name with <script> tags
  2. Post name with malicious JavaScript
  3. Verify HTML-escaped in response
  4. No script execution

#### 10.3 SQL Injection Prevention
- [ ] Test SQL injection:
  1. Candidate ID with SQL payload
  2. Code with SQL payload
  3. Verify queries parameterized
  4. No SQL execution

#### 10.4 CSRF Protection
- [ ] Test CSRF tokens:
  1. Vote submission requires valid CSRF token
  2. Missing token → 419 error
  3. Invalid token → 419 error
  4. Token validated on server

#### 10.5 Authorization Bypass Prevention
- [ ] Test authorization:
  1. Non-voter cannot submit vote
  2. User from different tenant cannot vote in this election
  3. Revoting attempt properly blocked
  4. IP address checked (if implemented)

---

## 11. Multi-Election Tests

### Priority: MEDIUM

#### 11.1 Multiple Concurrent Elections
- [ ] Test simultaneous elections:
  1. Create 2 real elections
  2. Create 2 demo elections
  3. User votes in election A
  4. User can vote in election B (different election)
  5. User cannot revote in election A
  6. Results kept separate

**Test File:** `tests/Feature/MultiElectionTest.php`

#### 11.2 Election Switching
- [ ] Test switching between elections:
  1. User votes in demo election 1
  2. Switches to demo election 2
  3. Votes in election 2 (allowed because different election)
  4. Returns to election 1
  5. Cannot revote in election 1

#### 11.3 User Vote History
- [ ] Test voting history:
  1. User votes in multiple elections
  2. Query user's votes
  3. All votes returned
  4. Correct election association
  5. No votes from other users leaked

---

## 12. Reporting & Analytics Tests

### Priority: LOW

#### 12.1 Election Results
- [ ] Test results calculation:
  1. Record 10 votes for candidate A
  2. Record 5 votes for candidate B
  3. Query results
  4. Candidate A: 10 votes
  5. Candidate B: 5 votes
  6. Correct percentages calculated

**Test File:** `tests/Feature/ElectionResultsTest.php`

#### 12.2 Voter Turnout
- [ ] Test turnout calculation:
  1. Create 100 eligible voters
  2. 60 vote
  3. Calculate turnout: 60%
  4. Correct formula

#### 12.3 Real-Time Results (if applicable)
- [ ] Test live results:
  1. Vote recorded
  2. Results update within 1 second
  3. No stale data served

#### 12.4 Results Export
- [ ] Test exporting results:
  1. Export to CSV
  2. Export to PDF
  3. Export to JSON
  4. Format correct
  5. All data included

---

## 13. Browser & Frontend Tests

### Priority: MEDIUM

#### 13.1 Responsive Design
- [ ] Test responsive design:
  1. Load voting form on mobile (375px)
  2. Load voting form on tablet (768px)
  3. Load voting form on desktop (1920px)
  4. All candidates visible
  5. Form usable on all sizes

**Test File:** `tests/Browser/VotingFormResponsiveTest.php`

#### 13.2 Form Submission Validation
- [ ] Test client-side validation:
  1. Submit with empty fields → Error
  2. Submit with invalid code → Error
  3. Required fields marked
  4. Error messages clear

#### 13.3 Image Loading
- [ ] Test image handling:
  1. Valid images load
  2. Broken image link → Fallback SVG
  3. Load time acceptable (< 1s per image)
  4. Images cached

#### 13.4 JavaScript Functionality
- [ ] Test JavaScript:
  1. Candidate selection works
  2. Review before submit works
  3. Code entry accepted
  4. No console errors
  5. Console warnings acceptable

---

## 14. Database Migration Tests

### Priority: MEDIUM

#### 14.1 Fresh Migration
- [ ] Test fresh database setup:
  1. Run `php artisan migrate:fresh`
  2. All tables created
  3. All relationships work
  4. No foreign key errors

**Test File:** `tests/Feature/MigrationTest.php`

#### 14.2 Migration Up/Down
- [ ] Test migration reversibility:
  1. Run migration up
  2. Verify tables exist
  3. Run migration down
  4. Verify tables removed
  5. Run up again - succeeds

#### 14.3 Data Preservation
- [ ] Test data during migrations:
  1. Insert data
  2. Run migration (new column)
  3. Existing data preserved
  4. New column has correct defaults

---

## 15. Documentation Tests

### Priority: LOW

#### 15.1 API Documentation
- [ ] Verify API docs:
  1. All endpoints documented
  2. Request examples provided
  3. Response examples provided
  4. Error codes explained
  5. Examples accurate

**Test:** Manual review of `docs/API.md`

#### 15.2 Setup Guide
- [ ] Verify setup documentation:
  1. Follow setup steps
  2. All steps work
  3. No missing dependencies
  4. No unclear instructions

#### 15.3 Architecture Documentation
- [ ] Verify architecture docs:
  1. Flow diagrams accurate
  2. Component relationships explained
  3. Data flow clear
  4. Code examples match implementation

---

## Priority Summary

### HIGH Priority (Must Test)
1. Complete vote submission flow (real & demo)
2. Voting restrictions enforcement
3. User eligibility checks
4. Cascade delete functionality
5. Security tests (tampering, XSS, SQL injection)

### MEDIUM Priority (Should Test)
1. Candidate ordering and images
2. Language persistence
3. Error handling and edge cases
4. Audit and logging
5. API endpoints
6. Multi-election scenarios
7. Database migrations

### LOW Priority (Nice to Test)
1. Performance tests
2. Reporting and analytics
3. Browser and responsive design
4. Documentation
5. Real-time updates

---

## Test Metrics

### Coverage Goals
- **Unit Tests:** ≥ 80%
- **Integration Tests:** ≥ 70%
- **Feature Tests:** ≥ 60%
- **Overall Code Coverage:** ≥ 75%

### Test Execution
- **Total Tests to Implement:** ~100
- **Currently Implemented:** 26
- **Remaining:** ~74
- **Estimated Time:** 2-3 weeks

### Continuous Integration
- [ ] Set up GitHub Actions for automated testing
- [ ] Run full test suite on every PR
- [ ] Generate coverage reports
- [ ] Block PRs if coverage drops below 75%

---

## Notes

### Tips for Implementation
1. **Factories:** Ensure all factories create valid test data with required relationships
2. **Database:** Use RefreshDatabase trait to isolate tests
3. **Mocking:** Mock external services (email, SMS, etc.)
4. **Assertions:** Use specific assertions (assertIsNull vs assertTrue)
5. **Documentation:** Add comments explaining complex test logic

### Common Issues
1. **Foreign Key Constraints:** Always create parent records before child records
2. **Timing Issues:** Use `now()` consistently for timestamps
3. **State Issues:** Clean up after each test (RefreshDatabase handles this)
4. **Async Issues:** Use appropriate wait times for async operations

---

