import React from 'react';
import ProductCard from '../components/ProductCard';
import styles from './Women.module.css';

const products = [
  { image: 'path/to/image1.jpg', name: 'Running Short', price: '$50.00' },
  { image: 'path/to/image2.jpg', name: 'Running Short', price: '$50.00' },
  { image: 'path/to/image3.jpg', name: 'Running Short', price: '$50.00', outOfStock: true },
  // Add more products as needed
];

const Women: React.FC = () => {
  return (
    <div className={styles.container}>
      <h1 className={styles.title}>Women</h1>
      <div className={styles.grid}>
        {products.map((product, index) => (
          <ProductCard
            key={index}
            image={product.image}
            name={product.name}
            price={product.price}
            outOfStock={product.outOfStock}
          />
        ))}
      </div>
    </div>
  );
};

export default Women;