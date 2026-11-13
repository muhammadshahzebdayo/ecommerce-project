import React from 'react';
import Link from 'next/link';
import { getCategories, getProducts } from './utils/api';
import { ArrowRight, Star } from 'lucide-react';
import HeroSlider from './components/HeroSlider';

export default async function Home() {
  const [categories, productsData] = await Promise.all([
    getCategories(),
    getProducts({ page: 1 }),
  ]);

  // Filter out the intimate bra product from featured homepage listing as requested
  const products = (productsData.data || []).filter(
    (product) => product.slug !== 'lace-wireless-bra'
  );

  return (
    <div className="flex flex-col w-full premium-hero-bg min-h-screen">
      
      {/* Dynamic Interactive Hero Slideshow */}
      <HeroSlider />

      {/* Categories Cards Section */}
      <section className="py-16 sm:py-24 border-b border-zinc-100 dark:border-zinc-900 bg-white/50 dark:bg-zinc-950/20 backdrop-blur-xs transition-colors">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-xl mx-auto">
            <h2 className="text-3xl font-serif font-bold text-zinc-900 dark:text-white">Shop By Category</h2>
            <p className="mt-2 text-xs uppercase tracking-widest text-zinc-400 font-semibold font-mono">
              Curated collections with dynamic sizing attributes
            </p>
          </div>

          <div className="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2">
            {categories.map((category) => {
              const isShoes = category.slug.includes('shoes');
              return (
                <div
                  key={category.id}
                  className="group relative overflow-hidden rounded-xl border border-zinc-100 bg-white p-8 dark:border-zinc-900 dark:bg-zinc-900/40 hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300 shadow-xs flex flex-col sm:flex-row gap-6 justify-between min-h-[260px]"
                >
                  <div className="flex-1 flex flex-col justify-between">
                    <div>
                      <span className="text-[10px] font-mono tracking-widest uppercase text-amber-600 dark:text-amber-500 font-bold block">
                        Category Prefix: {category.sku_prefix}
                      </span>
                      <h3 className="mt-4 text-2xl font-serif font-bold text-zinc-900 dark:text-white group-hover:text-amber-600 transition-colors">
                        {category.name}
                      </h3>
                      <p className="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                        {isShoes
                          ? 'Premium leather formal Oxford shoes, Peshawari Chappals, and hand-stitched traditional sandals.'
                          : 'Ultra-comfortable support, custom band and cup sizes, premium lace wireless bras, and lightweight sets.'}
                      </p>
                    </div>

                    <div className="mt-6 flex items-center justify-between">
                      <span className="text-xs font-mono text-zinc-400">
                        Slug: <code className="bg-zinc-50 dark:bg-zinc-900 px-1 py-0.5 rounded">{category.slug}</code>
                      </span>
                      <Link
                        href={`/category/${category.slug}`}
                        className="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-zinc-900 dark:text-white group-hover:translate-x-1.5 transition-transform"
                      >
                        <span>Explore</span>
                        <ArrowRight className="h-3.5 w-3.5" />
                      </Link>
                    </div>
                  </div>

                  {/* Category Card Image */}
                  <div className="w-full sm:w-36 h-36 rounded-lg overflow-hidden bg-zinc-50 border border-zinc-100 dark:bg-zinc-900 dark:border-zinc-800 flex-shrink-0 self-center">
                    <img
                      src={isShoes ? '/gents_shoes.png' : '/female_bra.png'}
                      alt={category.name}
                      className="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                    />
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {/* Brand Identity / Packaging Presentation Section */}
      <section className="py-16 sm:py-24 border-b border-zinc-100 dark:border-zinc-900 bg-zinc-50 dark:bg-zinc-900/10 transition-colors">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-xl mx-auto mb-16">
            <span className="text-[10px] font-mono tracking-widest uppercase text-amber-600 dark:text-amber-500 font-bold block">
              Our Packaging Signature
            </span>
            <h2 className="mt-3 text-3xl font-serif font-bold text-zinc-900 dark:text-white sm:text-4xl">
              Signature Presentation & Privacy
            </h2>
            <p className="mt-4 text-xs text-zinc-500 dark:text-zinc-400">
              Delivering an unboxing experience that reflects the craftsmanship of our footwear and ensures the absolute confidentiality of our intimate collections.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            {/* Gents Footwear Packaging Card */}
            <div className="group flex flex-col justify-between rounded-2xl border border-zinc-100 bg-white p-6 sm:p-8 dark:border-zinc-900 dark:bg-zinc-900/40 shadow-xs transition-all duration-300 hover:border-zinc-300 dark:hover:border-zinc-700">
              <div>
                <div className="h-[240px] sm:h-[280px] w-full rounded-xl overflow-hidden border border-zinc-100 dark:border-zinc-800 relative bg-zinc-100 dark:bg-zinc-900">
                  <img
                    src="/shoes_packaging.png"
                    alt="J.G.D Couture Gents Luxury Footwear Packaging"
                    className="h-full w-full object-cover object-center group-hover:scale-[1.02] transition-transform duration-500"
                  />
                </div>
                <h3 className="mt-6 text-xl font-serif font-bold text-zinc-900 dark:text-white">
                  Gents Signature Footwear Packaging
                </h3>
                <p className="mt-3 text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed font-light">
                  Every pair of our premium leather footwear is wrapped in custom gold foil tissue paper and housed in a heavy-duty, drawer-style matte textured box. Complete with an engraved shoe travel horn and premium velvet dust bags for long-term preservation.
                </p>
              </div>
              <div className="mt-6 pt-6 border-t border-zinc-100 dark:border-zinc-800/80 grid grid-cols-3 gap-2 text-center">
                <div>
                  <span className="block text-xs font-mono font-bold text-zinc-900 dark:text-white">Drawer Box</span>
                  <span className="text-[9px] text-zinc-400 uppercase tracking-wider">Rigid Shell</span>
                </div>
                <div className="border-x border-zinc-100 dark:border-zinc-800 px-2">
                  <span className="block text-xs font-mono font-bold text-zinc-900 dark:text-white">Velvet Bag</span>
                  <span className="text-[9px] text-zinc-400 uppercase tracking-wider">Dust Protection</span>
                </div>
                <div>
                  <span className="block text-xs font-mono font-bold text-zinc-900 dark:text-white">Shoe Horn</span>
                  <span className="text-[9px] text-zinc-400 uppercase tracking-wider">Free Accessory</span>
                </div>
              </div>
            </div>

            {/* Ladies Intimate Apparel Packaging Card */}
            <div className="group flex flex-col justify-between rounded-2xl border border-zinc-100 bg-white p-6 sm:p-8 dark:border-zinc-900 dark:bg-zinc-900/40 shadow-xs transition-all duration-300 hover:border-zinc-300 dark:hover:border-zinc-700">
              <div>
                <div className="h-[240px] sm:h-[280px] w-full rounded-xl overflow-hidden border border-zinc-100 dark:border-zinc-800 relative bg-zinc-100 dark:bg-zinc-900">
                  <img
                    src="/bra_packaging.png"
                    alt="J.G.D Couture Ladies Intimate discreet wrap packaging"
                    className="h-full w-full object-cover object-center group-hover:scale-[1.02] transition-transform duration-500"
                  />
                  <span className="absolute top-3 right-3 inline-flex items-center rounded-full bg-zinc-900/80 backdrop-blur-xs px-2.5 py-0.5 text-[10px] font-medium text-white border border-zinc-800">
                    🔒 100% Discreet Shipping
                  </span>
                </div>
                <h3 className="mt-6 text-xl font-serif font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                  <span>Ladies Intimate Discreet Packaging</span>
                </h3>
                <p className="mt-3 text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed font-light">
                  To ensure complete confidentiality, all orders are shipped in plain, unmarked double-wrapped external boxes with absolutely zero branding, logos, or content descriptions on the outer surface. Inside, items are beautifully nested in white silk wrapping tissue and secured with a hand-pressed gold J.G.D wax seal sticker inside a luxury white satin drawstring pouch.
                </p>
              </div>
              <div className="mt-6 pt-6 border-t border-zinc-100 dark:border-zinc-800/80 grid grid-cols-3 gap-2 text-center">
                <div>
                  <span className="block text-xs font-mono font-bold text-zinc-900 dark:text-white">Unmarked Box</span>
                  <span className="text-[9px] text-zinc-400 uppercase tracking-wider">Plain Exterior</span>
                </div>
                <div className="border-x border-zinc-100 dark:border-zinc-800 px-2">
                  <span className="block text-xs font-mono font-bold text-zinc-900 dark:text-white">Gold Wax Seal</span>
                  <span className="text-[9px] text-zinc-400 uppercase tracking-wider">Elegant Internal</span>
                </div>
                <div>
                  <span className="block text-xs font-mono font-bold text-zinc-900 dark:text-white">Satin Pouch</span>
                  <span className="text-[9px] text-zinc-400 uppercase tracking-wider">Private Storage</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      {/* Featured Products Showcase */}
      <section className="py-16 sm:py-24">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
              <h2 className="text-3xl font-serif font-bold text-zinc-900 dark:text-white">Featured Products</h2>
              <p className="mt-1 text-xs uppercase tracking-widest text-zinc-400 font-semibold font-mono">
                Real-time stock validation and instant checkout integration
              </p>
            </div>
            <Link
              href="/category/gents-shoes"
              className="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-500 hover:opacity-80 transition-opacity"
            >
              <span>View All Catalog</span>
              <ArrowRight className="h-3.5 w-3.5" />
            </Link>
          </div>

          {products.length === 0 ? (
            <div className="mt-12 text-center py-12 rounded-xl border border-dashed border-zinc-200 dark:border-zinc-800">
              <p className="text-sm text-zinc-500">No products found in DB. Make sure you seeded the database.</p>
            </div>
          ) : (
            <div className="mt-12 grid grid-cols-1 gap-y-12 sm:grid-cols-2 lg:grid-cols-3 sm:gap-x-8">
              {products.map((product) => {
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

                return (
                  <div
                    key={product.id}
                    className="group relative flex flex-col justify-between overflow-hidden rounded-xl border border-zinc-100 bg-white p-6 dark:border-zinc-900 dark:bg-zinc-900/20 hover:shadow-xl hover:shadow-zinc-100/50 dark:hover:shadow-none transition-all duration-300"
                  >
                    <div>
                      {/* Visual Category Label */}
                      <div className="flex items-center justify-between mb-4">
                        <span className="inline-flex items-center rounded-full bg-zinc-50 px-2.5 py-0.5 text-[10px] font-medium text-zinc-600 border border-zinc-100 dark:bg-zinc-800/80 dark:border-zinc-700 dark:text-zinc-300">
                          {product.category?.name}
                        </span>
                        <div className="flex items-center gap-0.5 text-amber-500">
                          <Star className="h-3 w-3 fill-current" />
                          <span className="text-[10px] font-bold font-mono">4.9</span>
                        </div>
                      </div>

                      {/* Product Image */}
                      <div className="aspect-square w-full overflow-hidden rounded-lg bg-zinc-50 border border-zinc-100 dark:bg-zinc-900 dark:border-zinc-800 flex items-center justify-center min-h-[180px] sm:min-h-[220px] relative group-hover:opacity-95 transition-opacity">
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

                      <h3 className="mt-6 text-lg font-serif font-bold text-zinc-900 dark:text-white hover:text-amber-600 transition-colors">
                        <Link href={`/product/${product.slug}`}>
                          {product.name}
                        </Link>
                      </h3>

                      <p className="mt-2 text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2">
                        {product.description}
                      </p>
                    </div>

                    <div className="mt-6 pt-4 border-t border-zinc-100 dark:border-zinc-900/60">
                      {/* Price & Variant count info */}
                      <div className="flex items-center justify-between">
                        <div>
                          <p className="text-[9px] uppercase tracking-widest text-zinc-400 font-semibold font-mono">
                            Price Starts
                          </p>
                          <p className="text-base font-mono font-bold text-zinc-900 dark:text-white">
                            Rs. {product.base_price.toLocaleString()}
                          </p>
                        </div>
                        <div className="text-right">
                          <span className="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-medium text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                            {product.variants?.length || 0} Variants
                          </span>
                        </div>
                      </div>

                      {/* Available attributes swatches indicator */}
                      <div className="mt-4 flex flex-wrap gap-y-2 gap-x-4 justify-between items-center">
                        {colors.length > 0 && (
                          <div className="flex items-center gap-1">
                            <span className="text-[9px] uppercase text-zinc-400">Colors:</span>
                            <div className="flex gap-0.5">
                              {colors.map((c) => (
                                <span
                                  key={c}
                                  className="h-2 w-2 rounded-full border border-zinc-200 bg-zinc-200 dark:border-zinc-700"
                                  title={c}
                                />
                              ))}
                            </div>
                          </div>
                        )}
                        {sizes.length > 0 && (
                          <div className="text-[10px] text-zinc-500">
                            Sizes: <span className="font-mono">{sizes.join(', ')}</span>
                          </div>
                        )}
                      </div>

                      <Link
                        href={`/product/${product.slug}`}
                        className="mt-6 flex w-full items-center justify-center gap-2 rounded-full bg-zinc-900 py-2.5 text-xs font-semibold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 transition-colors"
                      >
                        <span>Select Options</span>
                        <ArrowRight className="h-3 w-3" />
                      </Link>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>
      </section>
      
    </div>
  );
}
