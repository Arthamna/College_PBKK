<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Server-computed view of a cart. Totals are never taken from the browser.
 */
final readonly class CartSummary
{
    /**
     * @param list<array{product_id:int,name:string,slug:string,image:?string,unit_price:int,quantity:int,stock:int,subtotal:int}> $lines
     */
    public function __construct(
        public array $lines,
        public int $subtotal,
        public int $shipping,
    ) {
    }

    public function total(): int
    {
        return $this->subtotal + $this->shipping;
    }

    public function isEmpty(): bool
    {
        return $this->lines === [];
    }

    public function itemCount(): int
    {
        return array_sum(array_column($this->lines, 'quantity'));
    }
}
