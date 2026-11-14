<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Published;

/** PUBLISHED (D-233): a connection was asked for an organisation this operation is not routed to. Nothing is opened. */
final class NotTheRoutedOrganisation extends \RuntimeException
{
}
