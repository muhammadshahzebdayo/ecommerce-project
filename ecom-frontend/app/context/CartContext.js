'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';

const CartContext = createContext();

export function CartProvider({ children }) {
  const [cartItems, setCartItems] = useState([]);
  const [isCartOpen, setIsCartOpen] = useState(false);
  const [isHydrated, setIsHydrated] = useState(false);

  // Load cart items from localStorage on mount
  useEffect(() => {
    const savedCart = localStorage.getItem('ecom_cart');
    if (savedCart) {
      try {
        setCartItems(JSON.parse(savedCart));
      } catch (e) {
        console.error('Failed to parse cart storage:', e);
      }
    }
    setIsHydrated(true);
  }, []);

  // Save cart items to localStorage on change
  useEffect(() => {
    if (isHydrated) {
      localStorage.setItem('ecom_cart', JSON.stringify(cartItems));
    }
  }, [cartItems, isHydrated]);

  const addToCart = (item) => {
    setCartItems((prevItems) => {
      // Find item with same variant ID
      const existingItemIndex = prevItems.findIndex(
        (i) => i.variant_id === item.variant_id
      );

      if (existingItemIndex > -1) {
        const newItems = [...prevItems];
        const currentQty = newItems[existingItemIndex].quantity;
        const newQty = currentQty + item.quantity;
        
        // Cap quantity to stock limit if available
        const maxStock = item.maxStock ?? 99;
        newItems[existingItemIndex].quantity = Math.min(newQty, maxStock);
        
        return newItems;
      } else {
        return [...prevItems, item];
      }
    });
    setIsCartOpen(true); // Open drawer on add
  };

  const removeFromCart = (variantId) => {
    setCartItems((prevItems) =>
      prevItems.filter((item) => item.variant_id !== variantId)
    );
  };

  const updateQuantity = (variantId, quantity) => {
    if (quantity <= 0) {
      removeFromCart(variantId);
      return;
    }
    
    setCartItems((prevItems) =>
      prevItems.map((item) => {
        if (item.variant_id === variantId) {
          const maxStock = item.maxStock ?? 99;
          return { ...item, quantity: Math.min(quantity, maxStock) };
        }
        return item;
      })
    );
  };

  const clearCart = () => {
    setCartItems([]);
  };

  const cartCount = cartItems.reduce((acc, item) => acc + item.quantity, 0);
  
  const cartSubtotal = cartItems.reduce(
    (acc, item) => acc + item.price * item.quantity,
    0
  );

  return (
    <CartContext.Provider
      value={{
        cartItems,
        isHydrated,
        isCartOpen,
        setIsCartOpen,
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart,
        cartCount,
        cartSubtotal,
      }}
    >
      {children}
    </CartContext.Provider>
  );
}

export function useCart() {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
}
