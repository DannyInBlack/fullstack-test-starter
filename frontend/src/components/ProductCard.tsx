import React from 'react';
import styles from './ProductCard.module.css';

interface ProductCardProps {
  image: string;
  name: string;
  price: string;
  outOfStock?: boolean;
}

const ProductCard: React.FC<ProductCardProps> = ({ image, name, price, outOfStock }) => {
  return (
    <div className={`${styles.card} ${outOfStock ? styles.outOfStock : ''}`}>
      <img src={image} alt={name} className={styles.image} />
      {outOfStock && <div className={styles.overlay}>OUT OF STOCK</div>}
      <div className={styles.details}>
        <h3 className={styles.name}>{name}</h3>
        <p className={styles.price}>{price}</p>
      </div>
    </div>
  );
};

export default ProductCard;