import React from 'react';
import Link from 'next/link';
import { Mail, Phone, MapPin, Shield, CreditCard, RefreshCw } from 'lucide-react';

export default function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-zinc-950 text-zinc-400 dark:bg-black border-t border-zinc-900 transition-colors">
      
      {/* Top Value Propositions */}
      <div className="border-b border-zinc-900 py-8">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 gap-y-8 sm:grid-cols-3 text-center sm:text-left">
            <div className="flex flex-col sm:flex-row items-center gap-4">
              <div className="rounded-full bg-zinc-900 p-3 text-white border border-zinc-800">
                <Shield className="h-6 w-6" />
              </div>
              <div>
                <h4 className="text-sm font-semibold text-white uppercase tracking-wider">Secure Checkout</h4>
                <p className="mt-1 text-xs">All purchases are protected and encrypted.</p>
              </div>
            </div>
            <div className="flex flex-col sm:flex-row items-center gap-4">
              <div className="rounded-full bg-zinc-900 p-3 text-white border border-zinc-800">
                <RefreshCw className="h-6 w-6" />
              </div>
              <div>
                <h4 className="text-sm font-semibold text-white uppercase tracking-wider">Self-Healing SKUs</h4>
                <p className="mt-1 text-xs">Automatic warehouse tracking for variants.</p>
              </div>
            </div>
            <div className="flex flex-col sm:flex-row items-center gap-4">
              <div className="rounded-full bg-zinc-900 p-3 text-white border border-zinc-800">
                <CreditCard className="h-6 w-6" />
              </div>
              <div>
                <h4 className="text-sm font-semibold text-white uppercase tracking-wider">Multiple Options</h4>
                <p className="mt-1 text-xs">Cash on Delivery & instant digital checkouts.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Main Footer Links */}
      <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          
          {/* Brand Info */}
          <div className="col-span-1 md:col-span-2 space-y-4">
            <Link href="/" className="inline-flex items-center gap-2">
              <span className="font-serif text-2xl font-bold tracking-widest text-white">
                J.G.D
              </span>
              <span className="text-[10px] font-sans tracking-widest uppercase px-2 py-0.5 rounded bg-white text-zinc-950 font-semibold">
                COUTURE
              </span>
            </Link>
            <p className="text-xs leading-6 text-zinc-500 max-w-sm">
              Authentic clone design inspired by the premium fashion leaders Junaid Jamshed, Gul Ahmed Ideas, and Diners. Crafted for high performance, modern UX, and seamless checkout operations.
            </p>
            <div className="space-y-2.5 pt-4">
              <div className="flex items-center gap-3 text-xs">
                <MapPin className="h-4 w-4 text-zinc-500" />
                <span>Karachi, Lahore & Islamabad, Pakistan</span>
              </div>
              <div className="flex items-center gap-3 text-xs">
                <Phone className="h-4 w-4 text-zinc-500" />
                <span>+92 21 111-543-111</span>
              </div>
              <div className="flex items-center gap-3 text-xs">
                <Mail className="h-4 w-4 text-zinc-500" />
                <span>support@jgdcouture.com</span>
              </div>
            </div>
          </div>

          {/* Catalog Links */}
          <div>
            <h3 className="text-sm font-semibold uppercase tracking-wider text-white">Shop Catalog</h3>
            <ul className="mt-4 space-y-3 text-xs">
              <li>
                <Link href="/category/gents-shoes" className="hover:text-white transition-colors">
                  Gents Shoes
                </Link>
              </li>
              <li>
                <Link href="/category/female-undergarments" className="hover:text-white transition-colors">
                  Female Undergarments
                </Link>
              </li>
              <li>
                <a href="http://127.0.0.1:8000/admin" target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors">
                  Merchant Admin
                </a>
              </li>
            </ul>
          </div>

          {/* Customer Service */}
          <div>
            <h3 className="text-sm font-semibold uppercase tracking-wider text-white">Policies & Info</h3>
            <ul className="mt-4 space-y-3 text-xs">
              <li>
                <span className="cursor-not-allowed hover:text-white/60 transition-colors">Shipping & Delivery</span>
              </li>
              <li>
                <span className="cursor-not-allowed hover:text-white/60 transition-colors">Returns & Exchanges</span>
              </li>
              <li>
                <span className="cursor-not-allowed hover:text-white/60 transition-colors">Terms of Service</span>
              </li>
              <li>
                <span className="cursor-not-allowed hover:text-white/60 transition-colors">Privacy Policy</span>
              </li>
            </ul>
          </div>

        </div>

        {/* Bottom Rights */}
        <div className="mt-12 border-t border-zinc-900 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-600">
          <p>&copy; {currentYear} J.G.D Couture. All rights reserved.</p>
          <div className="flex items-center gap-4">
            <span className="font-mono tracking-widest text-[9px] uppercase px-2 py-1 rounded bg-zinc-900 border border-zinc-800 text-zinc-400">
              Laravel 11 + Filament v3 + Next.js 16
            </span>
          </div>
        </div>
      </div>
    </footer>
  );
}
