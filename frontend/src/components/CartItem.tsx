import React from 'react';
import styles from './CartItem.module.css';
import { OrderItem } from '../interfaces/Order';
import Attribute from './Attribute';

export interface CartItemProps {
  orderItem: OrderItem;
  onIncrease: () => void;
  onDecrease: () => void;
}

const CartItem: React.FC<CartItemProps> = ({
  orderItem,
  onIncrease,
  onDecrease,
}) => {

  const { product, quantity, selectedAttributes } = orderItem;
  const { name, prices, gallery, attributes } = product;
  const { amount, currency } = prices[0];
  const { symbol } = currency;
  const image = gallery[0];

  return (
    <div className={styles.item}>
      <div className={styles.details}>
        <h2 className={styles.name}>{name}</h2>
        <p className={styles.price}>
          {symbol}
          {amount.toFixed(2)}
        </p>
        <div className={styles.attributes}>
          {attributes.map((attr) => (
            <Attribute 
              cart 
              key={attr.name} 
              attr={attr} 
              orderItem={orderItem} 
              selected={selectedAttributes[attr.id]}
            />
          ))}
        </div>
      </div>
      <div className={styles.controls}>
        <button data-testid='cart-item-amount-increase' className={styles.increase} onClick={onIncrease}>
          +
        </button>
        <span data-testid='cart-item-amount' className={styles.quantity}>{quantity}</span>
        <button data-testid='cart-item-amount-decrease' className={styles.decrease} onClick={onDecrease}>
          -
        </button>
      </div>
      <div className={styles.image}>
        <img src={image} alt={name} />
      </div>
    </div>
  );
};

export default CartItem;