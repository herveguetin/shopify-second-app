<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Cart\Decorators;

use Skafer\Decorator\DecoratorAbstract;
use stdClass;

class BoxesDecorator extends DecoratorAbstract
{

    private stdClass $cart;

    protected function decorateObject(mixed &$cart)
    {
        $this->cart = &$cart;
        $this->updateCartLines();
        $this->updateTotals();
    }

    private function updateCartLines(): void
    {
        $cart = json_decode(json_encode($this->cart), true);
        $updatedLines = array_map(function (array $line) {
            if ($line['properties']['_box'] !== 'null') { // 'null' or json
                $this->updateLineAttributes($line);
            }
            return $line;
        }, $cart['items']);
        usort($updatedLines, function ($a, $b) {
            return ($b['properties']['_timestamp'] < $a['properties']['_timestamp']);
        });

        $this->cart->items = $updatedLines;
    }

    private function updateLineAttributes(array &$line): void
    {
        $preparedLine = $this->prepareLine($line);
        $boxData = $preparedLine['properties']['_box_data'];
        $line['properties']['_box_data'] = $boxData;
        $line['discounted_price'] = $boxData['line_price'] / $preparedLine['quantity'];
        $line['final_line_price'] = $boxData['line_price'];
        $line['line_level_total_discount'] = $boxData['discount_amount'];
        $line['price'] = $boxData['line_price'] / $preparedLine['quantity'];
        $line['total_discount'] = $boxData['discount_amount'];
    }

    private function prepareLine(array $line): array
    {
        $boxConfig = json_decode($line['properties']['_box'], true);
        $nbOfBoxes = floor($line['quantity'] / $boxConfig['items_per_box']);
        $boxesPrices = $nbOfBoxes * (float)$boxConfig['box_price']['amount'] * 100;
        $nbOfRemainingBottles = $line['quantity'] - $nbOfBoxes * $boxConfig['items_per_box'];
        $singleBottleUnitPrice = $line['original_price'];
        $remainingBottlesPrice = $nbOfRemainingBottles * $singleBottleUnitPrice;
        $linePrice = $boxesPrices + $remainingBottlesPrice;
        $discountAmount = $line['original_line_price'] - $linePrice;
        $line['properties']['_box_data'] = [
            'box_config' => $boxConfig,
            'nb_of_boxes' => $nbOfBoxes,
            'boxes_prices' => $boxesPrices,
            'nb_of_remaining_bottles' => $nbOfRemainingBottles,
            'single_bottle_unit_price' => $singleBottleUnitPrice,
            'remaining_bottles_price' => $remainingBottlesPrice,
            'line_price' => $linePrice,
            'discount_amount' => $discountAmount,
        ];
        return $line;
    }

    private function updateTotals(): void
    {
        $linesFinalTotals = array_map(function (array $cartLine) {
            return (float)$cartLine['final_line_price'];
        }, $this->cart->items);

        $this->cart->total_price = array_sum($linesFinalTotals);

        $linesDiscounts = array_map(function (array $cartLine) {
            return (float)$cartLine['total_discount'];
        }, $this->cart->items);

        $this->cart->total_discount = array_sum($linesDiscounts);
    }
}
