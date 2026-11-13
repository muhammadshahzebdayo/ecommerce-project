'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { useCart } from '../../context/CartContext';
import { ArrowLeft, ShoppingCart, Check, RefreshCw, Truck, ShieldCheck } from 'lucide-react';

export default function ProductDetails({ product }) {
  const { addToCart } = useCart();
  const [selectedColor, setSelectedColor] = useState('');
  const [selectedSize, setSelectedSize] = useState('');
  const [quantity, setQuantity] = useState(1);
  const [matchedVariant, setMatchedVariant] = useState(null);

  // Extract unique attributes from variants
  const colors = Array.from(
    new Set(
      product.variants?.flatMap((v) =>
        v.option_values
          ?.filter((ov) => ov.attribute?.name === 'Color')
          .map((ov) => ov.value)
      ) || []
    )
  );

  const sizes = Array.from(
    new Set(
      product.variants?.flatMap((v) =>
        v.option_values
          ?.filter((ov) => ov.attribute?.name === 'Size')
          .map((ov) => ov.value)
      ) || []
    )
  );

  // Match variant whenever size or color selection changes
  useEffect(() => {
    if (!selectedColor || !selectedSize || !product.variants) {
      setMatchedVariant(null);
      return;
    }

    const variant = product.variants.find((v) => {
      const hasColor = v.option_values.some(
        (ov) => ov.attribute?.name === 'Color' && ov.value === selectedColor
      );
      const hasSize = v.option_values.some(
        (ov) => ov.attribute?.name === 'Size' && ov.value === selectedSize
      );
      return hasColor && hasSize;
    });

    setMatchedVariant(variant || null);
    setQuantity(1); // reset quantity to 1 on variant change
  }, [selectedColor, selectedSize, product]);

  // Compute active price (falls back to base product price if no variant is selected)
  const activePrice = matchedVariant
    ? matchedVariant.price ?? product.base_price
    : product.base_price;

  // Handle Add to Cart
  const handleAddToCart = () => {
    if (!matchedVariant) return;

    const cartItem = {
      id: product.id,
      variant_id: matchedVariant.id,
      name: product.name,
      sku: matchedVariant.sku,
      price: parseFloat(activePrice),
      quantity: quantity,
      color: selectedColor,
      size: selectedSize,
      slug: product.slug,
      maxStock: matchedVariant.stock,
    };

    addToCart(cartItem);
  };

  return (
    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 w-full flex-1 animate-fadeIn">
      
      {/* Back button */}
      <div className="mb-8">
        <Link
          href={`/category/${product.category?.slug}`}
          className="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors"
        >
          <ArrowLeft className="h-4 w-4" />
          <span>Back to {product.category?.name}</span>
        </Link>
      </div>

      <div className="lg:grid lg:grid-cols-2 lg:gap-x-12">
        
        {/* Left Side: Product Image Showcase */}
        <div className="flex flex-col">
          <div className="aspect-square w-full overflow-hidden rounded-2xl bg-zinc-50 border border-zinc-100 dark:bg-zinc-900 dark:border-zinc-800 flex items-center justify-center min-h-[360px] sm:min-h-[450px] relative">
            <img
              src={
                product.slug === 'oxford-leather-shoes' ? '/gents_shoes.png' :
                product.slug === 'peshawari-chappal-classic' ? '/peshawari_chappal.png' :
                product.slug === 'lace-wireless-bra' ? '/female_bra.png' :
                product.slug === 'silk-satin-nighty' ? '/nighty_set.png' :
                '/gents_shoes.png'
              }
              alt={product.name}
              className="h-full w-full object-cover object-center"
            />
          </div>
        </div>

        {/* Right Side: Product Details & Dynamic Form */}
        <div className="mt-10 lg:mt-0 flex flex-col justify-between">
          <div className="space-y-6">
            
            {/* Category and Title */}
            <div>
              <span className="text-xs font-mono tracking-widest uppercase text-amber-600 dark:text-amber-500 font-bold block">
                {product.category?.name}
              </span>
              <h1 className="mt-2 text-3xl sm:text-4xl font-serif font-extrabold text-zinc-900 dark:text-white">
                {product.name}
              </h1>
            </div>

            {/* Description */}
            <p className="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed font-light">
              {product.description}
            </p>

            {/* Price display */}
            <div className="border-t border-b border-zinc-100 py-4 dark:border-zinc-900 flex items-baseline justify-between transition-colors">
              <span className="text-xs text-zinc-400 uppercase tracking-widest font-mono">
                {matchedVariant ? 'Variant Price' : 'Base Price'}
              </span>
              <span className="text-2xl sm:text-3xl font-mono font-bold text-zinc-900 dark:text-white">
                Rs. {activePrice.toLocaleString()}
              </span>
            </div>

            {/* Color Swatch Selectors */}
            {colors.length > 0 && (
              <div className="space-y-3">
                <div className="flex justify-between text-xs font-medium uppercase tracking-wider text-zinc-500">
                  <span>Select Color</span>
                  <span className="font-semibold text-zinc-900 dark:text-white font-mono">
                    {selectedColor || <span className="text-amber-600 dark:text-amber-500 font-bold italic lowercase">required</span>}
                  </span>
                </div>
                <div className="flex flex-wrap gap-2.5">
                  {colors.map((color) => {
                    const isSelected = selectedColor === color;
                    return (
                      <button
                        key={color}
                        onClick={() => setSelectedColor(color)}
                        className={`inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-xs font-medium transition-all border cursor-pointer ${
                          isSelected
                            ? 'bg-zinc-900 text-white border-zinc-900 dark:bg-white dark:text-zinc-950 dark:border-white shadow-sm font-semibold'
                            : 'bg-white text-zinc-700 border-zinc-200 hover:border-zinc-400 dark:bg-zinc-900 dark:text-zinc-300 dark:border-zinc-800 dark:hover:border-zinc-700'
                        }`}
                      >
                        {isSelected && <Check className="h-3 w-3" />}
                        <span>{color}</span>
                      </button>
                    );
                  })}
                </div>
              </div>
            )}

            {/* Size Swatch Selectors */}
            {sizes.length > 0 && (
              <div className="space-y-3 pt-2">
                <div className="flex justify-between text-xs font-medium uppercase tracking-wider text-zinc-500">
                  <span>Select Size</span>
                  <span className="font-semibold text-zinc-900 dark:text-white font-mono">
                    {selectedSize || <span className="text-amber-600 dark:text-amber-500 font-bold italic lowercase">required</span>}
                  </span>
                </div>
                <div className="flex flex-wrap gap-2">
                  {sizes.map((size) => {
                    const isSelected = selectedSize === size;
                    return (
                      <button
                        key={size}
                        onClick={() => setSelectedSize(size)}
                        className={`inline-flex h-10 min-w-[42px] items-center justify-center rounded border text-xs font-mono font-bold transition-all cursor-pointer ${
                          isSelected
                            ? 'bg-zinc-900 text-white border-zinc-900 dark:bg-white dark:text-zinc-950 dark:border-white shadow-sm'
                            : 'bg-white text-zinc-700 border-zinc-200 hover:border-zinc-400 dark:bg-zinc-900 dark:text-zinc-300 dark:border-zinc-800 dark:hover:border-zinc-700'
                        }`}
                      >
                        {size}
                      </button>
                    );
                  })}
                </div>
              </div>
            )}

            {/* Active Variant SKU Display Box */}
            <div className="rounded-xl border border-dashed border-zinc-200 p-4 bg-zinc-50/50 dark:border-zinc-800 dark:bg-zinc-900/10">
              <div className="flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest">
                <RefreshCw className="h-4 w-4 animate-spin-slow" />
                <span>Auto-Generated SKU:</span>
              </div>
              <div className="mt-2.5 flex items-center justify-between">
                <code className="text-sm font-mono font-bold tracking-wider text-zinc-900 dark:text-white bg-white border border-zinc-200 px-2.5 py-1 rounded dark:bg-zinc-900 dark:border-zinc-800">
                  {matchedVariant ? matchedVariant.sku : 'SELECT OPTIONS'}
                </code>
                {matchedVariant ? (
                  <span
                    className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${
                      matchedVariant.stock > 0
                        ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400'
                        : 'bg-rose-50 text-rose-700 dark:bg-rose-950/20 dark:text-rose-400'
                    }`}
                  >
                    {matchedVariant.stock > 0 ? `In Stock (${matchedVariant.stock} left)` : 'Out of Stock'}
                  </span>
                ) : (
                  <span className="text-[10px] text-zinc-400 font-semibold">
                    {!selectedColor || !selectedSize ? 'Choose color & size above' : 'No variant combination'}
                  </span>
                )}
              </div>
              <p className="mt-2.5 text-[10px] text-zinc-400 dark:text-zinc-500">
                This SKU is generated automatically inside Laravel by merging Category initials, Product codes, and color/size attributes in a transaction-safe database lock.
              </p>
            </div>

            {/* Action buttons (Quantity + Add to Cart) */}
            <div className="pt-4 flex flex-col sm:flex-row items-center gap-4">
              {matchedVariant && matchedVariant.stock > 0 ? (
                <>
                  {/* Quantity selector */}
                  <div className="flex h-12 w-full sm:w-auto items-center justify-between rounded-full border border-zinc-200 dark:border-zinc-800 px-4">
                    <button
                      onClick={() => setQuantity(Math.max(1, quantity - 1))}
                      className="text-zinc-500 hover:text-zinc-950 dark:hover:text-white p-1"
                    >
                      -
                    </button>
                    <span className="px-6 text-sm font-mono font-semibold">{quantity}</span>
                    <button
                      onClick={() => setQuantity(Math.min(matchedVariant.stock, quantity + 1))}
                      className="text-zinc-500 hover:text-zinc-950 dark:hover:text-white p-1"
                      disabled={quantity >= matchedVariant.stock}
                    >
                      +
                    </button>
                  </div>

                  {/* Add to Cart button */}
                  <button
                    onClick={handleAddToCart}
                    className="flex h-12 w-full items-center justify-center gap-2 rounded-full bg-zinc-900 text-sm font-semibold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 transition-colors shadow-lg shadow-zinc-200 dark:shadow-none cursor-pointer"
                  >
                    <ShoppingCart className="h-4 w-4" />
                    <span>Add to Shopping Cart</span>
                  </button>
                </>
              ) : (
                <button
                  disabled
                  className="flex h-12 w-full cursor-not-allowed items-center justify-center rounded-full bg-zinc-100 border border-dashed border-zinc-200 text-sm font-semibold uppercase tracking-wider text-zinc-400 dark:bg-zinc-900 dark:text-zinc-600 dark:border-zinc-800"
                >
                  {!selectedColor || !selectedSize
                    ? 'Please Select Swatch Options'
                    : 'Out of Stock'}
                </button>
              )}
            </div>

          </div>

          {/* Delivery & Security seals */}
          <div className="mt-8 border-t border-zinc-100 pt-6 dark:border-zinc-900 grid grid-cols-2 gap-4 text-xs text-zinc-500">
            <div className="flex items-center gap-2">
              <Truck className="h-4.5 w-4.5 text-zinc-400" />
              <span>Standard delivery 2-4 working days</span>
            </div>
            <div className="flex items-center gap-2">
              <ShieldCheck className="h-4.5 w-4.5 text-zinc-400" />
              <span>Direct warehouse dispatch check</span>
            </div>
          </div>

        </div>

      </div>
    </div>
  );
}
