import React from 'react';
import { AttributeSet } from '../interfaces/Product';
import styles from './Attribute.module.css';
import { OrderItem } from '../interfaces/Order';
import { toKebabCase } from '../utils';

const Attribute: React.FC<{ 
  attr: AttributeSet, 
  orderItem: OrderItem, 
  setItem?: (orderItem: OrderItem) => void 
  cart: boolean
  selected?: string
}> = ({ attr, orderItem, setItem, cart, selected }) => {

  const swatch = attr.type === 'swatch';

  return (
    <div data-testid={`${cart? "cart-item" : "product"}-attribute-${toKebabCase(attr.name)}`} key={attr.name} className={`${styles.attribute} ${cart? styles.cart: ''}`}>
      <h3>{attr.name.toUpperCase()}:</h3>
      <div className={`${styles.options} ${cart? styles.cart : ''}`}>
        {attr.items.map(item => { 
          console.log(item.value);
          return(
          <button 
            key={item.id} 
            className={`${swatch? styles.colorOption : styles.option} ${cart? styles.cart : ''} ${orderItem.selectedAttributes[attr.name] === item.id || selected === item.id? styles.active : ''}`} 
            data-testid={`${cart? "cart-item" : "product"}-attribute-${toKebabCase(attr.name)}-${item.value}${orderItem.selectedAttributes[attr.name] === item.id || selected === item.id? '-selected': ''}`}
            style={{ backgroundColor: swatch? item.displayValue : '' }}
            onClick={setItem? () => {
              setItem({ 
                ...orderItem, 
                selectedAttributes: { 
                  ...orderItem.selectedAttributes, 
                  [attr.name]: item.id 
                } 
              });
            } : undefined} 
            >
            {!swatch && (cart? item.value : item.displayValue)}
          </button>
        )})}
      </div>
    </div>
  );
};

export default Attribute;