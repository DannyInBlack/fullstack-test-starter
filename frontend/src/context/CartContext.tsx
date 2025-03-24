import React, { createContext, useContext, useState } from 'react';
import { OrderItem } from '../interfaces/Order';

interface CartContextProps {
  cartItems: OrderItem[];
  setCartItems: (items: OrderItem[]) => void;
  cartOpen: boolean;
  setCartOpen: (open: boolean) => void;
  addItem: (item: OrderItem) => void;
  removeItem: (index: number) => void;
  clearCart: () => void;
}

const CartContext = createContext<CartContextProps | undefined>(undefined);

export const CartProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [cartItems, setCartItems] = useState<OrderItem[]>([]);
  const [cartOpen, setCartOpen] = useState(false);

  const addItem = (item: OrderItem) => {
    setCartItems((prevItems) => {
      const itemIndex = prevItems.findIndex(
        (prevItem) =>
          prevItem.product.id === item.product.id &&
          JSON.stringify(prevItem.selectedAttributes) === JSON.stringify(item.selectedAttributes)
      );

      if (itemIndex !== -1) {
        const newItems = [...prevItems];
        newItems[itemIndex].quantity += 1;
        return newItems;
      } else {
        return [...prevItems, item];
      }
    });
  };

  const removeItem = (index: number) => {
    setCartItems((prevItems) => {
      const newItems = [...prevItems];
      newItems.splice(index, 1);
      return newItems;
    });
  };

  const clearCart = () => {
    setCartItems([]);
  };

  return (
    <CartContext.Provider value={{ cartItems, setCartItems, cartOpen, setCartOpen, addItem, removeItem, clearCart }}>
      {children}
    </CartContext.Provider>
  );
};

export const useCart = (): CartContextProps => {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
};
