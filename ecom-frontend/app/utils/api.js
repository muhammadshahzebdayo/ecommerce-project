const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000/api';

/**
 * Fetch all categories for navigation.
 */
export async function getCategories() {
  try {
    const res = await fetch(`${API_BASE_URL}/categories`, {
      cache: 'no-store', // ensures fresh categories
    });
    if (!res.ok) throw new Error('Failed to fetch categories');
    return await res.json();
  } catch (error) {
    console.error('Error fetching categories:', error);
    return [];
  }
}

/**
 * Fetch products list with filtering.
 * @param {Object} filters - Filtering options (category, size, color, min_price, max_price, page)
 */
export async function getProducts(filters = {}) {
  try {
    const params = new URLSearchParams();
    
    if (filters.category) params.append('category', filters.category);
    if (filters.min_price) params.append('min_price', filters.min_price);
    if (filters.max_price) params.append('max_price', filters.max_price);
    if (filters.page) params.append('page', filters.page);
    
    if (filters.size) {
      if (Array.isArray(filters.size)) {
        params.append('size', filters.size.join(','));
      } else {
        params.append('size', filters.size);
      }
    }
    
    if (filters.color) {
      if (Array.isArray(filters.color)) {
        params.append('color', filters.color.join(','));
      } else {
        params.append('color', filters.color);
      }
    }

    const url = `${API_BASE_URL}/products?${params.toString()}`;
    const res = await fetch(url, { cache: 'no-store' });
    if (!res.ok) throw new Error('Failed to fetch products');
    return await res.json();
  } catch (error) {
    console.error('Error fetching products:', error);
    return { data: [], current_page: 1, last_page: 1 };
  }
}

/**
 * Fetch single product details by slug.
 * @param {string} slug
 */
export async function getProductBySlug(slug) {
  try {
    const res = await fetch(`${API_BASE_URL}/products/${slug}`, {
      cache: 'no-store',
    });
    if (!res.ok) throw new Error('Failed to fetch product details');
    return await res.json();
  } catch (error) {
    console.error(`Error fetching product details for ${slug}:`, error);
    return null;
  }
}

/**
 * Submit checkout request.
 * @param {Object} orderData - Order data including customer info and items
 */
export async function checkout(orderData) {
  try {
    const res = await fetch(`${API_BASE_URL}/checkout`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(orderData),
    });
    const result = await res.json();
    return result;
  } catch (error) {
    console.error('Checkout error:', error);
    return {
      success: false,
      message: 'Network error occurred. Please try again.',
    };
  }
}
