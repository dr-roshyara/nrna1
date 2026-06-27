import { describe, it, expect } from 'vitest'
import { phaseFor } from '@/Domain/Election/ElectionPhaseService'
import { ElectionLifecycleStates } from '@/Constants/ElectionLifecycleStates'

describe('phaseFor() — Projection Invariants', () => {

  describe('administration phase group', () => {
    it.each([
      ElectionLifecycleStates.DRAFT,
      ElectionLifecycleStates.SUBMITTED_FOR_APPROVAL,
      ElectionLifecycleStates.APPROVED,
      ElectionLifecycleStates.REJECTED,
      ElectionLifecycleStates.SETUP_ADMINISTRATION,
    ])('maps %s → administration', (state) => {
      const result = phaseFor(state)
      expect(result.phase).toBe('administration')
      expect(result.isOverlay).toBe(false)
      expect(result.lifecycleState).toBe(state)
    })
  })

  describe('nomination phase group', () => {
    it('maps setup_nomination → nomination', () => {
      const result = phaseFor(ElectionLifecycleStates.SETUP_NOMINATION)
      expect(result.phase).toBe('nomination')
      expect(result.isOverlay).toBe(false)
    })
  })

  describe('voting phase group', () => {
    it.each([
      ElectionLifecycleStates.READY_FOR_VOTING,
      ElectionLifecycleStates.VOTING_ACTIVE,
    ])('maps %s → voting', (state) => {
      const result = phaseFor(state)
      expect(result.phase).toBe('voting')
      expect(result.isOverlay).toBe(false)
    })
  })

  describe('results_pending phase group', () => {
    it('maps counting → results_pending', () => {
      const result = phaseFor(ElectionLifecycleStates.COUNTING)
      expect(result.phase).toBe('results_pending')
      expect(result.isOverlay).toBe(false)
    })
  })

  describe('results phase group', () => {
    it('maps results_published → results', () => {
      const result = phaseFor(ElectionLifecycleStates.RESULTS_PUBLISHED)
      expect(result.phase).toBe('results')
      expect(result.isOverlay).toBe(false)
    })
  })

  describe('overlay states (null phase)', () => {
    it('maps archived → null phase + isOverlay=true', () => {
      const result = phaseFor(ElectionLifecycleStates.ARCHIVED)
      expect(result.phase).toBeNull()
      expect(result.isOverlay).toBe(true)
    })

    it('maps suspended → null phase + isOverlay=true', () => {
      const result = phaseFor(ElectionLifecycleStates.SUSPENDED)
      expect(result.phase).toBeNull()
      expect(result.isOverlay).toBe(true)
    })
  })

  describe('unknown state fallback', () => {
    it('returns null phase and isOverlay=false for unknown state', () => {
      const result = phaseFor('completely_unknown_state')
      expect(result.phase).toBeNull()
      expect(result.isOverlay).toBe(false)
    })
  })

  describe('all 12 lifecycle states are explicitly handled', () => {
    it('every ElectionLifecycleState maps to a defined projection', () => {
      const allStates = Object.values(ElectionLifecycleStates)
      expect(allStates).toHaveLength(12)
      allStates.forEach(state => {
        const result = phaseFor(state)
        expect(result).toBeDefined()
        expect(result.lifecycleState).toBe(state)
      })
    })
  })
})
