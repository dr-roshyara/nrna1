import { describe, it, expect } from 'vitest'
import { computed, ref } from 'vue'
import { useElectionCapabilities, type StateMachineWithCapabilities } from '@/Composables/useElectionCapabilities'
import { ElectionActions } from '@/Constants/ElectionActions'

function makeSM(overrides: Partial<StateMachineWithCapabilities> = {}): StateMachineWithCapabilities {
  return {
    currentState: 'draft',
    allowedActions: [],
    capabilities: {},
    ...overrides,
  }
}

describe('useElectionCapabilities', () => {
  describe('with null stateMachine', () => {
    const sm = computed(() => null)
    const caps = useElectionCapabilities(sm)

    it('canDo returns false for any action', () => {
      expect(caps.canDo(ElectionActions.BEGIN_SETUP)).toBe(false)
      expect(caps.canDo(ElectionActions.SUBMIT_FOR_APPROVAL)).toBe(false)
      expect(caps.canDo(ElectionActions.OPEN_VOTING)).toBe(false)
    })

    it('denialReason returns null for any action', () => {
      expect(caps.denialReason(ElectionActions.BEGIN_SETUP)).toBeNull()
      expect(caps.denialReason(ElectionActions.OPEN_VOTING)).toBeNull()
    })

    it('isDenied returns true for any action (implicit denial)', () => {
      expect(caps.isDenied(ElectionActions.BEGIN_SETUP)).toBe(true)
    })
  })

  describe('with a denied capability', () => {
    const sm = computed(() =>
      makeSM({
        capabilities: {
          begin_setup: {
            allowed: false,
            denial_reason: 'wrong_state',
            denial_detail: 'Draft state not yet submitted for approval',
          },
        },
      })
    )
    const caps = useElectionCapabilities(sm)

    it('canDo returns false', () => {
      expect(caps.canDo(ElectionActions.BEGIN_SETUP)).toBe(false)
    })

    it('isDenied returns true', () => {
      expect(caps.isDenied(ElectionActions.BEGIN_SETUP)).toBe(true)
    })

    it('denialReason returns the reason string', () => {
      expect(caps.denialReason(ElectionActions.BEGIN_SETUP)).toBe('wrong_state')
    })
  })

  describe('with an allowed capability', () => {
    const sm = computed(() =>
      makeSM({
        capabilities: {
          begin_setup: {
            allowed: true,
            denial_reason: null,
            denial_detail: null,
          },
        },
      })
    )
    const caps = useElectionCapabilities(sm)

    it('canDo returns true', () => {
      expect(caps.canDo(ElectionActions.BEGIN_SETUP)).toBe(true)
    })

    it('isDenied returns false', () => {
      expect(caps.isDenied(ElectionActions.BEGIN_SETUP)).toBe(false)
    })

    it('denialReason returns null', () => {
      expect(caps.denialReason(ElectionActions.BEGIN_SETUP)).toBeNull()
    })
  })

  describe('all 14 constitution actions', () => {
    const sm = computed(() =>
      makeSM({
        capabilities: {
          submit_for_approval: {
            allowed: true,
            denial_reason: null,
            denial_detail: null,
          },
          approve: { allowed: true, denial_reason: null, denial_detail: null },
          reject: { allowed: false, denial_reason: 'already_approved', denial_detail: null },
          auto_submit: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
          begin_setup: { allowed: true, denial_reason: null, denial_detail: null },
          revise_and_resubmit: { allowed: false, denial_reason: 'not_submitted', denial_detail: null },
          complete_administration: { allowed: true, denial_reason: null, denial_detail: null },
          complete_nomination: { allowed: false, denial_reason: 'not_ready', denial_detail: null },
          open_voting: { allowed: true, denial_reason: null, denial_detail: null },
          close_voting: { allowed: false, denial_reason: 'voting_in_progress', denial_detail: null },
          publish_results: { allowed: true, denial_reason: null, denial_detail: null },
          archive: { allowed: false, denial_reason: 'results_not_published', denial_detail: null },
          suspend: { allowed: true, denial_reason: null, denial_detail: null },
          resume: { allowed: false, denial_reason: 'not_suspended', denial_detail: null },
        },
      })
    )
    const caps = useElectionCapabilities(sm)

    it('canDo correctly handles submit_for_approval (allowed)', () => {
      expect(caps.canDo(ElectionActions.SUBMIT_FOR_APPROVAL)).toBe(true)
    })

    it('canDo correctly handles reject (denied)', () => {
      expect(caps.canDo(ElectionActions.REJECT)).toBe(false)
    })

    it('canDo correctly handles begin_setup (allowed)', () => {
      expect(caps.canDo(ElectionActions.BEGIN_SETUP)).toBe(true)
    })

    it('canDo correctly handles complete_administration (allowed)', () => {
      expect(caps.canDo(ElectionActions.COMPLETE_ADMINISTRATION)).toBe(true)
    })

    it('canDo correctly handles open_voting (allowed)', () => {
      expect(caps.canDo(ElectionActions.OPEN_VOTING)).toBe(true)
    })

    it('canDo correctly handles publish_results (allowed)', () => {
      expect(caps.canDo(ElectionActions.PUBLISH_RESULTS)).toBe(true)
    })

    it('canDo correctly handles archive (denied)', () => {
      expect(caps.canDo(ElectionActions.ARCHIVE)).toBe(false)
    })

    it('canDo correctly handles suspend (allowed)', () => {
      expect(caps.canDo(ElectionActions.SUSPEND)).toBe(true)
    })

    it('denialReason returns correct values for all actions', () => {
      expect(caps.denialReason(ElectionActions.SUBMIT_FOR_APPROVAL)).toBeNull()
      expect(caps.denialReason(ElectionActions.REJECT)).toBe('already_approved')
      expect(caps.denialReason(ElectionActions.CLOSE_VOTING)).toBe('voting_in_progress')
    })
  })

  describe('openVotingBlockedReason helpers', () => {
    it('returns denial_reason when open_voting is denied', () => {
      const sm = computed(() =>
        makeSM({
          capabilities: {
            open_voting: {
              allowed: false,
              denial_reason: 'not_ready_for_voting',
              denial_detail: 'Administration and nomination phases not complete',
            },
          },
        })
      )
      const caps = useElectionCapabilities(sm)
      expect(caps.denialReason(ElectionActions.OPEN_VOTING)).toBe('not_ready_for_voting')
    })

    it('returns null when open_voting is allowed', () => {
      const sm = computed(() =>
        makeSM({
          capabilities: {
            open_voting: {
              allowed: true,
              denial_reason: null,
              denial_detail: null,
            },
          },
        })
      )
      const caps = useElectionCapabilities(sm)
      expect(caps.denialReason(ElectionActions.OPEN_VOTING)).toBeNull()
    })
  })

  describe('undefined stateMachine', () => {
    const sm = computed(() => undefined)
    const caps = useElectionCapabilities(sm)

    it('canDo returns false when stateMachine is undefined', () => {
      expect(caps.canDo(ElectionActions.BEGIN_SETUP)).toBe(false)
    })

    it('denialReason returns null when stateMachine is undefined', () => {
      expect(caps.denialReason(ElectionActions.BEGIN_SETUP)).toBeNull()
    })
  })

  describe('reactive updates', () => {
    const capabilities = ref<Record<string, any> | undefined>(undefined)
    const sm = computed(() =>
      makeSM({
        capabilities: capabilities.value,
      })
    )
    const caps = useElectionCapabilities(sm)

    it('updates reactively when capabilities change', () => {
      expect(caps.canDo(ElectionActions.BEGIN_SETUP)).toBe(false)

      capabilities.value = {
        begin_setup: { allowed: true, denial_reason: null, denial_detail: null },
      }

      expect(caps.canDo(ElectionActions.BEGIN_SETUP)).toBe(true)
    })
  })
})
