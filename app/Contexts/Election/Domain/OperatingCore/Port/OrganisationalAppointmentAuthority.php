<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Port;

/**
 * Driven port: the EXTERNAL organisational appointment authority — DECLARED,
 * DELIBERATELY UNIMPLEMENTED (D-1; B-3; R-F4).
 *
 * EM-GOV-028 places Committee and Deputy appointment with "the organisational
 * governance body, or an authority expressly authorised by the organisation's
 * governance rules" — external and undefined (EM-OPEN-049: "NO DEFAULT AUTHORITY
 * MAY BE INVENTED MERELY BECAUSE ELECTION GOVERNANCE NEEDS ONE"). Vacancy filling
 * likewise arrives only from it (EM-GOV-056, 066).
 *
 * THIS PORT HAS NO ADAPTER, DEFAULT, STUB OR FALLBACK — anywhere, ever, until the
 * external dependency is resolved by organisational governance (never by the
 * Election model). §5b forbids Infrastructure from holding one. Absence of the
 * authority means the election cannot be constituted (EM-GOV-094 consequence) —
 * that is the intended wall, not a defect. A structural test enforces the absence.
 *
 * SCOPE: the Committee/Deputy-appointing authority ONLY. The CHIEF-appointing
 * authority is likewise external but additionally UNIDENTIFIED EVEN IN FORM
 * (EM-OPEN-049); nothing here presumes the two authorities are the same body, and
 * no port for the Chief authority exists — its form cannot yet be declared (D-1).
 *
 * The port declares no operations: the authority's acts ENTER the core as driving
 * acts (seat filling via `ElectionCommittee::fillSeat`, appointments recorded as
 * facts) executed by this port's future adapter — no internal caller exists
 * (EM-GOV-028, 056). Declaring operation signatures today would fix the
 * authority's form, which EM-OPEN-049 expressly leaves open.
 */
interface OrganisationalAppointmentAuthority
{
}
