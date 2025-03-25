import './App.css'
import { Route, Routes } from 'react-router-dom'
import Navbar from './components/Navbar'
import PDP from './pages/ProductDisplay'
import ProductDetails from './pages/ProductDetails'
import { CartProvider } from './context/CartContext'; // Import CartProvider

const App: React.FC = () => (
  <CartProvider>
    <Navbar />
    <Routes>
      <Route path='/:category' element={<PDP />} />
      <Route path='/' element={<PDP />} />
      <Route path='/product/:id' element={<ProductDetails />} />
    </Routes>
  </CartProvider>
)

export default App
