<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\StoreSetting;

class MoneyService
{
    /**
     * Get the active store currency code.
     */
    public function getCurrencyCode(): string
    {
        return (string) StoreSetting::get('currency_code', 'USD');
    }

    /**
     * Format a money amount with currency symbol according to PRD standards (US English).
     */
    public function format(float|string $amount, ?string $currency = null): string
    {
        $currency = strtoupper($currency ?? $this->getCurrencyCode());
        $val = (float) $amount;

        return match ($currency) {
            'USD' => '$' . number_format($val, 2, '.', ','),
            'EUR' => '€' . number_format($val, 2, '.', ','),
            'GBP' => '£' . number_format($val, 2, '.', ','),
            'IDR' => 'Rp ' . number_format($val, 0, ',', '.'),
            'JPY' => '¥' . number_format($val, 0, '.', ','),
            default => $currency . ' ' . number_format($val, 2, '.', ','),
        };
    }

    /**
     * Convert decimal amount to Stripe minor units (cents for USD).
     */
    public function toMinorUnits(float|string $amount, ?string $currency = null): int
    {
        $currency = strtoupper($currency ?? $this->getCurrencyCode());
        $val = (float) $amount;

        // Zero-decimal currencies
        if (in_array($currency, ['JPY', 'KRW', 'VND', 'IDR'], true)) {
            return (int) round($val);
        }

        // Standard 2-decimal currencies (USD, EUR, GBP, etc.)
        return (int) round($val * 100);
    }

    /**
     * Convert Stripe minor units back to decimal amount.
     */
    public function fromMinorUnits(int $minorUnits, ?string $currency = null): float
    {
        $currency = strtoupper($currency ?? $this->getCurrencyCode());

        if (in_array($currency, ['JPY', 'KRW', 'VND', 'IDR'], true)) {
            return (float) $minorUnits;
        }

        return round($minorUnits / 100, 2);
    }

    /**
     * Calculate order totals snapshot from items subtotal, fulfillment, and store settings.
     *
     * @return array{
     *   items_subtotal: float,
     *   shipping_amount: float,
     *   taxable_amount: float,
     *   tax_amount: float,
     *   total_amount: float,
     *   currency_code: string
     * }
     */
    public function calculateTotals(float $itemsSubtotal, string $fulfillmentType): array
    {
        $currency = $this->getCurrencyCode();

        // 1. Shipping calculation
        $deliveryFee = (float) StoreSetting::get('delivery_flat_fee', '15.00');
        $shippingAmount = ($fulfillmentType === 'delivery') ? $deliveryFee : 0.00;

        // 2. Tax calculation
        $taxRatePercent = (float) StoreSetting::get('tax_rate_percent', '8.00');
        $taxShipping = filter_var(StoreSetting::get('tax_shipping', 'false'), FILTER_VALIDATE_BOOLEAN);

        $taxableAmount = $taxShipping ? ($itemsSubtotal + $shippingAmount) : $itemsSubtotal;
        $taxAmount = round($taxableAmount * ($taxRatePercent / 100), 2);

        // 3. Total
        $totalAmount = round($itemsSubtotal + $shippingAmount + $taxAmount, 2);

        return [
            'items_subtotal' => round($itemsSubtotal, 2),
            'shipping_amount' => round($shippingAmount, 2),
            'taxable_amount' => round($taxableAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total_amount' => $totalAmount,
            'currency_code' => $currency,
        ];
    }
}
