import React from 'react';
import Link from 'next/link';
import { getProducts, getCategories } from '../../utils/api';
import { Star, SlidersHorizontal, ArrowLeft, ShoppingBag } from 'lucide-react';

export default async function CategoryPage({ params, searchParams }) {
  // Await params and searchParams for Next.js App Router compliance
  const resolvedParams = await params;
  const resolvedSearchParams = await searchParams;
  const slug = resolvedParams.slug;

  const currentCategorySlug = slug;
  
  // Format filters for API
  const filters = {
    category: currentCategorySlug,
    min_price: resolvedSearchParams.min_price || '',
    max_price: resolvedSearchParams.max_price || '',
    size: resolvedSearchParams.size || '',
    color: resolvedSearchParams.color || '',
    page: resolvedSearchParams.page || 1,
  };

  const [productsData, categories] = await Promise.all([
    getProducts(filters),
    getCategories(),
  ]);

  const products = productsData.data || [];
  
  // Resolve current category hierarchy
  let currentCategory = null;
  
  // Find current category (it could be a parent category or a subcategory)
  for (const cat of categories) {
    if (cat.slug === currentCategorySlug) {
      currentCategory = cat;
      break;
    }
    if (cat.subcategories) {
      const sub = cat.subcategories.find((s) => s.slug === currentCategorySlug);
      if (sub) {
        currentCategory = sub;
        break;
      }
    }
  }

  const categoryTitle = currentCategory ? currentCategory.name : 'Category Catalog';

  // Resolve subcategories list (if parent, show children; if child, show parent's children/siblings)
  let subcategories = [];
  if (currentCategory) {
    if (currentCategory.parent_id === null) {
      subcategories = currentCategory.subcategories || [];
    } else {
      const parentCategory = categories.find((cat) => cat.id === currentCategory.parent_id);
      subcategories = parentCategory ? parentCategory.subcategories : [];
    }
  }

  // Define static helper filters based on category types to keep swatches accurate
  const isShoes = currentCategorySlug.includes('shoes');
  const availableSizes = isShoes 
    ? ['40', '41', '42', '43', '44', '45'] 
    : ['32A', '34B', '36B', '36C', '38C', '40D', 'M', 'L', 'XL'];
  
  const availableColors = isShoes
    ? ['Black', 'Brown', 'Tan', 'Cherry']
    : ['White', 'Black', 'Nude', 'Pink', 'Red'];

  // Helper function to build query URL
  function getFilterUrl(newFilters) {
    const updated = { ...resolvedSearchParams, ...newFilters };
    const params = new URLSearchParams();
    
    Object.entries(updated).forEach(([key, val]) => {
      if (val) params.append(key, val);
    });
    
    return `/category/${currentCategorySlug}?${params.toString()}`;
  }

  // Parse active filter arrays
  const activeSizes = resolvedSearchParams.size ? resolvedSearchParams.size.split(',') : [];
  const activeColors = resolvedSearchParams.color ? resolvedSearchParams.color.split(',') : [];

  return (
    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 flex-1 w-full">
      
      {/* Breadcrumb / Top Bar */}
      <div className="flex flex-col gap-3 border-b border-zinc-100 pb-5 dark:border-zinc-900 transition-colors">
        <Link
          href="/"
          className="inline-flex items-center gap-1.5 text-xs text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors font-medium"
        >
          <ArrowLeft className="h-3.5 w-3.5" />
          <span>Back to Home</span>
        </Link>
        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <h1 className="text-2xl sm:text-3xl font-serif font-extrabold tracking-tight text-zinc-900 dark:text-white">
              {categoryTitle}
            </h1>
            <p className="mt-1 text-[10px] text-zinc-400 font-mono">
              Category SKU Prefix: <code className="bg-zinc-50 dark:bg-zinc-950 px-1 py-0.5 rounded font-bold text-amber-700 dark:text-amber-500">{currentCategory?.sku_prefix || 'N/A'}</code>
            </p>
          </div>
          <div className="text-xs text-zinc-500 font-mono font-medium">
            Showing {products.length} Products
          </div>
        </div>
      </div>

      {/* Subcategories Circular Avatar Row (Centered) */}
      {subcategories && subcategories.length > 0 && (
        <div className="mt-8 flex flex-wrap gap-8 items-center justify-center border-b border-zinc-100 pb-8 dark:border-zinc-900 transition-colors">
          {subcategories.map((sub) => {
            // Determine image mapping for subcategories
            let subImg = '/hero_fashion.png';
            if (sub.slug === 'lace-bras') subImg = '/female_bra.png';
            if (sub.slug === 'nighty-loungewear') subImg = '/nighty_set.png';
            
            const isSubActive = currentCategorySlug === sub.slug;
            
            return (
              <Link
                key={sub.id}
                href={`/category/${sub.slug}`}
                className="flex flex-col items-center gap-2 group text-center"
              >
                <div className={`w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden border-2 transition-all duration-300 relative bg-zinc-50 dark:bg-zinc-900 ${
                  isSubActive 
                    ? 'border-amber-600 ring-2 ring-amber-600/20 scale-105 shadow-md' 
                    : 'border-zinc-200 dark:border-zinc-800 group-hover:border-amber-600'
                }`}>
                  <img
                    src={subImg}
                    alt={sub.name}
                    className="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                  />
                </div>
                <span className={`text-[9px] font-bold tracking-wider uppercase transition-colors max-w-[85px] leading-tight ${
                  isSubActive
                    ? 'text-amber-600 dark:text-amber-500 font-extrabold'
                    : 'text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white'
                }`}>
                  {sub.name}
                </span>
              </Link>
            );
          })}
        </div>
      )}

      {/* Main Grid: Sidebar + Product Grid */}
      <div className="mt-8 lg:grid lg:grid-cols-4 lg:gap-8">
        
        {/* Sidebar Filters */}
        <aside className="lg:col-span-1 space-y-8 border-b lg:border-b-0 lg:border-r border-zinc-100 lg:pr-8 pb-8 lg:pb-0 dark:border-zinc-900">
          
          <div className="flex items-center justify-between">
            <h2 className="text-sm font-semibold uppercase tracking-wider text-zinc-900 dark:text-white flex items-center gap-2">
              <SlidersHorizontal className="h-4 w-4" />
              <span>Filters</span>
            </h2>
            <Link
              href={`/category/${currentCategorySlug}`}
              className="text-[11px] font-semibold text-amber-600 hover:text-amber-500 uppercase tracking-widest"
            >
              Clear All
            </Link>
          </div>

          {/* Price Range Filter */}
          <div className="space-y-3 pt-6 border-t border-zinc-100 dark:border-zinc-900/60">
            <h3 className="text-xs font-semibold text-zinc-900 dark:text-white uppercase tracking-wider">
              Price Range (Rs.)
            </h3>
            <div className="flex items-center gap-2">
              <form action="" className="flex items-center gap-2 w-full">
                <input
                  type="number"
                  name="min_price"
                  placeholder="Min"
                  defaultValue={resolvedSearchParams.min_price || ''}
                  className="w-full rounded bg-zinc-50 border border-zinc-200 py-1 px-2.5 text-xs outline-none focus:border-zinc-400 dark:bg-zinc-900 dark:border-zinc-800 dark:focus:border-zinc-700 font-mono"
                />
                <span className="text-zinc-400 text-xs">-</span>
                <input
                  type="number"
                  name="max_price"
                  placeholder="Max"
                  defaultValue={resolvedSearchParams.max_price || ''}
                  className="w-full rounded bg-zinc-50 border border-zinc-200 py-1 px-2.5 text-xs outline-none focus:border-zinc-400 dark:bg-zinc-900 dark:border-zinc-800 dark:focus:border-zinc-700 font-mono"
                />
                <button
                  type="submit"
                  className="hidden"
                />
              </form>
            </div>
            
            <div className="flex gap-2">
              <Link
                href={getFilterUrl({
                  min_price: '2000',
                  max_price: '6000',
                })}
                className="text-[10px] text-zinc-500 hover:text-zinc-900 dark:hover:text-white underline block pt-1"
              >
                Apply Range 2k - 6k
              </Link>
            </div>
          </div>

          {/* Size Filter */}
          <div className="space-y-3 pt-6 border-t border-zinc-100 dark:border-zinc-900/60">
            <h3 className="text-xs font-semibold text-zinc-900 dark:text-white uppercase tracking-wider">
              Filter by Size
            </h3>
            <div className="flex flex-wrap gap-1.5">
              {availableSizes.map((size) => {
                const isSelected = activeSizes.includes(size);
                const nextSizes = isSelected
                  ? activeSizes.filter((s) => s !== size)
                  : [...activeSizes, size];
                
                return (
                  <Link
                    key={size}
                    href={getFilterUrl({ size: nextSizes.join(',') })}
                    className={`inline-flex h-8 min-w-[32px] items-center justify-center rounded px-2.5 text-xs font-mono font-bold transition-all border ${
                      isSelected
                        ? 'bg-zinc-900 text-white border-zinc-900 dark:bg-white dark:text-zinc-950 dark:border-white shadow-sm'
                        : 'bg-white text-zinc-700 border-zinc-200 hover:border-zinc-400 dark:bg-zinc-900 dark:text-zinc-300 dark:border-zinc-800 dark:hover:border-zinc-700'
                    }`}
                  >
                    {size}
                  </Link>
                );
              })}
            </div>
          </div>

          {/* Color Filter */}
          <div className="space-y-3 pt-6 border-t border-zinc-100 dark:border-zinc-900/60">
            <h3 className="text-xs font-semibold text-zinc-900 dark:text-white uppercase tracking-wider">
              Filter by Color
            </h3>
            <div className="flex flex-wrap gap-2">
              {availableColors.map((color) => {
                const isSelected = activeColors.includes(color);
                const nextColors = isSelected
                  ? activeColors.filter((c) => c !== color)
                  : [...activeColors, color];

                return (
                  <Link
                    key={color}
                    href={getFilterUrl({ color: nextColors.join(',') })}
                    className={`inline-flex items-center justify-center rounded-full px-3 py-1 text-xs transition-all border ${
                      isSelected
                        ? 'bg-amber-600 text-white border-amber-600 font-semibold'
                        : 'bg-zinc-50 text-zinc-700 border-zinc-200 hover:border-zinc-300 dark:bg-zinc-900 dark:text-zinc-300 dark:border-zinc-800'
                    }`}
                  >
                    {color}
                  </Link>
                );
              })}
            </div>
          </div>

        </aside>

        {/* Product Grid */}
        <main className="lg:col-span-3 mt-8 lg:mt-0">
          {products.length === 0 ? (
            <div className="flex flex-col items-center justify-center text-center py-20 rounded-xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-white/50 dark:bg-zinc-900/10">
              <ShoppingBag className="h-12 w-12 text-zinc-300 dark:text-zinc-700 stroke-[1.2]" />
              <h3 className="mt-4 text-sm font-semibold text-zinc-950 dark:text-white">No products match filters</h3>
              <p className="mt-1 text-xs text-zinc-500">Try adjusting your pricing range or size/color swatches.</p>
              <Link
                href={`/category/${currentCategorySlug}`}
                className="mt-6 rounded-full bg-zinc-900 px-6 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-100 transition-colors"
              >
                Reset Filters
              </Link>
            </div>
          ) : (
            <div className="grid grid-cols-1 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 gap-x-6">
              {products.map((product) => {
                const sizes = Array.from(
                  new Set(
                    product.variants?.flatMap((v) =>
                      v.option_values
                        ?.filter((ov) => ov.attribute?.name === 'Size')
                        .map((ov) => ov.value)
                    ) || []
                  )
                );

                const colors = Array.from(
                  new Set(
                    product.variants?.flatMap((v) =>
                      v.option_values
                        ?.filter((ov) => ov.attribute?.name === 'Color')
                        .map((ov) => ov.value)
                    ) || []
                  )
                );

                return (
                  <div
                    key={product.id}
                    className="group relative flex flex-col justify-between overflow-hidden rounded-xl border border-zinc-100 bg-white p-5 dark:border-zinc-900 dark:bg-zinc-900/20 hover:shadow-xl hover:shadow-zinc-100/50 dark:hover:shadow-none transition-all duration-300"
                  >
                    <div>
                      {/* Rating details & Status */}
                      <div className="flex items-center justify-between mb-4">
                        <span className="inline-flex items-center rounded bg-amber-50 px-2 py-0.5 text-[9px] font-mono font-bold text-amber-700 dark:bg-amber-950/20 dark:text-amber-400">
                          {product.variants?.length || 0} Variants
                        </span>
                        <div className="flex items-center gap-0.5 text-amber-500">
                          <Star className="h-3 w-3 fill-current" />
                          <span className="text-[10px] font-bold font-mono">4.9</span>
                        </div>
                      </div>

                      {/* Product Image */}
                      <div className="aspect-square w-full overflow-hidden rounded-lg bg-zinc-50 border border-zinc-100 dark:bg-zinc-900 dark:border-zinc-800 flex items-center justify-center min-h-[160px] sm:min-h-[200px] relative group-hover:opacity-95 transition-opacity">
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

                      <h3 className="mt-4 text-base font-serif font-bold text-zinc-900 dark:text-white hover:text-amber-600 transition-colors">
                        <Link href={`/product/${product.slug}`}>
                          {product.name}
                        </Link>
                      </h3>

                      <p className="mt-1.5 text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2">
                        {product.description}
                      </p>
                    </div>

                    <div className="mt-6 pt-4 border-t border-zinc-100 dark:border-zinc-900/60">
                      <div className="flex items-center justify-between">
                        <div>
                          <p className="text-[9px] uppercase tracking-widest text-zinc-400 font-semibold font-mono">
                            Base Price
                          </p>
                          <p className="text-base font-mono font-bold text-zinc-900 dark:text-white">
                            Rs. {product.base_price.toLocaleString()}
                          </p>
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
                            Sizes: <span className="font-mono text-[10px] font-bold">{sizes.join(', ')}</span>
                          </div>
                        )}
                      </div>

                      <Link
                        href={`/product/${product.slug}`}
                        className="mt-6 flex w-full items-center justify-center gap-2 rounded-full bg-zinc-900 py-2.5 text-xs font-semibold uppercase tracking-wider text-white hover:bg-zinc-800 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 transition-colors"
                      >
                        <span>Select Options</span>
                      </Link>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </main>

      </div>
    </div>
  );
}
