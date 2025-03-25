import React from 'react';
import { Link, Outlet, useLocation, useNavigate, useParams } from 'react-router-dom';
import styles from './Navbar.module.css';
import Logo from '../assets/Logo.svg';
import Cart from '../assets/Cart.svg';
import { Category } from '../interfaces/Category';
import CartOverlay from './CartOverlay';
import { useCart } from '../context/CartContext';
import { gql, useQuery } from '@apollo/client';


const Navbar: React.FC = () => {
  const { setCartOpen, cartOpen } = useCart();
  const navigate = useNavigate();

  const GET_CATEGORIES = gql(`
     query Categories {
       categories {
         name
       }
     }
  `);

  const { data } = useQuery(GET_CATEGORIES);
  let { category } = useParams();
  let location = useLocation();

  if (data && location.pathname == '/'){
    navigate(`/${data.categories[0].name}`);
  }

  return (
    <>
        <nav className={styles.navbar}>
          <div className={styles.tabs}>
            {data && data.categories &&
              data.categories.map((cat : Category) => (
                <Link 
                  key={cat.name}
                  id={"#"+cat.name+"-tab"} 
                  className={styles.tab}  
                  to={`/${cat.name}`}
                  data-testid={cat.name === category ? "active-category-link" : "category-link"}
                  style={{
                    color: cat.name === category ? "#5ECE7B" : "#000000",
                    borderBottom: cat.name === category ? "2px solid #5ECE7B" : "2px solid #f8f9fa"
                  }}
                  >
                  {cat.name.toUpperCase()}
                </Link>
              ))}
          </div>
          <div className={styles.logoContainer}>
            <img src={Logo} alt="Logo" className={styles.logo} />
          </div>
          <div className={styles.cartContainer}>
            <button 
              data-testid="cart-btn" 
              className={styles.button}
              onClick={() => setCartOpen(!cartOpen)}
              >
              <img src={Cart} alt="Cart" className={styles.cart} />
            </button>
          </div>
          {cartOpen && (
            <CartOverlay />
          )}
        </nav>
      <Outlet />
    </>
  );
};

export default Navbar;