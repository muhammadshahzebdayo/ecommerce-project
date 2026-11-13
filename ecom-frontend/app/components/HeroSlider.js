'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { ArrowRight, ChevronLeft, ChevronRight } from 'lucide-react';

export default function HeroSlider() {
  const slides = [
    {
      id: 1,
      image: '/hero_fashion.png',
      tag: 'Gents Premium Footwear',
      titlePart1: 'The Art of Modern',
      titlePart2: 'Pakistani Luxury',
      subtitle: 'Handcrafted Peshawari Kaptaan Chappals and premium leather formal Oxford shoes. Detailed stitching, authentic materials, and direct warehouse dispatch.',
      btnText: 'Shop Gents Shoes',
      btnLink: '/category/gents-shoes'
    },
    {
      id: 2,
      image: '/nighty_set.png',
      tag: 'Ladies Intimate Couture',
      titlePart1: 'Luxurious Silk Satin &',
      titlePart2: 'Loungewear Sets',
      subtitle: 'Experience absolute nighttime comfort with our soft silk satin robes and wireless lace bras. Tailored for maximum support and a premium fit.',
      btnText: 'Ladies Undergarments',
      btnLink: '/category/female-undergarments'
    },
    {
      id: 3,
      image: '/branding_banner.png',
      tag: 'Secure & Discreet Deliveries',
      titlePart1: 'Confidential Privacy',
      titlePart2: 'Double-Wrapped Packaging',
      subtitle: 'All order dispatches are processed with unmarked outer wraps and secure tags to ensure 100% customer shipping privacy nationwide.',
      btnText: 'Explore Collections',
      btnLink: '/category/female-undergarments'
    }
  ];

  const [activeIndex, setActiveIndex] = useState(0);

  // Auto-play slides every 6 seconds
  useEffect(() => {
    const interval = setInterval(() => {
      setActiveIndex((prev) => (prev + 1) % slides.length);
    }, 6000);
    return () => clearInterval(interval);
  }, [slides.length]);

  const handlePrev = () => {
    setActiveIndex((prev) => (prev - 1 + slides.length) % slides.length);
  };

  const handleNext = () => {
    setActiveIndex((prev) => (prev + 1) % slides.length);
  };

  return (
    <section className="relative overflow-hidden min-h-[500px] sm:min-h-[580px] flex items-center border-b border-zinc-100 dark:border-zinc-900 transition-colors bg-zinc-50 dark:bg-zinc-950">
      
      {/* Slides Background Images Container */}
      <div className="absolute inset-0 z-0">
        {slides.map((slide, idx) => {
          const isActive = idx === activeIndex;
          return (
            <div
              key={slide.id}
              className={`absolute inset-0 transition-opacity duration-1000 ease-in-out ${
                isActive ? 'opacity-100' : 'opacity-0 pointer-events-none'
              }`}
            >
              {/* Blending gradients (Right-to-Left and Bottom-to-Top) */}
              <div className="absolute inset-0 bg-gradient-to-r from-zinc-50 via-zinc-50/90 to-zinc-50/20 dark:from-zinc-950 dark:via-zinc-950/90 to-zinc-950/20 z-10" />
              <img
                src={slide.image}
                alt={slide.titlePart1}
                style={{
                  imageRendering: '-webkit-optimize-contrast',
                  imageRendering: 'crisp-edges'
                }}
                className="h-full w-full object-cover lg:object-right opacity-95 dark:opacity-55 scale-[0.98] transition-transform duration-1000"
              />
            </div>
          );
        })}
      </div>

      {/* Slider Content Layer */}
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-20 w-full">
        {slides.map((slide, idx) => {
          const isActive = idx === activeIndex;
          if (!isActive) return null;
          
          return (
            <div key={slide.id} className="max-w-2xl animate-slideUp space-y-5">
              
              {/* Slide Tagline */}
              <div className="inline-flex items-center gap-2 rounded-full border border-zinc-200 dark:border-zinc-800 bg-white/60 dark:bg-black/60 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-400 backdrop-blur-xs">
                <span className="flex h-1.5 w-1.5 rounded-full bg-amber-600 animate-ping" />
                <span>{slide.tag}</span>
              </div>
              
              {/* Slide Title (Thinner Font weight and restored gold colors) */}
              <h1 className="text-3xl sm:text-5xl font-serif font-bold tracking-tight leading-tight text-zinc-900 dark:text-white">
                {slide.titlePart1} <br />
                <span className="text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-amber-700 dark:from-amber-500 dark:to-amber-600">
                  {slide.titlePart2}
                </span>
              </h1>
              
              {/* Slide Subtitle */}
              <p className="text-xs sm:text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed max-w-xl font-light">
                {slide.subtitle}
              </p>

              {/* Slide CTA Button */}
              <div className="pt-2">
                <Link
                  href={slide.btnLink}
                  className="inline-flex items-center justify-center gap-2 rounded-full bg-zinc-900 px-7 py-3 text-xs font-semibold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 transition-all shadow-lg shadow-zinc-200 dark:shadow-none hover:translate-x-1"
                >
                  <span>{slide.btnText}</span>
                  <ArrowRight className="h-3.5 w-3.5" />
                </Link>
              </div>

            </div>
          );
        })}
      </div>

      {/* Navigation Arrows (Matching Gul Ahmed Circular Buttons) */}
      <button
        onClick={handlePrev}
        className="absolute left-4 z-30 flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-zinc-900 shadow-md border border-zinc-100 hover:bg-white dark:bg-zinc-900/90 dark:text-white dark:border-zinc-800 dark:hover:bg-zinc-800 transition-all cursor-pointer hover:scale-105"
        aria-label="Previous Slide"
      >
        <ChevronLeft className="h-5 w-5" />
      </button>

      <button
        onClick={handleNext}
        className="absolute right-4 z-30 flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-zinc-900 shadow-md border border-zinc-100 hover:bg-white dark:bg-zinc-900/90 dark:text-white dark:border-zinc-800 dark:hover:bg-zinc-800 transition-all cursor-pointer hover:scale-105"
        aria-label="Next Slide"
      >
        <ChevronRight className="h-5 w-5" />
      </button>

      {/* Bottom Dot Indicators */}
      <div className="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 flex gap-2.5">
        {slides.map((_, idx) => {
          const isActive = idx === activeIndex;
          return (
            <button
              key={idx}
              onClick={() => setActiveIndex(idx)}
              className={`h-1.5 rounded-full transition-all duration-300 cursor-pointer ${
                isActive 
                  ? 'w-5 bg-amber-600 dark:bg-amber-500' 
                  : 'w-1.5 bg-zinc-300 dark:bg-zinc-700 hover:bg-zinc-400'
              }`}
              aria-label={`Go to slide ${idx + 1}`}
            />
          );
        })}
      </div>

    </section>
  );
}
