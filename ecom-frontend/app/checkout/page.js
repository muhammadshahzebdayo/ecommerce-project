'use client';

import React, { useState } from 'react';
import { useCart } from '../context/CartContext';
import { checkout as apiCheckout } from '../utils/api';
import { ShieldCheck, Truck, ShoppingBag, ArrowLeft, CheckCircle, AlertCircle, ShoppingCart } from 'lucide-react';
import Link from 'next/link';

export default function CheckoutPage() {
  const { cartItems, cartSubtotal, clearCart, isHydrated } = useCart();
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    address: '',
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [successOrder, setSuccessOrder] = useState(null);

  // Form handler
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  // Submit Order
  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    
    if (cartItems.length === 0) {
      setError('Your shopping cart is empty.');
      return;
    }

    setLoading(true);

    const orderPayload = {
      customer_name: formData.name,
      customer_email: formData.email,
      customer_phone: formData.phone,
      shipping_address: formData.address,
      items: cartItems.map((item) => ({
        variant_id: item.variant_id,
        quantity: item.quantity,
      })),
    };

    const response = await apiCheckout(orderPayload);
    setLoading(false);

    if (response.success) {
      setSuccessOrder(response.order_summary);
      clearCart(); // Clear the cart on success
    } else {
      setError(response.message || 'Failed to place order. Please try again.');
    }
  };

  if (!isHydrated) {
    return (
      <div className="flex-1 flex items-center justify-center py-24">
        <div className="h-8 w-8 animate-spin rounded-full border-3 border-zinc-300 border-t-zinc-900 dark:border-zinc-700 dark:border-t-white" />
      </div>
    );
  }

  // Order Success Screen
  if (successOrder) {
    return (
      <div className="mx-auto max-w-xl px-4 py-16 sm:px-6 lg:px-8 text-center flex-1 flex flex-col items-center justify-center animate-fadeIn">
        <CheckCircle className="h-16 w-16 text-emerald-600 mb-6 animate-pulse" />
        <h1 className="text-3xl font-serif font-extrabold text-zinc-900 dark:text-white">Order Confirmed!</h1>
        <p className="mt-3 text-sm text-zinc-500">
          Shukriya, {successOrder.customer}. Your order has been placed successfully in our database.
        </p>

        {/* Order Details box */}
        <div className="mt-8 w-full rounded-xl border border-zinc-100 bg-white p-6 text-left dark:border-zinc-900 dark:bg-zinc-900/40 shadow-xs space-y-4">
          <div className="flex justify-between items-center pb-2 border-b border-zinc-100 dark:border-zinc-800 text-xs font-semibold text-zinc-500 uppercase font-mono">
            <span>Item Details</span>
            <span>Total: Rs. {successOrder.total_amount.toLocaleString()}</span>
          </div>

          <div className="space-y-3">
            {successOrder.items.map((item, idx) => (
              <div key={idx} className="flex justify-between items-start text-xs">
                <div>
                  <p className="font-semibold text-zinc-900 dark:text-white">{item.product_name}</p>
                  <p className="text-[10px] text-zinc-400 font-mono mt-0.5">SKU: {item.sku} (x{item.quantity})</p>
                </div>
                <span className="font-mono font-medium">Rs. {(item.price * item.quantity).toLocaleString()}</span>
              </div>
            ))}
          </div>

          <div className="pt-4 border-t border-zinc-100 dark:border-zinc-800 text-xs text-zinc-400">
            <span className="font-semibold text-zinc-500 dark:text-zinc-300 block mb-1">Shipping Address:</span>
            {formData.address}
          </div>
        </div>

        <div className="mt-10">
          <Link
            href="/"
            className="inline-flex items-center justify-center rounded-full bg-zinc-900 px-8 py-3 text-xs font-bold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 transition-colors"
          >
            Continue Shopping
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 flex-1 w-full">
      
      {/* Top Banner */}
      <div className="mb-8 flex items-center justify-between">
        <Link
          href="/"
          className="inline-flex items-center gap-1 text-xs text-zinc-500 hover:text-zinc-950 dark:hover:text-white transition-colors"
        >
          <ArrowLeft className="h-3.5 w-3.5" />
          <span>Return to shop</span>
        </Link>
        <h1 className="text-xl font-serif font-bold text-zinc-900 dark:text-white">Secure Checkout</h1>
      </div>

      {cartItems.length === 0 ? (
        <div className="flex flex-col items-center justify-center text-center py-20 rounded-xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white/50">
          <ShoppingCart className="h-16 w-16 text-zinc-300 dark:text-zinc-700 stroke-[1.2]" />
          <h2 className="mt-4 text-sm font-semibold text-zinc-900 dark:text-white">Your cart is empty</h2>
          <p className="mt-1 text-xs text-zinc-500">You must add products to the cart before checking out.</p>
          <Link
            href="/"
            className="mt-6 rounded-full bg-zinc-900 px-6 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 transition-colors"
          >
            Go Shop
          </Link>
        </div>
      ) : (
        <div className="grid grid-cols-1 lg:grid-cols-12 lg:gap-x-12 gap-y-10">
          
          {/* Left Side: Order Form */}
          <div className="lg:col-span-7">
            <form onSubmit={handleSubmit} className="space-y-6">
              
              {error && (
                <div className="rounded-lg bg-rose-50 border border-rose-100 p-4 text-xs text-rose-800 flex items-center gap-2 dark:bg-rose-950/20 dark:border-rose-900/40 dark:text-rose-400">
                  <AlertCircle className="h-4.5 w-4.5" />
                  <span>{error}</span>
                </div>
              )}

              {/* Shipping Address Information */}
              <div className="space-y-4">
                <h2 className="text-sm font-semibold uppercase tracking-wider text-zinc-900 dark:text-white">
                  Customer & Shipping Information
                </h2>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label htmlFor="name" className="block text-[11px] font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">
                      Full Name
                    </label>
                    <input
                      required
                      type="text"
                      id="name"
                      name="name"
                      value={formData.name}
                      onChange={handleInputChange}
                      placeholder="e.g. Ali Ahmed"
                      className="w-full rounded border border-zinc-200 bg-white py-2 px-3 text-xs outline-none focus:border-zinc-400 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:focus:border-zinc-700"
                    />
                  </div>

                  <div>
                    <label htmlFor="phone" className="block text-[11px] font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">
                      Phone Number
                    </label>
                    <input
                      required
                      type="tel"
                      id="phone"
                      name="phone"
                      value={formData.phone}
                      onChange={handleInputChange}
                      placeholder="e.g. 03001234567"
                      className="w-full rounded border border-zinc-200 bg-white py-2 px-3 text-xs outline-none focus:border-zinc-400 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:focus:border-zinc-700 font-mono"
                    />
                  </div>
                </div>

                <div>
                  <label htmlFor="email" className="block text-[11px] font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">
                    Email Address
                  </label>
                  <input
                    required
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleInputChange}
                    placeholder="e.g. ali@example.com"
                    className="w-full rounded border border-zinc-200 bg-white py-2 px-3 text-xs outline-none focus:border-zinc-400 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:focus:border-zinc-700"
                  />
                </div>

                <div>
                  <label htmlFor="address" className="block text-[11px] font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">
                    Full Shipping Address
                    <span className="text-[10px] text-zinc-400 lowercase italic ml-1">(include city and province)</span>
                  </label>
                  <textarea
                    required
                    id="address"
                    name="address"
                    rows={4}
                    value={formData.address}
                    onChange={handleInputChange}
                    placeholder="e.g. House 123, Street 4, Sector F-8, Islamabad"
                    className="w-full rounded border border-zinc-200 bg-white py-2 px-3 text-xs outline-none focus:border-zinc-400 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:focus:border-zinc-700 resize-none"
                  />
                </div>
              </div>

              {/* Payment Methods */}
              <div className="space-y-4 pt-4 border-t border-zinc-100 dark:border-zinc-900">
                <h2 className="text-sm font-semibold uppercase tracking-wider text-zinc-900 dark:text-white">
                  Payment Method
                </h2>

                <div className="rounded-lg border border-amber-200 bg-amber-50/20 p-4 dark:border-amber-950/20 dark:bg-amber-950/5">
                  <div className="flex items-start">
                    <input
                      type="radio"
                      id="cod"
                      name="payment"
                      defaultChecked
                      className="mt-0.5 h-3.5 w-3.5 border-zinc-300 text-amber-600 focus:ring-amber-500"
                    />
                    <label htmlFor="cod" className="ml-3 block">
                      <span className="block text-xs font-semibold text-zinc-900 dark:text-white uppercase tracking-wider">
                        Cash On Delivery (COD)
                      </span>
                      <span className="mt-1 block text-xs text-zinc-500">
                        Pay with cash upon delivery. Standard warehouse stock allocation occurs instantly.
                      </span>
                    </label>
                  </div>
                </div>
              </div>

              {/* Submit Button */}
              <div className="pt-6">
                <button
                  type="submit"
                  disabled={loading}
                  className="flex w-full h-12 items-center justify-center gap-2 rounded-full bg-zinc-900 text-sm font-semibold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 transition-colors shadow-lg shadow-zinc-200 dark:shadow-none"
                >
                  {loading ? (
                    <div className="h-5.5 w-5.5 animate-spin rounded-full border-2 border-zinc-300 border-t-white dark:border-zinc-700 dark:border-t-zinc-900" />
                  ) : (
                    <>
                      <ShieldCheck className="h-4.5 w-4.5" />
                      <span>Confirm Order</span>
                    </>
                  )}
                </button>
              </div>

            </form>
          </div>

          {/* Right Side: Order Summary */}
          <div className="lg:col-span-5 lg:pl-4">
            <div className="rounded-2xl border border-zinc-100 bg-zinc-50/50 p-6 dark:border-zinc-900 dark:bg-zinc-900/10">
              <h2 className="text-sm font-semibold uppercase tracking-wider text-zinc-900 dark:text-white pb-4 border-b border-zinc-100 dark:border-zinc-800">
                Order Summary
              </h2>

              <div className="mt-6 space-y-4 max-h-80 overflow-y-auto pr-1">
                {cartItems.map((item) => (
                  <div key={item.variant_id} className="flex justify-between items-start text-xs border-b border-zinc-100 pb-4 dark:border-zinc-900 last:border-0 last:pb-0">
                    <div>
                      <span className="font-serif font-semibold text-zinc-900 dark:text-white">{item.name}</span>
                      <span className="text-[10px] text-zinc-400 font-mono block mt-0.5">SKU: {item.sku}</span>
                      <span className="inline-flex gap-2 text-[10px] text-zinc-500 mt-1">
                        {item.color && <span>Col: {item.color}</span>}
                        {item.size && <span>Size: {item.size}</span>}
                        <span>Qty: {item.quantity}</span>
                      </span>
                    </div>
                    <span className="font-mono font-semibold">Rs. {(item.price * item.quantity).toLocaleString()}</span>
                  </div>
                ))}
              </div>

              {/* Total Calculation */}
              <div className="mt-6 pt-6 border-t border-zinc-100 dark:border-zinc-800 space-y-3">
                <div className="flex justify-between text-xs text-zinc-500">
                  <span>Subtotal</span>
                  <span className="font-mono">Rs. {cartSubtotal.toLocaleString()}</span>
                </div>
                <div className="flex justify-between text-xs text-zinc-500">
                  <span>Shipping Cost</span>
                  <span className="font-mono text-emerald-600 font-semibold">FREE Delivery</span>
                </div>
                <div className="flex justify-between text-sm font-bold text-zinc-900 dark:text-white pt-3 border-t border-zinc-100 dark:border-zinc-800">
                  <span className="font-serif">Total Amount</span>
                  <span className="font-mono text-base">Rs. {cartSubtotal.toLocaleString()}</span>
                </div>
              </div>

              {/* Guarantee seals */}
              <div className="mt-8 space-y-3 pt-6 border-t border-zinc-100 dark:border-zinc-800 text-[10px] text-zinc-400">
                <div className="flex items-center gap-2">
                  <Truck className="h-4 w-4 text-zinc-500" />
                  <span>Free shipment over Pakistan. Dispatch tracking included.</span>
                </div>
                <div className="flex items-center gap-2">
                  <ShieldCheck className="h-4 w-4 text-zinc-500" />
                  <span>Secure transactions with direct inventory stock validation block.</span>
                </div>
              </div>

            </div>
          </div>

        </div>
      )}
    </div>
  );
}
