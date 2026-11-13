<?php
// Review probe (not a test; no code changed). Question: does G-4 hold for EVERY edge (v3.11 §13) when a containment
// change moves a unit into another country, re-deriving its descendants' positions from the new country's profile?
// Usage: php probe-cross-country-move.php <repo root>
require $argv[1] . '/vendor/autoload.php';
require $argv[1] . '/tests/Support/ReferenceGeography/InMemoryPublishedAtlas.php';
require $argv[1] . '/tests/Support/ReferenceGeography/CompetentInstrument.php';
use App\Contexts\ReferenceGeography\Domain\{Basis, BasisKind, BeforeImage, Claim, ClaimId, ClaimValue, DependencyRecordingReader, PublishedClaim, ReferenceUnitId as U, RevisionValidator, SlotHistory, SlotKey, ValidDate, ValidInterval};
use Tests\Support\ReferenceGeography\{CompetentInstrument, InMemoryPublishedAtlas};
$n = 0;
$c = function (SlotKey $s, string $v, string $from = '1950-01-01') use (&$n) { $n++; return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', $n)), $s, ClaimValue::of($v), new ValidInterval(ValidDate::fromString($from)), new Basis(BasisKind::Instrument, 'act')); };
$NP = U::fromString('1a1a1a1a-1a1a-4a1a-8a1a-1a1a1a1a1a1a'); $DE = U::fromString('5e5e5e5e-5e5e-4e5e-8e5e-5e5e5e5e5e5e');
$P = U::fromString('2b2b2b2b-2b2b-4b2b-8b2b-2b2b2b2b2b2b'); $X = U::fromString('3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c');
$published = [CompetentInstrument::competenceClaim(),
  $c(SlotKey::admission($NP), 'admitted'), $c(SlotKey::admission($DE), 'admitted'),
  // Nepal: province 3, district 4. Germany: district 4, province 6.
  $c(SlotKey::profileTier($NP, 'admin', 3), 'TERRITORIAL:province'), $c(SlotKey::profileTier($NP, 'admin', 4), 'TERRITORIAL:district'),
  $c(SlotKey::profileClass($NP, 'admin', 'province'), '3'), $c(SlotKey::profileClass($NP, 'admin', 'district'), '4'),
  $c(SlotKey::profileTier($DE, 'admin', 4), 'TERRITORIAL:district'), $c(SlotKey::profileTier($DE, 'admin', 6), 'TERRITORIAL:province'),
  $c(SlotKey::profileClass($DE, 'admin', 'district'), '4'), $c(SlotKey::profileClass($DE, 'admin', 'province'), '6'),
  ...array_map(fn ($u) => $c(SlotKey::existence($u), 'exists'), [$NP, $DE, $P, $X]),
  // valid in Nepal: P province (3) under NP; X district (4) under P
  $c(SlotKey::containment($P, 'admin'), $NP->value()), $c(SlotKey::classification($P, 'admin'), 'province'),
  $c(SlotKey::containment($X, 'admin'), $P->value()), $c(SlotKey::classification($X, 'admin'), 'district')];
$p = 0;
$atlas = new InMemoryPublishedAtlas(count($published), array_map(function ($cl) use (&$p) { $p++; return new PublishedClaim($cl, BeforeImage::of(SlotHistory::for($cl->slot), $cl->interval, $p - 1), $p); }, $published));
$move = [$c(SlotKey::containment($P, 'admin'), $DE->value(), '2016-01-01')];   // P moves to Germany: P at 6, its child X at 4
$rules = array_map(fn ($v) => $v->rule, (new RevisionValidator())->validate($move, new DependencyRecordingReader($atlas), CompetentInstrument::byId(['act']))->violations);
echo "Move P (province) from Nepal to Germany, from 2016: violations = [" . implode(',', $rules) . "]\n";
echo "After the move, by §13: position(P) = 6 (Germany's province), position(X) = 4 (Germany's district): the edge X -> P breaks G-4.\n";
