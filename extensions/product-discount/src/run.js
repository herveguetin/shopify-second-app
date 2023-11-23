// @ts-check
import { DiscountApplicationStrategy } from "../generated/api";

// Use JSDoc annotations for type safety
/**
* @typedef {import("../generated/api").RunInput} RunInput
* @typedef {import("../generated/api").FunctionRunResult} FunctionRunResult
* @typedef {import("../generated/api").Target} Target
* @typedef {import("../generated/api").ProductVariant} ProductVariant
*/

/**
* @type {FunctionRunResult}
*/
const EMPTY_DISCOUNT = {
  discountApplicationStrategy: DiscountApplicationStrategy.First,
  discounts: [],
};

// The configured entrypoint for the 'purchase.product-discount.run' extension target
/**
* @param {RunInput} input
* @returns {FunctionRunResult}
*/
export function run(input) {
  const lines = input.cart.lines
  const linesWithBoxes = lines.filter((line) => {
    const variant = /** @type {ProductVariant} */ (line.merchandise);
    const boxConfig = JSON.parse(line.attribute?.value ?? '{}')
    return Object.keys(boxConfig).length > 0
  })

  const discounts = linesWithBoxes.map(line => {
    const variant = /** @type {ProductVariant} */ (line.merchandise);
    const boxConfig = JSON.parse(line.attribute?.value ?? '{}')
    const nbOfBoxes = Math.floor(line.quantity / boxConfig.items_per_box)
    const boxesPrice = nbOfBoxes * parseFloat(boxConfig.box_price.amount)
    const nbOfremainingBottles = line.quantity - nbOfBoxes * boxConfig.items_per_box
    const singleBottleUnitPrice = line.cost.subtotalAmount.amount / line.quantity
    const remainingBottlesPrice = nbOfremainingBottles * singleBottleUnitPrice
    const linePrice = boxesPrice + remainingBottlesPrice
    const discountAmount = line.cost.subtotalAmount.amount - linePrice 

    const targets = [{
      productVariant: {
        id: variant.id
      }
    }]
    return {
      targets,
      value: {
        fixedAmount: {
          amount: discountAmount
        }
      },
      message: nbOfBoxes + " box(es) + " + nbOfremainingBottles + " bottle(s)"
    }
    });

  if (!discounts.length) {
    console.error("No cart lines qualify for volume discount.");
    return EMPTY_DISCOUNT;
  }

  return {
    discounts: discounts,
    discountApplicationStrategy: DiscountApplicationStrategy.All
  };
};
