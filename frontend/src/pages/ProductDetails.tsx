import React, { useEffect, useState } from 'react';
import styles from './ProductDetails.module.css';
import { useParams } from 'react-router-dom';
import { Product } from '../interfaces/Product';
import { gql, useQuery } from '@apollo/client';
import parse from 'html-react-parser';
import Attribute from '../components/Attribute';
import Next from '../assets/Next.svg';
import Previous from '../assets/Previous.svg';
import { OrderItem } from '../interfaces/Order';
import { useCart } from '../context/CartContext'; // Import useCart
import { toKebabCase } from '../utils';

const ProductDetails: React.FC = () => {
  const { cartItems, setCartItems, setCartOpen } = useCart(); // Use useCart hook

  const GET_PRODUCT = gql`
    query Product($id: ID!) {
      product(id: $id) {
        id
        name
        gallery
        description
        inStock
        prices {
          currency {
            label
            symbol
          }
          amount
        }
        attributes {
          name
          type
          items {
            id
            value
            displayValue
          }
        }
      }
    }
  `;

  const [product, setProduct] = useState<Product>({
    id: '',
    name: '',
    gallery: [],
    description: '',
    inStock: false,
    brand: '',
    category: '',
    prices: [{ currency: { label: "$", symbol: '' }, amount: 0 }],
    attributes: []
  });

  const [item, setItem] = useState<OrderItem>({
    product: {} as Product,
    quantity: 1,
    selectedAttributes: {}
  });

  const [selectedImage, setSelectedImage] = useState(0);

  const { id } = useParams<{ id: string }>();

  const [configured, setConfigured] = useState(false);

  useQuery<{ product: Product }>(GET_PRODUCT, {
    variables: { id },
    onCompleted: (data) => {
      data.product && setProduct(data.product);
    }
  });

  const addToCart = () => {
    const existingItemIndex = cartItems.findIndex(
      (cartItem) =>
        cartItem.product.id === item.product.id &&
        JSON.stringify(cartItem.selectedAttributes) === JSON.stringify(item.selectedAttributes)
    );

    if (existingItemIndex > -1) {
      const updatedCart = [...cartItems];
      updatedCart[existingItemIndex].quantity += item.quantity;
      setCartItems(updatedCart);
    } else {
      setCartItems([...cartItems, { ...item }]);
    }

    setCartOpen(true);
  };

  useEffect(() => {
    setSelectedImage(0);
  }
    , [product.gallery]);

  useEffect(() => {
    setItem({
      product: product,
      quantity: 1,
      selectedAttributes: product.attributes.reduce((acc, attr) => {
        acc[attr.name] = '';
        return acc;
      }, {} as { [key: string]: string })
    });
  }
    , [product]);

  useEffect(() => {
    setConfigured(Object.values(item.selectedAttributes).every(value => value !== ''));
  }
    , [item.selectedAttributes]);

  return (
    <div className={styles.container}>
      <div data-testid='product-gallery' className={styles.gallery}>
        {product.gallery.map((image, index) => (
          <img
            key={index}
            src={image}
            alt={`Product thumbnail ${index + 1}`}
            className={`${styles.thumbnail} ${product.gallery[selectedImage] === image ? styles.active : ''}`}
            onClick={() => setSelectedImage(index)}
          />
        ))}
      </div>
      <div className={styles.mainImageContainer}>
        <button onClick={() => setSelectedImage((selectedImage - 1 + product.gallery.length) % product.gallery.length)} className={`${styles.imageButton} ${styles.previousButton}`}>
          <img src={Previous} />
        </button>
        <img src={product.gallery[selectedImage]} alt="Selected product" className={styles.mainImage} />
        <button onClick={() => setSelectedImage((selectedImage + 1) % product.gallery.length)} className={`${styles.imageButton} ${styles.nextButton}`}>
          <img src={Next} />
        </button>
      </div>
      <div className={styles.details}>
        <h1 className={styles.title}>{product.name}</h1>
        <div className={styles.attributes}>
          {product.attributes.map(attr => (
            <Attribute key={attr.name} attr={attr} orderItem={item} setItem={(item) => setItem(item)} />
          ))}
        </div>
        <div className={styles.price}>
          <h3>PRICE:</h3>
          {product.prices.map(price => (
            <p key={price.currency.symbol}>
              {price.currency.symbol}
              {price.amount.toFixed(2)}
            </p>
          ))}
        </div>
        <button
          data-testid='add-to-cart'
          disabled={!product.inStock || !configured}
          className={styles.addToCartButton}
          onClick={addToCart}
        >
          ADD TO CART
        </button>
        <div data-testid='product-description' className={styles.description}>{parse(product.description)}</div>
      </div>
    </div>
  );
};

export default ProductDetails;