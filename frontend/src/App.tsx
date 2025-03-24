import './App.css'
import { Route, Routes, useSearchParams } from 'react-router-dom'
import Navbar from './components/Navbar'
import PDP from './pages/ProductDisplay'
import ProductDetails from './pages/ProductDetails'
import { useState } from 'react'
import { gql, useQuery } from '@apollo/client'
import { CartProvider } from './context/CartContext'; // Import CartProvider

const App: React.FC = () => {

  const GET_CATEGORIES = gql`
    query Categories {
      categories {
        name
      }
    }
  `;

  const [searchParams, _setSearchParams] = useSearchParams();
  // TODO: add context for category
  const [category, setCategory] = useState<string | null>(searchParams.get('category'));
  
  const { data } = useQuery(GET_CATEGORIES);

  return (
    <CartProvider>
      <Navbar data={data} category={category} setCategory={(category) => setCategory(category)} />
      <Routes>
        <Route path={`/`} element={<PDP category={category || "all"} setCategory={(category) => setCategory(category)} />} />
        <Route path='/product/:id' element={<ProductDetails />} />
      </Routes>
    </CartProvider>
  )
}

export default App
