import React from 'react';
import styles from './CartOverlay.module.css';
import { useCart } from '../context/CartContext'; // Import useCart
import CartItem from './CartItem';
import { gql, useMutation } from '@apollo/client';

const ADD_ORDER = gql(`
  mutation addOrder($items: [OrderItemInput!]!) {
    addOrder(items: $items)
  }
`);

const CartOverlay: React.FC = () => {
  const { cartOpen, setCartOpen, cartItems, setCartItems, } = useCart(); // Use useCart hook

  const [addOrder] = useMutation(ADD_ORDER);

  const handleOrderCreation = async () => {
    try {
      await addOrder({
        variables: {
          items: cartItems.map((item) => ({
            productId: item.product.id,
            quantity: item.quantity,
            attributeIds: Object.entries(item.selectedAttributes).map(
              ([key, value]) => ([key, value])
            )
          }))
        }
      });
    } catch (error) {
      console.log(error);
    }
  };

  const handleQuantityChange = (index: number, delta: number) => {
    const updatedCart = [...cartItems];
    updatedCart[index].quantity += delta;
    if (updatedCart[index].quantity === 0) {
      updatedCart.splice(index, 1);
    }
    setCartItems(updatedCart);
  };

  const totalPrice = cartItems.reduce(
    (sum, item) => sum + item.quantity * item.product.prices[0].amount,
    0
  );

  const totalItems = cartItems.reduce(
    (sum, item) => sum + item.quantity,
    0
  );

  return (
    <div className={styles.overlay}>
      <div className={styles.cart}>
        <h2 data-testid='cart-item-amount'>My Bag, {cartItems.length > 0 && `${totalItems} ${totalItems > 1? "Items" : "Item"}` }</h2>
        <div className={styles.items}>
          {cartItems.map((item, index) => (
            <CartItem 
              key={JSON.stringify(item.product.id) + JSON.stringify(item.selectedAttributes)}
              orderItem={item} 
              onDecrease={() => handleQuantityChange(index, -1)} 
              onIncrease={() => handleQuantityChange(index, 1)}
            />
          ))}
        </div>
        <div className={styles.total}>
          <h3>Total:</h3>
          <p data-testid='cart-total'>{cartItems[0]?.product.prices[0].currency.label || '$'}{totalPrice.toFixed(2)}</p>
        </div>
        <button
          className={styles.placeOrder}
          onClick={() => {
            setCartItems([]);
            handleOrderCreation();
          }}
          disabled={totalItems === 0}
        >
          PLACE ORDER
        </button>
      </div>
      <div style={{display: cartOpen? "hidden" : ""}} className={styles.backdrop} onClick={() => setCartOpen(false)}></div>
    </div>
  );
};

export default CartOverlay;
