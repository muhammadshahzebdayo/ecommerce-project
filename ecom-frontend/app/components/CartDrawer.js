'use client';

import React from 'react';
import { useCart } from '../context/CartContext';
import { X, Plus, Minus, Trash2, ShoppingBag } from 'lucide-react';
import Link from 'next/link';

export default function CartDrawer() {
  const {
    cartItems,
    isCartOpen,
    setIsCartOpen,
    updateQuantity,
    removeFromCart,
    cartSubtotal,
    isHydrated,
  } = useCart();

  if (!isCartOpen) return null;

  return (
    <div className="fixed inset-0 z-50 overflow-hidden" role="dialog" aria-modal="true">
      <div className="absolute inset-0 overflow-hidden">
        {/* Overlay background */}
        <div
          onClick={() => setIsCartOpen(false)}
          className="absolute inset-0 bg-black/60 backdrop-blur-xs transition-opacity duration-300 animate-fadeIn"
        />

        <div className="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
          {/* Drawer container */}
          <div className="pointer-events-auto w-screen max-w-md transform bg-white dark:bg-zinc-950 shadow-2xl transition-all duration-300 animate-slideLeft">
            <div className="flex h-full flex-col overflow-y-scroll">
              
              {/* Header */}
              <div className="flex items-center justify-between border-b border-zinc-100 px-6 py-6 dark:border-zinc-800">
                <div className="flex items-center gap-2">
                  <ShoppingBag className="h-5 w-5 text-zinc-900 dark:text-white" />
                  <h2 className="text-lg font-serif font-bold text-zinc-900 dark:text-white">Shopping Cart</h2>
                </div>
                <button
                  onClick={() => setIsCartOpen(false)}
                  className="rounded-full p-1 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-500 dark:hover:bg-zinc-900 dark:hover:text-white transition-colors"
                >
                  <X className="h-5 w-5" />
                </button>
              </div>

              {/* Items List */}
              <div className="flex-1 overflow-y-auto px-6 py-4">
                {!isHydrated ? (
                  <div className="flex h-full items-center justify-center">
                    <div className="h-6 w-6 animate-spin rounded-full border-2 border-zinc-300 border-t-zinc-900 dark:border-zinc-700 dark:border-t-white" />
                  </div>
                ) : cartItems.length === 0 ? (
                  <div className="flex h-full flex-col items-center justify-center text-center">
                    <ShoppingBag className="h-16 w-16 text-zinc-300 dark:text-zinc-700 stroke-[1]" />
                    <h3 className="mt-4 text-sm font-medium text-zinc-900 dark:text-white">Your cart is empty</h3>
                    <p className="mt-1 text-xs text-zinc-500">Add some premium apparel or shoes to your cart.</p>
                    <button
                      onClick={() => setIsCartOpen(false)}
                      className="mt-6 rounded-full bg-zinc-900 px-6 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 transition-colors"
                    >
                      Continue Shopping
                    </button>
                  </div>
                ) : (
                  <div className="space-y-6">
                    {cartItems.map((item) => (
                      <div
                        key={item.variant_id}
                        className="flex border-b border-zinc-100 pb-6 dark:border-zinc-800 last:border-0"
                      >
                        {/* Product Image */}
                        <div className="h-24 w-20 flex-shrink-0 overflow-hidden rounded bg-zinc-50 border border-zinc-100 dark:bg-zinc-900 dark:border-zinc-800 flex items-center justify-center">
                          <img
                            src={
                              item.slug === 'oxford-leather-shoes' ? '/gents_shoes.png' :
                              item.slug === 'peshawari-chappal-classic' ? '/peshawari_chappal.png' :
                              item.slug === 'lace-wireless-bra' ? '/female_bra.png' :
                              '/gents_shoes.png'
                            }
                            alt={item.name}
                            className="h-full w-full object-cover object-center"
                          />
                        </div>

                        {/* Details */}
                        <div className="ml-4 flex flex-1 flex-col">
                          <div>
                            <div className="flex justify-between text-sm font-medium text-zinc-900 dark:text-white">
                              <h3 className="font-serif hover:underline text-xs line-clamp-1">
                                <Link href={`/product/${item.slug}`} onClick={() => setIsCartOpen(false)}>
                                  {item.name}
                                </Link>
                              </h3>
                              <p className="ml-4 font-mono font-semibold text-xs">Rs. {item.price.toLocaleString()}</p>
                            </div>
                            
                            <p className="mt-1 text-[10px] text-zinc-500 dark:text-zinc-400 font-mono">
                              SKU: {item.sku}
                            </p>

                            {/* Attributes */}
                            <div className="mt-2 flex flex-wrap gap-2">
                              {item.color && (
                                <span className="inline-flex items-center rounded bg-zinc-50 px-2 py-0.5 text-[9px] font-medium text-zinc-600 border border-zinc-100 dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-400">
                                  Color: {item.color}
                                </span>
                              )}
                              {item.size && (
                                <span className="inline-flex items-center rounded bg-zinc-50 px-2 py-0.5 text-[9px] font-medium text-zinc-600 border border-zinc-100 dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-400">
                                  Size: {item.size}
                                </span>
                              )}
                            </div>
                          </div>

                          {/* Quantity & Delete */}
                          <div className="flex flex-1 items-end justify-between text-sm mt-2">
                            <div className="flex items-center rounded-full border border-zinc-200 dark:border-zinc-800 px-1 py-0.5">
                              <button
                                onClick={() => updateQuantity(item.variant_id, item.quantity - 1)}
                                className="p-1 text-zinc-500 hover:text-zinc-900 dark:hover:text-white"
                              >
                                <Minus className="h-2.5 w-2.5" />
                              </button>
                              <span className="px-2 text-xs font-mono font-medium">{item.quantity}</span>
                              <button
                                onClick={() => updateQuantity(item.variant_id, item.quantity + 1)}
                                className="p-1 text-zinc-500 hover:text-zinc-900 dark:hover:text-white"
                                disabled={item.quantity >= (item.maxStock ?? 99)}
                              >
                                <Plus className="h-2.5 w-2.5" />
                              </button>
                            </div>

                            <button
                              onClick={() => removeFromCart(item.variant_id)}
                              className="font-medium text-amber-600 hover:text-amber-500 flex items-center gap-1 text-[11px]"
                            >
                              <Trash2 className="h-3 w-3" />
                              <span>Remove</span>
                            </button>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                )}
              </div>

              {/* Footer Checkout Summary */}
              {isHydrated && cartItems.length > 0 && (
                <div className="border-t border-zinc-100 px-6 py-6 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50">
                  <div className="flex justify-between text-base font-medium text-zinc-900 dark:text-white">
                    <p className="font-serif">Subtotal</p>
                    <p className="font-mono text-lg font-bold">Rs. {cartSubtotal.toLocaleString()}</p>
                  </div>
                  <p className="mt-0.5 text-xs text-zinc-500">Shipping and taxes calculated at checkout.</p>
                  
                  <div className="mt-6">
                    <Link
                      href="/checkout"
                      onClick={() => setIsCartOpen(false)}
                      className="flex items-center justify-center rounded-full bg-zinc-900 py-3 text-sm font-semibold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 transition-colors shadow-lg shadow-zinc-200 dark:shadow-none"
                    >
                      Checkout Now
                    </Link>
                  </div>
                  
                  <div className="mt-4 flex justify-center text-center text-xs">
                    <p>
                      or{' '}
                      <button
                        type="button"
                        onClick={() => setIsCartOpen(false)}
                        className="font-medium text-amber-600 hover:text-amber-500 transition-colors"
                      >
                        Continue Shopping<span aria-hidden="true"> &rarr;</span>
                      </button>
                    </p>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
