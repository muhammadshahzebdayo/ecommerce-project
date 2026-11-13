import React from 'react';
import { getProductBySlug } from '../../utils/api';
import ProductDetails from './ProductDetails';
import Link from 'next/link';
import { ArrowLeft } from 'lucide-react';

export async function generateMetadata({ params }) {
  const resolvedParams = await params;
  const product = await getProductBySlug(resolvedParams.slug);
  
  if (!product) {
    return {
      title: 'Product Not Found',
    };
  }

  return {
    title: `${product.name} - J.G.D Couture`,
    description: product.description,
  };
}

export default async function ProductPage({ params }) {
  const resolvedParams = await params;
  const product = await getProductBySlug(resolvedParams.slug);

  if (!product) {
    return (
      <div className="mx-auto max-w-7xl px-4 py-24 text-center sm:px-6 lg:px-8 flex-1 flex flex-col items-center justify-center">
        <h1 className="text-2xl font-serif font-bold text-zinc-900 dark:text-white">Product Not Found</h1>
        <p className="mt-2 text-sm text-zinc-500">The product you are looking for does not exist or has been removed.</p>
        <Link
          href="/"
          className="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold uppercase tracking-wider text-amber-600 hover:opacity-80"
        >
          <ArrowLeft className="h-4 w-4" />
          <span>Go back home</span>
        </Link>
      </div>
    );
  }

  return <ProductDetails product={product} />;
}
