<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\IpBindingNetwork;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-315 (PO 2026-10-26, supersedes PBDIGIT-247 B3): IP binding matches the voter's network, not the exact
 * address. IPv4 /24, IPv4-mapped IPv6 as its IPv4 address, IPv6 /64. An unreadable address never matches (fail
 * closed). Pure PHP.
 */
final class IpBindingNetworkTest extends TestCase
{
    public function test_ipv4_addresses_in_one_24_network_match(): void
    {
        $this->assertSame('106.101.7.0/24', IpBindingNetwork::of('106.101.7.23'));
        $this->assertTrue(IpBindingNetwork::matches('106.101.7.23', '106.101.7.88'));
        $this->assertTrue(IpBindingNetwork::matches('106.101.7.0', '106.101.7.255'));
    }

    public function test_ipv4_addresses_in_different_24_networks_do_not_match(): void
    {
        $this->assertFalse(IpBindingNetwork::matches('106.101.7.23', '106.101.8.23'));
        $this->assertFalse(IpBindingNetwork::matches('106.101.7.23', '39.7.7.23'));
    }

    public function test_an_ipv4_mapped_ipv6_address_is_its_ipv4_address(): void
    {
        $this->assertSame('106.101.7.0/24', IpBindingNetwork::of('::ffff:106.101.7.23'));
        $this->assertTrue(IpBindingNetwork::matches('::ffff:106.101.7.23', '106.101.7.200'));
    }

    public function test_ipv6_addresses_in_one_64_network_match(): void
    {
        $this->assertSame('2001:db8:1:2::/64', IpBindingNetwork::of('2001:db8:1:2::10'));
        $this->assertTrue(IpBindingNetwork::matches('2001:db8:1:2::10', '2001:0db8:0001:0002:aaaa:bbbb:cccc:dddd'));
    }

    public function test_ipv6_addresses_in_different_64_networks_do_not_match(): void
    {
        $this->assertFalse(IpBindingNetwork::matches('2001:db8:1:2::10', '2001:db8:1:3::10'));
    }

    public function test_an_unreadable_or_missing_address_never_matches(): void
    {
        $this->assertNull(IpBindingNetwork::of('not-an-ip'));
        $this->assertNull(IpBindingNetwork::of(null));
        $this->assertFalse(IpBindingNetwork::matches('not-an-ip', 'not-an-ip'));
        $this->assertFalse(IpBindingNetwork::matches(null, '106.101.7.23'));
        $this->assertFalse(IpBindingNetwork::matches('106.101.7.23', null));
    }
}
