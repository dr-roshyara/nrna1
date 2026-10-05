<template>
  <!--
    PBDIGIT-213 C4-D — tells a registered voter why they cannot vote yet.
    Display only: shown when the server reports admissionPending (VotingEntitlement
    reason NotAdmitted); every voting route enforces it server-side.
  -->
  <div class="rounded-lg border border-warning-200 bg-warning-50 px-4 py-3 text-sm text-warning-800" role="status">
    Your identity must be verified by the Chief or Deputy Chief of the election before you can vote.
  </div>
</template>
