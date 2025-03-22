import React from 'react';
import ProductCard from '../components/ProductCard';
import styles from './ProductDisplay.module.css';
import { useSearchParams } from 'react-router-dom';

const products = [
  { image: 'path/to/image1.jpg', name: 'Running Short', price: 50.00 },
  { image: 'path/to/image2.jpg', name: 'Running Short', price: 50.00 },
  { image: 'path/to/image3.jpg', name: 'Running Short', price: 50.00, inStock: true },
  // Add more products as needed
];

const ProductDisplay: React.FC = () => {

  let [searchParams] = useSearchParams();
  console.log(searchParams.get('category'))

  return (
    <div className={styles.container}>
      <h1 className={styles.title}>Women</h1>
      <div className={styles.grid}>
        {products.map((product, index) => (
          <ProductCard
            id=''
            key={index}
            image={product.image}
            name={product.name}
            price={product.price}
            inStock={product.inStock || false}
          />
        ))}
      </div>
    </div>
  );
};

export default ProductDisplay;