import React from 'react';
import ProductCard from '../components/ProductCard';
import styles from './ProductDisplay.module.css';
import { useSearchParams } from 'react-router-dom';
import { gql, useQuery } from '@apollo/client';
import { Product } from '../interfaces/Product';

const ProductDisplay: React.FC = () => {

  let [searchParams] = useSearchParams();
  const [products, setProducts] = React.useState<Product[]>([]);

  const fetchProducts = async () => {
    let category = searchParams.get('category');
    if (!category || category === 'all')
      category = null;

    const GET_PRODUCTS = gql`
      query GetProducts {
        products {
          id
          name
          price
          inStock
          image
        }
      }
    `;
    const { data } = useQuery(GET_PRODUCTS);

    setProducts(data.products);
  }

  return (
    <div className={styles.container}>
      <h1 className={styles.title}>Women</h1>
      <div className={styles.grid}>
        {products.map((product, index) => (
          <ProductCard
            id=''
            key={index}
            image={product.gallery[0]}
            name={product.name}
            price={product.prices[0].amount}
            inStock={product.inStock || false}
          />
        ))}
      </div>
    </div>
  );
};

export default ProductDisplay;