'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { useCart } from '../context/CartContext';
import { ShoppingCart, Search, Menu, X, ShieldCheck } from 'lucide-react';

export default function Navbar({ categories = [] }) {
  const { cartCount, setIsCartOpen } = useCart();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  return (
    <>
      {/* Top Premium Banner with Admin shortcut */}
      <div className="w-full bg-zinc-950 text-zinc-400 py-1.5 px-4 text-[10px] font-medium tracking-widest uppercase flex justify-between items-center border-b border-zinc-900 dark:bg-black transition-colors duration-300">
        <span>Free Shipping Nationwide on All Orders</span>
        <a
          href="http://127.0.0.1:8000/admin"
          target="_blank"
          rel="noopener noreferrer"
          className="text-amber-500 hover:text-amber-400 transition-colors font-bold flex items-center gap-1"
          title="Merchant Admin Portal"
        >
          <ShieldCheck className="h-3.5 w-3.5" />
          <span>Admin Portal</span>
        </a>
      </div>

      <header className="sticky top-0 z-40 w-full border-b border-zinc-100 bg-white/80 backdrop-blur-md dark:border-zinc-800 dark:bg-black/80 transition-colors duration-300">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="flex h-20 items-center justify-between">
            {/* Logo */}
            <div className="flex-shrink-0">
              <Link href="/" className="flex items-center gap-2">
                <span className="font-serif text-2xl font-bold tracking-widest text-zinc-900 dark:text-white hover:opacity-80 transition-opacity">
                  J.G.D
                </span>
                <span className="text-[10px] font-sans tracking-widest uppercase px-2 py-0.5 rounded bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-semibold">
                  COUTURE
                </span>
              </Link>
            </div>

            {/* Desktop Navigation with Dynamic Hover Dropdowns */}
            <nav className="hidden md:flex space-x-8 h-full items-center">
              <Link
                href="/"
                className="text-sm font-medium tracking-wider text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white uppercase transition-colors"
              >
                Home
              </Link>
              
              {categories.map((category) => {
                const hasSubs = category.subcategories && category.subcategories.length > 0;
                
                if (hasSubs) {
                  return (
                    <div key={category.id} className="relative group h-full flex items-center cursor-pointer">
                      <Link
                        href={`/category/${category.slug}`}
                        className="text-sm font-medium tracking-wider text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white uppercase transition-colors flex items-center gap-1.5"
                      >
                        <span>{category.name}</span>
                        <span className="text-[9px] text-zinc-400 group-hover:rotate-180 transition-transform duration-300">▼</span>
                      </Link>
                      
                      {/* Dropdown Box */}
                      <div className="absolute top-16 left-0 mt-1 hidden group-hover:block w-52 bg-white/95 backdrop-blur-md dark:bg-zinc-950/95 border border-zinc-100 dark:border-zinc-800 rounded-xl shadow-xl py-3 z-50 animate-fadeIn transition-all">
                        {category.subcategories.map((sub) => (
                          <Link
                            key={sub.id}
                            href={`/category/${sub.slug}`}
                            className="block px-5 py-2.5 text-[11px] font-bold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-900 hover:text-amber-600 dark:hover:text-amber-500 uppercase tracking-widest transition-colors"
                          >
                            {sub.name}
                          </Link>
                        ))}
                      </div>
                    </div>
                  );
                }

                return (
                  <Link
                    key={category.id}
                    href={`/category/${category.slug}`}
                    className="text-sm font-medium tracking-wider text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white uppercase transition-colors"
                  >
                    {category.name}
                  </Link>
                );
              })}
            </nav>

            {/* Utility Icons & Expandable Search & Standalone Cart */}
            <div className="flex items-center gap-4">
              
              {/* Expandable Search Input */}
              <div className="relative hidden sm:flex items-center">
                <input
                  type="text"
                  placeholder="Search products..."
                  className="w-10 hover:w-56 focus:w-56 h-10 rounded-full border border-zinc-200 bg-zinc-50 pl-10 pr-4 text-xs font-light text-zinc-900 outline-none focus:border-zinc-400 cursor-pointer focus:cursor-text dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:focus:border-zinc-700 transition-all duration-500 ease-in-out"
                />
                <Search className="absolute left-3.5 top-3.5 h-3.5 w-3.5 text-zinc-400 pointer-events-none" />
              </div>

              {/* Shopping Cart Standalone Round Button */}
              <button
                onClick={() => setIsCartOpen(true)}
                className="relative p-2.5 text-zinc-900 dark:text-white hover:text-amber-600 dark:hover:text-amber-500 hover:border-zinc-400 dark:hover:border-zinc-700 transition-colors flex items-center justify-center border border-zinc-200 dark:border-zinc-850 rounded-full bg-white dark:bg-zinc-900 h-10 w-10 shadow-xs cursor-pointer"
                aria-label="Shopping Cart"
              >
                <ShoppingCart className="h-4.5 w-4.5 stroke-[2]" />
                {cartCount > 0 && (
                  <span className="absolute -top-1 -right-1 flex h-4.5 min-w-[18px] items-center justify-center rounded-full bg-amber-600 px-1 text-[8px] font-bold text-white animate-pulse">
                    {cartCount}
                  </span>
                )}
              </button>

              {/* Mobile Menu Toggle */}
              <button
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="p-2 text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white md:hidden transition-colors"
                aria-label="Toggle Menu"
              >
                {mobileMenuOpen ? <X className="h-5.5 w-5.5" /> : <Menu className="h-5.5 w-5.5" />}
              </button>
            </div>
          </div>
        </div>

        {/* Mobile Navigation Drawer */}
        {mobileMenuOpen && (
          <div className="md:hidden border-t border-zinc-100 bg-white px-4 py-4 dark:border-zinc-800 dark:bg-black animate-fadeIn">
            <div className="flex flex-col space-y-4">
              <Link
                href="/"
                onClick={() => setMobileMenuOpen(false)}
                className="text-sm font-medium tracking-wider text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white uppercase transition-colors"
              >
                Home
              </Link>
              {categories.map((category) => (
                <div key={category.id} className="flex flex-col gap-2">
                  <Link
                    href={`/category/${category.slug}`}
                    onClick={() => setMobileMenuOpen(false)}
                    className="text-sm font-bold tracking-wider text-zinc-900 dark:text-white uppercase"
                  >
                    {category.name}
                  </Link>
                  {category.subcategories && category.subcategories.map((sub) => (
                    <Link
                      key={sub.id}
                      href={`/category/${sub.slug}`}
                      onClick={() => setMobileMenuOpen(false)}
                      className="text-xs font-semibold pl-4 text-zinc-500 uppercase tracking-widest"
                    >
                      — {sub.name}
                    </Link>
                  ))}
                </div>
              ))}
              <div className="relative pt-2">
                <input
                  type="text"
                  placeholder="Search products..."
                  className="w-full rounded-full border border-zinc-200 bg-zinc-50 py-2 pl-10 pr-4 text-xs font-light text-zinc-900 outline-none dark:border-zinc-800 dark:bg-zinc-900 dark:text-white"
                />
                <Search className="absolute left-3.5 top-4.5 h-4 w-4 text-zinc-400" />
              </div>
            </div>
          </div>
        )}
      </header>
    </>
  );
}
