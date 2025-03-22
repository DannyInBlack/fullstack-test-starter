import React from 'react';
import styles from './ProductCard.module.css';
import Cart from '../assets/Cart.svg';
import { Link } from 'react-router-dom';
import { ProductCardProps } from '../interfaces/Product';

const ProductCard: React.FC<ProductCardProps> = ({ id, image, name, price, inStock } : ProductCardProps) => {
  return (
    <Link to={`/product/${id}`} >
      <div className={`${styles.card} ${inStock ? styles.outOfStock : ''}`}>
        <img src={image} alt={name} className={styles.image} />
        {inStock && <div className={styles.overlay}>OUT OF STOCK</div>}
        {!inStock && <button className={styles.cartButton}>
          <img src={Cart} alt="Cart" />
          </button>}
        <div className={styles.details}>
          <p className={styles.name}>{name}</p>
          <p className={styles.price}>{price}</p>
        </div>
      </div>
    </Link>
  );
};

export default ProductCard;