<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

/**
 * Why a redemption was refused (I-3). Every case gives the person the SAME neutral message (§12, PI-8): it never
 * reveals whether a code, claim, binding, account or organisation exists. The finer reason goes to the content-free
 * audit where an organisation is known.
 */
enum RedemptionRefusal: string
{
    case NotSignedIn = 'not-signed-in';
    case NoActivePerson = 'no-active-person';           // the Account is not active (unverified, deleted)
    case Throttled = 'throttled';                       // INV-ID-9: too many failed attempts for this code or client
    case NotRedeemable = 'not-redeemable';              // unknown, malformed, expired, revoked, redeemed, or R bound
    case PersonAlreadyBound = 'person-already-bound';   // INV-BND-2: this Person already holds another R of O

    public function publicMessage(): string
    {
        return 'The code could not be redeemed.';
    }
}
