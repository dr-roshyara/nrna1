// TRANSITIONAL — These grep-based tests will be replaced by ESLint rules in C.2.8
import { describe, it, expect } from 'vitest'
import { readFileSync } from 'fs'
import { resolve } from 'path'

const root = resolve(__dirname, '../../../resources/js')

function readFile(rel: string): string {
  return readFileSync(resolve(root, rel), 'utf-8')
}

/** Filter non-comment lines containing pattern */
function codeLines(content: string, pattern: string | RegExp): string[] {
  return content.split('\n').filter(line => {
    const trimmed = line.trim()
    // Skip comment-only lines
    if (trimmed.startsWith('//') || trimmed.startsWith('*')) return false
    return typeof pattern === 'string' ? line.includes(pattern) : pattern.test(line)
  })
}

describe('Frontend Architecture — Constitutional Invariants', () => {

  describe('ElectionPhaseService.ts must not bypass composable', () => {
    it('ActionAuthorizationRules does not read .allowedActions in method bodies', () => {
      const violations = codeLines(
        readFile('Domain/Election/ElectionPhaseService.ts'),
        '.allowedActions'
      ).filter(line => !line.includes('@deprecated') && !line.includes('allowedActions?:'))
      expect(violations).toHaveLength(0)
    })
  })

  describe('useElectionCapabilities.ts must use ElectionActions constants', () => {
    it('no hardcoded action strings in canDo() or denialReason() calls', () => {
      const violations = codeLines(
        readFile('Composables/useElectionCapabilities.ts'),
        /canDo\('[^']+'\)|denialReason\('[^']+'\)|isDenied\('[^']+'\)/
      )
      expect(violations).toHaveLength(0)
    })
  })

  describe('Management.vue must destructure from composable, not rewrap', () => {
    const mgmt = () => readFile('Pages/Election/Management.vue')

    it('does not redefine canSubmitForApproval as local computed', () => {
      expect(codeLines(mgmt(), /const canSubmitForApproval\s*=\s*computed/)).toHaveLength(0)
    })
    it('does not redefine canBeginSetup as local computed', () => {
      expect(codeLines(mgmt(), /const canBeginSetup\s*=\s*computed/)).toHaveLength(0)
    })
    it('does not redefine canOpenVoting as local computed', () => {
      expect(codeLines(mgmt(), /const canOpenVoting\s*=\s*computed/)).toHaveLength(0)
    })
  })

  describe('Vocabulary alignment — C.2.6', () => {

    describe('ElectionLifecycleStates.ts must exist with all 12 states', () => {
      it('contains all 12 constitutional lifecycle states', () => {
        const content = readFile('Constants/ElectionLifecycleStates.ts')
        const required = [
          'DRAFT', 'SUBMITTED_FOR_APPROVAL', 'APPROVED', 'REJECTED',
          'SETUP_ADMINISTRATION', 'SETUP_NOMINATION', 'READY_FOR_VOTING',
          'VOTING_ACTIVE', 'COUNTING', 'RESULTS_PUBLISHED', 'ARCHIVED', 'SUSPENDED'
        ]
        required.forEach(state => {
          expect(content).toContain(`${state}:`)
        })
      })
    })

    describe('ElectionPhaseService.ts must formalize phase/lifecycle separation', () => {
      it('exports ElectionPhase type (renamed from PhaseState)', () => {
        const content = readFile('Domain/Election/ElectionPhaseService.ts')
        expect(content).toContain('export type ElectionPhase')
      })

      it('does not export the old ambiguous PhaseState type', () => {
        const content = readFile('Domain/Election/ElectionPhaseService.ts')
        expect(content).not.toContain("export type PhaseState =")
      })

      it('exports phaseFor() projection function', () => {
        const content = readFile('Domain/Election/ElectionPhaseService.ts')
        expect(content).toContain('export function phaseFor')
      })

      it('does not contain orphaned PhaseStateAggregator class', () => {
        const content = readFile('Domain/Election/ElectionPhaseService.ts')
        expect(content).not.toContain('class PhaseStateAggregator')
      })

      it('does not contain orphaned PhaseProgressTracker class', () => {
        const content = readFile('Domain/Election/ElectionPhaseService.ts')
        expect(content).not.toContain('class PhaseProgressTracker')
      })
    })

    describe('Vocabulary alignment — C.2.7', () => {

      describe('StateMachinePanel.vue must use ElectionLifecycleStates constants', () => {
        it('imports ElectionLifecycleStates from @/Constants/ElectionLifecycleStates', () => {
          const content = readFile('Pages/Election/Partials/StateMachinePanel.vue')
          expect(content).toContain('ElectionLifecycleStates')
          expect(content).toContain('@/Constants/ElectionLifecycleStates')
        })

        it('imports phaseFor from @/Domain/Election/ElectionPhaseService', () => {
          const content = readFile('Pages/Election/Partials/StateMachinePanel.vue')
          expect(content).toContain('phaseFor')
          expect(content).toContain('@/Domain/Election/ElectionPhaseService')
        })

        it('phases array uses phaseFor() for phase field — not hardcoded phase strings', () => {
          const content = readFile('Pages/Election/Partials/StateMachinePanel.vue')
          // phases array entries should call phaseFor(), not hardcode phase group strings
          const violations = codeLines(content, /phase:\s*'(administration|nomination|voting|results)'/)
          expect(violations).toHaveLength(0)
        })
      })

      describe('StateBadge.vue must use ElectionLifecycleStates constants', () => {
        it('imports ElectionLifecycleStates from @/Constants/ElectionLifecycleStates', () => {
          const content = readFile('Components/Election/StateBadge.vue')
          expect(content).toContain('ElectionLifecycleStates')
          expect(content).toContain('@/Constants/ElectionLifecycleStates')
        })
      })
    })
  })

  describe('Projection Sovereignty — C.2.8', () => {

    describe('StateMachinePanel.vue must not derive completion from election fields', () => {
      it('phaseStates computed is fully removed', () => {
        const content = readFile('Pages/Election/Partials/StateMachinePanel.vue')
        const violations = codeLines(content, /const phaseStates\s*=/)
        expect(violations).toHaveLength(0)
      })

      it('isPhaseCompleted reads from stateMachine.completedStates', () => {
        const content = readFile('Pages/Election/Partials/StateMachinePanel.vue')
        expect(content).toContain('completedStates')
      })

      it('template does not contain hardcoded voting_active string literal', () => {
        const content = readFile('Pages/Election/Partials/StateMachinePanel.vue')
        // Split at <script setup> to isolate the template section only
        const templateSection = content.split('<script setup>')[0]
        const violations = codeLines(templateSection, "'voting_active'")
        expect(violations).toHaveLength(0)
      })
    })
  })

  describe('Frontend Constitutional Runtime Projection — C.2.4', () => {

    describe('Lifecycle Visualization — components do not use election.status', () => {
      it('Show.vue does not use election.status', () => {
        const src = readFile('Pages/Election/Show.vue')
        expect(src).not.toMatch(/election\.status/)
      })

      it('Candidacy Apply.vue does not use election.status', () => {
        const src = readFile('Pages/Election/Candidacy/Apply.vue')
        expect(src).not.toMatch(/election\.status/)
      })

      it('Elections Voters Index.vue does not use election.status', () => {
        const src = readFile('Pages/Elections/Voters/Index.vue')
        expect(src).not.toMatch(/election\.status/)
      })

      it('Organisations Elections Index.vue does not use election.status', () => {
        const src = readFile('Pages/Organisations/Elections/Index.vue')
        expect(src).not.toMatch(/election\.status/)
      })
    })

    describe('Lifecycle constants — hardcoded state strings replaced', () => {
      it('Viewboard.vue uses ElectionLifecycleStates constant not raw string', () => {
        const src = readFile('Pages/Election/Viewboard.vue')
        expect(src).not.toContain("'voting_active'")
        expect(src).toContain('ElectionLifecycleStates')
      })

      it('Show.vue does not contain hardcoded results_published string', () => {
        const src = readFile('Pages/Election/Show.vue')
        expect(src).not.toContain("'results_published'")
      })

      it('Organisations Show.vue does not contain hardcoded state strings', () => {
        const src = readFile('Pages/Organisations/Show.vue')
        expect(src).not.toMatch(/'(voting_active|results_published|archived|counting|setup_|draft|approved)'/)
      })
    })

    describe('Constitutional authority fragmentation — no lifecycle+role combinations', () => {
      it('no Vue page derives capability from lifecycle constant + role combination', () => {
        const glob = require('glob')
        const files = glob.sync('resources/js/Pages/**/*.vue', { cwd: resolve(__dirname, '../../..') })
        const violations = files.filter((f: string) => {
          const src = readFileSync(resolve(__dirname, '../../../' + f), 'utf-8')
          // Detect: lifecycle constant used with && (role/condition combination)
          return /ElectionLifecycleStates\.[A-Z_]+.*&&/.test(src) ||
                 /&&.*ElectionLifecycleStates\.[A-Z_]+/.test(src)
        })
        // Management.vue already migrated — should pass
        expect(violations).toEqual([])
      })
    })

    describe('capabilities_trace contamination — ephemeral field forbidden in production', () => {
      it('no component reads capabilities_trace', () => {
        const glob = require('glob')
        const files = glob.sync('resources/js/Pages/**/*.vue', { cwd: resolve(__dirname, '../../..') })
        const violations = files.filter((f: string) => {
          const src = readFileSync(resolve(__dirname, '../../../' + f), 'utf-8')
          return src.includes('capabilities_trace')
        })
        expect(violations).toEqual([])
      })
    })

    describe('Flat can_* props eliminated', () => {
      it('Dashboard/ElectionDashboard.vue does not exist (deleted dead code — re-entry forbidden)', () => {
        const { existsSync } = require('fs')
        const { resolve: resolvePath } = require('path')
        const filePath = resolvePath(__dirname, '../../../resources/js/Pages/Dashboard/ElectionDashboard.vue')
        expect(existsSync(filePath)).toBe(false)
      })

      it('Election Show.vue does not use can_vote_now prop', () => {
        const src = readFile('Pages/Election/Show.vue')
        expect(src).not.toContain('can_vote_now')
      })
    })
  })
})
