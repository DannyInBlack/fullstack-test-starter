import React from 'react';
import { Link } from 'react-router-dom';
import styles from './Navbar.module.css';
import Logo from '../assets/Logo.svg';
import Cart from '../assets/Cart.svg';
import { gql, useQuery } from '@apollo/client';

const GET_CATEGORIES = gql`
  query Categories {
    categories {
      name
    }
  }
`;

interface Category {
  name: string;
  __typename: string;
}

const Navbar: React.FC = () => {

  const { loading, error, data } = useQuery(GET_CATEGORIES);

  if (error) return <p>Error : {error.message}</p>;

  return (
    <nav className={styles.navbar}>
      <div className={styles.tabs}>
        {!loading && 
          data.categories.map((category : Category) => (
            <Link className={styles.tab} to={`/?category=${category.name}`}>
              {category.name}
            </Link>
          ))}
      </div>
      <div className={styles.logoContainer}>
        <img src={Logo} alt="Logo" className={styles.logo} />
      </div>
      <div className={styles.cartContainer}>
        <button className={styles.button}>
          <img src={Cart} alt="Cart" className={styles.cart} />
        </button>
      </div>
    </nav>
  );
};

export default Navbar;