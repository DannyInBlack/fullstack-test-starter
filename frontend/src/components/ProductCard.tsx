import React from 'react';
import styles from './ProductCard.module.css';
import Cart from '../assets/CartWhite.svg';
import { Link } from 'react-router-dom';
import { Product } from '../interfaces/Product';
import { useCart } from '../context/CartContext';
import { OrderItem } from '../interfaces/Order';

const ProductCard: React.FC<{product: Product}> = ({ product }) => {

  const { addItem } = useCart();

  const handleCartButtonClick = (e: React.MouseEvent, product: Product) => {
    e.preventDefault();
    e.stopPropagation();

    addItem({ 
      product: product,
      quantity: 1, 
      // Convert the attributes array to an object with the attribute id as the key
      selectedAttributes: product.attributes.reduce((acc, attribute) => {
        acc[attribute.id] = attribute.items[0].id;
        return acc;
      }, {} as Record<string, any>)
    } as OrderItem);
  }

  return (
    <Link className={styles.link} data-testid={`product-${product.id}`} to={`/product/${product.id}`} >
      <div className={`${styles.card} ${product.inStock ? '': styles.outOfStock }`}>
        <img src={product.gallery[0]} alt={"Product image"} className={styles.image} />
        {/* Handle out of stock products - very simple logic tbh */}
        {!product.inStock && <div className={styles.overlay}>OUT OF STOCK</div>}
        {product.inStock && 
          <button onClick={(e) => handleCartButtonClick(e, product)} className={styles.cartButton}>
            <img className={styles.cartIcon} src={Cart} alt="Cart" />
          </button>}
        <div className={styles.details}>
          <p className={styles.name}>{product.name}</p>
          <p className={styles.price}>{product.prices[0].currency.symbol}{product.prices[0].amount.toFixed(2)}</p>
        </div>
      </div>
    </Link>
  );
};

export default ProductCard;