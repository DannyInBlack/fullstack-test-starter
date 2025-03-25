import React, { useEffect } from 'react';
import ProductCard from '../components/ProductCard';
import styles from './ProductDisplay.module.css';
import { gql, useQuery } from '@apollo/client';
import { Product } from '../interfaces/Product';
import { useParams } from 'react-router-dom';

const GET_PRODUCTS = gql`
  query GetProducts($category: String) {
    products(category: $category) {
      id
      name
      attributes {
        id
        name
        type
        items {
          id
          value
          displayValue
        }
      }
      prices {
        amount
        currency {
          symbol
        }
      }
      inStock
      gallery
    }
  }
`;


const ProductDisplay: React.FC = () => {

  const [products, setProducts] = React.useState<Product[]>([]);
  const [hasMounted, setHasMounted] = React.useState(false);
  const { category } = useParams()

  // Track if the component has mounted
  useEffect(() => {
    setHasMounted(true);
  }, []);

  useQuery<{ products: Product[] }>(GET_PRODUCTS, {
    variables: { category: category === "all"? null : category },
    skip: !hasMounted,
    onCompleted: (data) => {
      setProducts(data.products);
    },
  });

  return (
    <div className={styles.container}>
      <h1 className={styles.title}>{category || "All"}</h1>
      <div className={styles.grid}>
        {products.map(product => (
          <ProductCard key={product.id} product={product}/>
        ))}
      </div>
    </div>
  );
};

export default ProductDisplay;