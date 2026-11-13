'use client';

import React, { useState, useEffect } from 'react';
import { Shield, EyeOff, UserCheck, ArrowRight } from 'lucide-react';

export default function GenderGate() {
  const [isOpen, setIsOpen] = useState(false);
  const [step, setStep] = useState(1);
  const [ageGroup, setAgeGroup] = useState('25-35');
  const [discretePackaging, setDiscretePackaging] = useState(true);

  // Check on mount if gate has been completed previously
  useEffect(() => {
    const completed = localStorage.getItem('jgd_gender_gate');
    if (!completed) {
      setIsOpen(true);
    }
  }, []);

  const handleSelectMale = () => {
    localStorage.setItem('jgd_gender_gate', 'male');
    setIsOpen(false);
  };

  const handleSelectFemale = () => {
    setStep(2);
  };

  const handleCompleteFemale = (e) => {
    e.preventDefault();
    localStorage.setItem('jgd_gender_gate', 'female');
    localStorage.setItem('jgd_female_age', ageGroup);
    localStorage.setItem('jgd_female_discrete', discretePackaging ? 'yes' : 'no');
    setIsOpen(false);
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center overflow-hidden">
      {/* Background Blur Overlay */}
      <div className="absolute inset-0 bg-black/80 backdrop-blur-md animate-fadeIn" />

      {/* Modal Box */}
      <div className="relative w-full max-w-md mx-4 bg-zinc-900 border border-zinc-800 rounded-2xl p-8 text-center shadow-2xl animate-slideUp z-10 text-white font-sans">
        
        {/* Logo */}
        <div className="flex justify-center mb-6">
          <span className="font-serif text-3xl font-bold tracking-widest text-white">
            J.G.D
          </span>
          <span className="text-[10px] font-sans tracking-widest uppercase px-2 py-0.5 rounded bg-white text-zinc-950 font-bold ml-1.5 self-center">
            COUTURE
          </span>
        </div>

        {step === 1 ? (
          <div className="animate-fadeIn space-y-6">
            <h2 className="text-lg font-serif font-bold uppercase tracking-wider text-amber-500">
              Welcome to the Store
            </h2>
            <p className="text-xs text-zinc-400 leading-relaxed">
              J.G.D Couture provides premium apparel, leather footwear, and intimate apparel. Please select your department to customize your experience.
            </p>

            <div className="space-y-4 pt-4">
              <button
                onClick={handleSelectMale}
                className="w-full h-12 rounded-full bg-white text-zinc-950 text-xs font-bold uppercase tracking-wider hover:bg-zinc-100 transition-colors flex items-center justify-center gap-2"
              >
                <span>Shop Gents Collection</span>
                <ArrowRight className="h-4 w-4" />
              </button>

              <button
                onClick={handleSelectFemale}
                className="w-full h-12 rounded-full border border-zinc-700 bg-zinc-850 hover:bg-zinc-800 text-xs font-bold uppercase tracking-wider transition-colors flex items-center justify-center gap-2"
              >
                <span>Shop Ladies Collection</span>
                <ArrowRight className="h-4 w-4 text-amber-500" />
              </button>
            </div>
            
            <p className="text-[9px] text-zinc-500 pt-4">
              By entering you agree to our standard shopping protocols and stock inventory allocations.
            </p>
          </div>
        ) : (
          <form onSubmit={handleCompleteFemale} className="animate-fadeIn space-y-6 text-left">
            <h2 className="text-md font-serif font-bold uppercase tracking-wider text-amber-500 text-center">
              Personalize Your Experience
            </h2>
            <p className="text-xs text-zinc-400 leading-relaxed text-center">
              We care about your convenience and privacy. Please confirm your preferences:
            </p>

            {/* Age Group Swatches */}
            <div className="space-y-2">
              <label className="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest">
                Age Group
              </label>
              <div className="grid grid-cols-4 gap-2">
                {['18-24', '25-35', '36-45', '46+'].map((group) => {
                  const isActive = ageGroup === group;
                  return (
                    <button
                      key={group}
                      type="button"
                      onClick={() => setAgeGroup(group)}
                      className={`h-9 rounded border text-[10px] font-bold font-mono transition-colors ${
                        isActive
                          ? 'bg-amber-600 border-amber-600 text-white'
                          : 'bg-zinc-800 border-zinc-700 text-zinc-300 hover:border-zinc-500'
                      }`}
                    >
                      {group}
                    </button>
                  );
                })}
              </div>
            </div>

            {/* Discrete Shipping Packaging */}
            <div className="rounded-xl border border-zinc-800 bg-zinc-950 p-4 space-y-2 pt-3">
              <div className="flex items-start gap-3">
                <input
                  type="checkbox"
                  id="packaging"
                  checked={discretePackaging}
                  onChange={(e) => setDiscretePackaging(e.target.checked)}
                  className="mt-1 h-3.5 w-3.5 border-zinc-700 bg-zinc-800 text-amber-600 focus:ring-amber-500 rounded"
                />
                <label htmlFor="packaging" className="cursor-pointer">
                  <span className="text-[11px] font-bold uppercase tracking-wider text-zinc-200 flex items-center gap-1.5">
                    <EyeOff className="h-3.5 w-3.5 text-amber-500" />
                    <span>Discrete Double-Wrapping</span>
                  </span>
                  <span className="block mt-1 text-[10px] text-zinc-500 leading-normal">
                    We wrap your package in double layers with confidential tags. No product detail will be visible on the outer shipping slip.
                  </span>
                </label>
              </div>
            </div>

            {/* Confirm Enter */}
            <div className="pt-2">
              <button
                type="submit"
                className="w-full h-12 rounded-full bg-white text-zinc-950 text-xs font-bold uppercase tracking-wider hover:bg-zinc-100 transition-colors flex items-center justify-center gap-2"
              >
                <UserCheck className="h-4 w-4" />
                <span>Enter Couture Store</span>
              </button>
            </div>

          </form>
        )}

      </div>
    </div>
  );
}
