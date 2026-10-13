<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\IpVoteGroup;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-288 item 4 (Human PO 2026-10-13): for the per-IP vote limit, an IPv4 address counts on its own and an IPv6
 * address counts with its /64 network (the prefix is configurable). Pure PHP; an unreadable address is its own
 * group (exact match, as before) and never throws.
 */
final class IpVoteGroupTest extends TestCase
{
    public function test_an_ipv4_address_is_its_own_group(): void
    {
        $this->assertSame('203.0.113.7', IpVoteGroup::of('203.0.113.7'));
        $this->assertNotSame(IpVoteGroup::of('203.0.113.7'), IpVoteGroup::of('203.0.113.8'));
    }

    public function test_ipv6_addresses_in_one_64_network_share_a_group(): void
    {
        $this->assertSame(IpVoteGroup::of('2001:db8:1:2::10'), IpVoteGroup::of('2001:db8:1:2:aaaa:bbbb:cccc:dddd'));
        $this->assertSame('2001:db8:1:2::/64', IpVoteGroup::of('2001:db8:1:2::10'));
    }

    public function test_ipv6_addresses_in_different_64_networks_do_not_share_a_group(): void
    {
        $this->assertNotSame(IpVoteGroup::of('2001:db8:1:2::10'), IpVoteGroup::of('2001:db8:1:3::10'));
    }

    public function test_ipv6_spellings_of_one_network_share_a_group(): void
    {
        $this->assertSame(IpVoteGroup::of('2001:DB8:0001:0002::1'), IpVoteGroup::of('2001:db8:1:2:0:0:0:2'));
    }

    public function test_the_ipv6_prefix_is_configurable(): void
    {
        $this->assertSame(IpVoteGroup::of('2001:db8:1:2::1', 48), IpVoteGroup::of('2001:db8:1:ffff::1', 48));
        $this->assertNotSame(IpVoteGroup::of('2001:db8:1:2::1', 128), IpVoteGroup::of('2001:db8:1:2::2', 128));
        $this->assertSame('2001:db8:1::/48', IpVoteGroup::of('2001:db8:1:2::1', 48));
    }

    public function test_an_out_of_range_prefix_falls_back_to_64(): void
    {
        $this->assertSame('2001:db8:1:2::/64', IpVoteGroup::of('2001:db8:1:2::1', 0));
        $this->assertSame('2001:db8:1:2::/64', IpVoteGroup::of('2001:db8:1:2::1', 129));
    }

    public function test_an_unreadable_address_is_its_own_group_and_never_throws(): void
    {
        $this->assertSame('not-an-ip', IpVoteGroup::of('not-an-ip'));
        $this->assertSame('', IpVoteGroup::of(''));
    }

    public function test_same_group_tells_whether_two_addresses_count_together(): void
    {
        $this->assertTrue(IpVoteGroup::sameGroup('2001:db8:1:2::10', '2001:db8:1:2::99'));
        $this->assertFalse(IpVoteGroup::sameGroup('2001:db8:1:2::10', '2001:db8:1:3::10'));
        $this->assertTrue(IpVoteGroup::sameGroup('203.0.113.7', '203.0.113.7'));
        $this->assertFalse(IpVoteGroup::sameGroup('203.0.113.7', '203.0.113.8'));
        $this->assertFalse(IpVoteGroup::sameGroup('2001:db8:1:2::10', 'garbage'));
    }

    public function test_an_ipv4_mapped_ipv6_address_counts_as_its_ipv4_address(): void
    {
        // Otherwise every IPv4 client reported in mapped form would share one IPv6 /64 group (::/64).
        $this->assertSame('203.0.113.7', IpVoteGroup::of('::ffff:203.0.113.7'));
        $this->assertTrue(IpVoteGroup::sameGroup('::ffff:203.0.113.7', '203.0.113.7'));
        $this->assertFalse(IpVoteGroup::sameGroup('::ffff:203.0.113.7', '::ffff:203.0.113.8'));
    }

    public function test_ipv4_spellings_are_the_ipv4_address(): void
    {
        // How an IPv4 address may be stored: plain and IPv4-mapped (counted with one exact-match query).
        $this->assertSame(['203.0.113.7', '::ffff:203.0.113.7'], IpVoteGroup::spellingsOfIpv4('203.0.113.7'));
        $this->assertSame(['203.0.113.7', '::ffff:203.0.113.7'], IpVoteGroup::spellingsOfIpv4('::ffff:203.0.113.7'));
        $this->assertNull(IpVoteGroup::spellingsOfIpv4('2001:db8::1'));
        $this->assertNull(IpVoteGroup::spellingsOfIpv4('garbage'));
    }
}
