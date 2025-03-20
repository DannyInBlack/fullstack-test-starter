import React from 'react';
import { Link } from 'react-router-dom';
import styles from './Navbar.module.css';
import Logo from '../assets/Logo.svg';
import Cart from '../assets/Cart.svg';

const Navbar: React.FC = () => {
  return (
    <nav className={styles.navbar}>
      <div className={styles.tabs}>
        <Link to="/women" className={styles.tab}>Women</Link>
        <Link to="/men" className={styles.tab}>Men</Link>
        <Link to="/children" className={styles.tab}>Children</Link>
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