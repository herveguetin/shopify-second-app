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
  const BOX_SIZE = 6
  const lines = input.cart.lines
  const linesWithBoxes = lines.filter((line) => {
    const variant = /** @type {ProductVariant} */ (line.merchandise);
    const value = JSON.parse(variant.product.metafield?.value ?? '{}')
    return value.amount !== undefined && line.quantity >= BOX_SIZE
  })

  const discounts = linesWithBoxes.map(line => {
    const variant = /** @type {ProductVariant} */ (line.merchandise);
    const boxPrice = JSON.parse(variant.product.metafield?.value ?? '{}')
    const nbOfBoxes = Math.floor(line.quantity / BOX_SIZE)
    const boxesPrice = nbOfBoxes * parseFloat(boxPrice.amount)
    const nbOfremainingBottles = line.quantity - nbOfBoxes * BOX_SIZE
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


  console.log(JSON.stringify(discounts))

  if (!discounts.length) {
    console.error("No cart lines qualify for volume discount.");
    return EMPTY_DISCOUNT;
  }

  return {
    discounts: discounts,
    discountApplicationStrategy: DiscountApplicationStrategy.All
  };
};
